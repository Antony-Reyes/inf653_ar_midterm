CREATE TABLE authors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    author VARCHAR(255) NOT NULL
);

CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category VARCHAR(255) NOT NULL
);

CREATE TABLE quotes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quote TEXT NOT NULL,
    author_id INT,
    category_id INT,
    FOREIGN KEY (author_id) REFERENCES authors(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Sample Data
INSERT INTO authors (author) VALUES
    ('Dr. Seuss'),
    ('Barack Obama'),
    ('Taylor Swift');

INSERT INTO categories (category) VALUES
    ('Life'),
    ('Motivation');

INSERT INTO quotes (quote, author_id, category_id) VALUES
    ('Today you are you!', 1, 1),
    ('The more that you read...', 1, 2),
    ('Money is not the only answer...', 2, 1);
