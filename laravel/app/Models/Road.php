<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Road extends Model
{
    use HasFactory;

    protected $fillable = ['road','type'];

    public function roadProperties()
    {
        return $this->hasMany(RoadProperty::class);
    }
}
