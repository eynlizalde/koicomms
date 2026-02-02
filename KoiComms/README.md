# Army's Angels Integrated School, INC. - Website Project

Welcome! This is the code for the official website of Army's Angels Integrated School, INC. This guide will help you set it up on your own computer.

## What You Need

To run this website, you only need one piece of software:
- **XAMPP**: This is a free and easy-to-install package that includes everything we need: a web server (Apache), a database (MariaDB), and the programming language (PHP).

## Step-by-Step Setup Guide

Follow these steps exactly, and you'll have the website running in no time!

### Step 1: Download and Install XAMPP

1.  Go to the official XAMPP download page: [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)
2.  Download the latest version for your operating system (Windows, Mac, or Linux).
3.  Open the installer you downloaded and follow the on-screen instructions. You can leave all the settings as they are by default.

### Step 2: Place the Project Files

1.  Find the folder where you have this project's code.
2.  Copy the entire project folder (the one this `README.md` file is in).
3.  Go to the folder where you installed XAMPP. On Windows, this is usually `C:\xampp`.
4.  Inside the `xampp` folder, find another folder called `htdocs`.
5.  Paste the project folder inside `htdocs`.

### Step 3: Start Your Web Server

1.  Open the **XAMPP Control Panel**. You can find this in your computer's Start Menu.
2.  You will see a list of services. Find **Apache** and **MySQL**.
3.  Click the **Start** button for both Apache and MySQL. They should turn green, which means they are running!

![XAMPP Control Panel](https://i.imgur.com/gY2iG53.png)

### Step 4: Create and Set Up the Database

This is the most important step! The database stores all the information for the website.

1.  Open your web browser (like Chrome, Firefox, etc.).
2.  Go to this address: `http://localhost/phpmyadmin`
3.  This is `phpMyAdmin`, the tool we use to manage the database.
4.  On the left side, click the **New** button to create a new database.
5.  A box will appear asking for the "Database name". Type exactly `koicomms_db` and click **Create**.
6.  Now that the database is created, you need to import the website's tables and content. In the left sidebar, click on the `koicomms_db` database you just made.
7.  Look for the **Import** tab at the top of the page and click it.
8.  On the import page, you will see a "Choose File" button. Click it.
9.  Find and select the `database.sql` file that is included with this project.
10. Scroll to the bottom of the page and click the **Go** button. After a few seconds, you should see a message saying the import was successful.

### Step 5: View the Website!

You're all done! To see the website:

1.  Open your web browser.
2.  Go to the address: `http://localhost/your-project-folder-name/`
    -   **Important:** Replace `your-project-folder-name` with the actual name of the folder you pasted into `htdocs`. For example, if your folder is named `KoiComms`, the address is `http://localhost/KoiComms/`.

### How to Log In as Admin

-   **Admin Page**: Go to `http://localhost/your-project-folder-name/components/adminside.php`
-   **Email**: `admin@aais.com`
-   **Password**: `admin123`

Once you are logged in, you will see pencil icons next to the text on the pages. You can click these to edit the content directly!