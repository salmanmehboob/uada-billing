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
use App\Models\ViolationBill;
use App\Models\ViolationBillCharge;
use App\Models\ViolationBillTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BillViolationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:view-bill-violation'])->only(['showViolation']);
        $this->middleware(['permission:edit-bill-violation'])->only(['editViolation', 'updateViolation']);
        $this->middleware(['permission:create-bill-violation'])->only(['indexViolation', 'storeViolation']);
        $this->middleware(['permission:delete-bill-violation'])->only(['changeStatusViolation', 'deleteViolation']);
    }

    public function showViolation()
    {
        $title = 'Violation Bill';
        $query = ViolationBill::query();
        $bills = $query->with('size', 'sector', 'allotee', 'fromMonth', 'toMonth')->where('is_period', '=', 0)->get();
        return view('backend.bills.violation.index_violation', compact('title', 'bills'));
    }

    public function indexViolation()
    {
        $title = 'Violation Bill';
        $allotees = Allotee::with('size','sector')->where('is_active', 1)->get();
        $banks = Bank::all();
        $months = Month::all();
        return view('backend.bills.violation.create_violation', compact('title', 'banks', 'allotees', 'months'));
    }

    public function storeViolation(Request $request)
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

//            dd($request->all());
            $allotee = Allotee::with('size', 'sector')->find($request->allotee_id);

            $billID = $this->billGenerationSetupViolation($request, $allotee);

            DB::commit();
            return redirect()->route('view-bill-violation', $billID);

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();

        }
    }

    public function viewViolation($id)
    {
//        dd($id);
        $title = 'Violation Bill';
        $sectors = Sector::all();
        $sizes = Size::all();
        $bill = Bill::with('allotee', 'size', 'sector', 'fromMonth', 'toMonth',
            'billCharges', 'billCharges.PlotCharges.charge', 'transaction', 'bank')
            ->find($id);

//        dd($bill);
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
        return view('backend.bills.violation.bill_view_violataion', compact('title', 'sectors', 'sizes',
            'bill', 'billHistory', 'totalArrearAmount'));
    }

    public function editViolation($id)
    {
        $title = 'Violation Bill';
        $allotees = Allotee::all();
        $banks = Bank::all();

        $bill = ViolationBill::with('allotee', 'billCharges')->find($id);
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
                'amount' => $ch->amount,
                'selected' => $chargeExistsInBill,
            ];
        }

//        dd($bill->billCharges);
        return view('backend.bills.violation.edit_violation', compact('title', 'chargesDetailArray',
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

    public function updateViolation(Request $request, $id)
    {
//        dd($request->all());
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


        $bill = ViolationBill::find($id);

         if ($request->charges) {
            foreach ($request->charges as $key => $chargeID) {
                $charges = PlotCharges::where('size_id', $allotee->size->id)
                    ->where('year', $request->year)
                    ->where('id', $chargeID)->first();

                if ($charges) {

                    $totalMonthCharges = $request->total_amount[$key];
                    $totalViolation = $request->total_sft[$key];
                    $chargeAmount = $request->charge_amount[$key];


                    $totalBeforeArrearAmountOfCharges += $totalMonthCharges;

                    $percentage = getSettingValue('sub_charges');
                    $percentageDecimal = $percentage / 100;
                    $totalArrears = 0;

                    $totalAmountOfCharges = $totalBeforeArrearAmountOfCharges + $totalArrears;
                    $subCharges = ($totalAmountOfCharges * $percentageDecimal);
                    $subTotal = $totalAmountOfCharges + $subCharges;

                    $applyCharges[] = array(
                        'plot_charge_id' => $charges->id,
                        'charge_amount' => $chargeAmount,
                        'total_violation' => $totalViolation,
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


        ViolationBillCharge::where('violation_bill_id', $id)->delete();
        foreach ($applyCharges as $row) {
            $billCharges = new ViolationBillCharge();
            $billChargesData = array(
                'violation_bill_id' => $id,
                'plot_charge_id' => $row['plot_charge_id'],
                'amount' => $row['charge_amount'],
                'total_violation' => $row['total_violation'],
                'total' => $row['amount'],
                'issue_date' => dateInsert($request->issue_date),
                'due_date' => dateInsert($request->due_date),
            );

            $billCharges->create($billChargesData);
        }

        ViolationBillTransaction::where('violation_bill_id', $id)->delete();

        $billTransactionData = array(
            'violation_bill_id' => $id,
            'total' => $bill->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $bill->total,
            'payment_date' => NULL,

        );

        ViolationBillTransaction::create($billTransactionData);

        DB::commit();
        return redirect()->route('view-bill-violation', $id);

    }

    public function billGenerationSetupViolation($request, $allotee)
    {
        $totalBeforeArrearAmountOfCharges = 0;
        if ($request->charges) {
            foreach ($request->charges as $key => $chargeID) {
                $charges = PlotCharges::where('size_id', $allotee->size->id)
                    ->where('year', $request->year)
                    ->where('id', $chargeID)->first();
                if ($charges) {

                    $totalMonthCharges = $request->total_amount[$key];
                    $totalViolation = $request->total_sft[$key];
                    $chargeAmount = $request->charge_amount[$key];


                    $totalBeforeArrearAmountOfCharges += $totalMonthCharges;

                    $percentage = getSettingValue('sub_charges');
                    $percentageDecimal = $percentage / 100;
                    $totalArrears = 0;

                    $totalAmountOfCharges = $totalBeforeArrearAmountOfCharges + $totalArrears;
                    $subCharges = ($totalAmountOfCharges * $percentageDecimal);
                    $subTotal = $totalAmountOfCharges + $subCharges;

                    $applyCharges[] = array(
                        'plot_charge_id' => $charges->id,
                        'charge_amount' => $chargeAmount,
                        'total_violation' => $totalViolation,
                        'amount' => $totalMonthCharges
                    );
                }
            }
        }

         $billData = array(
            'bill_number' => ViolationBill::generateBillNumber(),
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

//        dd($billData);
        ViolationBill::where('is_period', '=', 0)
            ->where('allotee_id', '=', $allotee->id)->update(['is_active' => 0]);

        $billID = ViolationBill::create($billData);
        foreach ($applyCharges as $row) {
            $billCharges = new ViolationBillCharge();
            $billChargesData = array(
                'violation_bill_id' => $billID->id,
                'plot_charge_id' => $row['plot_charge_id'],
                'amount' => $row['charge_amount'],
                'total_violation' => $row['total_violation'],
                'total' => $row['amount'],
                'issue_date' => dateInsert($request->issue_date),
                'due_date' => dateInsert($request->due_date),
            );

            $billCharges->create($billChargesData);
        }


        $billTransactionData = array(
            'violation_bill_id' => $billID->id,
            'total' => $billID->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $billID->total,
            'payment_date' => NULL,

        );

        ViolationBillTransaction::create($billTransactionData);
        return $billID->id;
    }

    public function checkDuplicateBill(Request $request)
    {



        $message = NULL;
        $status = true;
        $year = $request->input('year');
        $from_month = $request->input('from_month');
        $to_month = $request->input('to_month');
        $allotee = Allotee::find($request->input('allotee'));

        $chargesID = $request->checkedCheckboxes;


        if (isset($from_month) || isset($to_month)) {

            $checkDuplicate = ViolationBill::with('fromMonth', 'toMonth', 'billCharges')
                ->where('year', $year)
                ->where('allotee_id', $allotee->id)
                ->whereHas('billCharges', function ($query) use ($chargesID) {
                    $query->whereIn('plot_charge_id', $chargesID);
                })
                ->where(function ($query) use ($from_month, $to_month) {
                    // Check for overlapping ranges when both $from_month and $to_month are provided
                    if ($from_month !== null && $to_month !== null) {
                        $query->whereBetween('from_month', [$from_month, $to_month])
                            ->orWhereBetween('to_month', [$from_month, $to_month])
                            ->orWhere(function ($query) use ($from_month, $to_month) {
                                $query->where('from_month', '<', $from_month)
                                    ->where('to_month', '>', $to_month);
                            });
                    } // Check for overlapping ranges with $from_month only
                    elseif ($from_month !== null) {
                        $query->where(function ($query) use ($from_month) {
                            $query->whereBetween('from_month', [$from_month, 12])
                                ->orWhereBetween('to_month', [$from_month, 12])
                                ->orWhere(function ($query) use ($from_month) {
                                    $query->where('from_month', '<', $from_month)
                                        ->where('to_month', '>', 12);
                                });
                        });
                    } // Check for overlapping ranges with $to_month only
                    elseif ($to_month !== null) {
                        $query->where(function ($query) use ($to_month) {
                            $query->whereBetween('from_month', [1, $to_month])
                                ->orWhereBetween('to_month', [1, $to_month])
                                ->orWhere(function ($query) use ($to_month) {
                                    $query->where('from_month', '<', 1)
                                        ->where('to_month', '>', $to_month);
                                });
                        });
                    }
                });

//            dd($checkDuplicate);
            if ($checkDuplicate->exists()) {
                $checkDuplicateData = $checkDuplicate->first();
//            dd($checkDuplicateData);
                $message = 'The Selected Charges  has already generated in bill from  ' .
                    $checkDuplicateData->fromMonth->name . ' to  ' . $checkDuplicateData->toMonth->name;
                $status = false;
            }
        } else {
            $checkDuplicate = ViolationBill::with('fromMonth', 'toMonth', 'billCharges')
                ->where('year', $year)
                ->where('size_id', $allotee->id)
                ->whereHas('billCharges', function ($query) use ($chargesID) {
                    $query->where('plot_charge_id', $chargesID);
                });
            if ($checkDuplicate->exists()) {
                $checkDuplicateData = $checkDuplicate->first();

//                dd($checkDuplicateData);
                $message = 'The Selected Charges has already generated in bill ' . $checkDuplicateData->bill_number;
                $status = false;
            }
        }


        return response()->json(['message' => $message, 'status' => $status], 200);
    }

    public function getViolation(Request $request)
    {
        $bill = ViolationBill::with('transaction')->find($request->bill_id);
        return response()->json(['responseData' => $bill]);
    }

    public function receiptViolation()
    {
        $title = 'Receipt Violtion Bill';
        $bill = ViolationBill::where('is_active', 1)->where('is_paid', 0)->get();
        return view('backend.bills.violation.receipt_violation_bill',
            compact('title', 'bill'));
    }

    public function storeReceiptViolation(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'bill_id' => 'required',
            'total' => 'required',
            'issue_date' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.

        }

        $validatedData = $validator->validated();
        $bill = ViolationBill::find($validatedData['bill_id']);
        $bill->is_paid = 1;
        $bill->save();

        $billTransaction = ViolationBillTransaction::where('violation_bill_id', $validatedData['bill_id'])->first();
        $billTransaction->is_paid = 1;
        $billTransaction->paid_amount = $validatedData['total'];
        $billTransaction->due_amount = 0;
        $billTransaction->payment_date = dateInsert($validatedData['issue_date']);

        $billTransaction->save();

        if (isset($billTransaction)) {

            return redirect()->route('show-bill-violation')->with('success', 'Bill Receipt Updated Successfully');

        } else {

            return redirect()->route('show-bill-violation')->with('error', 'Some thing Went Wrong');

        }

    }

}
