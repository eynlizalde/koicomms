# Army's Angels Integrated School, INC. - Website

This repository contains the source code for the official website of Army's Angels Integrated School, INC. The website is built with PHP and features a custom admin panel for content management.

## Key Features

*   **Dynamic Content:** Most text content on the pages (Fees, Activities, etc.) can be edited directly on the page by a logged-in administrator.
*   **Dynamic Activity Sections:** Administrators can add and delete entire event sections on the "School Activities" page, including uploading new images.
*   **Configurable Settings:** Key site settings, like the "Enroll Now" link, can be managed from the admin dashboard.
*   **Secure Admin System:** Features a password-protected admin login and a secure password reset function using email.

---

## Setup and Installation

Follow these steps to set up the project in a new development or production environment.

### 1. Prerequisites

*   A web server that supports PHP (e.g., Apache, Nginx). XAMPP or WAMP are suitable for local development.
*   A MySQL or MariaDB database server.
*   [Composer](https://getcomposer.org/) for managing PHP dependencies.
*   Access to an SMTP mail server for sending password reset emails.

### 2. Installation Steps

1.  **Clone the Repository**
    ```bash
    git clone [your-repository-url]
    cd [repository-folder]
    ```

2.  **Install PHP Dependencies**
    Run Composer in the root directory of the project. This will download libraries like PHPMailer and create the `vendor` directory.
    ```bash
    composer install
    ```

3.  **Database Setup**
    *   **Create a Database:** On your database server, create a new, empty database (e.g., `koicomms_db`).
    *   **Import Data:** Import the database structure and content.
        > **Note:** This repository should contain a `database.sql` backup file. You can create this by going to your working local phpMyAdmin, selecting your `koicomms_db` database, and using the "Export" feature to save it as a `.sql` file.
    *   **Update Credentials:** Open the `php/database.php` file and update the following variables with your new database server's details:
        ```php
        $servername = "your_db_host"; // e.g., "localhost"
        $username = "your_db_username";
        $password = "your_db_password";
        $dbname = "your_db_name";
        ```

4.  **Environment Configuration**
    *   In the root directory, create a file named `.env`.
    *   Copy the contents of `.env.example` into your new `.env` file.
    *   Update the variables in the `.env` file with your SMTP mail server credentials. This is required for the "Forgot Password" feature to work.

### 3. Accessing the Admin Panel

*   **Login Page:** The admin login page is located at `/components/adminside.php`.
*   **Credentials:** Admin credentials are stored in the `users` table of your database. You can find or set the admin email and password there. Passwords are encrypted, so to set a new one, you should generate a new password hash.

---

## Admin Functionality Overview

*   **On-Page Editing:** When logged in as an admin, pencil icons (<i class="fas fa-pencil-alt"></i>) will appear next to editable content and images. Clicking these allows you to update content or upload new images directly.
*   **Activities Page:**
    *   **Add Section:** Click the "Add New Section" button to create a new activity section.
    *   **Delete Section:** Click the '×' button on a section to mark it for deletion.
    *   **Save Changes:** After adding or deleting sections, a "Save All Changes" button will appear. Clicking this makes the changes permanent in the database.
*   **Admin Dashboard:** Accessible after logging in, this page allows you to manage global site settings, such as the "Enroll Now" link.
