@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    @if(isset($deal) && !empty($deal))
        Edit Deal::{{$deal->id}}
    @else
        Add Deal
    @endif
@endsection
@section('page_title_con')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                @if(isset($deal) && !empty($deal))
                    Edit Deal::{{$deal->id}}
                @else
                    Add Deal
                @endif
            </h4>
        </div>
        <div class="page-rightheader ml-auto d-lg-flex d-none">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex">
                        <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                             width="24">
                            <path d="M0 0h24v24H0V0z" fill="none"/>
                            <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/>
                            <path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/>
                        </svg>
                        <span class="breadcrumb-icon"> Home</span></a></li>
                <li class="breadcrumb-item " aria-current="page">
                    <a href="{{route('admin.deals.list')}}">Deals</a>
                </li>
                <li class="breadcrumb-item active"
                    aria-current="page">                            @if(isset($deal) && !empty($deal))
                        Edit Deal::{{$deal->id}}
                    @else
                        Add Deal
                    @endif
                </li>
            </ol>
        </div>
    </div>

@endsection
@section('body')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <form method="post"
                          action="{{isset($deal)?route('admin.deals.update', encrypt($deal->id)):route('admin.deals.post')}}"
                          onsubmit="return saveForm(this)">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div id="" class="panel panel-primary">
                            <div class="tab-menu-heading">
                                <div class="tabs-menu ">
                                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav panel-tabs">
                                        <li class="nav-item">
                                            <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab"
                                               href="#step-1">
                                                <span>Deal Info</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#step-3">
                                                <span>Relationships</span>
                                            </a>
                                        </li>
                                        <!--<li class="nav-item">
                                            <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#step-2">
                                                <span>Commission</span>
                                            </a>
                                        </li>-->

                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">
                                    <div id="step-1" class="tab-pane tabs-animation fade show active" role="tabpanel">
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                @if(isset($deal) && false)
                                                    <div class="form-row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label class="form-label font-weight-bold">Deal
                                                                    ID</label>
                                                            </div>
                                                            <input type="text" readonly value="{{$deal->id}}"
                                                                   class="form-control"/>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="form-row">
                                                    <!--<div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Deal ID</label>
                                                            <input type="text" readonly value="{{isset($deal) ? $deal->id :
                                                    ''}}"
                                                                   class="form-control"/>
                                                        </div>
                                                    </div>-->
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Broker</label>
                                                            <select name="broker_id" id="broker_id"
                                                                    class="form-control">
                                                                <option value="">Select Broker</option>
                                                                @foreach($brokers as $broker)
                                                                    <option value="{{$broker->id}}" {{isset($deal)
                                                            && $deal->broker_id == $broker->id ?
                                                            'selected="selected"' :
                                                            ''}}>{{$broker->display_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Broker
                                                                Staff</label>
                                                            <select name="broker_staff_id" id="broker_staff_id"
                                                                    class="form-control">
                                                                <option value="">Select Broker Staff</option>
                                                                @foreach($broker_staffs as $broker_staff)
                                                                    <option value="{{$broker_staff->id}}"
                                                                            {{isset($deal)
                                                            && $deal->broker_id != $broker_staff->parent_broker ?
                                                            'disabled="disabled"' :
                                                            ''}}
                                                                            data-parent_id="{{$broker_staff->parent_broker}}" {{isset($deal)
                                                            && $deal->broker_staff_id == $broker_staff->id ?
                                                            'selected="selected"' : ''}}
                                                                    >{{$broker_staff->display_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                             
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Client</label><?php //print_R($clients); ?>
                                                            <select name="contact_id" id="contact_id"
                                                                    class="form-control">
                                                                <option value="">Select Client</option>
                                                                @foreach($clients as $client)
                                                                    <option value="{{$client->id}}" {{isset($deal)
                                                            && $deal->contact_id == $client->id ?
                                                            'selected="selected"' : ''}}>{{$client->surname.' '.$client->first_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    

                                                </div>
                                                <div class="form-row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Product</label>
                                                            <select name="product_id" id="product_id"
                                                                    class="form-control">
                                                                <option value="">Select Product</option>
                                                                @foreach($products as $product)
                                                                    <option value="{{$product->id}}" {{isset($deal)
                                                            && $deal->product_id == $product->id ?
                                                            'selected="selected"' : ''}}>{{$product->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Lender</label>
                                                            <select name="lender_id" id="lender_id"
                                                                    class="form-control">
                                                                <option value="">Select Lender</option>
                                                                @foreach($lenders as $lender)
                                                                    <option value="{{$lender->id}}" {{isset($deal)
                                                            && $deal->lender_id == $lender->id ?
                                                            'selected="selected"' : ''}}>{{($lender->code != '') ? $lender->code :
                                                            $lender->name
                                                            }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Loan Ref</label>
                                                            <input type="text" name="loan_ref" value="{{isset($deal)
                                                            && $deal->loan_ref != '' ?
                                                            $deal->loan_ref : ''}}" maxlength="255" id="loan_ref"
                                                                   class="form-control"
                                                                   placeholder="Loan Ref"/>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Application No</label>
                                                            <input type="text" name="application_no" value="{{isset($deal)
                                                            && $deal->application_no != '' ?
                                                            $deal->application_no : ''}}" maxlength="255" id="application_no"
                                                                   class="form-control"
                                                                   placeholder="Application No"/>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Linked To</label>
                                                            <select name="linked_to" id="linked_to"
                                                                    class="form-control">
                                                                <option value="">Select Deal</option>
                                                                @foreach($existing_deals as $existing_deal)
                                                                    <option value="{{$existing_deal->id}}" {{(isset($deal)
                                                            && $deal->linked_to && $deal->linked_to == $existing_deal->id ?
                                                            "selected" : '')}}>{{$existing_deal->loan_ref}}
                                                                        - {{$existing_deal->display_name}}   </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">

                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Loan
                                                                amount</label>
                                                            <input type="text" name="broker_est_loan_amt"
                                                                   placeholder="Loan amount"
                                                                   id="broker_est_loan_amt" value="{{isset($deal)
                                                            && $deal->actual_loan  > 0  ?
                                                            $deal->actual_loan : ''}}"
                                                                   class="form-control number-input" maxlength="13"/>

                                                        </div>
                                                    </div>
                                                    {{--<div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Loan Repaid</label>
                                                            <input type="text" name="loan_repaid" id="loan_repaid" class="form-control"
                                                                placeholder="Loan Repaid" value="{{isset($deal)
                                                                    && $deal->loan_repaid != '' ?
                                                                    $deal->loan_repaid : ''}}" maxlength="255" />
                                                        </div>
                                                    </div>--}}
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Status</label>
                                                            <select name="status" id="status" class="form-control">
                                                                <option value="">Select status</option>
                                                                @foreach($statuses as $status)
                                                                    <option value="{{$status->id}}" {{isset($deal) && $deal->status == $status->id ?
                                                            'selected="selected"' : ''}}>{{$status->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Status
                                                                Date</label>
                                                            <input type="text" data-toggle="datepicker"
                                                                   placeholder="dd/mm/yyyy" name="status_date" value="{{isset($deal)
                                                            && $deal->status_date != '' ?
                                                            date('d/m/Y', strtotime($deal->status_date)) : ''}}" placeholder="Status Date"
                                                                   id="status_date"
                                                                   class="form-control" maxlength="255"/>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Proposed
                                                                Settlement</label>
                                                            <input type="text" data-toggle="datepicker"
                                                                   placeholder="dd/mm/yyyy" name="proposed_settlement"
                                                                   value="{{isset($deal)
                                                            && $deal->proposed_settlement != '' ?
                                                            date('d/m/Y', strtotime($deal->proposed_settlement)) : ''}}"
                                                                   placeholder="Proposed Settlement"
                                                                   id="proposed_settlement"
                                                                   class="form-control" maxlength="255"/>

                                                        </div>
                                                    </div>

                                                    {{-- <div class="col-sm-4">
                                                        <div class="position-relative form-check form-check-inline">
                                                            <label>&nbsp;</label>
                                                            <label class="form-check-label" style="padding-top: 35px;">

                                                                <input type="checkbox" data-onstyle="success"
                                                                       data-offstyle="danger" data-toggle="toggle" data-on="Yes"
                                                                       data-off="No" data-size="mini" value="1"
                                                                       id="loan_repaid"
                                                                       name="loan_repaid" {{isset($deal)
                                                                && $deal->loan_repaid == 1 ?
                                                                'checked="checked"' : ''}}
                                                                       class="" >
                                                                Loan Repaid
                                                            </label>
                                                            @if($errors->has('line_of_credit'))
                                                                <div class="error"
                                                                     style="color:red">{{$errors->first('line_of_credit')}}</div>
                                                            @endif
                                                        </div>
                                                    </div> --}}
                                                    {{-- <div class="col-sm-4">
                                                        <div class="position-relative form-check form-check-inline">
                                                            <label>&nbsp;</label>
                                                            <label class="form-check-label" style="padding-top: 35px;">

                                                                <input type="checkbox" data-onstyle="success"
                                                                       data-offstyle="danger" data-toggle="toggle" data-on="Yes"
                                                                       data-off="No" data-size="mini" value="1"
                                                                       id="line_of_credit"
                                                                       name="line_of_credit" {{isset($deal)
                                                                && $deal->line_of_credit == 1 ?
                                                                'checked="checked"' : ''}}
                                                                       class="" >
                                                                Line Of Credit
                                                            </label>
                                                            @if($errors->has('line_of_credit'))
                                                                <div class="error"
                                                                     style="color:red">{{$errors->first('line_of_credit')}}</div>
                                                            @endif
                                                        </div>
                                                    </div> --}}
                                                </div>
                                                <div class="form-row">

                                                    {{--<div class="col-sm-4">
                                                        <div class="position-relative form-check form-check-inline">
                                                            <label>&nbsp;</label>
                                                            <label class="form-check-label" style="padding-top: 35px;">

                                                                <input type="checkbox" data-onstyle="success"
                                                                    data-offstyle="danger" data-toggle="toggle" data-on="Yes"
                                                                    data-off="No" data-size="mini" value="1"
                                                                    id="exclude_from_tracking"
                                                                    name="exclude_from_tracking"
                                                                    {{isset($deal)
                                                                && $deal->exclude_from_tracking == 1 ?
                                                                'checked="checked"' : ''}}
                                                                    class="" >
                                                                Exclude From Tracking?
                                                            </label>
                                                            @if($errors->has('exclude_from_tracking'))
                                                                <div class="error"
                                                                    style="color:red">{{$errors->first('exclude_from_tracking')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>--}}
                                                    {{--<div class="col-sm-4">
                                                        <div class="position-relative form-check form-check-inline">
                                                            <label>&nbsp;</label>
                                                            <label class="form-check-label" style="padding-top: 35px;">

                                                                <input type="checkbox" data-onstyle="success"
                                                                    data-offstyle="danger" data-toggle="toggle" data-on="Yes"
                                                                    data-off="No" data-size="mini" value="1"
                                                                    id="gst_applies"
                                                                    name="gst_applies"
                                                                    {{isset($deal)
                                                                && $deal->gst_applies == 1 ?
                                                                'checked="checked"' : ''}}
                                                                    class="" >
                                                                GST Applies?
                                                            </label>
                                                            @if($errors->has('gst_applies'))
                                                                <div class="error"
                                                                    style="color:red">{{$errors->first('gst_applies')}}</div>
                                                            @endif
                                                        </div>
                                                    </div>--}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-2">
                                            <div class="card-header">
                                                Referror split
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">

                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Referror</label>
                                                            <select name="referror_split_referror"
                                                                    id="referror_split_referror"
                                                                    class="form-control">
                                                                <option value="">Select Referror</option>
                                                                @foreach($refferors as $refferor)
                                                                    <option
                                                                        value="{{$refferor->id}}" {{isset($deal) &&
                                                                    $deal->referror_split_referror == $refferor->id ?
                                                                        'selected="selected"' :''}}
                                                                    >{{$refferor->display_name}}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Fee Per
                                                                Deal</label>
                                                            <input type="text" name="referror_split_comm_per_deal"
                                                                   placeholder="$0.00"
                                                                   id="referror_split_comm_per_deal" value="{{isset($deal)
                                                            && $deal->referror_split_comm_per_deal  > 0  ?
                                                            $deal->referror_split_comm_per_deal : ''}}"
                                                                   class="form-control number-input"
                                                                   maxlength="13"/>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Referrer Split
                                                                Upfront %</label>
                                                            <input type="text" name="referror_split_agg_brk_sp_upfrt"
                                                                   placeholder="0.00%"
                                                                   id="referror_split_agg_brk_sp_upfrt" value="{{isset($deal)
                                                            && $deal->referror_split_agg_brk_sp_upfrt  > 0  ?
                                                            $deal->referror_split_agg_brk_sp_upfrt : ''}}"
                                                                   class="form-control number-input"
                                                                   maxlength="13"/>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Referrer Split
                                                                Trail %</label>
                                                            <input type="text" name="referror_split_agg_brk_sp_trail"
                                                                   placeholder="0.00%"
                                                                   id="referror_split_agg_brk_sp_trail" value="{{isset($deal)
                                                            && $deal->referror_split_agg_brk_sp_trail  > 0  ?
                                                            $deal->referror_split_agg_brk_sp_trail : ''}}"
                                                                   class="form-control number-input"
                                                                   maxlength="13"/>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-2">
                                            <div class="card-header">
                                                Broker split
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                     <div class="col-sm-3">
                                                      <div class="position-relative  form-group">
                                                          <label class="form-label font-weight-bold">Commission Model</label>
                                                          <select name="commission_model" id="commission_model" class="form-control">
                                                              <option value="">Select Model</option>
                                                              @foreach($commission_models as $commission_model)
                                                                  <option
                                                                      {{isset($deal)
                                                            && $deal->commission_model == $commission_model->id ?
                                                            'selected': ''}}
                                                                      value="{{$commission_model->id}}">{{ $commission_model->name }}</option>
                                                                  @endforeach
                                                              </select>
                                                      </div>
                                                  </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Fee per
                                                                Deal</label>
                                                            <input type="text" name="broker_split_fee_per_deal"
                                                                   placeholder="$0.00"
                                                                   id="broker_split_fee_per_deal" value="{{isset($deal)
                                                            && $deal->broker_split_fee_per_deal > 0 ?
                                                            $deal->broker_split_fee_per_deal : ''}}"
                                                                   class="form-control number-input"
                                                                   maxlength="13"/>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Broker Split
                                                                Upfront %</label>
                                                            <input type="text" name="broker_split_agg_brk_sp_upfrt"
                                                                   placeholder="0.00%"
                                                                   id="broker_split_agg_brk_sp_upfrt" value="{{isset($deal)
                                                            && $deal->broker_split_agg_brk_sp_upfrt  > 0  ?
                                                            $deal->broker_split_agg_brk_sp_upfrt : ''}}"
                                                                   class="form-control number-input"
                                                                   maxlength="13"/>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Broker Split
                                                                Trail %</label>
                                                            <input type="text" name="broker_split_agg_brk_sp_trail"
                                                                   placeholder="0.00%"
                                                                   id="broker_split_agg_brk_sp_trail" value="{{isset($deal)
                                                            && $deal->broker_split_agg_brk_sp_trail  > 0  ?
                                                            $deal->broker_split_agg_brk_sp_trail : ''}}"
                                                                   class="form-control number-input"
                                                                   maxlength="13"/>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!--<div class="card mb-2">
                                            <div class="card-header">
                                                Broker Staff split
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">


                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Fee Per
                                                                Deal</label>
                                                            <input type="text" name="broker_staff_split_comm_per_deal"
                                                                   placeholder="$0.00"
                                                                   id="broker_staff_split_comm_per_deal" value="{{isset($deal)
                                                            && $deal->broker_staff_split_comm_per_deal  > 0  ?
                                                            $deal->broker_staff_split_comm_per_deal : ''}}"
                                                                   class="form-control number-input"
                                                                   maxlength="13"/>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Broker Staff
                                                                Split Upfront %</label>
                                                            <input type="text"
                                                                   name="broker_staff_split_agg_brk_sp_upfrt"
                                                                   placeholder="0.00%"
                                                                   id="broker_staff_split_agg_brk_sp_upfrt" value="{{isset($deal)
                                                            && $deal->broker_staff_split_agg_brk_sp_upfrt  > 0  ?
                                                            $deal->broker_staff_split_agg_brk_sp_upfrt : ''}}"
                                                                   class="form-control number-input"
                                                                   maxlength="13"/>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Broker Staff
                                                                Split Trail %</label>
                                                            <input type="text"
                                                                   name="broker_staff_split_agg_brk_sp_trail"
                                                                   placeholder="0.00%"
                                                                   id="broker_staff_split_agg_brk_sp_trail" value="{{isset($deal)
                                                            && $deal->broker_staff_split_agg_brk_sp_trail  > 0  ?
                                                            $deal->broker_staff_split_agg_brk_sp_trail : ''}}"
                                                                   class="form-control number-input"
                                                                   maxlength="13"/>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->

                                        <div class="card mb-2">
                                            <div class="card-header">
                                                Broker Estimated
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Actual
                                                                Loan</label>
                                                            <input type="text" name="actual_loan"
                                                                   placeholder="Actual Loan"
                                                                   id="actual_loan" class="form-control number-input"
                                                                   value="{{isset($deal)
                                                            && $deal->actual_loan != '' ?
                                                            $deal->actual_loan : ''}}" maxlength="13"/>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Upfront</label>
                                                            <input type="text" name="broker_est_upfront"
                                                                   placeholder="Upfront"
                                                                   id="broker_est_upfront" value="{{isset($deal)
                                                            && $deal->broker_est_upfront  > 0  ?
                                                            $deal->broker_est_upfront : ''}}"
                                                                   class="form-control number-input" maxlength="13"/>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Trail</label>
                                                            <input type="text" name="broker_est_trail"
                                                                   placeholder="Trail"
                                                                   id="broker_est_trail" value="{{isset($deal)
                                                            && $deal->broker_est_trail  > 0  ?
                                                            $deal->broker_est_trail : ''}}"
                                                                   class="form-control number-input"
                                                                   maxlength="13"/>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Brokerage</label>
                                                            <input type="text" name="broker_est_brokerage"
                                                                   placeholder="Brokerage"
                                                                   id="broker_est_brokerage" value="{{isset($deal)
                                                            && $deal->broker_est_brokerage  > 0  ?
                                                            $deal->broker_est_brokerage : ''}}"
                                                                   class="form-control number-input"
                                                                   maxlength="13"/>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">

                                        </div>
                                        <div class="card mb-2">
                                            <div class="card-header">
                                                Commi$h Estimated
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">

                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Upfront</label>
                                                            <input type="text" name="agg_est_upfront"
                                                                   placeholder="Upfront"
                                                                   id="agg_est_upfront" value="{{isset($deal)
                                                            && $deal->agg_est_upfront != '' ?
                                                            $deal->agg_est_upfront : ''}}"
                                                                   class="form-control number-input" maxlength="13"/>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Trail</label>
                                                            <input type="text" name="agg_est_trail" placeholder="Trail"
                                                                   id="agg_est_trail" value="{{isset($deal)
                                                            && $deal->agg_est_trail != '' ?
                                                            $deal->agg_est_trail : ''}}"
                                                                   class="form-control number-input" maxlength="13"/>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Brokerage</label>
                                                            <input type="text" name="agg_est_brokerage"
                                                                   placeholder="Brokerage"
                                                                   id="agg_est_brokerage" value="{{isset($deal)
                                                            && $deal->agg_est_brokerage != '' ?
                                                            $deal->agg_est_brokerage : ''}}"
                                                                   class="form-control number-input" maxlength="13"/>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label class="form-label font-weight-bold">Note</label>
                                                            <textarea class="form-control" id="note" name="note"
                                                                      rows="5"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane tabs-animation fade" role="tabpanel" id="step-2">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-3">

                                                        <div class="form-group">

                                                            <label class="mr-sm-2 font-weight-bold">Type Amount</label>
                                                            <select class="form-control" id="commission_type_1"
                                                                    name="commission_type_1">
                                                                <option value="">Select Type</option>
                                                                @foreach($comm_types as $key =>$comm_type)
                                                                    <option value="{{$key}}">{{$comm_type}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <div class="form-group ">

                                                            <label class="mr-sm-2 font-weight-bold">Total Amount</label>
                                                            <input type="text" name="total_amount_1"
                                                                   placeholder="Total Amount"
                                                                   id="total_amount_1" class="form-control number-input"
                                                                   maxlength="13"/>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <div class="form-group ">
                                                            <label class="mr-sm-2 font-weight-bold">Date Statement</label>
                                                            <input type="text" data-toggle="datepicker"
                                                                   placeholder="dd/mm/yyyy" name="date_statement_1"
                                                                   id="date_statement_1" class="form-control "
                                                                   maxlength="13"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group ">
                                                            <label class="mr-sm-2 font-weight-bold">FMA Amount</label>
                                                            <input type="text" name="agg_amount_1"
                                                                   placeholder="FMA Amount"
                                                                   id="agg_amount_1" class="form-control number-input"
                                                                   maxlength="13"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">

                                                            <label class="mr-sm-2 font-weight-bold">Broker
                                                                Amount Paid</label>
                                                            <input type="text" name="broker_amount_1"
                                                                   placeholder="Broker Amount"
                                                                   id="broker_amount_1"
                                                                   class="form-control number-input" maxlength="13"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">

                                                            <label class="mr-sm-2 font-weight-bold">Date Paid</label>
                                                            <input type="text" data-toggle="datepicker"
                                                                   placeholder="dd/mm/yyyy" name="bro_amt_date_paid_1"
                                                                   id="bro_amt_date_paid_1" class="form-control "
                                                                   maxlength="13"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">

                                                            <label class="mr-sm-2 font-weight-bold">Broker Staff
                                                                Amount</label>
                                                            <input type="text" name="broker_staff_amount_1"
                                                                   placeholder="Broker Staff Amount"
                                                                   id="broker_staff_amount_1"
                                                                   class="form-control number-input" maxlength="13"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="mr-sm-2 font-weight-bold">Date Paid</label>
                                                            <input type="text" data-toggle="datepicker"
                                                                   placeholder="dd/mm/yyyy"
                                                                   name="bro_staff_amt_date_paid_1"
                                                                   id="bro_staff_amt_date_paid_1" class="form-control "
                                                                   maxlength="13"  />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="mr-sm-2 font-weight-bold">Referral
                                                                Amount</label>
                                                            <input type="text" name="referror_amount_1"
                                                                   placeholder="Referrer Amount"
                                                                   id="referror_amount_1"
                                                                   class="form-control number-input" maxlength="13"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3" >
                                                        <div class="form-group">
                                                            <label class="mr-sm-2 font-weight-bold">Date Paid</label>
                                                            <input type="text" data-toggle="datepicker"
                                                                   placeholder="dd/mm/yyyy" name="ref_amt_date_paid_1"
                                                                   id="ref_amt_date_paid_1" class="form-control .ui-datepicker"
                                                                   maxlength="13" />
                                                                   <input type="hidden" id="deal_id_1">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-primary float-right"
                                                                id="add_actual">Add
                                                        </button>
                                                        <button type="button" class="btn btn-success float-right"
                                                                id="update_actual" style="display: none;">Update
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="table-responsive">
                                                        <table class="table table-boarded" id="deal_commission">
                                                            <thead>
                                                            <tr>
                                                                <th>No.
                                                                </th>
                                                                <th>
                                                                    Type Amount
                                                                </th>
                                                                <th>
                                                                    Total Amount
                                                                </th>
                                                                <th>
                                                                    Date Statement
                                                                </th>
                                                                <th>
                                                                    FMA Amount
                                                                </th>
                                                                <th>
                                                                    Broker Amount
                                                                </th>
                                                                <th>
                                                                    Date Paid
                                                                </th>
                                                                <th>
                                                                    Broker Staff Amount
                                                                </th>
                                                                <th>
                                                                    Date Paid
                                                                </th>

                                                                <th>
                                                                    Referral Amount
                                                                </th>
                                                                <th>
                                                                    Date Paid
                                                                </th>
                                                                <th>
                                                                    Action
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="actuals_tbody">

                                                            @if(isset($deal,$deal_commissions) && count($deal_commissions)>0)
                                                                @foreach($deal_commissions as $key => $deal_commission)
                                                                    <tr>
                                                                        <td><span class="tr_counter">{{ $key+1
                                                        }}</span></td>
                                                                        <td>{{ $deal_commission->commission_type_name
                                                        }}</td>
                                                                        <td>{{ $deal_commission->total_amount }}</td>
                                                                        <td>{{
                                                        $deal_commission->date_statement }}</td>
                                                                        <td>{{
                                                        $deal_commission->agg_amount }}</td>
                                                                        <td>{{
                                                        $deal_commission->broker_amount }}</td>
                                                        <td>{{ $deal_commission->bro_amt_date_paid }}</td>
                                                                        <td>{{
                                                        $deal_commission->broker_staff_amount }}</td>
                                                                        <td>{{
                                                        $deal_commission->bro_staff_amt_date_paid }}</td>
                                                                        <td>{{ $deal_commission->referror_amount }}</td>
                                                                        <td>{{ $deal_commission->ref_amt_date_paid }}</td>
                                                                        <td>
                                                            <a href="javascript:void(0)" data-id="{{$deal_commission->id}}" onclick="editRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary" title="Edit"><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i></a></td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div id="step-3" class="tab-pane tabs-animation fade " role="tabpanel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="relationship_container">
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-4 text-center">
                                                                <label class="form-label font-weight-bold">Linked
                                                                    to</label>
                                                            </div>
                                                            <div class="col-sm-4 text-center">
                                                                <label
                                                                    class="form-label font-weight-bold">Relation</label> <?php //print_r($clients); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="relation_conbody" id="relation_conbody">
                                                        @if(isset($deal,$deal->relations) && count($deal->relations) >0 )

                                                            @foreach($deal->relations as $rkey => $crelation )

                                                                <div class="col-sm-12 relation_child"
                                                                     id="rel_{{$rkey}}">
                                                                    <div class="row">
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <input type="hidden"
                                                                                       name="relationship[{{$rkey}}][old_id]"
                                                                                       value="{{$crelation->id}}"/>
                                                                                <select
                                                                                    name="relationship[{{$rkey}}][linked_to]"
                                                                                    id="relationship_{{$rkey}}_linked_to"
                                                                                    class="form-control linked_to_selector">
                                                                                    <option value="">Select Client
                                                                                    </option>
                                                                                    @foreach($clients as $client)
                                                                                        <option value="{{$client->id}}" {{(isset
                                                                                ($crelation) && $crelation->client_id == $client->id ?
                                                        'selected="selected"' :'')}}>{{$client->surname.' '.$client->first_name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-group">
                                                                                <select
                                                                                    name="relationship[{{$rkey}}][relation]"
                                                                                    id="relationship_{{$rkey}}_relation"
                                                                                    class="form-control">
                                                                                    <option value="">Select Relation
                                                                                    </option>
                                                                                    @foreach($relations as $relation)
                                                                                        <option
                                                                                            value="{{$relation->id}}" {{ (isset
                                                                                ($crelation) && $crelation->type ==
                                                                                 $relation->id ?
                                                        'selected="selected"' :'') }}>{{$relation->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <button type="button" class="btn btn-danger"
                                                                                    onclick="removerelation
                                                       (this)" data-id="{{$rkey}}"><i class="fa fa-trash"></i></button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="col-sm-12 relation_child" id="rel_0">
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <select name="relationship[0][linked_to]"
                                                                                    id="relationship_0_linked_to"
                                                                                    class="form-control linked_to_selector">
                                                                                <option value="">Select Client</option>
                                                                                @foreach($clients as $client)
                                                                                    <option
                                                                                        value="{{$client->id}}">{{$client->surname.' '.$client->first_name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <select name="relationship[0][relation]"
                                                                                    id="relationship_0_relation"
                                                                                    class="form-control">
                                                                                <option value="">Select Relation
                                                                                </option>
                                                                                @foreach($relations as $relation)
                                                                                    <option
                                                                                        value="{{$relation->id}}">{{$relation->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <button type="button" class="btn btn-danger"
                                                                                onclick="removerelation
                                                       (this)" data-id="0"><i class="fa fa-trash"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                         <!--    <div class="col-sm-12 relation_child" id="rel_1">
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <select name="relationship[1][linked_to]"
                                                                                    id="relationship_1_linked_to"
                                                                                    class="form-control linked_to_selector">
                                                                                <option value="">Select Client</option>
                                                                                @foreach($clients as $client)
                                                                                    <option
                                                                                        value="{{$client->id}}">{{$client->display_name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <select name="relationship[1][relation]"
                                                                                    id="relationship_1_relation"
                                                                                    class="form-control">
                                                                                <option value="">Select Relation
                                                                                </option>
                                                                                @foreach($relations as $relation)
                                                                                    <option
                                                                                        value="{{$relation->id}}">{{$relation->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <button type="button" class="btn btn-danger"
                                                                                onclick="removerelation
                                                       (this)" data-id="1"><i class="fa fa-trash"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div> -->
                                                        @endif


                                                    </div>

                                                </div>
                                                <div class="col-sm-12 mb-5">
                                                    <div class="float-right">
                                                        <button type="button" class="btn btn-primary"
                                                                id="add_new_relation"
                                                                onclick="addNewRelation(this)">Add new Relation
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="divider"></div>
                        <div class="clearfix">
                            <button type="submit" id="finish-btn"
                                    class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-success">
                                Save
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-section')
    <script type="text/javascript"
            src="{{asset('front-assets/vendors/@chenfengyuan/datepicker/dist/datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('front-assets/vendors/daterangepicker/daterangepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('front-assets/js/form-components/datepicker.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('front-assets/vendors/smartwizard/dist/js/jquery.smartWizard.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('front-assets/js/form-components/form-wizard.js')}}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>

        var relationstring = '';
        @foreach($relations as $relation)
            relationstring += '<option value="{{$relation->id}}">{{$relation->name}}</option>';
        @endforeach

        var clientstring = '';
        @foreach($clients as $client)
            clientstring += '<option value="{{$client->id}}">{{$client->surname.' '.$client->first_name}}</option>';
        @endforeach

        $("#smartwizard").on("showStep", function (e, anchorObject, stepNumber, stepDirection) {
            /*if (stepNumber == 1) {
                $('#finish-btn').show();
                $('#reset-btn').hide();
                $('#next-btn').hide();
            } else {
                $('#finish-btn').hide();
                $('#reset-btn').show();
                $('#next-btn').show();
            }*/
        });
  
        

        var deal_client_types = '';
        @foreach($deal_client_types as $deal_client_type)
            deal_client_types += '<option value="{{$deal_client_type->id}}">{{$deal_client_type->name}}</option>';
        @endforeach

        $('#isIndividual').click(function () {
            //var checker = $("#isIndividual").toggle(this.checked);
        });

        var rel_counter = {{ (isset($deal,$deal->relations) && count($deal->relations) >0) ? count
        ($deal->relations) : 2  }};
        var task_counter = {{ isset($deal,$deal->tasks) && count($deal->tasks) > 0 ? count($deal->tasks)
        : 2  }};

        var actuals_tbody_counter = 0;
        jQuery(document).ready(function () {

            jQuery('body').on('click', '#add_actual', function () {

                var commission_type = jQuery('#commission_type_1').val();
                var total_amount = jQuery('#total_amount_1').val();
                var date_statement = jQuery('#date_statement_1').val();
                var agg_amount = jQuery('#agg_amount_1').val();
                var broker_amount = jQuery('#broker_amount_1').val();
                var broker_staff_amount = jQuery('#broker_staff_amount_1').val();
                var bro_amt_date_paid = jQuery('#bro_amt_date_paid_1').val();
                var bro_staff_amt_date_paid = jQuery('#bro_staff_amt_date_paid_1').val();
                var referror_amount = jQuery('#referror_amount_1').val();
                var ref_amt_date_paid = jQuery('#ref_amt_date_paid_1').val();
                if (commission_type == '') {
                    printErrorMsg("Please select type!");
                    return false;
                }
                if (total_amount == '') {
                    printErrorMsg("Please enter amount!");
                    return false;
                }

                var commission_type_label = jQuery('#commission_type_1 option[value="' + commission_type + '"]').text();
                jQuery('#actuals_tbody').append(`<tr><td class="counter"><span class="tr_counter"></span><input type="hidden" name="actuals_comm[` + actuals_tbody_counter + `][commission_type]" value="` + commission_type + `" /><input type="hidden" name="actuals_comm[` + actuals_tbody_counter + `][total_amount]" value="` + total_amount + `" /><input type="hidden" name="actuals_comm[` + actuals_tbody_counter + `][date_statement]" value="` + date_statement + `" /><input type="hidden" name="actuals_comm[` + actuals_tbody_counter + `][agg_amount]" value="` + agg_amount + `" /><input type="hidden" name="actuals_comm[` + actuals_tbody_counter + `][broker_amount]" value="` + broker_amount + `" /><input type="hidden" name="actuals_comm[` + actuals_tbody_counter + `][broker_staff_amount]" value="` + broker_staff_amount + `" /><input type="hidden" name="actuals_comm[` + actuals_tbody_counter + `][bro_staff_amt_date_paid]" value="` + bro_staff_amt_date_paid + `" /><input type="hidden" name="actuals_comm[` + actuals_tbody_counter + `][bro_amt_date_paid]" value="` + bro_amt_date_paid + `" /><input type="hidden" name="actuals_comm[` + actuals_tbody_counter + `][referror_amount]" value="` + referror_amount + `" /><input type="hidden" name="actuals_comm[` + actuals_tbody_counter + `][ref_amt_date_paid]" value="` + ref_amt_date_paid + `" /></td><td>` + commission_type_label + `</td><td>` + total_amount + `</td><td>` + date_statement + `</td><td>` + agg_amount + `</td><td>` + broker_amount + `</td><td>` + broker_staff_amount + `</td><td>` + bro_staff_amt_date_paid + `</td><td>` + bro_amt_date_paid + `</td><td>` + referror_amount + `</td><td>` + ref_amt_date_paid + `</td></tr>`);
                actuals_tbody_counter++;
                rearrangeCounterNo();
                return false;
            })

            $("#broker_id").select2({

                placeholder: "Select Broker",
            });
            $("#referror_split_referror").select2({

                placeholder: "Select Referror",
            });
            $("#lender_id").select2({

                placeholder: "Select Lender",
            });

            $("#product_id").select2({

                placeholder: "Select Product",
            });
            $("#contact_id").select2({

                placeholder: "Select Client",
            });
            $("#linked_to").select2({

                placeholder: "Select Deal",
            });
            $("#status").select2({

                placeholder: "Select Status",
            });
            jQuery('.relation_to_client').select2({

                placeholder: "Select Client",
            })
            @if(isset($broker) && $broker->state > 0)
            jQuery('#broker_staff_id option').attr('disabled', 'disabled');
            jQuery('#broker_staff_id option[value=""]').removeAttr('disabled')
            jQuery('#broker_staff_id option[data-parent_id="{{$broker->state}}"]').removeAttr('disabled');
            /*$("#broker_staff_id").select2({

                placeholder: "Select Broker Staff",
            });*/
            @else
            jQuery('#broker_staff_id option').attr('disabled', 'disabled');
            jQuery('#broker_staff_id option[value=""]').removeAttr('disabled')
            /*$("#broker_staff_id").select2({

                placeholder: "Select Broker Staff",
            });*/
            @endif

            @if(isset($deal) && $deal->broker_id > 0)
            jQuery('#broker_staff_id option').attr('disabled', 'disabled');
            jQuery('#broker_staff_id option[value=""]').removeAttr('disabled')
            jQuery('#broker_staff_id option[data-parent_id="{{$deal->broker_id}}"]').removeAttr('disabled');
            @endif


            jQuery('body').on('change', '#broker_id', function () {
                var currentState = jQuery(this).val();
                if (currentState != '') {
                    jQuery('#broker_staff_id option').attr('disabled', 'disabled');
                    jQuery('#broker_staff_id option[value=""]').removeAttr('disabled')
                    jQuery('#broker_staff_id option[data-parent_id="' + currentState + '"]').removeAttr('disabled');
                    jQuery('#broker_staff_id').val('');
                    /*jQuery('#broker_staff_id').select2({

                        placeholder: "Select Staff",
                    })*/
                } else {
                    jQuery('#broker_staff_id option').attr('disabled', 'disabled');
                    jQuery('#broker_staff_id option[value=""]').removeAttr('disabled')
                    /* jQuery('#broker_staff_id').select2({

                         placeholder: "Select Staff",
                     })*/
                }
            })
        });

        /*function addNewRelation(current) {
            var htmlCon = '<div class="col-sm-12" id="rel_' + rel_counter + '"><div class="row"><div class="col-sm-6"><div ' +
                'class="form-group"><select name="relationship[' + rel_counter + '][linked_to]" ' +
                'id="relationship_' + rel_counter + '_linked_to" class="form-control relation_to_client"><option value="">Select ' +
                'Client</option>' + clientstring + '</select></div></div><div class="col-sm-4"><div class="form-group"><select ' +
                'name="relationship[' + rel_counter + '][relation]" id="relationship_' + rel_counter + '_relation" ' +
                'class="form-control"><option value="">Select Type</option>' + deal_client_types + '</select></div></div><div class="col-sm-2"><button type="button" class="btn btn-danger" onclick="removerelation (this)" data-id="' + rel_counter + '"><i class="fa fa-trash"></i> ' +
                '</button></div></div></div>';
            jQuery('#relation_conbody').append(htmlCon);
            jQuery('.relation_to_client').select2({

                placeholder: "Select Client",
            })
            rel_counter++;
        }*/

        function addNewRelation(current) {
            var htmlCon = '<div class="col-sm-12" id="rel_' + rel_counter + '"><div class="row"><div class="col-sm-4"><div ' +
                'class="form-group"><select  name="relationship[' + rel_counter + '][linked_to]" ' +
                'id="relationship_' + rel_counter + '_linked_to" class="form-control linked_to_selector"><option ' +
                'value="">Select ' +
                'Client</option>' + clientstring + '</select></div></div><div class="col-sm-4"><div class="form-group"><select ' +
                'name="relationship[' + rel_counter + '][relation]" id="relationship_' + rel_counter + '_relation" ' +
                'class="form-control"><option value="">Select Relation</option>' + relationstring + '</select></div></div><div class="col-sm-2"><button type="button" class="btn btn-danger" onclick="removerelation (this)" data-id="' + rel_counter + '"><i class="fa fa-trash"></i> ' +
                '</button></div></div></div>';
            jQuery('#relation_conbody').append(htmlCon);
            jQuery('.linked_to_selector').select2({
                placeholder: "Select Linked To",
            })
            rel_counter++;
        }

        function removerelation(current) {
            var row_id = jQuery(current).attr('data-id');
            if (typeof row_id != "undefined") {
                jQuery('#rel_' + row_id).remove()
            }
        }

        function editRecord(current){
            console.log(jQuery(current).attr('data-id'));
            var dealId = jQuery(current).attr('data-id');
            $.ajax({
                url:'{{route("getDealCommission")}}',
                type:'GET',
                data:{dealId:dealId},
                success:function(response){
                    $("#commission_type_1").val(response.type);
                    $("#total_amount_1").val(response.total_amount);
                    $("#date_statement_1").val(response.date_statement);
                    $("#agg_amount_1").val(response.agg_amount);
                    $("#broker_amount_1").val(response.broker_amount);
                    $('#bro_amt_date_paid_1').val(response.bro_amt_date_paid);
                    $('#broker_staff_amount_1').val(response.broker_staff_amount);
                    $("#bro_staff_amt_date_paid_1").val(response.bro_staff_amt_date_paid);
                    $("#referror_amount_1").val(response.referror_amount);
                    $("#ref_amt_date_paid_1").val(response.ref_amt_date_paid);
                    $("#deal_id_1").val(response.deal_id);
                    $("#add_actual").css('display', 'none');
                    $("#update_actual").css('display', 'block');
                    $("#update_actual").attr('deal-id', dealId);

                }
            })
        }

        $('#update_actual').click(function(){

                var commission_type = jQuery('#commission_type_1').val();
                var total_amount = jQuery('#total_amount_1').val();
                var date_statement = jQuery('#date_statement_1').val();
                var agg_amount = jQuery('#agg_amount_1').val();
                var broker_amount = jQuery('#broker_amount_1').val();
                var broker_staff_amount = jQuery('#broker_staff_amount_1').val();
                var bro_amt_date_paid = jQuery('#bro_amt_date_paid_1').val();
                var bro_staff_amt_date_paid = jQuery('#bro_staff_amt_date_paid_1').val();
                var referror_amount = jQuery('#referror_amount_1').val();
                var ref_amt_date_paid = jQuery('#ref_amt_date_paid_1').val();
                var id = jQuery('#update_actual').attr('deal-id');
                var deal_id = $("#deal_id_1").val();
                var _token = "{{ csrf_token() }}";
                if (commission_type == '') {
                    printErrorMsg("Please select type!");
                    return false;
                }
                if (total_amount == '') {
                    printErrorMsg("Please enter amount!");
                    return false;
                }

                $.ajax({
                    url:"{{route('updateDealCommission')}}",
                    type: "POST",
                    data:{_token:_token,commission_type:commission_type, total_amount:total_amount, date_statement:date_statement, agg_amount:agg_amount, broker_amount:broker_amount, broker_staff_amount:broker_staff_amount,bro_amt_date_paid:bro_amt_date_paid, bro_staff_amt_date_paid:bro_staff_amt_date_paid, referror_amount:referror_amount, ref_amt_date_paid:ref_amt_date_paid, deal_id:deal_id, id:id},
                    success:function(response){
                        if(response == 1){
                            location.reload();
                        }
                    }
                })
        })

        $('#commission_model').change(function(){
            var commission_model = $(this).val();
            var broker_id = $('#broker_id').val();
            var loan_amount = $('#broker_est_loan_amt').val();
            if(broker_id != '' && commission_model != ''){
                $.ajax({
                    url:'{{route("admin.deal.broker_record")}}',
                    type:"GET",
                    data:{commission_model:commission_model, broker_id:broker_id},
                    success:function(response){
                        //console.log(response)
                        // if(loan_amount != ''){

                        // }
                        $('#broker_split_fee_per_deal').val(response.flat_fee_chrg);
                        $("#broker_split_agg_brk_sp_upfrt").val(response.upfront_per);
                        $("#broker_split_agg_brk_sp_trail").val(response.trail_per);
                    }
                })
            }
        })

        function addNewTask(current) {
            var task_tr = '<tr id="task_rw_' + task_counter + '"><td><input type="text" data-toggle="datepicker"  placeholder="dd/mm/yyyy"  name="tasks[' + task_counter + '][follow_up_date]" class="task_followup_date form-control" id="tasks_' + task_counter + '_follow_up_date" placeholder="Follow Up Date" /></td><td><input type="text" name="tasks[' + task_counter + '][processor]" class=" form-control" id="tasks_' + task_counter + '_processor" placeholder="Processor"  /></td><td><input type="text" name="tasks[' + task_counter + '][details]" class=" form-control"id="tasks_' + task_counter + '_details"  placeholder="Detail" /></td><td><input type="text" name="tasks[' + task_counter + '][user]" class=" form-control" id="tasks_' + task_counter + '_user" placeholder="User"  /></td><td><button class="btn btn-danger" onclick="return removetask(this)" data-id="' + task_counter + '"><i class="fa fa-trash"></i></button></td></tr>';
            jQuery('#task_tbody').append(task_tr);
            $('[data-toggle="datepicker"]').datepicker({
                format: 'dd/mm/yyyy',
                autoHide: true
            });
            task_counter++;

        }

        function removetask(current) {
            var row_id = jQuery(current).attr('data-id');
            if (typeof row_id != "undefined") {
                jQuery('#task_rw_' + row_id).remove()
            }
        }

    
        

        function saveForm(current) {
            showLoader();
            //console.log($("form").serializeArray());
            //alert();
            $.ajax({
                url: jQuery(current).attr('action'),
                type: 'POST',
                dataType:'json',
                data: $("form").serialize(),
                success: function (data) {
                    console.log(data)
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                        hideLoader();

                    } else if (!$.isEmptyObject(data.errors)) {
                        printErrorMsg(data.errors);
                        hideLoader();
                    } else {
                        successMessage(data.success);
                        setTimeout(function () {
                            window.location.href = data.link
                        }, 3000);
                        hideLoader();

                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    if (IsJsonString(jqXHR.responseText)) {
                        var respo = JSON.parse(jqXHR.responseText);
                        errorMessage(respo.message);
                        printErrorMsg(respo.errors)
                        hideLoader();
                    } else {
                        errorMessage(jqXHR.responseText)
                        hideLoader();
                    }
                }
            });
            return false;
        }


        jQuery('body').on('change', '#broker_id', function () {
            getbrokercommission();
            getlendercommission();

        })

        jQuery('body').on('change', '#lender_id', function () {
            getbrokercommission();
            getlendercommission();
        })
        jQuery('body').on('change', '#product_id', function () {
            getbrokercommission();
            getlendercommission();
            getreferrorcommission();
        });

        jQuery('body').on('change', '#referror_split_referror', function () {
            getreferrorcommission();
        });

        var agg_upfront = agg_trail = agg_brokrage = 0;

        function getbrokercommission() {
            var broker_id = jQuery('#broker_id').val();
            var product_id = jQuery('#product_id').val();
            var lender_id = jQuery('#lender_id').val();
            jQuery('#broker_split_commis_model').val('');
            jQuery('#broker_split_fee_per_deal').val('');
            jQuery('#broker_split_agg_brk_sp_upfrt').val('');
            jQuery('#broker_split_agg_brk_sp_trail').val('')
            if (broker_id != '' && product_id != '' && lender_id != '') {
                $.ajax({
                    url: "{{ route('admin.brokers.getcommission') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "lender_id": lender_id,
                        "product_id": product_id,
                        "broker_id": broker_id,

                    },
                    success: function (data) {

                        if (!$.isEmptyObject(data.error)) {
                            printErrorMsg(data.error);
                            hideLoader();

                        } else if (!$.isEmptyObject(data.errors)) {
                            printErrorMsg(data.errors);
                            hideLoader();
                        } else {

                            if (!$.isEmptyObject(data.model)) {
                                jQuery('#broker_split_commis_model').val(data.model.commission_model);
                                jQuery('#broker_split_fee_per_deal').val(data.model.fee_per_deal);
                                //jQuery('#broker_split_fee_per_deal').val(data.model.flat_fee_chrg);
                                jQuery('#broker_split_agg_brk_sp_upfrt').val(data.model.upfront_per);
                                jQuery('#broker_split_agg_brk_sp_trail').val(data.model.trail);
                            }

                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        if (IsJsonString(jqXHR.responseText)) {
                            var respo = JSON.parse(jqXHR.responseText);
                            errorMessage(respo.message);
                            printErrorMsg(respo.errors)
                            hideLoader();
                        } else {
                            errorMessage(jqXHR.responseText)
                            hideLoader();
                        }
                    }
                });
            }

            return false;
        }



        function getreferrorcommission() {
            var referror_split_referror = jQuery('#referror_split_referror').val();
            var product_id = jQuery('#product_id').val();

            jQuery('#referror_split_comm_per_deal').val('');
            jQuery('#referror_split_agg_brk_sp_upfrt').val('');
            jQuery('#referror_split_agg_brk_sp_trail').val('');

            //if (product_id != '' && referror_split_referror != '') {
                if (referror_split_referror != '') {
                $.ajax({
                    url: "{{ route('admin.contact.getcommission') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "referror_split_referror": referror_split_referror,
                        //"product_id": product_id

                    },
                    success: function (data) {

                        if (!$.isEmptyObject(data.error)) {
                            printErrorMsg(data.error);
                            hideLoader();

                        } else if (!$.isEmptyObject(data.errors)) {
                            printErrorMsg(data.errors);
                            hideLoader();
                        } else {
                            console.log(data.model.upfront)
                            if (!$.isEmptyObject(data.model)) {

                                jQuery('#referror_split_comm_per_deal').val(data.model.fee_per_deal);
                                jQuery('#referror_split_agg_brk_sp_upfrt').val(data.model.upfront_per);
                                jQuery('#referror_split_agg_brk_sp_trail').val(data.model.trail);
                            }

                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        if (IsJsonString(jqXHR.responseText)) {
                            var respo = JSON.parse(jqXHR.responseText);
                            errorMessage(respo.message);
                            printErrorMsg(respo.errors)
                            hideLoader();
                        } else {
                            errorMessage(jqXHR.responseText)
                            hideLoader();
                        }
                    }
                });
            }

            return false;
        }

        jQuery('body').on('change keyup blur', '#broker_est_loan_amt', function () {
            getlendercommission();
            //calculateaggesti();
            var loan_amount = $(this).val();
            $('#actual_loan').val(loan_amount);
        });

        jQuery('body').on('change', '#commission_type_1', function () {

            getcommissiontype1datareferror(1);
        })

        function calculateaggesti() {
            jQuery('#agg_est_upfront').val('');
            jQuery('#agg_est_trail').val('');
            jQuery('#agg_est_brokerage').val('');
            jQuery('#broker_est_upfront').val('');
            jQuery('#broker_est_trail').val('');
            jQuery('#broker_est_brokerage').val('');
            var loan_amount = jQuery('#broker_est_loan_amt').val();

            if (parseFloat(loan_amount) > 0) {
                if (parseFloat(agg_upfront) > 0) {
                    jQuery('#agg_est_upfront').val(parseFloat((parseFloat(loan_amount) * parseFloat(agg_upfront)) / 100).toFixed(2));

                    jQuery('#broker_est_upfront').val(parseFloat((parseFloat(loan_amount) * parseFloat(agg_upfront)) / 100).toFixed(2));
                }
                if (parseFloat(agg_trail)) {
                    jQuery('#agg_est_trail').val(parseFloat(((parseFloat(loan_amount) * parseFloat(agg_trail)) / 100) / 12)
                        .toFixed(2));
                    jQuery('#broker_est_trail').val(parseFloat(((parseFloat(loan_amount) * parseFloat(agg_trail)) / 100) / 12)
                        .toFixed(2));

                }
                if (parseFloat(agg_trail)) {
                    jQuery('#agg_est_brokerage').val(parseFloat(((parseFloat(loan_amount) * parseFloat(agg_brokrage))
                        / 100)).toFixed(2));
                    jQuery('#broker_est_brokerage').val(parseFloat(((parseFloat(loan_amount) * parseFloat(agg_brokrage))
                        / 100)).toFixed(2));
                }
            }

        }

        function getlendercommission() {
            agg_upfront = agg_trail = agg_brokrage = 0
            var product_id = jQuery('#product_id').val();
            var lender_id = jQuery('#lender_id').val();
            jQuery('#agg_est_upfront').val('');
            jQuery('#agg_est_trail').val('');
            jQuery('#agg_est_brokerage').val('');
            if (product_id != '' && lender_id != '') {
                $.ajax({
                    url: "{{ route('admin.lendercommissionschedule.getsingle') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "lender_id": lender_id,
                        "product_id": product_id,
                    },
                    success: function (data) {

                        if (!$.isEmptyObject(data.error)) {
                            printErrorMsg(data.error);
                            hideLoader();

                        } else if (!$.isEmptyObject(data.errors)) {
                            printErrorMsg(data.errors);
                            hideLoader();
                        } else {

                            if (!$.isEmptyObject(data.model)) {
                                agg_upfront = data.model.upfront;
                                agg_trail = data.model.trail;
                                agg_brokrage = data.model.brokrage;
                                calculateaggesti();
                            }

                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        if (IsJsonString(jqXHR.responseText)) {
                            var respo = JSON.parse(jqXHR.responseText);
                            errorMessage(respo.message);
                            printErrorMsg(respo.errors)
                            hideLoader();
                        } else {
                            errorMessage(jqXHR.responseText)
                            hideLoader();
                        }
                    }
                });
                return false;
            }

        }

        function getcommissiontype1databroker(id) {
            var commission_type = jQuery('#commission_type_' + id).val();
            var product_id = jQuery('#product_id').val();
            var broker_id = jQuery('#broker_id').val();
            jQuery('#broker_split_commis_model').val('');
            jQuery('#broker_split_fee_per_deal').val('');
            jQuery('#broker_split_agg_brk_sp_upfrt').val('');
            jQuery('#broker_split_agg_brk_sp_trail').val('')
            if (broker_id != '' && product_id != '' && commission_type != '') {
                $.ajax({
                    url: "{{ route('admin.brokers.getcommission') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "commission_type": commission_type,
                        "product_id": product_id,
                        "broker_id": broker_id,

                    },
                    success: function (data) {

                        if (!$.isEmptyObject(data.error)) {
                            printErrorMsg(data.error);
                            hideLoader();

                        } else if (!$.isEmptyObject(data.errors)) {
                            printErrorMsg(data.errors);
                            hideLoader();
                        } else {

                            if (!$.isEmptyObject(data.model)) {

                            }

                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        if (IsJsonString(jqXHR.responseText)) {
                            var respo = JSON.parse(jqXHR.responseText);
                            errorMessage(respo.message);
                            printErrorMsg(respo.errors)
                            hideLoader();
                        } else {
                            errorMessage(jqXHR.responseText)
                            hideLoader();
                        }
                    }
                });
            }

            return false;
        }

        function getcommissiontype1datareferror(id) {

        }

        function rearrangeCounterNo() {
            jQuery('#actuals_tbody .tr_counter').each(function (i) {
                $(this).html(i + 1);
            });
        }
    </script>
    @if(Session::has('message'))
        <script>
            successMessage("{{ Session::get('message') }}")
        </script>
    @endif
    <style>
        option:disabled {
            display: none;
        }

        .select2-container--bootstrap4, .select2-container--default {
            width: 100% !important;
        }
    </style>
@endpush


