@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    View Deal::{{$deal->id}}

@endsection
@section('page_title_con')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                View Deal::{{$deal->id}} <a class="btn
                btn-primary text-white" href="{{route('admin.deals.edit',encrypt($deal->id))}}"><i
                        class="pe-7s-pen
                btn-icon-wrapper"></i> </a>
            </h4>
        </div>
        <div class="page-rightheader ml-auto d-lg-flex d-none">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/><path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/></svg><span class="breadcrumb-icon"> Home</span></a></li>
                <li class="breadcrumb-item " aria-current="page">
                    <a href="{{route('admin.deals.list')}}">Deals</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">                             View Deal::{{$deal->loan_ref}}
                </li>
            </ol>
        </div>
    </div>


@endsection
@section('body')
    <div id="" class="panel panel-primary">
        <div class="tab-menu-heading">
            <div class="tabs-menu ">
    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav panel-tabs">
        <li class="nav-item">
            <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
                <span>Main</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
                <span>Clients</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                <span>Upfront</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3">
                <span>Trail</span>
            </a>
        </li>
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#tab-content-4">
                <span>Tasks</span>
            </a>
        </li>
    </ul>
    </div>
    </div>
    <div class="panel-body tabs-menu-body">
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
            <div class="row" >
                <div class="col-lg-12 col-md-12">
                    <div class="main-card mb-3 ">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-3 d-flex align-items-stretch">
                                        <div class="card">
                                            <div class="card-header font-weight-bold bg-azure-darker text-white">
                                                INFORMATION
                                            </div>
                                            <div class="card-body">
                                                <p class="mb-2"><b>Broker : </b><a class="active_a" href="{{route('admin.brokers.view',encrypt($deal->broker_id))}}" target="_blank">{{$deal->broker_display_name}}</a></p>
                                                <p class="mb-2"><b>Broker Staff : </b>{{$deal->broker_staff_display_name}}</p>
                                                <p class="mb-2"><b>Client : </b><a class="active_a" href="{{ route('admin.contact.edit',
                                                encrypt($deal->contact_id))
                                                 }}">{{$deal->preferred_name}}</a></p>
                                                <p class="mb-2"><b>Product : </b>{{$deal->productname}}</p>
                                                <p class="mb-2"><b>Lender : </b>{{$deal->lendername}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 d-flex align-items-stretch">
                                        <div class="card">
                                            <div class="card-header font-weight-bold bg-orange-darker text-white">
                                                LOAN DETAIL
                                            </div>
                                            <div class="card-body">
                                                <p class="mb-2"><b>Loan amount : </b><span style="color:red;">
                                                    @if($deal->actual_loan != null)
                                                        ${{number_format($deal->actual_loan, 2)}}
                                                    @else
                                                    0.00 $
                                                    @endif</span></p>
                                                <p class="mb-2"><b>Loan Ref : </b>
                                                        {{$deal->linked_to_loan_ref}}
                                                    </p>
                                                    <p class="mb-2"><b>Application No: </b>
                                                        {{$deal->application_no}}
                                                    </p>
                                                @if($deal->linkto_pk_id)
                                                <p class="mb-2"><b>Linked To : </b> <a href="{{route('admin.deals.view',encrypt($deal->linkto_pk_id))}}" class="active_a">{{$deal->linkto_pk_id}}-{{$deal->linked_to_display_name}}</a></p>
                                                @endif
                                                <p class="mb-2"><b>Status : </b>{{$deal->status}}</p>
                                                <p class="mb-2"><b>Status Date : </b>@if($deal->status_date !='')
                                                        {{date('d/m/Y',strtotime($deal->status_date))}} @endif</p>
                                                <p class="mb-2"><b>Proposed Settlement : </b>{{date('d/m/Y',strtotime($deal->proposed_settlement))}}</p>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 d-flex align-items-stretch">
                                        <div class="card">
                                            <div class="card-header font-weight-bold bg-red-dark text-white">
                                                REFERROR SPLIT
                                            </div>
                                            <div class="card-body">
                                                <p class="mb-2"><b>Referror : </b>{{$deal->refer_display_name}}</p>
                                                <p class="mb-2"><b>Comm. per Deal : </b>{{$deal->referror_split_comm_per_deal}}</p>
                                                <p class="mb-2"><b>Referrer Split (Upfront %) : </b>{{$deal->referror_split_agg_brk_sp_upfrt}}</p>
                                                <p class="mb-2"><b>Referrer Split (Trail %) : </b>{{$deal->referror_split_agg_brk_sp_trail}}</p>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-sm-3 d-flex align-items-stretch">
                                        <div class="card">
                                            <div class="card-header font-weight-bold bg-lime-darker text-white">
                                                BROKER SPLIT
                                            </div>
                                            <div class="card-body">

                                                <p class="mb-2"><b>Commission Model : </b>{{$deal->commission_model_display_name}}</p>
                                                <p class="mb-2"><b>Fee per Deal : </b>{{$deal->broker_split_fee_per_deal}}</p>
                                                <p class="mb-2"><b>Broker Split (Upfront %) : </b>{{$deal->broker_split_agg_brk_sp_upfrt}}</p> 
                                                <p class="mb-2"><b>Broker Split (Trail %) : </b>{{$deal->broker_split_agg_brk_sp_trail}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="main-card mb-3 ">
                   <div class="row">
                    <!--    <div class="col-sm-3 d-flex align-items-stretch">
                            <div class="card">
                                <div class="card-header font-weight-bold bg-yellow-dark text-white">
                                    BROKER STAFF SPLIT
                                </div>
                                <div class="card-body">

                                    <p class="mb-2"><b>Comm. per Deal : </b>{{$deal->broker_staff_split_comm_per_deal}}</p>
                                    <p class="mb-2"><b>Agg/Brk Split (Upfront %) : </b>{{$deal->broker_staff_split_agg_brk_sp_upfrt}}</p>
                                    <p class="mb-2"><b>Agg/Brk Split (Trail %) : </b>{{$deal->broker_staff_split_agg_brk_sp_trail}}</p>
                                </div>
                            </div>
                    
                        </div>-->    
                    <div class="col-sm-3 d-flex align-items-stretch">
                            <div class="card">
                                <div class="card-header font-weight-bold bg-cyan-dark text-white">
                                    BROKER ESTIMATED
                                </div>
                                <div class="card-body">
                                    <p class="mb-2"><b>Loan amount : </b>
                                    <span style="color:red;">
                                        @if($deal->actual_loan != null)
                                            ${{number_format($deal->actual_loan, 2)}}
                                        @else
                                            0.00 $
                                        @endif
                                    </span></p>
                                    <p class="mb-2"><b>Upfront : </b>{{$deal->broker_est_upfront}}</p>
                                    <p class="mb-2"><b>Trail : </b>{{$deal->broker_est_trail}}</p>
                                    <p class="mb-2"><b>Brokerage : </b>{{$deal->broker_est_brokerage}}</p>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 d-flex align-items-stretch">
                            <div class="card">
                                <div class="card-header font-weight-bold bg-gray-dark text-white">
                                    AGG ESTIMATED
                                </div>
                                <div class="card-body">

                                    <p class="mb-2"><b>Upfront : </b>{{$deal->agg_est_upfront}}</p>
                                    <p class="mb-2"><b>Trail : </b>{{$deal->agg_est_trail}}</p>
                                    <p class="mb-2"><b>Brokerage : </b>{{$deal->agg_est_brokerage}}</p>

                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <div class="main-card mb-3 ">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header font-weight-bold bg-indigo-dark text-white">NOTE</div>
                                <div class="card-body">
                                    {{$deal->note}}
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="tab-pane tabs-animation fade show " id="tab-content-1" role="tabpanel">
            <div class="col-sm-12">
                <div class="row">
                   <div class="col-sm-12">
                       <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    Relations
                                </div>
                            </div>
                           <div class="card-body">
                               <div class="col-sm-12">
                                   <div class="table-responsive">
                                   <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Name</th>
                                                        <th>Relation</th> 
                                                        <!-- <th>Mail Out?</th> -->
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(count($deal->relationDetails)>0)
                                                    @foreach($deal->relationDetails as $ke => $relationDetail)
                                                        <tr>
                                                            <td>{{($ke+1)}}</td>
                                                            <td><a href="{{route('admin.contact.view',encrypt($relationDetail->id))}}"
                                                                   target="_blank" class="active_a">
                                                                    {{$relationDetail->surname.' '.$relationDetail->first_name}}</a></td>
                                                            <td>{{$relationDetail->client_relation_label}}</td>
                                                            <!-- <td>{{ ($relationDetail->mailout_display) }}</td> -->
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
                </div>
            </div>
        </div>
        <div class="tab-pane tabs-animation fade show " id="tab-content-2" role="tabpanel">
            <div class="col-sm-12">
                <div class="row">
                   <div class="col-sm-12">
                       <div class="card">
                           <div class="card-header">
                               <button type="button" class="btn btn-primary float-right" onclick="return showAddActual()">Add Actual</button>
                           </div>
                           <div class="card-body">
                               <div class="col-sm-12">
                                   <div class="table-responsive">
                                       <table class="table table-bordered" id="">
                                           <thead>
                                           <tr>
                                               <th>No.
                                               </th>
                                               <th>
                                                   Type
                                               </th>
                                               <th>
                                                   Total Amount
                                               </th>
                                               <th>
                                                   Date Statem.
                                               </th>
                                               <th>
                                                   Agg Amount
                                               </th>
                                               <th>
                                                   Broker Amount
                                               </th>
                                               <th>
                                                   Date Paid
                                               </th>
                                               <th>
                                                   Referrer Amount
                                               </th>
                                               <th>
                                                   Date Paid
                                               </th>
                                           </tr>
                                           </thead>
                                           <tbody>
                                           @if(isset($deal,$deal_commissions) && count($deal_commissions)>0)
                                               @foreach($deal_commissions as $key => $deal_commission)
                                                   <tr><td><span class="tr_counter">{{ $key+1 }}</span></td><td>{{ $deal_commission->commission_type_name }}</td><td>{{ $deal_commission->total_amount }}</td><td>{{ $deal_commission->date_statement }}</td><td>{{ $deal_commission->agg_amount }}</td><td>{{ $deal_commission->broker_amount }}</td><td>{{ $deal_commission->bro_amt_date_paid }}</td>
                                                    @if($deal_commission->referror_amount == null)
                                                    <td>0.00</td>
                                                    @else
                                                    <td>{{ $deal_commission->referror_amount }}</td>
                                                    @endif
                                                    <td>{{ $deal_commission->ref_amt_date_paid }}</td></tr>
                                               @endforeach
                                           @endif
                                           </tbody>
                                       </table>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
                </div>
            </div>
        </div>
        <div class="tab-pane tabs-animation fade show " id="tab-content-3" role="tabpanel">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="commission-modal-table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>
                                                    Commission Type
                                                </th>
                                                <!-- <th>
                                                    Client
                                                </th> -->
                                               <!--  <th>
                                                    Account No
                                                </th> -->
                                                <!-- <th>
                                                    Period
                                                </th>
 -->                                                <th>
                                                    Broker
                                                </th>
                                                <th>
                                                    Master
                                                </th>
                                                <th>
                                                    Referror Amount
                                                </th>
                                                <th>
                                                    Paid
                                                </th>

                                                <th>
                                                    Paid Date
                                                </th>
                                                <!-- th>
                                                    Payment No
                                                </th> -->
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane tabs-animation fade show " id="tab-content-4" role="tabpanel">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary float-right" onclick="return showAddTasks()
">Add Task</button>
                            </div>
                            <div class="card-body">
                                <div class="col-sm-12">
                                    <div class="table-responsive">

                                        <table style="width: 100%;max-width:none !important" id="TableData" class=" table-hover
                        table-striped
                        table-bordered display nowrap" data-toggle="table" data-height="500" data-show-columns="true"
                                               data-sAjaxSource="{{ route('admin.dealtsk.getrecords',encrypt($deal->id)) }}"
                                               data-aoColumns='{"mData": "Index no"},{"mData": "Start Date"},{"mData": "End Date"},{"mData": "User"},
                        {"mData": "Detail"},{"mData": "Note"},{"mData": "Added On"},{"mData":
                        "Action"}'>
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <!--<th>Processor</th>-->
                                                <th>User</th>
                                                <th>Detail</th>
                                                <th>Note</th>
                                                <th>Added On</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                 <!--<th>Processor</th>-->
                                                 <th>User</th>
                                                <th>Detail</th>
                                                <th>Note</th>
                                                <th>Added On</th>
                                                <th>Action</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection

@push('script-section')

    @include('layout.datatable')
    <script type="text/javascript" src="{{asset('front-assets/vendors/@chenfengyuan/datepicker/dist/datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('front-assets/vendors/daterangepicker/daterangepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('front-assets/js/form-components/datepicker.js')}}"></script>
    <script>
        var modalTable = '';
        var dealId = '{{$deal->id}}';
        var dealData = '';
        function showCommissions(current)
        {
            if (typeof modalTable != 'undefined' && typeof modalTable == 'object') //&& dataTable instanceof $.fn.dataTable.Api
            {
                modalTable.destroy();
            }
            var encId = '{{ encrypt($deal->id) }}'
            modalTable = $('#commission-modal-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('admin.deals.getcomdata') }}",
                    data: function (d) {
                        d.deal_id = encId

                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'commission_type_name', name: 'commission_type_name'},
                    // {data: 'client', name: 'client'},
                    // {data: 'period', name: 'period'},
                    {data: 'agg_amount', name: 'agg_amount'},
                    {data: 'broker_amount', name: 'broker_amount'},
                    {data: 'referror_amount', name: 'referror_amount'},
                    {data: 'total_amount', name: 'total_amount'},
                    {data: 'date_statement', name: 'date_statement'},
                    // {data: 'payment_no', name: 'payment_no'},
                ]
            });

        }
        function refreshTable()
        {
            var customArgs = {};

            refreshdataTable(customArgs,function(response,fnCallback)
            {

                if(typeof response.payload != 'undefined')
                {

                    var payloads = response.payload;
                    var TempObj = {};
                    TempObj['sEcho'] = payloads.sEcho;
                    TempObj['iTotalRecords'] = payloads.iTotalRecords;
                    TempObj['iTotalDisplayRecords'] = payloads.iTotalDisplayRecords;
                    var aaDataArray = [];

                    if(payloads.aaData.length > 0 )
                    {
                        jQuery.each(payloads.aaData ,function(key,value)
                        {
                            var edit_row = '<a data-url="{{url('admin/deals-tasks/get-record/')}}/'+value
                                    .encrypt_id+'/{{
                            encrypt
                            ($deal->id) }}" ' +
                                'data-id="'+value
                                    .id+'" ' +
                                'onclick="return' +
                                ' ' +
                                'editRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary" ' +
                                'title="Edit"><i title="Edit"  class="pe-7s-pen btn-icon-wrapper" ' +
                                'style="color:#fff"></i></a>';


                            var TempObj = {
                                "Index no" : value.id,
                                "User" : value.user,
                                "Processor" : value.processor,
                                "Start Date" : value.followup_date,
                                "End Date" : value.end_date,
                                "Detail" : value.followup_detail,
                                "Note" : value.details,
                                "Added On" : value.formated_created_at,
                                "Action" :  edit_row ,
                            };
                            aaDataArray.push(TempObj);
                        })
                    }
                    TempObj['aaData'] = aaDataArray;
                    fnCallback(TempObj);
                }
            },function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            });
        }
        jQuery(document).ready(function(){
            showCommissions();
            refreshTable();
        })

        function showAddTasks()
        {
            jQuery('#add_task_form').trigger("reset");
            jQuery('#detail').text("");
            jQuery('#add_task_form').attr('action','{{ route('admin.dealtsk.post',encrypt($deal->id))  }}')
            jQuery('#tasks-modal').modal('show')
        }

        function showAddActual()
        {
            jQuery('#add_actual_form').attr('action','{{ route('admin.deals.addactual',encrypt($deal->id))  }}')
            $.ajax({
                url:"{{route('admin.deals.getdealdata', $deal->id)}}",
                type:'GET',
                data:{dealId:dealId},
                success:function(response){
                    console.log(response);
                    dealData = response;
                }
            })
            jQuery('#actual-modal').modal('show')
        }
        function calculations(){
            var commission_type = $('#commission_type_1').val();
            var total_amount = $('#total_amount_1').val();
            if(commission_type != null){
                if(commission_type == 12){
                   broker_amount =  (dealData.broker_split_agg_brk_sp_trail * total_amount) / 100;
                   master_amount = total_amount - broker_amount;
                   $('#broker_amount_1').val(broker_amount.toFixed(2));
                   $('#agg_amount_1').val(master_amount.toFixed(2));

                }else if(commission_type == 13){
                    broker_amount =  (dealData.broker_split_agg_brk_sp_upfrt * total_amount) / 100;
                    master_amount = total_amount - broker_amount;
                    $('#broker_amount_1').val(broker_amount.toFixed(2));
                    $('#agg_amount_1').val(master_amount.toFixed(2));
                }
            }
        }
        function editRecord(current)
        {
            var url = jQuery(current).attr('data-url');
            var id = jQuery(current).attr('data-id');
           // alert(url);
            showLoader();
            $.ajax({
                url: url,
                type:'POST',
                data: {},
                beforeSend: function(request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));

                },
                success: function(data) {

                    if(!$.isEmptyObject(data.error)){
                        printErrorMsg(data.error);
                        hideLoader();

                    }else if(!$.isEmptyObject(data.errors)){
                        printErrorMsg(data.errors);
                        hideLoader();
                    }else{
                        data = data.data;
                        if(typeof data.deal != "undefined" && data.deal!='')
                        {
                            
                            //alert(dealtaskdata.end_date);
                            var deal = data.deal;
                            var dealtaskdata = data.taskdata
                            console.log(dealtaskdata);
                            //alert(dealtaskdata.followup_date);
                            jQuery('#edit_id').val(deal.id);
                            jQuery('#followup_date').val(dealtaskdata.followup_date);
                            jQuery('#end_date').val(dealtaskdata.end_date);
                            jQuery('#processor').val(dealtaskdata.processor);
                            jQuery('#user').val(dealtaskdata.user);
                            jQuery('#followup_detail').val(dealtaskdata.followup_detail);
                            jQuery('#detail').html(dealtaskdata.details);
                            jQuery('#add_task_form').attr('action','{{ url('admin/deals-tasks/update')
                            }}/'+dealtaskdata.enc_id+'/{{ encrypt($deal->id) }}')
                            jQuery('#tasks-modal').modal('show')
                            hideLoader();
                        }

                    }
                },error:function(jqXHR, textStatus, errorThrown)
                {
                    if(IsJsonString(jqXHR.responseText))
                    {
                        var respo =JSON.parse(jqXHR.responseText);
                        errorMessage(respo.message);
                        printErrorMsg(respo.errors)
                        hideLoader();
                    }else{
                        errorMessage(jqXHR.responseText)
                        hideLoader();
                    }
                }
            });
            return false;
        }
    </script>

    @endpush

@section('modal-section')


    <div class="modal fade tasks-modal" id="tasks-modal" tabindex="-1" role="dialog" aria-labelledby="Deal Tasks"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Tasks</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post"
                          action=""
                          onsubmit="return saveForm(this)" id="add_task_form">
                        @csrf
                    <div class="form-row">
                        <input type="hidden" name="edit_id" id="edit_id" />
                        <div class="col-md-3 col-sm-12">
                            <div class="position-relative form-group">
                                <label class="form-label font-weight-bold">Start Date</label>
                                <input type="text" data-toggle="datepicker"  placeholder="dd/mm/yyyy"
                                       name="followup_date" autocomplete="off" id="followup_date"
                                       class="form-control"
                                       maxlength="255" value=""/>

                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="position-relative form-group">
                                <label class="form-label font-weight-bold">End Date</label>
                                <input type="text" data-toggle="datepicker"  placeholder="dd/mm/yyyy"
                                       name="end_date" autocomplete="off" id="end_date"
                                       class="form-control"
                                       maxlength="255" value=""/>

                            </div>
                        </div>

                        <div class="col-sm-3">
                                <div class="position-relative form-group">
                                    <label for="user" class="form-label font-weight-bold">User</label>
                                    <select name="user" id="user" class="multiselect-dropdown form-control">
                                        <option value="" >Select User</option>
                                        @if(count($processors)>0)
                                            @foreach($processors as $user)
                                                <option value="{{$user->id}}">
                                                    {{$user->fname.' '.$user->lname}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                       
                        <div class="col-sm-3">
                            <div class="position-relative form-group">
                                <label for="followup_detail" class="form-label font-weight-bold">Detail</label>
                                <select name="followup_detail" id="followup_detail" class="multiselect-dropdown form-control">
                                    <option value="" >Select Detail</option>
                                    @if(count($dealDetails)>0)
                                        @foreach($dealDetails as $ddkey => $dealDetail)
                                            <option value="{{$ddkey}}">
                                                {{$dealDetail}}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="position-relative form-group">
                                <label for="exampleText" class="form-label font-weight-bold">Note</label>
                                <textarea name="detail" id="detail" class="form-control"></textarea>
                            </div>
                        </div>

                        <button class="mt-1 btn btn-primary">Submit</button>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade actual-modal" id="actual-modal" tabindex="-1" role="dialog" aria-labelledby="Deal Actual"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Actual</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post"
                          action=""
                          onsubmit="return saveActualForm(this)" id="add_actual_form">
                        @csrf
                        <div class="form-row">
                            <input type="hidden" name="edit_id" id="actual_edit_id" />
                            <div class="row">
                                <div class="col-sm-3">

                                    <div class="form-group ">

                                        <label  class="mr-sm-2 font-weight-bold">Type</label>
                                        <select class="form-control" id="commission_type_1"
                                                name="commission_type_1">
                                            <option value="">Select Type</option>
                                            @foreach($comm_types as $key =>$comm_type)
                                                <option value="{{$key}}" >{{$comm_type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group ">

                                        <label  class="mr-sm-2 font-weight-bold">Total Amount</label>
                                        <input type="text" name="total_amount_1" placeholder="Total Amount"
                                               id="total_amount_1" class="form-control number-input" maxlength="13" onkeyup="calculations()" />
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group ">
                                        <label  class="mr-sm-2 font-weight-bold">Date Statem.</label>
                                        <input type="date" placeholder="mm/dd/yyyy"  name="date_statement_1"
                                               id="date_statement_1" class="form-control " maxlength="13" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group ">
                                        <label  class="mr-sm-2 font-weight-bold">Agg Amount</label>
                                        <input type="text" name="agg_amount_1" placeholder="Agg Amount"
                                               id="agg_amount_1" class="form-control number-input" maxlength="13" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">

                                        <label  class="mr-sm-2 font-weight-bold">Broker Amount</label>
                                        <input type="text" name="broker_amount_1" placeholder="Broker Amount"
                                               id="broker_amount_1" class="form-control number-input" maxlength="13" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">

                                        <label  class="mr-sm-2 font-weight-bold">Date Paid</label>
                                        <input type="date"
                                               placeholder="mm/dd/yyyy"  name="bro_amt_date_paid_1" id="bro_amt_date_paid_1" class="form-control " maxlength="13" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">

                                        <label  class="mr-sm-2 font-weight-bold">Referrer Amount</label>
                                        <input type="text" name="referror_amount_1" placeholder="Referrer Amount"
                                               id="referror_amount_1" class="form-control number-input" maxlength="13" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label  class="mr-sm-2 font-weight-bold">Date Paid</label>
                                        <input type="date" placeholder="mm/dd/yyyy"  name="ref_amt_date_paid_1"
                                               id="ref_amt_date_paid_1" class="form-control " maxlength="13" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button class="mt-1 btn btn-primary">Submit</button>
                                </div>
                            </div>


                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>

        </div>
    </div>
    <script>
        var deal_enc = '{{encrypt($deal->id)}}'
        function saveForm(current)
        {
           // console.log(jQuery(current).attr('action'));
            showLoader();
            $.ajax({
               
                url: jQuery(current).attr('action'),
                type:'POST',
                data:  $("form").serialize(),
                success: function(data) {
                    //alert('ggg2');
                    if(!$.isEmptyObject(data.error)){
                        printErrorMsg(data.error);
                        hideLoader();

                    }else if(!$.isEmptyObject(data.errors)){
                        printErrorMsg(data.errors);
                        hideLoader();
                    }else{
                        successMessage(data.success);
                        jQuery('#add_task_form').trigger("reset");
                        jQuery('#tasks-modal').modal('hide');
                        refreshTable();
                        hideLoader();

                    }
                },error:function(jqXHR, textStatus, errorThrown)
                {
                    if(IsJsonString(jqXHR.responseText))
                    {
                        var respo =JSON.parse(jqXHR.responseText);
                        errorMessage(respo.message);
                        printErrorMsg(respo.errors)
                        hideLoader();
                    }else{
                        errorMessage(jqXHR.responseText)
                        hideLoader();
                    }
                }
            });
            return false;
        }

        function saveActualForm(current)
        {

            showLoader();
            $.ajax({
                url: jQuery(current).attr('action'),
                type:'POST',
                data:  $("form").serialize(),
                success: function(data) {

                    if(!$.isEmptyObject(data.error)){
                        printErrorMsg(data.error);
                        hideLoader();

                    }else if(!$.isEmptyObject(data.errors)){
                        printErrorMsg(data.errors);
                        hideLoader();
                    }else{
                        successMessage(data.success);
                        jQuery('#add_actual_form').trigger("reset");
                        jQuery('#actual-modal').modal('hide');
                        location.reload()
                        hideLoader();

                    }
                },error:function(jqXHR, textStatus, errorThrown)
                {
                    if(IsJsonString(jqXHR.responseText))
                    {
                        var respo =JSON.parse(jqXHR.responseText);
                        errorMessage(respo.message);
                        printErrorMsg(respo.errors)
                        hideLoader();
                    }else{
                        errorMessage(jqXHR.responseText)
                        hideLoader();
                    }
                }
            });
            return false;
        }

    </script>
    <style>
        .datepicker-container{
            z-index: 11111 !important;
            width: 278px;
        }
        .active_a:hover{
            background: none !important;
            border-radius: 0 !important;
            border:none !important;
        }
    </style>
@endsection
