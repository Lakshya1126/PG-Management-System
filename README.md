<div align="center">

# 🏠 PG-Management-System — PG Life Accommodation Booking Platform

**A web-based system for browsing Paying Guest (PG) listings, booking rooms, and managing PG accommodation**

[![PHP](https://img.shields.io/badge/PHP-Server--Side-777BB4?style=flat-square&logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat-square&logo=mysql)](https://www.mysql.com/)
[![HTML5](https://img.shields.io/badge/HTML5-Structure-E34F26?style=flat-square&logo=html5)](https://developer.mozilla.org/en-US/docs/Web/HTML)
[![CSS3](https://img.shields.io/badge/CSS3-Styling-1572B6?style=flat-square&logo=css3)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![JavaScript](https://img.shields.io/badge/JavaScript-Interactivity-F7DF1E?style=flat-square&logo=javascript)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-Responsive%20UI-7952B3?style=flat-square&logo=bootstrap)](https://getbootstrap.com/)
[![XAMPP](https://img.shields.io/badge/XAMPP-Local%20Server-FB7A24?style=flat-square)](https://www.apachefriends.org/)

</div>

---

## 📋 Table of Contents

1. [What is this Project?](#-what-is-this-project)
2. [Key Features](#-key-features)
3. [How it Works — End-to-End Flow](#-how-it-works--end-to-end-flow)
4. [Architecture Overview](#-architecture-overview)
5. [Project Structure](#-project-structure)
6. [Database Design](#-database-design)
7. [Page / Endpoint Reference](#-page--endpoint-reference)
8. [Installation & Running](#-installation--running)
9. [Tech Stack Summary](#-tech-stack-summary)
10. [User Roles](#-user-roles)
11. [Limitations](#-limitations)
12. [Future Scope](#-future-scope)

---

## 🎯 What is this Project?

The **PG Management System (PG Life)** is a dynamic, web-based application built to simplify and automate the management of Paying Guest (PG) accommodations. Traditional PG management relies on manual record-keeping and in-person inquiries, which is slow, error-prone, and hard to scale. This system replaces it with a centralized digital platform that makes browsing and booking easier for tenants and gives owners and administrators one place to manage listings and bookings.

The system has two sides:

- 🧍 **Tenant side** — browse PGs by city, filter by gender, view amenities, ratings and testimonials, book a room, and review bookings from a personal dashboard.
- 🛠️ **Admin side** — view all users, properties and bookings, and manage amenities, cities and reports *(admin dashboard is under development)*.

Built with **PHP** on the backend and **MySQL** for data storage, with a responsive **HTML/CSS/JavaScript (Bootstrap)** frontend.

---

## ✨ Key Features

| Feature | Details |
|---|---|
| 🔐 **Authentication** | Login and signup through popup modals (`login_modal.php`, `signup_modal.php`), with session handling via PHP sessions and `logout.php` |
| 🏙️ **City-wise Search** | Pick a city (Delhi, Mumbai, Hyderabad, Bengaluru) from the home page dropdown or city cards |
| 🔎 **Property Listings** | `property_list.php` lists PGs for the chosen city with rent, ratings, and interest counts, plus a gender filter (Unisex / Male / Female) |
| 🏘️ **Property Details** | `property_detail.php` shows amenities (with icons), food/cleanliness/safety ratings, and a testimonial carousel |
| ❤️ **Interested Marking** | Logged-in users can mark PGs as interested; these appear on their dashboard |
| 🛏️ **Multi-step Booking** | `booking.php` walks through room type → AC preference → check-in date, duration and special requests |
| 💰 **Dynamic Pricing** | Final price is calculated from the base rent, room type and AC choice |
| 👤 **User Dashboard** | `dashboard.php` shows personal details and the user's interested PGs |
| 📱 **Responsive UI** | Bootstrap grid, hover effects, color-coded star ratings, Font Awesome icons |
| 🔒 **Data Integrity** | Primary and foreign keys, `NOT NULL` and `UNIQUE` constraints, and login-protected booking |

---

## 🔄 How it Works — End-to-End Flow

```
┌─────────────────────────────────────────────────────────────────────────┐
│                          TENANT BOOKING FLOW                            │
└─────────────────────────────────────────────────────────────────────────┘

1. LOGIN / SIGNUP
   └── Popup modals on index.php (email + password); session stores user_id
         │
         ▼
2. SEARCH PGs (index.php → property_list.php?city=...)
   ├── Select a city from the dropdown or city cards
   └── Optionally filter by gender (Unisex / Male / Female)
         │
         ▼
3. VIEW DETAILS (property_detail.php)
   ├── Amenities, ratings (food, cleanliness, safety)
   ├── Testimonials from other tenants
   └── Mark as "interested"
         │
         ▼
4. BOOK (booking.php?property_id=...)
   ├── Step 1: choose room type   — single / double / triple
   ├── Step 2: choose AC type     — AC / non-AC
   └── Step 3: check-in date, duration (months), special requests
         │
         ▼
5. CONFIRMATION
   └── Booking saved with calculated price and status = "pending"
         │
         ▼
6. MANAGE (dashboard.php)
   └── View profile details and interested PGs; logout via logout.php

┌─────────────────────────────────────────────────────────────────────────┐
│                     ADMIN FLOW (FUTURE EXPANSION)                       │
└─────────────────────────────────────────────────────────────────────────┘

1. Admin login
2. View all users, properties and bookings
3. Manage amenities, cities and reports
4. CRUD operations on PG listings
```

### Pricing Logic (from `booking.php`)

| Selection | Effect on base rent |
|---|---|
| Single room | × 1.0 |
| Double room | × 0.8 |
| Triple room | × 0.6 |
| AC | × 1.3 (applied on top of the room multiplier) |

---

## 🏗️ Architecture Overview

The system follows a classic **client-server, page-based PHP architecture** — each feature is its own PHP script that talks directly to a shared MySQL database through `mysqli`, rather than a REST API layer.

```
┌──────────────────────────────────────────────────────────────────────┐
│                   BROWSER (HTML + CSS + JavaScript)                  │
│        Home / Listings / Details / Booking / Dashboard pages         │
└───────────────────────────┬──────────────────────────────────────────┘
                            │ HTTP (form submissions / page requests)
                            ▼
┌──────────────────────────────────────────────────────────────────────┐
│                      APACHE + PHP (XAMPP)                            │
│                                                                      │
│  Pages                       Shared includes                         │
│  ─────────────────           ─────────────────────                   │
│  index.php                   includes/database_connect.php           │
│  property_list.php           includes/header.php                     │
│  property_detail.php         includes/footer.php                     │
│  booking.php                 includes/login_modal.php                │
│  dashboard.php               includes/signup_modal.php               │
│  logout.php                                                          │
└───────────────────────────┬──────────────────────────────────────────┘
                            │ mysqli
                            ▼
┌──────────────────────────────────────────────────────────────────────┐
│                          MySQL DATABASE                              │
│  users · cities · properties · bookings · amenities ·                │
│  properties_amenities · interested_users_properties · testimonials   │
└──────────────────────────────────────────────────────────────────────┘
```

---

## 📁 Project Structure

```
PGLIFE/
│
├── index.php                 # Home page: city search, city cards, modals
├── property_list.php         # PG listings for a selected city + filter modal
├── property_detail.php       # Single PG: amenities, ratings, testimonials
├── booking.php               # Multi-step booking flow and price calculation
├── dashboard.php             # User dashboard (details, interested PGs)
├── logout.php                # Ends the active session
│
├── includes/
│   ├── database_connect.php  # Shared MySQL connection (mysqli)
│   ├── header.php            # Navigation bar
│   ├── footer.php            # Page footer
│   ├── login_modal.php       # Login popup
│   └── signup_modal.php      # Signup popup
│
├── css/                      # bootstrap.min.css, common.css, index.css, ...
├── js/                       # jquery.js, bootstrap.min.js, common.js,
│                             #   property_list.js, property_detail.js
└── img/                      # City images, gender icons, property photos
```

> Note: the program code section of the project report documents `index.php`, `booking.php`, `dashboard.php`, `property_detail.php` and `property_list.php`. The `includes/`, `css/`, `js/` and `img/` folders are referenced by that code.

---

## 🗄️ Database Design

| Table | Description | Key Fields |
|---|---|---|
| **users** | Registered user details | `id`, `full_name`, `phone`, `email`, `password`, `college_name`, `gender` |
| **cities** | Cities where PGs are available | `id`, `name` |
| **properties** | PG property listings | `id`, `name`, `city_id`, `gender`, `address`, `description`, `rent`, `rating_food`, `rating_clean`, `rating_safety` |
| **bookings** | User bookings | `id`, `user_id`, `property_id`, `check_in_date`, `duration_months`, `special_requests`, `room_type`, `ac_type`, `price`, `status` |
| **interested_users_properties** | Maps interested users to PGs | `user_id`, `property_id` |
| **amenities** | Master list of amenities | `id`, `name`, `icon`, `type` |
| **properties_amenities** | Links PGs to amenities | `property_id`, `amenity_id` |
| **testimonials** | User feedback per property | `id`, `user_id`, `property_id`, `content` |

**Relationships**

- **City → Property**: one-to-many
- **User → Booking**: one-to-many
- **Property → Booking**: one-to-many
- **User ↔ Property** (interested): many-to-many via `interested_users_properties`
- **Property ↔ Amenity**: many-to-many via `properties_amenities`
- **User / Property → Testimonial**: each testimonial links one user to one property

**Column details:** `gender` is `male / female / unisex` for properties, `room_type` is `single / double / triple`, `ac_type` is `ac / non-ac`, and booking `status` is `pending / confirmed / cancelled`.

**Integrity rules:** primary keys on all main tables, foreign keys (`city_id`, `user_id`, `property_id`), `NOT NULL` on mandatory fields, `UNIQUE` email at signup, and `CHECK` constraints on ratings (0–5), gender values and rent > 0.

---

## 🌐 Page / Endpoint Reference

| Page | Method | Purpose |
|---|---|---|
| `index.php` | GET | Home page with city search and login/signup modals |
| `property_list.php?city=<name>` | GET | List PGs in a city (redirects to home if `city` is missing) |
| `property_detail.php?property_id=<id>` | GET | Detailed view of one PG |
| `booking.php?property_id=<id>` | GET / POST | Multi-step booking (requires login) |
| `dashboard.php` | GET | Logged-in user's dashboard (redirects if not logged in) |
| `logout.php` | GET | End the session |

---

## 🚀 Installation & Running

### Prerequisites

| Tool | Purpose |
|---|---|
| XAMPP (Apache + PHP + MySQL) | Local server environment |
| phpMyAdmin / MySQL Workbench | Managing the database visually |
| VS Code or Notepad++ | Editing the project files |

### Steps

```bash
# 1. Place the project folder inside your XAMPP htdocs directory
#    e.g. C:\xampp\htdocs\PGLIFE

# 2. Start Apache and MySQL from the XAMPP control panel

# 3. Create the database
#    Open phpMyAdmin (http://localhost/phpmyadmin) and create a database.
#    Use the same name that is set in includes/database_connect.php

# 4. Create the tables
#    users, cities, properties, bookings, interested_users_properties,
#    amenities, properties_amenities, testimonials
#    (use the Database Design section above as a reference)

# 5. Confirm the connection settings in includes/database_connect.php
#    host = localhost, user = root, password = "", dbname = <your database>

# 6. Open the app in your browser
#    http://localhost/PGLIFE/index.php
```

---

## 🛠️ Tech Stack Summary

| Layer | Technology | Purpose |
|---|---|---|
| **Frontend** | HTML, CSS, JavaScript, Bootstrap | Page structure, styling, responsive layout, interactivity |
| **Icons & Fonts** | Font Awesome, Open Sans | Amenity icons and typography |
| **Backend** | PHP | Server-side processing and dynamic functionality |
| **Database** | MySQL (via `mysqli`) | Storing users, properties, bookings, amenities and testimonials |
| **Local Server** | XAMPP | Apache + PHP + MySQL development environment |
| **Database Tooling** | phpMyAdmin / MySQL Workbench | Visual database management |
| **Editor** | VS Code / Notepad++ | Development environment |

---

## 👥 User Roles

### Tenant (Student / User)
- Signs up and logs in with email and password
- Browses PGs by city and gender filter
- Views amenities, ratings and testimonials
- Marks PGs as interested and books a room with preferred room and AC type
- Reviews details and interested PGs on the dashboard

### Admin *(planned)*
- Logs in through a separate admin login
- Views all users, properties and bookings
- Manages amenities, cities, and reports
- Adds, edits and deletes PG listings

### Owner *(optional, planned)*
- Adds new properties and sees user interest

---

## ⚠️ Limitations

- Admin dashboard is still under development
- Requires continuous internet connectivity — no offline support
- Only four cities are currently available (Delhi, Mumbai, Hyderabad, Bengaluru)
- No online payment gateway; bookings are saved with a `pending` status
- No dedicated mobile app — responsive web only
- Some queries in `booking.php` insert request values directly into SQL; they should be converted to prepared statements (`mysqli_prepare`) before any real deployment

---

## 🔮 Future Scope

- Complete admin dashboard for listings, bookings, users and reports
- Integrated online payments (UPI, cards, net banking, wallets)
- Owner portal for adding and managing properties
- Real-time room availability and occupancy tracking
- Booking confirmation, rent-due and cancellation notifications
- Booking cancellation and history management
- Prepared statements and stronger input validation across all pages
- Analytics on bookings, rent collection and occupancy
- Mobile app and more cities

<div align="center">

Developed by Lakshya Kansal

</div>
