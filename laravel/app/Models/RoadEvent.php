<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadEvent extends Model
{
    use HasFactory;

    protected $fillable = ['rp_id','alert_c','text'];

    public function roadProperty()
    {
        $this->belongsTo(RoadProperty::class,'rp_id');
    }
}
