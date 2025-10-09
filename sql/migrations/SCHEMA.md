# Catering Packages Database Schema

## Overview
This migration adds normalized database tables to manage catering packages and their inclusion items, enabling full CRUD operations through the admin UI.

## Database Schema

### Table: `packages`
Stores the main package definitions.

| Column | Type | Description |
|--------|------|-------------|
| package_id | INT(11) AUTO_INCREMENT | Primary key |
| name | VARCHAR(255) | Package name (e.g., "Premium Wedding Package") |
| pax | INT(11) | Number of people (100, 150, 200) |
| price | DECIMAL(10,2) | Package price in PHP |
| type | VARCHAR(100) | Package type (default: 'catering') |
| description | TEXT | Package description |
| active | TINYINT(1) | Active flag (1=active, 0=inactive) |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

**Indexes:**
- PRIMARY KEY on `package_id`
- INDEX on `pax`
- INDEX on `active`

### Table: `package_items`
Stores individual inclusion items for each package.

| Column | Type | Description |
|--------|------|-------------|
| item_id | INT(11) AUTO_INCREMENT | Primary key |
| package_id | INT(11) | Foreign key to packages table |
| item_label | VARCHAR(255) | Item description (e.g., "Beef Menu", "Chairs with cover") |
| item_type | VARCHAR(50) | Item category (food, dessert, beverage, setup, equipment, furniture, tableware, staff) |
| qty_text | VARCHAR(100) | Optional quantity text (e.g., "100", "4", "100pcs") |
| sort_order | INT(11) | Display order (1-17) |
| notes | TEXT | Optional notes |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

**Indexes:**
- PRIMARY KEY on `item_id`
- INDEX on `package_id`
- INDEX on `sort_order`

**Foreign Keys:**
- `package_id` REFERENCES `packages(package_id)` ON DELETE CASCADE ON UPDATE CASCADE

## Seeded Data

### Packages (3 total)

| PAX | Name | Price | Description | Items |
|-----|------|-------|-------------|-------|
| 100 | Premium Wedding Package | ₱55,000 | A comprehensive wedding package for mid-sized receptions | 17 |
| 150 | Luxury Wedding Package | ₱78,000 | Ideal for medium to large events | 17 |
| 200 | Grand Wedding Package | ₱99,000 | Our largest package for grand events | 17 |

### Package Items (51 total across all packages)

Each package includes the following 17 items:

#### Food Items (5)
1. Beef Menu
2. Pork Menu
3. Chicken Menu
4. Rice
5. Veggies or Pasta or Fish Fillet

#### Desserts & Beverages (2)
6. Cups of Desserts (quantity varies by package: 100/100/200)
7. Drinks

#### Setup Items (4)
8. Backdrop and Platform / Complete Setup
9. Table Buffet w/ Skirting Setup
10. Cake and Gift Table w/ Skirting Designs
11. Elegant Table Buffet

#### Equipment (1)
12. Chaffing Dish w/ Food Heat Lamp (7 pieces)

#### Furniture (2)
13. Chairs with cover
14. Tables with cover

#### Tableware (2)
15. Pax Silverware, Glassware, and Dinnerware (100/150/200)
16. Serving Spoons (100pcs/150pcs/200pcs)

#### Staff (1)
17. Food Attendants (4/6/8 depending on package)

## Item Types Used

The following 8 item types categorize the inclusions:

- **food** - Main course items (beef, pork, chicken, rice, veggies/pasta/fish)
- **dessert** - Dessert items (cups of desserts)
- **beverage** - Drink items
- **setup** - Event setup elements (backdrop, buffet tables, decorations)
- **equipment** - Serving equipment (chaffing dishes, heat lamps)
- **furniture** - Tables and chairs
- **tableware** - Plates, utensils, glasses, serving spoons
- **staff** - Service personnel (food attendants)

## Admin Operations Enabled

With these tables in place, the admin UI can:

1. **Create** new packages with custom pax count and pricing
2. **Read** all packages and their items
3. **Update** package details (name, price, description, active status)
4. **Delete** packages (cascade deletes all associated items)
5. **Add** new items to existing packages
6. **Edit** item labels, types, quantities, and sort order
7. **Remove** items from packages
8. **Reorder** items by adjusting sort_order
9. **Filter** packages by active status or pax count
10. **Categorize** items using the item_type field

## Future Enhancements

### Optional: Link to Event Bookings

To associate packages with event bookings, you can add:

```sql
ALTER TABLE eventbookings ADD COLUMN eb_package_id INT(11) DEFAULT NULL;
ALTER TABLE eventbookings ADD CONSTRAINT fk_eb_package 
  FOREIGN KEY (eb_package_id) REFERENCES packages(package_id) 
  ON DELETE SET NULL ON UPDATE CASCADE;
```

This would allow:
- Selecting a specific package when creating an event booking
- Tracking which package was chosen for each event
- Generating reports on package popularity
- Calculating revenue per package

## Compliance

✅ **Forward-only migration** - No existing tables are altered or dropped  
✅ **MariaDB 10.4 compatible** - Uses standard SQL syntax  
✅ **Safe to run multiple times** - Uses `CREATE TABLE IF NOT EXISTS`  
✅ **No data loss** - Only adds new tables and data  
✅ **Referential integrity** - Foreign key constraints ensure data consistency  
✅ **Cascading deletes** - Removing a package automatically removes its items  

## Migration File Location

`sql/migrations/2025-10-09_create_packages_and_items.sql`

See `sql/migrations/README.md` for detailed instructions on running the migration.
