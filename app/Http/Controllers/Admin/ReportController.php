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

        return view('backend.report.general_report', compact('title',
            'size', 'sector', 'type', 'bank', 'months', 'charges'));
    }

    /**
     * Generate the general report via GET request (not DataTables/AJAX)
     */
    public function generalReportGet(Request $request)
    {
        $title = 'General Report';
        $size = Size::all();
        $sector = Sector::all();
        $type = Type::all();
        $bank = Bank::all();
        $months = Month::all();
        $charges = Charge::all();

        // Build query with filters
        $bills = Bill::with('allotee', 'size', 'sector', 'fromMonth', 'toMonth', 'bank', 'generatedBy', 'billCharges.PlotCharges');


        if (isset($request->status)) {

            $bills->where('is_paid', $request->status);
        }

         if (!empty($request->size_id)) {
            $bills->where('size_id', $request->size_id);
        }
        if (!empty($request->sector_id)) {
            $bills->where('sector_id', $request->sector_id);
        }
//        if (!empty($request->bank_id)) {
//            $bills->where('bank_id', $request->bank_id);
//        }
        if (!empty($request->year)) {
            $bills->where('year', $request->year);
        }
        if (!empty($request->from_month)) {
            $bills->where('from_month', '>=', $request->from_month);
        }
        if (!empty($request->to_month)) {
            $bills->where('to_month', '<=', $request->to_month);
        }

        if (isset($request->amount) && $request->amount != '' && isset($request->amount_filter) && $request->amount_filter != '') {
            $bills->where('sub_total', $request->amount_filter, $request->amount);
        }
//        if (!empty($request->charge_id)) {
//            if ($request->charge_id == 3) {
//                $bills->where('bill_number', 'like', '%-NU-%');
//            } elseif ($request->charge_id == 24) {
//                $bills->where('bill_number', 'like', '%-STD-%');
//            } else {
//                $bills->whereHas('billCharges.PlotCharges', function ($query) use ($request) {
//                    $query->where('charge_id', $request->charge_id);
//                    if (!empty($request->size_id)) {
//                        $query->where('size_id', $request->size_id);
//                    }
//                });
//            }
//        }

        $bills = $bills->orderBy('id', 'desc')->get();

        return view('backend.report.general_report', compact('title', 'size', 'sector', 'type', 'bank', 'months', 'charges', 'bills'));
    }


}
