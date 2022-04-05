<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentBound extends Model
{
    use HasFactory;

    protected $fillable = ['ip_id','key','lat_lon'];

    public function incidentProperty()
    {
        $this->belongsTo(IncidentProperty::class,'ip_id');
    }
}
