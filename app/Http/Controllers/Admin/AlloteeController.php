<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAllotee;
use App\Http\Requests\UpdateAllotee;
use App\Http\Traits\AlloteeTrait;
use App\Mail\OtpMail;
use App\Models\Allotee;
use App\Models\AlloteeEducationalSubject;
use App\Models\AlloteeIndustry;
use App\Models\AlloteeSkill;
use App\Models\Invoice;
use App\Models\Sector;
use App\Models\Size;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AlloteeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:view-allotee'])->only(['show']);
        $this->middleware(['permission:edit-allotee'])->only(['edit', 'update']);
        $this->middleware(['permission:create-allotee'])->only(['index', 'store']);
        $this->middleware(['permission:delete-allotee'])->only(['changeStatus', 'delete']);
    }

    public function show(Request $request)
    {
        $title = 'Allotee';

        if ($request->ajax()) {
            $allotees = Allotee::with('size', 'sector', 'type'); // Added 'type' if it's a relationship as per your column

            return Datatables::of($allotees)
                ->filter(function ($instance) use ($request) {
                    $searchTerm = $request->get('search');
                    $sectorId = $request->get('sector_id');     // Get sector_id from request
                    $plotSizeId = $request->get('plot_size_id'); // Get plot_size_id from request

                    // Apply global search term
                    if (!empty($searchTerm)) {
                        $instance->where(function ($query) use ($searchTerm) {
                            $query->where('plot_no', 'like', '%' . $searchTerm . '%')
                                ->orWhere('name', 'like', '%' . $searchTerm . '%')
                                ->orWhereHas('sector', function ($query) use ($searchTerm) {
                                    $query->where('name', 'like', '%' . $searchTerm . '%');
                                })
                                ->orWhereHas('size', function ($query) use ($searchTerm) {
                                    $query->where('name', 'like', '%' . $searchTerm . '%');
                                });
                        });
                    }

                    // Apply sector filter
                    if (!empty($sectorId)) {
                        $instance->where('sector_id', $sectorId);
                    }

                    // Apply plot size filter
                    if (!empty($plotSizeId)) {
                        $instance->where('size_id', $plotSizeId); // Assuming 'size_id' is the foreign key
                    }
                })
                ->addColumn('code', function ($row) {
                    return $row->id;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('plot_no', function ($row) {
                    return $row->plot_no;
                })
                ->addColumn('sector', function ($row) {
                    return $row->sector->name ?? '';
                })
                ->addColumn('plot_size', function ($row) {
                    return $row->size->name ?? '';
                })
                ->addColumn('type', function ($row) {
                    return $row->type->name ?? '';
                })
                ->addColumn('status', function ($row) {
                    return $row->is_active == 1 ? 'Active' : 'Inactive';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('edit-allotee', $row->id);
                    $changeStatusUrl = route('changeStatus-allotee');
                    $deleteURl = route('delete-allotee');
                    $isActive = '';
                    $isInactive = '';
                    if ($row->is_active == 1) {
                        $isActive = 'd-none';
                        $activeTitle = 'Suspend Record';
                    } else {
                        $isInactive = 'd-none';
                        $activeTitle = 'Active Record';
                    }


                    $editBtn =
                        '
                              <a title="Edit" href="' . $editUrl . '"  class=" text-primary mr-1 ' . $editUrl . ' "><i- class="far fa-edit"></i></a>
                              <a  data-url="' . $changeStatusUrl . '"  data-status="0" data-label="inactive" data-id="' . $row->id . '" title="' . $activeTitle . '"    class="text-danger  change-status-record mr-1  ' . $isActive . '"><i- class="fas fa-exchange-alt"></i></a>
                             <a   data-url=""  data-status="1" data-label="active" data-id="' . $row->id . '" title="' . $activeTitle . '"    class="text-success  change-status-record mr-1  ' . $isInactive . '"><i- class="fas fa-exchange-alt"></i></a>
                             <a  data-url="' . $deleteURl . '"  data-status="1" data-label="delete" data-id="' . $row->id . '" title="Delete Record"    class="text-danger  change-status-record mr-1"><i- class="fas fa-trash-alt"></i></a>

                              ';

                    return $editBtn;
                })
                ->rawColumns(['action', 'checkbox', 'invoice_number'])
                ->make(true);
        }

        // For the initial page load, get all sectors and plot sizes to populate the filters
        $sectors = Sector::all(); // Assuming you have a Sector model
        $plotSizes = Size::all(); // Assuming you have a Size model for plot sizes

        return view('backend.allotees.index', compact('title', 'sectors', 'plotSizes'));
    }

    public function indexTransfer()
    {
        $title = 'Transfer Allotee';
        $sectors = Sector::all();
        $sizes = Size::all();
        $types = Type::all();
        return view('backend.allotees.create_transfer', compact('title', 'sectors', 'sizes', 'types'));
    }

    public function index()
    {
        $title = 'Allotee';
        $sectors = Sector::all();
        $sizes = Size::all();
        $types = Type::all();

        return view('backend.allotees.create', compact('title', 'sectors', 'sizes', 'types'));
    }


    public function store(Request $request, Allotee $allotee)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'guardian_name' => 'required|max:255',
            'email' => 'sometimes',
//            'phone_no' => 'required|max:255',
            'plot_no' => 'required',
            'sector_id' => 'required',
            'size_id' => 'required',
            'type_id' => 'required',
//            'account_no' => 'sometimes',
            'contact_person_name' => 'sometimes',
            'address' => 'required',
            'arrears' => 'sometimes',
        ]);

        if ($validator->fails()) {

            return redirect(route('add-allotee'))
                ->withErrors($validator)
                ->withInput();

        }

        $validatedData = $validator->validated();
        $allotee->create($validatedData);
        if (($allotee)) {

            return redirect()->route('show-allotee')->with('success', 'Allotee Added Successfully');

        } else {

            return redirect()->route('show-allotee')->with('error', 'Some thing Went Wrong');

        }
    }

    public function storeTransfer(Request $request, Allotee $allotee)
    {

//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'new_name' => 'required|max:255',
            'guardian_name' => 'required|max:255',
            'new_guardian_name' => 'required|max:255',
            'email' => 'sometimes',
//            'phone_no' => 'required|max:255',
//            'new_phone_no' => 'required|max:255',
            'plot_no' => 'required',
            'sector_id' => 'required',
            'size_id' => 'required',
            'type_id' => 'required',
//            'account_no' => 'sometimes',
//            'new_account_no' => 'sometimes',
            'contact_person_name' => 'sometimes',
            'address' => 'required',
            'new_address' => 'required',
            'arrears' => 'sometimes',
        ]);

        if ($validator->fails()) {

            return redirect(route('add-allotee'))
                ->withErrors($validator)
                ->withInput();

        }

        $validatedData = $validator->validated();
//        dd($validatedData);
        $oldAlloteeData = array(
            'name' => $validatedData['name'],
            'guardian_name' => $validatedData['guardian_name'],
//            'phone_no'=> $validatedData['phone_no'],
            'plot_no' => $validatedData['plot_no'],
            'sector_id' => $validatedData['sector_id'],
            'size_id' => $validatedData['size_id'],
            'type_id' => $validatedData['type_id'],
//            'account_no'=> $validatedData['account_no'],
            'address' => $validatedData['address'],
            'is_active' => 0,
        );
        $allotee->create($oldAlloteeData);

        $newAlloteeData = array(
            'name' => $validatedData['new_name'],
            'guardian_name' => $validatedData['new_guardian_name'],
//            'phone_no'=> $validatedData['new_phone_no'],
            'plot_no' => $validatedData['plot_no'],
            'sector_id' => $validatedData['sector_id'],
            'size_id' => $validatedData['size_id'],
            'type_id' => $validatedData['type_id'],
//            'account_no'=> $validatedData['new_account_no'],
            'address' => $validatedData['new_address'],
            'arrears' => $validatedData['arrears'],
        );

        $allotee->create($newAlloteeData);

        if (($allotee)) {

            return redirect()->route('show-allotee')->with('success', 'Allotee Added Successfully');

        } else {

            return redirect()->route('show-allotee')->with('error', 'Some thing Went Wrong');

        }
    }


    public function edit($id)
    {
        $allotee = Allotee::find($id);
        $title = 'Allotee';
        $sectors = Sector::all();
        $sizes = Size::all();
        $types = Type::all();

        return view('backend.allotees.edit', compact('title', 'sectors', 'sizes', 'allotee', 'types'));
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
            'name' => 'required|max:255',
            'guardian_name' => 'required|max:255',
            'email' => 'sometimes',
//            'phone_no' => 'required|max:255',
            'plot_no' => 'required',
            'sector_id' => 'required',
            'size_id' => 'required',
            'type_id' => 'required',
//            'account_no' => 'sometimes',
            'contact_person_name' => 'sometimes',
            'address' => 'required',
            'arrears' => 'sometimes',
        ]);

        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.

        }

        $validatedData = $validator->validated();
        $allotee = Allotee::find($id);
        $alloteeResponse = $allotee->update($validatedData);

        if (isset($alloteeResponse)) {

            return redirect()->route('show-allotee')->with('success', 'Allotee Updated Successfully');

        } else {

            return redirect()->route('show-allotee')->with('error', 'Some thing Went Wrong');

        }

    }

    public function changeStatus(Request $request)
    {
        $allotee = Allotee::find($request->id);
        $allotee->is_active = $request->status;
        $allotee->save();

        return response()->json(['success' => 'Status has been changed.']);
    }

    public function delete(Request $request)
    {
        $allotee = Allotee::find($request->id);
        $allotee->delete();

        return response()->json(['success' => 'Record has been deleted.']);
    }


}
