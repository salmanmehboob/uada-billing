<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allotee;
use App\Models\Bank;
use App\Models\Bill;
use App\Models\BillCharge;
use App\Models\BillTransaction;
use App\Models\Month;
use App\Models\PlotCharges;
use App\Models\Sector;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CombineBillController extends Controller
{

    public function showCombineBill()
    {
        $title = 'Combine Bill';
        $query = Bill::query();
        $bills = $query->with('size', 'sector', 'allotee', 'fromMonth', 'toMonth')
            ->where('is_time_period', 1)->orWhere('is_non_time_period', 1)
            ->get();
        return view('backend.bills.combine_bill_view', compact('title', 'bills'));
    }

    public function addCombineBill()
    {
        $title = 'Combine Bill';
        $sector = Sector::all();
        $size = Size::all();
        $banks = Bank::all();
        $months = Month::all();
        return view('backend.bills.combine_bill_create', compact('title', 'banks',
            'sector', 'months', 'size'));
    }

    public function storeCombineBill(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'sector_id' => 'required',
            'size_id' => 'required',
            'bank_id' => 'required',
            'year' => 'required',
            'from_month' => 'required',
            'to_month' => 'required',
            'issue_date' => 'required',
            'due_date' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.

        }

        DB::beginTransaction();
        try {
            Session::forget('billIDS');

//            dd($request->all());
            $allotees = Allotee::with('size', 'sector')->where(function ($query) use ($request) {
//                if ($request->sector_id > 0 && $request->size_id == 0) {
//
//                    $query->where('sector_id', $request->sector_id);
//
//                } elseif ($request->size_id > 0 && $request->sector_id == 0) {
//
//                    $query->where('size_id', $request->size_id);
//
//                } else {

                $query->where('sector_id', $request->sector_id)
                    ->where('size_id', $request->size_id);
//                }
            })->where('is_active', 1)->get();

//            dd($allotees->count());
            $totalBeforeArrearAmountOfCharges = 0;
            $totalMonths = $request->to_month - $request->from_month + 1;

            $billIDS = array();
            foreach ($allotees as $allotee) {
                $billID = $this->billGenerationSetup($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges);
                 if ($billID !== null) {
                    $billIDS[] = $billID;
                }
            }


//            dd($billIDS);
            Session::put('billIDS', $billIDS);
            DB::commit();
            return redirect()->route('view-combine-bill');

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();

        }
    }

    public function viewCombineBill()
    {
        $title = 'Bill';
        $sectors = Sector::all();
        $sizes = Size::all();

        $billIDS = Session::get('billIDS'); // Retrieve the array from the session
        $billHistories = array();
        $totalArrearAmounts = array();
        $bills = [];
        foreach ($billIDS as $id) {
            $bills[$id] = Bill::with('allotee', 'size', 'sector', 'fromMonth', 'toMonth',
                'billCharges', 'billCharges.PlotCharges.charge', 'transaction', 'bank')
                ->find($id);

            $arrears = Bill::with('fromMonth', 'toMonth', 'transaction')
                ->where('id', '=', $id);

            $billHistories[$id] = $arrears->get();
            $totalArrearAmounts[$id] = $arrears->where('is_paid', '=', 0)
                ->sum('total');


        }
//        dd($bills);


        return view('backend.bills.bill_view_combine', compact('title', 'sectors', 'sizes',
            'bills', 'billHistories', 'totalArrearAmounts'));
    }

    public function billGenerationSetup($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges)
    {
        $totalArrears = 0;
        $totalAmountOfCharges = 0;
        $subCharges = 0;
        $subTotal = 0;
        $applyCharges = array();
        $responseID = null;
        if ($request->charges) {

            foreach ($request->charges as $chargeID) {

                $charges = PlotCharges::where('size_id', $allotee->size->id)
                    ->where('year', $request->year)
                    ->where('id', $chargeID)->first();


                if ($charges) {
                    if ($charges->is_period == 1) {

                        $totalMonthCharges = $charges->amount * $totalMonths;
                    } else {
                        $totalMonthCharges = $charges->amount;

                    }


                    $totalBeforeArrearAmountOfCharges += $totalMonthCharges;

                    $percentage = getSettingValue('sub_charges');
                    $percentageDecimal = $percentage / 100;
                    if ($allotee->arrears > 0) {
                        $totalArrears = ($allotee->arrears ?? 0);
                    } else {
                        $arrears = Bill::with('fromMonth', 'toMonth', 'transaction')
                            ->where('is_paid', '=', 0)
                            ->where('is_period', '=', 1)
                            ->where('is_active', '=', 1)
                            ->where('allotee_id', '=', $allotee->id)
                            ->get();
                        $totalArrears = $arrears->sum('sub_total');
                    }
                    $totalAmountOfCharges = $totalBeforeArrearAmountOfCharges + $totalArrears;
                    $subCharges = ($totalAmountOfCharges * $percentageDecimal);
                    $subTotal = $totalAmountOfCharges + $subCharges;


                    $applyCharges[] = array(
                        'plot_charge_id' => $charges->id,
                        'amount' => $totalMonthCharges
                    );
                }
            }
        }

        if (isset($applyCharges) && !empty($applyCharges)) {
            Bill::where('is_period', '=', 1)
                ->where('allotee_id', '=', $allotee->id)->update(['is_active' => 0]);

            $billData = array(
                'bill_number' => Bill::generateBillNumber(),
                'allotee_id' => $allotee->id,
                'bank_id' => $request->bank_id,
                'sector_id' => $allotee->sector->id,
                'size_id' => $allotee->size->id,
                'year' => $request->year,
                'from_month' => $request->from_month,
                'to_month' => $request->to_month,
                'total_months' => $totalMonths,
                'issue_date' => dateInsert($request->issue_date),
                'due_date' => dateInsert($request->due_date),
                'is_paid' => 0,
                'is_installment' => 0,
                'is_period' => 1,
                'generated_by' => auth()->user()->id,
                'bill_total' => $totalBeforeArrearAmountOfCharges,
                'arrears' => $totalArrears,
                'total' => $totalAmountOfCharges,
                'sub_charges' => $subCharges,
                'sub_total' => $subTotal,
                'is_time_period' => true,
            );

            $billResponse = Bill::create($billData);

            foreach ($applyCharges as $row) {
                $billCharges = new BillCharge();
                $billChargesData = array(
                    'bill_id' => $billResponse->id,
                    'plot_charge_id' => $row['plot_charge_id'],
                    'total' => $row['amount']
                );
                $billCharges->create($billChargesData);
            }

            $billTransactionData = array(
                'bill_id' => $billResponse->id,
                'total' => $billResponse->total,
                'is_paid' => 0,
                'paid_amount' => 0,
                'due_amount' => $billResponse->total,
                'payment_date' => NULL,

            );

            BillTransaction::create($billTransactionData);
            $allotee->arrears = 0;
            $allotee->save();
            $responseID = $billResponse->id;

            return $responseID;
        }

    }


}
