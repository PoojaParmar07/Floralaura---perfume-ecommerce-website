# 🌸 FloraLaura – Perfume eCommerce Website

**FloraLaura** is a perfume eCommerce web application where users can browse perfume products, add them to a cart, and place orders. It is designed for local development using XAMPP, PHP, and MySQL.

---

## 🚀 Features

- 🛍️ Product listing with images and pricing
- ➕ Add to Cart functionality with quantity selection
- 🧺 Cart page with remove option.
- ✅ Order placement.
- 🗃️ Database integration using MySQL
- 🧑‍💻 Built with HTML, CSS, JavaScript,bootstrap, PHP, and MySQL

---

🛠️ Technologies Used

- HTML5, CSS3, JavaScript
- PHP 8.x
- MySQL (MariaDB)
- XAMPP (Apache & MySQL)

---

🧑‍🍳 How to Run the Project Locally
 
 1. Requirements
- [XAMPP](https://www.apachefriends.org/index.html) installed

 2. Setup Steps

```bash
1. Copy project folder into htdocs
C:\xampp\htdocs\floralaura

2. Start Apache and MySQL via XAMPP Control Panel

3. Open phpMyAdmin
Go to http://localhost/phpmyadmin

4.Create and Import Database
Create a database named floralaura

Run the following SQL to create tables:

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  description TEXT,
  price DECIMAL(10,2),
  image VARCHAR(255),
  stock INT
);

CREATE TABLE cart (
  id INT AUTO_INCREMENT PRIMARY KEY,
  session_id VARCHAR(100),
  product_id INT,
  quantity INT,
  FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(100),
  email VARCHAR(100),
  address TEXT,
  total DECIMAL(10,2),
  order_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  product_id INT,
  quantity INT,
  price DECIMAL(10,2),
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

