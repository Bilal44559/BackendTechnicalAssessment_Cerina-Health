<h2>Appointment Confirmation</h2>

<p><strong>Event:</strong> {{ $eventType }}</p>

<p>Hello {{ $appointment->patient->name }},</p>

<p>Your appointment has been <strong>confirmed</strong>.</p>

<p><strong>Doctor:</strong> {{ $appointment->doctor->name }}</p>
<p><strong>Date:</strong> {{ $appointment->appointment_date }}</p>

<p>Thank you!</p>
