<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $fillable = [
        'visa_type_id',
        'destination_id',
        'applicant_name',
        'email',
        'passport_file',
        'photo_file',
        'additional_info',
        'phone_number',
        'status',
        'payment_id' 
    ];

    protected $casts = [
        'document_data' => 'array',
    ];

    public function visaType()
    {
        return $this->belongsTo(VisaType::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
