<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ABP;
use App\Models\Broker;
use App\Models\City;
use App\Models\User;
use App\Models\ClientReferral;
use App\Models\ClientRelation;
use App\Models\ClientRole;
use App\Models\CommissionData;
use App\Models\Commission;
use App\Models\Deal;
use App\Models\Industry;
use App\Models\Processor;
use App\Models\ReferrerCommission;
use App\Models\Lenders;
use App\Models\RefferorRelation;
use App\Models\Service;
use App\Models\State;
use App\Models\TaskRelation;
use App\Models\ContactSearch;
use App\Models\RefferorCommissionSchedule;
use App\Models\ReferrerCommissionModelinstitute;
use App\Models\ReferrerCommissionModel;
use App\Models\Relationship;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;
use Yajra\DataTables\Facades\DataTables;

class ContactSearchController extends Controller
{
    public function contactList()
    {

        $data = [];
        $data['states'] = State::all();
        $data['cities'] = City::all();
        $data['industries'] = Industry::all();
        //echo "<pre>";
        //print_R($data);die;
        //echo "<pre>";
        return view('admin.contact.list', $data);
    }

    public function contactAdd()
    {
        
        $relations = Relationship::all();
        $data['relations'] = $relations;
        $data['processors'] = Processor::all();
        $data['clients'] = ContactSearch::select(DB::raw('id,CONCAT_WS(" ",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name, CONCAT(first_name," ",middle_name, " ", surname) as client_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name
        ,street_number,street_name,same_as_residential,postal_street_number')
        )->where('search_for',1)->whereNull('deleted_at')->get();
       $data['refferors'] = ContactSearch::select(DB::raw('id,CONCAT_WS(" ",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,trading,trust_name,surname,first_name,
       middle_name,preferred_name,entity_name,street_number,street_name,same_as_residential,postal_street_number')
       )->where('search_for',2)->whereNull('deleted_at')->get();
        $data['roles'] = ClientRole::all();
        $data['refferor_relations'] = RefferorRelation::whereNull('deleted_at')->get();
        $data['services'] = Service::whereNull('deleted_at')->get();
        $data['industries'] = Industry::all();
        $data['abps'] =  Broker::select(
            DB::raw('id,CONCAT_WS(" ",trading,trust_name,surname,given_name) as display_name,trading,trust_name,surname,given_name')
        )->where('is_bdm', 0)->whereNull('deleted_at')->get();
        $data['states'] = State::all();
        $data['cities'] = City::all();
       echo '<pre>';
       // print_R($relations);die;
     echo '</pre>';
        return view('admin.contact.add_edit', $data);
    }

    public function ReferrerAdd()
    {
        $relations = Relationship::all();
        $data['relations'] = $relations;
        $data['clients'] = ContactSearch::select(DB::raw('id,CONCAT_WS(" ",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->where('search_for',1)->whereNull('deleted_at')->get();
       $data['refferors'] = ContactSearch::select(DB::raw('id,CONCAT_WS(" ",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name, CONCAT(first_name," ",middle_name, " ", surname) as refferor_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
       )->where('search_for',2)->whereNull('deleted_at')->get();
        $data['roles'] = ClientRole::all();
        $data['refferor_relations'] = RefferorRelation::whereNull('deleted_at')->get();
        $data['services'] = Service::whereNull('deleted_at')->get();
        $data['industries'] = Industry::all();
        $data['abps'] =  Broker::select(
            DB::raw('id,CONCAT(given_name," ",surname) as display_name,trading,trust_name,surname,given_name')
        )->where('is_bdm', 0)->whereNull('deleted_at')->get();
        $data['states'] = State::all();
        $data['cities'] = City::all();
        return view('admin.contact.add_edit_referror', $data);
    }

    public function contactPost(Request $request)
    {
        $validated = $request->validate([
            'search_for' => 'required|max:255',
            /*'trading' => 'exclude_if:individual,1|required|max:255',
            'services_1' => 'exclude_if:referred_to_1,null|required',
            'date_1' => 'exclude_if:referred_to_1,null|required|date',
            'services_2' => 'exclude_if:referred_to_2,null|required',
            'date_2' => 'exclude_if:referred_to_2,null|required|date',*/
            //'acc_no' => 'exclude_if:acc_name,null|required',
            //'bank' => 'exclude_if:acc_name,null|required',
            //'bsb' => 'exclude_if:acc_name,null|required',
            // 'relationship.*.relation' => 'exclude_if:relationship.*.linked_to,null|required',
           /* 'tasks.*.followup_date' => 'required|date',
            'tasks.*.processor' => 'required',
            'tasks.*.details' => 'required',
            'tasks.*.user' => 'required',*/
           'surname' => 'required',
           'preferred_name' => 'required',
        ],[], [
            'search_for' => 'Type'
        ]);

        try {

            DB::beginTransaction();
            $contact = new ContactSearch();

            if($request['dob'] != '')
            {
                $tempdob = str_replace('/','-',$request['dob']);
                $request['dob'] = date('Y-m-d',strtotime($tempdob));
            }
            if($request['date_1'] != '')
            {
                $tempdob = str_replace('/','-',$request['date_1']);
                $request['date_1'] = date('Y-m-d',strtotime($tempdob));
            }
            if($request['date_2'] != '')
            {
                $tempdob = str_replace('/','-',$request['date_2']);
                $request['date_2'] = date('Y-m-d',strtotime($tempdob));
            }
            $contact->search_for = $request['search_for'];
            $contact->individual = isset($request['individual']) && $request['individual'] ?  1 : 0;
            $contact->general_mail_out = isset($request['general_mail_out']) && $request['general_mail_out'] ?  1 : 0;
            $contact->trading = $request['trading'];
            $contact->trust_name = $request['trust_name'];
            $contact->surname = $request['surname'];
            $contact->first_name = $request['first_name'];
            $contact->preferred_name = $request['preferred_name'];
            $contact->middle_name = $request['middle_name'];
            $contact->dob = $request['dob'];
            $contact->role_title = $request['role_title'];
            $contact->role = $request['role'];
            $contact->entity_name = $request['entity_name'];
            $contact->principle_contact = $request['principle_contact'];
            $contact->work_phone = $request['work_phone'];
            $contact->home_phone = $request['home_phone'];
            $contact->mobile_phone = $request['mobile_phone'];
            $contact->fax = $request['fax'];
            $contact->email = $request['email'];
            $contact->web = $request['web'];
            $contact->street_number = $request['street_number'];
            $contact->street_name = $request['street_name'];
            $contact->street_type = $request['street_type'];
            $contact->suburb = $request['suburb'];

            $contact->city = $request['city'];
            $contact->state = $request['state'];
            $contact->postal_code = $request['postal_code'];
            $contact->postal_street_number = $request['postal_street_number'];
            $contact->postal_street_name = $request['postal_street_name'];
            $contact->postal_street_type = $request['postal_street_type'];
            $contact->postal_suburb = $request['postal_suburb'];
            $contact->same_as_residential = isset($request['same_as_residential']) ? 1 : 0;
            $contact->mail_city = $request['mail_city'];
            $contact->mail_state = $request['mail_state'];
            $contact->mail_postal_code = $request['mail_postal_code'];
            $contact->client_industry = $request['client_industry'];
            $contact->other_industry = ($request['client_industry'] == 27) ? $request['other_industry'] : '';
            $contact->note = $request['note'];
            $contact->acc_name = $request['acc_name'];
            $contact->acc_no = $request['acc_no'];
            $contact->bank = $request['bank'];
            $contact->bsb = $request['bsb'];
            $contact->abp = $request['abp'];
            $contact->abn = $request['abn'];
            $contact->referrer_type = $request['referrer_type'];
            $contact->referrer_id = ($request['referrer_type'] == 2) ? $request['client_id'] : $request['referrer_id']; //$request['referrer_id'];
            $contact->other_referrer = $request['other_referrer'];
            $contact->social_media_link = $request['social_media_link'];
            $contact->refferor_relation_to_client = $request['refferor_relation_to_client'];
            $contact->refferor_note = $request['refferor_note'];
            $contact->created_by = Auth::user()->id;
            $contact->save();
            if ($contact->id > 0) {
                if (!empty($request->referrors)) {
                    $tempArr = [];
                    foreach ($request->referrors as $referrors) {
                        if ($referrors['referrer_id'] > 0 && $referrors['upfront'] != '') {
                            $tempArr[] = [
                                'client_id' => $contact->id,
                                'referrer_id' => $referrors['referrer_id'],
                                'upfront' => $referrors['upfront'],
                                'trail' => $referrors['trail'],
                                'comm_per_deal' => $referrors['comm_per_deal'],
                            ];
                        }
                    }

                    if (!empty($tempArr)) {
                        ReferrerCommission::insert($tempArr);
                    }
                }
            }
            if ($contact->id > 0) {
                if (!empty($request->relationship)) {
                    $tempArr = [];
                    foreach ($request->relationship as $relation) {
                        if ($relation['linked_to'] > 0 && $relation['relation'] > 0) {
                            $tempArr[] = [
                            'client_id' => $contact->id,
                            'relation_with' => $relation['linked_to'],
                            'relation'=>$relation['relation']
                        ];
                        }
                    }

                    if (!empty($tempArr)) {
                        ClientRelation::insert($tempArr);
                    }
                }
                if (!empty($request['client_referrals'])) {

                    $tempArr = [];
                    foreach ($request['client_referrals'] as $client_referral) {
                        if ($client_referral['referred_to'] !='' && $client_referral['service_id'] > 0) {
                            $tempdate = str_replace('/','-',$client_referral['date']);
                            $tempArr[] = [
                            'client_id' => $contact->id,
                            'referred_to' => $client_referral['referred_to'],
                            'notes' => $client_referral['notes'],
                            'date' => date('Y-m-d',strtotime($tempdate)),
                            'service_id'=>$client_referral['service_id']
                        ];
                        }
                    }

                    if (!empty($tempArr)) {

                        ClientReferral::insert($tempArr);
                    }
                }

                if (!empty($request->tasks)) {
                    $taskArr = [];
                    foreach ($request->tasks as $task) {
                        if($task['followup_date']!= '' && $task['processor']!='' && $task['details'] != '')
                        {

                                $tempdob = str_replace('/','-',$task['followup_date']);
                                $task['followup_date'] = date('Y-m-d',strtotime($tempdob));

                            $taskArr[] = [
                                'client_id' => $contact->id,
                                'followup_date' => $task['followup_date'],
                                'processor' => $task['processor'],
                                'details' => $task['details'],
                                //'user' => $task['user'],
                                'created_by' => Auth::user()->id,
                                'created_at' => Carbon::now('utc')->toDateTimeString(),
                                'updated_at' => Carbon::now('utc')->toDateTimeString()
                            ];
                        }

                    }

                    if (!empty($taskArr)) {
                        TaskRelation::insert($taskArr);
                    }
                }
                DB::commit();
            }
            else{
                DB::rollback();
                return response()->json(['error'=>"Something went wrong while save record!"]);
            }
            //print_R($contact->id);

            //return redirect()->route('admin.contact.list')->with('success', 'Contact detail added successfully.');
           // Session::flash('message', 'Record added successfully!');
           return response()->json(['success'=>'Record added successfully!','id'=>encrypt($contact->id)]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error'=> $e->getMessage()]);

        }
    }

    public function contactEdit($id)
    {

        $contact = ContactSearch::select(DB::raw('contact_searches.*,IF(contact_searches.dob IS NOT NULL,DATE_FORMAT(contact_searches.dob,"'.$this->mysql_date_format.'"),"") as dob,IF(contact_searches.date_1 IS NOT NULL,DATE_FORMAT(contact_searches.date_1,"'.$this->mysql_date_format.'"),"") as date_1,IF(contact_searches.date_2 IS NOT NULL,DATE_FORMAT(contact_searches.date_2,"'.$this->mysql_date_format.'"),"") as date_2'))->with(['relations','tasks'])->where('id',decrypt($id))->first();
        $data['processors'] = Processor::all();

        $data['relations'] = Relationship::all();
        $data['refferor_relation'] = RefferorRelation::all();;
        
        $data['clients'] = ContactSearch::select(DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,CONCAT(surname," ",first_name," ",middle_name) as client_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->where('search_for',1)->whereNull('deleted_at')->get();
        //echo '<pre>';
        //print_R($data['clients']);die;
        //echo '</pre>';
        $data['refferors'] = ContactSearch::select(DB::raw('id,CONCAT_WS(",",trading,trust_name,CONCAT(surname," ",first_name," ",middle_name),preferred_name,entity_name) as display_name,CONCAT(first_name," ",middle_name, " ", surname) as refferor_name,trading,trust_name,surname,first_name,middle_name,preferred_name,entity_name')
        )->where('search_for',2)->whereNull('deleted_at')->get();
        $data['roles'] = ClientRole::all();
        $data['refferor_relations'] = RefferorRelation::whereNull('deleted_at')->get();
        $data['services'] = Service::whereNull('deleted_at')->get();
        $data['industries'] = Industry::all();
        $data['abps'] =  Broker::select(
            DB::raw('id,CONCAT(given_name," ",surname) as display_name,trading,trust_name,surname,given_name')
        )->where('is_bdm', 0)->whereNull('deleted_at')->get();
        $data['states'] = State::all();
        $data['cities'] = City::all();
        $data['contact'] = $contact;
        $data['client_referrals'] = ClientReferral::whereClientId(decrypt($id))->get();

        if ($contact) {
            $tempalte = 'add_edit';
            if($contact->search_for == 2)
            {
                $tempalte = 'add_edit_referror';
            }
            return view('admin.contact.'.$tempalte, $data);
        } else {
            return redirect()->back()->with('error', 'Contact not found.');
        }
    }

    public function contactView($id)
    {
        
        // abp.id as contact_abp_id,CONCAT_WS(",",abp.trading,abp.trust_name,abp.surname,abp.given_name) abp_name,
        $contact = ContactSearch::select(DB::raw('contact_searches.*,
        abp.id as contact_abp_id,CONCAT_WS(",",abp.trading) abp_name,
        states.name as state_name,
        cities.name as city_name,
        IF(contact_searches.client_industry=27,
        contact_searches.other_industry,
        industries.name) as industry_name,
        ser1.name as service_1_display,  
        ser2.name as service_2_display,
        CONCAT_WS(",",reffered_by_client.trading,reffered_by_client.trust_name,CONCAT(reffered_by_client.surname," ",reffered_by_client.first_name," ",reffered_by_client.middle_name),reffered_by_client.preferred_name,reffered_by_client.entity_name) as reffered_by_client_display_name, relationship.name as refferor_relation_display_client,IF(contact_searches.dob IS NOT NULL,DATE_FORMAT(contact_searches.dob,"'.$this->mysql_date_format.'"),"") as dob,IF(contact_searches.date_1 IS NOT NULL,DATE_FORMAT(contact_searches.date_1,"'.$this->mysql_date_format.'"),"") as date_1,IF(contact_searches.date_2 IS NOT NULL,DATE_FORMAT(contact_searches.date_2,"'.$this->mysql_date_format.'"),"") as date_2,mail_cities.name as mail_city,mail_states.name as mail_state'))
        ->with(['relationDetails','tasks'])->leftJoin('brokers as abp','abp.id','=','contact_searches.abp')->leftJoin('states','states.id','=','contact_searches.state')
        ->leftJoin('cities','cities.id','=','contact_searches.city')->leftJoin('industries','industries.id','=','contact_searches.client_industry')->leftJoin('services as ser1','ser1.id','=','contact_searches.services_1')
        ->leftJoin('services as ser2','ser2.id','=','contact_searches.services_2')->leftJoin('contact_searches as reffered_by_client','reffered_by_client.id','=','contact_searches.referrer_id')
        ->leftJoin('relationship','relationship.id','=','contact_searches.refferor_relation_to_client')->leftJoin('states as mail_states','mail_states.id','=','contact_searches.mail_state')
        ->leftJoin('cities as mail_cities','mail_cities.id','=','contact_searches.mail_city')->where('contact_searches.id',decrypt($id))->first();
        $client_referrals=ClientReferral::where('client_id',decrypt($id))
            ->with(['service'])
            ->get();
            
           // print_R($contact->referrer_type);die;
            
            if($contact->referrer_type ==1){
                
                $contact1 = ContactSearch::find($contact->other_referrer);
                
                $referrer_name = $contact1->first_name.' '.$contact1->surname;
                $title = 'Referrer';
                
            }elseif($contact->referrer_type ==2){
                
                $contact1 = ContactSearch::find($contact->referrer_id);
                
                $referrer_name = $contact1->surname.' '.$contact1->first_name;
                $title = 'Existing Clients';
                
            }elseif($contact->referrer_type ==3){
                
                $contact1 = Processor::find($contact->referrer_id);
                
                $referrer_name = $contact1->name;
                $title = 'Staff Referral';
                    
            }elseif($contact->referrer_type ==4){
            
                
                if($contact->social_media_link ==1){
                    $referrer_name = 'Facebook';
                    
                }elseif($contact->social_media_link ==2){
                    $referrer_name = 'Twitter';
                    
                }elseif($contact->social_media_link ==3){
                    $referrer_name = 'Instagram';
                }elseif($contact->social_media_link ==4){
                    $referrer_name = 'Youtube';
                    
                }else{
                    $referrer_name ='';
                }
                
                $title = 'Social Media';
                    
                    
                
            }else{
                $referrer_name ='';
                $title = '';
            }
            
            //referrer_id
            //social_media_link
            //other_referrer
        if ($contact) {
            //$processors = Processor::all();
            $processors = User::where('role','!=','admin')->get();
            
            return view('admin.contact.view', compact('contact','processors','client_referrals','referrer_name','title'));
        } else {
            return redirect()->back()->with('error', 'Contact not found.');
        }
    }
    
    public function contactViewReferrer($id)
    {
    
        $contact = ContactSearch::select(DB::raw('contact_searches.*,rcommmodel_tbl.id as rcomid,rcommmodel_tbl.commission_model_id,rcommmodel_tbl.upfront_per,rcommmodel_tbl.trail_per,rcommmodel_tbl.flat_fee_chrg,
        abp.id as contact_abp_id,CONCAT_WS(",",abp.trading,abp.trust_name,abp.surname,abp.given_name) abp_name,
        states.name as state_name,
        cities.name as city_name,
        IF(contact_searches.client_industry=27,
        contact_searches.other_industry,
        industries.name) as industry_name,
        ser1.name as service_1_display,
        ser2.name as service_2_display,
        CONCAT_WS(",",reffered_by_client.trading,reffered_by_client.trust_name,CONCAT(reffered_by_client.surname," ",reffered_by_client.first_name," ",reffered_by_client.middle_name),reffered_by_client.preferred_name,reffered_by_client.entity_name) as reffered_by_client_display_name, refferor_relations.name as refferor_relation_display_client,IF(contact_searches.dob IS NOT NULL,DATE_FORMAT(contact_searches.dob,"'.$this->mysql_date_format.'"),"") as dob,IF(contact_searches.date_1 IS NOT NULL,DATE_FORMAT(contact_searches.date_1,"'.$this->mysql_date_format.'"),"") as date_1,IF(contact_searches.date_2 IS NOT NULL,DATE_FORMAT(contact_searches.date_2,"'.$this->mysql_date_format.'"),"") as date_2,mail_cities.name as mail_city,mail_states.name as mail_state'))->with(['relationDetails','tasks'])->leftJoin('brokers as abp','abp.id','=','contact_searches.abp')->leftJoin('states','states.id','=','contact_searches.state')->leftJoin('cities','cities.id','=','contact_searches.city')->leftJoin('industries','industries.id','=','contact_searches.client_industry')->leftJoin('services as ser1','ser1.id','=','contact_searches.services_1')->leftJoin('services as ser2','ser2.id','=','contact_searches.services_2')->leftJoin('contact_searches as reffered_by_client','reffered_by_client.id','=','contact_searches.referrer_id')->leftJoin('refferor_relations','refferor_relations.id','=','contact_searches.refferor_relation_to_client')->leftJoin('states as mail_states','mail_states.id','=','contact_searches.mail_state')->leftJoin('cities as mail_cities','mail_cities.id','=','contact_searches.mail_city')
        ->where('contact_searches.id',decrypt($id))
        ->leftJoin('referrer_commission_model as rcommmodel_tbl','rcommmodel_tbl.referrer_id','=','contact_searches.id')
        ->first();
        $client_referrals=ClientReferral::where('client_id',decrypt($id))
            ->with(['service'])
            ->get();
        if ($contact) {
            //$processors = Processor::all();
            $processors = User::where('role','!=','admin')->get();
            
       $institutes= Lenders::all();
        $commission_models = Commission::all();
            return view('admin.contact.view_referrer', compact('contact','processors','client_referrals','institutes','commission_models'));
        } else {
            return redirect()->back()->with('error', 'Contact not found.');
        }
    }

    public function contactUpdate(Request $request, $id)
    {
        
        try{
            // $validated = $request->validate([
            //     'search_for' => 'required|max:255',
            //     'middle_name' => 'required',
            //     'preferred_name' => 'required',
            //     /*'trading' => 'exclude_if:individual,1|required|max:255',
            //     'services_1' => 'exclude_if:referred_to_1,null|required',
            //     'date_1' => 'exclude_if:referred_to_1,null|required|date',
            //     'services_2' => 'exclude_if:referred_to_2,null|required',
            //     'date_2' => 'exclude_if:referred_to_2,null|required|date',*/
            //     //'acc_no' => 'exclude_if:acc_name,null|required',
            //     //'bank' => 'exclude_if:acc_name,null|required',
            //     //'bsb' => 'exclude_if:acc_name,null|required',
            //     // 'relationship.*.relation' => 'exclude_if:relationship.*.linked_to,null|required',
            //     /* 'tasks.*.followup_date' => 'required|date',
            //      'tasks.*.processor' => 'required',
            //      'tasks.*.details' => 'required',
            //      'tasks.*.user' => 'required',*/
            // ]);
            $contact = ContactSearch::find(decrypt($id));
            

            if ($contact) 
            {
                if($request['dob'] != '')
                {
                $tempdob = str_replace('/','-',$request['dob']);
                $request['dob'] = date('Y-m-d',strtotime($tempdob));
                }
                if($request['date_1'] != '')
                {
                    $tempdob = str_replace('/','-',$request['date_1']);
                    $request['date_1'] = date('Y-m-d',strtotime($tempdob));
                }
                if($request['date_2'] != '')
                {
                    $tempdob = str_replace('/','-',$request['date_2']);
                    $request['date_2'] = date('Y-m-d',strtotime($tempdob));
                }
                DB::beginTransaction();
                $contact->search_for = $request['search_for'];
                $contact->individual = isset($request['individual']) && $request['individual'] ?  1 : 0;
                $contact->general_mail_out = isset($request['general_mail_out']) && $request['general_mail_out'] ?  1 : 0;
                $contact->trading = $request['trading'];
                $contact->trust_name = $request['trust_name'];
                $contact->surname = $request['surname'];
                $contact->first_name = $request['first_name'];
                $contact->preferred_name = $request['preferred_name'];
                $contact->middle_name = $request['middle_name'];
                $contact->dob = $request['dob'];
                $contact->role_title = $request['role_title'];
                $contact->role = $request['role'];
                $contact->entity_name = $request['entity_name'];
                $contact->principle_contact = $request['principle_contact'];
                $contact->work_phone = $request['work_phone'];
                $contact->home_phone = $request['home_phone'];
                $contact->mobile_phone = $request['mobile_phone'];
                $contact->fax = $request['fax'];
                $contact->email = $request['email'];
                $contact->web = $request['web'];
                $contact->street_number = $request['street_number'];
                $contact->street_name = $request['street_name'];
                $contact->street_type = $request['street_type'];
                $contact->suburb = $request['suburb'];
                $contact->city = $request['city'];
                $contact->state = $request['state'];
                $contact->postal_code = $request['postal_code'];
                $contact->postal_street_number = $request['postal_street_number'];
                $contact->postal_street_name = $request['postal_street_name'];
                $contact->postal_street_type = $request['postal_street_type'];
                $contact->postal_suburb = $request['postal_suburb'];
                $contact->same_as_residential = isset($request['same_as_residential']) ? 1 : 0;
                $contact->mail_city = $request['mail_city'];
                $contact->mail_state = $request['mail_state'];
                $contact->mail_postal_code = $request['mail_postal_code'];
                $contact->client_industry = $request['client_industry'];
                $contact->other_industry = ($request['client_industry'] == 27) ? $request['other_industry'] : '';
                $contact->note = $request['note'];
                $contact->acc_name = $request['acc_name'];
                $contact->acc_no = $request['acc_no'];
                $contact->bank = $request['bank'];
                $contact->bsb = $request['bsb'];
                $contact->abp = $request['abp'];
                $contact->abn = $request['abn'];
                $contact->referrer_type = $request['referrer_type'];
                $contact->referrer_id = ($request['referrer_type'] == 2) ? $request['client_id'] : $request['referrer_id']; //$request['referrer_id'];
                $contact->other_referrer = $request['other_referrer'];
                $contact->social_media_link = $request['social_media_link'];
                $contact->refferor_relation_to_client = $request['refferor_relation_to_client'];
                $contact->refferor_note = $request['refferor_note'];
                $contact->last_updated_by = Auth::user()->id;
                $contact->save();
                if ($contact->id > 0) {
                    
                    
                    if (!empty($request->referrors)) {
                        $tempArr = [];
                        foreach ($request->referrors as $referrors) {
                            //if ($referrors['referrer_id'] > 0 && $referrors['entity'] != '') {
                            if ($referrors['referrer_id'] > 0 ) {
                                $tempArr[] = [
                                    'client_id' => $contact->id,
                                    'referrer_id' => $referrors['referrer_id'],
                                    'upfront' => $referrors['upfront'],
                                    'trail' => $referrors['trail'],
                                    'comm_per_deal' => $referrors['comm_per_deal'],
                                ];
                            }
                        }

                        //echo '<pre>';
                  //  print_R($tempArr);die;
     //echo '</pre>';

                        if (!empty($tempArr)) {
                            ReferrerCommission::insert($tempArr);
                        }
                    }
                }

                if (!empty($request->relationship)) {
                    $tempArr = [];
                    $existingAr = [];
                    foreach ($request->relationship as $relation) {
                        if ($relation['linked_to'] > 0 && $relation['relation'] > 0) {
                            if(isset($relation['old_id']) && $relation['old_id'] > 0){
                                $existingAr[] = $relation['old_id'];
                                ClientRelation::where('id',$relation['old_id'])->where('client_id',$contact->id)
                                    ->update([
                                    'relation_with' => $relation['linked_to'],
                                    'relation'=>$relation['relation']
                                ]);
                            }else{
                                $tempArr[] = [
                                    'client_id' => $contact->id,
                                    'relation_with' => $relation['linked_to'],
                                    'relation'=>$relation['relation']
                                ];
                            }
                        }
                    }
                    $delSql = ClientRelation::where('client_id',$contact->id);
                    if(count($existingAr)>0)
                    {
                        $delSql = $delSql->whereNotIn('id',$existingAr);
                    }
                    $delSql->delete();
                    if (!empty($tempArr)) {

                        ClientRelation::insert($tempArr);
                    }
                }else{
                    ClientRelation::where('client_id',$contact->id)->delete();
                }

                if (!empty($request['client_referrals'])) {

                    $tempArr = [];
                    //print_R($request['client_referrals']);
                    foreach ($request['client_referrals'] as $client_referral) {
                        if(isset($client_referral['old_id']) && $client_referral['old_id'] > 0){
                            $tempdate = str_replace('/', '-', $client_referral['date']);
                            $existingAr[] = $client_referral['old_id'];
                            //print_R($existingAr);
                            ClientReferral::where('id',$client_referral['old_id'])->where('client_id',$contact->id)
                                ->update([
                                    'referred_to' => $client_referral['referred_to'],
                                    'notes' => $client_referral['notes'],
                                    'date' => date('Y-m-d', strtotime($tempdate)),
                                    'service_id' => $client_referral['service_id']
                                ]);
                        }else {
                            if ($client_referral['referred_to'] != '' && $client_referral['service_id'] > 0) {
                                $tempdate = str_replace('/', '-', $client_referral['date']);
                                $tempArr[] = [
                                    'client_id' => $contact->id,
                                    'referred_to' => $client_referral['referred_to'],
                                    'notes' => $client_referral['notes'],
                                    'date' => date('Y-m-d', strtotime($tempdate)),
                                    'service_id' => $client_referral['service_id']
                                ];
                            }
                        }
                    }
                    $delSql = ClientReferral::where('client_id',$contact->id);
                    if(count($existingAr)>0)
                    {
                        $delSql = $delSql->whereNotIn('id',$existingAr);
                    }
                    $delSql->delete();
                    if (!empty($tempArr)) {

                        ClientReferral::insert($tempArr);
                    }
                }else{
                    ClientReferral::where('client_id',$contact->id)->delete();
                }

                if (!empty($request->tasks)) {
                    $taskArr = [];
                    $existingTskAr = [];
                    foreach ($request->tasks as $task) {
                       // print_R($task);
                        if($task['followup_date']!= '' && $task['processor']!='' && $task['details'] != '')
                        {
                            
                            $tempdob = str_replace('/','-',$task['followup_date']);
                                $task['followup_date'] = date('Y-m-d',strtotime($tempdob));
                                
                            if(isset($task['old_id']) && $task['old_id'] > 0)
                            {
                                $existingTskAr[] = $task['old_id'];
                                TaskRelation::where('id',$task['old_id'])->where('client_id',$contact->id)
                                              ->update([
                                                  'followup_date' => $task['followup_date'],
                                                  'processor' => $task['processor'],
                                                  'details' => $task['details'],
                                                  //'user' => $task['user'],
                                              ]);
                                              
                            }else {
                                $taskArr = [
                                    'client_id' => $contact->id,
                                    'followup_date' => $task['followup_date'],
                                    'processor' => $task['processor'],
                                    'details' => $task['details'],
                                    //'user' => $task['user'],
                                    'created_at' => Carbon::now('utc')->toDateTimeString(),
                                    'updated_at' => Carbon::now('utc')->toDateTimeString()
                                ];
                            }
                        }

                    }
                    $delTSql = TaskRelation::where('client_id',$contact->id);
                    if(count($existingTskAr)>0)
                    {
                        $delTSql = $delTSql->whereNotIn('id',$existingTskAr);
                    }
                   
                    $delTSql->delete();

                    if (!empty($taskArr)) {
                        //print_R($taskArr);
                        TaskRelation::insert($taskArr);
                    }
                }else{
                    TaskRelation::where('client_id',$contact->id)->delete();
                }


                DB::commit();
                return response()->json(['success'=>'Record updated successfully!','id'=>$id]);
            } else {
                return response()->json(['error'=>"Record not found!"]);
            }
        }catch (\Exception $e)
        {
            DB::rollback();
            return response()->json(['error'=> $e->getMessage()]);
        }
    }

    public function contactDelete($id)
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
        try{
            $input =  $request->all();

            $datatableData = arrangeArrayPair( obj2Arr( $input ), 'name', 'value' );

            $start = 0;
            $rowperpage = 25;
            if ( isset( $datatableData['iDisplayStart'] ) && $datatableData['iDisplayLength'] != '-1' ) {
                $start =  intval( $datatableData['iDisplayStart'] );
                $rowperpage = intval( $datatableData['iDisplayLength'] );
            }

            if($datatableData['type'] == 1 || $datatableData['type'] == 2)
            {
                // Ordering
                $sortableColumnsName = [
                    'contact_searches.id',
                    'contact_searches.surname',
                    'contact_searches.preferred_name',
                    'contact_searches.trading',
                    /*'contact_searches.trust_name',*/

                    /*'
                    'contact_searches.entity_name',
                    'contact_searches.principle_contact',
                    'abp.name',*/
                    'contact_searches.broker',
                    'contact_searches.street_number',
                    'cities.name',
                    /*'contact_searches.postal_code',
                    'contact_searches.work_phone',
                    'contact_searches.home_phone',*/
                    /*'contact_searches.mobile_phone',*/
                    /*'contact_searches.client_industry',*/
                    'contact_searches.created_at',
                    'contact_searches.updated_at',
                    '',
                ];
                $columnName              = "contact_searches.created_at";
                $columnSortOrder              = "DESC";
                if ( isset( $datatableData['iSortCol_0'] ) ) {
                    $sOrder = "ORDER BY  ";
                    for ( $i = 0; $i < intval( $datatableData['iSortingCols'] ); $i ++ ) {
                        if ( (bool) $datatableData[ 'bSortable_' . intval( $datatableData[ 'iSortCol_' . $i ] ) ] == TRUE && isset( $datatableData[ 'sSortDir_' . $i ] ) ) {
                            $columnSortOrder = ( strcasecmp( $datatableData[ 'sSortDir_' . $i ], 'ASC' ) == 0 ) ? 'ASC' : 'DESC';
                            $columnName  = $sortableColumnsName[ intval( $datatableData[ 'iSortCol_' . $i ] ) ];

                        }
                    }

                    if ( $columnName == "" ) {
                        $columnName              = "contact_searches.created_at";
                        $columnSortOrder              = "DESC";
                    }
                }
                $searchValue = '';
                if ( isset( $datatableData['sSearch'] ) && $datatableData['sSearch'] != "" ) {
                    $searchValue = $datatableData['sSearch'];
                }
                // Total records
                $totalRecords = ContactSearch::select(DB::raw('count(*) as allcount'))->whereNull('deleted_at')->count();
                $filterSql = ContactSearch::select(DB::raw('count(*) as allcount'))->whereNull('contact_searches.deleted_at')
                                          ->leftJoin('brokers as abp','abp.id','=','contact_searches.abp')->leftJoin
                    ('states','states.id','=','contact_searches.state')->leftJoin('cities','cities.id','=','contact_searches.city')->leftJoin('industries','industries.id','=','contact_searches.client_industry');
                if(isset($datatableData['type']) && $datatableData['type'] > 0)
                {
                    $filterSql = $filterSql->where('contact_searches.search_for','=',$datatableData['type']);
                }
                if(isset($datatableData['surname']) && $datatableData['surname'] != '')
                {
                    $filterSql = $filterSql->where('contact_searches.surname','=',$datatableData['surname']);
                }
                if(isset($datatableData['given_name']) && $datatableData['given_name'] != '')
                {
                    $filterSql = $filterSql->where('contact_searches.preferred_name','=',$datatableData['given_name']);
                }if(isset($datatableData['entity_name']) && $datatableData['entity_name'] != '')
                {
                    $filterSql = $filterSql->where('contact_searches.entity_name','=',$datatableData['entity_name']);
                }
                if(isset($datatableData['trading']) && $datatableData['trading'] != '')
                {
                    $filterSql = $filterSql->where('contact_searches.trading','=',$datatableData['trading']);
                }
                if(isset($datatableData['state']) && $datatableData['state'] > 0)
                {
                    $filterSql = $filterSql->where('contact_searches.state','=',$datatableData['state']);
                }
                if(isset($datatableData['city']) && $datatableData['city'] > 0)
                {
                    $filterSql = $filterSql->where('contact_searches.city','=',$datatableData['city']);
                }
                if(isset($datatableData['postal_code']) && $datatableData['postal_code'] != '')
                {
                    $filterSql = $filterSql->where('contact_searches.postal_code','=',$datatableData['postal_code']);
                }
                if(isset($datatableData['id']) && $datatableData['id'] != '')
                {
                    $filterSql = $filterSql->where('contact_searches.id','=',$datatableData['id']);
                }if(isset($datatableData['industry']) && $datatableData['industry'] > 0)
            {
                $filterSql = $filterSql->where('contact_searches.client_industry','=',$datatableData['id']);
            }
                if(isset($datatableData['street_number']) && $datatableData['street_number'] != '')
                {
                    $filterSql = $filterSql->where('contact_searches.street_number','=',$datatableData['street_number']);
                }

                if(isset($datatableData['street_name']) && $datatableData['street_name'] != '')
                {
                    $filterSql = $filterSql->where('contact_searches.street_name','=',$datatableData['street_name']);
                }
                if(isset($datatableData['street_type']) && $datatableData['street_type'] != '')
                {
                    $filterSql = $filterSql->where('contact_searches.street_type','=',$datatableData['street_type']);
                }
                if(isset($datatableData['suburb']) && $datatableData['suburb'] != '')
                {
                    $filterSql = $filterSql->where('contact_searches.suburb','=',$datatableData['suburb']);
                }
                if($searchValue!='')
                {
                    $filterSql =$filterSql->where(function ($query) use ($searchValue) {
                        return $query->where('contact_searches.trading', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.trust_name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.surname', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.preferred_name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.entity_name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.principle_contact', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('abp.given_name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('abp.surname', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('abp.entity_name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('states.name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('cities.name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.work_phone', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.home_phone', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.mobile_phone', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('industries.name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.other_industry', 'like',  '%' .$searchValue . '%');
                    });
                }


                $totalRecordswithFilter = $filterSql->count();
                // Fetch records
                $sql = ContactSearch::select(DB::raw('contact_searches.id,(CASE contact_searches.search_for WHEN 1 THEN "Client" WHEN 2 THEN "Referror" END) as type,contact_searches.trading,contact_searches.trust_name,contact_searches.surname,contact_searches.preferred_name,contact_searches.entity_name,contact_searches.principle_contact,CONCAT_WS(" ",abp.surname,abp.given_name) as abp_name,contact_searches.dob,states.name as state_name,cities.name as city_name,contact_searches.postal_code,contact_searches.work_phone,contact_searches.home_phone,contact_searches.mobile_phone, IF(contact_searches.client_industry=27,contact_searches.other_industry,industries.name) as industry_name, DATE_FORMAT(contact_searches.created_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_created_at, DATE_FORMAT(contact_searches.updated_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_updated_at,IF(contact_searches.dob IS NOT NULL,DATE_FORMAT(contact_searches.dob,"'.$this->mysql_date_format.'"),"") as dob, CONCAT(contact_searches.street_number," ",contact_searches.street_name) as address'))->orderBy($columnName,
                    $columnSortOrder)->whereNull('contact_searches.deleted_at')->leftJoin('brokers as abp','abp.id',
                    '=','contact_searches.abp')->leftJoin('states','states.id','=','contact_searches.state')->leftJoin('cities','cities.id','=','contact_searches.city')->leftJoin('industries','industries.id','=','contact_searches.client_industry')

                                    ->skip($start)
                                    ->take($rowperpage);

                if(isset($datatableData['type']) && $datatableData['type'] > 0)
                {
                    $sql = $sql->where('contact_searches.search_for','=',$datatableData['type']);
                }
                if(isset($datatableData['surname']) && $datatableData['surname'] !='')
                {
                    $sql = $sql->where('contact_searches.surname','=',$datatableData['surname']);
                }
                if(isset($datatableData['given_name']) && $datatableData['given_name'] !='')
                {
                    $sql = $sql->where('contact_searches.preferred_name','=',$datatableData['given_name']);

                }if(isset($datatableData['entity_name']) && $datatableData['entity_name'] !='')
                {
                    $sql = $sql->where('contact_searches.entity_name','=',$datatableData['entity_name']);

                }
                if(isset($datatableData['trading']) && $datatableData['trading'] !='')
                {
                    $sql = $sql->where('contact_searches.trading','=',$datatableData['trading']);
                }
                if(isset($datatableData['state']) && $datatableData['state'] > 0)
                {
                    $sql = $sql->where('contact_searches.state','=',$datatableData['state']);
                }
                if(isset($datatableData['city']) && $datatableData['city'] > 0)
                {
                    $sql = $sql->where('contact_searches.city','=',$datatableData['city']);
                }
                if(isset($datatableData['postal_code']) && $datatableData['postal_code'] != '')
                {
                    $sql = $sql->where('contact_searches.postal_code','=',$datatableData['postal_code']);
                }
                if(isset($datatableData['id']) && $datatableData['id'] > 0)
                {
                    $sql = $sql->where('contact_searches.id','=',$datatableData['id']);
                }

                if(isset($datatableData['street_number']) && $datatableData['street_number'] != '')
                {
                    $sql = $sql->where('contact_searches.street_number','=',$datatableData['street_number']);
                }

                if(isset($datatableData['street_name']) && $datatableData['street_name'] != '')
                {
                    $sql = $sql->where('contact_searches.street_name','=',$datatableData['street_name']);
                }
                if(isset($datatableData['street_type']) && $datatableData['street_type'] != '')
                {
                    $sql = $sql->where('contact_searches.street_type','=',$datatableData['street_type']);
                }
                if(isset($datatableData['suburb']) && $datatableData['suburb'] != '')
                {
                    $sql = $sql->where('contact_searches.suburb','=',$datatableData['suburb']);
                }

                if($searchValue!='')
                {

                    $sql =$sql->where(function ($query) use ($searchValue) {
                        return $query->where('contact_searches.trading', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.trust_name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.surname', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.preferred_name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.entity_name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.principle_contact', 'like',  '%' .$searchValue . '%')
                                    //  ->orWhere('abp.name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('states.name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('cities.name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.work_phone', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.home_phone', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.mobile_phone', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('industries.name', 'like',  '%' .$searchValue . '%')
                                     ->orWhere('contact_searches.other_industry', 'like',  '%' .$searchValue . '%');
                    });
                }


                $records = $sql->get();

                if(count($records) >0)
                {
                    foreach($records as $rkey => $record)
                    {
                        $records[$rkey]['encrypt_id'] = encrypt($record->id);
                    }
                }
            }else{
                $sortableColumnsName = [
                    'brokers.id',
//                    'brokers.type',
                    'brokers.surname',
                    'brokers.entity_name',
                    'brokers.trading',

                    'parent_brokers.entity_name',
                    'brokers.business',
                    /*'states.name',*/
                    'cities.name',
                    /*'brokers.mobile_phone',*/
                    'brokers.created_at',
                    'brokers.updated_at',
                    '',
                ];
                $columnName              = "brokers.created_at";
                $columnSortOrder              = "DESC";
                if (isset($datatableData['iSortCol_0'])) {
                    $sOrder = "ORDER BY  ";
                    for ($i = 0; $i < intval($datatableData['iSortingCols']); $i++) {
                        if ((bool) $datatableData['bSortable_' . intval($datatableData['iSortCol_' . $i])] == TRUE && isset($datatableData['sSortDir_' . $i])) {
                            $columnSortOrder = (strcasecmp($datatableData['sSortDir_' . $i], 'ASC') == 0) ? 'ASC' : 'DESC';
                            $columnName  = $sortableColumnsName[intval($datatableData['iSortCol_' . $i])];
                        }
                    }

                    if ($columnName == "") {
                        $columnName              = "brokers.created_at";
                        $columnSortOrder              = "DESC";
                    }
                }
                $searchValue = '';
                if (isset($datatableData['sSearch']) && $datatableData['sSearch'] != "") {
                    $searchValue = $datatableData['sSearch'];
                }
                // Total records
                $totalRecords = Broker::select(DB::raw('count(*) as allcount'))->whereNull('deleted_at')->count();
                $filterSql = Broker::select(DB::raw('count(*) as allcount'))->whereNull('brokers.deleted_at')
                                   ->leftJoin('states', 'states.id', '=', 'brokers.state')->leftJoin('cities', 'cities.id', '=', 'brokers.city')->leftJoin('brokers as parent_brokers','parent_brokers.id','=','brokers.parent_broker');
                if (isset($datatableData['type']) && $datatableData['type'] > 0) {

                    if($datatableData['type'] == 3)
                    {
                        $filterSql = $filterSql->where('brokers.parent_broker', '=', 0);
                    }else{
                        $filterSql = $filterSql->where('brokers.parent_broker', '>', 0);
                    }
                }
                if (isset($datatableData['surname']) && $datatableData['surname'] !='') {
                    $filterSql = $filterSql->where('brokers.surname', '=', $datatableData['surname']);
                }
                if (isset($datatableData['given_name']) && $datatableData['given_name'] !='') {
                    $filterSql = $filterSql->where('brokers.given_name', '=', $datatableData['given_name']);
                }if (isset($datatableData['entity_name']) && $datatableData['entity_name'] !='') {
                    $filterSql = $filterSql->where('brokers.entity_name', '=', $datatableData['entity_name']);
                }
                if (isset($datatableData['trading']) && $datatableData['trading'] !='') {
                    $filterSql = $filterSql->where('brokers.trading', '=', $datatableData['trading']);
                }
                if (isset($datatableData['state']) && $datatableData['state'] !='') {
                    $filterSql = $filterSql->where('brokers.state', '=', $datatableData['state']);
                }
                if (isset($datatableData['city']) && $datatableData['city'] !='') {
                    $filterSql = $filterSql->where('brokers.city', '=', $datatableData['city']);
                }
                if (isset($datatableData['postal_code']) && $datatableData['postal_code'] !='') {
                    $filterSql = $filterSql->where('brokers.pincode', '=', $datatableData['postal_code']);
                }
                if (isset($datatableData['id']) && $datatableData['id'] > 0) {
                    $filterSql = $filterSql->where('brokers.id', '=', $datatableData['id']);
                }


                if ($searchValue != '') {
                    $filterSql = $filterSql->where(function ($query) use ($searchValue) {
                        return $query->where('brokers.trading', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('brokers.trust_name', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('brokers.surname', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('brokers.given_name', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('states.name', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('cities.name', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('brokers.work_phone', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('brokers.home_phone', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('brokers.mobile_phone', 'like',  '%' . $searchValue . '%');
                    });
                }


                $totalRecordswithFilter = $filterSql->count();
                // Fetch records
                $sql = Broker::select(DB::raw('brokers.id,broker_types.name as type,brokers.trading,brokers.trust_name,brokers.surname,brokers.given_name as preferred_name,DATE_FORMAT(brokers.dob,"'.$this->mysql_date_format.'") as dob,states.name as state_name,cities.name as city_name,brokers.pincode,brokers.work_phone,brokers.home_phone,brokers.mobile_phone, DATE_FORMAT(brokers.created_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_created_at, DATE_FORMAT(brokers.updated_at,"'.$this->mysql_date_format.' %H:%i:%s") as formated_updated_at, brokers.business as address,parent_brokers.entity_name as abp_name'))->orderBy(
                    $columnName,
                    $columnSortOrder
                )->whereNull('brokers.deleted_at')->leftJoin('broker_types', 'broker_types.id', '=', 'brokers.type')
                                                  ->leftJoin('states', 'states.id', '=', 'brokers.state')->leftJoin('cities', 'cities.id', '=', 'brokers.city')->leftJoin('brokers as parent_brokers','parent_brokers.id','=','brokers.parent_broker')

                             ->skip($start)
                             ->take($rowperpage);
                if (isset($datatableData['type']) && $datatableData['type'] > 0) {
                    if($datatableData['type'] == 3)
                    {
                        $sql = $sql->where('brokers.parent_broker', '=', 0);
                    }else{
                        $sql = $sql->where('brokers.parent_broker', '>', 0);
                    }
                    //$sql = $sql->where('brokers.type', '=', $datatableData['type']);
                }
                if (isset($datatableData['surname']) && $datatableData['surname'] !='') {
                    $sql = $sql->where('brokers.surname', '=', $datatableData['surname']);
                }
                if (isset($datatableData['given_name']) && $datatableData['given_name'] !='') {
                    $sql = $sql->where('brokers.given_name', '=', $datatableData['given_name']);
                }if (isset($datatableData['entity_name']) && $datatableData['entity_name'] !='') {
                    $sql = $sql->where('brokers.entity_name', '=', $datatableData['entity_name']);
                }
                if (isset($datatableData['trading']) && $datatableData['trading'] !='') {
                    $sql = $sql->where('brokers.trading', '=', $datatableData['trading']);
                }
                if (isset($datatableData['state']) && $datatableData['state'] !='') {
                    $sql = $sql->where('brokers.state', '=', $datatableData['state']);
                }
                if (isset($datatableData['city']) && $datatableData['city'] !='') {
                    $sql = $sql->where('brokers.city', '=', $datatableData['city']);
                }
                if (isset($datatableData['postal_code']) && $datatableData['postal_code'] !='') {
                    $sql = $sql->where('brokers.pincode', '=', $datatableData['postal_code']);
                }
                if (isset($datatableData['id']) && $datatableData['id'] > 0) {
                    $sql = $sql->where('brokers.id', '=', $datatableData['id']);
                }
                if ($searchValue != '') {
                    $sql = $sql->where(function ($query) use ($searchValue) {
                        return $query->where('brokers.trading', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('brokers.trust_name', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('brokers.surname', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('brokers.given_name', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('states.name', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('cities.name', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('brokers.work_phone', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('brokers.home_phone', 'like',  '%' . $searchValue . '%')
                                     ->orWhere('brokers.mobile_phone', 'like',  '%' . $searchValue . '%');
                    });
                }

                $records = $sql->get();

                if (count($records) > 0) {
                    foreach ($records as $rkey => $record) {
                        $records[$rkey]['encrypt_id'] = encrypt($record->id);
                    }
                }
            }

            $response = array(
                "sEcho" => intval( ( isset( $datatableData['sEcho'] ) ? $datatableData['sEcho'] : 0 ) ),
                "draw" => intval( ( isset( $datatableData['sEcho'] ) ? $datatableData['sEcho'] : 0 ) ),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $records
            );

            return response()->json(['success'=>true,'payload'=>$response]);
        } catch (\Exception $e)
        {
            return response()->json(['error'=>$e->getMessage()]);
        }
    }

    public function getSgDCommissions(Request $request)
    {
        try {
            $input =  $request->all();

            if ($request->ajax()) {

                $contact = ContactSearch::find(decrypt($request->deal_id));
                if(!$contact)
                {
                    return response()->json(['error' => 'Invalid Contact!']);
                }
                // $data = CommissionData::select(DB::raw("commissions_data.id, date_format(commissions_data.settlement_date,'".$this->mysql_date_format."') as settlement_date,commission_types.name as commission_type, CONCAT(contact_searches.surname,',',contact_searches.preferred_name) as client,commissions_data.account_number as account_no,commissions_data.period,commissions_data.commission,commissions_data.gst,commissions_data.total_paid,commissions_data.payment_no") )->leftJoin('commission_types','commission_types.id','=','commissions_data.commission_type')->leftJoin('contact_searches','contact_searches.id','=','commissions_data.contact_id')->where('contact_id',$contact->id);


                $data = CommissionData::select(DB::raw("commissions_data.id, date_format(commissions_data.settlement_date,'".$this->mysql_date_format."') as settlement_date,commission_types.name as commission_type,commissions_data.period,commissions_data.commission,commissions_data.gst,commissions_data.total_paid,commissions_data.payment_no") )->leftJoin('commission_types','commission_types.id','=','commissions_data.commission_type');

                return DataTables::of($data)
                                 ->addIndexColumn()
                                 ->filterColumn('account_no', function($query, $keyword) {
                                     $sql = "commissions_data.account_number  like ?";
                                     $query->whereRaw($sql, ["%{$keyword}%"]);
                                 })
                                 ->make(true);
            }

            // return response()->json(['success' => true, 'payload' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getCommission1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'referror_split_referror' => "required|exists:contact_searches,id",
            'product_id' => "required|exists:products,id",
        ]);



        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

      $commissions =   RefferorCommissionSchedule::where('refferor_id',$request->referror_split_referror)->where('product_id',
        $request->product_id)
            ->join('commission_types as ct','ct.id','=','refferor_product_commission_schedule.commission_type_id')
            ->whereIn('ct.name',['Upfront','Trail','Brokerage'])->get();

        $upfront = 0;
        $brokrage = 0;
        $trail = 0;
        if($commissions)
        {
            foreach($commissions as $commission)
            {
                if($commission->name == 'Upfront')
                {
                    $upfront = $commission->per_rate;
                }else if($commission->name == 'Trail')
                {
                    $trail = $commission->per_rate;
                }else if($commission->name == 'Brokerage')
                {
                    $brokrage = $commission->per_rate;
                }
            }
        }

        return response()->json(['success'=>'success','model'=>['upfront'=>$upfront,'trail'=>$trail,'brokrage'=>$brokrage]]);
    }

    public function getCommission(Request $request)
    {
        /*$validated = $request->validate([
            'broker_id' => 'required|exists:brokers,id',
            'lender_id' => 'required|exists:lenders,id',

        ], [], [
            'broker_id' => 'Broker',
            'lender_id' => 'Lender'
        ]);*/

        $validator = Validator::make($request->all(), [
            'referror_split_referror' => "required|exists:contact_searches,id",
            //'product_id' => "required|exists:products,id",
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        //$commissionModel = ReferrerCommissionModelinstitute::where('referrer_id',$request->referror_split_referror)->where('lender_id', $request->product_id)->first();

            $commissionModel = ReferrerCommissionModelinstitute::where('referrer_id',$request->referror_split_referror)->where('lender_id',1)->first();

        if(!$commissionModel)
        {
            return response()->json(['status' => 'success','model'=> []]);
        }

        $BrokerCom = ReferrerCommissionModel::find($commissionModel->referrer_com_mo_inst_id);

        $commission_model = 0;
        $fee_per_deal = 0;

        if($BrokerCom)
        {
            $commission_model = $BrokerCom->commission_model_id;
            $fee_per_deal = $BrokerCom->flat_fee_chrg;
        }


    return    response()->json(['status' => 'success','model'=> ['commission_model' => $commission_model,'fee_per_deal' => $fee_per_deal,'upfront_per' =>
            $commissionModel->upfront,'trail' => $commissionModel->trail]]);
    }
}
