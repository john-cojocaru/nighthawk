# Nighthawk Smart Solutions - Web Platform

This is a full-featured web application for managing client services, bookings, and internal messaging, built for **Nighthawk Smart Solutions**. The system supports both **client-side interaction** and an **admin dashboard** for managing operations.

---

## 🛠 Technologies Used

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP 8.x
- **Database:** MySQL (MariaDB) via phpMyAdmin
- **Local Server:** XAMPP

---

## 📁 Project Structure

nighthawk/
├── booking.php # Client booking page
├── contact.php # Contact/message page
├── index.php # Homepage
├── login.html / register.html
├── admin_dashboard.php # Admin main panel
├── admin_manage_services.php
├── admin_manage_bookings.php
├── backend/
│ ├── db.php
│ ├── user_register.php / user_login.php
│ ├── admin_add_service.php / admin_edit_service.php / admin_delete_service.php
│ ├── booking_handler.php
│ ├── message_reply.php
├── img/ # Service & logo images
├── css/
│ └── style.css
└── sql/
└── nighthawk_db.sql # Full DB schema and sample data

pgsql
Copy
Edit

---

## ✅ Features

### 🔐 Authentication
- Secure client & admin login/register system
- Role-based access control

### 🧰 Services Management
- Admin can add, edit, delete services via `admin_manage_services.php`
- All services are displayed dynamically on the homepage and booking forms

### 📆 Booking System
- Clients can book appointments from available services
- Admin can view, confirm, cancel, or delete bookings
- Admin panel styled identically to client-facing version

### ✉️ Messaging System
- Logged-in users can contact admin
- Admin and users can reply in threads (threaded mailbox UI)
- Messages filtered by Unread / Sent / All
- Status indicators: unread → read when opened

---

## 🔧 Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/john-cojocaru/nighthawk.git
Import the Database

Open phpMyAdmin

Create a database named nighthawk_db

Import the nighthawk_db.sql file

Run the Server

Start XAMPP (Apache + MySQL)

Place the project folder inside htdocs/

Visit http://localhost/nighthawk

Admin Credentials

Email: admin@nighthawk.com

Password: (refer to your stored hash or set a new one)

📸 Screenshots
Client Booking Interface

Admin Service Management

Admin Booking Panel

Messaging Mailbox View (Client & Admin)
(Add screenshots to your GitHub repo)

📌 Notes
All styles are unified in css/style.css

Messages and bookings use dynamic database-driven UI

This is a fully working local development prototype using XAMPP

📬 Contact
Project developed by John Cojocaru, Dorina Habravan, Alex Cosmin Barbulescu, Eugen Oprea
© 2025 Nighthawk Smart Solutions


