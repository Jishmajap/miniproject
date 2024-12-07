Database shops_management

# Table shop_owners
- id,name,email,phone_number,password

`CREATE TABLE shop_owners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone_number VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL
);`

# Table shops
- id,shop_name,address,district,latitude,longitude,phone_number,email,website,owner_email

`CREATE TABLE shops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shop_name VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    district VARCHAR(255) NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    website VARCHAR(255),
    owner_email VARCHAR(255) NOT NULL
);`


# Table services
- id,shop_id,service_name,ription,price,email

`CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shop_id INT NOT NULL,
    service_name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    email VARCHAR(255) NOT NULL,
    FOREIGN KEY (shop_id) REFERENCES shops(id)
);`


# Table service_requests
- id,shop_name,shop_address,name,email,phone,cureent_location,service_needed,time_stamp,status,shop_id,customer_name

`CREATE TABLE service_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shop_name VARCHAR(255) NOT NULL,
    shop_address TEXT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    current_location TEXT NOT NULL,
    service_needed TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) NOT NULL,
    shop_id INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    FOREIGN KEY (shop_id) REFERENCES shops(id)
);`


Database user_management

 # table users
- id,name,email,password

`CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);`
