# File Management System - School Project

This project is a web-based **File Management System** designed to meet specific requirements as part of a school
assignment. It was built using **Laravel**, **Inertia.js**, **React.js**, and **TailwindCSS**.

## Purpose

The purpose of this system is to provide users with a secure and efficient way to manage files collaboratively. It
ensures controlled access, tracks changes, and supports file versioning while maintaining a user-friendly interface.

## Features

1. **File States and Access Control**:
    - Files can be marked as "free" or "in use" by specific users.
    - Users can only access files within their authorized groups.

2. **Check-in/Check-out**:
    - Reserve, download, edit, and upload files with a streamlined check-in/check-out process.
    - Supports bulk operations with conflict prevention.

3. **Concurrency Control**:
    - Prevents simultaneous reservation of the same file by multiple users.
    - Supports up to 100 concurrent users.

4. **Reporting and Notifications**:
    - Generates detailed reports by file or user.
    - Sends real-time notifications for file status changes.

5. **Backup and Versioning**:
    - Automatically backs up files during check-in/check-out processes.
    - Allows restoring previous file versions.

6. **Cross-Device Compatibility**:
    - Fully responsive and compatible with all major browsers and devices.

7. **Data Export/Import**:
    - Export operational reports in CSV or PDF format.
    - Import new files into the system.

8. **Change Tracking**:
    - Logs detailed change history, including editor, timestamps, and modifications.

## Tech Stack

- **Backend**: [Laravel](https://laravel.com/)
- **Frontend
  **: [Inertia.js](https://inertiajs.com/), [React.js](https://reactjs.org/), [TailwindCSS](https://tailwindcss.com/)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/khaldounalhalabi/file-manager.git
   cd file-manager
2. run the following
    ```bash
    composer install
    npm install
    npm run dev
3. configure your .env file
4. generate encryption key :
    ```bash
    php artisan key:generate
5. run the migration and the seeders
    ```bash 
    php artisan migrate
    php artisan db:seed
6. head to your local host /public/v1/customer/login
7. login using: email : user@files.com and password : 123456789
