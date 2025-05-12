@extends('layouts.app')
@push('style')
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            text-indent: 0;
        }

        .border {
            border: 1px solid black !important;
        }


        .s5 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 16pt;
        }

        .s6 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 13pt;
        }


        .s9 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 12pt;
        }

        .s10 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 11pt;
        }

        .s11 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 9pt;
        }

        .s12 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 14pt;
        }

        .s13 {
            color: #F00;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 11pt;
        }

    </style>
@endpush
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
                    <a href="{{route('show-bill')}}" class="breadcrumb-item">Bills</a>
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
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{$title}}</h5>
                <div class="header-elements">
                    <div class="list-icons">


                        <button id="printBtn" type="button"
                                class="btn btn-info btn-labeled btn-labeled-left check-total"><b><i
                                    class="icon-printer"></i></b> Print
                        </button>

                        <button id="pdfBtn" type="button" data-invoice="{{$bill->bill_number}}"
                                class="btn btn-warning btn-labeled btn-labeled-left check-total"><b><i
                                    class="icon-download"></i></b> PDF
                        </button>


                    </div>
                </div>
            </div>
            <div class="invoice" id="printInvoice">

                <div class="card">
                    <div class="card-body">
                        <table style="margin-left:21pt; border-collapse: collapse;">
                            <tr class="border  ">

                                <td>
                                    <img class="p-2" height="120" width="120"
                                         src="{{asset('assets/front_end/img/officelogo.jpg')}}">
                                </td>
                                <td class="" style="width:645pt;" colspan="6">

                                    <p class="s5 text-center"
                                       style="margin-bottom: 0px;"> {{getSettingValue('company_name')}}</p>
                                    <p class="s6 pt-1 pl-3 text-center" style="margin-bottom: 0px;">
                                        BANK: {{$bill->bank->name . ' ' . $bill->bank->branch}}
                                        A/C: {{$bill->bank->account_no}} </p>

                                    <p style="padding-top: 1pt;text-indent: 0pt;text-align: center;margin-bottom: 0px;">
                                        <span class="s9"> VIOLATION BILL </span>
                                    </p>
                                    <p style="padding-top: 1pt;text-indent: 0pt;text-align: center;margin-bottom: 0px;">
                                        <span class="s9"> Customer Copy </span>
                                    </p>
                                </td>
                                <td>
                                    <img class="p-2" height="120" width="120"
                                         src="{{asset('assets/front_end/img/kpklogo.png')}}"
                                         alt="">
                                </td>
                            </tr>
                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr>
                                <td class="border  "
                                    style="width:389pt;"
                                    colspan=6>
                                    <p class="s10 pt-1 pl-4 pr-3 text-center" style="margin-bottom: 0px;">Bill
                                        Number</p>
                                </td>
                                <td class="border  "
                                    style="width: 437pt;"
                                    colspan="6">
                                    <p class="s12 pt-1 pl-3 text-left" style="margin-bottom: 0px;">
                                        *{{$bill->bill_number}}*</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-top border-left border-bottom" rowspan="4" style="width: 300pt"
                                    colspan="6">

                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        {{$bill->allotee->name}}
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        Plot : <span class="s11">{{$bill->allotee->plot_no}}</span>
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        Area : <span class="s11">{{$bill->size->name}}</span>
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        Sector : <span class="s11">{{$bill->sector->name}}</span>
                                    </p>

                                    <p class="s11 pl-3 text-left" style="line-height: 9pt;margin-bottom: 0px;">
                                        Address : {{$bill->allotee->address}}
                                    </p>

                                </td>


                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s13 pt-1 pl-1 text-left" style="margin-bottom: 0px;">Account No.</p>
                                </td>

                                <td class="border border-top border-left border-bottom" style="width: 95pt;"
                                    colspan="2">
                                    <p class="s13 pt-1 pl-1 text-left"
                                       style="margin-bottom: 0px;">{{$bill->allotee->account_no}}</p>
                                </td>


                            </tr>
                            <tr>
                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s10 pl-2 pt-1 text-left" style="margin-bottom: 0px;">Issue Date</p>
                                </td>
                                <td class="border  "
                                    style="width: 86pt;">
                                    <p class="s13 pl-2 pt-1 text-left" style="margin-bottom: 0px;">Due Date</p>
                                </td>
                                <td class="border  "
                                    style="width: 107pt;">
                                    <p class="s10 pl-2 pt-1 text-left" style="margin-bottom: 0px;">Billing Period</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s11 pt-1 pl-2 text-left"
                                       style="margin-bottom: 0px;">{{showDate($bill->issue_date)}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 86pt;">
                                    <p class="s13 pt-1 pl-2 text-left"
                                       style="margin-bottom: 0px;">{{showDate($bill->due_date)}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 107pt;">
                                    <p class="s11 pt-1 pl-1 text-left" style="margin-bottom: 0px;"> {{$bill->year}} </p>
                                </td>
                            </tr>

                        </table>
                        <table class="float-left" style="margin-left:21pt; border-collapse: collapse;">

                            <tr>
                                <th class="border  "
                                    style="width: 179pt;"
                                    colspan="4">
                                    <p class="s10 pl-2 pt-1 text-center" style="margin-bottom: 0px;">Charges
                                        Description</p>
                                </th>
                                <th class="border  "
                                    style="width: 70pt;"
                                    colspan="4">
                                    <p class="s10 pl-1 pt-1 text-center" style="margin-bottom: 0px;">Rate (SFT)</p>
                                </th>
                                <th class="border  "
                                    style="width: 70pt;"
                                    colspan="4">
                                    <p class="s10 pl-1 pt-1 text-center" style="margin-bottom: 0px;">Violation</p>
                                </th>
                                <th class="border   "
                                    style="width: 70pt;"
                                    colspan="4">
                                    <p class="s10 pl-1 pt-1 text-center" style="margin-bottom: 0px;">Cost</p>
                                </th>

                            </tr>
                            <tr>
                                @foreach($bill->billCharges as $billCharges)
                                    {{--                                    {{dd($billCharges->PlotCharges)}}--}}
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s10 pl-1 pt-1 text-left"
                                           style="margin-bottom: 0px;">{{$billCharges->PlotCharges->charge->name}}</p>
                                    </td>
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s11 pr-1 pt-1 text-right"
                                           style="margin-bottom: 0px;">{{$billCharges->amount}}</p>
                                    </td>
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s11 pr-2 pt-1 text-right"
                                           style="margin-bottom: 0px;">{{$billCharges->total_violation }}</p>
                                    </td>
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s11 pr-2 pt-1 text-right"
                                           style="margin-bottom: 0px;">{{$billCharges->total}}</p>
                                    </td>
                            </tr>
                            @endforeach


                        </table>
                        <!-- -------------------------------Current Bill----------------------------------------- -->

                        <table style="width: 437pt">

                            <tr>
                                <td class="border" style=" width: 329pt;">
                                    <p class="s10 pl-1 pt-1 text-left" style="margin-bottom: 0px;">Current Bill</p>
                                </td>

                                <td class="border  "
                                    colspan="9">
                                    <p class="s11 pr-2 pt-1 text-right"
                                       style="margin-bottom: 0px;">{{$bill->bill_total}}</p>
                                </td>
                            </tr>

                            <!-- -------------------------------Arrears----------------------------------------- -->

                            <tr>
                                <td class="border" style=" width: 329pt;">

                                    <p class="s10 pl-1 pt-1 text-left" style="margin-bottom: 0px;">Arrears</p>
                                </td>

                                <td class="border  "
                                    colspan="9">
                                    <p class="s11 pr-2 pt-1 text-right"
                                       style="margin-bottom: 0px;"> {{$bill->arrears ?? '0'}} </p>
                                </td>


                            </tr>
                            <tr>
                                <td class="border" style=" width: 329pt;">

                                    <p class="s10 pl-1 pt-1 text-left" style="margin-bottom: 0px;">Amount Payable by Due
                                        Date</p>
                                </td>

                                <td class="border  "
                                    colspan="9">
                                    <p class="s11 pr-2 pt-1 text-right"
                                       style="margin-bottom: 0px;"> {{$bill->total}} </p>
                                </td>


                            </tr>
                            <tr>
                                <td class="border" style=" width: 329pt;">

                                    <p class="s10 pl-1 pt-1 text-left" style="margin-bottom: 0px;">Surcharge
                                        @Rs. {{getSettingValue('sub_charges')}}
                                        %
                                        After Due Date</p>
                                </td>

                                <td class="border  "
                                    colspan="9">
                                    <p class="s11 pr-2 pt-1 text-right"
                                       style="margin-bottom: 0px;"> {{$bill->sub_charges}}</p>
                                </td>


                            </tr>
                            <tr>
                                <td class="border" style=" width: 329pt;">

                                    <p class="s10 pl-1 pt-1 text-left" style="margin-bottom: 0px;">Amount Payable After
                                        Due Date</p>
                                </td>

                                <td class="border  "
                                    colspan="9">
                                    <p class="s11 pr-2 pt-1 text-right"
                                       style="margin-bottom: 0px;">  {{$bill->sub_total}} </p>
                                </td>
                            </tr>

                        </table>

                    </div>

                    {{--------------------------------Office Copy----------------------------------}}

                    <div class="card-body">
                        <table style="margin-left:21pt; border-collapse: collapse;">
                            <tr class="border  ">

                                <td>
                                    <img class="p-2" height="120" width="120"
                                         src="{{asset('assets/front_end/img/officelogo.jpg')}}">
                                </td>
                                <td class="" style="width:645pt;" colspan="6">

                                    <p class="s5 text-center"
                                       style="margin-bottom: 0px;"> {{getSettingValue('company_name')}}</p>
                                    <p class="s6 pt-1 pl-3 text-center" style="margin-bottom: 0px;">
                                        BANK: {{$bill->bank->name . ' ' . $bill->bank->branch}}
                                        A/C: {{$bill->bank->account_no}} </p>

                                    <p style="padding-top: 1pt;text-indent: 0pt;text-align: center;margin-bottom: 0px;">
                                        <span class="s9"> VIOLATION BILL </span>
                                    </p>
                                    <p style="padding-top: 1pt;text-indent: 0pt;text-align: center;margin-bottom: 0px;">
                                        <span class="s9"> Office Copy </span>
                                    </p>
                                </td>
                                <td>
                                    <img class="p-2" height="120" width="120"
                                         src="{{asset('assets/front_end/img/kpklogo.png')}}"
                                         alt="">
                                </td>
                            </tr>
                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr>
                                <td class="border  "
                                    style="width:389pt;"
                                    colspan=6>
                                    <p class="s10 pt-1 pl-4 pr-3 text-center" style="margin-bottom: 0px;">Bill
                                        Number</p>
                                </td>
                                <td class="border  "
                                    style="width: 437pt;"
                                    colspan="6">
                                    <p class="s12 pt-1 pl-3 text-left" style="margin-bottom: 0px;">
                                        *{{$bill->bill_number}}*</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-top border-left border-bottom" rowspan="4" style="width: 300pt"
                                    colspan="6">

                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        {{$bill->allotee->name}}
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        Plot : <span class="s11">{{$bill->allotee->plot_no}}</span>
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        Area : <span class="s11">{{$bill->size->name}}</span>
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        Sector : <span class="s11">{{$bill->sector->name}}</span>
                                    </p>

                                    <p class="s11 pl-3 text-left" style="line-height: 9pt;margin-bottom: 0px;">
                                        Address : {{$bill->allotee->address}}
                                    </p>

                                </td>


                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s13 pt-1 pl-1 text-left" style="margin-bottom: 0px;">Account No.</p>
                                </td>

                                <td class="border border-top border-left border-bottom" style="width: 95pt;"
                                    colspan="2">
                                    <p class="s13 pt-1 pl-1 text-left"
                                       style="margin-bottom: 0px;">{{$bill->allotee->account_no}}</p>
                                </td>


                            </tr>
                            <tr>
                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s10 pl-2 pt-1 text-left" style="margin-bottom: 0px;">Issue Date</p>
                                </td>
                                <td class="border  "
                                    style="width: 86pt;">
                                    <p class="s13 pl-2 pt-1 text-left" style="margin-bottom: 0px;">Due Date</p>
                                </td>
                                <td class="border  "
                                    style="width: 107pt;">
                                    <p class="s10 pl-2 pt-1 text-left" style="margin-bottom: 0px;">Billing Period</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s11 pt-1 pl-2 text-left"
                                       style="margin-bottom: 0px;">{{showDate($bill->issue_date)}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 86pt;">
                                    <p class="s13 pt-1 pl-2 text-left"
                                       style="margin-bottom: 0px;">{{showDate($bill->due_date)}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 107pt;">
                                    <p class="s11 pt-1 pl-1 text-left" style="margin-bottom: 0px;">{{$bill->year}} </p>
                                </td>
                            </tr>

                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr>
                                <th class="border  "
                                    style="width: 389pt;"
                                    colspan="4">
                                    <p class="s10 pl-2 pt-1 text-center" style="margin-bottom: 0px;">Charges
                                        Description</p>
                                </th>
                                <th class="border  "
                                    style="width: 146pt;"
                                    colspan="4">
                                    <p class="s10 pl-1 pt-1 text-center" style="margin-bottom: 0px;">Rate (SFT)</p>
                                </th>
                                <th class="border  "
                                    style="width: 146pt;"
                                    colspan="4">
                                    <p class="s10 pl-1 pt-1 text-center" style="margin-bottom: 0px;">Violation</p>
                                </th>
                                <th class="border   "
                                    style="width: 145pt;"
                                    colspan="4">
                                    <p class="s10 pl-1 pt-1 text-center" style="margin-bottom: 0px;">Cost</p>
                                </th>

                            </tr>
                            <tr>
                                @foreach($bill->billCharges as $billCharges)
                                    {{--                                    {{dd($billCharges->PlotCharges)}}--}}
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s10 pl-1 pt-1 text-left"
                                           style="margin-bottom: 0px;">{{$billCharges->PlotCharges->charge->name}}</p>
                                    </td>
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s11 pr-1 pt-1 text-right"
                                           style="margin-bottom: 0px;">{{$billCharges->amount}}</p>
                                    </td>
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s11 pr-2 pt-1 text-right"
                                           style="margin-bottom: 0px;">{{$billCharges->total_violation }}</p>
                                    </td>
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s11 pr-2 pt-1 text-right"
                                           style="margin-bottom: 0px;">{{$billCharges->total}}</p>
                                    </td>
                            </tr>
                            @endforeach


                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr class="h-14">
                                <td class="border  "
                                    style="width: 826pt;"
                                    colspan="12">
                                    <p class="s10 text-center line-height-9" style="margin-bottom: 0px;">Payable
                                        Amount</p>
                                </td>
                            </tr>
                            <tr class="h-14">
                                <td class="border  "
                                    style="width: 158pt;"
                                    bgcolor="">
                                    <p class="s10 pl-4 text-left line-height-9" style="margin-bottom: 0px;">Current
                                        Bill</p>
                                </td>
                                <td class="border  "
                                    style="width: 148pt;"
                                    colspan="2" bgcolor="">
                                    <p class="s10 pl-4 pr-4 text-center line-height-9" style="margin-bottom: 0px;">
                                        Arrears</p>
                                </td>
                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="3" bgcolor="">
                                    <p class="s13 pl-1 text-left line-height-9" style="margin-bottom: 0px;">Total Before
                                        Due Date</p>
                                </td>
                                <td class="border  "
                                    style="width: 86pt;"
                                    bgcolor="">
                                    <p class="s10 pl-3 text-left line-height-9" style="margin-bottom: 0px;">
                                        Surcharge</p>
                                </td>
                                <td class="border  "
                                    style="width: 107pt;"
                                    bgcolor="">
                                    <p class="s13 pl-3 text-left line-height-9" style="margin-bottom: 0px;">Total After
                                        Due Date</p>
                                </td>
                            </tr>
                            <tr class="h-14">
                                <td class="border  "
                                    style="width: 158pt;">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->bill_total}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 148pt;"
                                    colspan="2">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->arrears}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="3">
                                    <p class="s13 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->total}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 86pt;">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->sub_charges}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 107pt;">
                                    <p class="s13 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->sub_total}}</p>
                                </td>
                            </tr>

                        </table>

                    </div>
                    {{--------------------------------File Copy----------------------------------}}
                    <div class="card-body">
                        <table style="margin-left:21pt; border-collapse: collapse;">
                            <tr class="border  ">

                                <td>
                                    <img class="p-2" height="120" width="120"
                                         src="{{asset('assets/front_end/img/officelogo.jpg')}}">
                                </td>
                                <td class="" style="width:645pt;" colspan="6">

                                    <p class="s5 text-center"
                                       style="margin-bottom: 0px;"> {{getSettingValue('company_name')}}</p>
                                    <p class="s6 pt-1 pl-3 text-center" style="margin-bottom: 0px;">
                                        BANK: {{$bill->bank->name . ' ' . $bill->bank->branch}}
                                        A/C: {{$bill->bank->account_no}} </p>

                                    <p style="padding-top: 1pt;text-indent: 0pt;text-align: center;margin-bottom: 0px;">
                                        <span class="s9"> VIOLATION BILL </span>
                                    </p>
                                    <p style="padding-top: 1pt;text-indent: 0pt;text-align: center;" margin-bottom:
                                       0px;>
                                        <span class="s9"> File Copy </span>
                                    </p>
                                </td>
                                <td>
                                    <img class="p-2" height="120" width="120"
                                         src="{{asset('assets/front_end/img/kpklogo.png')}}"
                                         alt="">
                                </td>
                            </tr>
                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr>
                                <td class="border  "
                                    style="width:389pt;"
                                    colspan=6>
                                    <p class="s10 pt-1 pl-4 pr-3 text-center" style="margin-bottom: 0px;">Bill
                                        Number</p>
                                </td>
                                <td class="border  "
                                    style="width: 437pt;"
                                    colspan="6">
                                    <p class="s12 pt-1 pl-3 text-left" style="margin-bottom: 0px;">
                                        *{{$bill->bill_number}}*</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-top border-left border-bottom" rowspan="4" style="width: 300pt"
                                    colspan="6">

                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        {{$bill->allotee->name}}
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        Plot : <span class="s11">{{$bill->allotee->plot_no}}</span>
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        Area : <span class="s11">{{$bill->size->name}}</span>
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        Sector : <span class="s11">{{$bill->sector->name}}</span>
                                    </p>

                                    <p class="s11 pl-3 text-left" style="line-height: 9pt;margin-bottom: 0px;">
                                        Address : {{$bill->allotee->address}}
                                    </p>

                                </td>


                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s13 pt-1 pl-1 text-left" style="margin-bottom: 0px;">Account No.</p>
                                </td>

                                <td class="border border-top border-left border-bottom" style="width: 95pt;"
                                    colspan="2">
                                    <p class="s13 pt-1 pl-1 text-left"
                                       style="margin-bottom: 0px;">{{$bill->allotee->account_no}}</p>
                                </td>


                            </tr>
                            <tr>
                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s10 pl-2 pt-1 text-left" style="margin-bottom: 0px;">Issue Date</p>
                                </td>
                                <td class="border  "
                                    style="width: 86pt;">
                                    <p class="s13 pl-2 pt-1 text-left" style="margin-bottom: 0px;">Due Date</p>
                                </td>
                                <td class="border  "
                                    style="width: 107pt;">
                                    <p class="s10 pl-2 pt-1 text-left" style="margin-bottom: 0px;">Billing Period</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s11 pt-1 pl-2 text-left"
                                       style="margin-bottom: 0px;">{{showDate($bill->issue_date)}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 86pt;">
                                    <p class="s13 pt-1 pl-2 text-left"
                                       style="margin-bottom: 0px;">{{showDate($bill->due_date)}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 107pt;">
                                    <p class="s11 pt-1 pl-1 text-left" style="margin-bottom: 0px;">{{$bill->year}} </p>
                                </td>
                            </tr>

                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr>
                                <th class="border  "
                                    style="width: 389pt;"
                                    colspan="4">
                                    <p class="s10 pl-2 pt-1 text-center" style="margin-bottom: 0px;">Charges
                                        Description</p>
                                </th>
                                <th class="border  "
                                    style="width: 146pt;"
                                    colspan="4">
                                    <p class="s10 pl-1 pt-1 text-center" style="margin-bottom: 0px;">Rate (SFT)</p>
                                </th>
                                <th class="border  "
                                    style="width: 146pt;"
                                    colspan="4">
                                    <p class="s10 pl-1 pt-1 text-center" style="margin-bottom: 0px;">Violation</p>
                                </th>
                                <th class="border   "
                                    style="width: 145pt;"
                                    colspan="4">
                                    <p class="s10 pl-1 pt-1 text-center" style="margin-bottom: 0px;">Cost</p>
                                </th>

                            </tr>
                            <tr>
                                @foreach($bill->billCharges as $billCharges)
                                    {{--                                    {{dd($billCharges->PlotCharges)}}--}}
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s10 pl-1 pt-1 text-left"
                                           style="margin-bottom: 0px;">{{$billCharges->PlotCharges->charge->name}}</p>
                                    </td>
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s11 pr-1 pt-1 text-right"
                                           style="margin-bottom: 0px;">{{$billCharges->amount}}</p>
                                    </td>
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s11 pr-2 pt-1 text-right"
                                           style="margin-bottom: 0px;">{{$billCharges->total_violation }}</p>
                                    </td>
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s11 pr-2 pt-1 text-right"
                                           style="margin-bottom: 0px;">{{$billCharges->total}}</p>
                                    </td>
                            </tr>
                            @endforeach


                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr class="h-14">
                                <td class="border  "
                                    style="width: 826pt;"
                                    colspan="12">
                                    <p class="s10 text-center line-height-9" style="margin-bottom: 0px;">Payable
                                        Amount</p>
                                </td>
                            </tr>
                            <tr class="h-14">
                                <td class="border  "
                                    style="width: 158pt;"
                                    bgcolor="">
                                    <p class="s10 pl-4 text-left line-height-9" style="margin-bottom: 0px;">Current
                                        Bill</p>
                                </td>
                                <td class="border  "
                                    style="width: 148pt;"
                                    colspan="2" bgcolor="">
                                    <p class="s10 pl-4 pr-4 text-center line-height-9" style="margin-bottom: 0px;">
                                        Arrears</p>
                                </td>
                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="3" bgcolor="">
                                    <p class="s13 pl-1 text-left line-height-9" style="margin-bottom: 0px;">Total Before
                                        Due Date</p>
                                </td>
                                <td class="border  "
                                    style="width: 86pt;"
                                    bgcolor="">
                                    <p class="s10 pl-3 text-left line-height-9" style="margin-bottom: 0px;">
                                        Surcharge</p>
                                </td>
                                <td class="border  "
                                    style="width: 107pt;"
                                    bgcolor="">
                                    <p class="s13 pl-3 text-left line-height-9" style="margin-bottom: 0px;">Total After
                                        Due Date</p>
                                </td>
                            </tr>
                            <tr class="h-14">
                                <td class="border  "
                                    style="width: 158pt;">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->bill_total}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 148pt;"
                                    colspan="2">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->arrears}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="3">
                                    <p class="s13 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->total}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 86pt;">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->sub_charges}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 107pt;">
                                    <p class="s13 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->sub_total}}</p>
                                </td>
                            </tr>

                        </table>
                    </div>
                    {{--------------------------------Bank Copy----------------------------------}}
                    <div class="card-body">
                        <table style="margin-left:21pt; border-collapse: collapse;">
                            <tr class="border  ">

                                <td>
                                    <img class="p-2" height="120" width="120"
                                         src="{{asset('assets/front_end/img/officelogo.jpg')}}">
                                </td>
                                <td class="" style="width:645pt;" colspan="6">

                                    <p class="s5 text-center"
                                       style="margin-bottom: 0px;"> {{getSettingValue('company_name')}}</p>
                                    <p class="s6 pt-1 pl-3 text-center" style="margin-bottom: 0px;">
                                        BANK: {{$bill->bank->name . ' ' . $bill->bank->branch}}
                                        A/C: {{$bill->bank->account_no}} </p>

                                    <p style="padding-top: 1pt;text-indent: 0pt;text-align: center;margin-bottom: 0px;">
                                        <span class="s9"> VIOLATION BILL </span>
                                    </p>
                                    <p style="padding-top: 1pt;text-indent: 0pt;text-align: center;" margin-bottom:
                                       0px;>
                                        <span class="s9"> Bank Copy </span>
                                    </p>
                                </td>
                                <td>
                                    <img class="p-2" height="120" width="120"
                                         src="{{asset('assets/front_end/img/kpklogo.png')}}"
                                         alt="">
                                </td>
                            </tr>
                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr>
                                <td class="border  "
                                    style="width:389pt;"
                                    colspan=6>
                                    <p class="s10 pt-1 pl-4 pr-3 text-center" style="margin-bottom: 0px;">Bill
                                        Number</p>
                                </td>
                                <td class="border  "
                                    style="width: 437pt;"
                                    colspan="6">
                                    <p class="s12 pt-1 pl-3 text-left" style="margin-bottom: 0px;">
                                        *{{$bill->bill_number}}*</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-top border-left border-bottom" rowspan="4" style="width: 300pt"
                                    colspan="6">

                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        {{$bill->allotee->name}}
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        Plot : <span class="s11">{{$bill->allotee->plot_no}}</span>
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        Area : <span class="s11">{{$bill->size->name}}</span>
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px;">
                                        Sector : <span class="s11">{{$bill->sector->name}}</span>
                                    </p>

                                    <p class="s11 pl-3 text-left" style="line-height: 9pt;margin-bottom: 0px;">
                                        Address : {{$bill->allotee->address}}
                                    </p>

                                </td>


                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s13 pt-1 pl-1 text-left" style="margin-bottom: 0px;">Account No.</p>
                                </td>

                                <td class="border border-top border-left border-bottom" style="width: 95pt;"
                                    colspan="2">
                                    <p class="s13 pt-1 pl-1 text-left"
                                       style="margin-bottom: 0px;">{{$bill->allotee->account_no}}</p>
                                </td>


                            </tr>
                            <tr>
                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s10 pl-2 pt-1 text-left" style="margin-bottom: 0px;">Issue Date</p>
                                </td>
                                <td class="border  "
                                    style="width: 86pt;">
                                    <p class="s13 pl-2 pt-1 text-left" style="margin-bottom: 0px;">Due Date</p>
                                </td>
                                <td class="border  "
                                    style="width: 107pt;">
                                    <p class="s10 pl-2 pt-1 text-left" style="margin-bottom: 0px;">Billing Period</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s11 pt-1 pl-2 text-left"
                                       style="margin-bottom: 0px;">{{showDate($bill->issue_date)}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 86pt;">
                                    <p class="s13 pt-1 pl-2 text-left"
                                       style="margin-bottom: 0px;">{{showDate($bill->due_date)}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 107pt;">
                                    <p class="s11 pt-1 pl-1 text-left" style="margin-bottom: 0px;">{{$bill->year}} </p>
                                </td>
                            </tr>

                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr>
                                <th class="border  "
                                    style="width: 389pt;"
                                    colspan="4">
                                    <p class="s10 pl-2 pt-1 text-center" style="margin-bottom: 0px;">Charges
                                        Description</p>
                                </th>
                                <th class="border  "
                                    style="width: 146pt;"
                                    colspan="4">
                                    <p class="s10 pl-1 pt-1 text-center" style="margin-bottom: 0px;">Rate (SFT)</p>
                                </th>
                                <th class="border  "
                                    style="width: 146pt;"
                                    colspan="4">
                                    <p class="s10 pl-1 pt-1 text-center" style="margin-bottom: 0px;">Violation</p>
                                </th>
                                <th class="border   "
                                    style="width: 145pt;"
                                    colspan="4">
                                    <p class="s10 pl-1 pt-1 text-center" style="margin-bottom: 0px;">Cost</p>
                                </th>

                            </tr>
                            <tr>
                                @foreach($bill->billCharges as $billCharges)
                                    {{--                                    {{dd($billCharges->PlotCharges)}}--}}
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s10 pl-1 pt-1 text-left"
                                           style="margin-bottom: 0px;">{{$billCharges->PlotCharges->charge->name}}</p>
                                    </td>
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s11 pr-1 pt-1 text-right"
                                           style="margin-bottom: 0px;">{{$billCharges->amount}}</p>
                                    </td>
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s11 pr-2 pt-1 text-right"
                                           style="margin-bottom: 0px;">{{$billCharges->total_violation }}</p>
                                    </td>
                                    <td class="border  "
                                        colspan="4">
                                        <p class="s11 pr-2 pt-1 text-right"
                                           style="margin-bottom: 0px;">{{$billCharges->total}}</p>
                                    </td>
                            </tr>
                            @endforeach


                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr class="h-14">
                                <td class="border  "
                                    style="width: 826pt;"
                                    colspan="12">
                                    <p class="s10 text-center line-height-9" style="margin-bottom: 0px;">Payable
                                        Amount</p>
                                </td>
                            </tr>
                            <tr class="h-14">
                                <td class="border  "
                                    style="width: 158pt;"
                                    bgcolor="">
                                    <p class="s10 pl-4 text-left line-height-9" style="margin-bottom: 0px;">Current
                                        Bill</p>
                                </td>
                                <td class="border  "
                                    style="width: 148pt;"
                                    colspan="2" bgcolor="">
                                    <p class="s10 pl-4 pr-4 text-center line-height-9" style="margin-bottom: 0px;">
                                        Arrears</p>
                                </td>
                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="3" bgcolor="">
                                    <p class="s13 pl-1 text-left line-height-9" style="margin-bottom: 0px;">Total Before
                                        Due Date</p>
                                </td>
                                <td class="border  "
                                    style="width: 86pt;"
                                    bgcolor="">
                                    <p class="s10 pl-3 text-left line-height-9" style="margin-bottom: 0px;">
                                        Surcharge</p>
                                </td>
                                <td class="border  "
                                    style="width: 107pt;"
                                    bgcolor="">
                                    <p class="s13 pl-3 text-left line-height-9" style="margin-bottom: 0px;">Total After
                                        Due Date</p>
                                </td>
                            </tr>
                            <tr class="h-14">
                                <td class="border  "
                                    style="width: 158pt;">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->bill_total}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 148pt;"
                                    colspan="2">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->arrears}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 90pt;"
                                    colspan="3">
                                    <p class="s13 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->total}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 86pt;">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->sub_charges}}</p>
                                </td>
                                <td class="border  "
                                    style="width: 107pt;">
                                    <p class="s13 text-center line-height-9"
                                       style="margin-bottom: 0px;">{{$bill->sub_total}}</p>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /form validation -->

    </div>
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

    <script src="{{asset('assets/global_assets/js/demo_pages/picker_date.js')}}"></script>

    <script src="{{asset('assets/custom/js/jspdf.umd.min.js')}}"></script>
    <script src="{{asset('assets/custom/js/html2canvas.min.js')}}"></script>
    <script src="{{asset('assets/custom/js/html2canvas.js')}}"></script>
    <script src="{{asset('assets/custom/js/printThis.js')}}"></script>
    <script>

        $(document).ready(function () {
            window.html2canvas = html2canvas; // add this line of code
            window.jsPDF = window.jspdf.jsPDF; // add this line of code
            $(function () {

                $('#printBtn').on('click', function () {
                    $('#printInvoice').printThis({
                        importCSSL: true,
                        loadCSS: "{{asset('assets/assets/light/css/bootstrap.min.css')}}",
                    });
                });

                $('#pdfBtn').on('click', function () {
                    var input = document.getElementById("printInvoice");
                    const invoiceNumber = $(this).data('invoice');
                    html2canvas(input)
                        .then((canvas) => {
                            const imgData = canvas.toDataURL('image/png');
                            // var pdf = new jsPDF("p", "mm", "a4");
                            var pdf = new jsPDF("p", "in", "legal"); // Set page orientation to landscape ("l") and page size to legal
                            const imgProps = pdf.getImageProperties(imgData);
                            const pdfWidth = pdf.internal.pageSize.getWidth();
                            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                            // Add extra height to accommodate content cut from the bottom
                            const extraHeight = -0.5; // Adjust this value as needed
                            const adjustedPdfHeight = pdfHeight + extraHeight;

                            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, adjustedPdfHeight);
                            pdf.save(invoiceNumber + '.pdf');
                        });
                });
            });

        });
    </script>

@endpush
