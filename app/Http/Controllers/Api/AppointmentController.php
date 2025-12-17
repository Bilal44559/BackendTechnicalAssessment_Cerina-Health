<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date|after:now',
            'notes' => 'nullable|string'
        ]);

        $exists = Appointment::where('doctor_id',$data['doctor_id'])
            ->where('appointment_date',$data['appointment_date'])
            ->exists();

        if ($exists) {
            return response()->json(['message'=>'Doctor already booked'], 422);
        }

        $appointment = Appointment::create($data);
        return new AppointmentResource($appointment);
    }

    public function show($id)
    {
        return new AppointmentResource(
            Appointment::with(['doctor','patient'])->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $data = $request->validate([
            'status' => 'in:pending,confirmed,completed,cancelled',
            'appointment_date' => 'date|after:now',
            'notes' => 'nullable'
        ]);

        $appointment->update($data);
        return new AppointmentResource($appointment);
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        if (now()->diffInHours($appointment->appointment_date) < 24) {
            return response()->json([
                'message'=>'Cannot cancel within 24 hours'
            ],403);
        }

        $appointment->update(['status'=>'cancelled']);
        return response()->json(['message'=>'Appointment cancelled']);
    }
}
