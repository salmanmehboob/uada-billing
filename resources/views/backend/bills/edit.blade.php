@extends('layouts.app')

@section('content')
    <!--**********************************
            Content body start
        ***********************************-->

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold"></span>{{$title}}
                </h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home </a>
                    <a href="/show-bill" class="breadcrumb-item">Bills</a>
                    <span class="breadcrumb-item active">{{$title}}</span>
                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>
    </div>
    <!-- /page header -->

    <!-- Content area -->
    <div class="content">

        <!-- Form validation -->
        <div class="card">

            <!-- Registration form -->
            <form action="{{route('update-bill', $bill->id)}}" method="post"
                  name="bill_registration" class="flex-fill form-validate-jquery">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-0">
                            <div class="card-body">

                                <div class="mb-3">
                                    <h1 class="mb-0">Bill Generation Setup</h1>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Select Bill Type <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Bill Type" required
                                                    name="bill_type_id" id="bill_type_id"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option></option>
                                                <option value="tr" {{$bill->is_transfer ? 'selected' : ''}}>Transfer
                                                </option>
                                                <option value="ps" {{$bill->is_possession ? 'selected' : ''}}>
                                                    Possession
                                                </option>


                                                <option value="p" {{$bill->is_time_period ? 'selected' : ''}}>Time
                                                    Period
                                                </option>
                                                <option value="np" {{$bill->is_non_time_period ? 'selected' : ''}}>Non
                                                    Time Period
                                                </option>
                                                <option value="v" {{$bill->is_violation ? 'selected' : ''}}>Violation
                                                </option>
                                                <option value="nu" {{$bill->is_non_user ? 'selected' : ''}}>Non User
                                                </option>
                                                <option value="sd" {{$bill->is_stamp_duty ? 'selected' : ''}}>Stamp
                                                    Duty
                                                </option>

                                            </select>
                                            @if ($errors->has('bill_type_id'))
                                                <span
                                                    class="text-danger">{{ $errors->first('bill_type_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Select Allotee <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Allotee" required
                                                    name="allotee_id" id="allotee_id"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option></option>
                                                @foreach($allotees as $key =>  $row)
                                                    <option
                                                        {{($bill->allotee_id == $row->id) ? 'selected' : ''}}
                                                        value="{{$row->id}}">{{$row->name . ' ' . $row->plot_no ?? '' .' ' . $row->size->name ?? '' . ' ' . $row->sector->name ?? ''}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('allotee_id'))
                                                <span
                                                    class="text-danger">{{ $errors->first('allotee_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Select Bank <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Bank" required
                                                    name="bank_id" id="bank_id"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option></option>
                                                @foreach($banks as $key =>  $row)
                                                    <option
                                                        {{($bill->bank_id == $row->id) ? 'selected' : ''}}

                                                        value="{{$row->id}}">{{$row->name .' '. $row->branch.' ' . $row->account_no}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('bank_id'))
                                                <span
                                                    class="text-danger">{{ $errors->first('bank_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div
                                        class="col-md-4 {{ ($bill->is_stamp_duty || $bill->is_non_user) ? 'd-none' : '' }}">

                                        <label class="col-form-label  ">Financial Year <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Financial Year"
                                                    name="year" id="year"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option></option>
                                                @php
                                                    $currentYear = date('Y');
                                                     $startYear = $currentYear - 29;
                                                      $currentMonth = date('m');
                                                         if ($currentMonth > 6) {
                                                             $currentYear++;
                                                             $startYear++;
                                                         }
                                                @endphp
                                                @for ($year = $currentYear; $year >= $startYear; $year--)
                                                    <option
                                                        {{($bill->year == $year) ? 'selected' : ''}}
                                                        value="{{ $year }}">{{ $year }}</option>
                                                @endfor

                                            </select>
                                            @if ($errors->has('year'))
                                                <span
                                                    class="text-danger">{{ $errors->first('year') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                <div class="" id="charges">
                                    <div class="mb-3 mt-3">
                                        <h1 class="mb-0">Generate Charges to Allotee</h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group form-group-feedback form-group-feedback-right"
                                                 id="checkboxContainer">

                                                @foreach($chargesDetailArray as $row)
                                                    <div class="form-inline form-check form-check-right" style="margin-top: 1.3rem!important;">
                                                        <label class="form-check-label">
                                                            {{$row['name'] . ' (' . $row['amount'] . ')'}}
                                                            <input type="checkbox" name="charges[]"
                                                                   data-reference="{{$row['is_reference']}}"
                                                                   data-name="{{$row['name']}}"
                                                                   data-rid="{{$row['reference_id']}}"
                                                                   {{($row['selected'] == true) ? 'checked' : ''}}
                                                                   class="form-check-input-styled charge-checkbox"
                                                                   value="{{$row['id']}}"
                                                                   data-fouc>

                                                        </label>

                                                    </div>
                                                    @if($row['is_reference'] == true)
                                                        <select id="select2Dropdown_{{$row['id']}}"
                                                                class="select2 form-control"
                                                                name="bill_{{$row['id']}}"></select>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        @if($bill->is_violation)
                                            <div class="col-md-3">
                                                <div class="form-group form-group-feedback form-group-feedback-right"
                                                     id="chargeAmountContainer">
                                                    @foreach($chargesDetailArray as $key => $row)
                                                        <div class="mt-2">
                                                            @if(isset($billCharges[$key]))
                                                                <input type="text" name="charge_amount[]"
                                                                       class="form-control total-charge-amount"
                                                                       id="charge_amount_{{$billCharges[$key]['plot_charge_id']}}"
                                                                       placeholder="Total Charge Amount"
                                                                       value="{{$billCharges[$key]['amount']}}">
                                                            @else
                                                                <input type="text" name="charge_amount[]"
                                                                       class="form-control total-charge-amount"
                                                                       id="charge_amount_{{$row['id']}}"
                                                                       placeholder="Total Charge Amount"
                                                                       disabled="disabled"
                                                                       value="">
                                                            @endif
                                                        </div>
                                                    @endforeach

                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-group-feedback form-group-feedback-right"
                                                     id="sftContainer">
                                                    @foreach($chargesDetailArray as $key => $row)
                                                        <div class="mt-2">
                                                            @if(isset($billCharges[$key]))
                                                                <input type="text" name="total_sft[]"
                                                                       class="form-control total-sft"
                                                                       id="total_sft_{{$billCharges[$key]['plot_charge_id']}}"
                                                                       placeholder="Total SFT"
                                                                       value="{{$billCharges[$key]['total_violation']}}">
                                                            @else
                                                                <input type="text" name="total_sft[]"
                                                                       class="form-control total-sft"
                                                                       id="total_sft_{{$row['id']}}"
                                                                       disabled="disabled"
                                                                       placeholder="Total SFT"
                                                                       value="">
                                                            @endif
                                                        </div>
                                                    @endforeach

                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-group-feedback form-group-feedback-right"
                                                     id="totalContainer">
                                                    @foreach($chargesDetailArray as $key => $row)
                                                        <div class="mt-2">
                                                            @if(isset($billCharges[$key]))
                                                                <input type="text" name="total_amount[]"
                                                                       class="form-control"
                                                                       readonly="readonly"
                                                                       id="total_amount_{{$billCharges[$key]['plot_charge_id']}}"
                                                                       placeholder="Total Amount"
                                                                       value="{{$billCharges[$key]['total']}}">
                                                            @else
                                                                <input type="text" name="total_amount[]"
                                                                       class="form-control"
                                                                       readonly="readonly"
                                                                       disabled="disabled"
                                                                       id="total_amount_{{$row['id']}}"
                                                                       placeholder="Total Amount"
                                                                       value="">
                                                            @endif
                                                        </div>
                                                    @endforeach


                                                </div>

                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row period-base {{($bill->is_time_period )? '' : 'd-none'}}">
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">From Month<span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Month"
                                                    name="from_month" id="from_month"
                                                    class="form-control select-search mb-3 month"
                                                    data-fouc>
                                                <option></option>
                                                @foreach($months as $key =>  $row)
                                                    <option
                                                        {{($bill->from_month == $row->id) ? 'selected' : ''}}

                                                        value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('from_month'))
                                                <span
                                                    class="text-danger">{{ $errors->first('from_month') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">To Month<span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Month"
                                                    name="to_month" id="to_month"
                                                    class="form-control select-search mb-3 month"
                                                    data-fouc>
                                                <option></option>
                                                @foreach($months as $key =>  $row)
                                                    <option
                                                        {{($bill->to_month == $row->id) ? 'selected' : ''}}
                                                        value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('to_month'))
                                                <span
                                                    class="text-danger">{{ $errors->first('to_month') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                <div class="{{($bill->is_non_user )? '' : 'd-none'}}" id="row-container-non-user">
                                    @if($bill->is_non_user )
                                        @foreach($bill->billCharges as $chargeKey => $charges)
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">From Month<span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <select data-placeholder="Select Month" required
                                                                name="from_month[]"
                                                                class="form-control select mb-3 non-user-month"
                                                                data-fouc>
                                                            <option></option>
                                                            @foreach($months as $key =>  $row)
                                                                <option
                                                                    {{($charges->from_month == $row->id) ? 'selected' : ''}}
                                                                    value="{{$row->id}}">{{$row->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('from_month'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('from_month') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">From Year <span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <select data-placeholder="Select From Year" required
                                                                name="from_year[]"
                                                                class="form-control select mb-3 non-user-year"
                                                                data-fouc>
                                                            <option></option>
                                                            @foreach ($years  as $row)
                                                                <option
                                                                    {{($charges->from_year == $row) ? 'selected' : ''}} value="{{ $row }}">{{ $row }}</option>
                                                            @endforeach

                                                        </select>
                                                        @if ($errors->has('from_year'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('from_year') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">To Month<span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <select data-placeholder="Select Month" required
                                                                name="to_month[]"
                                                                class="form-control select mb-3 non-user-month"
                                                                data-fouc>
                                                            <option></option>
                                                            @foreach($months as $key =>  $row)
                                                                <option
                                                                    {{($charges->to_month == $row->id) ? 'selected' : ''}}
                                                                    value="{{$row->id}}">{{$row->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('to_month'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('to_month') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">To Year <span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <select data-placeholder="Select To Year" required
                                                                name="to_year[]"
                                                                class="form-control select mb-3 non-user-year"
                                                                data-fouc>
                                                            <option></option>

                                                            @foreach ($years  as $row)
                                                                <option
                                                                    {{($charges->to_year == $row) ? 'selected' : ''}} value="{{ $row }}">{{ $row }}</option>
                                                            @endforeach

                                                        </select>
                                                        @if ($errors->has('to_year'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('to_year') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">Rate <span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <input type="text" class="form-control charge-amount-non-user"
                                                               name="charge_amount[]" value="{{$charges->amount}}">
                                                        @if ($errors->has('charge_amount'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('charge_amount') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2 {{$chargeKey == 0 ? 'd-none' : ''}}">
                                                    <label class="col-form-label"></label>
                                                    <button type="button"   title="Delete Record" class="btn btn-danger remove-row" style="margin-top: 36px"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-10 {{($bill->is_non_user )? '' : 'd-none'}} " id="row-container-btn">
                                    <button type="button" id="add-more"
                                            title="Add More" class="btn btn-primary float-right"><i
                                            class="fas fa-plus"></i></button>
                                </div>


                                <div class="{{($bill->is_stamp_duty )? '' : 'd-none'}}" id="row-container-stamp-duty">
                                    @if($bill->is_stamp_duty )
                                        @foreach($bill->billCharges as $chargeKey =>  $charges)
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">From Month<span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <select data-placeholder="Select Month" required
                                                                name="from_month[]"
                                                                class="form-control select mb-3 month"
                                                                data-fouc>
                                                            <option></option>
                                                            @foreach($months as $key =>  $row)
                                                                <option
                                                                    {{($charges->from_month == $row->id) ? 'selected' : ''}}
                                                                    value="{{$row->id}}">{{$row->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('from_month'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('from_month') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">From Year <span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <select data-placeholder="Select From Year" required
                                                                name="from_year[]"
                                                                class="form-control select mb-3 "
                                                                data-fouc>
                                                            <option></option>
                                                            @foreach ($years  as $row)
                                                                <option
                                                                    {{($charges->from_year == $row) ? 'selected' : ''}} value="{{ $row }}">{{ $row }}</option>
                                                            @endforeach

                                                        </select>
                                                        @if ($errors->has('from_year'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('from_year') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">To Month<span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <select data-placeholder="Select Month" required
                                                                name="to_month[]"
                                                                class="form-control select mb-3 month"
                                                                data-fouc>
                                                            <option></option>
                                                            @foreach($months as $key =>  $row)
                                                                <option
                                                                    {{($charges->to_month == $row->id) ? 'selected' : ''}}
                                                                    value="{{$row->id}}">{{$row->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('to_month'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('to_month') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">To Year <span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <select data-placeholder="Select To Year" required
                                                                name="to_year[]"
                                                                class="form-control select mb-3 "
                                                                data-fouc>
                                                            <option></option>

                                                            @foreach ($years  as $row)
                                                                <option
                                                                    {{($charges->to_year == $row) ? 'selected' : ''}} value="{{ $row }}">{{ $row }}</option>
                                                            @endforeach

                                                        </select>
                                                        @if ($errors->has('to_year'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('to_year') }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">No of Transfer <span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <input type="text" class="form-control" name="no_of_transfer[]"
                                                               value="{{$charges->no_of_transfer}}">
                                                        @if ($errors->has('no_of_transfer'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('no_of_transfer') }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">Rate Per Marla <span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <input type="text" class="form-control" name="charge_amount[]"
                                                               value="{{$charges->amount}}">
                                                        @if ($errors->has('charge_amount'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('charge_amount') }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-12 {{$chargeKey == 0 ? 'd-none' : ''}}">
                                                    <label class="col-form-label"></label>
                                                    <button type="button"   title="Delete Record" class="btn btn-danger btn-sm float-right remove-row mb-1" ><i class="fas fa-trash"></i></button>
                                                </div>

                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-12 {{($bill->is_stamp_duty )? '' : 'd-none'}}"
                                     id="row-container-btn-stamp-duty">
                                    <button type="button" id="add-more-stamp-duty"
                                            title="Add More" class="btn btn-primary float-right"><i
                                            class="fas fa-plus"></i></button>
                                </div>

                                <div class="row">
                                    <div class="col-md-4  ">
                                        <label class="col-form-label col-md-4">Issue Date </label>
                                        <div class="col-md-12">
                                            <div class="input-group">
										<span class="input-group-prepend">
											<span class="input-group-text"><i class="icon-calendar22"></i></span>
										</span>
                                                <input type="text" name="issue_date"
                                                       class="form-control pickadate-year"
                                                       value="{{showDatePicker($bill->issue_date)}}">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-4  ">
                                        <label class="col-form-label col-md-4">Due Date </label>
                                        <div class="col-md-12">
                                            <div class="input-group">
										<span class="input-group-prepend">
											<span class="input-group-text"><i class="icon-calendar22"></i></span>
										</span>
                                                <input type="text" name="due_date"
                                                       class="form-control pickadate-year-future"
                                                       value="{{showDatePicker($bill->due_date)}}">
                                            </div>
                                        </div>
                                    </div>


                                </div>


                                <div class="row">
                                    <div class="col-md-12 p-3">
                                        <button type="submit" id="submitBtn"
                                                class="btn bg-teal-400 btn-labeled btn-labeled-right float-right">
                                            <b><i class="icon-database-edit2"></i></b> Update Bill
                                        </button>
                                    </div>

                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /registration form -->
    </div>
    <!-- /form validation -->

    <!-- /content area -->
    <!--**********************************
        Content body end
    ***********************************-->

@endsection

@push('script')
    <script src="{{asset('assets/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/inputs/touchspin.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/styling/switch.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/demo_pages/form_validation.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/demo_pages/form_select2.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/inputs/inputmask.js')}}"></script>


    <script src="{{asset('assets/global_assets/js/plugins/ui/moment/moment.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/pickers/daterangepicker.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/pickers/anytime.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/pickers/pickadate/picker.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/pickers/pickadate/picker.date.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/pickers/pickadate/picker.time.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/pickers/pickadate/legacy.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/demo_pages/picker_date.js')}}"></script>

    <script src="{{asset('assets/global_assets/js/demo_pages/form_checkboxes_radios.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.charge-checkbox').each(function () {
                handleCheckbox($(this));
            });

            function handleCheckbox(checkbox) {
                let selectedBilType = $('#bill_type_id').val();
                const reference = checkbox.data('reference');
                const name = checkbox.data('name');
                const checkboxId = checkbox.val();
                const allotee = $('#allotee_id').val();

                if (reference == 1) {

                    getdropdownvalues(checkbox, reference, name, checkboxId, allotee, selectedBilType);
                }
            }

            $('#bill_type_id').on('select2:select', function (e) {
                e.preventDefault();
                const bill_type_id = e.currentTarget.value; // Use e.currentTarget to get the selected value directly.

                if (bill_type_id === 'p') {
                    $('#row-container-non-user').addClass('d-none');
                    $('#row-container-btn').addClass('d-none');

                    $('.period-base').removeClass('d-none');
                    $('#from_month').prop('required', true);
                    $('#to_month').prop('required', true);

                    $('#yearDiv').removeClass('d-none');
                    $('#year').prop('required', true);

                    $('#from_month').prop('disabled', false);
                    $('#to_month').prop('disabled', false);

                    $('select[name="from_month[]"]').prop('required', false);
                    $('select[name="from_year[]"]').prop('required', false);
                    $('select[name="to_month[]"]').prop('required', false);
                    $('select[name="to_year[]"]').prop('required', false);

                    // hide stamp duty container
                    $('#row-container-stamp-duty').addClass('d-none');
                    $('#row-container-btn-stamp-duty').addClass('d-none');
                }


                if (bill_type_id === 'np') {
                    $('#row-container-non-user').addClass('d-none');
                    $('#row-container-btn').addClass('d-none');
                    $('.period-base').addClass('d-none');
                    $('#yearDiv').removeClass('d-none');
                    $('#year').prop('required', true);

                    $('select[name="from_month[]"]').prop('required', false);
                    $('select[name="from_year[]"]').prop('required', false);
                    $('select[name="to_month[]"]').prop('required', false);
                    $('select[name="to_year[]"]').prop('required', false);

                    // hide stamp duty container
                    $('#row-container-stamp-duty').addClass('d-none');
                    $('#row-container-btn-stamp-duty').addClass('d-none');
                }

                if (bill_type_id === 'v') {
                    $('#row-container-non-user').addClass('d-none');
                    $('#row-container-btn').addClass('d-none');
                    $('.period-base').addClass('d-none');
                    $('#yearDiv').removeClass('d-none');
                    $('#year').prop('required', true);

                    $('select[name="from_month[]"]').prop('required', false);
                    $('select[name="from_year[]"]').prop('required', false);
                    $('select[name="to_month[]"]').prop('required', false);
                    $('select[name="to_year[]"]').prop('required', false);

                    // hide stamp duty container
                    $('#row-container-stamp-duty').addClass('d-none');
                    $('#row-container-btn-stamp-duty').addClass('d-none');
                }

                if (bill_type_id == 'nu') {

                    // hide time period container
                    $('#row-container-common').addClass('d-none');
                    $('.period-base').addClass('d-none');

                    $('#yearDiv').addClass('d-none');
                    $('#year').prop('required', false);
                    $('#from_month').prop('disabled', true);
                    $('#to_month').prop('disabled', true);

                    $('#checkboxContainer').empty();
                    $('#chargeAmountContainer').empty();
                    $('#sftContainer').empty();
                    $('#totalContainer').empty();

                    // show non user container
                    $('#row-container-non-user').removeClass('d-none');
                    $('#row-container-btn').removeClass('d-none');

                    $('select[name="from_month[]"]').prop('required', true);
                    $('select[name="from_year[]"]').prop('required', true);
                    $('select[name="to_month[]"]').prop('required', true);
                    $('select[name="to_year[]"]').prop('required', true);

                    // hide stamp duty container
                    $('#row-container-stamp-duty').addClass('d-none');
                    $('#row-container-btn-stamp-duty').addClass('d-none');
                }

                if (bill_type_id === 'sd') {
                    // Add the 'disabled' attribute to the select elements
                    $('select.non-user-month').prop('disabled', true);
                    $('select.non-user-year').prop('disabled', true);
                    $('.charge-amount-non-user').prop('disabled', true);

                    // Remove required to the select elements
                    $('select.non-user-month').prop('required', false);
                    $('select.non-user-year').prop('required', false);


                    // hide the year drop down
                    $('#yearDiv').addClass('d-none');
                    $('#year').prop('required', false);

                    // hide period container
                    $('#row-container-common').addClass('d-none');
                    $('.period-base').addClass('d-none');

                    // hide non user container
                    $('#row-container-non-user').addClass('d-none');
                    $('#row-container-btn').addClass('d-none');

                    $('#row-container-stamp-duty').removeClass('d-none');
                    $('#row-container-btn-stamp-duty').removeClass('d-none');
                }

                if (bill_type_id === 'tr') {
                    $('#row-container-non-user').addClass('d-none');
                    $('#row-container-btn').addClass('d-none');
                    $('.period-base').addClass('d-none');
                    $('#yearDiv').removeClass('d-none');
                    $('#year').prop('required', true);

                    $('select[name="from_month[]"]').prop('required', false);
                    $('select[name="from_year[]"]').prop('required', false);
                    $('select[name="to_month[]"]').prop('required', false);
                    $('select[name="to_year[]"]').prop('required', false);

                    // hide stamp duty container
                    $('#row-container-stamp-duty').addClass('d-none');
                    $('#row-container-btn-stamp-duty').addClass('d-none');
                }
            });

            $('#year').on('select2:select', function (e) {
                e.preventDefault();
                const selectedData = e.params.data;
                const year = selectedData.text;
                const allotee = $('#allotee_id').val();
                const bill_type_id = $('#bill_type_id').val();

                if (bill_type_id == 'p' || bill_type_id == 'np' || bill_type_id == 'ps' || bill_type_id == 'tr') {
                    if (allotee.length == 0) {
                        alert('Please Select Allotee for plot charges  ');
                        return false;
                    }
                    $.ajax({
                        url: '{{ route('get-plotCharges') }}',
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        data: {'year': year, 'allotee': allotee},
                        success: function (data, status, xhr) {
                            const checkboxesData = data;

                            // Clear any existing checkboxes
                            $('#checkboxContainer').empty();

                            // Loop through the checkbox data and create/append checkboxes
                            checkboxesData.forEach(function (checkboxData) {
                                const label = $('<label>').addClass('form-check-label').text(checkboxData.name);

                                const checkbox = $('<input>').attr({
                                    type: 'checkbox',
                                    name: 'charges[]',
                                    class: 'form-check-input-styled',
                                    value: checkboxData.id,
                                });

                                const formCheck = $('<div>').addClass('form-check form-check-right')
                                    .append(label)
                                    .append(checkbox);

                                // Append the checkbox to the container
                                $('#checkboxContainer').append(formCheck);
                                $('.form-check-input-styled').uniform();
                                $('#chargeAmountContainer').empty();
                                $('#sftContainer').empty();
                                $('#totalContainer').empty();

                                // Check if is_reference is 1, then prepend the Select2 dropdown
                                if (checkboxData.is_reference == 1) {
                                    // Create the Select2 dropdown
                                    const select2Dropdown = $('<select>').attr({
                                        id: 'select2Dropdown_' + checkboxData.id,
                                        class: 'select2 form-control d-none',
                                        name: 'bill_' + checkboxData.id
                                    });

                                    // Append the Select2 dropdown after the checkbox on the same line
                                    formCheck.append(select2Dropdown);
                                }
                            });

                        },
                        error: function (jqXhr, textStatus, errorMessage) {
                        }
                    });
                }

                if (bill_type_id == 'v') {
                    $.ajax({
                        url: '{{ route('get-plotCharges') }}',
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        data: {'year': year, 'allotee': allotee},
                        success: function (data, status, xhr) {
                            const checkboxesData = data;

                            // Clear any existing checkboxes
                            $('#checkboxContainer').empty();

                            // Loop through the checkbox data and create/append checkboxes
                            checkboxesData.forEach(function (checkboxData) {
                                const label = $('<label>').addClass('form-check-label').text(checkboxData.name);

                                const checkbox = $('<input>').attr({
                                    type: 'checkbox',
                                    name: 'charges[]',
                                    class: 'form-check-input-styled',
                                    value: checkboxData.id,
                                });

                                const formCheck = $('<div>').addClass('form-check form-check-right')
                                    .append(label)
                                    .append(checkbox);

                                // Append the checkbox to the container
                                $('#checkboxContainer').append(formCheck);
                                $('.form-check-input-styled').uniform();
                            });

                        },
                        error: function (jqXhr, textStatus, errorMessage) {
                        }
                    });
                }


            });

            {{--$('.month').on('select2:select', function (e) {--}}
            {{--    e.preventDefault();--}}
            {{--    const selectedData = e.params.data;--}}
            {{--    // const from_month = selectedData.id;--}}
            {{--    const from_month = $('#from_month').val();--}}
            {{--    const to_month = $('#to_month').val();--}}
            {{--    const year = $('#year').val();--}}
            {{--    const allotee = $('#allotee_id').val();--}}
            {{--    const bill_type_id = $('#bill_type_id').val();--}}

            {{--    const checkedCheckboxes = $('input[name="charges[]"]:checked').map(function () {--}}
            {{--        return $(this).val();--}}
            {{--    }).get();--}}

            {{--    if (checkedCheckboxes.length == 0 && bill_type_id != 'sd') {--}}
            {{--        alert('Please Select Plot Charges');--}}
            {{--        $('.month').val(null).trigger('change');--}}

            {{--        return false;--}}
            {{--    }--}}
            {{--    if (allotee.length == 0) {--}}
            {{--        alert('Please Select Allotee for plot charges  ');--}}
            {{--        $('.month').val(null).trigger('change');--}}

            {{--        return false;--}}
            {{--    }--}}
            {{--    if (year.length == 0 && bill_type_id != 'sd') {--}}
            {{--        alert('Please Select year for plot charges  ');--}}
            {{--        $('.month').val(null).trigger('change');--}}

            {{--        return false;--}}
            {{--    }--}}

            {{--    $.ajax({--}}
            {{--        url: '{{ route('check-duplicate-bill') }}',--}}
            {{--        type: 'POST',--}}
            {{--        cache: false,--}}
            {{--        dataType: 'json',--}}
            {{--        data: {--}}
            {{--            'from_month': from_month,--}}
            {{--            'to_month': to_month,--}}
            {{--            'year': year,--}}
            {{--            'allotee': allotee,--}}
            {{--            'bill_type_id': bill_type_id,--}}
            {{--            'checkedCheckboxes': checkedCheckboxes--}}
            {{--        },--}}
            {{--        success: function (data, status, xhr) {--}}

            {{--            if (data.status == false) {--}}
            {{--                Swal.fire({--}}

            {{--                    title: data.message,--}}

            {{--                    // timer: 3000,--}}

            {{--                    showConfirmButton: true,--}}
            {{--                    onClose: () => {--}}

            {{--                        $('.month').val(null).trigger('change');--}}
            {{--                    }--}}

            {{--                });--}}


            {{--            }--}}


            {{--        },--}}
            {{--        error: function (jqXhr, textStatus, errorMessage) {--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}

            $('body').on('change', '.charge-checkbox', function (e) {
                e.preventDefault();
                handleCheckbox($(this));
            });

            function getdropdownvalues(checkbox, reference, name, checkboxId, allotee, selectedBilType) {
                let select2Dropdown = $('#select2Dropdown_' + checkboxId);
                let url;
                let selected;
                let referenceBillID = checkbox.data('rid');
                if (checkbox.prop('checked')) {
                    if (selectedBilType == 'tr' || selectedBilType == 'ps') {
                        if (name == 'Stamp Duty') {
                            url = '{{ route('get-stamp-duty-bill-dropdown') }}';
                        } else if (name == 'Non User Charges') {
                            url = '{{ route('get-non-user-bill-dropdown') }}';
                        }

                        if (url) {
                            $.ajax({
                                url: url,
                                type: 'POST',
                                cache: false,
                                dataType: 'json',
                                data: {
                                    'allotee': allotee,
                                },
                                success: function (data, status, xhr) {
                                    select2Dropdown.removeClass('d-none');
                                    select2Dropdown.empty();  // Clear existing options

                                    if (data.length > 0) {
                                        // Append options from the data array
                                        data.forEach(function (option) {
                                            console.log(option.id, referenceBillID)
                                            let selected = (option.id == referenceBillID) ? 'selected' : '';
                                            select2Dropdown.append($('<option>').text(option.name).val(option.id).prop('selected', selected));
                                        });
                                    } else {
                                        // If data is empty, append a default option
                                        select2Dropdown.append($('<option>').text('No Reference Bill found').val(''));
                                    }
                                },
                                error: function (jqXhr, textStatus, errorMessage) {
                                }
                            });
                        }
                    }
                } else {
                    // Checkbox is unchecked
                    // Hide the corresponding Select2 dropdown
                    select2Dropdown.addClass('d-none');
                }

            }


            {{--$('body').on('change', '.charge-checkbox', function (e) {--}}
            {{--    e.preventDefault();--}}
            {{--    let selectedBilType = $('#bill_type_id').val();--}}
            {{--    if (this.checked) {--}}

            {{--        if (selectedBilType == 'np') {--}}
            {{--            const checkedCheckboxes = $(this).val();--}}
            {{--            const year = $('#year').val();--}}
            {{--            const allotee = $('#allotee_id').val();--}}
            {{--            $.ajax({--}}
            {{--                url: '{{ route('check-duplicate-bill') }}',--}}
            {{--                type: 'POST',--}}
            {{--                cache: false,--}}
            {{--                dataType: 'json',--}}
            {{--                data: {--}}
            {{--                    'year': year,--}}
            {{--                    'allotee': allotee,--}}
            {{--                    'checkedCheckboxes': checkedCheckboxes--}}
            {{--                },--}}
            {{--                success: function (data, status, xhr) {--}}

            {{--                    if (data.status == false) {--}}
            {{--                        Swal.fire({--}}
            {{--                            title: data.message,--}}
            {{--                            showConfirmButton: true,--}}
            {{--                            onClose: () => {--}}

            {{--                                // $(this).unchecked();--}}
            {{--                            }--}}

            {{--                        });--}}


            {{--                    }--}}


            {{--                },--}}
            {{--                error: function (jqXhr, textStatus, errorMessage) {--}}
            {{--                }--}}
            {{--            });--}}
            {{--        }--}}
            {{--    }--}}

            {{--})--}}

            $('body').on('click', '.charge-checkbox', function () {
                const checkboxValue = $(this).val(); // Get the value of the clicked checkbox
                const totalChargeAmountInput = $('#charge_amount_' + checkboxValue); // Get the corresponding total_sft input element
                const totalSftInput = $('#total_sft_' + checkboxValue); // Get the corresponding total_sft input element
                const totalAmountInput = $('#total_amount_' + checkboxValue); // Get the corresponding total_amount input element

                // Toggle the disabled property based on the checkbox state
                totalChargeAmountInput.prop('disabled', !$(this).is(':checked'));
                totalSftInput.prop('disabled', !$(this).is(':checked'));
                totalAmountInput.prop('disabled', !$(this).is(':checked'));
            });

            // Event handler for changes in .total-charge-amount
            $('body').on('keyup change', '.total-charge-amount', function () {
                const id = $(this).attr('id').replace('charge_amount_', '');
                const chargeAmount = parseFloat($(this).val());
                const totalSftInput = $('#total_sft_' + id);
                const totalAmountInput = $('#total_amount_' + id);
                if (!isNaN(chargeAmount)) {
                    const totalSftValue = chargeAmount * parseFloat(totalSftInput.val());
                    totalAmountInput.val(totalSftValue.toFixed(2));
                } else {
                    totalAmountInput.val('');
                }
            });

            $('body').on('keyup change', '.total-sft', function () {
                // Get the ID of the corresponding charge_amount input
                const id = $(this).attr('id').replace('total_sft_', '');
                const chargeAmountInput = $('#charge_amount_' + id);
                const totalAmountInput = $('#total_amount_' + id);

                const chargeAmount = parseFloat(chargeAmountInput.val());
                const totalSftValue = parseFloat($(this).val());

                // Check if both values are valid numbers
                if (!isNaN(chargeAmount) && !isNaN(totalSftValue)) {
                    // Calculate the total-charge-amount value
                    const totalChargeAmount = chargeAmount * totalSftValue;

                    // Set the calculated total-charge-amount value
                    totalAmountInput.val(totalChargeAmount.toFixed(2));
                } else {
                    // Clear the total-charge-amount input if either value is not a valid number
                    totalAmountInput.val('');
                }
            });


            function loadSelect() {
                $('.select').select2();
            }

            /////////////////////////////////////FOR NON USER CHARGES///////////////////////////////////////////
            // Click event for the "Add More" button
            $('#add-more').on('click', function () {
                let monthOptions = @json($months);
                let yearOptions = @json($years);

                let fromMonthDropdown = generateMonthDropdown("from_month[]", monthOptions);
                let toMonthDropdown = generateMonthDropdown("to_month[]", monthOptions);
                let fromYearDropdown = generateYearDropdown("from_year[]", yearOptions);
                let toYearDropdown = generateYearDropdown("to_year[]", yearOptions);

                function generateMonthDropdown(inputName, monthOptions) {
                    let monthDropdownHtml = '<select data-placeholder="Select Month" required name="' + inputName + '" class="form-control select mb-3" data-fouc>' +
                        '<option></option>';
                    for (let i = 0; i < monthOptions.length; i++) {
                        monthDropdownHtml += '<option value="' + monthOptions[i].id + '">' + monthOptions[i].name + '</option>';
                    }
                    monthDropdownHtml += '</select>';
                    return monthDropdownHtml;
                }

                function generateYearDropdown(inputName, yearOptions) {

                    let yearDropdownHtml = '<select data-placeholder="Select Year" required name="' + inputName + '" class="form-control select mb-3 year" data-fouc>' +
                        '<option></option>';

                    for (let i = 0; i < yearOptions.length; i++) {
                        yearDropdownHtml += '<option value="' + yearOptions[i] + '">' + yearOptions[i] + '</option>';
                    }

                    yearDropdownHtml += '</select>';

                    return yearDropdownHtml;
                }

                let newHtml = '<div class="row">' +
                    '<div class="col-md-2">' +
                    '<label class="col-form-label  ">From Month<span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    fromMonthDropdown +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label  ">From Year <span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    fromYearDropdown +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label">To Month<span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    toMonthDropdown +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label  ">To Year <span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    toYearDropdown +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label  ">Rate <span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    '<input type="text" class="form-control" name="charge_amount[]">' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label  "></label>' +
                    '<button type="button"   title="Delete Record" class="btn btn-danger remove-row" style="margin-top: 36px"><i class="fas fa-trash"></i></button>' +
                    '</div>' +

                    '</div>';
                $('#row-container-non-user').append(newHtml);
                loadSelect();
            });


            // Click event for the "Remove" button
            $('#row-container-non-user').on('click', '.remove-row', function () {
                if ($('#row-container-non-user .row').length > 1) {
                    $(this).parent().parent().remove();
                }
            });

            //////////////////////////////////////For Stamp Duty////////////////////////////////////////////////
            // Click event for the "Add More" button
            $('#add-more-stamp-duty').on('click', function () {
                let monthOptions = @json($months);
                let yearOptions = @json($years);

                let fromMonthDropdown = generateMonthDropdown("from_month[]", monthOptions);
                let toMonthDropdown = generateMonthDropdown("to_month[]", monthOptions);
                let fromYearDropdown = generateYearDropdown("from_year[]", yearOptions);
                let toYearDropdown = generateYearDropdown("to_year[]", yearOptions);

                function generateMonthDropdown(inputName, monthOptions) {
                    let monthDropdownHtml = '<select data-placeholder="Select Month" required name="' + inputName + '" class="form-control select mb-3 month" data-fouc>' +
                        '<option></option>';
                    for (let i = 0; i < monthOptions.length; i++) {
                        monthDropdownHtml += '<option value="' + monthOptions[i].id + '">' + monthOptions[i].name + '</option>';
                    }
                    monthDropdownHtml += '</select>';
                    return monthDropdownHtml;
                }

                function generateYearDropdown(inputName, yearOptions) {

                    let yearDropdownHtml = '<select data-placeholder="Select Year" required name="' + inputName + '" class="form-control select mb-3 year" data-fouc>' +
                        '<option></option>';

                    for (let i = 0; i < yearOptions.length; i++) {
                        yearDropdownHtml += '<option value="' + yearOptions[i] + '">' + yearOptions[i] + '</option>';
                    }

                    yearDropdownHtml += '</select>';

                    return yearDropdownHtml;
                }

                let newHtml = '<div class="row">' +
                    '<div class="col-md-2">' +
                    '<label class="col-form-label  ">From Month<span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    fromMonthDropdown +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label  ">From Year <span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    fromYearDropdown +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label">To Month<span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    toMonthDropdown +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label  ">To Year <span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    toYearDropdown +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label  ">No of Transfer <span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    '<input type="text" class="form-control" name="no_of_transfer[]">' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label  ">Rate Per Marla <span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    '<input type="text" class="form-control" name="charge_amount[]">' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-12">' +
                    '<label class="col-form-label  "></label>' +
                    '<button type="button"   title="Delete Record" class="btn btn-danger remove-row float-right mb-2" style="margin-top: 36px"><i class="fas fa-trash"></i></button>' +
                    '</div>' +

                    '</div>';
                $('#row-container-stamp-duty').append(newHtml);
                loadSelect();
            });


            // Click event for the "Remove" button
            $('#row-container-stamp-duty').on('click', '.remove-row', function () {
                if ($('#row-container-stamp-duty .row').length > 1) {
                    $(this).parent().parent().remove();
                }
            });


        });
    </script>

@endpush
