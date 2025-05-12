<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:view-size'])->only(['index']);
        $this->middleware(['permission:edit-size'])->only(['edit', 'update']);
        $this->middleware(['permission:create-size'])->only(['create', 'store']);
        $this->middleware(['permission:delete-size'])->only(['changeStatus','delete']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Size';
        $query = Size::query();
        $sizes = $query->orderBy('name', 'ASC')->get();
        $provinces = NULL;
         $charges = NULL;
         return view('backend.sizes.index', compact('title', 'sizes' , 'provinces','charges'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Size $size)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sizes,name|max:255',
            'value' => 'required|max:255',
        ]);

        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.

        }

        $validatedData = $validator->validated();
        $size->name = $validatedData['name'];
        $size->value = $validatedData['value'];
        $size->is_active = 1;
        $size->save();

        if (isset($size->id)) {
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
        $size = Size::find($request->id);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.

        }

        $validatedData = $validator->validated();
        $size->name = $validatedData['name'];
        $size->value = $validatedData['value'];
        $size->save();

        if (isset($size->id)) {
            return response()->json(['success' => 'Record has been Updated Successfully.']);
        } else {
            return response()->json(['error' => 'Some thing Went Wrong']);
        }

    }


    public function getSizes(Request $request)
    {
        $size = Size::find($request->id);
        return response()->json(['responseData' => $size]);
    }

    public function changeStatus(Request $request)
    {
        $size = Size::find($request->id);
        $size->is_active = $request->status;
        $size->save();

        return response()->json(['success' => 'Status has been changed.']);
    }
    public function delete(Request $request)
    {
        Size::find($request->id)->delete();
        return response()->json(['success' => 'Record has been deleted.']);
    }
}
