# PHP RESTful User API

A simple RESTful API built with core PHP and MySQL for basic user management. This project allows you to create, read, update, and delete (CRUD) user records via standard HTTP methods.

## 🔧 Features

- Add new users (`POST`)
- View all users (`GET`)
- Fully update a user (`PUT`)
- Partially update user details (`PATCH`)
- Delete a user (`DELETE`)
- Secure input handling (sanitization to prevent SQL Injection & XSS)

## 🧰 Technologies Used

- PHP (Pure procedural and OOP style)
- MySQL
- MySQLi extension

---

## 📁 Project Structure

php-restful-user-api/
├── index.php # Entry point for handling HTTP requests
├── src/
│ ├── db.php # Database connection
│ └── functions.php # Core logic for CRUD operations



---

## 🚀 API Endpoints

| Method | Endpoint     | Description                      |
|--------|--------------|----------------------------------|
| POST   | `/index.php` | Create a new user                |
| GET    | `/index.php` | Retrieve all users               |
| PUT    | `/index.php` | Fully update a user (ID required)|
| PATCH  | `/index.php` | Partially update a user          |
| DELETE | `/index.php` | Delete a user (ID required)      |

---

## 📦 Sample Requests

### Create User (POST)
```bash
curl -X POST -d "name=John Doe&email=john@example.com&age=30" http://localhost/index.php

Get All Users (GET)
curl http://localhost/index.php

Full Update (PUT)
curl -X PUT -H "Content-Type: application/json" -d '{"id":"1", "name":"Jane", "email":"jane@example.com", "age":"28"}' http://localhost/index.php

Partial Update (PATCH)
curl -X PATCH -H "Content-Type: application/json" -d '{"id":"1", "email":"newemail@example.com"}' http://localhost/index.php

Delete User (DELETE)
curl -X DELETE -H "Content-Type: application/json" -d '{"id":"1"}' http://localhost/index.php


🛠 Setup Instructions

1. Clone the Repository
    git clone https://github.com/your-username/php-restful-user-api.git

2. Configure Database
    Create a database and a users table (see below).
    Update src/db.php with your DB credentials.

3. SQL for Users Table
    CREATE TABLE `users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(100) NOT NULL,
      `email` varchar(100) DEFAULT NULL,
      `age` int(3) DEFAULT NULL,
      `created_at` datetime NOT NULL,
      `updated_at` datetime NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

4. Run via Local Server

    Place the project folder inside your XAMPP/MAMP htdocs directory.
    Start Apache and MySQL services.
    Access the API at: http://localhost/php-restful-user-api/index.php

5. Basic example for git commit:

    cd project folder
    git init
    git add .
    git commit -m "Initial commit"
    git branch -M main
    git remote add origin https://github.com/github_username/github_repo_name
    git push -u origin main


📄 License
    This project is licensed under the MIT License.
