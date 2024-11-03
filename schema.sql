-- Create the user_passwords table
CREATE TABLE user_passwords (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    address VARCHAR(255) NOT NULL,
    age INT NOT NULL
);

-- Create the customers table
CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL
);

-- Create the coffee_shops table
CREATE TABLE coffee_shops (
    shop_id INT AUTO_INCREMENT PRIMARY KEY,
    shop_name VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL
);

-- Create the orders table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    shop_id INT NOT NULL,
    customer_id INT NOT NULL,
    order_details TEXT NOT NULL,
    order_date DATETIME NOT NULL,
    created_by INT NOT NULL,
    updated_by INT NOT NULL,
    last_updated DATETIME NOT NULL,
    FOREIGN KEY (shop_id) REFERENCES coffee_shops(shop_id),
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    FOREIGN KEY (created_by) REFERENCES user_passwords(user_id),
    FOREIGN KEY (updated_by) REFERENCES user_passwords(user_id)
);

INSERT INTO `coffee_shops` (`shop_id`, `shop_name`, `location`) VALUES 
(1, 'Brew Haven', '6750 Ayala Avenue, Makati'),
(2, 'Brew Haven', 'Reserve at The Podium, Mandaluyong'),
(3, 'Brew Haven', 'Reserve Estancia Mall, Pasig'),
(4, 'Brew Haven', 'Sierra Valley, Rizal'),
(5, 'Brew Haven', '32nd Street & 7th Ave, Taguig'),
(6, 'Brew Haven', 'Evia Mall, Las Piñas'),
(7, 'Brew Haven', 'Macapagal Boulevard, Pasay'),
(8, 'Brew Haven', 'Capitol Commons, Pasig'),
(9, 'Brew Haven', 'Araneta Center, Quezon City'),
(10, 'Brew Haven', 'Harbour Square, Manila'),
(11, 'Brew Haven', 'Intramuros, Manila'),
(12, 'Brew Haven', 'Molito, Alabang'),
(13, 'Brew Haven', 'Tagaytay'),
(14, 'Brew Haven', 'Iloilo Festive Walk'),
(15, 'Brew Haven', 'Cagayan de Oro - Limketkai Center'),
(16, 'Brew Haven', 'Cebu IT Park'),
(17, 'Brew Haven', 'Greenbelt 3, Makati'),
(18, 'Brew Haven', 'Bonifacio High Street, Taguig'),
(19, 'Brew Haven', 'BGC Uptown Mall, Taguig'),
(20, 'Brew Haven', 'Maysilo Circle, Mandaluyong');