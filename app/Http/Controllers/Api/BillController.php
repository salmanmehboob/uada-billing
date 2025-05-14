<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Allotee;
use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{

    public function getBillForWeb(Request $request)
    {
        $sectorId = $request->sector_id;
        $sizeId = $request->size_id;
        $plotNo = $request->plot_no;

        // Step 1: Find Allotee
        $allotee = Allotee::where('plot_no', $plotNo)
            ->where('sector_id', $sectorId)
            ->where('size_id', $sizeId)
            ->first();

        if (!$allotee) {
            return response()->json([
                'status' => false,
                'message' => 'Allotee not found.'
            ], 404);
        }

        // Step 2: Get Latest Bill for Allotee
        $bill = Bill::with([
            'allotee', 'size', 'sector', 'fromMonth', 'toMonth',
            'billCharges', 'billCharges.PlotCharges.charge', 'transaction', 'bank'
        ])
            ->where('allotee_id', $allotee->id)
            ->where('is_paid', '=',0)
            ->orderBy('id', 'desc')
            ->first(); // Use first() to get the latest one

        if (!$bill) {
            return response()->json([
                'status' => false,
                'message' => 'Bill not found.'
            ], 404);
        }

        // Step 3: Get Arrears
//        $arrears = Bill::with('fromMonth', 'toMonth', 'transaction')
//            ->where('id', '<', $bill->id)
////            ->where('is_period', 1)
//            ->where('allotee_id', $bill->allotee_id)
//            ->where('is_paid', '=',0)
//            ->get();


        // Step 4: Return JSON Response
        return response()->json([
            'status' => true,
            'bill' => $bill,
         ]);
    }

    public function show($id)
    {
        $bill = Bill::with([
            'allotee',
            'size',
            'sector',
            'fromMonth',
            'toMonth',
            'billCharges',
            'billCharges.PlotCharges.charge',
            'transaction',
            'bank'
        ])->find($id);

        if (!$bill) {
            return response()->json([
                'success' => false,
                'message' => 'Bill not found'
            ], 404);
        }

        // Get arrears
        $arrears = Bill::with(['fromMonth', 'toMonth', 'transaction'])
            ->where('id', '<', $id)
            ->where('is_period', 1)
            ->where('allotee_id', $bill->allotee_id);

        $billHistory = $arrears->get();
        $totalArrearAmount = $arrears->where('is_paid', 0)->sum('total');

        return response()->json([
            'success' => true,
            'data' => [
                'bill' => $bill,
                'billHistory' => $billHistory,
                'totalArrearAmount' => $totalArrearAmount,
            ]
        ]);
    }


}
