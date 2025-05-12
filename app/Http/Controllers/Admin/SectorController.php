<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:view-sector'])->only(['index']);
        $this->middleware(['permission:edit-sector'])->only(['edit', 'update']);
        $this->middleware(['permission:create-sector'])->only(['create', 'store']);
        $this->middleware(['permission:delete-sector'])->only(['changeStatus','delete']);
    }


    public function index()
    {
        $title = 'Sector';
        $query = Sector::query();
        $sectors = $query->orderBy('name', 'ASC')->get();
        $provinces = NULL;
        $sizes = NULL;
        $charges = NULL;
        return view('backend.sectors.index', compact('title', 'charges', 'sectors', 'provinces', 'sizes'));
    }

    public function store(Request $request, Sector $sector)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sectors,name|max:255',
        ]);

        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.

        }

        $validatedData = $validator->validated();
        $sector->name = $validatedData['name'];
        $sector->is_active = 1;
        $sector->save();

        if (isset($sector->id)) {
            return response()->json(['success' => 'Record has been Added Successfully.']);
        } else {
            return response()->json(['error' => 'Some thing Went Wrong']);
        }

    }

    public function update(Request $request)
    {
        $sector = Sector::find($request->id);

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
        $sector->name = $validatedData['name'];
        $sector->save();

        if (isset($sector->id)) {
            return response()->json(['success' => 'Record has been Updated Successfully.']);
        } else {
            return response()->json(['error' => 'Some thing Went Wrong']);
        }

    }

    public function getSectors(Request $request)
    {
        $sector = Sector::find($request->id);
        return response()->json(['responseData' => $sector]);
    }

    public function changeStatus(Request $request)
    {
        $sector = Sector::find($request->id);
        $sector->is_active = $request->status;
        $sector->save();

        return response()->json(['success' => 'Status has been changed.']);
    }

    public function delete(Request $request)
    {
        Sector::find($request->id)->delete();
        return response()->json(['success' => 'Record has been deleted.']);
    }
}
