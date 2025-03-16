CREATE TABLE courses (
    course_id VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_preview VARCHAR(255),
    category_id CHAR(36) NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);