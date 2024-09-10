<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaType extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'visa_types_destinations');
    }
}
