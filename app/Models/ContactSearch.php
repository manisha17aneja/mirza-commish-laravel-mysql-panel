<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ContactSearch extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $hidden = [
        'deleted_at','deleted_by','last_updated_by','created_by'
    ];

    public function relationDetails()
    {
        return $this->belongsToMany('App\Models\ContactSearch','client_relations','client_id','relation_with')->join('relationship as client_relationships','client_relations.relation','=','client_relationships.id')->select(DB::raw('contact_searches.trading,contact_searches.trust_name,contact_searches.surname,contact_searches.first_name,contact_searches.preferred_name,contact_searches.middle_name,contact_searches.role_title,contact_searches.entity_name,client_relationships.name as client_relation_label,client_relations.mailout,IF(client_relations.mailout =1,"Yes","No") as mailout_display,contact_searches.id as client_relation_id'));
    }

    public function relations()
    {
        return $this->hasMany('App\Models\ClientRelation','client_id');
    }

    public function tasks()
    {
        $mysql_format = config('app.mysql_date_format');
        return $this->hasMany('App\Models\TaskRelation','client_id')->select(DB::raw('client_tasks.*,IF(client_tasks.followup_date IS NOT NULL,DATE_FORMAT(client_tasks.followup_date,"'.$mysql_format.'"),"") as followup_date,processors.name as processor'))->leftJoin('processors','processors.id','=','client_tasks.processor');
    }
    public function mail_state_table(){
        return $this->belongsTo(State::class,'mail_state');
    }
}
