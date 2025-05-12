<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allotee;
use App\Models\Bank;
use App\Models\Bill;
use App\Models\Month;
use App\Models\Sector;
use App\Models\Size;
use App\Models\StampDutyBill;
use App\Models\StampDutyBillCharge;
use App\Models\StampDutyBillTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BillStampDutyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:view-bill-stamp-duty'])->only(['showStampDuty']);
        $this->middleware(['permission:edit-bill-stamp-duty'])->only(['editStampDuty', 'updateStampDuty']);
        $this->middleware(['permission:create-bill-stamp-duty'])->only(['indexStampDuty', 'storeStampDuty']);
        $this->middleware(['permission:delete-bill-stamp-duty'])->only(['changeStatusStampDuty', 'deleteStampDuty']);
    }

    public function showStampDuty()
    {
        $title = 'Stamp Duty Bill';
        $query = StampDutyBill::query();
        $bills = $query->with('size', 'sector', 'allotee')->get();
        return view('backend.bills.stampDuty.index_stamp_duty', compact('title', 'bills'));
    }

    public function indexStampDuty()
    {
        $title = 'Stamp Duty Bill';
        $allotees = Allotee::with('size', 'sector')->where('is_active', 1)->get();
        $banks = Bank::all();
        $months = Month::all();
        $currentYear = date('Y');
        $startYear = $currentYear - 29;
        $yearArray = [];
        for ($year = $currentYear; $year >= $startYear; $year--) {
            $yearArray[] = $year;
        }
        $years = $yearArray;

        return view('backend.bills.stampDuty.create_stamp_duty',
            compact('title', 'banks', 'allotees', 'months', 'years'));
    }

    public function storeStampDuty(Request $request)
    {

//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'allotee_id' => 'required|numeric', // Assuming it's a numeric ID
            'bank_id' => 'required|numeric', // Assuming it's a numeric ID
            'from_month' => 'required|array',
            'from_year' => 'required|array',
            'to_month' => 'required|array',
            'to_year' => 'required|array',
            'charge_amount' => 'required|array',
            'issue_date' => 'required|date_format:m/d/Y',
            'due_date' => 'required|date_format:m/d/Y',
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

            $billID = $this->billGenerationSetupStampDuty($request, $allotee);

            DB::commit();
            return redirect()->route('view-bill-stamp-duty', $billID);

        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();

        }
    }

    public function billGenerationSetupStampDuty($request, $allotee)
    {
        if ($request->charge_amount) {
            $dataCharges = [];
            $totalBillAmount = 0;
            $totalBillSubCharges = 0;
            $subBillTotal = 0;
            $totalMarla = $allotee->size->value;
            foreach ($request->charge_amount as $key => $amount) {
                if (isset($request->from_month[$key], $request->from_year[$key], $request->to_month[$key], $request->to_year[$key])) {
                    $fromMonth = intval($request->from_month[$key]);
                    $fromYear = intval($request->from_year[$key]);
                    $toMonth = intval($request->to_month[$key]);
                    $toYear = intval($request->to_year[$key]);
                    $noOfTransfer = intval($request->no_of_transfer[$key]);
                    $chargeAmount = floatval($amount);

                    $totalMonths = ($toYear - $fromYear) * 12 + ($toMonth - $fromMonth) + 1;
                    $totalAmount = $totalMarla * $chargeAmount * $noOfTransfer;

                    $totalBillAmount += $totalAmount;


                    // Calculate sub_charges and sub_total
                    $percentage = getSettingValue('sub_charges');
                    $percentageDecimal = $percentage / 100; // 0.1
                    $subCharges = $totalAmount * $percentageDecimal;
                    $totalBillSubCharges += $subCharges;

                    $subTotal = $totalAmount + $subCharges;
                    $subBillTotal += $subTotal;

                    // Prepare data for database insertion
                    $dataCharges[] = [
                        'from_month' => $fromMonth,
                        'from_year' => $fromYear,
                        'to_month' => $toMonth,
                        'to_year' => $toYear,
                        'total_months' => $totalMonths,
                        'no_of_transfer' => $noOfTransfer,
                        'amount' => $chargeAmount,
                        'total' => $totalAmount,
                    ];
                }
            }

        }

        $billData = array(
            'bill_number' => StampDutyBill::generateBillNumber(),
            'allotee_id' => $allotee->id,
            'bank_id' => $request->bank_id,
            'sector_id' => $allotee->sector->id,
            'size_id' => $allotee->size->id,
            'issue_date' => dateInsert($request->issue_date),
            'due_date' => dateInsert($request->due_date),
            'is_paid' => 0,
            'is_period' => 0,
            'generated_by' => auth()->user()->id,
            'total' => $totalBillAmount,
            'sub_charges' => $totalBillSubCharges,
            'sub_total' => $subBillTotal,
        );

//        dd($billData , $dataCharges);
        $billID = StampDutyBill::create($billData);

        foreach ($dataCharges as $billChargesData) {
            $billChargesData['stamp_duty_bill_id'] = $billID->id;
            StampDutyBillCharge::create($billChargesData);
        }


        $billTransactionData = array(
            'stamp_duty_bill_id' => $billID->id,
            'total' => $billID->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $billID->total,
            'payment_date' => NULL,
        );

        StampDutyBillTransaction::create($billTransactionData);
        return $billID->id;
    }

    public function viewStampDuty($id)
    {
        $title = 'Stamp Duty Bill';
        $sectors = Sector::all();
        $sizes = Size::all();
        $bill = Bill::with('allotee', 'size', 'sector',
            'billCharges.fromMonth', 'billCharges.toMonth',
            'transaction', 'bank')
            ->find($id);
//        dd($bill);
        return view('backend.bills.stampDuty.bill_view_stamp_duty', compact(
            'title',
            'sectors', 'sizes',
            'bill'));
    }

    public function editStampDuty($id)
    {
        $title = 'Stamp Duty Bill';
        $allotees = Allotee::all();
        $banks = Bank::all();
        $months = Month::all();
        $currentYear = date('Y');
        $startYear = $currentYear - 29;
        $yearArray = [];
        for ($year = $currentYear; $year >= $startYear; $year--) {
            $yearArray[] = $year;
        }
        $years = $yearArray;
        $bill = StampDutyBill::with('allotee', 'billCharges')->find($id);
//        dd($bill->billCharges);
        return view('backend.bills.stampDuty.edit_stamp_duty',
            compact('title', 'bill', 'banks', 'allotees', 'months', 'years'));
    }

    public function getSectors(Request $request)
    {
        $sector = Sector::find($request->id);
        return response()->json(['responseData' => $sector]);
    }

    public function updateStampDuty(Request $request, $id)
    {
//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'allotee_id' => 'required|numeric', // Assuming it's a numeric ID
            'bank_id' => 'required|numeric', // Assuming it's a numeric ID
            'from_month' => 'required|array',
            'from_year' => 'required|array',
            'to_month' => 'required|array',
            'to_year' => 'required|array',
            'charge_amount' => 'required|array',
            'issue_date' => 'required|date_format:m/d/Y',
            'due_date' => 'required|date_format:m/d/Y',
        ]);


        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.

        }

        $allotee = Allotee::with('size', 'sector')->find($request->allotee_id);

        $bill = StampDutyBill::find($id);
        $dataCharges = [];

        if ($request->charge_amount) {
            $totalBillAmount = 0;
            $totalBillSubCharges = 0;
            $subBillTotal = 0;
            $totalMarla = $allotee->size->value;

            foreach ($request->charge_amount as $key => $amount) {
                if (isset($request->from_month[$key], $request->from_year[$key], $request->to_month[$key], $request->to_year[$key])) {
                    $fromMonth = intval($request->from_month[$key]);
                    $fromYear = intval($request->from_year[$key]);
                    $toMonth = intval($request->to_month[$key]);
                    $toYear = intval($request->to_year[$key]);
                    $noOfTransfer = intval($request->no_of_transfer[$key]);
                    $chargeAmount = floatval($amount);

                    $totalMonths = ($toYear - $fromYear) * 12 + ($toMonth - $fromMonth) + 1;
                    $totalAmount = $totalMarla * $chargeAmount * $noOfTransfer;
                    $totalBillAmount += $totalAmount;

                    // Calculate sub_charges and sub_total
                    $percentage = getSettingValue('sub_charges');
                    $percentageDecimal = $percentage / 100; // 0.1
                    $subCharges = $totalAmount * $percentageDecimal;
                    $totalBillSubCharges += $subCharges;

                    $subTotal = $totalAmount + $subCharges;
                    $subBillTotal += $subTotal;

                    // Prepare data for database insertion
                    $dataCharges[] = [
                        'from_month' => $fromMonth,
                        'from_year' => $fromYear,
                        'to_month' => $toMonth,
                        'to_year' => $toYear,
                        'total_months' => $totalMonths,
                        'no_of_transfer' => $noOfTransfer,
                        'amount' => $chargeAmount,
                        'total' => $totalAmount,
                    ];
                }
            }

        }


        $billData = array(
            'allotee_id' => $allotee->id,
            'bank_id' => $request->bank_id,
            'sector_id' => $allotee->sector->id,
            'size_id' => $allotee->size->id,
            'issue_date' => dateInsert($request->issue_date),
            'due_date' => dateInsert($request->due_date),
            'total' => $totalBillAmount,
            'sub_charges' => $totalBillSubCharges,
            'sub_total' => $subBillTotal,
        );

//        dd($billData , $dataCharges);
        $bill->update($billData);

        StampDutyBillCharge::where('stamp_duty_bill_id', $id)->delete();

        foreach ($dataCharges as $billChargesData) {
            $billChargesData['stamp_duty_bill_id'] = $bill->id;
            StampDutyBillCharge::create($billChargesData);
        }


        StampDutyBillTransaction::where('stamp_duty_bill_id', $id)->delete();
        $billTransactionData = array(
            'stamp_duty_bill_id' => $bill->id,
            'total' => $bill->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $bill->total,
            'payment_date' => NULL,
        );
        StampDutyBillTransaction::create($billTransactionData);

        DB::commit();
        return redirect()->route('view-bill-stamp-duty', $id);
    }

    public function getStampDuty(Request $request)
    {
        $bill = StampDutyBill::with('transaction')->find($request->bill_id);
        return response()->json(['responseData' => $bill]);
    }

    public function receiptStampDuty()
    {
        $title = 'Receipt Stamp Duty Bill';
        $bill = StampDutyBill::where('is_active', 1)->where('is_paid', 0)->get();
        return view('backend.bills.stampDuty.receipt_stamp_duty_bill',
            compact('title', 'bill'));
    }

    public function storeReceiptStampDuty(Request $request)
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
        $bill = StampDutyBill::find($validatedData['bill_id']);
        $bill->is_paid = 1;
        $bill->save();

        $billTransaction = StampDutyBillTransaction::where('stamp_duty_bill_id', $validatedData['bill_id'])->first();
        $billTransaction->is_paid = 1;
        $billTransaction->paid_amount = $validatedData['total'];
        $billTransaction->due_amount = 0;
        $billTransaction->payment_date = dateInsert($validatedData['issue_date']);

        $billTransaction->save();

        if (isset($billTransaction)) {

            return redirect()->route('show-bill-stamp-duty')->with('success', 'Bill Receipt Updated Successfully');

        } else {

            return redirect()->route('show-bill-stamp-duty')->with('error', 'Some thing Went Wrong');

        }

    }

}
