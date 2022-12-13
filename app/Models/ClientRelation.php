<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientRelation extends Model
{
    protected $table = 'client_relations';
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    use HasFactory;

    public function relationLabel()
    {
        return $this->belongsTo('App\Models\Relationship','relation');
    }
}
