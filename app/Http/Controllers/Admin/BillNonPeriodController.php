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
use Illuminate\Support\Facades\Validator;

class BillNonPeriodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:view-bill-non-period'])->only(['showNonPeriod']);
        $this->middleware(['permission:edit-bill-non-period'])->only(['editNonPeriod', 'updateNonPeriod']);
        $this->middleware(['permission:create-bill-non-period'])->only(['indexNonPeriod', 'storeNonPeriod']);
        $this->middleware(['permission:delete-bill-non-period'])->only(['changeStatusNonPeriod', 'deleteNonPeriod']);
    }

    public function showNonPeriod()
    {
        $title = 'Bill';
        $query = Bill::query();
        $bills = $query->with('size', 'sector', 'allotee', 'fromMonth', 'toMonth')->where('is_period', '=', 0)->get();
        return view('backend.bills.index_non_period', compact('title', 'bills'));
    }

    public function indexNonPeriod()
    {
        $title = 'Bill';
        $allotees = Allotee::with('size','sector')->where('is_active', 1)->get();
        $banks = Bank::all();
        $months = Month::all();
        return view('backend.bills.create_non_period', compact('title', 'banks', 'allotees', 'months'));
    }

    public function storeNonPeriod(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'allotee_id' => 'required',
            'bank_id' => 'required',
            'year' => 'required',
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
            $validatedData = $validator->validated();

            $allotee = Allotee::with('size', 'sector')->find($request->allotee_id);

            $billID = $this->billGenerationSetupNonPeriod($request, $allotee);

            DB::commit();
            return redirect()->route('view-bill-non-period', $billID);

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();

        }
    }

    public function viewNonPeriod($id)
    {
//        dd($id);
        $title = 'Bill';
        $sectors = Sector::all();
        $sizes = Size::all();
        $bill = Bill::with('allotee', 'size', 'sector', 'fromMonth', 'toMonth',
            'billCharges', 'billCharges.PlotCharges.charge', 'transaction', 'bank')
            ->find($id);

        $arrears = Bill::with('fromMonth', 'toMonth', 'transaction')
            ->where('is_period', '=', 0)
            ->where('id', '<', $id)
            ->where('allotee_id', '=', $bill->allotee_id);
        $billHistory = $arrears->get();
        $totalArrearAmount = $arrears
            ->where('is_paid', '=', 0)
            ->sum('total');


//        dd(showMonth($bill->toMonth);
//        dd($billHistory);
        return view('backend.bills.bill_view_non_period', compact('title', 'sectors', 'sizes',
            'bill', 'billHistory', 'totalArrearAmount'));
    }

    public function editNonPeriod($id)
    {
        $title = 'Bill';
        $allotees = Allotee::all();
        $banks = Bank::all();

        $bill = Bill::with('allotee', 'billCharges')->find($id);
//        dd($bill->billCharges);
        $plotCharges = PlotCharges::with('charge')
            ->where('year', $bill->year)
            ->where('size_id', $bill->allotee->size_id)
            ->where('is_period', 0)
            ->get();

        $chargesDetailArray = [];
        foreach ($plotCharges as $ch) {

            $chargeExistsInBill = $bill->billCharges->contains(function ($billCharge) use ($ch) {
                return $billCharge->plot_charge_id === $ch->id;
            });
            $chargesDetailArray[] = [
                'id' => $ch->id,
                'name' => $ch->charge->name,
                'selected' => $chargeExistsInBill,
            ];
        }
        return view('backend.bills.edit_non_period', compact('title', 'chargesDetailArray',
            'banks', 'allotees', 'bill'));
    }

    public function getSectors(Request $request)
    {
        $sector = Sector::find($request->id);
        return response()->json(['responseData' => $sector]);
    }

    public function getBills(Request $request)
    {
        $bill = Bill::with('transaction')->find($request->bill_id);
        return response()->json(['responseData' => $bill]);
    }

    public function updateNonPeriod(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'allotee_id' => 'required',
            'bank_id' => 'required',
            'year' => 'required',
            'issue_date' => 'required',
            'due_date' => 'required',
        ]);


        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.

        }

        $validatedData = $validator->validated();

        $allotee = Allotee::with('size', 'sector')->find($request->allotee_id);
        $totalBeforeArrearAmountOfCharges = 0;


        $bill = Bill::find($id);
        if ($request->charges) {
            foreach ($request->charges as $chargeID) {
                $charges = PlotCharges::where('size_id', $allotee->size->id)
                    ->where('year', $request->year)
                    ->where('id', $chargeID)->first();

                if ($charges) {

                    $totalMonthCharges = $charges->amount;

                    $totalBeforeArrearAmountOfCharges += $totalMonthCharges;

                    $percentage = getSettingValue('sub_charges');
                    $percentageDecimal = $percentage / 100;

                    $totalArrears = 0;

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

        $billData = array(
            'allotee_id' => $allotee->id,
            'bank_id' => $request->bank_id,
            'sector_id' => $allotee->sector->id,
            'size_id' => $allotee->size->id,
            'year' => $request->year,
            'from_month' => NULL,
            'to_month' => $request->to_month,
            'total_months' => 0,
            'issue_date' => dateInsert($request->issue_date),
            'due_date' => dateInsert($request->due_date),
            'is_paid' => 0,
            'is_installment' => 0,
            'generated_by' => auth()->user()->id,
            'bill_total' => $totalBeforeArrearAmountOfCharges,
            'arrears' => $totalArrears,
            'total' => $totalAmountOfCharges,
            'sub_charges' => $subCharges,
            'sub_total' => $subTotal,
        );

        $bill->update($billData);
//        dd($bill);

        BillCharge::where('bill_id', $id)->delete();
        foreach ($applyCharges as $row) {
            $billCharges = new BillCharge();
            $billChargesData = array(
                'bill_id' => $id,
                'plot_charge_id' => $row['plot_charge_id'],
                'total' => $row['amount'],
                'issue_date' => dateInsert($request->issue_date),
                'due_date' => dateInsert($request->due_date),
            );

            $billCharges->create($billChargesData);
        }

        BillTransaction::where('bill_id', $id)->delete();

        $billTransactionData = array(
            'bill_id' => $id,
            'total' => $bill->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $bill->total,
            'payment_date' => NULL,

        );

        BillTransaction::create($billTransactionData);
//        $billID = $this->billGenerationSetup($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges);

        DB::commit();
        return redirect()->route('view-bill-non-period', $id);

    }

    public function billGenerationSetupNonPeriod($request, $allotee)
    {
        $totalBeforeArrearAmountOfCharges = 0;
        if ($request->charges) {
            foreach ($request->charges as $chargeID) {
                $charges = PlotCharges::where('size_id', $allotee->size->id)
                    ->where('year', $request->year)
                    ->where('id', $chargeID)->first();
                if ($charges) {

                    $totalMonthCharges = $charges->amount;


                    $totalBeforeArrearAmountOfCharges += $totalMonthCharges;

                    $percentage = getSettingValue('sub_charges');
                    $percentageDecimal = $percentage / 100;
                    $totalArrears = 0;

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

        $billData = array(
            'bill_number' => Bill::generateBillNumber(),
            'allotee_id' => $allotee->id,
            'bank_id' => $request->bank_id,
            'sector_id' => $allotee->sector->id,
            'size_id' => $allotee->size->id,
            'year' => $request->year,
            'from_month' => NULL,
            'to_month' => NULL,
            'total_months' => 0,
            'issue_date' => dateInsert($request->issue_date),
            'due_date' => dateInsert($request->due_date),
            'is_paid' => 0,
            'is_installment' => 0,
            'is_period' => 0,
            'generated_by' => auth()->user()->id,
            'bill_total' => $totalBeforeArrearAmountOfCharges,
            'arrears' => $totalArrears,
            'total' => $totalAmountOfCharges,
            'sub_charges' => $subCharges,
            'sub_total' => $subTotal,

        );

        Bill::where('is_period', '=', 0)
            ->where('allotee_id', '=', $allotee->id)->update(['is_active' => 0]);

        $billID = Bill::create($billData);
        foreach ($applyCharges as $row) {
            $billCharges = new BillCharge();
            $billChargesData = array(
                'bill_id' => $billID->id,
                'plot_charge_id' => $row['plot_charge_id'],
                'total' => $row['amount'],
                'issue_date' => dateInsert($request->issue_date),
                'due_date' => dateInsert($request->due_date),
            );

            $billCharges->create($billChargesData);
        }


        $billTransactionData = array(
            'bill_id' => $billID->id,
            'total' => $billID->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $billID->total,
            'payment_date' => NULL,

        );

        BillTransaction::create($billTransactionData);
        $allotee->arrears = 0;
        $allotee->save();
        return $billID->id;
    }

}
