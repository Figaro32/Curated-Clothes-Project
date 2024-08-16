-- Brands --
CREATE TABLE brand (
    id INTEGER NOT NULL UNIQUE,
    name TEXT NOT NULL,
    PRIMARY KEY(id AUTOINCREMENT)
);

INSERT INTO
    brand (id, name)
VALUES
    (1, 'Nike');

INSERT INTO
    brand (id, name)
VALUES
    (2, 'Acne Studios');

INSERT INTO
    brand (id, name)
VALUES
    (3, 'Ader Error');

INSERT INTO
    brand (id, name)
VALUES
    (4, 'Anderson Bell');

INSERT INTO
    brand (id, name)
VALUES
    (5, 'Palm Angels');

-- Users --
CREATE TABLE users(
    id INTEGER NOT NULL UNIQUE,
    name TEXT NOT NULL,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    PRIMARY KEY(id AUTOINCREMENT)
);

-- password: monkey--
INSERT INTO
    users(id, name, username, password)
VALUES
    (
        1,
        'Kristin Kim',
        'kristin',
        '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.'
    );

INSERT INTO
    users(id, name, username, password)
VALUES
    (
        2,
        'Joe Kim',
        'joe',
        '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.'
    );

--- Sessions ---
CREATE TABLE sessions (
    id INTEGER NOT NULL UNIQUE,
    user_id INTEGER NOT NULL,
    session TEXT NOT NULL UNIQUE,
    last_login TEXT NOT NULL,
    PRIMARY KEY(id AUTOINCREMENT) FOREIGN KEY(user_id) REFERENCES users(id)
);

--- Groups ---
CREATE TABLE groups (
    id INTEGER NOT NULL UNIQUE,
    name TEXT NOT NULL UNIQUE,
    PRIMARY KEY(id AUTOINCREMENT)
);

INSERT INTO
    groups (id, name)
VALUES
    (1, 'admin');

--- Group Membership ---
CREATE TABLE user_groups (
    id INTEGER NOT NULL UNIQUE,
    user_id INTEGER NOT NULL,
    group_id INTEGER NOT NULL,
    PRIMARY KEY(id AUTOINCREMENT) FOREIGN KEY(group_id) REFERENCES groups(id),
    FOREIGN KEY(user_id) REFERENCES users(id)
);

-- User 'kristin' is a member of the 'user' group --
INSERT INTO
    user_groups(user_id, group_id)
VALUES
    (1, 1);

-- Clothing Type --
CREATE TABLE clothing_type(
    id INTEGER NOT NULL UNIQUE,
    name TEXT NOT NULL,
    PRIMARY KEY(id AUTOINCREMENT)
);

INSERT INTO
    clothing_type (id, name)
VALUES
    (1, 'Jackets');

INSERT INTO
    clothing_type (id, name)
VALUES
    (2, 'Jeans');

INSERT INTO
    clothing_type (id, name)
VALUES
    (3, 'Pants');

INSERT INTO
    clothing_type (id, name)
VALUES
    (4, 'Shirts');

INSERT INTO
    clothing_type (id, name)
VALUES
    (5, 'Shorts');

INSERT INTO
    clothing_type (id, name)
VALUES
    (6, 'Sweaters');

INSERT INTO
    clothing_type (id, name)
VALUES
    (7, 'Tops');

-- Wishlist --
CREATE TABLE wishlist (
    id INTEGER NOT NULL UNIQUE,
    brand_id INTEGER NOT NULL,
    clothing_type_id INTEGER NOT NULL,
    file_name TEXT NOT NULL,
    file_ext TEXT NOT NULL,
    FOREIGN KEY(brand_id) REFERENCES brands(id),
    FOREIGN KEY(clothing_type_id) REFERENCES clothing_type(id),
    PRIMARY KEY(id AUTOINCREMENT)
);

INSERT INTO
    wishlist(
        id,
        brand_id,
        clothing_type_id,
        file_name,
        file_ext
    )
VALUES
    (
        1,
        2,
        1,
        'ACNE STUDIOS Blue Destroyed Denim Jacket',
        'png'
    );

INSERT INTO
    wishlist(
        id,
        brand_id,
        clothing_type_id,
        file_name,
        file_ext
    )
VALUES
    (
        2,
        2,
        2,
        'ACNE STUDIOS Gray Loose Fit Jeans',
        'png'
    );

INSERT INTO
    wishlist(
        id,
        brand_id,
        clothing_type_id,
        file_name,
        file_ext
    )
VALUES
    (
        3,
        3,
        2,
        'ADER ERROR BLUE Embroidered Jeans',
        'png'
    );

INSERT INTO
    wishlist(
        id,
        brand_id,
        clothing_type_id,
        file_name,
        file_ext
    )
VALUES
    (
        4,
        3,
        2,
        'ADER ERROR Blue Paneled Jeans',
        'png'
    );

INSERT INTO
    wishlist(
        id,
        brand_id,
        clothing_type_id,
        file_name,
        file_ext
    )
VALUES
    (
        5,
        4,
        2,
        'ANDERSSON BELL Black Matthew Curved Jeans',
        'png'
    );

INSERT INTO
    wishlist(
        id,
        brand_id,
        clothing_type_id,
        file_name,
        file_ext
    )
VALUES
    (
        6,
        1,
        5,
        'NIKE Gray Cargo Shorts',
        'png'
    );

INSERT INTO
    wishlist(
        id,
        brand_id,
        clothing_type_id,
        file_name,
        file_ext
    )
VALUES
    (
        7,
        1,
        4,
        'NIKE Off-White Embroidered Long Sleeve T-Shirt',
        'png'
    );

INSERT INTO
    wishlist(
        id,
        brand_id,
        clothing_type_id,
        file_name,
        file_ext
    )
VALUES
    (
        8,
        5,
        4,
        'Palm Angels White Oversized T-Shirt',
        'png'
    );
