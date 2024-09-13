<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'description', 'image'];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
    public function visaTypes()
    {
        return $this->belongsToMany(VisaType::class, 'visa_types_destinations');
    }
}
