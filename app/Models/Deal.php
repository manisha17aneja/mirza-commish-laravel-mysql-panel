<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Deal extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $hidden = [
        'deleted_at','deleted_by','updated_by','created_by'
    ];
    public function relations(){
        return $this->hasMany('App\Models\DealClient','deal_id');
    }
    public function relationDetails()
    {
        return $this->belongsToMany('App\Models\ContactSearch','deal_clients_rel','deal_id','client_id')->join('relationship as client_relationships','deal_clients_rel.type','=','client_relationships.id')->select(DB::raw('contact_searches.id, contact_searches.trading,contact_searches.trust_name,contact_searches.surname,contact_searches.first_name,contact_searches.preferred_name,contact_searches.middle_name,contact_searches.role_title,contact_searches.entity_name,client_relationships.name as client_relation_label'));
    }
    public function broker(){
        return $this->belongsTo('App\Models\Broker');
    }
    public function deal_commission(){
        return $this->hasMany(DealCommission::class, 'deal_id', 'id');
    }
    public function commission_data(){
        return $this->hasMany(CommissionData::class);
    }
    public function tasks()
    {
        return $this->hasMany('App\Models\DealTask','deal_id');
    }
    public function broker_staff(){
        return $this->belongsTo(Broker::class,'broker_staff_id');
    }
    public function deal_clients(){
        return $this->belongsToMany('App\Models\ContactSearch','deal_clients_rel','deal_id','client_id')->join('deal_client_types as client_relationships','deal_clients_rel.type','=','client_relationships.id')->select(DB::raw('contact_searches.trading,contact_searches.trust_name,contact_searches.surname,contact_searches.first_name,contact_searches.preferred_name,contact_searches.middle_name,contact_searches.role_title,contact_searches.entity_name,client_relationships.name as client_relation_label'));
    }
    public function lender(){
        return $this->belongsTo(Lenders::class,'lender_id');
    }
    public function deal_status(){
        return $this->belongsTo(DealStatus::class,'status','id');
    }
    public function deal_status_update(){
        return $this->hasOne(DealStatusUpdate::class,'id','deal_id');
    }
    public function product(){
        return $this->belongsTo(Products::class,'product_id');
    }
    public function client(){
        return $this->belongsTo(ContactSearch::class,'contact_id');
    }
    public function referrer(){
        return $this->belongsTo(ContactSearch::class,'referror_split_referror');
    }
    public function scopeGetAIPExportRecords($q,$group_by='',$date_from='',$date_to=''){
        $q->select('deals.*')->with(['lender','deal_status','client','product'])
            ->whereIn('status',[11])->orderBy('created_at');
        if($group_by!=''){
            if($group_by=='lender'){
                $q->groupBy('lender_id');
            }if($group_by=='broker_staff'){
                $q->groupBy('broker_staff_id');
            }
            if($group_by=='client'){
                $q->groupBy('contact_id');
            }

        }
        if($date_from!=''){
            $q->where('created_at','>=', date('Y-m-d H:i:s',strtotime($date_from.' 00:00:00')));
        }if($this->date_to!=''){
            $q->where('created_at','<=', date('Y-m-d H:i:s',strtotime($date_to.' 23:59:59')));
        }
        return $q;
    }
}
