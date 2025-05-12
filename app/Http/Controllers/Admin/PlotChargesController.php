<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allotee;
use App\Models\Charge;
use App\Models\ChargeType;
use App\Models\PlotCharges;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlotChargesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:view-plotcharges'])->only(['index']);
        $this->middleware(['permission:edit-plotcharges'])->only(['edit', 'update']);
        $this->middleware(['permission:create-plotcharges'])->only(['create', 'store']);
        $this->middleware(['permission:delete-plotcharges'])->only(['changeStatus','delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $title = 'Plot Charges';
        $query = PlotCharges::query();
        $plotCharges = $query->with('size', 'charge', 'type')->get();
        $charges = Charge::orderBy('name', 'ASC')->get();
        $sizes = Size::orderBy('name', 'ASC')->get();
        return view('backend.plot_charges.index', compact('title',
            'plotCharges', 'charges', 'sizes'));
    }

    public function index()
    {
        $title = 'Plot Charges';
        $charges = Charge::orderBy('name', 'ASC')->get();
        $sizes = Size::orderBy('name', 'ASC')->get();
        $chargeType = ChargeType::orderBy('name', 'ASC')->get();
        return view('backend.plot_charges.create', compact('title',
            'charges', 'sizes', 'chargeType'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PlotCharges $plotCharges)
    {
        $validator = Validator::make($request->all(), [
            'size_id' => 'required',
            'charge_id' => 'required',
            'charge_type_id' => 'required',
            'amount' => 'required',
            'year' => 'required',
            'is_period' => 'required',
            'is_open' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect(route('add-plotCharges'))
                ->withErrors($validator)
                ->withInput();

        }

        $validatedData = $validator->validated();
        $plotCharges->amount = $validatedData['amount'];
        $plotCharges->size_id = $validatedData['size_id'];
        $plotCharges->charge_id = $validatedData['charge_id'];
        $plotCharges->charge_type_id = $validatedData['charge_type_id'];
        $plotCharges->year = $validatedData['year'];
        $plotCharges->is_period = $validatedData['is_period'];
        $plotCharges->is_open = $validatedData['is_open'];
        $plotCharges->save();

        if (isset($plotCharges->id)) {

            return redirect()->route('show-plotCharges')->with('success', 'Record has been Added Successfully');

        } else {
            return redirect()->back()->with('error', 'Some thing Went Wrong');
        }

    }

    public function edit($id)
    {
        $title = 'Plot Charges';
        $plotCharges = PlotCharges::find($id);
        $charges = Charge::orderBy('name', 'ASC')->get();
        $sizes = Size::orderBy('name', 'ASC')->get();
        $chargeType = ChargeType::orderBy('name', 'ASC')->get();
//dd($plotCharges);
        return view('backend.plot_charges.edit', compact('title',
            'plotCharges', 'charges', 'sizes', 'chargeType'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'size_id' => 'required',
            'charge_id' => 'required',
            'charge_type_id' => 'required',
            'amount' => 'required',
            'year' => 'required',
            'is_period' => 'required',
            'is_open' => 'required',

        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();

        }

        $validatedData = $validator->validated();
        $dataToUpdate = [
            'amount' => $validatedData['amount'],
            'size_id' => $validatedData['size_id'],
            'charge_id' => $validatedData['charge_id'],
            'charge_type_id' => $validatedData['charge_type_id'],
            'year' => $validatedData['year'],
            'is_period' => $validatedData['is_period'],
            'is_open' => $validatedData['is_open'],
        ];

        $plotCharges = PlotCharges::where('id', $id)->update($dataToUpdate);

//        dd($plotCharges);
        if (isset($plotCharges)) {

            return redirect()->route('show-plotCharges')->with('success', 'Record has been Updated Successfully');

        } else {
            return redirect()->back()->with('error', 'Some thing Went Wrong');
        }

    }


    public function getPlotCharges(Request $request)
    {

        $chargesDetailArray = array();
        $year = $request->input('year');
        $allotee = Allotee::find($request->input('allotee'));
        $bill_type_id = $request->input('bill_type_id');

        $plotCharges = PlotCharges::with('charge')
            ->where('year', $year)
            ->where('size_id', $allotee->size_id);

        if ($bill_type_id == 'p') {
            $plotCharges->where('is_period', 1);
        }

        if ($bill_type_id == 'ps') {
             $plotCharges->whereHas('charge', function ($query) {
                $query->where('is_possession', 1);
            });
         }

        if ($bill_type_id == 'tr') {
            $plotCharges->whereHas('charge', function ($query) {
                $query->where('is_transfer', 1);
            });
        }

        if ($bill_type_id == 'ps') {
            $plotCharges->whereHas('charge', function ($query) {
                $query->where('is_possession', 1);
            });
        }


//        dd($plotCharges->count());
         $plotCharges = $plotCharges->get();

        foreach ($plotCharges as $ch) {
            $chargesDetailArray[] = array(
                'id' => $ch->id,
                'name' => $ch->charge->name ,
                'amount' => $ch->amount,
                'is_reference' => $ch->charge->is_reference,
            );
        }

        return response()->json($chargesDetailArray);
    }

    public function getPlotChargesByNonPeriod(Request $request)
    {

        $chargesDetailArray = array();
        $year = $request->input('year');
        $allotee = Allotee::find($request->input('allotee'));
        $plotCharges = PlotCharges::with('charge')
            ->where('year', $year)
            ->where('size_id', $allotee->size_id)
            ->where('is_period', 0)
            ->get();
        foreach ($plotCharges as $ch) {
            $chargesDetailArray[] = array(
                'id' => $ch->id,
                'name' => $ch->charge->name,
            );
        }

        return response()->json($chargesDetailArray);
    }

    public function getPlotChargesByViolation(Request $request)
    {

        $chargesDetailArray = array();
        $year = $request->input('year');
        $allotee = Allotee::find($request->input('allotee'));
        $plotCharges = PlotCharges::with('charge')
            ->where('year', $year)
            ->where('size_id', $allotee->size_id)
            ->where('is_period', 0)
            ->where('is_open', 1)
            ->get();
        foreach ($plotCharges as $ch) {
            $chargesDetailArray[] = array(
                'id' => $ch->id,
                'name' => $ch->charge->name,
                'amount' => $ch->amount,
            );
        }

        return response()->json($chargesDetailArray);
    }


    public function getPlotChargesBySize(Request $request)
    {

        $chargesDetailArray = array();
        $year = $request->input('year');
        $size_id = $request->input('size_id');
//        dd($request->all());
        if($size_id == 0 ){
            $plotCharges = PlotCharges::with('charge')
                ->where('year', $year)
                ->where('is_period', 1)
                ->groupBy('charge_id')
                ->get();

        }else{
            $plotCharges = PlotCharges::with('charge')
                ->where('year', $year)
                ->where('size_id', $size_id)
                ->where('is_period', 1)
                ->get();

        }
        foreach ($plotCharges as $ch) {
            $chargesDetailArray[] = array(
                'id' => $ch->id,
                'name' => $ch->charge->name,
            );
        }

        return response()->json($chargesDetailArray);
    }

    public function changeStatus(Request $request)
    {
        $plotCharges = PlotCharges::find($request->id);
        $plotCharges->is_active = $request->status;
        $plotCharges->save();

        return response()->json(['success' => 'Status has been changed.']);
    }

    public function delete(Request $request)
    {
        PlotCharges::find($request->id)->delete();
        return response()->json(['success' => 'Record has been deleted.']);
    }
}
