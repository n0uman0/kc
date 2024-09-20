-- up
CREATE TABLE categories (
    id VARCHAR(36) NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    parent_id VARCHAR(36),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    depth INT NOT NULL DEFAULT 1,
    FOREIGN KEY (parent_id) REFERENCES categories(id)
);

DELIMITER $$

CREATE PROCEDURE InsertCategory(
    IN category_id CHAR(36),
    IN category_name VARCHAR(255),
    IN category_description TEXT,
    IN parent_category_id CHAR(36)
)
BEGIN
    DECLARE parent_depth INT DEFAULT 0;

    IF parent_category_id IS NOT NULL THEN
        SELECT depth INTO parent_depth FROM categories WHERE id = parent_category_id;
        
        IF parent_depth IS NULL THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Parent category not found.';
        END IF;
        
        IF parent_depth + 1 > 4 THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Max category tree depth exceeded.';
        END IF;
    END IF;

    INSERT INTO categories (id, NAME, DESCRIPTION, parent_id, depth)
    VALUES (category_id, category_name, category_description, parent_category_id, parent_depth + 1);

END $$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE GetSingleCategoryCourseCount(IN categoryId VARCHAR(36))
BEGIN
    -- Use recursive CTE to build category hierarchy starting from the specified category ID
    WITH RECURSIVE category_hierarchy AS (
        -- Anchor: Start with the specified category
        SELECT 
            c.id,
            c.name,
            c.description,
            c.parent_id,
            c.created_at,
            c.updated_at,
            c.id AS root_category_id -- Set root category as itself initially
        FROM 
            categories c
        WHERE 
            c.id = categoryId -- Only start from the specified category

        UNION ALL

        -- Recursive: Join each category with its parent category
        SELECT 
            sub.id,
            sub.name,
            sub.description,
            sub.parent_id,
            sub.created_at,
            sub.updated_at,
            parent.root_category_id -- Root category is propagated down the hierarchy
        FROM 
            categories sub
        JOIN 
            category_hierarchy parent ON sub.parent_id = parent.id
    ),

    -- Subquery to count the courses for each category
    category_courses AS (
        SELECT 
            category_id, 
            COUNT(*) AS courses_count
        FROM 
            courses
        GROUP BY 
            category_id
    )

    -- Final query to sum course counts across all levels for the specified category and its subcategories
    SELECT 
        root_category_id AS id,
        root.name AS NAME,
        root.description,
        root.parent_id,
        root.created_at,
        root.updated_at,
        IFNULL(SUM(cc.courses_count), 0) AS count_of_courses -- Sum course counts for this category and its subcategories
    FROM 
        category_hierarchy ch
    -- Join with root category to get its info
    JOIN 
        categories root ON ch.root_category_id = root.id
    -- Left join course counts to aggregate counts
    LEFT JOIN 
        category_courses cc ON ch.id = cc.category_id
    GROUP BY 
        root_category_id, root.name, root.description, root.parent_id, root.created_at, root.updated_at;

END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE GetCategoriesWithCoursesCount()
BEGIN
    -- Use recursive CTE to build category hierarchy and aggregate course counts
    WITH RECURSIVE category_hierarchy AS (
        -- Anchor: Start with all categories
        SELECT 
            c.id,
            c.name,
            c.description,
            c.parent_id,
            c.created_at,
            c.updated_at,
            c.id AS root_category_id -- Set root category as itself initially
        FROM 
            categories c

        UNION ALL

        -- Recursive: Join each category with its parent category
        SELECT 
            sub.id,
            sub.name,
            sub.description,
            sub.parent_id,
            sub.created_at,
            sub.updated_at,
            parent.root_category_id -- Root category is propagated down the hierarchy
        FROM 
            categories sub
        JOIN 
            category_hierarchy parent ON sub.parent_id = parent.id
    ),

    -- Subquery to count the courses for each category
    category_courses AS (
        SELECT 
            category_id, 
            COUNT(*) AS courses_count
        FROM 
            courses
        GROUP BY 
            category_id
    )

    -- Final query to sum course counts across all levels for each root category
    SELECT 
        ch.root_category_id AS id,
        root.name AS NAME,
        root.description,
        root.parent_id,
        root.created_at,
        root.updated_at,
        IFNULL(SUM(cc.courses_count), 0) AS count_of_courses -- Sum course counts for each root and its subcategories
    FROM 
        category_hierarchy ch
    -- Join with root categories to get their info
    JOIN 
        categories root ON ch.root_category_id = root.id
    -- Left join course counts to aggregate counts
    LEFT JOIN 
        category_courses cc ON ch.id = cc.category_id
    GROUP BY 
        ch.root_category_id, root.name, root.description, root.parent_id, root.created_at, root.updated_at
    ORDER BY 
        root.name;

END$$

DELIMITER ;

CALL InsertCategory('1c2a3b4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 'Technology', NULL, NULL);
CALL InsertCategory('2c3d4e5f-6a7b-8c9d-0e1f-2a3b4c5d6e7f', 'Software Development', NULL, '1c2a3b4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d');
CALL InsertCategory('3d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f8a', 'Hardware Engineering 2', NULL, '2c3d4e5f-6a7b-8c9d-0e1f-2a3b4c5d6e7f');
CALL InsertCategory('3d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f82', 'Hardware Engineering 3', NULL, '3d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f8a');
CALL InsertCategory('4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b', 'Education', NULL, NULL);
CALL InsertCategory('5f6a7b8c-9d0e-1f2a-3b4c-5d6e7f8a9b0c', 'Higher Education', NULL, '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b');
CALL InsertCategory('6a7b8c9d-0e1f-2a3b-4c5d-6e7f8a9b0c1d', 'K-12 Education', NULL, '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b');
CALL InsertCategory('7b8c9d0e-1f2a-3b4c-5d6e-7f8a9b0c1d2e', 'Health & Wellness', NULL, NULL);
CALL InsertCategory('8c9d0e1f-2a3b-4c5d-6e7f-8a9b0c1d2e3f', 'Fitness & Nutrition', NULL, '7b8c9d0e-1f2a-3b4c-5d6e-7f8a9b0c1d2e');
CALL InsertCategory('9d0e1f2a-3b4c-5d6e-7f8a-9b0c1d2e3f4a', 'Mental Health', NULL, '7b8c9d0e-1f2a-3b4c-5d6e-7f8a9b0c1d2e');
CALL InsertCategory('0e1f2a3b-4c5d-6e7f-8a9b-0c1d2e3f4a5b', 'Arts & Entertainment', NULL, NULL);
CALL InsertCategory('1f2a3b4c-5d6e-7f8a-9b0c-1d2e3f4a5b6c', 'Visual Arts', NULL, '0e1f2a3b-4c5d-6e7f-8a9b-0c1d2e3f4a5b');
CALL InsertCategory('2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d', 'Performing Arts', NULL, '0e1f2a3b-4c5d-6e7f-8a9b-0c1d2e3f4a5b');
CALL InsertCategory('3b4c5d6e-7f8a-9b0c-1d2e-3f4a5b6c7d8e', 'Science & Nature', NULL, NULL);
CALL InsertCategory('4c5d6e7f-8a9b-0c1d-2e3f-4a5b6c7d8e9f', 'Biology', NULL, '3b4c5d6e-7f8a-9b0c-1d2e-3f4a5b6c7d8e');
CALL InsertCategory('5d6e7f8a-9b0c-1d2e-3f4a-5b6c7d8e9f0a', 'Physics', NULL, '3b4c5d6e-7f8a-9b0c-1d2e-3f4a5b6c7d8e');
CALL InsertCategory('6e7f8a9b-0c1d-2e3f-4a5b-6c7d8e9f0a1b', 'Food & Cooking', NULL, NULL);
CALL InsertCategory('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c', 'Recipes', NULL, '6e7f8a9b-0c1d-2e3f-4a5b-6c7d8e9f0a1b');
CALL InsertCategory('8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d', 'Culinary Techniques', NULL, '6e7f8a9b-0c1d-2e3f-4a5b-6c7d8e9f0a1b');
CALL InsertCategory('9b0c1d2e-3f4a-5b6c-7d8e-9f0a1b2c3d4e', 'Travel & Tourism', NULL, NULL);
CALL InsertCategory('0c1d2e3f-4a5b-6c7d-8e9f-0a1b2c3d4e5f', 'Destinations', NULL, '9b0c1d2e-3f4a-5b6c-7d8e-9f0a1b2c3d4e');
CALL InsertCategory('1d2e3f4a-5b6c-7d8e-9f0a-1b2c3d4e5f6a', 'Travel Tips', NULL, '9b0c1d2e-3f4a-5b6c-7d8e-9f0a1b2c3d4e');

-- down
DROP TABLE `categories`;
DROP PROCEDURE `InsertCategory`;