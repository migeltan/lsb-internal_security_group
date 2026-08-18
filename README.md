# SMART Portal — Prototype (Phase 1)

Digital Access Pass and Vehicle Sticker Application Portal — prototype for the
Internal Security Group, House of Representatives (Philippines).

## What's in Phase 1

- Full MySQL schema (`sql/schema.sql`) covering applicants, both application
  types, family/education background, documents, users, and an audit log.
- Landing page (`index.php`).
- Access Pass application form (`access-pass.php`) — personal info, repeatable
  family background, repeatable educational background, conditional document
  uploads (based on applicant type), photo upload, declaration.
- Vehicle Sticker application form (`vehicle-sticker.php`) — personal info,
  vehicle info, conditional document uploads (based on ownership).
- Shared header/footer, navy/gold government-style Bootstrap theme.
- Placeholder stubs for Application Status and Admin Login (built in later phases).

**Not yet functional in Phase 1:** form submission, file storage, reference
number generation, admin authentication, review workflow, access pass
generation. These arrive in Phases 2–5.

## Setup

### 1. Install XAMPP
Download from https://www.apachefriends.org/ and install it.

### 2. Place the project in htdocs
Copy this whole `smart-portal` folder into your XAMPP web root:

- Windows: `C:\xampp\htdocs\smart-portal`
- macOS: `/Applications/XAMPP/htdocs/smart-portal`
- Linux: `/opt/lampp/htdocs/smart-portal`

### 3. Start Apache and MySQL
Open the XAMPP Control Panel and click **Start** next to both Apache and MySQL.

### 4. Import the database
Open **phpMyAdmin** (`http://localhost/phpmyadmin`), go to the **Import** tab,
choose `sql/schema.sql`, and click **Go**. This creates the `smart_portal`
database with all tables and a seed admin account.

> Seed admin credentials (Phase 3 onward): `admin` / `ChangeMe123!`
> Change this password before showing the prototype to anyone else.

### 5. Check the database config
Open `config/database.php`. Defaults match a fresh XAMPP install
(user `root`, no password). If you set a MySQL root password, update
`DB_PASS` there.

### 6. Open in your browser
Visit:

```
http://localhost/smart-portal/
```

You should see the landing page.

## Project structure

```
smart-portal/
├── index.php                  Landing page
├── access-pass.php            Access pass application form
├── vehicle-sticker.php        Vehicle sticker application form
├── application-status.php     Status checker (stub — Phase 4)
├── admin/
│   └── login.php              Admin login (stub — Phase 3)
├── config/
│   └── database.php           PDO database connection
├── includes/
│   ├── header.php
│   └── footer.php
├── assets/
│   ├── css/style.css
│   └── js/
│       ├── access-pass.js     Family/education repeaters, conditional docs
│       └── vehicle-sticker.js Conditional document logic
├── sql/
│   └── schema.sql             Full database schema + seed admin
├── uploads/                   Will hold submitted documents (Phase 2)
└── templates/                 Will hold the access pass template (Phase 5)
```

## Next: Phase 2

File uploads, application reference number generation (`AP-2026-00001` /
`VS-2026-00001`), and saving submitted form data into the database via
`submit.php`.
