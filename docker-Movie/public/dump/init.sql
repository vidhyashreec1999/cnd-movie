-- Create database
CREATE DATABASE IF NOT EXISTS movie_booking;
USE movie_booking;

-- Create user and grant privileges
CREATE USER IF NOT EXISTS 'user1'@'%' IDENTIFIED BY 'passwd';
GRANT ALL PRIVILEGES ON movie_booking.* TO 'user1'@'%';
FLUSH PRIVILEGES;

-- Create tables

-- Movies table
CREATE TABLE IF NOT EXISTS movies (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    duration INT NOT NULL,
    image_url VARCHAR(255)
);

-- Theaters table
CREATE TABLE IF NOT EXISTS theaters (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL
);

-- Showtimes table with cascading deletes
CREATE TABLE IF NOT EXISTS showtimes (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    movie_id INT NOT NULL,
    theater_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (theater_id) REFERENCES theaters(id)
);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    showtime_id INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    seats INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (showtime_id) REFERENCES showtimes(id)
);

-- Insert sample data into movies table
INSERT INTO movies (title, description, duration, image_url) VALUES
('Inception', 'A thief who enters the dreams of others to steal secrets from their subconscious.', 148, 'https://c4.wallpaperflare.com/wallpaper/574/531/642/2010-inception-movie-inception-poster-wallpaper-preview.jpg'),
('The Shawshank Redemption', 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.', 142, 'https://images-cdn.ubuy.co.in/634f8adb65d5bc12a734313b-classic-popular-movies-the-shawshank.jpg');

-- Insert sample data into theaters table
INSERT INTO theaters (name, location) VALUES
('Cineplex Downtown', '123 Main St, Cityville'),
('Starlight Cinema', '456 Oak Ave, Townsburg');

-- Insert sample data into showtimes table
INSERT INTO showtimes (movie_id, theater_id, start_time, price) VALUES
(1, 1, '2024-08-25 18:00:00', 12.99),
(1, 2, '2024-08-25 20:00:00', 11.99),
(2, 1, '2024-08-25 19:00:00', 12.99),
(2, 2, '2024-08-25 21:00:00', 11.99);

-- Insert sample data into bookings table
INSERT INTO bookings (showtime_id, customer_name, customer_email, seats, total_price) VALUES
(1, 'John Doe', 'johndoe@example.com', 2, 25.98),
(2, 'Jane Smith', 'janesmith@example.com', 3, 35.97);
