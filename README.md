# Online Food Ordering System in PHP

This is a full-featured web-based **Online Food Ordering System** built using **PHP**, **MySQL**, **HTML/CSS**, and **JavaScript**. It allows users to browse menus, place food orders, and for restaurants to manage those orders in real time.

## 📦 Features

### 👨‍🍳 Users
- Register and log in securely
- Browse restaurants and dishes
- Add items to cart and checkout
- View and manage orders
- Reset password functionality

### 🧑‍💼 Admin Panel
- Manage users and restaurants
- Add/edit/delete dishes
- View all orders
- View delivery and earnings reports

### 🛵 Restaurant Panel
- Accept and manage orders
- Update order statuses
- Track earnings

### 🚚 Delivery Panel
- View assigned deliveries
- Mark orders as delivered

## 🛠️ Technologies Used

- **Frontend**: HTML, CSS (with SCSS), JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Dependency Management**: Composer
- **Email Services**: PHP Mailer

## 📁 Project Structure


## ⚙️ Setup Instructions

1. **Clone or download** the repository.
2. **Import the SQL database**:
   - Locate the `.sql` file (usually in a `/db` or root folder if provided).
   - Import it using phpMyAdmin or CLI.
3. **Configure the Database**:
   - Open `connection/connect.php`
   - Update it with your MySQL credentials.
4. **Run Locally**:
   - Use XAMPP/WAMP and place the folder in the `htdocs` directory.
   - Open `http://localhost/online-food-ordering-system/` in your browser.

## 🔐 Login Credentials

These may be default/test credentials. Update them in the database as needed.

- **Admin**: `heet@gmail.com` / `123456`
- **User**: `amit.verma@ex.in` / `123456`
- **Restaurant**: `SorrisoCafe@gmail.com` / `123456`
- **Delivery**: `prince@gmail.com` / `123456`

## 📝 License

This project is open-source and free to use for learning and development purposes.
---
