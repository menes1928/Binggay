-- Common SQL Queries for Packages and Package Items
-- Use these queries to interact with the catering packages database

-- ========================================
-- SELECT QUERIES
-- ========================================

-- Get all active packages with item counts
SELECT 
    p.package_id,
    p.name,
    p.pax,
    p.price,
    p.description,
    COUNT(pi.item_id) as item_count,
    p.created_at
FROM packages p
LEFT JOIN package_items pi ON pi.package_id = p.package_id
WHERE p.active = 1
GROUP BY p.package_id
ORDER BY p.pax ASC;

-- Get a specific package with all its items
SELECT 
    p.package_id,
    p.name,
    p.pax,
    p.price,
    p.description,
    pi.item_id,
    pi.item_label,
    pi.item_type,
    pi.qty_text,
    pi.sort_order
FROM packages p
LEFT JOIN package_items pi ON pi.package_id = p.package_id
WHERE p.package_id = 1  -- Change this ID as needed
ORDER BY pi.sort_order ASC;

-- Get all items for a specific PAX count
SELECT 
    p.name as package_name,
    pi.item_label,
    pi.item_type,
    pi.qty_text
FROM packages p
JOIN package_items pi ON pi.package_id = p.package_id
WHERE p.pax = 100  -- Change to 150 or 200 as needed
ORDER BY pi.sort_order ASC;

-- Get items grouped by type for a package
SELECT 
    pi.item_type,
    GROUP_CONCAT(
        CONCAT(pi.item_label, 
            CASE 
                WHEN pi.qty_text IS NOT NULL THEN CONCAT(' (', pi.qty_text, ')')
                ELSE ''
            END
        ) 
        ORDER BY pi.sort_order 
        SEPARATOR ', '
    ) as items
FROM package_items pi
WHERE pi.package_id = 1  -- Change this ID as needed
GROUP BY pi.item_type;

-- ========================================
-- INSERT QUERIES
-- ========================================

-- Add a new package
INSERT INTO packages (name, pax, price, type, description, active) 
VALUES ('Custom Package', 250, 120000.00, 'catering', 'Custom large event package', 1);

-- Add items to the newly created package (get the package_id first)
SET @new_package_id = LAST_INSERT_ID();

INSERT INTO package_items (package_id, item_label, item_type, qty_text, sort_order) VALUES
(@new_package_id, 'Beef Menu', 'food', NULL, 1),
(@new_package_id, 'Pork Menu', 'food', NULL, 2),
(@new_package_id, 'Chicken Menu', 'food', NULL, 3);

-- ========================================
-- UPDATE QUERIES
-- ========================================

-- Update package price
UPDATE packages 
SET price = 58000.00, updated_at = CURRENT_TIMESTAMP
WHERE package_id = 1;

-- Update package active status
UPDATE packages 
SET active = 0, updated_at = CURRENT_TIMESTAMP
WHERE package_id = 1;

-- Update an item label
UPDATE package_items 
SET item_label = 'Premium Beef Menu', updated_at = CURRENT_TIMESTAMP
WHERE item_id = 1;

-- Reorder items (swap sort_order)
UPDATE package_items 
SET sort_order = 
    CASE item_id
        WHEN 1 THEN 2
        WHEN 2 THEN 1
    END,
    updated_at = CURRENT_TIMESTAMP
WHERE item_id IN (1, 2);

-- ========================================
-- DELETE QUERIES
-- ========================================

-- Delete a specific item (won't delete the package)
DELETE FROM package_items WHERE item_id = 1;

-- Delete a package (CASCADE will delete all its items automatically)
DELETE FROM packages WHERE package_id = 1;

-- Delete all inactive packages
DELETE FROM packages WHERE active = 0;

-- ========================================
-- ADMIN UI QUERIES
-- ========================================

-- Get packages for admin listing (with pagination)
SELECT 
    p.package_id,
    p.name,
    p.pax,
    p.price,
    p.active,
    COUNT(pi.item_id) as total_items,
    p.created_at,
    p.updated_at
FROM packages p
LEFT JOIN package_items pi ON pi.package_id = p.package_id
GROUP BY p.package_id
ORDER BY p.pax ASC
LIMIT 10 OFFSET 0;  -- Change OFFSET for pagination

-- Get package details for editing
SELECT * FROM packages WHERE package_id = 1;

-- Get all items for editing
SELECT * FROM package_items 
WHERE package_id = 1 
ORDER BY sort_order ASC;

-- ========================================
-- REPORTING QUERIES
-- ========================================

-- Get package statistics
SELECT 
    COUNT(*) as total_packages,
    SUM(CASE WHEN active = 1 THEN 1 ELSE 0 END) as active_packages,
    MIN(price) as min_price,
    MAX(price) as max_price,
    AVG(price) as avg_price
FROM packages;

-- Get item type distribution
SELECT 
    item_type,
    COUNT(*) as count,
    COUNT(DISTINCT package_id) as packages_with_type
FROM package_items
GROUP BY item_type
ORDER BY count DESC;

-- Get most expensive package with details
SELECT 
    p.name,
    p.pax,
    p.price,
    COUNT(pi.item_id) as total_items
FROM packages p
LEFT JOIN package_items pi ON pi.package_id = p.package_id
WHERE p.active = 1
GROUP BY p.package_id
ORDER BY p.price DESC
LIMIT 1;

-- ========================================
-- FUTURE: EVENT BOOKING INTEGRATION
-- ========================================

-- After adding eb_package_id to eventbookings table, use these queries:

-- Link an event booking to a package
-- UPDATE eventbookings 
-- SET eb_package_id = 1 
-- WHERE eb_id = 1;

-- Get event bookings with package details
-- SELECT 
--     eb.eb_id,
--     eb.eb_name,
--     eb.eb_date,
--     p.name as package_name,
--     p.pax as package_pax,
--     p.price as package_price
-- FROM eventbookings eb
-- LEFT JOIN packages p ON p.package_id = eb.eb_package_id
-- WHERE eb.eb_status = 'Pending';

-- Get most popular package (by bookings)
-- SELECT 
--     p.package_id,
--     p.name,
--     p.pax,
--     COUNT(eb.eb_id) as booking_count,
--     SUM(p.price) as total_revenue
-- FROM packages p
-- LEFT JOIN eventbookings eb ON eb.eb_package_id = p.package_id
-- GROUP BY p.package_id
-- ORDER BY booking_count DESC;
