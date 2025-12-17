# Healthcare Appointment Booking API

A simple **Healthcare Appointment Booking System** built with **Laravel 12** and **Sanctum authentication**, with API endpoints for managing doctors, patients, and appointments.

---

## Table of Contents

* [Setup Instructions](#setup-instructions)
* [Authentication](#authentication)
* [API Endpoints](#api-endpoints)
* [Demo Frontend](#demo-frontend)
* [Notes](#notes)

---

## Setup Instructions

1. Clone the repository:

```bash
git clone <your-repo-url>
cd healthcare-api
```

2. Install dependencies:

```bash
composer install
```

3. Copy `.env.example` to `.env` and configure your database:

```bash
cp .env.example .env
php artisan key:generate
```

4. Run migrations and seeders:

```bash
php artisan migrate --seed
```

5. Start the development server:

```bash
php artisan serve
```

Your API is now available at: `http://127.0.0.1:8000`

---

## Authentication

* **Login Endpoint:** `POST /api/login`
* Request:

```json
{
  "email": "admin@test.com",
  "password": "password"
}
```

* Response:

```json
{
  "token": "YOUR_SANCTUM_TOKEN"
}
```

* Use the token in **Authorization header** for all protected endpoints:

```
Authorization: Bearer YOUR_SANCTUM_TOKEN
```

---

## API Endpoints

### Login

* **POST** `/api/login`
* Body:

```json
{
  "email": "admin@test.com",
  "password": "password"
}
```

* Response:

```json
{
  "token": "YOUR_SANCTUM_TOKEN"
}
```

---

### Book Appointment

* **POST** `/api/appointments`
* Headers: `Authorization: Bearer <token>`
* Body:

```json
{
  "doctor_id": 1,
  "patient_id": 2,
  "appointment_date": "2025-12-20 14:00:00",
  "notes": "Routine checkup"
}
```

* Response:

```json
{
  "data": {
    "id": 1,
    "doctor": { "id": 1, "name": "Dr. John", "specialization": "Cardiology" },
    "patient": { "id": 2, "name": "Jane Doe", "email": "jane@test.com" },
    "appointment_date": "2025-12-20 14:00:00",
    "status": "pending",
    "notes": "Routine checkup"
  }
}
```

---

### Get Appointment

* **GET** `/api/appointments/{id}`
* Headers: `Authorization: Bearer <token>`
* Response:

```json
{
  "data": {
    "id": 1,
    "doctor": { "id": 1, "name": "Dr. John" },
    "patient": { "id": 2, "name": "Jane Doe" },
    "appointment_date": "2025-12-20 14:00:00",
    "status": "pending",
    "notes": "Routine checkup"
  }
}
```

---

### Update Appointment

* **PUT** `/api/appointments/{id}`
* Headers: `Authorization: Bearer <token>`
* Body:

```json
{
  "status": "confirmed",
  "notes": "Patient confirmed"
}
```

* Response:

```json
{
  "data": {
    "id": 1,
    "status": "confirmed",
    "notes": "Patient confirmed"
  }
}
```

---

### Cancel Appointment

* **DELETE** `/api/appointments/{id}`
* Headers: `Authorization: Bearer <token>`
* Response:

```json
{
  "message": "Appointment cancelled"
}
```

> ⚠️ Appointments can only be cancelled at least **24 hours before** the scheduled time.

---

### Available Doctors

* **GET** `/api/doctors/available?specialization=Cardiology&sort_by=name&order=asc`
* Headers: `Authorization: Bearer <token>`
* Response:

```json
{
  "data": [
    { "id": 1, "name": "Dr. John", "specialization": "Cardiology" },
    { "id": 2, "name": "Dr. Smith", "specialization": "Cardiology" }
  ]
}
```

---

### Patient Appointment History

* **GET** `/api/patients/{id}/appointments`
* Headers: `Authorization: Bearer <token>`
* Response:

```json
{
  "data": [
    {
      "id": 1,
      "doctor": { "id": 1, "name": "Dr. John" },
      "appointment_date": "2025-12-20 14:00:00",
      "status": "pending"
    },
    {
      "id": 2,
      "doctor": { "id": 2, "name": "Dr. Smith" },
      "appointment_date": "2025-12-25 10:00:00",
      "status": "confirmed"
    }
  ]
}
```

---

## Demo Frontend

A simple HTML page is included at `public/demo.html`:

* Login and obtain token
* Fetch available doctors
* Display API responses
* Handle errors gracefully

Open in browser:

```
http://127.0.0.1:8000/demo.html
```

---

## Notes

* All date/time values use `YYYY-MM-DD HH:MM:SS` format
* Authentication required for all endpoints except `/api/login`
* Appointments cannot be double-booked for the same doctor/time slot
* Email notifications are sent when appointment is confirmed (using log driver by default)

---

## Default Admin User (for testing)

| Email                                   | Password |
| --------------------------------------- | -------- |
| [admin@test.com](mailto:admin@test.com) | password |

```
```
