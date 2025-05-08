# User Management System

A simple application that allows users to register, login, and update their account information.

## Features

- User Registration
- User Login
- View & Update Account Information
- Dynamic base path detection based on folder name

## Technology Stack

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Libraries**:
  - Bootstrap 5 (Frontend CSS)
  - jQuery (JavaScript library)
  - Flight PHP Framework (RESTful API)

## Installation & Setup

### Prerequisites

- PHP 7.4+
- MySQL 5.7+
- Web server (Apache, Nginx, or MAMP/XAMPP)
- Composer (PHP package manager)

### Database Setup

1. Create a database and import the provided SQL schema:
   ```
   mysql -u yourusername -p yourdbname < database/schema.sql
   ```

2. Configure database connection in `config/database.php`

### Application Setup

1. Clone the repository:
   ```
   git clone https://github.com/IamCooller/user-system-php
   cd /user-system-php
   ```

2. Install PHP dependencies:
   ```
   composer install
   ```

3. Place the project in your web server's document root directory (e.g., htdocs for MAMP)

4. The application automatically detects the folder name it's installed in and adjusts paths accordingly

## Changing Project Directory Name

This application dynamically detects the folder name it's installed in and adjusts all paths accordingly. If you rename the project folder or move it to a different location, no additional configuration is needed - the system will automatically adapt.

## API Endpoints

### User Registration
- **Endpoint**: `/api/register`
- **Method**: POST
- **Parameters**:
  - name
  - email
  - dob (Date of Birth)
  - password

### User Login
- **Endpoint**: `/api/login`
- **Method**: POST
- **Parameters**:
  - email
  - password

### Get User Information
- **Endpoint**: `/api/users/{id}`
- **Method**: GET

### Update User Information
- **Endpoint**: `/api/users/{id}`
- **Method**: PUT
- **Parameters**:
  - name
  - email
  - dob (Date of Birth)

## Project Structure

- `/api` - API backend files
- `/css` - CSS stylesheets
- `/js` - JavaScript files
- `/config` - Configuration files
- `/database` - Database schema and migrations
- `/vendor` - Composer dependencies (not included in repository)

## Notes

- This application uses client-side session management for demo purposes
- In a production environment, proper authentication/JWT tokens would be implemented 