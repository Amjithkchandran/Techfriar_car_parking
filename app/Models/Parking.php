<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    use HasFactory;

    protected $table  = "parkings";
    protected $primayKey = 'id';
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = [
        'id','appointment_number','customer_name','driving_licence','vehicle_number','start_date',
        'end_date','slot','parking_fee'
    ];
}
