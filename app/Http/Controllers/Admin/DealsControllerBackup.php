<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\Commission;
use App\Models\CommissionData;
use App\Models\CommissionType;
use App\Models\Deal;
use App\Models\DealCommission;
use App\Models\DealStatus;
use App\Models\DealStatusUpdate;
use App\Models\Lenders;
use App\Models\Processor;
use App\Models\Products;
use App\Models\ContactSearch;
use App\Models\DealClient;
use App\Models\DealClientType;
use App\Models\DealTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;
use Yajra\DataTables\Facades\DataTables;

class DealsController extends Controller
{
    public $comm_types = [
        'Trail Commissions' => 2,
        'Adjustment' => 3,
        'Upfront' => 1

    ];
    public function dealList()
    {
        $data = [];
        $data['lenders'] = Lenders::whereNull('deleted_at')->get();
        $data['products'] = Products::whereNull('deleted_at')->get();
        $data['statuses'] = DealStatus::select(DB::raw('id,status_code,CONCAT(lpad(status_code, 2, 0)," ",name) as name'))->whereNull('deleted_at')->orderBy('status_code','ASC')->get();
        return view('admin.deal.list', $data);
    }

    public function dealAdd()
    {

        $data['brokers'] = Broker::select(DB::raw('id,CONCAT_WS(",",surname,given_name) as display_name,trading,trust_name,surname,given_name,entity_name,parent_broker'))->where('parent_broker',0)->whereNull('deleted_at')->get();
        $data['broker_staffs'] = Broker::select(DB::raw('id,CONCAT_WS(",",surname,given_name) as display_name,trading,trust_name,surname,given_name,entity_name,parent_broker'))->where('parent_broker','>',0)->whereNull('deleted_at')->get();
        $data['clients'] = ContactSearch::select(DB::raw('id,CONCAT_WS(",",surname,preferred_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->where('search_for',1)->whereNull('deleted_at')->get();;
        $data['products'] = Products::whereNull('deleted_at')->get();
        $data['lenders'] = Lenders::whereNull('deleted_at')->get();
        $data['statuses'] = DealStatus::select(DB::raw('id,status_code,CONCAT(lpad(status_code, 2, 0)," ",name) as name'))->whereNull('deleted_at')->orderBy('status_code','ASC')->get();
        $data['refferors'] = ContactSearch::select(DB::raw('id,CONCAT_WS(",",surname,preferred_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->where('search_for',2)->whereNull('deleted_at')->get();
        $data['comm_types'] = CommissionType::all()->pluck('name','id')->toArray();
        $data['commission_models'] = Commission::all();
        $data['deal_client_types'] = DealClientType::whereNull('deleted_at')->get();
        $data['existing_deals'] = Deal::select(DB::raw('deals.*,CONCAT_WS(",",contact_searches.surname,contact_searches.preferred_name) as display_name'))->join('contact_searches','contact_searches.id','=','deals.contact_id')->get();
        return view('admin.deal.add_edit', $data);
    }

    public function dealPost(Request $request)
    {
        $validated = $request->validate([
            'broker_id' => 'required',
            'contact_id' => 'required',
            'product_id' => 'required',
            'lender_id' => 'required',
            'status' => 'required'

        ], [], [
            'broker_id' => 'Broker',
            'contact_id' => 'Client',
            'product_id' => 'Product',
            'lender_id' => 'Lender',
        ]);

        try {
            DB::beginTransaction();
            $contact = new Deal();
            $proposed_settlement=$request['proposed_settlement'];
            $status_date=$request['status_date'];
            if($request['proposed_settlement']!=''){
                $proposed_settlement = \DateTime::createFromFormat('d/m/Y', $request['proposed_settlement']);
                $proposed_settlement=$proposed_settlement->format('Y-m-d');
            }
            if($request['status_date']!=''){
                $status_date = \DateTime::createFromFormat('d/m/Y', $request['status_date']);
                $status_date=$status_date->format('Y-m-d');
            }
            $contact->line_of_credit = isset($request['line_of_credit']) && $request['line_of_credit'] > 0 ?  1 : 0;
            $contact->exclude_from_tracking = isset($request['exclude_from_tracking']) && $request['exclude_from_tracking'] > 0 ?  1 : 0;
            $contact->gst_applies = isset($request['gst_applies']) && $request['gst_applies'] > 0 ?  1 : 0;
            $contact->has_trail = isset($request['has_trail']) && $request['has_trail'] > 0 ?  1 : 0;


            $contact->broker_id = $request['broker_id'];
            $contact->broker_staff_id = $request['broker_staff_id'];
            $contact->contact_id = $request['contact_id'];
            $contact->product_id = $request['product_id'];
            $contact->lender_id = $request['lender_id'];
            $contact->loan_ref = $request['loan_ref'];
            $contact->linked_to = ($request['linked_to'] >0 ) ? $request['linked_to'] : 0;
            $contact->loan_repaid = isset($request['loan_repaid']) && $request['loan_repaid'] == 1 ? $request['loan_repaid'] : 0;
            $contact->status = $request['status'];
            $contact->proposed_settlement = $proposed_settlement;
            $contact->actual_loan = $request['actual_loan'];
            $contact->broker_est_upfront = $request['broker_est_upfront'];
            $contact->broker_est_trail = $request['broker_est_trail'];
            $contact->broker_est_brokerage = $request['broker_est_brokerage'];
            $contact->broker_est_loan_amt = $request['broker_est_loan_amt'];
            $contact->agg_est_upfront = $request['agg_est_upfront'];
            $contact->agg_est_trail = $request['agg_est_trail'];
            $contact->agg_est_brokerage = $request['agg_est_brokerage'];
            $contact->status_date = $status_date;
            $contact->broker_split_commis_model = ($request['broker_split_commis_model'] > 0) ? $request['broker_split_commis_model'] : 0;
            $contact->broker_split_fee_per_deal = ($request['broker_split_fee_per_deal'] > 0) ? $request['broker_split_fee_per_deal'] : 0;
            $contact->broker_split_agg_brk_sp_upfrt = ($request['broker_split_agg_brk_sp_upfrt'] > 0) ? $request['broker_split_agg_brk_sp_upfrt'] : 0;
            $contact->broker_split_agg_brk_sp_trail = ($request['broker_split_agg_brk_sp_trail'] > 0) ? $request['broker_split_agg_brk_sp_trail'] : 0;
            $contact->referror_split_referror = ($request['referror_split_referror'] > 0) ? $request['referror_split_referror'] : 0;
            $contact->referror_split_comm_per_deal = ($request['referror_split_comm_per_deal'] > 0) ? $request['referror_split_comm_per_deal'] : 0;
            $contact->referror_split_agg_brk_sp_upfrt = ($request['referror_split_agg_brk_sp_upfrt'] > 0) ? $request['referror_split_agg_brk_sp_upfrt'] : 0;
            $contact->broker_staff_split_comm_per_deal = ($request['broker_staff_split_comm_per_deal'] > 0) ? $request['broker_staff_split_comm_per_deal'] : 0;
            $contact->broker_staff_split_agg_brk_sp_upfrt = ($request['broker_staff_split_agg_brk_sp_upfrt'] > 0) ? $request['broker_staff_split_agg_brk_sp_upfrt'] : 0;
            $contact->broker_staff_split_agg_brk_sp_trail = ($request['broker_staff_split_agg_brk_sp_trail'] > 0) ? $request['broker_staff_split_agg_brk_sp_trail'] : 0;
            $contact->referror_split_agg_brk_sp_trail = ($request['referror_split_agg_brk_sp_trail'] > 0) ? $request['referror_split_agg_brk_sp_trail'] : 0;

            $contact->note = $request['note'];


            $contact->created_by = Auth::user()->id;
            $contact->save();

            if ($contact->id > 0) {
                /* if($request['commission_type_1'] > 0 && $request['total_amount_1'] > 0)
                {
                    $dealComm  = new DealCommission();
                    $dealComm->deal_id = $contact->id;
                    $dealComm->order = 1;
                    $dealComm->type = isset($request['commission_type_1']) && $request['commission_type_1'] > 0 ?
                        $request['commission_type_1'] : 0;
                    $dealComm->total_amount = $request['total_amount_1'];
                    $dealComm->date_statement = $request['date_statement_1'];
                    $dealComm->agg_amount = $request['agg_amount_1'];
                    $dealComm->broker_amount = $request['broker_amount_1'];
                    $dealComm->bro_amt_date_paid = $request['bro_amt_date_paid_1'];
                    $dealComm->referror_amount = $request['referror_amount_1'];
                    $dealComm->ref_amt_date_paid = $request['ref_amt_date_paid_1'];
                    $dealComm->created_by = Auth::user()->id;
                    $dealComm->save();
                }

                if($request['commission_type_2'] > 0 && $request['total_amount_2'] > 0) {
                    $dealComm1 = new DealCommission();
                    $dealComm1->deal_id = $contact->id;
                    $dealComm1->order = 2;
                    $dealComm1->type = isset($request['commission_type_2']) && $request['commission_type_2'] > 0 ?
                        $request['commission_type_2'] : 0;
                    $dealComm1->total_amount = $request['total_amount_2'];
                    $dealComm1->date_statement = $request['date_statement_2'];
                    $dealComm1->agg_amount = $request['agg_amount_2'];
                    $dealComm1->broker_amount = $request['broker_amount_2'];
                    $dealComm1->bro_amt_date_paid = $request['bro_amt_date_paid_2'];
                    $dealComm1->referror_amount = $request['referror_amount_2'];
                    $dealComm1->ref_amt_date_paid = $request['ref_amt_date_paid_2'];
                    $dealComm1->created_by = Auth::user()->id;
                    $dealComm1->save();
                }
                if($request['commission_type_3'] > 0 && $request['total_amount_3'] > 0) {
                    $dealComm2 = new DealCommission();
                    $dealComm2->deal_id = $contact->id;
                    $dealComm2->order = 3;
                    $dealComm2->type = isset($request['commission_type_3']) && $request['commission_type_3'] > 0 ?
                        $request['commission_type_3'] : 0;
                    $dealComm2->total_amount = $request['total_amount_3'];
                    $dealComm2->date_statement = $request['date_statement_3'];
                    $dealComm2->agg_amount = $request['agg_amount_3'];
                    $dealComm2->broker_amount = $request['broker_amount_3'];
                    $dealComm2->bro_amt_date_paid = $request['bro_amt_date_paid_3'];
                    $dealComm2->referror_amount = $request['referror_amount_3'];
                    $dealComm2->ref_amt_date_paid = $request['ref_amt_date_paid_3'];
                    $dealComm2->created_by = Auth::user()->id;
                    $dealComm2->save();
                } */

                if(isset($request->actuals_comm) && count($request->actuals_comm) > 0)
                {
                    $com_data = [];
                    foreach($request->actuals_comm as $actual_com)
                    {
                           $com_data[] = [
                               'deal_id' => $contact->id,
                               'order' => 1,
                               'type' => isset($actual_com['commission_type']) && $actual_com['commission_type'] > 0 ?
                               $actual_com['commission_type'] : 0,
                                'total_amount'=> $actual_com['total_amount'],
                                'date_statement' => date('Y-m-d',strtotime($actual_com['date_statement'])),
                                'agg_amount' => $actual_com['agg_amount'],
                                'broker_amount' => $actual_com['broker_amount'],
                                'broker_staff_amount' => $actual_com['broker_staff_amount'],
                                'bro_staff_amt_date_paid' => date('Y-m-d',strtotime($actual_com['bro_staff_amt_date_paid'])),
                                'bro_amt_date_paid' => date('Y-m-d',strtotime($actual_com['bro_amt_date_paid'])),
                                'referror_amount' => $actual_com['referror_amount'],
                                'ref_amt_date_paid' => date('Y-m-d',strtotime($actual_com['ref_amt_date_paid'])),
                                'created_by' => Auth::user()->id,
                                'created_at' => Carbon::now('utc')->toDateTimeString(),
                                'updated_at' => Carbon::now('utc')->toDateTimeString(),
                           ];
                    }

                    if(count($com_data) > 0)
                    {
                        DealCommission::insert($com_data);
                    }
                }

                if (!empty($request->relationship)) {
                    $tempArr = [];
                    foreach ($request->relationship as $relation) {
                        if ($relation['linked_to'] > 0 && $relation['relation'] > 0) {
                            $tempArr[] = [
                            'deal_id' => $contact->id,
                            'client_id' => $relation['linked_to'],
                            'type'=>$relation['relation'],
                            'created_at' => Carbon::now('utc')->toDateTimeString(),
                                'updated_at' => Carbon::now('utc')->toDateTimeString(),
                                'created_by' => Auth::user()->id


                        ];
                        }
                    }

                    if (!empty($tempArr)) {
                        DealClient::insert($tempArr);
                    }
                }

                /*if (!empty($request->tasks)) {
                    $taskArr = [];
                    foreach ($request->tasks as $task) {
                        if($task['followup_date']!= '' && $task['processor']!='' && $task['details'] != '' && $task['user'] != '')
                        {
                            $taskArr[] = [
                                'deal_id' => $contact->id,
                                'followup_date' => $task['followup_date'],
                                'processor' => $task['processor'],
                                'details' => $task['details'],
                                'user' => $task['user'],
                                'created_at' => Carbon::now('utc')->toDateTimeString(),
                                'updated_at' => Carbon::now('utc')->toDateTimeString(),
                                'created_by' => Auth::user()->id
                            ];
                        }

                    }

                    if (!empty($taskArr)) {
                        DealTask::insert($taskArr);
                    }
                }*/

                DealStatusUpdate::create([
                    'deal_id' => $contact->id,
                    'from_status' => 0,
                    'status_id' => $request['status'],
                    'created_by' => Auth::user()->id
                ]);
                DB::commit();

                return  response()->json(['success' => 'Record added successfully!','link'=>route('admin.deals.view',encrypt
                ($contact->id))]);
            } else {
                DB::rollback();
                return response()->json(['error' => "Something went wrong while save record!"]);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function dealEdit($id)
    {

        $deal = Deal::with(['relations','tasks'])->find(decrypt($id));

        if ($deal) {

        $data['brokers'] = Broker::select(DB::raw('id,CONCAT_WS(",",surname,given_name) as display_name,trading,trust_name,surname,given_name,entity_name,parent_broker'))->where('parent_broker',0)->whereNull('deleted_at')->get();
        $data['broker_staffs'] = Broker::select(DB::raw('id,CONCAT_WS(",",surname,given_name) as display_name,trading,trust_name,surname,given_name,entity_name,parent_broker'))->where('parent_broker','>',0)->whereNull('deleted_at')->get();
        $data['clients'] = ContactSearch::select(DB::raw('id,CONCAT_WS(",",surname,preferred_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->where('search_for',1)->whereNull('deleted_at')->get();
            $data['refferors'] = ContactSearch::select(DB::raw('id,CONCAT_WS(",",surname,preferred_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
            )->where('search_for',2)->whereNull('deleted_at')->get();
        $data['products'] = Products::whereNull('deleted_at')->get();
        $data['lenders'] = Lenders::whereNull('deleted_at')->get();
        $data['statuses'] = DealStatus::select(DB::raw('id,status_code,CONCAT(lpad(status_code, 2, 0)," ",name) as name'))
                                      ->whereNull('deleted_at')->orderBy
        ('status_code','ASC')->get();
        $data['deal'] =$deal;
            $data['commission_models'] = Commission::all();
        $data['deal_commissions'] =DealCommission::select(DB::raw('deal_commissions.*,ct.name as commission_type_name,DATE_FORMAT(date_statement,"'.$this->mysql_date_format.'") as date_statement,DATE_FORMAT(bro_amt_date_paid,"'.$this->mysql_date_format.'") as bro_amt_date_paid,DATE_FORMAT(ref_amt_date_paid,"'.$this->mysql_date_format.'") as ref_amt_date_paid'))->where('deal_id',$deal->id)->whereNull('deal_commissions.deleted_at')->orderBy('order','ASC')->join('commission_types as ct','ct.id','=','deal_commissions.type')->get();
            $data['comm_types'] = CommissionType::all()->pluck('name','id')->toArray();

        $data['deal_client_types'] = DealClientType::whereNull('deleted_at')->get();
            $data['existing_deals'] = Deal::select(DB::raw('deals.*,CONCAT_WS(",",contact_searches.surname,contact_searches.preferred_name) as display_name'))->join('contact_searches','contact_searches.id','=','deals.contact_id')->where('deals.id','!=',$deal->id)->get();
            return view('admin.deal.add_edit', $data);
        } else {
            return redirect()->back()->with('error', 'Deal not found.');
        }
    }

    public function dealView($id)
    {

        $deal = Deal::with(['deal_clients','tasks'])->select(DB::raw("deals.*,brokers.given_name,cs.preferred_name,lenders.code as lendername,CONCAT( cs.surname,', ',cs.preferred_name) AS preferred_name, st.name status, p.name productname, cs.email, date_format(deals.created_at,'".$this->mysql_date_format."') date_sattled, CONCAT_WS(',',brokers.surname,brokers.given_name) as broker_display_name, CONCAT_WS(',',broker_staff.surname,broker_staff.given_name) as broker_staff_display_name,CONCAT(refer.surname,', ',refer.preferred_name) AS refer_display_name,commission_model.name as commission_model_display_name,linkto.loan_ref as linked_to_loan_ref,CONCAT_WS(',',linktocont.surname,linktocont.preferred_name) as linked_to_display_name,linkto.id as linkto_pk_id") )
        ->leftJoin('brokers','deals.broker_id','=','brokers.id')
        ->leftJoin('contact_searches as cs','deals.contact_id','=','cs.id')->leftJoin('brokers as broker_staff','deals.broker_staff_id','=','broker_staff.id')
        ->leftJoin('lenders','deals.lender_id','=','lenders.id')
        ->leftJoin('deal_statuses as st','deals.status','=','st.id')->leftJoin('deals as linkto','deals.linked_to','=','linkto.id')->leftJoin('contact_searches as linktocont','linktocont.id','=','linkto.contact_id')->leftJoin('contact_searches as refer','deals.referror_split_referror','=','refer.id')->leftJoin('commission_model','commission_model.id','=','deals.broker_split_commis_model')
        ->leftJoin('products as p','deals.product_id','=','p.id')->find(decrypt($id))
        ;

        $processors = Processor::all();
        $dealDetails = [
            '1' => '12 month follow up',
            '2' => '24 month follow up'
        ];

        if ($deal) {
            $deal_commissions =DealCommission::select(DB::raw('deal_commissions.*,ct.name as commission_type_name,DATE_FORMAT(date_statement,"'.$this->mysql_date_format.'") as date_statement,DATE_FORMAT(bro_amt_date_paid,"'.$this->mysql_date_format.'") as bro_amt_date_paid,DATE_FORMAT(ref_amt_date_paid,"'.$this->mysql_date_format.'") as ref_amt_date_paid'))->where('deal_id',$deal->id)->whereNull('deal_commissions.deleted_at')->orderBy('order','ASC')->join('commission_types as ct','ct.id','=','deal_commissions.type')->get();
            $comm_types = CommissionType::all()->pluck('name','id')->toArray();
            return view('admin.deal.view', compact('deal','deal_commissions','processors','dealDetails','comm_types'));
        } else {
            return redirect()->back()->with('error', 'Deal not found.');
        }
    }

    public function dealUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'broker_id' => 'required',
            'contact_id' => 'required',
            'product_id' => 'required',
            'lender_id' => 'required',
            'status' => 'required'

        ], [], [
            'broker_id' => 'Broker',
            'contact_id' => 'Client',
            'product_id' => 'Product',
            'lender_id' => 'Lender',
        ]);


        try {

            $contact = Deal::find(decrypt($id));

            if ($contact) {


                if($contact->status >= 9)
                {
                    return response()->json(['error' => "You can not update this record!"]);
                }

                $currentStatus = $contact->status;
                DB::beginTransaction();



                $contact->line_of_credit = isset($request['line_of_credit']) && $request['line_of_credit'] > 0 ?  1 : 0;
                $contact->exclude_from_tracking = isset($request['exclude_from_tracking']) && $request['exclude_from_tracking'] > 0 ?  1 : 0;
                $contact->gst_applies = isset($request['gst_applies']) && $request['gst_applies'] > 0 ?  1 : 0;
                $contact->has_trail = isset($request['has_trail']) && $request['has_trail'] > 0 ?  1 : 0;


                $contact->broker_id = $request['broker_id'];
                $contact->broker_staff_id = $request['broker_staff_id'];
                $contact->contact_id = $request['contact_id'];
                $contact->product_id = $request['product_id'];
                $contact->lender_id = $request['lender_id'];
                $contact->loan_ref = $request['loan_ref'];
                $contact->linked_to = ($request['linked_to'] >0 ) ? $request['linked_to'] : 0;
                $contact->loan_repaid = isset($request['loan_repaid']) && $request['loan_repaid'] == 1 ? $request['loan_repaid'] : 0;
                $contact->status = $request['status'];
                $contact->proposed_settlement = $request['proposed_settlement'];
                $contact->actual_loan = $request['actual_loan'];
                $contact->broker_est_upfront = $request['broker_est_upfront'];
                $contact->broker_est_trail = $request['broker_est_trail'];
                $contact->broker_est_brokerage = $request['broker_est_brokerage'];
                $contact->broker_est_loan_amt = $request['broker_est_loan_amt'];
                $contact->agg_est_upfront = $request['agg_est_upfront'];
                $contact->agg_est_trail = $request['agg_est_trail'];
                $contact->agg_est_brokerage = $request['agg_est_brokerage'];
                $contact->status_date = date('Y-m-d',strtotime( $request['status_date']));
                $contact->broker_split_commis_model = ($request['broker_split_commis_model'] > 0) ? $request['broker_split_commis_model'] : 0;
                $contact->broker_split_fee_per_deal = ($request['broker_split_fee_per_deal'] > 0) ? $request['broker_split_fee_per_deal'] : 0;
                $contact->broker_split_agg_brk_sp_upfrt = ($request['broker_split_agg_brk_sp_upfrt'] > 0) ? $request['broker_split_agg_brk_sp_upfrt'] : 0;
                $contact->broker_split_agg_brk_sp_trail = ($request['broker_split_agg_brk_sp_trail'] > 0) ? $request['broker_split_agg_brk_sp_trail'] : 0;
                $contact->referror_split_referror = ($request['referror_split_referror'] > 0) ? $request['referror_split_referror'] : 0;
                $contact->referror_split_comm_per_deal = ($request['referror_split_comm_per_deal'] > 0) ? $request['referror_split_comm_per_deal'] : 0;
                $contact->referror_split_agg_brk_sp_upfrt = ($request['referror_split_agg_brk_sp_upfrt'] > 0) ? $request['referror_split_agg_brk_sp_upfrt'] : 0;
                $contact->broker_staff_split_comm_per_deal = ($request['broker_staff_split_comm_per_deal'] > 0) ? $request['broker_staff_split_comm_per_deal'] : 0;
                $contact->broker_staff_split_agg_brk_sp_upfrt = ($request['broker_staff_split_agg_brk_sp_upfrt'] > 0) ? $request['broker_staff_split_agg_brk_sp_upfrt'] : 0;
                $contact->broker_staff_split_agg_brk_sp_trail = ($request['broker_staff_split_agg_brk_sp_trail'] > 0) ? $request['broker_staff_split_agg_brk_sp_trail'] : 0;
                $contact->referror_split_agg_brk_sp_trail = ($request['referror_split_agg_brk_sp_trail'] > 0) ? $request['referror_split_agg_brk_sp_trail'] : 0;
                $contact->note = $request['note'];


                $contact->updated_by = Auth::user()->id;
                $contact->save();

                if ($contact->id > 0) {
                        if($currentStatus != $request['status'])
                        {
                            DealStatusUpdate::create([
                                'deal_id' => $contact->id,
                                'from_status' => $currentStatus,
                                'status_id' => $request['status'],
                                'created_by' => Auth::user()->id
                            ]);
                        }
                   /*  if($request['commission_type_1'] > 0 && $request['total_amount_1'] > 0)
                    {
                        if(isset($request['comission_1_hd_id']) && $request['comission_1_hd_id'] > 0)
                        {
                            $dealComm = DealCommission::where('id',$request['comission_1_hd_id'])->where('deal_id',
                                $contact->id)->first();
                            if(!$dealComm)
                            {
                                DB::rollback();
                                return response()->json(['error' => "Invalid Commission record!"]);
                            }
                            $dealComm->updated_by = Auth::user()->id;
                        }else{
                            $dealComm  = new DealCommission();
                            $dealComm->deal_id = $contact->id;
                            $dealComm->order = 1;
                            $dealComm->created_by = Auth::user()->id;
                        }
                        $dealComm->type = isset($request['commission_type_1']) && $request['commission_type_1'] > 0 ?
                            $request['commission_type_1'] : 0;
                        $dealComm->total_amount = $request['total_amount_1'];
                        $dealComm->date_statement = $request['date_statement_1'];
                        $dealComm->agg_amount = $request['agg_amount_1'];
                        $dealComm->broker_amount = $request['broker_amount_1'];
                        $dealComm->bro_amt_date_paid = $request['bro_amt_date_paid_1'];
                        $dealComm->referror_amount = $request['referror_amount_1'];
                        $dealComm->ref_amt_date_paid = $request['ref_amt_date_paid_1'];

                        $dealComm->save();
                    }else{
                        if(isset($request['comission_1_hd_id']) && $request['comission_1_hd_id'] > 0) {
                            $dealComm = DealCommission::where('id', $request['comission_1_hd_id'])->where('deal_id',
                                $contact->id)->first();
                            if (!$dealComm) {
                                DB::rollback();
                                return response()->json(['error' => "Invalid Commission record!"]);
                            }
                            $dealComm->total_amount = 0;
                            $dealComm->date_statement = '';
                            $dealComm->agg_amount = 0;
                            $dealComm->broker_amount = 0;
                            $dealComm->bro_amt_date_paid = '';
                            $dealComm->referror_amount = 0;
                            $dealComm->ref_amt_date_paid = 0;
                            $dealComm->updated_by = Auth::user()->id;
                            $dealComm->save();
                        }
                    }

                    if($request['commission_type_2'] > 0 && $request['total_amount_2'] > 0) {
                        if(isset($request['comission_2_hd_id']) && $request['comission_2_hd_id'] > 0)
                        {
                            $dealComm1 = DealCommission::where('id',$request['comission_2_hd_id'])->where('deal_id',
                                $contact->id)->first();
                            if(!$dealComm1)
                            {
                                DB::rollback();
                                return response()->json(['error' => "Invalid Commission record!"]);
                            }
                            $dealComm1->updated_by = Auth::user()->id;
                        }else{
                            $dealComm1 = new DealCommission();
                            $dealComm1->deal_id = $contact->id;
                            $dealComm1->order = 2;
                            $dealComm1->created_by = Auth::user()->id;
                        }


                        $dealComm1->type = isset($request['commission_type_2']) && $request['commission_type_2'] > 0 ?
                            $request['commission_type_2'] : 0;
                        $dealComm1->total_amount = $request['total_amount_2'];
                        $dealComm1->date_statement = $request['date_statement_2'];
                        $dealComm1->agg_amount = $request['agg_amount_2'];
                        $dealComm1->broker_amount = $request['broker_amount_2'];
                        $dealComm1->bro_amt_date_paid = $request['bro_amt_date_paid_2'];
                        $dealComm1->referror_amount = $request['referror_amount_2'];
                        $dealComm1->ref_amt_date_paid = $request['ref_amt_date_paid_2'];

                        $dealComm1->save();
                    }else{
                        if(isset($request['comission_2_hd_id']) && $request['comission_2_hd_id'] > 0) {
                            $dealComm = DealCommission::where('id', $request['comission_2_hd_id'])->where('deal_id',
                                $contact->id)->first();
                            if (!$dealComm) {
                                DB::rollback();
                                return response()->json(['error' => "Invalid Commission record!"]);
                            }
                            $dealComm->total_amount = 0;
                            $dealComm->date_statement = '';
                            $dealComm->agg_amount = 0;
                            $dealComm->broker_amount = 0;
                            $dealComm->bro_amt_date_paid = '';
                            $dealComm->referror_amount = 0;
                            $dealComm->ref_amt_date_paid = 0;
                            $dealComm->updated_by = Auth::user()->id;
                            $dealComm->save();
                        }
                    }
                    if($request['commission_type_3'] > 0 && $request['total_amount_3'] > 0) {

                        if(isset($request['comission_3_hd_id']) && $request['comission_3_hd_id'] > 0)
                        {
                            $dealComm2 = DealCommission::where('id',$request['comission_3_hd_id'])->where('deal_id',
                                $contact->id)->first();
                            if(!$dealComm2)
                            {
                                DB::rollback();
                                return response()->json(['error' => "Invalid Commission record!"]);
                            }
                        }else{
                            $dealComm2 = new DealCommission();
                            $dealComm2->deal_id = $contact->id;
                            $dealComm2->order = 3;

                        }

                        $dealComm2->type = isset($request['commission_type_3']) && $request['commission_type_3'] > 0 ?
                            $request['commission_type_3'] : 0;
                        $dealComm2->total_amount = $request['total_amount_3'];
                        $dealComm2->date_statement = $request['date_statement_3'];
                        $dealComm2->agg_amount = $request['agg_amount_3'];
                        $dealComm2->broker_amount = $request['broker_amount_3'];
                        $dealComm2->bro_amt_date_paid = $request['bro_amt_date_paid_3'];
                        $dealComm2->referror_amount = $request['referror_amount_3'];
                        $dealComm2->ref_amt_date_paid = $request['ref_amt_date_paid_3'];
                        $dealComm2->created_by = Auth::user()->id;
                        $dealComm2->save();
                    }
                    else{
                        if(isset($request['comission_3_hd_id']) && $request['comission_3_hd_id'] > 0) {
                            $dealComm = DealCommission::where('id', $request['comission_3_hd_id'])->where('deal_id',
                                $contact->id)->first();
                            if (!$dealComm) {
                                DB::rollback();
                                return response()->json(['error' => "Invalid Commission record!"]);
                            }
                            $dealComm->total_amount = 0;
                            $dealComm->date_statement = '';
                            $dealComm->agg_amount = 0;
                            $dealComm->broker_amount = 0;
                            $dealComm->bro_amt_date_paid = '';
                            $dealComm->referror_amount = 0;
                            $dealComm->ref_amt_date_paid = 0;
                            $dealComm->updated_by = Auth::user()->id;
                            $dealComm->save();
                        }
                    }
 */
                        if(isset($request->actuals_comm) && count($request->actuals_comm) > 0)
                        {
                            $com_data = [];
                            foreach($request->actuals_comm as $actual_com)
                            {
                                $com_data[] = [
                                    'deal_id' => $contact->id,
                                    'order' => 1,
                                    'type' => isset($actual_com['commission_type']) && $actual_com['commission_type'] > 0 ?
                                    $actual_com['commission_type'] : 0,
                                     'total_amount'=> $actual_com['total_amount'],
                                     'date_statement' => date('Y-m-d',strtotime($actual_com['date_statement'])),
                                     'agg_amount' => $actual_com['agg_amount'],
                                     'broker_amount' => $actual_com['broker_amount'],
                                     'broker_staff_amount' => $actual_com['broker_staff_amount'],
                                     'bro_staff_amt_date_paid' => date('Y-m-d',strtotime($actual_com['bro_staff_amt_date_paid'])),
                                     'bro_amt_date_paid' => date('Y-m-d',strtotime($actual_com['bro_amt_date_paid'])),
                                     'referror_amount' => $actual_com['referror_amount'],
                                     'ref_amt_date_paid' => date('Y-m-d',strtotime($actual_com['ref_amt_date_paid'])),
                                     'created_by' => Auth::user()->id,
                                     'created_at' => Carbon::now('utc')->toDateTimeString(),
                                     'updated_at' => Carbon::now('utc')->toDateTimeString(),
                                ];
                            }

                            if(count($com_data) > 0)
                            {
                                DealCommission::insert($com_data);
                            }
                        }
                    if (!empty($request->relationship)) {
                        $tempArr = [];
                        $existingAr = [];
                        foreach ($request->relationship as $relation) {
                            if ($relation['linked_to'] > 0 && $relation['relation'] > 0) {
                                if(isset($relation['old_id']) && $relation['old_id'] > 0)
                                {
                                    $existingAr[] = $relation['old_id'];
                                    DealClient::where('id',$relation['old_id'])->where('deal_id',$contact->id)->update([
                                        'client_id' => $relation['linked_to'],
                                        'type'=>$relation['relation'],
                                        'updated_by' => Auth::user()->id
                                    ]);
                                }else{
                                    $tempArr[] = [
                                        'deal_id' => $contact->id,
                                        'client_id' => $relation['linked_to'],
                                        'type'=>$relation['relation'],
                                        'created_by' => Auth::user()->id
                                    ];
                                }
                            }
                        }
                        $delSql = DealClient::where('deal_id',$contact->id);
                        if(count($existingAr)>0)
                        {
                            $delSql = $delSql->whereNotIn('id',$existingAr);
                        }
                        $delSql->delete();
                        if (!empty($tempArr)) {

                            DealClient::insert($tempArr);
                        }
                    }else{
                        DealClient::where('deal_id',$contact->id)->delete();
                    }

                    /*if (!empty($request->tasks)) {
                        $taskArr = [];
                        $existingTskAr = [];
                        foreach ($request->tasks as $task) {
                            if($task['followup_date']!= '' && $task['processor']!='' && $task['details'] != '' && $task['user'] != '')
                            {
                                if(isset($task['old_id']) && $task['old_id'] > 0)
                                {
                                    $existingTskAr[] = $task['old_id'];
                                    DealTask::where('id',$task['old_id'])->where('deal_id',$contact->id)
                                                  ->update([
                                                      'followup_date' => $task['followup_date'],
                                                      'processor' => $task['processor'],
                                                      'details' => $task['details'],
                                                      'user' => $task['user'],
                                                      'updated_by' => Auth::user()->id
                                                  ]);
                                }else {
                                    $taskArr[] = [
                                        'deal_id' => $contact->id,
                                        'followup_date' => $task['followup_date'],
                                        'processor' => $task['processor'],
                                        'details' => $task['details'],
                                        'user' => $task['user'],
                                        'created_by' => Auth::user()->id,
                                        'created_at' => Carbon::now('utc')->toDateTimeString(),
                                        'updated_at' => Carbon::now('utc')->toDateTimeString()
                                    ];
                                }
                            }

                        }
                        $delTSql = DealTask::where('deal_id',$contact->id);
                        if(count($existingTskAr)>0)
                        {
                            $delTSql = $delTSql->whereNotIn('id',$existingTskAr);
                        }
                        $delTSql->delete();
                        if (!empty($taskArr)) {
                            DealTask::insert($taskArr);
                        }
                    }else{
                        DealTask::where('deal_id',$contact->id)->delete();
                    }*/
                    DB::commit();
                } else {
                    DB::rollback();
                    return response()->json(['error' => "Something went wrong while save record!"]);
                }
            } else {
                return response()->json(['error' => "Record not found!"]);
            }

           return response()->json(['success' => 'Record updated successfully!','link'=>route('admin.deals.view', encrypt ($contact->id))]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function dealDelete($id)
    {
        $contact = ContactSearch::find(decrypt($id));
        if ($contact) {
            $contact->delete();
            return redirect()->back()->with('success', 'Contact deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Contact not found.');
        }
    }

    public function getRecords(Request $request)
    {
        try {
            $input =  $request->all();

            if ($request->ajax()) {
                $data = Deal::select(DB::raw("deals.*,given_name,preferred_name,lenders.code as lendername,CONCAT(cs.surname,', ',cs.preferred_name) AS preferred_name, CONCAT(LPAD(st.status_code,2,0),':',st.name) status, p.name productname, cs.email, date_format(deals.created_at,'".$this->mysql_date_format."') date_sattled") )
                    ->leftJoin('brokers','deals.broker_id','=','brokers.id')
                    ->leftJoin('contact_searches as cs','deals.contact_id','=','cs.id')
                    ->leftJoin('lenders','deals.lender_id','=','lenders.id')
                    ->leftJoin('deal_statuses as st','deals.status','=','st.id')
                    ->leftJoin('products as p','deals.product_id','=','p.id')
                    ;

                return DataTables::of($data)
                        ->addIndexColumn()
                    ->editColumn('id',function($row){


                        return $row->id;

                    })
                        ->addColumn('action', function($row){
                            $html = ' &nbsp;'.
                                '<div class="dropdown">'.
                                '          <button id="dLabel" type="button" data-toggle="dropdown"'.
                                '                  aria-haspopup="true" aria-expanded="false" class="btn btn-primary btn-sm"><i class="pe-7s-helm"></i>  <span class="caret"></span>' .
                                '          </button>' .
                                '          <ul class="dropdown-menu broker_menu " role="menu" ' .
                                'aria-labelledby="dLabel">' .
                                '<li class="nav-item"><a href="'.route('admin.deals.edit',encrypt($row->id)).'" class="edit "><i title="Edit" class="pe-7s-pen btn-icon-wrapper"></i> Edit</a></li>'.
                                '            <li class="nav-item"><a  href="'.route('admin.deals.view',encrypt($row->id))
                                .'"><i class="fa fa-eye"></i> View</a></li>'.

                               /* '            <li class="nav-item"><a  href="'.route('admin.dealtsk.list',encrypt($row->id)).'"><i class="fa fa-tasks"></i> Tasks</a></li>' .
                                '            <li class="nav-item"><a  href="javascript:void(0)" data-id="'.encrypt($row->id)
                                .'" onclick="return showCommissions(this)"><i class="fa fa-tasks"></i> Commissions</a></li>' .*/
                                '          </ul>' .
                                '        </div>';

                                return $html;
                        })
                        ->filter(function ($instance) use ($request) {
                            if (!empty($request->get('type'))) {
                                $instance->where('cs.search_for', $request->get('type'));
                            } if (!empty($request->get('deal_id'))) {
                                $instance->where('deals.id', $request->get('deal_id'));
                            }
                            if (!empty($request->get('surname'))) {
                                $instance->where(function($w) use($request){
                                    $w->where('cs.surname',$request->get('surname'))
                                      ->orWhere('brokers.surname',$request->get('surname'));
                                });
                            }if (!empty($request->get('first_name'))) {

                                $instance->where(function($w) use($request){
                                    $w->where('cs.preferred_name',$request->get('first_name'))
                                      ->orWhere('brokers.given_name',$request->get('first_name'));
                                });
                            }
                            if (!empty($request->get('trading'))) {
                                $instance->where('trading',$request->get('trading'));
                            }if (!empty($request->get('entity_name'))) {
                                $instance->where(function($w) use($request){
                                    $w->where('cs.entity_name',$request->get('entity_name'))
                                      ->orWhere('brokers.entity_name',$request->get('entity_name'));
                                });
                            }
                            if (!empty($request->get('lender_id'))) {
                                $instance->where('lender_id',$request->get('lender_id'));
                            }
                            if (!empty($request->get('product_id'))) {
                                $instance->where('product_id',$request->get('product_id'));
                            }
                            if (!empty($request->get('loan_op')) && !empty($request->get('loan_amt'))) {
                                $op = $request->get('loan_op');
                                if($op == "eq") $op = '=';
                                if($op == "gt") $op = '>';
                                if($op == "gte") $op = '>=';
                                if($op == "lt") $op = '<';
                                if($op == "lte") $op = '<=';

                                $instance->where('actual_loan',$op,$request->get('loan_amt'));
                            }
                            if (!empty($request->get('ex_loan_repaid'))) {
                                $instance->where(function($w) use($request){
                                    $w->whereNull('loan_repaid')
                                    ->orWhere('loan_repaid', '=', "");
                                });
                            }
                            if (!empty($request->get('has_trail'))) {
                                $instance->where('has_trail','=',1);
                            }
                            if (!empty($request->get('status'))) {
                                $instance->where('status','=',$request->get('status'));
                            }
                            if (!empty($request->get('from_date'))) {
                                $instance->where('proposed_settlement','>=',$request->get('from_date'));
                            }
                            if (!empty($request->get('to_date'))) {
                                $instance->where('proposed_settlement','<=',$request->get('to_date'));
                            }
                        })
                        ->rawColumns(['action','id'])
                        ->make(true);
            }

            // return response()->json(['success' => true, 'payload' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function commissions(Request $request)
    {
        $data = [];

        return view('admin.deal.commissions', $data);
    }

    public function commissionPost(Request $request)
    {

        $validated = $request->validate([
            'file' => 'required|mimes:csv,txt'

        ], [], [
            'file' => 'File'
        ]);

        try {

            $extension = $request->file('file')->getClientOriginalExtension(); //
            $mimeType = $request->file('file')->getClientMimeType(); //

            $csv_mimetypes = array(
                'text/csv',
                'text/plain',
                'application/csv',
                'text/comma-separated-values',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext',
                'application/octet-stream',
                'application/txt',
            );

            if (!in_array($mimeType, $csv_mimetypes)) {
                return response()->json(['error' => "Invalid file!"]);
            }
            $header = null;
            $data = array();
            $delimiter = ',';
            $this->comm_types = CommissionType::all()->pluck('id','name')->toArray();

            if (($handle = fopen($request->file('file')->getRealPath(), 'r')) !== false)
            {
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
                {
                    if (!$header)
                    {
                        $row[] = 'deal_id';
                        $row[] = 'broker_id';
                        $row[] = 'broker_staff_id';
                        $row[] = 'contact_id';
                        $row[] = 'product_id';
                        $header = $row;
                        $header = array_map('trim', $header);
                        $header = array_map('cleanString', $header);
                    }
                    else
                    {   $row = array_map('trim', $row);
                            $row[] = 0;
                            $row[] = 0;
                            $row[] = 0;
                            $row[] = 0;
                            $row[] = 0;
                            $tempRw = array_combine($header, $row);
                            if($tempRw['account_number'] != '')
                            {
                                $tempRw['account_number'] = trim(str_replace('#','',$tempRw['account_number']));
                            }

                            if($tempRw['loan_amount'] != '')
                            {
                                $tempRw['loan_amount'] = trim(number_format(str_replace(['$',','],'',$tempRw['loan_amount']),2,'.',''));
                            }
                            if($tempRw['rate'] != '')
                            {
                                $tempRw['rate'] = trim(number_format(str_replace(['$','%',','],'',$tempRw['rate']),2,'.',''));
                            }
                            if($tempRw['commission'] != '')
                            {
                                $tempRw['commission'] = trim(number_format(str_replace(['$',','],'',$tempRw['commission']),2,'.',''));
                            }
                            if($tempRw['gst'] != '')
                            {
                                $tempRw['gst'] = trim(number_format(str_replace(['$',','],'',$tempRw['gst']),2,'.',''));
                            }
                            if($tempRw['total_paid'] != '')
                            {
                                $tempRw['total_paid'] = trim(number_format(str_replace(['$',','],'',$tempRw['total_paid']),2,'.',''));
                            }

                            if($tempRw['period'] != '')
                            {
                                $tempRw['period'] = date('Y-m',strtotime($tempRw['period']));
                            }
                            if($tempRw['settlement_date'] != '')
                            {
                                $tempRw['settlement_date'] = date('Y-m-d',strtotime($tempRw['settlement_date']));
                            }
                            if(array_key_exists($tempRw['commission_type'],$this->comm_types))
                            {
                                $tempRw['commission_type'] = $this->comm_types[$tempRw['commission_type']];
                            }else{
                                $tempRw['commission_type'] = 1;
                            }

                        $data[] = $tempRw;
                    }
                }
                fclose($handle);
            }

            if(!empty($data))
            {
                $account_numbers = Arr::pluck($data, 'account_number');

                $deals = Deal::whereIn('loan_ref',array_unique($account_numbers))->get()->keyBy('loan_ref')->toArray();

                $sql = array();

                foreach( $data as $key => $row ) {

                    if($deals &&  array_key_exists($row['account_number'],$deals))
                    {
                        $row['deal_id'] = $deals[$row['account_number']]['id'];
                        $row['broker_id'] = isset($deals[$row['account_number']]['broker_id']) && $deals[$row['account_number']]['broker_id'] > 0 ? $deals[$row['account_number']]['broker_id'] : 0 ;
                        $row['broker_staff_id'] = isset($deals[$row['account_number']]['broker_staff_id']) &&
                            $deals[$row['account_number']]['broker_staff_id'] > 0 ?$deals[$row['account_number']]['broker_staff_id'] : 0;
                        $row['contact_id'] = isset($deals[$row['account_number']]['contact_id']) && $deals[$row['account_number']]['contact_id'] > 0 ? $deals[$row['account_number']]['contact_id'] : 0;
                        $row['product_id'] = isset($deals[$row['account_number']]['product_id']) && $deals[$row['account_number']]['product_id'] > 0 ? $deals[$row['account_number']]['product_id'] : 0;

                    }

                    $sql[] = '("'.implode('","',$row).'")';
                }
                $chunckedArray=array_chunk($sql,50);
                $query = [];
                if(count($chunckedArray) > 0)
                {
                    foreach($chunckedArray as $chkAr)
                    {
                        $query[]= "  INSERT INTO commissions_data  (`".implode('`,`',$header)."`) VALUES ".implode(",",$chkAr);

                    }
                }

               if(!empty($query))
               {
                   $pdo = DB::connection()->getPdo();

                   foreach($query as $q)
                   {
                       $sth = $pdo->prepare($q);
                       $sth->execute();
                   }
               }
                return response()->json(['success' => 'Record uploaded successfully!']);
            }else{
                return response()->json(['error' => "File is empty!"]);
            }

            return response()->json(['success' => 'Record uploaded successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getCommissions(Request $request)
    {
        try {
            $input =  $request->all();

            if ($request->ajax()) {
                $data = CommissionData::select(DB::raw("commissions_data.*, date_format(commissions_data.created_at,'%Y-%m-%d')
                date_sattled,commission_types.name as commission_type_name") )->leftJoin('commission_types','commission_types.id','=','commissions_data.commission_type');

                return DataTables::of($data)
                                 ->addIndexColumn()->editColumn('commission_type',function($row){
                        return $row->commission_type_name;
                    })
                                 ->make(true);
            }

            // return response()->json(['success' => true, 'payload' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getSgDCommissions(Request $request)
    {
        try {

            $input =  $request->all();

            if ($request->ajax()) {

                $deal = Deal::find(decrypt($request->deal_id));
                if(!$deal)
                {
                    return response()->json(['error' => 'Invalid Deal!']);
                }
                $data = CommissionData::select(DB::raw("commissions_data.id, date_format(commissions_data.settlement_date,'%Y-%m-%d') as settlement_date,commission_types.name as commission_type_name, CONCAT(contact_searches.surname,',',contact_searches.preferred_name) as client,commissions_data.account_number as account_no,commissions_data.period,commissions_data.commission,commissions_data.gst,commissions_data.total_paid,commissions_data.payment_no") )->leftJoin('commission_types','commission_types.id','=','commissions_data.commission_type')->leftJoin('contact_searches','contact_searches.id','=','commissions_data.contact_id')->where('deal_id',$deal->id);

                return DataTables::of($data)
                                 ->addIndexColumn()
                    ->filterColumn('account_no', function($query, $keyword) {
                        $sql = "commissions_data.account_number  like ?";
                        $query->whereRaw($sql, ["%{$keyword}%"]);
                    })->editColumn('commission_type',function($row){
                        return $row->commission_type_name;
                    })
                                 ->make(true);
            }


            // return response()->json(['success' => true, 'payload' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function addActual(Request $request,$id)
    {
        $validated = $request->validate([
            'commission_type_1' => 'required',
            'total_amount_1' => 'required'

        ], [], [
            'commission_type_1' => 'Commission Type',
            'total_amount_1' => 'Total Amount'
        ]);

        $id = decrypt($id);
        $deal = Deal::find($id);

        if(!$deal)
        {
            return response()->json(['error'=>'Invalid Deal!']);
        }

        DealCommission::create([
            'deal_id' => $deal->id,
            'order' => 1,
            'type' => isset($request->commission_type_1) && $request->commission_type_1 > 0 ?
                $request->commission_type_1 : 0,
            'total_amount'=> $request->total_amount_1,
            'date_statement' => $request->date_statement_1 != '' ? date('Y-m-d',strtotime($request->date_statement_1)
            ) : NULL,
            'agg_amount' => $request->agg_amount_1,
            'broker_amount' => $request->broker_amount_1,
            'broker_staff_amount' => $request->broker_staff_amount_1,
            'bro_staff_amt_date_paid' => $request->bro_staff_amt_date_paid_1,
            'bro_amt_date_paid' => $request->bro_amt_date_paid_1 != '' ? date('Y-m-d',strtotime
    ($request->bro_amt_date_paid_1)) : NULL,
            'referror_amount' => $request->referror_amount_1,
            'ref_amt_date_paid' => $request->ref_amt_date_paid_1 != '' ?  date('Y-m-d',strtotime
    ($request->ref_amt_date_paid_1)) : NULL,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now('utc')->toDateTimeString(),
            'updated_at' => Carbon::now('utc')->toDateTimeString(),
        ]);

        response()->json(['success' => 'Actual Added!']);
    }
}
