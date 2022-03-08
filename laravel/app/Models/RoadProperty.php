<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadProperty extends Model
{
    use HasFactory;

    protected $fillable = ['road_id','segment_id','code_direction','afrc','category','label','incident_type','from','to','reason','start','end'];

    public function road()
    {
        $this->belongsTo(Road::class);
    }

    public function roadEvent()
    {
        $this->hasMany(RoadEvent::class, 'rp_id');
    }

    public function incidentProperty()
    {
        $this->hasOne(IncidentProperty::class,'rp_id');
    }

}
