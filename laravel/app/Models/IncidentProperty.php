<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentProperty extends Model
{
    use HasFactory;

    protected $fillable = ['rp_id','polyline','start','end','hm'];

    public function roadProperty()
    {
        $this->belongsTo(RoadProperty::class,'rp_id');
    }

    public function incidentBound()
    {
        $this->hasMany(IncidentBound::class,'ip_id');
    }
}
