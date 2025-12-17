<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['name','specialization','availability_schedule'];

    protected $casts = [
        'availability_schedule' => 'array'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
