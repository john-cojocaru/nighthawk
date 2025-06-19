Nighthawk Smart Solutions Website

📘 Overview

A fully featured website for Nighthawk Smart Solutions, a company specializing in fiber optic cabling, commercial network installations, and smart home systems. Built using PHP, MySQL, HTML/CSS/JS, and designed following Agile methodology, the project provides a seamless platform for service booking, client communication, and internal content management.

🚀 Features

Responsive Web Design: Clean, modern UI with responsive layouts for mobile and desktop.

Interactive Contact Form: Professional inquiry form with integrated backend processing.

Booking System: Calendar-based client meeting scheduler with time slot selection.

Admin CMS Panel: CRUD for services, appointments, clients, and company portfolio.

User Authentication: Secure login with role-based access control for admins.

Validation & Security: Backend validation, session handling, and secure password storage.

Portfolio Gallery: Showcase of past commercial and residential projects.

Real-Time Logs & Error Handling: Admin can review error logs for system transparency.

📋 Prerequisites

PHP 8.1 or later

MySQL 5.7 or later

XAMPP or similar local server stack

🛠️ Installation

Clone the repository:

git clone https://github.com/your-username/nighthawk-smart-solutions.git

Move into the project directory:

cd nighthawk-smart-solutions

Start XAMPP and ensure Apache and MySQL are running.

Import nighthawk_db.sql into phpMyAdmin.

Configure database settings in config.php.

Open the browser and navigate to:

http://localhost/nighthawk-smart-solutions

📖 Usage

Admin Login

Go to /admin/login.php

Enter admin credentials

Access dashboard to manage:

Services

Bookings

Clients

Portfolio projects

Client Booking Flow

Navigate to Contact/Booking page

Fill in name, email, preferred date/time

Submit the form

Confirmation message shown

CMS Functionality

Add/edit/delete services and appointments

Upload portfolio images and details

View inquiry and booking list

⚙️ Configuration

Update config.php with your database credentials:

$host = 'localhost';
$db = 'nighthawk_db';
$user = 'root';
$pass = '';

🧪 Testing

Manual functional testing for each module (forms, CMS, login)

Validation checks and booking submission logs

SQL script tested via phpMyAdmin

🧱 Project Structure

📁 /admin
  ├── dashboard.php
  ├── manage_services.php
  ├── manage_appointments.php
📁 /assets
  ├── /css, /js, /images
📁 /includes
  ├── config.php
  ├── db_connect.php
📁 /public
  ├── index.php
  ├── contact.php
  ├── booking.php
📁 /uploads
  ├── portfolio images

🔐 Security

Passwords hashed with bcrypt

Input validation to prevent SQL injection and XSS

Session-based authentication for admin area

📦 Deployment

Initially developed in XAMPP

Ready for live hosting (Namecheap/GoDaddy/Hostinger)

Upload all files to public_html and import database

📈 Roadmap

Email notifications for bookings

Admin calendar view of upcoming appointments

Image optimisation for portfolio

Dashboard statistics widgets

Contact form 

🙌 Acknowledgments

Visual Paradigm for diagrams

Trello for task tracking

HTML5  design inspiration

PHP manual for reference

📄 License

This project is licensed under the MIT License.


