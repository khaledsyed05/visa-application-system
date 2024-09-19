<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaFieldRequirement extends Model
{
    use HasFactory;
    protected $fillable = ['field_name', 'is_required', 'visa_type_id', 'destination_id'];

    public function visaType()
    {
        return $this->belongsTo(VisaType::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
