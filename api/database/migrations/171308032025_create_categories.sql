CREATE TABLE categories (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    parent_id CHAR(36),
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);