-- Ensure the database is created only if it does not exist
CREATE DATABASE IF NOT EXISTS INF653_AR_Midterm;
USE INF653_AR_Midterm;

-- Authors Table
CREATE TABLE IF NOT EXISTS authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author VARCHAR(255) NOT NULL
);

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(255) NOT NULL
);

-- Quotes Table
CREATE TABLE IF NOT EXISTS quotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quote TEXT NOT NULL,
    author_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Insert Sample Authors
INSERT INTO authors (author) VALUES
    ('Albert Einstein'),
    ('Mark Twain'),
    ('Maya Angelou'),
    ('William Shakespeare'),
    ('Confucius');

-- Insert Sample Categories
INSERT INTO categories (category) VALUES
    ('Motivation'),
    ('Philosophy'),
    ('Life'),
    ('Happiness'),
    ('Success');

-- Insert Sample Quotes
INSERT INTO quotes (quote, author_id, category_id) VALUES
    ('Life is like riding a bicycle. To keep your balance, you must keep moving.', 1, 3),
    ('The secret of getting ahead is getting started.', 2, 5),
    ('You may not control all the events that happen to you, but you can decide not to be reduced by them.', 3, 3),
    ('Some are born great, some achieve greatness, and some have greatness thrust upon them.', 4, 5),
    ('It does not matter how slowly you go as long as you do not stop.', 5, 1);

