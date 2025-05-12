<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allotee;
use App\Models\Bank;
use App\Models\Bill;
use App\Models\BillCharge;
use App\Models\BillTransaction;
use App\Models\InternPerformance;
use App\Models\Month;
use App\Models\NonUserBill;
use App\Models\PlotCharges;
use App\Models\Sector;
use App\Models\Size;
use App\Models\StampDutyBill;
use App\Models\ViolationBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BillController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:view-bill'])->only(['show', 'showCombineBill', 'viewCombineBill']);
        $this->middleware(['permission:edit-bill'])->only(['edit', 'update']);
        $this->middleware(['permission:create-bill'])->only(['index', 'store', 'addCombineBill', 'storeCombineBill']);
        $this->middleware(['permission:delete-bill'])->only(['changeStatus', 'delete']);
    }

    public function show(Request $request)
    {
        $title = 'All Bills';
        if ($request->ajax()) {
            $bills = Bill::with('size', 'sector', 'allotee', 'fromMonth', 'toMonth', 'transaction');

            return Datatables::of($bills)
                ->filter(function ($instance) use ($request) {
                    $searchTerm = $request->get('search');
                    $instance->where(function ($query) use ($searchTerm) {

                        $query->whereHas('allotee', function ($query) use ($searchTerm) {
                            $query->where('plot_no', 'like', '%' . $searchTerm . '%');
                        })
                            ->orWhereHas('allotee', function ($query) use ($searchTerm) {
                                $query->where('name', 'like', '%' . $searchTerm . '%');
                            })
                            ->orWhereHas('sector', function ($query) use ($searchTerm) {
                                $query->where('name', 'like', '%' . $searchTerm . '%');
                            })
                            ->orWhereHas('size', function ($query) use ($searchTerm) {
                                $query->where('name', 'like', '%' . $searchTerm . '%');
                            })
                        ->orWhere('bill_number', 'like', '%' . $searchTerm . '%'); //search by bill number
                    });

                    if (!empty($request->get('sector_id'))) {
                        $instance->where('sector_id', $request->get('sector_id'));
                    }
                    if (!empty($request->get('size_id'))) {
                        $instance->where('size_id', $request->get('size_id'));
                    }

                })
                ->addColumn('checkBill', function ($bill) {

                        return '<input type="checkbox" class="form-check-right checkBill" name="checkBill[' . $bill->id . ']" id="" value="1">';

                })
                ->addColumn('billType', function ($bill) {
                    return showBillTypeStatus($bill);
                })
                ->addColumn('bill_number', function ($bill) {
                    return $bill->bill_number;
                })
                ->addColumn('name', function ($bill) {
                    // Concatenate the parts of the name, handling null values properly
                    return ($bill->allotee->name ?? '') . ' ' .
                        ($bill->allotee->plot_no ?? '') . ' ' .
                        ($bill->sector->name ?? '') . ' ' .
                        ($bill->size->name ?? '');
                })
                ->addColumn('year', function ($bill) {
                    return $bill->year;
                })
                ->addColumn('duration', function ($bill) {
                    return $bill->fromMonth->name . '-' . $bill->toMonth->name;
                })
                ->addColumn('total', function ($bill) {
                    return $bill->total ?? '';
                })->addColumn('sub_charges', function ($bill) {
                    return $bill->sub_charges ?? '';
                })
                ->addColumn('sub_total', function ($bill) {
                    return $bill->sub_total ?? '';
                })
                ->addColumn('due_amount', function ($bill) {
                    return $bill->transaction->due_amount ?? '';
                }) ->addColumn('is_active', function ($bill) {
                    if (isset($bill->is_active) &&  $bill->is_active == 1) {
                        return '<span class="badge badge-success">Active</span>';
                    } else {
                        return '<span class="badge badge-warning">Arrear Adjusted</span>';
                    }

                 })
                ->addColumn('status', function ($bill) {
                    if (isset($bill->transaction->due_amount) && $bill->transaction->due_amount > 0 && $bill->is_paid == 1) {
                        return '<span class="badge badge-warning">Partially Paid</span>';
                    } else {
                        return showBooleanStatus($bill->is_paid);
                    }
                })
                ->addColumn('action', function ($bill) {
                    $editUrl = route('edit-bill', $bill->id);
                    $deleteUrl = route('delete-bill');
                    $editBtn = '';
                    $deleteBtn = '';
                    $viewBtn = '';
                    //$bill->is_paid == 0 &&
//                    if ($bill->is_active == 1) {
                        $deleteBtn = '<a href="javascript:void(0)" data-url="' . $deleteUrl . '"
                                           data-id="' . $bill->id . '"
                                           class="text-danger delete-record "
                                           title="Delete Record"><i class="fas fa-trash"></i></a>';
//                    }
//                    $bill->is_paid == 0 &&
                    if ($bill->is_active == 1 || auth()->user()->id == 1) {
                        $editBtn = ' <a title="Edit" href="' . $editUrl . '"
                                       class="text-primary mr-1"><i class="fas fa-edit"></i></a>';
                    }


                    if ($bill->is_time_period) {
                        $viewBtn = '<a title="View" href="' . route('view-bill', $bill->id) . '" class="text-success mr-1"><i class="fas fa-eye"></i></a>';
                    } elseif ($bill->is_non_time_period) {
                        $viewBtn = '<a title="View" href="' . route('view-bill-non-period', $bill->id) . '" class="text-success mr-1"><i class="fas fa-eye"></i></a>';
                    } elseif ($bill->is_violation) {
                        $viewBtn = '<a title="View" href="' . route('view-bill-violation', $bill->id) . '" class="text-success mr-1"><i class="fas fa-eye"></i></a>';
                    } elseif ($bill->is_non_user) {
                        $viewBtn = '<a title="View" href="' . route('view-bill-non-period', $bill->id) . '" class="text-success mr-1"><i class="fas fa-eye"></i></a>';
                    } elseif ($bill->is_stamp_duty) {
                        $viewBtn = '<a title="View" href="' . route('view-bill-stamp-duty', $bill->id) . '" class="text-success mr-1"><i class="fas fa-eye"></i></a>';
                    } elseif ($bill->is_transfer) {
                        $viewBtn = '<a title="View" href="' . route('view-transfer-bill', $bill->id) . '" class="text-success mr-1"><i class="fas fa-eye"></i></a>';
                    } elseif ($bill->is_possession) {
                        $viewBtn = '<a title="View" href="' . route('view-possession-bill', $bill->id) . '" class="text-success mr-1"><i class="fas fa-eye"></i></a>';
                    }

                    return $viewBtn . $editBtn . $deleteBtn;
                })
                ->rawColumns(['action', 'status', 'billType','checkBill' ,'is_active'])
                ->make(true);
        }
        $sector = Sector::all();
        $size = Size::all();
        return view('backend.bills.index', compact('title','sector','size'));
    }


    public function index()
    {
        $title = 'Bill';
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
        return view('backend.bills.create', compact('title', 'banks',
            'allotees', 'months', 'years'));
    }

    public function store(Request $request)
    {

//        dd($request->all());
        $redirectRoute = '';

        $validator = Validator::make($request->all(), [
            'bill_type_id' => 'required',
            'allotee_id' => 'required',
            'bank_id' => 'required',
            'year' => 'required_if:bill_type_id,p,np,v',
            'issue_date' => 'required',
            'due_date' => 'required',
            'from_month' => ['required_if:bill_type_id,p'],
            'to_month' => ['required_if:bill_type_id,p'],
            'from_month.*' => 'required_if:bill_type_id,nu',
            'from_year.*' => 'required_if:bill_type_id,nu',
            'to_month.*' => 'required_if:bill_type_id,nu',
            'to_year.*' => 'required_if:bill_type_id,nu',
        ]);


        if ($validator->fails()) {

//            dd($validator->getMessageBag()->toArray());
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.

        }


        DB::beginTransaction();
        try {

            //            $validatedData = $validator->validated();
            $billID = NULL;
            $allotee = Allotee::with('size', 'sector')->find($request->allotee_id);
            $totalBeforeArrearAmountOfCharges = 0;
            $totalMonths = NULL;

            $billType = $request->bill_type_id;
            if ($billType == 'p') {
                $billID = $this->billGenerationTimePeriodSetup($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType);
                $redirectRoute = 'view-bill';
            }

            if ($billType == 'np') {
                $billID = $this->billGenerationNonTimePeriodSetup($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType);
                $redirectRoute = 'view-bill-non-period';
            }

            if ($billType == 'v') {
                $billID = $this->billGenerationSetupViolation($request, $allotee, $billType);
                $redirectRoute = 'view-bill-violation';
            }

            if ($billType == 'nu') {
                $billID = $this->billGenerationSetupNonUser($request, $allotee, $billType);
                $redirectRoute = 'view-bill-non-user';
            }

            if ($billType == 'sd') {

//                dd($request->all());
                $billID = $this->billGenerationSetupStampDuty($request, $allotee);
                $redirectRoute = 'view-bill-stamp-duty';
            }
            if ($billType == 'tr') {

//                dd($request->all());
                $billID = $this->billGenerationTransferSetup($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType);

                $redirectRoute = 'view-transfer-bill';
            }

            if ($billType == 'ps') {
                $billID = $this->billGenerationPossessionSetup($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType);
                $redirectRoute = 'view-possession-bill';
            }

            DB::commit();

            return redirect()->route($redirectRoute, $billID);

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();

        }
    }


    public function view($id)
    {
        $title = 'Bill';
        $sectors = Sector::all();
        $sizes = Size::all();
        $bill = Bill::with('allotee', 'size', 'sector', 'fromMonth', 'toMonth',
            'billCharges', 'billCharges.PlotCharges.charge', 'transaction', 'bank')
            ->find($id);

        $arrears = Bill::with('fromMonth', 'toMonth', 'transaction')
            ->where('id', '<', $id)
            ->where('is_period', '=', 1)
            ->where('allotee_id', '=', $bill->allotee_id);
        $billHistory = $arrears->get();
        $totalArrearAmount = $arrears->where('is_paid', '=', 0)
            ->sum('total');

        return view('backend.bills.bill_view', compact('title', 'sectors', 'sizes',
            'bill', 'billHistory', 'totalArrearAmount'));
    }


    public function edit($id)
    {
        $title = 'Bill';
        $allotees = Allotee::all();
        $banks = Bank::all();
        $months = Month::all();

        $bill = Bill::with('allotee', 'billCharges')->find($id);

        $plotChargesQuery = PlotCharges::with('charge')
            ->where('year', $bill->year)
            ->where('size_id', $bill->allotee->size_id);

        if ($bill->is_time_period) {
            $plotChargesQuery->where('is_period', '=', 1);
        } elseif ($bill->is_transfer) {
            $plotChargesQuery->whereHas('charge', function ($query) {
                $query->where('is_transfer', 1);
            });

        } elseif ($bill->is_possession) {
            $plotChargesQuery->whereHas('charge', function ($query) {
                $query->where('is_possession', 1);
            });

        } elseif ($bill->is_violation) {
            $plotChargesQuery->where('is_period', 0)
                ->where('is_open', 1);


        } else {
            $plotChargesQuery->where('is_period', '=', 0);
        }

        $plotCharges = $plotChargesQuery->get();


//        dd($bill,$plotCharges );
        $chargesDetailArray = [];

        foreach ($plotCharges as $ch) {
            $billCharges = $bill->billCharges->where('plot_charge_id', $ch->id)->first();
            $chargeExistsInBill = $bill->billCharges->contains('plot_charge_id', $ch->id);

            $chargesDetailArray[$ch->id] = [
                'id' => $ch->id,
                'name' => $ch->charge->name,
                'amount' => $ch->amount,
                'is_reference' => $ch->charge->is_reference,
                'reference_id' => $billCharges->reference_bill_id ?? '',
                'selected' => $chargeExistsInBill,
            ];
        }

        $currentYear = date('Y');
        $startYear = $currentYear - 29;
        $yearArray = [];
        for ($year = $currentYear; $year >= $startYear; $year--) {
            $yearArray[] = $year;
        }
        $years = $yearArray;
        $billCharges = [];
        foreach ($bill->billCharges as $billCH) {
            $billCharges[$billCH['plot_charge_id']] = $billCH;
        }
//        dd($billCharges, $chargesDetailArray);
        return view('backend.bills.edit', compact('title', 'chargesDetailArray', 'banks',
            'allotees', 'months', 'bill', 'years', 'billCharges'));
    }


    public function getSectors(Request $request)
    {
        $sector = Sector::find($request->id);
        return response()->json(['responseData' => $sector]);
    }

    public function getBills(Request $request)
    {
        $bill = Bill::with('transaction')->find($request->bill_id);
//        dd($bill);
        return response()->json(['responseData' => $bill]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bill_type_id' => 'required',
            'allotee_id' => 'required',
            'bank_id' => 'required',
            'year' => 'required_if:bill_type_id,p,np,v',
            'issue_date' => 'required',
            'due_date' => 'required',
            'from_month' => ['required_if:bill_type_id,p'],
            'to_month' => ['required_if:bill_type_id,p'],
            'from_month.*' => 'required_if:bill_type_id,nu',
            'from_year.*' => 'required_if:bill_type_id,nu',
            'to_month.*' => 'required_if:bill_type_id,nu',
            'to_year.*' => 'required_if:bill_type_id,nu',
        ]);


        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.

        }
//        dd($request->all());


        $allotee = Allotee::with('size', 'sector')->find($request->allotee_id);

        $billType = $request->bill_type_id;

        $bill = Bill::find($id);

        if ($billType == 'p') {
            $totalBeforeArrearAmountOfCharges = 0;
            $totalMonths = $request->to_month - $request->from_month + 1;
            $this->billUpdateTimePeriod($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType, $bill, $id);
            $redirectRoute = 'view-bill';
        }

        if ($billType == 'np') {
            $totalBeforeArrearAmountOfCharges = 0;
            $totalMonths = $request->to_month - $request->from_month + 1;
            $this->billUpdateNonTimePeriod($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType, $bill, $id);
            $redirectRoute = 'view-bill-non-period';
        }
        if ($billType == 'v') {
            $totalBeforeArrearAmountOfCharges = 0;
            $totalMonths = $request->to_month - $request->from_month + 1;
            $this->billUpdateViolation($request, $allotee, $totalBeforeArrearAmountOfCharges, $billType, $bill, $id);
            $redirectRoute = 'view-bill-violation';
        }

        if ($billType == 'nu') {
            $this->billUpdateNonUser($request, $allotee, $billType, $bill, $id);
            $redirectRoute = 'view-bill-non-user';
        }

        if ($billType == 'sd') {
            $this->billUpdateStampDuty($request, $allotee, $billType, $bill, $id);
            $redirectRoute = 'view-bill-stamp-duty';
        }

        if ($billType == 'tr') {
            $totalBeforeArrearAmountOfCharges = 0;
            $totalMonths = $request->to_month - $request->from_month + 1;
            $this->billUpdateTransfer($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType, $bill, $id);
            $redirectRoute = 'view-transfer-bill';
        }
        if ($billType == 'ps') {
            $totalBeforeArrearAmountOfCharges = 0;
            $totalMonths = $request->to_month - $request->from_month + 1;
            $this->billUpdatePossession($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType, $bill, $id);
            $redirectRoute = 'view-possession-bill';
        }


// Commit the database transaction
        DB::commit();

// Redirect to the view-bill route with the updated bill id
        return redirect()->route($redirectRoute, $id);


    }

    public function changeStatus(Request $request)
    {
        $bill = Bill::find($request->id);
        $bill->is_active = $request->status;
        $bill->save();

        return response()->json(['success' => 'Status has been changed.']);
    }

    public function delete(Request $request)
    {
        $bill = Bill::find($request->id);
        BillTransaction::where('bill_id', $bill->id)->delete();
        $bill->delete();

        return response()->json(['success' => 'Record has been deleted.']);
    }

    public function deleteBulk(Request $request)
    {
        DB::beginTransaction();

        try {
            $selectedIds = $request->input('selectedIds');

            // Chunk the selected IDs into arrays of size 10
            $chunks = array_chunk($selectedIds, 10);

            foreach ($chunks as $chunk) {
                // Delete bills
                $deletedBills = Bill::whereIn('id', $chunk)->delete();

                // Delete related transactions
                $deletedTransactions = BillTransaction::whereIn('bill_id', $chunk)->delete();
            }

            DB::commit();

            $redirectUrl = route('show-bill');
            if ($deletedBills && $deletedTransactions) {
                return response()->json(['message' => 'Bills deleted successfully', 'redirect' => $redirectUrl], 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }





    public function checkDuplicateBill(Request $request)
    {


        $message = NULL;
        $status = true;
        $year = $request->input('year');
        $from_month = $request->input('from_month');
        $to_month = $request->input('to_month');
        $bill_type_id = $request->input('bill_type_id');
        $allotee = Allotee::find($request->input('allotee'));

        $chargesID = $request->checkedCheckboxes;


        if (isset($from_month) || isset($to_month)) {

            $checkDuplicate = Bill::with('fromMonth', 'toMonth', 'billCharges')
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
            if ($checkDuplicate->exists()) {
                $checkDuplicateData = $checkDuplicate->first();
//            dd($checkDuplicateData);
                $message = 'The Selected Charges  has already generated in bill from  ' .
                    $checkDuplicateData->fromMonth->name . ' to  ' . $checkDuplicateData->toMonth->name;
                $status = false;
            }
        } else {
            $checkDuplicate = Bill::with('fromMonth', 'toMonth', 'billCharges')
                ->where('year', $year)
                ->where('allotee_id', $allotee->id)
                ->whereHas('billCharges', function ($query) use ($chargesID) {
                    $query->where('plot_charge_id', $chargesID);
                });
            if ($checkDuplicate->exists()) {
                $checkDuplicateData = $checkDuplicate->first();
                $message = 'The Selected Charges has already generated in bill ' . $checkDuplicateData->bill_number;
                $status = false;
            }
        }


        return response()->json(['message' => $message, 'status' => $status], 200);
    }

    public function getStampDutyBillsDropdown(Request $request)
    {
        $allotee = $request->allotee;
        $bills = Bill::where('is_stamp_duty', true)->where('allotee_id', $allotee)->latest()->get();
        $billsArray = array();
        foreach ($bills as $b) {
            $billsArray[] = array(
                'id' => $b->id,
                'name' => $b->bill_number,
            );
        }

        return response()->json($billsArray);
    }

    public function getNonUserBillsDropdown(Request $request)
    {
        $allotee = $request->allotee;
        $bills = Bill::where('is_non_user', true)->where('allotee_id', $allotee)->latest()->get();
        $billsArray = array();
        foreach ($bills as $b) {
            $billsArray[] = array(
                'id' => $b->id,
                'name' => $b->bill_number,
            );
        }

        return response()->json($billsArray);
    }

    public function viewTransferBill($id)
    {
//        dd($id);
        $title = 'Bill';
        $sectors = Sector::all();
        $sizes = Size::all();
        $bill = Bill::with('allotee', 'size', 'sector', 'fromMonth', 'toMonth',
            'billCharges.referenceBill', 'billCharges.PlotCharges.charge', 'transaction', 'bank')
            ->find($id);

        $arrears = Bill::with('fromMonth', 'toMonth', 'transaction')
            ->where('is_transfer', '=', 1)
            ->where('id', '<', $id)
            ->where('allotee_id', '=', $bill->allotee_id);
        $billHistory = $arrears->get();
        $totalArrearAmount = $arrears
            ->where('is_paid', '=', 0)
            ->sum('total');


//        dd($bill);
//        dd($bill->billCharges);
        return view('backend.bills.bill_view_transfer', compact('title', 'sectors', 'sizes',
            'bill', 'billHistory', 'totalArrearAmount'));
    }

    public function viewPossessionBill($id)
    {
        $title = 'Bill';
        $sectors = Sector::all();
        $sizes = Size::all();
        $bill = Bill::with('allotee', 'size', 'sector', 'fromMonth', 'toMonth',
            'billCharges.referenceBill', 'billCharges.PlotCharges.charge', 'transaction', 'bank')
            ->find($id);

        $arrears = Bill::with('fromMonth', 'toMonth', 'transaction')
            ->where('is_transfer', '=', 1)
            ->where('id', '<', $id)
            ->where('allotee_id', '=', $bill->allotee_id);
        $billHistory = $arrears->get();
        $totalArrearAmount = $arrears
            ->where('is_paid', '=', 0)
            ->sum('bill_total');


//        dd($billHistory);
//        dd($bill->billCharges);
        return view('backend.bills.bill_view_possession', compact('title', 'sectors', 'sizes',
            'bill', 'billHistory', 'totalArrearAmount'));
    }


    public function receiptBill()
    {
        $title = 'Receipt Bill';
        $bill = Bill::where('is_active', 1)->where('is_paid', 0)->get();
        return view('backend.bills.receipt_bill', compact('title', 'bill'));
    }

    public function allReceiptBill(Request $request)
    {
        $title = 'Receipt Bill Transactions';

        if ($request->ajax()) {
            $billTransaction = BillTransaction::with('bill')->whereNotNull('bill_id')->get();

            return Datatables::of($billTransaction)
//                ->addColumn('billType', function ($bill) {
//                    return showBillTypeStatus($bill->bill);
//                })
                ->addColumn('bill_number', function ($bill) {

                    return $bill->bill->bill_number ?? '';
                })
                ->addColumn('name', function ($bill) {
                    // Concatenate the parts of the name, handling null values properly
                    return ($bill->bill->allotee->name ?? '') . ' ' .
                        ($bill->bill->allotee->plot_no ?? '') . ' ' .
                        ($bill->bill->sector->name ?? '') . ' ' .
                        ($bill->bill->size->name ?? '');
                })
                ->addColumn('total', function ($bill) {
                    return $bill->total ?? '';
                })
                ->addColumn('paid_amount', function ($bill) {
                    return $bill->paid_amount ?? '';
                })
                ->addColumn('due_amount', function ($bill) {
                    return $bill->due_amount ?? '';
                })
                ->addColumn('payment_date', function ($bill) {
                    return $bill->payment_date;
                })
                ->rawColumns(['status', 'billType'])
                ->make(true);
        }
        return view('backend.bills.reciept_transaction', compact('title'));
    }

    public function storeReceiptBill(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bill_id' => 'required',
                'payable_amount' => 'required',
                'original_amount' => 'required',
                'due_amount' => 'required',
                'issue_date' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ), 400); // 400 being the HTTP code for an invalid request.
            }

            $validatedData = $validator->validated();

            $bill = Bill::find($validatedData['bill_id']);
            $bill->is_paid = 1;
            $bill->save();

            if ($validatedData['payable_amount'] == $validatedData['original_amount']) {
                $arrears = 0;
            } else {
                $arrears = $validatedData['original_amount'] - $validatedData['payable_amount'];
            }

            $allotee = Allotee::find($bill->allotee_id);
            $allotee->arrears = $arrears;
            $allotee->save();

            $billTransaction = BillTransaction::where('bill_id', $validatedData['bill_id'])->first();
            $billTransaction->is_paid = 1;
            $billTransaction->paid_amount = $validatedData['payable_amount'];
            $billTransaction->due_amount = $arrears;
            $billTransaction->payment_date = dateInsert($validatedData['issue_date']);
            $billTransaction->save();

            if ($billTransaction) {
                return redirect()->route('show-bill')->with('success', 'Bill Receipt Updated Successfully');
            } else {
                return redirect()->route('show-bill')->with('error', 'Something Went Wrong');
            }
        } catch (Exception $e) {
            // Handle the exception here, you can log it or return an appropriate response
            return redirect()->route('show-bill')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function billGenerationTimePeriodSetup($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType)
    {
        $totalMonths = $request->to_month - $request->from_month + 1;
//        dd($totalMonths);


        $charges = PlotCharges::where('size_id', $allotee->size->id)
            ->where('year', $request->year)
            ->whereIn('id', $request->charges)
            ->get();

        $applyCharges = [];

        foreach ($charges as $charge) {
            $totalMonthCharges = ($charge->is_period == 1) ? $charge->amount * $totalMonths : $charge->amount;
            $totalBeforeArrearAmountOfCharges += $totalMonthCharges;

            $applyCharges[] = [
                'plot_charge_id' => $charge->id,
                'amount' => $totalMonthCharges,
            ];
        }

        $totalArrears = ($allotee->arrears > 0) ? $allotee->arrears :
            Bill::where('is_time_period', 1)
                ->where('allotee_id', $allotee->id)
                ->where('is_paid', 0)
                ->where('is_active', 1)
                ->sum('total');

        $percentage = getSettingValue('sub_charges');
        $percentageDecimal = $percentage / 100;
        $totalAmountOfCharges = $totalBeforeArrearAmountOfCharges + $totalArrears;
        $subCharges = $totalBeforeArrearAmountOfCharges * $percentageDecimal;
        $subTotal = $totalAmountOfCharges + $subCharges;

        // Deactivate bills based on $billType
        Bill::where('is_time_period', 1)
            ->where('allotee_id', $allotee->id)
            ->update(['is_active' => 0]);


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
            'is_time_period' => ($billType == 'p') ? true : false,
        );

        $bill = Bill::create($billData);
//        foreach ($applyCharges as $row) {
//            $billCharges = new BillCharge();
//            $billChargesData = array(
//                'bill_id' => $billID->id,
//                'plot_charge_id' => $row['plot_charge_id'],
//                'total' => $row['amount'],
//            );
//
//            $billCharges->create($billChargesData);
//        }

        // Create Bill Charges
        foreach ($applyCharges as $row) {
            $bill->charges()->create([
                'plot_charge_id' => $row['plot_charge_id'],
                'total' => $row['amount'],
            ]);
        }

        // Create Bill Transaction
        BillTransaction::create([
            'bill_id' => $bill->id,
            'total' => $bill->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $bill->total,
            'payment_date' => null,
        ]);

        // Clear arrears
        $allotee->arrears = 0;
        $allotee->save();

        return $bill->id;
    }

    public function billGenerationNonTimePeriodSetup($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType)
    {
        $totalMonths = $request->to_month - $request->from_month + 1;

        $charges = PlotCharges::where('size_id', $allotee->size->id)
            ->where('year', $request->year)
            ->whereIn('id', $request->charges)
            ->get();

        $applyCharges = [];

        foreach ($charges as $charge) {
            $totalMonthCharges = ($charge->is_period == 1) ? $charge->amount * $totalMonths : $charge->amount;
            $totalBeforeArrearAmountOfCharges += $totalMonthCharges;

            $applyCharges[] = [
                'plot_charge_id' => $charge->id,
                'amount' => $totalMonthCharges,
            ];
        }

        $totalArrears = ($allotee->arrears > 0) ? $allotee->arrears :
            Bill::where('is_non_time_period', 1)
                ->where('allotee_id', $allotee->id)
                ->sum('sub_total');


        $percentage = getSettingValue('sub_charges');
        $percentageDecimal = $percentage / 100;
        $totalAmountOfCharges = $totalBeforeArrearAmountOfCharges + $totalArrears;
        $subCharges = $totalAmountOfCharges * $percentageDecimal;
        $subTotal = $totalAmountOfCharges + $subCharges;

        // Deactivate bills based on $billType
        Bill::where('is_non_time_period', 1)
            ->where('allotee_id', $allotee->id)
            ->update(['is_active' => 0]);


        $billData = array(
            'bill_number' => Bill::generateNonTimePeriodBillNumber(),
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
            'is_non_time_period' => true,
        );

        $bill = Bill::create($billData);


        // Create Bill Charges
        foreach ($applyCharges as $row) {
            $bill->charges()->create([
                'plot_charge_id' => $row['plot_charge_id'],
                'total' => $row['amount'],
            ]);
        }

        // Create Bill Transaction
        BillTransaction::create([
            'bill_id' => $bill->id,
            'total' => $bill->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $bill->total,
            'payment_date' => null,
        ]);

        // Clear arrears
        $allotee->arrears = 0;
        $allotee->save();

        return $bill->id;
    }

    public function billGenerationSetupViolation($request, $allotee, $billType)
    {
        $totalBeforeArrearAmountOfCharges = 0;
        $applyCharges = [];

        if ($request->charges) {
            $chargeIDs = $request->charges;
            $charges = PlotCharges::whereIn('id', $chargeIDs)
                ->where('size_id', $allotee->size->id)
                ->where('year', $request->year)
                ->get();

            foreach ($request->charges as $key => $chargeID) {
                $charge = $charges->where('id', $chargeID)->first();

                if ($charge) {
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

                    $applyCharges[] = [
                        'plot_charge_id' => $charge->id,
                        'charge_amount' => $chargeAmount,
                        'total_violation' => $totalViolation,
                        'amount' => $totalMonthCharges
                    ];
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
            'is_violation' => true,
        );

        Bill::where(($billType == 'v') ? 'is_violation' : 'is_violation', '=', ($billType == 'v') ? 1 : 0)
            ->where('allotee_id', $allotee->id)
            ->update(['is_active' => 0]);


        $billID = Bill::create($billData);
        foreach ($applyCharges as $row) {
            $billCharges = new BillCharge();
            $billChargesData = array(
                'bill_id' => $billID->id,
                'plot_charge_id' => $row['plot_charge_id'],
                'amount' => $row['charge_amount'],
                'total_violation' => $row['total_violation'],
                'total' => $row['amount'],
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
        return $billID->id;
    }

    public function billGenerationSetupNonUser($request, $allotee, $billType)
    {
        if ($request->charge_amount) {

            $dataCharges = [];
            $totalBillAmount = 0;
            $totalBillSubCharges = 0;
            $subBillTotal = 0;
            foreach ($request->charge_amount as $key => $amount) {
                if (isset($request->from_month[$key], $request->from_year[$key], $request->to_month[$key], $request->to_year[$key])) {
                    $fromMonth = intval($request->from_month[$key]);
                    $fromYear = intval($request->from_year[$key]);
                    $toMonth = intval($request->to_month[$key]);
                    $toYear = intval($request->to_year[$key]);
                    $chargeAmount = floatval($amount);

                    $totalMonths = ($toYear - $fromYear) * 12 + ($toMonth - $fromMonth) + 1;
                    $totalAmount = $totalMonths * $chargeAmount;
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
                        'amount' => $chargeAmount,
                        'total' => $totalAmount,
                    ];
                }
            }

        }

        $billData = array(
            'bill_number' => NonUserBill::generateBillNumber(),
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
            'is_non_user' => true,

        );

        $billID = Bill::create($billData);

        foreach ($dataCharges as $billChargesData) {
            $billChargesData['bill_id'] = $billID->id;
//            $billChargesData['plot_charge_id'] = 3; // non user charges
//            dd($billChargesData);
            BillCharge::create($billChargesData);
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
        return $billID->id;
    }


    public function billGenerationSetupStampDuty($request, $allotee)
    {
        try {
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
                        $chargeAmount = intval($amount);

                        $totalMonths = ($toYear - $fromYear) * 12 + ($toMonth - $fromMonth) + 1;
//                        dd($allotee , $totalMarla, $chargeAmount , $noOfTransfer);
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
                'is_stamp_duty' => true,
            );

            // Create bill
            $billID = Bill::create($billData);

            // Create bill charges
            foreach ($dataCharges as $billChargesData) {
                $billChargesData['bill_id'] = $billID->id;
                BillCharge::create($billChargesData);
            }

            // Create bill transaction
            $billTransactionData = array(
                'stamp_duty_bill_id' => $billID->id,
                'total' => $billID->total,
                'is_paid' => 0,
                'paid_amount' => 0,
                'due_amount' => $billID->total,
                'payment_date' => NULL,
            );

            BillTransaction::create($billTransactionData);
            return $billID->id;
        } catch (\Exception $e) {

            dd('Error in billGenerationSetupStampDuty: ' . $e->getMessage());
            Log::error('Error in billGenerationSetupStampDuty: ' . $e->getMessage());
            // You can also rethrow the exception if you want to propagate it further
            // throw $e;
        }
    }

    public function billGenerationTransferSetup($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType)
    {

        $charges = PlotCharges::where('size_id', $allotee->size->id)
            ->where('year', $request->year)
            ->whereIn('id', $request->charges)
            ->get();

        $applyCharges = [];
        $referenceBillID = NULL;
        foreach ($charges as $charge) {
            $totalMonthCharges = $charge->amount;
            if ($totalMonthCharges == 0) {
                $referenceID = $charge->id;
                $referenceBill = 'bill_' . $referenceID;
                $referenceBillID = $request->input($referenceBill);
//                dd($referenceBillID, $request->all());
//                $totalMonthCharges = Bill::find($referenceBillID)->sub_total;
                $totalMonthCharges = Bill::find($referenceBillID)->total;
            }
            $totalBeforeArrearAmountOfCharges += $totalMonthCharges;

            $applyCharges[] = [
                'plot_charge_id' => $charge->id,
                'amount' => $totalMonthCharges,
                'reference_bill_id' => $referenceBillID,
            ];
        }
//        dd($totalBeforeArrearAmountOfCharges , $applyCharges , $request->all());

        $lastBillArrear = Bill::where('is_time_period', '=', 1)
            ->where('allotee_id', $allotee->id)
            ->where('is_paid', 0)
            ->where('is_active', 1)
            ->latest() // Orders by `created_at` or `updated_at` column (use `orderBy` if specific)
            ->first();

        $billArrears = $lastBillArrear ? $lastBillArrear->total : 0;


        $alloteeArrears = ($allotee->arrears > 0) ? $allotee->arrears : 0;

        $totalArrears = $alloteeArrears + $billArrears;

        $percentage = getSettingValue('sub_charges');
        $percentageDecimal = $percentage / 100;
        $totalAmountOfCharges = $totalBeforeArrearAmountOfCharges + $totalArrears;
        $subCharges = $totalAmountOfCharges * $percentageDecimal;
        $subTotal = $totalAmountOfCharges + $subCharges;


        // Deactivate bills based on $billType
        Bill::where('is_transfer', '=', ($billType == 'tr') ? 1 : 0)
            ->where('allotee_id', $allotee->id)
            ->update(['is_active' => 0]);


        $billData = array(
            'bill_number' => Bill::generateTransferBillNumber(),
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
            'is_transfer' => ($billType == 'tr') ? true : false,
        );

//        dd($billData);
        $bill = Bill::create($billData);


        // Create Bill Charges
        foreach ($applyCharges as $row) {
            $bill->charges()->create([
                'plot_charge_id' => $row['plot_charge_id'],
                'total' => $row['amount'],
                'reference_bill_id' => $row['reference_bill_id'],
            ]);
        }

        // Create Bill Transaction
        BillTransaction::create([
            'bill_id' => $bill->id,
            'total' => $bill->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $bill->total,
            'payment_date' => null,
        ]);

        return $bill->id;
    }

    public function billGenerationPossessionSetup($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType)
    {

        $charges = PlotCharges::where('size_id', $allotee->size->id)
            ->where('year', $request->year)
            ->whereIn('id', $request->charges)
            ->get();

        $applyCharges = [];
        $referenceBillID = NULL;
        foreach ($charges as $charge) {
            $totalMonthCharges = $charge->amount;
            if ($totalMonthCharges == 0) {
                $referenceID = $charge->id;
                $referenceBill = 'bill_' . $referenceID;
                $referenceBillID = $request->input($referenceBill);
//                dd($referenceBillID, $request->all());
                $totalMonthCharges = Bill::find($referenceBillID)->total;
            }
            $totalBeforeArrearAmountOfCharges += $totalMonthCharges;

            $applyCharges[] = [
                'plot_charge_id' => $charge->id,
                'amount' => $totalMonthCharges,
                'reference_bill_id' => $referenceBillID,
            ];
        }
//        dd($totalBeforeArrearAmountOfCharges , $applyCharges , $request->all());

        $totalArrears = ($allotee->arrears > 0) ? $allotee->arrears : 0;


//        $totalArrears = 0;

        $percentage = getSettingValue('sub_charges');
        $percentageDecimal = $percentage / 100;
        $totalAmountOfCharges = $totalBeforeArrearAmountOfCharges + $totalArrears;
        $subCharges = $totalAmountOfCharges * $percentageDecimal;
        $subTotal = $totalAmountOfCharges + $subCharges;


        // Deactivate bills based on $billType
        Bill::where('is_transfer', '=', ($billType == 'tr') ? 1 : 0)
            ->where('allotee_id', $allotee->id)
            ->update(['is_active' => 0]);


        $billData = array(
            'bill_number' => Bill::generatePossessionBillNumber(),
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
            'is_possession' => true
        );

//        dd($billData);
        $bill = Bill::create($billData);


        // Create Bill Charges
        foreach ($applyCharges as $row) {
            $bill->charges()->create([
                'plot_charge_id' => $row['plot_charge_id'],
                'total' => $row['amount'],
                'reference_bill_id' => $row['reference_bill_id'],
            ]);
        }

        // Create Bill Transaction
        BillTransaction::create([
            'bill_id' => $bill->id,
            'total' => $bill->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $bill->total,
            'payment_date' => null,
        ]);

        return $bill->id;
    }


    public function billUpdateTimePeriod($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType, $bill, $id)
    {
        if ($request->charges) {
            $charges = PlotCharges::where('size_id', $allotee->size->id)
                ->where('year', $request->year)
                ->whereIn('id', $request->charges)
                ->get();

            foreach ($charges as $charge) {
                $totalMonthCharges = ($charge->is_period == 1) ? $charge->amount * $totalMonths : $charge->amount;
                $totalBeforeArrearAmountOfCharges += $totalMonthCharges;

                $percentageDecimal = getSettingValue('sub_charges') / 100;

                $totalArrears = max($allotee->arrears, Bill::where('is_time_period',1)
                    ->where('allotee_id', $allotee->id)
                    ->where('id', '<', $id)
                    ->where('is_paid', 0)
                    ->where('is_active', 0)
                    ->sum('total'));

                $totalAmountOfCharges = $totalBeforeArrearAmountOfCharges + $totalArrears;
                $subCharges = $totalBeforeArrearAmountOfCharges * $percentageDecimal;
                $subTotal = $totalAmountOfCharges + $subCharges;

                $applyCharges[] = [
                    'plot_charge_id' => $charge->id,
                    'amount' => $totalMonthCharges,
                ];
            }
        }

        // Deactivate bills based on $billType
        Bill::where('is_time_period', 1)
            ->where('allotee_id', $allotee->id)
            ->where('id', '<', $id)
            ->update(['is_active' => 0]);

        $billData = [
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
            'generated_by' => auth()->user()->id,
            'bill_total' => $totalBeforeArrearAmountOfCharges,
            'arrears' => $totalArrears,
            'total' => $totalAmountOfCharges,
            'sub_charges' => $subCharges,
            'sub_total' => $subTotal,
            'is_time_period' => ($billType == 'p'),
        ];

        $bill->update($billData);

        BillCharge::where('bill_id', $id)->delete();

        $billChargesData = array_map(function ($row) use ($id) {
            return [
                'bill_id' => $id,
                'plot_charge_id' => $row['plot_charge_id'],
                'total' => $row['amount'],
            ];
        }, $applyCharges);

        BillCharge::insert($billChargesData);

        BillTransaction::where('bill_id', $id)->delete();

        BillTransaction::create([
            'bill_id' => $id,
            'total' => $bill->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $bill->total,
            'payment_date' => null,
        ]);
    }

    public function billUpdateNonTimePeriod($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType, $bill, $id)
    {
        if ($request->charges) {
            $charges = PlotCharges::where('size_id', $allotee->size->id)
                ->where('year', $request->year)
                ->whereIn('id', $request->charges)
                ->get();

            $totalBeforeArrearAmountOfCharges = 0;

            foreach ($charges as $charge) {
                $totalMonthCharges = ($charge->is_period == 0) ? $charge->amount * $totalMonths : $charge->amount;
                $totalBeforeArrearAmountOfCharges += $totalMonthCharges;

                $percentage = getSettingValue('sub_charges');
                $percentageDecimal = $percentage / 100;

                $totalArrears = max($allotee->arrears, Bill::where('is_non_time_period', $billType == 'np' ? 1 : 0)
                    ->where('allotee_id', $allotee->id)
                    ->where('id', '<', $id)
                    ->sum('sub_total'));

                $totalAmountOfCharges = $totalBeforeArrearAmountOfCharges + $totalArrears;
                $subCharges = $totalAmountOfCharges * $percentageDecimal;
                $subTotal = $totalAmountOfCharges + $subCharges;

                $applyCharges[] = [
                    'plot_charge_id' => $charge->id,
                    'amount' => $totalMonthCharges,
                ];
            }

            // Define bill data using an associative array
            $billData = [
                'allotee_id' => $allotee->id,
                'bank_id' => $request->bank_id,
                'sector_id' => $allotee->sector->id,
                'size_id' => $allotee->size->id,
                'year' => $request->year,
                'from_month' => null,
                'to_month' => null,
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
                'is_non_time_period' => ($billType == 'np'),
            ];

            // Update the bill using mass assignment
            $bill->update($billData);

            // Delete existing bill charges for the specified bill_id
            BillCharge::where('bill_id', $id)->delete();

            // Use mass creation to create multiple bill charges
            $billChargesData = array_map(function ($row) use ($id) {
                return [
                    'bill_id' => $id,
                    'plot_charge_id' => $row['plot_charge_id'],
                    'total' => $row['amount'],
                ];
            }, $applyCharges);

            BillCharge::insert($billChargesData);

            // Delete existing bill transactions for the specified bill_id
            BillTransaction::where('bill_id', $id)->delete();

            // Create a new bill transaction
            $billTransactionData = [
                'bill_id' => $id,
                'total' => $bill->total,
                'is_paid' => 0,
                'paid_amount' => 0,
                'due_amount' => $bill->total,
                'payment_date' => null,
            ];

            BillTransaction::create($billTransactionData);
        }

    }

    public function billUpdateTransfer($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType, $bill, $id)
    {
        if ($request->charges) {
            $charges = PlotCharges::where('size_id', $allotee->size->id)
                ->where('year', $request->year)
                ->whereIn('id', $request->charges)
                ->get();

            $totalBeforeArrearAmountOfCharges = 0;
            $referenceBillID = NULL;
            foreach ($charges as $charge) {
                $totalMonthCharges = $charge->amount;
                if ($totalMonthCharges == 0) {
                    $referenceID = $charge->id;
                    $referenceBill = 'bill_' . $referenceID;
                    $referenceBillID = $request->input($referenceBill);
//                dd($referenceBillID, $request->all());
//                    $totalMonthCharges = Bill::find($referenceBillID)->sub_total;
                    $totalMonthCharges = Bill::find($referenceBillID)->total;
                }
                $totalBeforeArrearAmountOfCharges += $totalMonthCharges;

                $applyCharges[] = [
                    'plot_charge_id' => $charge->id,
                    'amount' => $totalMonthCharges,
                    'reference_bill_id' => $referenceBillID,
                ];
            }

            $lastBillArrear = Bill::where('is_time_period', '=', 1)
                ->where('allotee_id', $allotee->id)
                ->where('is_paid', 0)
                ->where('is_active', 1)
                ->latest() // Orders by `created_at` or `updated_at` column (use `orderBy` if specific)
                ->first();

            $billArrears = $lastBillArrear ? $lastBillArrear->total : 0;

            $alloteeArrears = ($allotee->arrears > 0) ? $allotee->arrears : 0;


            $totalArrears = $alloteeArrears + $billArrears;

            $percentage = getSettingValue('sub_charges');
            $percentageDecimal = $percentage / 100;
            $totalAmountOfCharges = $totalBeforeArrearAmountOfCharges + $totalArrears;
            $subCharges = $totalAmountOfCharges * $percentageDecimal;
            $subTotal = $totalAmountOfCharges + $subCharges;


            // Define bill data using an associative array
            $billData = [
                'allotee_id' => $allotee->id,
                'bank_id' => $request->bank_id,
                'sector_id' => $allotee->sector->id,
                'size_id' => $allotee->size->id,
                'year' => $request->year,
                'from_month' => null,
                'to_month' => null,
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
            ];

            // Update the bill using mass assignment
            $bill->update($billData);

            // Delete existing bill charges for the specified bill_id
            BillCharge::where('bill_id', $id)->delete();

            // Use mass creation to create multiple bill charges
            $billChargesData = array_map(function ($row) use ($id) {
                return [
                    'bill_id' => $id,
                    'plot_charge_id' => $row['plot_charge_id'],
                    'total' => $row['amount'],
                    'reference_bill_id' => $row['reference_bill_id'],

                ];
            }, $applyCharges);

            BillCharge::insert($billChargesData);

            // Delete existing bill transactions for the specified bill_id
            BillTransaction::where('bill_id', $id)->delete();

            // Create a new bill transaction
            $billTransactionData = [
                'bill_id' => $id,
                'total' => $bill->total,
                'is_paid' => 0,
                'paid_amount' => 0,
                'due_amount' => $bill->total,
                'payment_date' => null,
            ];

            BillTransaction::create($billTransactionData);
        }

    }

    public function billUpdatePossession($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges, $billType, $bill, $id)
    {
        if ($request->charges) {
            $charges = PlotCharges::where('size_id', $allotee->size->id)
                ->where('year', $request->year)
                ->whereIn('id', $request->charges)
                ->get();

            $totalBeforeArrearAmountOfCharges = 0;
            $referenceBillID = NULL;
            foreach ($charges as $charge) {
                $totalMonthCharges = $charge->amount;
                if ($totalMonthCharges == 0) {
                    $referenceID = $charge->id;
                    $referenceBill = 'bill_' . $referenceID;
                    $referenceBillID = $request->input($referenceBill);
//                dd($referenceBillID, $request->all());
                    $totalMonthCharges = Bill::find($referenceBillID)->total;
                }
                $totalBeforeArrearAmountOfCharges += $totalMonthCharges;

                $applyCharges[] = [
                    'plot_charge_id' => $charge->id,
                    'amount' => $totalMonthCharges,
                    'reference_bill_id' => $referenceBillID,
                ];
            }

//            $totalArrears = ($allotee->arrears > 0) ? $allotee->arrears :
//                Bill::where('is_possession', '=',1)
//                    ->where('allotee_id', $allotee->id)
//                    ->sum('bill_total');

            $totalArrears = ($allotee->arrears > 0) ? $allotee->arrears : 0;


//            $totalArrears = 0;

            $percentage = getSettingValue('sub_charges');
            $percentageDecimal = $percentage / 100;
            $totalAmountOfCharges = $totalBeforeArrearAmountOfCharges + $totalArrears;
            $subCharges = $totalAmountOfCharges * $percentageDecimal;
            $subTotal = $totalAmountOfCharges + $subCharges;


            // Define bill data using an associative array
            $billData = [
                'allotee_id' => $allotee->id,
                'bank_id' => $request->bank_id,
                'sector_id' => $allotee->sector->id,
                'size_id' => $allotee->size->id,
                'year' => $request->year,
                'from_month' => null,
                'to_month' => null,
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
            ];

            // Update the bill using mass assignment
            $bill->update($billData);

            // Delete existing bill charges for the specified bill_id
            BillCharge::where('bill_id', $id)->delete();

            // Use mass creation to create multiple bill charges
            $billChargesData = array_map(function ($row) use ($id) {
                return [
                    'bill_id' => $id,
                    'plot_charge_id' => $row['plot_charge_id'],
                    'total' => $row['amount'],
                    'reference_bill_id' => $row['reference_bill_id'],

                ];
            }, $applyCharges);

            BillCharge::insert($billChargesData);

            // Delete existing bill transactions for the specified bill_id
            BillTransaction::where('bill_id', $id)->delete();

            // Create a new bill transaction
            $billTransactionData = [
                'bill_id' => $id,
                'total' => $bill->total,
                'is_paid' => 0,
                'paid_amount' => 0,
                'due_amount' => $bill->total,
                'payment_date' => null,
            ];

            BillTransaction::create($billTransactionData);
        }

    }

    public function billUpdateViolation($request, $allotee, $totalBeforeArrearAmountOfCharges, $billType, $bill, $id)
    {
        $applyCharges = [];

        if ($request->charges) {
//            dd($request->all());
            $charges = PlotCharges::whereIn('id', $request->charges)
                ->where('size_id', $allotee->size->id)
                ->where('year', $request->year)
                ->get();

            foreach ($request->charges as $key => $chargeID) {
                $charge = $charges->where('id', $chargeID)->first();
                if ($charge) {
                    $totalMonthCharges = $request->total_amount[$key];
                    $totalViolation = $request->total_sft[$key];
                    $chargeAmount = $request->charge_amount[$key];

                    $totalBeforeArrearAmountOfCharges += $totalMonthCharges;

                    $percentage = getSettingValue('sub_charges');
                    $percentageDecimal = $percentage / 100;
                    $totalArrears = 0;

                    $totalAmountOfCharges = $totalBeforeArrearAmountOfCharges + $totalArrears;
                    $subCharges = $totalAmountOfCharges * $percentageDecimal;
                    $subTotal = $totalAmountOfCharges + $subCharges;

                    $applyCharges[] = [
                        'plot_charge_id' => $charge->id,
                        'charge_amount' => $chargeAmount,
                        'total_violation' => $totalViolation,
                        'amount' => $totalMonthCharges
                    ];
                }
            }
        }

        $billData = [
            'allotee_id' => $allotee->id,
            'bank_id' => $request->bank_id,
            'sector_id' => $allotee->sector->id,
            'size_id' => $allotee->size->id,
            'year' => $request->year,
            'from_month' => Null,
            'to_month' => Null,
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
            'is_violation' => ($billType == 'v'),
        ];

        $bill->update($billData);

        $bill->charges()->delete();
        foreach ($applyCharges as $row) {
            $billCharges = new BillCharge();
            $billChargesData = array(
                'bill_id' => $id,
                'plot_charge_id' => $row['plot_charge_id'],
                'amount' => $row['charge_amount'],
                'total_violation' => $row['total_violation'],
                'total' => $row['amount'],

            );

            $billCharges->create($billChargesData);
        }

        $bill->transaction()->delete();
        $bill->transaction()->create([
            'total' => $bill->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $bill->total,
            'payment_date' => null,
        ]);
    }

    public function billUpdateNonUser($request, $allotee, $billType, $bill, $id)
    {
        $dataCharges = [];

        if ($request->charge_amount) {
            $totalBillAmount = 0;
            $totalBillSubCharges = 0;
            $subBillTotal = 0;

            foreach ($request->charge_amount as $key => $amount) {
                if (isset($request->from_month[$key], $request->from_year[$key], $request->to_month[$key], $request->to_year[$key])) {
                    $fromMonth = intval($request->from_month[$key]);
                    $fromYear = intval($request->from_year[$key]);
                    $toMonth = intval($request->to_month[$key]);
                    $toYear = intval($request->to_year[$key]);

                    $totalMonths = ($toYear - $fromYear) * 12 + ($toMonth - $fromMonth) + 1;
                    $totalAmount = $totalMonths * floatval($amount);
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
                        'amount' => floatval($amount),
                        'total' => $totalAmount,
                    ];
                }
            }
        }

        $billData = [
            'allotee_id' => $allotee->id,
            'bank_id' => $request->bank_id,
            'sector_id' => $allotee->sector->id,
            'size_id' => $allotee->size->id,
            'issue_date' => dateInsert($request->issue_date),
            'due_date' => dateInsert($request->due_date),
            'total' => $totalBillAmount,
            'sub_charges' => $totalBillSubCharges,
            'sub_total' => $subBillTotal,
            'is_non_user' => ($billType == 'nu'),
        ];

        $bill->update($billData);

        BillCharge::where('bill_id', $id)->delete();

        foreach ($dataCharges as $billChargesData) {
            $billChargesData['bill_id'] = $bill->id;
            BillCharge::create($billChargesData);
        }

        BillTransaction::where('bill_id', $id)->delete();

        $billTransactionData = [
            'bill_id' => $bill->id,
            'total' => $bill->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $bill->total,
            'payment_date' => null,
        ];

        BillTransaction::create($billTransactionData);
    }

    public function billUpdateStampDuty($request, $allotee, $billType, $bill, $id)
    {
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

        $billData = [
            'allotee_id' => $allotee->id,
            'bank_id' => $request->bank_id,
            'sector_id' => $allotee->sector->id,
            'size_id' => $allotee->size->id,
            'issue_date' => dateInsert($request->issue_date),
            'due_date' => dateInsert($request->due_date),
            'total' => $totalBillAmount,
            'sub_charges' => $totalBillSubCharges,
            'sub_total' => $subBillTotal,
        ];

        $bill->update($billData);

        BillCharge::where('bill_id', $id)->delete();

        foreach ($dataCharges as $billChargesData) {
            $billChargesData['bill_id'] = $bill->id;
            BillCharge::create($billChargesData);
        }

        BillTransaction::where('bill_id', $id)->delete();
        $billTransactionData = [
            'stamp_duty_bill_id' => $bill->id,
            'total' => $bill->total,
            'is_paid' => 0,
            'paid_amount' => 0,
            'due_amount' => $bill->total,
            'payment_date' => null,
        ];
        BillTransaction::create($billTransactionData);
    }


    /////////////////////////////////////////////////////////////////////////////////////
    public function showCombineBill()
    {
        $title = 'Combine Bill';
        $query = Bill::query();
        $bills = $query->with('size', 'sector', 'allotee', 'fromMonth', 'toMonth')->get();
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
//            dd($request->all());

//            $allotees = Allotee::with('size', 'sector')->where(function ($query) use ($request) {
//                $query->where('sector_id', $request->sector_id)
//                    ->orWhere('size_id', $request->size_id);
//            })->get();
            $allotees = Allotee::with('size', 'sector')->where(function ($query) use ($request) {
                if ($request->sector_id > 0 && $request->size_id == 0) {

                    $query->where('sector_id', $request->sector_id);

                } elseif ($request->size_id > 0 && $request->sector_id == 0) {

                    $query->where('size_id', $request->size_id);

                } else {
                    $query->where('sector_id', $request->sector_id)
                        ->where('size_id', $request->size_id);
                }
            })->get();

//            dd($allotees->count());
            $totalBeforeArrearAmountOfCharges = 0;
            $totalMonths = $request->to_month - $request->from_month + 1;

            $billIDS = array();
            foreach ($allotees as $allotee) {
                $billIDS[] = $this->billGenerationTimePeriodSetup($request, $allotee, $totalMonths, $totalBeforeArrearAmountOfCharges);
            }

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


        return view('backend.bills.bill_view_combine', compact('title', 'sectors', 'sizes',
            'bills', 'billHistories', 'totalArrearAmounts'));
    }


}
