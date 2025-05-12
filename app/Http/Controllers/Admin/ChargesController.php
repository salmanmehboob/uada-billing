<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Charge;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChargesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:view-charges'])->only(['index']);
        $this->middleware(['permission:edit-charges'])->only(['edit', 'update']);
        $this->middleware(['permission:create-charges'])->only(['create', 'store']);
        $this->middleware(['permission:delete-charges'])->only(['changeStatus', 'delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Charges';
        $query = Charge::query();
        $charges = $query->orderBy('name', 'ASC')->get();
        $provinces = NULL;
        $sizes = NULL;
        return view('backend.charges.index', compact('title', 'charges', 'provinces', 'sizes'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Charge $charge)
    {

//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:charges,name|max:255',
        ]);

        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.

        }

        $validatedData = $validator->validated();
        $charge->name = $validatedData['name'];
        $charge->is_possession = $request->possession === 'possession';
        $charge->is_transfer = $request->transfer === 'transfer';
        $charge->is_reference = $charge->is_transfer && $charge->is_possession;
        $charge->is_active = 1;
        $charge->save();

        if (isset($charge->id)) {
            return response()->json(['success' => 'Record has been Added Successfully.']);
        } else {
            return response()->json(['error' => 'Some thing Went Wrong']);
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $charge = Charge::find($request->id);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.

        }

        $validatedData = $validator->validated();

        $charge->name = $validatedData['name'];
        $charge->is_possession = $request->possession === 'possession';
        $charge->is_transfer = $request->transfer === 'transfer';
        $charge->is_reference = $charge->is_transfer && $charge->is_possession;
        $charge->save();

        if (isset($charge->id)) {
            return response()->json(['success' => 'Record has been Updated Successfully.']);
        } else {
            return response()->json(['error' => 'Some thing Went Wrong']);
        }

    }


    public function getCharges(Request $request)
    {
        $charge = Charge::find($request->id);
        return response()->json(['responseData' => $charge]);
    }

    public function changeStatus(Request $request)
    {
        $charge = Charge::find($request->id);
        $charge->is_active = $request->status;
        $charge->save();

        return response()->json(['success' => 'Status has been changed.']);
    }

    public function delete(Request $request)
    {
        Charge::find($request->id)->delete();
        return response()->json(['success' => 'Record has been deleted.']);
    }
}
