<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allotee;
use App\Models\Bank;
use App\Models\ChargeType;
use App\Models\Month;
use App\Models\PlotCharges;
use App\Models\Sector;
use App\Models\Size;
use App\Models\YearlyBill;
use App\Models\YearlyBillCharge;
use App\Models\YearlyBillTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BillYearlyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:view-bill-yearly'])->only(['showYearly']);
        $this->middleware(['permission:edit-bill-yearly'])->only(['editYearly', 'updateYearly']);
        $this->middleware(['permission:create-bill-yearly'])->only(['indexYearly', 'storeYearly']);
        $this->middleware(['permission:delete-bill-yearly'])->only(['changeStatusYearly', 'deleteYearly']);
    }

        public function showYearly()
    {
        $title = 'Yearly Bill';
        $query = YearlyBill::query();
        $bills = $query->with('size', 'sector', 'allotee')->get();
        return view('backend.bills.yearly.index_yearly', compact('title', 'bills'));
    }

    public function indexYearly()
    {
        $title = 'Yearly Bill';
        $allotees = Allotee::with('size', 'sector')->where('is_active', 1)->get();
        $banks = Bank::all();
        $months = Month::all();
        $charges = PlotCharges::with('charge')->where('charge_type_id' ,1)->get();
        $currentYear = date('Y');
        $startYear = $currentYear - 29;
        $currentMonth = date('m');
        if ($currentMonth > 6) {
            $currentYear++;
            $startYear++;
        }
        $yearArray = [];
        for ($year = $currentYear; $year >= $startYear; $year--) {
            $yearArray[] = $year;
        }
        $years = $yearArray;

//        dd($chargeType);
        return view('backend.bills.yearly.create_yearly',
            compact('title', 'banks', 'allotees', 'months', 'years' ,'charges'));
    }

    public function storeYearly(Request $request)
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

            $billID = $this->billGenerationSetupYearly($request, $allotee);

            DB::commit();
            return redirect()->route('view-bill-yearly', $billID);

        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();

        }
    }

    public function billGenerationSetupYearly($request, $allotee)
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
            'bill_number' => YearlyBill::generateBillNumber(),
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
        $billID = YearlyBill::create($billData);

        foreach ($dataCharges as $billChargesData) {
            $billChargesData['yearly_bill_id'] = $billID->id;
            YearlyBillCharge::create($billChargesData);
        }


        $billTransactionData = array(
            'yearly_bill_id' => $billID->id,
            'total' => $billID->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $billID->total,
            'payment_date' => NULL,
        );

        YearlyBillTransaction::create($billTransactionData);
        return $billID->id;
    }

    public function viewYearly($id)
    {
        $title = 'Yearly Bill';
        $sectors = Sector::all();
        $sizes = Size::all();
        $bill = YearlyBill::with('allotee', 'size', 'sector',
            'billCharges.fromMonth', 'billCharges.toMonth',
            'transaction', 'bank')
            ->find($id);
//        dd($bill);
        return view('backend.bills.yearly.bill_view_yearly', compact(
            'title',
            'sectors', 'sizes',
            'bill'));
    }

    public function editYearly($id)
    {
        $title = 'Yearly Bill';
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
        $bill = YearlyBill::with('allotee', 'billCharges')->find($id);
//        dd($bill->billCharges);
        return view('backend.bills.yearly.edit_yearly',
            compact('title', 'bill', 'banks', 'allotees', 'months', 'years'));
    }

    public function getSectors(Request $request)
    {
        $sector = Sector::find($request->id);
        return response()->json(['responseData' => $sector]);
    }

    public function updateYearly(Request $request, $id)
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

        $bill = YearlyBill::find($id);
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

        YearlyBillCharge::where('yearly_bill_id', $id)->delete();

        foreach ($dataCharges as $billChargesData) {
            $billChargesData['yearly_bill_id'] = $bill->id;
            YearlyBillCharge::create($billChargesData);
        }


        YearlyBillTransaction::where('yearly_bill_id', $id)->delete();
        $billTransactionData = array(
            'yearly_bill_id' => $bill->id,
            'total' => $bill->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $bill->total,
            'payment_date' => NULL,
        );
        YearlyBillTransaction::create($billTransactionData);

        DB::commit();
        return redirect()->route('view-bill-yearly', $id);
    }

    public function getYearly(Request $request)
    {
        $bill = YearlyBill::with('transaction')->find($request->bill_id);
        return response()->json(['responseData' => $bill]);
    }

    public function receiptYearly()
    {
        $title = 'Receipt Yearly Bill';
        $bill = YearlyBill::where('is_active', 1)->where('is_paid', 0)->get();
        return view('backend.bills.yearly.receipt_yearly_bill',
            compact('title', 'bill'));
    }

    public function storeReceiptYearly(Request $request)
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
        $bill = YearlyBill::find($validatedData['bill_id']);
        $bill->is_paid = 1;
        $bill->save();

        $billTransaction = YearlyBillTransaction::where('yearly_bill_id', $validatedData['bill_id'])->first();
        $billTransaction->is_paid = 1;
        $billTransaction->paid_amount = $validatedData['total'];
        $billTransaction->due_amount = 0;
        $billTransaction->payment_date = dateInsert($validatedData['issue_date']);

        $billTransaction->save();

        if (isset($billTransaction)) {

            return redirect()->route('show-bill-yearly')->with('success', 'Bill Receipt Updated Successfully');

        } else {

            return redirect()->route('show-bill-yearly')->with('error', 'Some thing Went Wrong');

        }

    }

}
