<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Bill;
use App\Models\Charge;
use App\Models\Month;
use App\Models\Sector;
use App\Models\Size;
use App\Models\Type;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    public function generalReport(Request $request)
    {
        $title = 'General Report';
        $size = Size::all();
        $sector = Sector::all();
        $type = Type::all();
        $bank = Bank::all();
        $months = Month::all();
        $charges = Charge::all();
        if ($request->ajax()) {

            $bills = Bill::with('allotee', 'size', 'sector', 'fromMonth',
                'toMonth', 'bank', 'generatedBy', 'billCharges.PlotCharges');

            return Datatables::of($bills)
                ->filter(function ($instance) use ($request) {
//                    $searchTerm = $request->get('search');
                     if (!empty($request->get('size_id'))) {
                        $instance->where('size_id', $request->get('size_id'));
                    }

                    if (!empty($request->get('sector_id'))) {
                        $instance->where('sector_id', $request->get('sector_id'));
                    }

                    if (!empty($request->get('bank_id'))) {
                        $instance->where('bank_id', $request->get('bank_id'));
                    }

                    if (!empty($request->get('year'))) {
                        $instance->where('year', $request->get('year'));
                    }

                    if (!empty($request->get('from_month'))) {
                        $instance->where('from_month', $request->get('from_month'));
                    }

                    if (!empty($request->get('to_month'))) {
                        $instance->where('year', $request->get('to_month'));
                    }

                    if (!empty($request->get('status'))) {
                        $instance->where('is_paid', $request->get('status'));
                    }

                    if (!empty($request->get('charge_id'))) {
                        if($request->get('charge_id') == 3){
                            $instance->where('bill_number', 'like', '%-NU-%');
                        }else if($request->get('charge_id') == 24){
                            $instance->where('bill_number', 'like', '%-STD-%');
                        }else{
                            $instance->whereHas('billCharges.PlotCharges', function ($query) use ($request) {
                                $query->where('charge_id', $request->get('charge_id'));

                                if (!empty($request->get('size_id'))) {
                                    $query->where('size_id', $request->get('size_id'));
                                }
                            });
                        }

                    }

                })
                ->addColumn('bill_number', function ($row) {
                    $viewBtn = '<a target="_blank" title="View" href="' . route('view-bill', $row->id) . '" class="text-info mr-1">' . $row->bill_number . '</a>';
                    return $viewBtn;
                })

                ->addColumn('allotee_name', function ($row) {
                    return $row->allotee->name;
                })
                ->addColumn('bank', function ($row) {
                    return $row->bank->name;
                })
                ->addColumn('sector', function ($row) {
                    return $row->sector->name ?? '';
                })
                ->addColumn('plot_size', function ($row) {
//                   dd($row->size->name);
                    return $row->size->name ?? '';
                })
                ->addColumn('year', function ($row) {
                    return $row->year;
                })
                ->addColumn('from_month', function ($row) {
                    return $row->fromMonth->short;
                })
                ->addColumn('to_month', function ($row) {
                    return $row->toMonth->short;
                })
                ->addColumn('issue_date', function ($row) {
                    return showDate($row->issue_date);
                })
                ->addColumn('due_date', function ($row) {
                    return showDate($row->due_date);
                })
                ->addColumn('total', function ($row) {
                    return $row->total;
                })
                ->addColumn('sub_charges', function ($row) {
                    return $row->sub_charges;
                })
                ->addColumn('sub_total', function ($row) {
                    return $row->sub_total;
                })
                ->addColumn('status', function ($row) {
                    return $row->is_paid == 1 ? 'Paid' : 'Un Paid';
                })
                ->addColumn('generated_by', function ($row) {
                    return $row->generatedBy->name ?? '';
                })
                ->rawColumns(['bill_number'])
                ->make(true);
        }
        return view('backend.report.general_report', compact('title',
            'size', 'sector', 'type', 'bank', 'months', 'charges'));
    }


}
