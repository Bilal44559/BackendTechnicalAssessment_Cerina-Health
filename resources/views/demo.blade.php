<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Healthcare API Demo</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f7fa;
      margin: 0;
      padding: 20px;
    }
    h1, h2 { color: #333; }
    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 20px;
      margin-bottom: 20px;
    }
    input, select, button {
      padding: 10px;
      margin: 5px 0 !important;
      border-radius: 6px;
      border: 1px solid #ccc;
      width: 100%;
      box-sizing: border-box;
    }
    button {
      cursor: pointer;
      background: #4CAF50;
      color: white;
      border: none;
      transition: 0.3s;
    }
    button:hover {
      background: #45a049;
    }
    pre {
      background: #272822;
      color: #f8f8f2;
      padding: 15px;
      border-radius: 8px;
      max-height: 300px;
      overflow-y: auto;
    }
    .flex {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }
    .flex input, .flex select {
      flex: 1;
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <div id="alertWrapper"
     style="position: fixed; top: 20px; right: 20px; z-index: 9999; width: 300px;">
</div>


<h1>Healthcare API Demo</h1>

<div class="card">
  <h2>Login</h2>
  <input id="email" placeholder="Email" value="admin@test.com">
  <input id="password" type="password" placeholder="Password" value="password">
  <button onclick="login()">Login</button>
</div>

<div class="card">
  <h2>Book Appointment</h2>
  <div class="flex">
    <select id="bookDoctorId">
      <option value="">Select Doctor</option>
    </select>
    <input id="bookPatientId" placeholder="Patient ID" value="1">
  </div>
  <input id="bookDate" placeholder="Appointment Date (YYYY-MM-DD HH:MM:SS)" value="2025-12-20 14:00:00">
  <input id="bookNotes" placeholder="Notes" value="Routine checkup">
  <button onclick="bookAppointment()">Book Appointment</button>
</div>

<div class="card">
  <h2>Update Appointment</h2>
  <input id="updateAppointmentId" placeholder="Appointment ID" value="1">
  <input id="updateStatus" placeholder="Status (pending, confirmed, completed, cancelled)" value="confirmed">
  <input id="updateNotes" placeholder="Notes" value="Updated notes">
  <button onclick="updateAppointment()">Update Appointment</button>
</div>

<div class="card">
  <h2>Cancel Appointment</h2>
  <input id="cancelAppointmentId" placeholder="Appointment ID" value="1">
  <button onclick="cancelAppointment()">Cancel Appointment</button>
</div>

<div class="card">
  <h2>Patient Appointment History</h2>
  <input id="historyPatientId" placeholder="Patient ID" value="1">
  <button onclick="getPatientHistory()">Get History</button>
</div>

<div class="card">
  <h2>API Response</h2>
  <pre id="response"></pre>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
  function showAlert(type, message, duration = 4000) {
    const wrapper = document.getElementById('alertWrapper');

    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show shadow`;
    alert.style.marginBottom = '10px';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    wrapper.appendChild(alert);

    // Auto-close after X ms
    setTimeout(() => {
        alert.classList.remove('show');
        setTimeout(() => alert.remove(), 200);
    }, duration);
}


let token = '';
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

async function login() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    try {
        const res = await fetch('http://127.0.0.1:8000/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });

        const data = await res.json();
        document.getElementById('response').textContent = JSON.stringify(data, null, 2);

        if (res.ok) {
            token = data.token;
            showAlert('success', 'Login successful!');
            loadDoctors();
        } else {
            showAlert('danger', data.message || 'Login failed.');
        }

    } catch (e) {
        showAlert('danger', 'Network error occurred!');
    }
}



// Load doctors dynamically into dropdown
async function loadDoctors() {
    if(!token) return;
    try {
        const res = await fetch('http://127.0.0.1:8000/api/doctors/available', {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const data = await res.json();
        const select = document.getElementById('bookDoctorId');
        select.innerHTML = '<option value="">Select Doctor</option>';
        data.data.forEach(doc => {
            const option = document.createElement('option');
            option.value = doc.id;
            option.textContent = `${doc.name} (${doc.specialization})`;
            select.appendChild(option);
        });
    } catch(e) {
        console.error(e);
    }
}

// Book Appointment
async function bookAppointment() {
    if (!token) return showAlert('danger', 'Please login first!');

    const body = {
        doctor_id: document.getElementById('bookDoctorId').value,
        patient_id: document.getElementById('bookPatientId').value,
        appointment_date: document.getElementById('bookDate').value,
        notes: document.getElementById('bookNotes').value
    };

    const res = await fetch('http://127.0.0.1:8000/api/appointments', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(body)
    });

    const data = await res.json();
    document.getElementById('response').textContent = JSON.stringify(data, null, 2);

    if (res.ok) {
        showAlert('success', 'Appointment booked successfully!');
    } else {
        showAlert('danger', data.message || 'Failed to book appointment.');
    }
}


// Update Appointment
async function updateAppointment() {
    if (!token) return showAlert('danger', 'Please login first!');
    const id = document.getElementById('updateAppointmentId').value;

    const res = await fetch(`http://127.0.0.1:8000/api/appointments/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({
            status: document.getElementById('updateStatus').value,
            notes: document.getElementById('updateNotes').value
        })
    });

    const data = await res.json();
    document.getElementById('response').textContent = JSON.stringify(data, null, 2);

    if (res.ok) {
        showAlert('success', 'Appointment updated!');
    } else {
        showAlert('danger', data.message || 'Update failed.');
    }
}


// Cancel Appointment
async function cancelAppointment() {
    if (!token) return showAlert('danger', 'Please login first!');
    const id = document.getElementById('cancelAppointmentId').value;

    const res = await fetch(`http://127.0.0.1:8000/api/appointments/${id}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${token}` }
    });

    const data = await res.json();
    document.getElementById('response').textContent = JSON.stringify(data, null, 2);

    if (res.ok) {
        showAlert('warning', 'Appointment cancelled.');
    } else {
        showAlert('danger', data.message || 'Cancellation failed.');
    }
}


// Patient Appointment History
async function getPatientHistory() {
    if (!token) return showAlert('danger', 'Please login first!');
    const id = document.getElementById('historyPatientId').value;

    const res = await fetch(`http://127.0.0.1:8000/api/patients/${id}/appointments`, {
        headers: { 'Authorization': `Bearer ${token}` }
    });

    const data = await res.json();
    document.getElementById('response').textContent = JSON.stringify(data, null, 2);

    if (res.ok) {
        showAlert('info', 'History loaded.');
    } else {
        showAlert('danger', data.message || 'Could not load history.');
    }
}

</script>

</body>
</html>
