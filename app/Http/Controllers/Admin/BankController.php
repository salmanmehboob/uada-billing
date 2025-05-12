<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Charge;
use App\Models\ChargeType;
use App\Models\Bank;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:view-bank'])->only(['index']);
        $this->middleware(['permission:edit-bank'])->only(['edit', 'update']);
        $this->middleware(['permission:create-bank'])->only(['create', 'store']);
        $this->middleware(['permission:delete-bank'])->only(['changeStatus','delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $title = 'Bank';
        $bank = Bank::all();

        return view('backend.bank.index', compact('title',
            'bank'));
    }

    public function index()
    {
        $title = 'Bank';
        return view('backend.bank.create',  compact('title'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'branch' => 'required',
            'account_no' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect(route('add-bank'))
                ->withErrors($validator)
                ->withInput();

        }

        $validatedData = $validator->validated();
        $bank = Bank::create($validatedData);

        if (isset($bank)) {

            return redirect()->route('show-bank')->with('success', 'Record has been Added Successfully');

        } else {
            return redirect()->back()->with('error', 'Some thing Went Wrong');
        }

    }

    public function edit($id)
    {
        $title = 'Bank';
        $bank = Bank::find($id);

        return view('backend.bank.edit',  compact('title',
            'bank'));
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
        $bank = Bank::find($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'branch' => 'required',
            'account_no' => 'required',

        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();

        }

        $validatedData = $validator->validated();
        $bank->name = $validatedData['name'];
        $bank->branch = $validatedData['branch'];
        $bank->account_no = $validatedData['account_no'];
        $bank->save();

        if (isset($bank->id)) {

            return redirect()->route('show-bank')->with('success', 'Record has been Updated Successfully');

        } else {
            return redirect()->back()->with('error', 'Some thing Went Wrong');
        }

    }


    public function getBank(Request $request)
    {
        $bank = Bank::find($request->id);
        return response()->json(['responseData' => $bank]);
    }

    public function changeStatus(Request $request)
    {
        $bank = Bank::find($request->id);
        $bank->is_active = $request->status;
        $bank->save();

        return response()->json(['success' => 'Status has been changed.']);
    }

    public function delete(Request $request)
    {
        Bank::find($request->id)->delete();
        return response()->json(['success' => 'Record has been deleted.']);
    }
}
