@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    View Broker::{{$broker->given_name}}

@endsection
@section('page_title_con')


<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title">
            View Broker::{{$broker->given_name}} <a class="btn
                btn-primary text-white" href="{{route('admin.brokers.edit',encrypt($broker->id))}}"><i
                    class="pe-7s-pen
                btn-icon-wrapper"></i> </a>
        </h4>
    </div>
    <div class="page-rightheader ml-auto d-lg-flex d-none">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/><path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/></svg><span class="breadcrumb-icon"> Home</span></a></li>
            <li class="breadcrumb-item " aria-current="page">
                <a href="{{route('admin.contact.list') }}">Contacts</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">                             View Broker::{{$broker->given_name}}
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
                <span>Information</span>
            </a>
        </li>
       <!-- <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
                <span>Referrors</span>
            </a>
        </li>-->



        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-6" data-toggle="tab" href="#tab-content-6">
                <span>Commission Model</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3">
                <span>Tasks</span>
            </a>
        </li>

    </ul>
            </div>
        </div>
        <div class="panel-body tabs-menu-body">
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="row mb-3">
                <div class="col-sm-3">
                  <div class="card">
                      <div class="card-body">
                          <p class="mb-2"><b>Type:</b>{{($broker->parent_broker > 0 ? 'Broker Staff' : 'Broker')
                               }}</p>
                          <p class="mb-2"><b>Individual:</b>{{($broker->individual == 1 ? 'Yes' : 'No')}}</p>

                          <p class="mb-2"><b>Trading/Business:</b>{{$broker->trading}}</p>
                          <p class="mb-2"><b>Trust Name:</b>{{$broker->trust_name}}</p>
                          <p class="mb-2"><b>Entity Name:</b>{{$broker->entity_name}}</p>
                          <p class="mb-2"><b>Parent Broker:</b>{{($broker->parent_broker_display_name != '') ?
                          $broker->parent_broker_display_name : '-'}}</p>
                      </div>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="card">
                      <div class="card-body">
                          <p class="mb-2"><b>Salutation:</b>{{$broker->salutation}}</p>
                          <p class="mb-2"><b>Surname:</b>{{$broker->surname}}</p>
                          <p class="mb-2"><b>Given Name:</b>{{$broker->given_name}}</p>
                          <p class="mb-2"><b>DOB:</b>{{$broker->dob}}</p>
                          
                      </div>
                  </div>

                </div>
                <div class="col-sm-3">
                   <div class="card">
                       <div class="card-body">
                           <p class="mb-2"><b>Work Phone:</b>{{$broker->work_phone}}</p>
                           <p class="mb-2"><b>Home Phone:</b>{{$broker->home_phone}}</p>
                           <p class="mb-2"><b>Mobile Phone:</b>{{$broker->mobile_phone}}</p>
                           <p class="mb-2"><b>Fax:</b>{{$broker->fax}}</p>
                           <p class="mb-2"><b>Email:</b>{{$broker->email}}</p>
                           <p class="mb-2"><b>Web:</b>{{$broker->web}}</p>
                       </div>
                   </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-2"><b>Address:</b>{{$broker->business}}</p>
                            <p class="mb-2"><b>State:</b>{{$broker->state_name}}</p>
                            <p class="mb-2"><b>City:</b>{{$broker->city_name}}</p>
                            <p class="mb-2"><b>Postal Code:</b>{{$broker->pincode}}</p>

                        </div>
                    </div>

                </div>
            </div>
 
        </div>
        
        <div class="col-lg-12 col-md-12">
            <div class="main-card mb-3 ">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6 ">
                               <div class="card">
                                   <div class="card-header">
                                       <div class="card-title">
                                           Other
                                       </div>
                                   </div>
                                   <div class="card-body">
                                       <p class="mb-2"><b>ABN:</b>{{ ($broker->abn != '') ? $broker->abn :'-'  }}</p>
                                       <p class="mb-2"><b>Start Date:</b>{{ ($broker->start_date!='') ? $broker->start_date : '-' }}</p>
                                       <p class="mb-2"><b>End Date:</b>{{ ($broker->end_date != '') ? $broker->end_date : '-' }}</p>
                                       <p class="mb-2"><b>Subject To GST?:</b>{{ ($broker->subject_to_gst > 0) ? 'Yes' : 'No' }}</p>
                                   </div>
                               </div>
                            </div>

                            <div class="col-sm-6 ">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            Bank Detail
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-2"><b>Bank Name:</b>{{ ($broker->bank != '') ? $broker->bank : '-' }}</p>
                                        <p class="mb-2"><b>Account Name:</b>{{ ($broker->account_name != '') ? $broker->account_name :'-'  }}</p>
                                        <p class="mb-2"><b>BSB:</b>{{ ($broker->bsb != '') ? $broker->bsb : '-' }}</p>
                                        <p class="mb-2"><b>Account Number:</b>{{ ($broker->account_number!='') ? $broker->account_number : '-' }}</p>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
                  <div class="row mb-3">
               <div class="col-sm-12">
                   <div class="card">
                       <div class="card-body">
                           <div class="row">
                               <div class="col-sm-12">
                                   <p class="mb-2"><b>Note:</b><br/>
                                       {{ ($broker->note != '') ? $broker->note :'-'  }}
                                   </p>
                               </div>

                           </div>

                       </div>
                   </div>
               </div>
            </div>
        </div>
    </div>
        </div>
        <div class="tab-pane tabs-animation fade show " id="tab-content-1" role="tabpanel">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-bordered table">
                                    <thead>
                                    <tr>
                                        <th>Referror</th>
                                        <th>Entity</th>
                                        <th>Upfront %</th>
                                        <th>Trail %</th>
                                        <th>Comm per Deal</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($broker->referrorClients) > 0)
                                        @foreach($broker->referrorClients as $referrorClient)
                                            <tr>
                                                <td>{{ $referrorClient->referrors_name }}</td>
                                                <td>{{ $referrorClient->entity }}</td>
                                                <td>{{ $referrorClient->upfront }}</td>
                                                <td>{{ $referrorClient->trail }}</td>
                                                <td>{{ $referrorClient->comm_per_deal }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td style="text-align: center" colspan="5">No Records.</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane tabs-animation fade " id="tab-content-2" role="tabpanel">
            <div class="col-sm-12">
                <div class="row">
                   <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary float-right" onclick="return showAddExpense()">Add Exepnse</button>
                        </div>
                        <div class="card-body">
                         <div class="table-responsive">

                             <table style="width: 100%;max-width:none !important" id="TableDatae" class=" table-hover
                                 table-striped
                                 table-bordered display nowrap" data-toggle="table" data-height="500" data-show-columns="true"
                                 data-sAjaxSource="{{ route('admin.brokerexp.getrecords',encrypt($broker->id)) }}"
                                 data-aoColumns='{"mData": "Index no"},{"mData": "Expense Type"},{"mData": "Order Date"},
                                 {"mData": "Broker Charged"}, {"mData": "Broker Paid"}, {"mData": "Base Cost"},{"mData": "Broker Charge"}, {"mData": "Action"}'>
                                 <thead>
                                     <tr>
                                         <th>#</th>
                                         <th>Expense Type</th>
                                         <th>Order Date</th>
                                         <th>Broker Charged</th>
                                         <th>Broker Paid</th>
                                         <th>Base Cost</th>
                                         <th>Broker Charge</th>
                                         <th>Action</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                 </tbody>
                                 <tfoot>
                                     <tr>
                                         <th>#</th>
                                         <th>Expense Type</th>
                                         <th>Order Date</th>
                                         <th>Broker Charged</th>
                                         <th>Broker Paid</th>
                                         <th>Base Cost</th>
                                         <th>Broker Charge</th>
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

        <div class="tab-pane tabs-animation fade " id="tab-content-3" role="tabpanel">
            <div class="col-sm-12">
                <div class="row">
                   <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary float-right" onclick="return showAddTask()">Add Task</button>
                        </div>
                        <div class="card-body">
                            <div class="table responsive table-togglable table-hover">

                                <table style="width: 100%;max-width:none !important" id="TableDatat" class=" table-hover
                                    table-striped
                                    table-bordered display nowrap" data-toggle="table" data-height="500" data-show-columns="true"
                                    data-sAjaxSource="{{ route('admin.brokertsk.getrecords',encrypt($broker->id)) }}"
                                    data-aoColumns='{"mData": "Index no"},{"mData": "Person to Followup"},{"mData": "Followup Date"}, {"mData": "Detail"},{"mData": "Completed Date"},{"mData": "Status"},{"mData": "Added On"},{"mData": "Action"}'>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Person to Followup </th>
                                            <th>Followup Date</th>
                                            <th>Detail</th>
                                            <th>Completed Date</th>
                                            <th>Status</th>
                                            <th>Added On</th>

                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Person to Followup </th>
                                            <th>Followup Date</th>
                                            <th>Detail</th>
                                            <th>Completed Date</th>
                                            <th>Status</th>
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

        <div class="tab-pane tabs-animation fade " id="tab-content-4" role="tabpanel">
            <div class="col-sm-12">
                <div class="row">
                   <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary float-right" onclick="return showAddFee()">Add Fee</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">

                                <table style="width: 100%;max-width:none !important" id="TableDataf" class=" table-hover
                                    table-striped
                                    table-bordered display nowrap" data-toggle="table" data-height="500" data-show-columns="true"
                                    data-sAjaxSource="{{ route('admin.brokerfee.getrecords',encrypt($broker->id)) }}"
                                    data-aoColumns='{"mData": "Index no"},{"mData": "Type"},{"mData": "Frequency"}, {"mData": "Due Date"},{"mData": "Amount"},{"mData": "Added On"},{"mData": "Modified On"},{"mData": "Action"}'>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Type </th>
                                            <th>Frequency</th>
                                            <th>Due Date</th>
                                            <th>Amount</th>
                                            <th>Added On</th>
                                            <th>Modified On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Type </th>
                                            <th>Frequency</th>
                                            <th>Due Date</th>
                                            <th>Amount</th>
                                            <th>Added On</th>
                                            <th>Modified On</th>
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

        <div class="tab-pane tabs-animation fade " id="tab-content-5" role="tabpanel">
            <div class="col-sm-12">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary float-right" onclick="return showAddCert()">Add Certificate</button>
                        </div>
                        <div class="card-body">
                          <div class="table-responsive">

                              <table style="width: 100%;max-width:none !important" id="TableDatac" class=" table-hover
                                  table-striped
                                  table-bordered display nowrap" data-toggle="table" data-height="500" data-show-columns="true"
                                  data-sAjaxSource="{{ route('admin.brokercert.getrecords',encrypt($broker->id)) }}"
                                  data-aoColumns='{"mData": "Index no"},{"mData": "Type"},{"mData": "Required"}, {"mData":
                                  "Held"},{"mData": "Expiry Date"},{"mData": "Added On"},{"mData": "Modified On"},{"mData":
                                  "Action"}'>
                                  <thead>
                                      <tr>
                                          <th>#</th>
                                          <th>Type </th>
                                          <th>Required</th>
                                          <th>Held</th>
                                          <th>Expiry Date</th>
                                          <th>Added On</th>
                                          <th>Modified On</th>
                                          <th>Action</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                                  <tfoot>
                                      <tr>
                                          <th>#</th>
                                          <th>Type </th>
                                          <th>Required</th>
                                          <th>Held</th>
                                          <th>Expiry Date</th>
                                          <th>Added On</th>
                                          <th>Modified On</th>
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

        <div class="tab-pane tabs-animation fade " id="tab-content-6" role="tabpanel">
            <div class="col-sm-12">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card">

                        <div class="card-body">

                            <form method="post"
                            action="{{isset($taskdata)?route('admin.brokercom.update', [encrypt
                            ($taskdata->id),
                            encrypt($broker->id)]):route('admin.brokercom.post',encrypt($broker->id))}}" id="commission_model_form" onsubmit="return saveCommissionModelForm(this)">
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

                          <div class="form-row">
                                  <div class="col-sm-3">
                                      <div class="position-relative  form-group">
                                          <label class="form-label font-weight-bold">Commission Model</label> 
                                          <select name="commission_model" id="commission_model" class="form-control">
                                              <option value="">Select Model</option>
                                              @foreach($commission_models as $commission_model)
                                                  <option value="{{$commission_model->id}}" 
                                                      {{ isset($broker) && $broker->commission_model_id == $commission_model->id ? 'selected="selected"' : '' }}
                                                      >{{ $commission_model->name }}</option>
                                                  @endforeach
                                              </select>
                                      </div>
                                  </div>
                                   <div class="col-sm-3">
                                      <div class="position-relative form-group">
                                          <label  class="form-label font-weight-bold">Upfront(%)</label>
                                          <input name="upfront_per" id="upfront_per" type="text" class="form-control
                                          text-lowercase number-input" data-max="100" data-min="0" placeholder="Upfront" required  value="{{$broker->upfront_per}}">
                                          @if($errors->has('upfront_per'))
                                              <div class="error"
                                                   style="color:red">{{$errors->first('upfront_per')}}</div>
                                          @endif
                                      </div>
                                   </div>
                                   <div class="col-sm-3">
                                      <div class="position-relative form-group">
                                          <label  class="form-label font-weight-bold">Trail(%)</label>
                                          <input name="trail_per" id="trail_per" type="text" class="form-control
                                          text-lowercase number-input" data-max="100" data-min="0" placeholder="Trail" required value="{{$broker->trail_per}}">
                                          @if($errors->has('trail_per'))
                                              <div class="error"
                                                   style="color:red">{{$errors->first('trail_per')}}</div>
                                          @endif
                                      </div>
                                   </div>
                                   <div class="col-sm-3">
                                      <div class="position-relative form-group">
                                          <label  class="form-label font-weight-bold">Flat Fee Charge</label>
                                          <input name="flat_fee_chrg" id="flat_fee_chrg" type="text" class="form-control
                                          text-lowercase number-input" placeholder="Flat Fee Chrg" required value="{{$broker->flat_fee_chrg}}">
                                          @if($errors->has('flat_fee_chrg'))
                                              <div class="error"
                                                   style="color:red">{{$errors->first('flat_fee_chrg')}}</div>
                                          @endif
                                      </div>
                                   </div>
                                   <!-- <div class="col-sm-3">
                                      <div class="position-relative form-group">
                                          <label  class="form-label font-weight-bold">BDM Flat Fee (%)</label>
                                          <input name="bdm_flat_fee_per" id="bdm_flat_fee_per" type="text" class="form-control
                                          text-lowercase number-input" placeholder="BDM Flat Fee Percent" required value="{{ isset
                                          ($taskdata->bdm_flat_fee_per)
                                          ?$taskdata->bdm_flat_fee_per:'' }}">
                                          @if($errors->has('bdm_flat_fee_per'))
                                              <div class="error"
                                                   style="color:red">{{$errors->first('bdm_flat_fee_per')}}</div>
                                          @endif
                                      </div>
                                   </div>
                                   <div class="col-sm-3">
                                      <div class="position-relative form-group">
                                          <label  class="form-label font-weight-bold">BDM Upfront (%)</label>
                                          <input name="bdm_upfront_per" id="bdm_upfront_per" type="text" class="form-control
                                          text-lowercase number-input" placeholder="BDM Upfront Percent" required value="{{ isset
                                          ($taskdata->bdm_upfront_per)
                                          ?$taskdata->bdm_upfront_per:'' }}">
                                          @if($errors->has('bdm_upfront_per'))
                                              <div class="error"
                                                   style="color:red">{{$errors->first('bdm_upfront_per')}}</div>
                                          @endif
                                      </div>
                                   </div> -->
                                   <div class="col-sm-3">
                                    <button class="mt-1 btn btn-primary">Submit</button>
                                   </div>
                              <div class="clearfix clear"></div>
                              <table class="mb-0 table table-bordered">
                                      <thead>
                                          <tr>
                                              <th>#</th>
                                              <th>Institute</th>
                                              <th>Upfront(%)</th>
                                              <th>Trail(%)</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          @foreach($institutes as $key => $institute)
                                              <tr><?php 
                                              $bcmi = DB::table('broker_commission_model_institution')->where('broker_com_mo_inst_id', $broker->bcomid)->where('lender_id', $institute->id)->first();
                                              //print_R($bcmi->upfront);
                                              ?>
                                                  <td>{{ ($key + 1) }}</td>
                                                  <td>{{($institute->name != '') ? $institute->name : $institute->code}}</td>
                                                  <td><input type="hidden" name="institutes_model[{{$institute->id}}][id]" value="{{ $institute->id }}" class="form-control" data-max="100" /><input type="text" class="form-control upfront_amnt number-input" placeholder="Upfront" data-max="100" name="institutes_model[{{$institute->id}}][upfront]" id="institutes_model_{{$institute->id}}_upfront" value="{{$bcmi->upfront}}" /></td>
                                                  <td><input type="text" class="form-control trail_amnt number-input" data-max="100" name="institutes_model[{{$institute->id}}][trail]" id="institutes_model_{{$institute->id}}_trail" placeholder="Trail" value="{{$bcmi->trail}}" /></td>
                                              </tr>
                                          @endforeach
                                      </tbody>
                              </table>
                                   <div class="col-sm-12"></div>

                          </div>
                      </form>

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
<script>
    var dataTableexpense;
var dataTableexpenseAjaxParams = {};

function showAddExpense()
{
    jQuery('#add_expense_form').attr('action','{{ route('admin.brokerexp.post',encrypt($broker->id))  }}')
    jQuery('#expense-modal').modal('show')
}

function showAddTask()
{
    jQuery('#add_task_form').attr('action','{{ route('admin.brokertsk.post',encrypt($broker->id))  }}')
    jQuery('#tasks-modal').modal('show')
}

function showAddFee()
{
    jQuery('#add_fee_form').attr('action','{{ route('admin.brokerfee.post',encrypt($broker->id))  }}')
    jQuery('#fee-modal').modal('show')
}

function showAddCert()
{
    jQuery('#add_cert_form').attr('action','{{ route('admin.brokercert.post',encrypt($broker->id))  }}')
    jQuery('#cert-modal').modal('show')
}

function refreshdataTableexpense(customArgs, ModuleCallback,fnRowCallback) {
    if (typeof dataTableexpense != 'undefined' && typeof dataTableexpense == 'object') //&& dataTable instanceof $.fn.dataTable.Api
    {
        dataTableexpense.destroy();
    }
    if (jQuery('#TableDatae').length > 0) {

        var sortColumn = 0;
        var hide_datetimes = [];
        var center_columns = [];

        var aoColumns = jQuery('#TableDatae').attr('data-aoColumns');
        if(typeof aoColumns == 'undefined' || aoColumns == '') {

            jQuery('#TableDatae thead th').each(function (key, value) {
                if (jQuery(value).text() == 'Added On' || jQuery(value).text() == 'Added By') {
                    sortColumn = key;
                   // hide_datetimes.push(key);
                } else if (jQuery(value).text() == 'Modified On' || jQuery(value).text() == 'Modified By') {
                   // hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('text-center')) {
                    center_columns.push(key);
                }
            });
        }
        else{
            var splited = aoColumns.split(',');
            if(typeof splited != 'undefined' && splited.length > 0)
            {
                var totalSplited = splited.length;
                for(var k = 0;k<totalSplited;k++)
                {
                    var tempKeyVal = JSON.parse(splited[k]);
                    if (tempKeyVal.mData == 'Added On' || tempKeyVal.mData == 'Added By') {
                        sortColumn = k;
                        //hide_datetimes.push(k);
                    } else if (tempKeyVal.mData == 'Modified On' || tempKeyVal.mData == 'Modified By') {
                        //hide_datetimes.push(k);
                    }
                }
            }

            jQuery('#TableDatae thead th').each(function (key, value) {
                if(jQuery(value).hasClass('text-center'))
                {
                    center_columns.push(key)
                    ;
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }

            });
        }


        var allowexport = jQuery('#TableDatae').attr('data-allowexport');
        var allowCardView = jQuery('#TableDatae').attr('data-allowcardview');
        var dtOrientation = jQuery('#TableDatae').attr('data-orientation');
        var dtPageSize = jQuery('#TableDatae').attr('data-pagesize');
        var keys = jQuery('#TableDatae').attr('data-keys');
        var initcallback = jQuery('#TableDatae').attr('data-initcallback');
        dtOrientation = (typeof dtOrientation != "undefined" && dtOrientation != '') ? dtOrientation : 'portrait';
        dtPageSize = (typeof dtPageSize != "undefined" && dtPageSize != '') ? dtPageSize : 'A4';

        var allowButtons = [
            {
                extend: 'colvis',
                columns: ':not(.noVis)',
                className: 'btn btn-default',
                title: 'Column Visibility',
                text: '<div class="font-icon-wrapper font-icon-sm"><i class="pe-7s-menu icon-gradient' +
                    ' bg-premium-dark" title="Column Visibility"></i></div>',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            },
            {
                text: '<div class="font-icon-wrapper font-icon-sm"> <i class="pe-7s-refresh-2 icon-gradient' +
                    ' bg-premium-dark" title="Refresh Table"></i></div>'
                , action: function (e, dt, node, config) {
                    //dt.clear().draw();
                    dt.ajax.reload(null,true);

                },
                className: 'btn btn-default',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            }
        ]

        if(typeof allowexport != 'undefined' && allowexport == 1)
        {
            allowButtons.push({
                extend:    'excelHtml5Rj',
                text:      '<i class="fa fa-file-excel-o text-white"></i>',
                titleAttr: 'Excel',
                className: 'btn btn-danger text-white',
            });
            allowButtons.push({
                extend:    'csvHtml5Rj',
                text:      '<i class="fa fa-file-text-o text-white"></i>',
                titleAttr: 'CSV',
                className: 'btn btn-warning text-white',
            });
            /* allowButtons.push({
                     extend:    'pdfHtml5Rj',
                     text:      '<i class="fa fa-file-pdf-o text-white"></i>',
                     titleAttr: 'PDF',
                     className: 'btn btn-info text-white',
                     orientation : dtOrientation,
                     pageSize: dtPageSize
                 })*/
        }
        var dom_type = 'lBfrtip';


        if (typeof keys != 'undefined' && keys == true) {
            keys = true;
        } else {
            keys = false;
        }

        dataTableexpense = jQuery('#TableDatae').DataTable({
            pagingType: "full_numbers",
            bProcessing: true,
            bServerSide: true,
            /* bAutoWidth: true, */
            responsive:false,
            colReorder: true,
            sAjaxDataProp: 'aaData',
            iDisplayLength: 25,
            "oLanguage": {
                "sInfoFiltered": ""
            },
            buttons: allowButtons,
            keys: keys,
            'dom': dom_type,
            sAjaxSource: jQuery('#TableDatae').attr('data-sAjaxSource'),
            fnServerData: function (sSource, aoData, fnCallback, oSettings) {

                if (customArgs && Object.keys(customArgs).length > 0) {
                    jQuery.each(customArgs, function (key, value) {
                        aoData.push({name: key, value: value});
                    });
                }
                dataTableexpenseAjaxParams = aoData;
                var myData = JSON.stringify(aoData);
                let $url = sSource;
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": $url,
                    beforeSend: function(request) {
                        showLoader();
                        request.setRequestHeader("Content-Type", "application/json");
                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));

                    },
                    "data": myData,
                    "success": function (response) {
                        if($.isEmptyObject(response.error)){
                            ModuleCallback(response, fnCallback);
                        }else{
                            printErrorMsg(response.error);
                            hideLoader();
                        }
                    },
                    "error": function (xhr, status, error) {
                        hideLoader()
                        if (xhr.status == 401) {
                            window.location = siteUrl + 'login';
                        }
                    }
                });
            },
            /* autoWidth : true,
            scrollX: true, */
            sServerMethod: "POST",
            aoColumns: eval('[' + jQuery('#TableDatae').attr('data-aoColumns') + ']'),
            fnRowCallback: fnRowCallback,
            order: [
                [sortColumn, "desc"]
            ],
            bDestroy: true,
            columnDefs: [
                {
                targets: 0,
                className: 'noVis',
                },
                {
                    bSortable: false,
                    aTargets: ["no-sort"]
                },
                {
                    visible: false,
                    targets: hide_datetimes
                },
                {
                    className : 'text-center',
                    targets :center_columns
                }
            ],

            /*fixedColumns: {
                leftColumns: 2
            },
            fixedHeader: {
                header: true,
                footer: false
            },*/
           /*  scrollY:        false,
            scrollCollapse: true, */
            "initComplete": function(settings, json) {
                if(jQuery('#filter').length > 0)
                {
                    jQuery('#filter').removeAttr('disabled');
                }
                if(jQuery('#reset').length > 0)
                {
                    jQuery('#reset').removeAttr('disabled');
                }
                if(jQuery('#filter_btn').length > 0)
                {
                    jQuery('#filter_btn').removeAttr('disabled');
                }
                if(jQuery('#reset_btn').length > 0)
                {
                    jQuery('#reset_btn').removeAttr('disabled');
                }

                if(typeof initcallback != "undefined" && initcallback != undefined)
                {
                    eval(initcallback+'()')
                }
                hideLoader();
            },
            "drawCallback": function(settings) {
                /*var api = this.api();
                var $table = $(api.table().node());

                // Remove data-label attribute from each cell
                $('tbody td', $table).each(function() {
                    $(this).removeAttr('data-label');
                });

                $('tbody tr', $table).each(function() {
                    $(this).height('auto');
                });*/
                hideLoader();
            }

        });


        setTimeout(function () {
            dataTableexpense.columns.adjust();
        }, 100);
    }
}

var dataTabletasks;
var dataTabletasksAjaxParams = {};
function refreshdataTabletasks(customArgs, ModuleCallback,fnRowCallback) {
    if (typeof dataTabletasks != 'undefined' && typeof dataTabletasks == 'object') //&& dataTable instanceof $.fn.dataTable.Api
    {
        dataTabletasks.destroy();
    }
    if (jQuery('#TableDatat').length > 0) {

        var sortColumn = 0;
        var hide_datetimes = [];
        var center_columns = [];

        var aoColumns = jQuery('#TableDatat').attr('data-aoColumns');
        if(typeof aoColumns == 'undefined' || aoColumns == '') {

            jQuery('#TableDatat thead th').each(function (key, value) {
                if (jQuery(value).text() == 'Added On' || jQuery(value).text() == 'Added By') {
                    sortColumn = key;
                   // hide_datetimes.push(key);
                } else if (jQuery(value).text() == 'Modified On' || jQuery(value).text() == 'Modified By') {
                   // hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('text-center')) {
                    center_columns.push(key);
                }
            });
        }
        else{
            var splited = aoColumns.split(',');
            if(typeof splited != 'undefined' && splited.length > 0)
            {
                var totalSplited = splited.length;
                for(var k = 0;k<totalSplited;k++)
                {
                    var tempKeyVal = JSON.parse(splited[k]);
                    if (tempKeyVal.mData == 'Added On' || tempKeyVal.mData == 'Added By') {
                        sortColumn = k;
                        //hide_datetimes.push(k);
                    } else if (tempKeyVal.mData == 'Modified On' || tempKeyVal.mData == 'Modified By') {
                        //hide_datetimes.push(k);
                    }
                }
            }

            jQuery('#TableDatat thead th').each(function (key, value) {
                if(jQuery(value).hasClass('text-center'))
                {
                    center_columns.push(key)
                    ;
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }

            });
        }


        var allowexport = jQuery('#TableDatat').attr('data-allowexport');
        var allowCardView = jQuery('#TableDatat').attr('data-allowcardview');
        var dtOrientation = jQuery('#TableDatat').attr('data-orientation');
        var dtPageSize = jQuery('#TableDatat').attr('data-pagesize');
        var keys = jQuery('#TableDatat').attr('data-keys');
        var initcallback = jQuery('#TableDatat').attr('data-initcallback');
        dtOrientation = (typeof dtOrientation != "undefined" && dtOrientation != '') ? dtOrientation : 'portrait';
        dtPageSize = (typeof dtPageSize != "undefined" && dtPageSize != '') ? dtPageSize : 'A4';

        var allowButtons = [
            {
                extend: 'colvis',
                columns: ':not(.noVis)',
                className: 'btn btn-default',
                title: 'Column Visibility',
                text: '<div class="font-icon-wrapper font-icon-sm"><i class="pe-7s-menu icon-gradient' +
                    ' bg-premium-dark" title="Column Visibility"></i></div>',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            },
            {
                text: '<div class="font-icon-wrapper font-icon-sm"> <i class="pe-7s-refresh-2 icon-gradient' +
                    ' bg-premium-dark" title="Refresh Table"></i></div>'
                , action: function (e, dt, node, config) {
                    //dt.clear().draw();
                    dt.ajax.reload(null,true);

                },
                className: 'btn btn-default',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            }
        ]

        if(typeof allowexport != 'undefined' && allowexport == 1)
        {
            allowButtons.push({
                extend:    'excelHtml5Rj',
                text:      '<i class="fa fa-file-excel-o text-white"></i>',
                titleAttr: 'Excel',
                className: 'btn btn-danger text-white',
            });
            allowButtons.push({
                extend:    'csvHtml5Rj',
                text:      '<i class="fa fa-file-text-o text-white"></i>',
                titleAttr: 'CSV',
                className: 'btn btn-warning text-white',
            });
            /* allowButtons.push({
                     extend:    'pdfHtml5Rj',
                     text:      '<i class="fa fa-file-pdf-o text-white"></i>',
                     titleAttr: 'PDF',
                     className: 'btn btn-info text-white',
                     orientation : dtOrientation,
                     pageSize: dtPageSize
                 })*/
        }
        var dom_type = 'lBfrtip';


        if (typeof keys != 'undefined' && keys == true) {
            keys = true;
        } else {
            keys = false;
        }

        dataTabletasks = jQuery('#TableDatat').DataTable({
            pagingType: "full_numbers",
            bProcessing: true,
            bServerSide: true,
            /* bAutoWidth: true, */
            responsive:false,
            colReorder: true,
            sAjaxDataProp: 'aaData',
            iDisplayLength: 25,
            "oLanguage": {
                "sInfoFiltered": ""
            },
            buttons: allowButtons,
            keys: keys,
            'dom': dom_type,
            sAjaxSource: jQuery('#TableDatat').attr('data-sAjaxSource'),
            fnServerData: function (sSource, aoData, fnCallback, oSettings) {

                if (customArgs && Object.keys(customArgs).length > 0) {
                    jQuery.each(customArgs, function (key, value) {
                        aoData.push({name: key, value: value});
                    });
                }
                dataTabletasksAjaxParams = aoData;
                var myData = JSON.stringify(aoData);
                let $url = sSource;
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": $url,
                    beforeSend: function(request) {
                        showLoader();
                        request.setRequestHeader("Content-Type", "application/json");
                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));

                    },
                    "data": myData,
                    "success": function (response) {
                        if($.isEmptyObject(response.error)){
                            ModuleCallback(response, fnCallback);
                        }else{
                            printErrorMsg(response.error);
                            hideLoader();
                        }
                    },
                    "error": function (xhr, status, error) {
                        hideLoader()
                        if (xhr.status == 401) {
                            window.location = siteUrl + 'login';
                        }
                    }
                });
            },
            /* autoWidth : true,
            scrollX: true, */
            sServerMethod: "POST",
            aoColumns: eval('[' + jQuery('#TableDatat').attr('data-aoColumns') + ']'),
            fnRowCallback: fnRowCallback,
            order: [
                [sortColumn, "desc"]
            ],
            bDestroy: true,
            columnDefs: [
                {
                targets: 0,
                className: 'noVis',
                },
                {
                    bSortable: false,
                    aTargets: ["no-sort"]
                },
                {
                    visible: false,
                    targets: hide_datetimes
                },
                {
                    className : 'text-center',
                    targets :center_columns
                }
            ],

            /*fixedColumns: {
                leftColumns: 2
            },
            fixedHeader: {
                header: true,
                footer: false
            },*/
           /*  scrollY:        false,
            scrollCollapse: true, */
            "initComplete": function(settings, json) {
                if(jQuery('#filter').length > 0)
                {
                    jQuery('#filter').removeAttr('disabled');
                }
                if(jQuery('#reset').length > 0)
                {
                    jQuery('#reset').removeAttr('disabled');
                }
                if(jQuery('#filter_btn').length > 0)
                {
                    jQuery('#filter_btn').removeAttr('disabled');
                }
                if(jQuery('#reset_btn').length > 0)
                {
                    jQuery('#reset_btn').removeAttr('disabled');
                }

                if(typeof initcallback != "undefined" && initcallback != undefined)
                {
                    eval(initcallback+'()')
                }
                hideLoader();
            },
            "drawCallback": function(settings) {
                /*var api = this.api();
                var $table = $(api.table().node());

                // Remove data-label attribute from each cell
                $('tbody td', $table).each(function() {
                    $(this).removeAttr('data-label');
                });

                $('tbody tr', $table).each(function() {
                    $(this).height('auto');
                });*/
                hideLoader();
            }

        });


        setTimeout(function () {
            dataTabletasks.columns.adjust();
        }, 100);
    }
}

var dataTablefees;
var dataTablefeesAjaxParams = {};
function refreshdataTablefees(customArgs, ModuleCallback,fnRowCallback) {
    if (typeof dataTablefees != 'undefined' && typeof dataTablefees == 'object') //&& dataTable instanceof $.fn.dataTable.Api
    {
        dataTablefees.destroy();
    }
    if (jQuery('#TableDataf').length > 0) {

        var sortColumn = 0;
        var hide_datetimes = [];
        var center_columns = [];

        var aoColumns = jQuery('#TableDataf').attr('data-aoColumns');
        if(typeof aoColumns == 'undefined' || aoColumns == '') {

            jQuery('#TableDataf thead th').each(function (key, value) {
                if (jQuery(value).text() == 'Added On' || jQuery(value).text() == 'Added By') {
                    sortColumn = key;
                   // hide_datetimes.push(key);
                } else if (jQuery(value).text() == 'Modified On' || jQuery(value).text() == 'Modified By') {
                   // hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('text-center')) {
                    center_columns.push(key);
                }
            });
        }
        else{
            var splited = aoColumns.split(',');
            if(typeof splited != 'undefined' && splited.length > 0)
            {
                var totalSplited = splited.length;
                for(var k = 0;k<totalSplited;k++)
                {
                    var tempKeyVal = JSON.parse(splited[k]);
                    if (tempKeyVal.mData == 'Added On' || tempKeyVal.mData == 'Added By') {
                        sortColumn = k;
                        //hide_datetimes.push(k);
                    } else if (tempKeyVal.mData == 'Modified On' || tempKeyVal.mData == 'Modified By') {
                        //hide_datetimes.push(k);
                    }
                }
            }

            jQuery('#TableDataf thead th').each(function (key, value) {
                if(jQuery(value).hasClass('text-center'))
                {
                    center_columns.push(key)
                    ;
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }

            });
        }


        var allowexport = jQuery('#TableDataf').attr('data-allowexport');
        var allowCardView = jQuery('#TableDataf').attr('data-allowcardview');
        var dtOrientation = jQuery('#TableDataf').attr('data-orientation');
        var dtPageSize = jQuery('#TableDataf').attr('data-pagesize');
        var keys = jQuery('#TableDataf').attr('data-keys');
        var initcallback = jQuery('#TableDataf').attr('data-initcallback');
        dtOrientation = (typeof dtOrientation != "undefined" && dtOrientation != '') ? dtOrientation : 'portrait';
        dtPageSize = (typeof dtPageSize != "undefined" && dtPageSize != '') ? dtPageSize : 'A4';

        var allowButtons = [
            {
                extend: 'colvis',
                columns: ':not(.noVis)',
                className: 'btn btn-default',
                title: 'Column Visibility',
                text: '<div class="font-icon-wrapper font-icon-sm"><i class="pe-7s-menu icon-gradient' +
                    ' bg-premium-dark" title="Column Visibility"></i></div>',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            },
            {
                text: '<div class="font-icon-wrapper font-icon-sm"> <i class="pe-7s-refresh-2 icon-gradient' +
                    ' bg-premium-dark" title="Refresh Table"></i></div>'
                , action: function (e, dt, node, config) {
                    //dt.clear().draw();
                    dt.ajax.reload(null,true);

                },
                className: 'btn btn-default',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            }
        ]

        if(typeof allowexport != 'undefined' && allowexport == 1)
        {
            allowButtons.push({
                extend:    'excelHtml5Rj',
                text:      '<i class="fa fa-file-excel-o text-white"></i>',
                titleAttr: 'Excel',
                className: 'btn btn-danger text-white',
            });
            allowButtons.push({
                extend:    'csvHtml5Rj',
                text:      '<i class="fa fa-file-text-o text-white"></i>',
                titleAttr: 'CSV',
                className: 'btn btn-warning text-white',
            });
            /* allowButtons.push({
                     extend:    'pdfHtml5Rj',
                     text:      '<i class="fa fa-file-pdf-o text-white"></i>',
                     titleAttr: 'PDF',
                     className: 'btn btn-info text-white',
                     orientation : dtOrientation,
                     pageSize: dtPageSize
                 })*/
        }
        var dom_type = 'lBfrtip';


        if (typeof keys != 'undefined' && keys == true) {
            keys = true;
        } else {
            keys = false;
        }

        dataTablefees = jQuery('#TableDataf').DataTable({
            pagingType: "full_numbers",
            bProcessing: true,
            bServerSide: true,
            /* bAutoWidth: true, */
            responsive:false,
            colReorder: true,
            sAjaxDataProp: 'aaData',
            iDisplayLength: 25,
            "oLanguage": {
                "sInfoFiltered": ""
            },
            buttons: allowButtons,
            keys: keys,
            'dom': dom_type,
            sAjaxSource: jQuery('#TableDataf').attr('data-sAjaxSource'),
            fnServerData: function (sSource, aoData, fnCallback, oSettings) {

                if (customArgs && Object.keys(customArgs).length > 0) {
                    jQuery.each(customArgs, function (key, value) {
                        aoData.push({name: key, value: value});
                    });
                }
                dataTablefeesAjaxParams = aoData;
                var myData = JSON.stringify(aoData);
                let $url = sSource;
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": $url,
                    beforeSend: function(request) {
                        showLoader();
                        request.setRequestHeader("Content-Type", "application/json");
                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));

                    },
                    "data": myData,
                    "success": function (response) {
                        if($.isEmptyObject(response.error)){
                            ModuleCallback(response, fnCallback);
                        }else{
                            printErrorMsg(response.error);
                            hideLoader();
                        }
                    },
                    "error": function (xhr, status, error) {
                        hideLoader()
                        if (xhr.status == 401) {
                            window.location = siteUrl + 'login';
                        }
                    }
                });
            },
            /* autoWidth : true,
            scrollX: true, */
            sServerMethod: "POST",
            aoColumns: eval('[' + jQuery('#TableDataf').attr('data-aoColumns') + ']'),
            fnRowCallback: fnRowCallback,
            order: [
                [sortColumn, "desc"]
            ],
            bDestroy: true,
            columnDefs: [
                {
                targets: 0,
                className: 'noVis',
                },
                {
                    bSortable: false,
                    aTargets: ["no-sort"]
                },
                {
                    visible: false,
                    targets: hide_datetimes
                },
                {
                    className : 'text-center',
                    targets :center_columns
                }
            ],

            /*fixedColumns: {
                leftColumns: 2
            },
            fixedHeader: {
                header: true,
                footer: false
            },*/
           /*  scrollY:        false,
            scrollCollapse: true, */
            "initComplete": function(settings, json) {
                if(jQuery('#filter').length > 0)
                {
                    jQuery('#filter').removeAttr('disabled');
                }
                if(jQuery('#reset').length > 0)
                {
                    jQuery('#reset').removeAttr('disabled');
                }
                if(jQuery('#filter_btn').length > 0)
                {
                    jQuery('#filter_btn').removeAttr('disabled');
                }
                if(jQuery('#reset_btn').length > 0)
                {
                    jQuery('#reset_btn').removeAttr('disabled');
                }

                if(typeof initcallback != "undefined" && initcallback != undefined)
                {
                    eval(initcallback+'()')
                }
                hideLoader();
            },
            "drawCallback": function(settings) {
                /*var api = this.api();
                var $table = $(api.table().node());

                // Remove data-label attribute from each cell
                $('tbody td', $table).each(function() {
                    $(this).removeAttr('data-label');
                });

                $('tbody tr', $table).each(function() {
                    $(this).height('auto');
                });*/
                hideLoader();
            }

        });


        setTimeout(function () {
            dataTablefees.columns.adjust();
        }, 100);
    }
}

var dataTablecerts;
var dataTablecertsAjaxParams = {};
function refreshdataTablecerts(customArgs, ModuleCallback,fnRowCallback) {
    if (typeof dataTablecerts != 'undefined' && typeof dataTablecerts == 'object') //&& dataTable instanceof $.fn.dataTable.Api
    {
        dataTablecerts.destroy();
    }
    if (jQuery('#TableDatac').length > 0) {

        var sortColumn = 0;
        var hide_datetimes = [];
        var center_columns = [];

        var aoColumns = jQuery('#TableDatac').attr('data-aoColumns');
        if(typeof aoColumns == 'undefined' || aoColumns == '') {

            jQuery('#TableDatac thead th').each(function (key, value) {
                if (jQuery(value).text() == 'Added On' || jQuery(value).text() == 'Added By') {
                    sortColumn = key;
                   // hide_datetimes.push(key);
                } else if (jQuery(value).text() == 'Modified On' || jQuery(value).text() == 'Modified By') {
                   // hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }
                if (jQuery(value).hasClass('text-center')) {
                    center_columns.push(key);
                }
            });
        }
        else{
            var splited = aoColumns.split(',');
            if(typeof splited != 'undefined' && splited.length > 0)
            {
                var totalSplited = splited.length;
                for(var k = 0;k<totalSplited;k++)
                {
                    var tempKeyVal = JSON.parse(splited[k]);
                    if (tempKeyVal.mData == 'Added On' || tempKeyVal.mData == 'Added By') {
                        sortColumn = k;
                        //hide_datetimes.push(k);
                    } else if (tempKeyVal.mData == 'Modified On' || tempKeyVal.mData == 'Modified By') {
                        //hide_datetimes.push(k);
                    }
                }
            }

            jQuery('#TableDatac thead th').each(function (key, value) {
                if(jQuery(value).hasClass('text-center'))
                {
                    center_columns.push(key)
                    ;
                }
                if (jQuery(value).hasClass('noshow')) {
                    hide_datetimes.push(key);
                }

            });
        }


        var allowexport = jQuery('#TableDatac').attr('data-allowexport');
        var allowCardView = jQuery('#TableDatac').attr('data-allowcardview');
        var dtOrientation = jQuery('#TableDatac').attr('data-orientation');
        var dtPageSize = jQuery('#TableDatac').attr('data-pagesize');
        var keys = jQuery('#TableDatac').attr('data-keys');
        var initcallback = jQuery('#TableDatac').attr('data-initcallback');
        dtOrientation = (typeof dtOrientation != "undefined" && dtOrientation != '') ? dtOrientation : 'portrait';
        dtPageSize = (typeof dtPageSize != "undefined" && dtPageSize != '') ? dtPageSize : 'A4';

        var allowButtons = [
            {
                extend: 'colvis',
                columns: ':not(.noVis)',
                className: 'btn btn-default',
                title: 'Column Visibility',
                text: '<div class="font-icon-wrapper font-icon-sm"><i class="pe-7s-menu icon-gradient' +
                    ' bg-premium-dark" title="Column Visibility"></i></div>',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            },
            {
                text: '<div class="font-icon-wrapper font-icon-sm"> <i class="pe-7s-refresh-2 icon-gradient' +
                    ' bg-premium-dark" title="Refresh Table"></i></div>'
                , action: function (e, dt, node, config) {
                    //dt.clear().draw();
                    dt.ajax.reload(null,true);

                },
                className: 'btn btn-default',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            }
        ]

        if(typeof allowexport != 'undefined' && allowexport == 1)
        {
            allowButtons.push({
                extend:    'excelHtml5Rj',
                text:      '<i class="fa fa-file-excel-o text-white"></i>',
                titleAttr: 'Excel',
                className: 'btn btn-danger text-white',
            });
            allowButtons.push({
                extend:    'csvHtml5Rj',
                text:      '<i class="fa fa-file-text-o text-white"></i>',
                titleAttr: 'CSV',
                className: 'btn btn-warning text-white',
            });
            /* allowButtons.push({
                     extend:    'pdfHtml5Rj',
                     text:      '<i class="fa fa-file-pdf-o text-white"></i>',
                     titleAttr: 'PDF',
                     className: 'btn btn-info text-white',
                     orientation : dtOrientation,
                     pageSize: dtPageSize
                 })*/
        }
        var dom_type = 'lBfrtip';


        if (typeof keys != 'undefined' && keys == true) {
            keys = true;
        } else {
            keys = false;
        }

        dataTablecerts = jQuery('#TableDatac').DataTable({
            pagingType: "full_numbers",
            bProcessing: true,
            bServerSide: true,
            /* bAutoWidth: true, */
            responsive:false,
            colReorder: true,
            sAjaxDataProp: 'aaData',
            iDisplayLength: 25,
            "oLanguage": {
                "sInfoFiltered": ""
            },
            buttons: allowButtons,
            keys: keys,
            'dom': dom_type,
            sAjaxSource: jQuery('#TableDatac').attr('data-sAjaxSource'),
            fnServerData: function (sSource, aoData, fnCallback, oSettings) {

                if (customArgs && Object.keys(customArgs).length > 0) {
                    jQuery.each(customArgs, function (key, value) {
                        aoData.push({name: key, value: value});
                    });
                }
                dataTablecertsAjaxParams = aoData;
                var myData = JSON.stringify(aoData);
                let $url = sSource;
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": $url,
                    beforeSend: function(request) {
                        showLoader();
                        request.setRequestHeader("Content-Type", "application/json");
                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));

                    },
                    "data": myData,
                    "success": function (response) {
                        if($.isEmptyObject(response.error)){
                            ModuleCallback(response, fnCallback);
                        }else{
                            printErrorMsg(response.error);
                            hideLoader();
                        }
                    },
                    "error": function (xhr, status, error) {
                        hideLoader()
                        if (xhr.status == 401) {
                            window.location = siteUrl + 'login';
                        }
                    }
                });
            },
            /* autoWidth : true,
            scrollX: true, */
            sServerMethod: "POST",
            aoColumns: eval('[' + jQuery('#TableDatac').attr('data-aoColumns') + ']'),
            fnRowCallback: fnRowCallback,
            order: [
                [sortColumn, "desc"]
            ],
            bDestroy: true,
            columnDefs: [
                {
                targets: 0,
                className: 'noVis',
                },
                {
                    bSortable: false,
                    aTargets: ["no-sort"]
                },
                {
                    visible: false,
                    targets: hide_datetimes
                },
                {
                    className : 'text-center',
                    targets :center_columns
                }
            ],

            /*fixedColumns: {
                leftColumns: 2
            },
            fixedHeader: {
                header: true,
                footer: false
            },*/
           /*  scrollY:        false,
            scrollCollapse: true, */
            "initComplete": function(settings, json) {
                if(jQuery('#filter').length > 0)
                {
                    jQuery('#filter').removeAttr('disabled');
                }
                if(jQuery('#reset').length > 0)
                {
                    jQuery('#reset').removeAttr('disabled');
                }
                if(jQuery('#filter_btn').length > 0)
                {
                    jQuery('#filter_btn').removeAttr('disabled');
                }
                if(jQuery('#reset_btn').length > 0)
                {
                    jQuery('#reset_btn').removeAttr('disabled');
                }

                if(typeof initcallback != "undefined" && initcallback != undefined)
                {
                    eval(initcallback+'()')
                }
                hideLoader();
            },
            "drawCallback": function(settings) {
                /*var api = this.api();
                var $table = $(api.table().node());

                // Remove data-label attribute from each cell
                $('tbody td', $table).each(function() {
                    $(this).removeAttr('data-label');
                });

                $('tbody tr', $table).each(function() {
                    $(this).height('auto');
                });*/
                hideLoader();
            }

        });


        setTimeout(function () {
            dataTablecerts.columns.adjust();
        }, 100);
    }
}


jQuery(document).ready(function(){

   // jQuery('.upfront_amnt').val('<?php echo $broker->upfront_per;?>');
    //jQuery('.trail_amnt').val('<?php echo $broker->trail_per;?>');

jQuery('body').on('keyup blur keypress','#upfront_per',function(){
    var currentVal  = jQuery(this).val()
    jQuery('.upfront_amnt').val(currentVal);

})

jQuery('body').on('keyup blur keypress','#trail_per',function(){
    var currentVal  = jQuery(this).val()
    jQuery('.trail_amnt').val(currentVal);
})

jQuery('body').on('change','#commission_model',function(){
    var curVal =  jQuery(this).val();
    
    jQuery('.upfront_amnt').val(0);
    jQuery('.trail_amnt').val(0);
    jQuery('#upfront_per').val('');
    jQuery('#trail_per').val('');
    jQuery('#flat_fee_chrg').val('');
    jQuery('#bdm_flat_fee_per').val('');
    jQuery('#bdm_upfront_per').val('');
    if(curVal != '')
    {//console.log(curVal);
                $.ajax({
            url: '{{ route("admin.brokercom.getcmml",encrypt($broker->id)) }}',
            type:'POST',
            data:  {"com_model":curVal},
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
                     if(!$.isEmptyObject(data.comm_model))
                     {
                         jQuery('#upfront_per').val(data.comm_model.upfront_per)
                         jQuery('#trail_per').val(data.comm_model.trail_per)
                         jQuery('#flat_fee_chrg').val(data.comm_model.flat_fee_chrg)
                         jQuery('#bdm_flat_fee_per').val(data.comm_model.bdm_flat_fee_per)
                         jQuery('#bdm_upfront_per').val(data.comm_model.bdm_upfront_per)
                     }

                     if(!$.isEmptyObject(data.comm_insti))
                     {
                         jQuery(data.comm_insti).each(function(ikey,ival){
                                jQuery('#institutes_model_'+ival.lender_id+'_upfront').val(parseFloat(ival.upfront).toFixed(2))
                                jQuery('#institutes_model_'+ival.lender_id+'_trail').val(parseFloat(ival.trail).toFixed(2))
                         })
                     }
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
    }
    return false;
})
})
function saveCommissionModelForm(current)
{

showLoader();
$.ajax({
    url: jQuery(current).attr('action'),
    type:'POST',
    data:  $("#commission_model_form").serialize(),
    success: function(data) {

        if(!$.isEmptyObject(data.error)){
            printErrorMsg(data.error);
            hideLoader();

        }else if(!$.isEmptyObject(data.errors)){
            printErrorMsg(data.errors);
            hideLoader();
        }else{
            successMessage(data.success);
            /* setTimeout(function(){
                location.reload()
            },1000); */
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

jQuery(document).ready(function(){
            refreshTableTablec();
            refreshTableTablee();
            refreshTableTablet();
            refreshTableTablef();
})

        function refreshTableTablec()
        {
               var customArgs = {};

               refreshdataTablecerts(customArgs,function(response,fnCallback)
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
                            var edit_row = '<a href="javascript:void(0)" data-url="{{url('admin/broker-certifications/edit_get/')}}/'+value
                                    .encrypt_id+'/{{ encrypt
                            ($broker->id) }}" ' +
                                'data-id="'+value
                                    .id+'" ' +
                                'onclick="return' +
                                ' ' +
                                'editCertRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary" ' +
                                'title="Edit"><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i></a>';


                            var TempObj = {
                                "Index no" : value.id,
                                "Type" : value.certificate_name,
                                "Required" : value.required_display,
                                "Held" : value.held_display,
                                "Expiry Date" : value.expiry_date,
                                "Added On" : value.formated_created_at,
                                "Modified On" : value.formated_updated_at,
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

        function refreshTableTablee()
        {
               var customArgs = {};

               refreshdataTableexpense(customArgs,function(response,fnCallback)
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
                            var edit_row = '<a href="javascript:void();"  data-url="{{url('admin/broker-expenses/edit_get/')}}/'+value.encrypt_id+'/{{ encrypt($broker->id) }}" ' +
                                'data-id="'+value
                                    .id+'" ' +
                                'onclick="return' +
                                ' ' +
                                'editExepenseRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary" ' +
                                'title="Edit"><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i></a>';


                            var TempObj = {
                                "Index no" : value.id,
                                "Expense Type" : value.expense_type_id,
                                "Order Date" : value.ordered_date,
                                "Broker Charged" : value.broker_charged,
                                "Broker Paid" : value.broker_paid,
                                "Base Cost" : value.base_cost,
                                "Broker Charge" : value.broker_charge,
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

        function refreshTableTablet()
        {
               var customArgs = {};

               refreshdataTabletasks(customArgs,function(response,fnCallback)
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
                            var edit_row = '<a  data-url="{{url('admin/broker-tasks/edit_get/')}}/'+value.encrypt_id+'/{{ encrypt
                            ($broker->id) }}" ' +
                                'data-id="'+value
                                    .id+'" ' +
                                'onclick="return' +
                                ' ' +
                                'editTaskRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary" ' +
                                'title="Edit"><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i></a>';


                            var TempObj = {
                                "Index no" : value.id,
                                "Person to Followup" : value.person_to_followup,
                                "Followup Date" : value.followup_date,
                                "Detail" : value.detail,
                                "Completed Date" : value.completed_date,
                                "Status" : value.status_display,
                                "Added On" : value.formated_created_at,
                                "Modified On" : value.formated_updated_at,
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

        function refreshTableTablef()
        {
               var customArgs = {};

               refreshdataTablefees(customArgs,function(response,fnCallback)
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
                            var edit_row = '<a href="javascript:void(0)" data-url="{{url('admin/broker-fees/edit_get/')}}/'+value.encrypt_id+'/{{ encrypt
                            ($broker->id) }}" ' +
                                'data-id="'+value
                                    .id+'" ' +
                                'onclick="return' +
                                ' ' +
                                'editFeeRecord(this)" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary" ' +
                                'title="Edit"><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i></a>';


                            var TempObj = {
                                "Index no" : value.id,
                                "Type" : value.fee_type_display,
                                "Frequency" : value.frequency_display,
                                "Due Date" : value.due_date,
                                "Amount" : value.amount,
                                "Added On" : value.formated_created_at,
                                "Modified On" : value.formated_updated_at,
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


        function editExepenseRecord(current)
        {
            var url = jQuery(current).attr('data-url');
            var id = jQuery(current).attr('data-id');
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
                        if(typeof data.broker != "undefined" && data.broker!='')
                        {
                            var deal = data.broker;
                            var dealtaskdata = data.expdata

                            jQuery('#expense_edit_id').val(dealtaskdata.id);
                            jQuery('#expense_type_id').val(dealtaskdata.expense_type_id);
                            jQuery('#expense_ordered_date').val(dealtaskdata.ordered_date);
                            jQuery('#expense_broker_charged').val(dealtaskdata.broker_charged);
                            jQuery('#expense_broker_paid').val(dealtaskdata.broker_paid);
                            jQuery('#expense_base_cost').val(dealtaskdata.base_cost);
                            jQuery('#expense_broker_charge').val(dealtaskdata.broker_charge);
                            jQuery('#expense_detail').val(dealtaskdata.detail);
                            jQuery('#expense_markup').val(dealtaskdata.markup);
                            jQuery('#add_expense_form').attr('action','{{ url('admin/broker-expenses/update')
                            }}/'+dealtaskdata.enc_id+'/{{ encrypt($broker->id) }}')
                            jQuery('#expense-modal').modal('show')
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

        function editTaskRecord(current)
        {
            var url = jQuery(current).attr('data-url');
            var id = jQuery(current).attr('data-id');
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
                        if(typeof data.broker != "undefined" && data.broker!='')
                        {
                            var deal = data.broker;
                            var dealtaskdata = data.taskdata

                            jQuery('#task_edit_id').val(dealtaskdata.id);
                            jQuery('#task_person_to_followup').val(dealtaskdata.person_to_followup);
                            jQuery('#task_followup_date').val(dealtaskdata.followup_date);
                            jQuery('#task_completed_date').val(dealtaskdata.completed_date);
                            jQuery('#task_status').val(dealtaskdata.status);
                            jQuery('#task_detail').val(dealtaskdata.detail);

                            jQuery('#add_task_form').attr('action','{{ url('admin/broker-tasks/update')
                            }}/'+dealtaskdata.enc_id+'/{{ encrypt($broker->id) }}')
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

        function editFeeRecord(current)
        {
            var url = jQuery(current).attr('data-url');
            var id = jQuery(current).attr('data-id');
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
                        if(typeof data.broker != "undefined" && data.broker!='')
                        {
                            var deal = data.broker;
                            var dealtaskdata = data.taskdata

                            jQuery('#fee_edit_id').val(dealtaskdata.id);
                            jQuery('#fee_type').val(dealtaskdata.type);
                            jQuery('#fee_frequency').val(dealtaskdata.frequency);
                            jQuery('#fee_due_date').val(dealtaskdata.due_date);
                            jQuery('#fee_amount').val(dealtaskdata.amount);


                            jQuery('#add_fee_form').attr('action','{{ url('admin/broker-fees/update')
                            }}/'+dealtaskdata.enc_id+'/{{ encrypt($broker->id) }}')
                            jQuery('#fee-modal').modal('show')
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

        function editCertRecord(current)
        {
            var url = jQuery(current).attr('data-url');
            var id = jQuery(current).attr('data-id');
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
                        if(typeof data.broker != "undefined" && data.broker!='')
                        {
                            var deal = data.broker;
                            var dealtaskdata = data.taskdata

                            jQuery('#cert_edit_id').val(dealtaskdata.id);
                            jQuery('#cert_type').val(dealtaskdata.type);
                            if(dealtaskdata.required == 1)
                            {
                                jQuery('#cert_required').attr('checked','checked').prop('checked',true).trigger('change');
                            }
                            if(dealtaskdata.held == 1)
                            {
                                jQuery('#cert_held').attr('checked','checked').prop('checked',true).trigger('change');
                            }



                            jQuery('#cert_expiry_date').val(dealtaskdata.expiry_date);


                            jQuery('#add_cert_form').attr('action','{{ url('admin/broker-certifications/update')
                            }}/'+dealtaskdata.enc_id+'/{{ encrypt($broker->id) }}')
                            jQuery('#cert-modal').modal('show')
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

<div class="modal fade expense-modal" id="expense-modal" tabindex="-1" role="dialog" aria-labelledby="Broker Expenses" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expensetitle">Add Expense</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post"
                      action=""
                      onsubmit="return saveExpenseForm(this)" id="add_expense_form">
                    @csrf
                <div class="form-row">
                    <input type="hidden" name="edit_id" id="expense_edit_id" />

                    <div class="col-sm-4">
                        <div class="position-relative form-group">
                            <label for="exampleSelect" class="form-label font-weight-bold">Expense Type</label>
                            <select name="expense_type_id" id="expense_type_id" class="form-control" required>
                                <option value="">Please Select</option>
                                @foreach ($exptypes as $exptype)
                                    <option value="{{$exptype->id}}" >{{$exptype->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="position-relative form-group">
                            <label  class="form-label font-weight-bold">Order Date</label>
                            <input name="ordered_date" id="expense_ordered_date" type="date" class="form-control text-lowercase" required value="">

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="position-relative form-group">
                            <label  class="form-label font-weight-bold">Broker Charged</label>
                            <input name="broker_charged" id="expense_broker_charged" type="date" class="form-control text-lowercase" value="">

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="position-relative form-group">
                            <label  class="form-label font-weight-bold">Broker Paid</label>
                            <input name="broker_paid" id="expense_broker_paid" type="date" class="form-control text-lowercase"  value="">

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="position-relative form-group">
                            <label  class="form-label font-weight-bold">Base Cost</label>
                            <input name="base_cost" id="expense_base_cost" type="text" class="form-control text-lowercase
number-input" required value="">

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="position-relative form-group">
                            <label  class="form-label font-weight-bold">Markup</label>
                            <input name="markup" id="expense_markup" type="text" class="form-control number-input text-lowercase"
                                   required value="">

                        </div>

                    </div>
                        <div class="col-sm-4">
                            <div class="position-relative form-group">
                                <label  class="form-label font-weight-bold">Broker Charge</label>
                                <input name="broker_charge" id="expense_broker_charge" type="text" class="form-control
                                text-lowercase number-input" required value="">

                            </div>
                        </div>
                    <div class="col-sm-12">
                        <div class="position-relative form-group">
                            <label for="exampleText" class="form-label font-weight-bold">Detail</label>
                            <textarea name="detail" id="expense_detail" class="form-control"></textarea>
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

    <div class="modal fade tasks-modal" id="tasks-modal" tabindex="-1" role="dialog" aria-labelledby="Broker Tasks" aria-hidden="true">
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
                          action="" onsubmit="return saveForm(this)" id="add_task_form">
                        @csrf

                        <input type="hidden" name="edit_id" id="task_edit_id" />

                        <div class="form-row">
                            <div class="col-md-4 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Person to Followup</label>
                                        <input type="text" name="person_to_followup" id="task_person_to_followup"
                                               class="form-control" maxlength="255" placeholder="Person to Followup"
                                               value=""/>

                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Date</label>
                                        <input type="date" name="followup_date" id="task_followup_date" class="form-control" maxlength="255" value=""/>

                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Completed Date</label>
                                        <input type="date" name="completed_date" id="task_completed_date" class="form-control" maxlength="255" />

                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Status</label>
                                    <select name="status" id="task_status" class="form-control" required>
                                        <option selected value="">Choose Type</option>
                                        @foreach($statuses as $status)
                                                <option value="{{$status->id}}"
                                                  >{{$status->name}}</option>
                                            @endforeach

                                    </select>

                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="position-relative form-group">
                                    <label for="exampleText" class="form-label font-weight-bold">Detail</label>
                                    <textarea name="detail" id="task_detail" class="form-control"></textarea>
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

    <div class="modal fade fee-modal" id="fee-modal" tabindex="-1" role="dialog" aria-labelledby="Broker Fees" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feeModalTitle">Add Fee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post"
                          action="" onsubmit="return saveFeeForm(this)" id="add_fee_form">
                        @csrf

                        <input type="hidden" name="edit_id" id="fee_edit_id" />

                        <div class="form-row">
                            <div class="col-md-4 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Fee Type</label>
                                    <select name="type" id="fee_type" class="form-control" required>
                                        <option selected value="">Choose Type</option>
                                        @foreach($fee_types as $fee_type)
                                            <option value="{{$fee_type->id}}"
                                                >{{$fee_type->name}}</option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Frequency</label>
                                    <select name="frequency" id="fee_frequency" class="form-control" required>
                                        <option selected value="">Choose Frequency</option>
                                        @foreach($frequencies as $key => $frequency)
                                            <option value="{{$key}}"
                                                >{{$frequency}}</option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Due Date</label>
                                        <input type="date" name="due_date" id="fee_due_date" class="form-control"
                                               maxlength="255" value=""/>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="position-relative form-group">
                                    <label  class="form-label font-weight-bold">Amount</label>
                                    <input name="amount" id="fee_amount" type="text" class="form-control
                                    text-lowercase number-input" placeholder="Amount" required value="">

                                </div>
                            </div>
                            <div class="col-sm-12"></div>
                            <div class="clearfix clear"></div>
                            <button class="mt-1 btn btn-primary">Submit</button>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade fee-modal" id="cert-modal" tabindex="-1" role="dialog" aria-labelledby="Broker Fees" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feeModalTitle">Add Certificate</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post"
                          action="" onsubmit="return saveCertForm(this)" id="add_cert_form">
                        @csrf

                        <input type="hidden" name="edit_id" id="cert_edit_id" />

                        <div class="form-row">
                            <div class="col-md-3 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Certificate</label>
                                    <select name="type" id="cert_type" class="form-control" required>
                                        <option selected value="">Choose Type</option>
                                        @foreach($certifications as $certification)
                                            <option value="{{$certification->id}}">{{$certification->name}}</option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="position-relative form-check form-check-inline">
                                    <label>&nbsp;</label>
                                    <label class="form-check-label" style="padding-top: 35px;">

                                        <input type="checkbox" data-onstyle="success"
                                               data-offstyle="danger" data-toggle="toggle" data-on="Yes"
                                               data-off="No" data-size="mini" value="1"
                                               id="cert_required"
                                                name="required"
                                               class="" >
                                        Required
                                    </label>

                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="position-relative form-check form-check-inline">
                                    <label>&nbsp;</label>
                                    <label class="form-check-label" style="padding-top: 35px;">

                                        <input type="checkbox" data-onstyle="success"
                                               data-offstyle="danger" data-toggle="toggle" data-on="Yes"
                                               data-off="No" data-size="mini" value="1"
                                               id="cert_held"
                                                name="held"
                                               class="" >
                                        Held
                                    </label>

                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="position-relative form-group">
                                    <label class="form-label font-weight-bold">Expiry Date</label>
                                        <input type="date" name="expiry_date" id="cert_expiry_date" class="form-control"
                                               maxlength="255" value=""/>

                                </div>
                            </div>
                            <div class="col-sm-12"></div>
                            <div class="clearfix clear"></div>
                            <button class="mt-1 btn btn-primary">Submit</button>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>

        </div>
    </div>
    <script>
        var deal_enc = '{{encrypt($broker->id)}}'
        function saveForm(current)
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
                        jQuery('#add_task_form').trigger("reset");
                        jQuery('#tasks-modal').modal('hide');
                        refreshTableTablet();
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

        function saveExpenseForm(current)
        {

            showLoader();
            $.ajax({
                url: jQuery(current).attr('action'),
                type:'POST',
                data:  $("#add_expense_form").serialize(),
                success: function(data) {

                    if(!$.isEmptyObject(data.error)){
                        printErrorMsg(data.error);
                        hideLoader();

                    }else if(!$.isEmptyObject(data.errors)){
                        printErrorMsg(data.errors);
                        hideLoader();
                    }else{
                        successMessage(data.success);
                        jQuery('#add_expense_form').trigger("reset");
                        jQuery('#expense-modal').modal('hide');
                        refreshTableTablee();
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

        function saveFeeForm(current)
        {

            showLoader();
            $.ajax({
                url: jQuery(current).attr('action'),
                type:'POST',
                data:  $("#add_fee_form").serialize(),
                success: function(data) {

                    if(!$.isEmptyObject(data.error)){
                        printErrorMsg(data.error);
                        hideLoader();

                    }else if(!$.isEmptyObject(data.errors)){
                        printErrorMsg(data.errors);
                        hideLoader();
                    }else{
                        successMessage(data.success);
                        jQuery('#add_fee_form').trigger("reset");
                        jQuery('#fee-modal').modal('hide');
                        refreshTableTablef();
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

        function saveCertForm(current)
        {
            showLoader();
            $.ajax({
                url: jQuery(current).attr('action'),
                type:'POST',
                data:  $("#add_cert_form").serialize(),
                success: function(data) {

                    if(!$.isEmptyObject(data.error)){
                        printErrorMsg(data.error);
                        hideLoader();

                    }else if(!$.isEmptyObject(data.errors)){
                        printErrorMsg(data.errors);
                        hideLoader();
                    }else{
                        successMessage(data.success);
                        jQuery('#add_cert_form').trigger("reset");
                        jQuery('#cert-modal').modal('hide');
                        refreshTableTablec();
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
            z-index: 9999 !important;
        }
    </style>
@endsection
