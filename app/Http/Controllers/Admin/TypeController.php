<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:view-type'])->only(['index']);
        $this->middleware(['permission:edit-type'])->only(['edit', 'update']);
        $this->middleware(['permission:create-type'])->only(['create', 'store']);
        $this->middleware(['permission:delete-type'])->only(['changeStatus','delete']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Type';
        $query = Type::query();
        $types = $query->orderBy('name', 'ASC')->get();
        $provinces = NULL;
         $charges = NULL;
         return view('backend.types.index', compact('title', 'types' , 'provinces','charges'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Type $type)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:types,name|max:255',
        ]);

        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.

        }

        $validatedData = $validator->validated();
        $type->name = $validatedData['name'];
        $type->is_active = 1;
        $type->save();

        if (isset($type->id)) {
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
        $type = Type::find($request->id);

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
        $type->name = $validatedData['name'];
        $type->save();

        if (isset($type->id)) {
            return response()->json(['success' => 'Record has been Updated Successfully.']);
        } else {
            return response()->json(['error' => 'Some thing Went Wrong']);
        }

    }


    public function getTypes(Request $request)
    {
        $type = Type::find($request->id);
        return response()->json(['responseData' => $type]);
    }

    public function changeStatus(Request $request)
    {
        $type = Type::find($request->id);
        $type->is_active = $request->status;
        $type->save();

        return response()->json(['success' => 'Status has been changed.']);
    }
    public function delete(Request $request)
    {
        Type::find($request->id)->delete();
        return response()->json(['success' => 'Record has been deleted.']);
    }
}
