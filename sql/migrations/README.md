# Database Migrations

This directory contains database migration scripts for the Binggay catering application.

## Running Migrations

### Migration: 2025-10-09_create_packages_and_items.sql

This migration creates the `packages` and `package_items` tables to support the catering packages feature.

**To run this migration:**

```bash
mysql -u your_username -p sandok < sql/migrations/2025-10-09_create_packages_and_items.sql
```

Or using MariaDB:

```bash
mariadb -u your_username -p sandok < sql/migrations/2025-10-09_create_packages_and_items.sql
```

**What this migration does:**

1. Creates the `packages` table to store package definitions (name, pax, price, type, description, active flag)
2. Creates the `package_items` table to store individual inclusion items for each package
3. Sets up a foreign key constraint with CASCADE delete from `package_items` to `packages`
4. Seeds three initial packages:
   - 100 PAX - ₱55,000 (Premium Wedding Package)
   - 150 PAX - ₱78,000 (Luxury Wedding Package)
   - 200 PAX - ₱99,000 (Grand Wedding Package)
5. Seeds all inclusion items for each package based on the current website content

**Database Compatibility:**
- MariaDB 10.4+
- MySQL 5.7+

**Note:** This migration uses `CREATE TABLE IF NOT EXISTS` to prevent errors if tables already exist. It's safe to run multiple times.

## Future Linking

To link packages to event bookings in the future, you can add a foreign key column to the `eventbookings` table:

```sql
ALTER TABLE eventbookings ADD COLUMN eb_package_id INT(11) DEFAULT NULL;
ALTER TABLE eventbookings ADD CONSTRAINT fk_eb_package 
  FOREIGN KEY (eb_package_id) REFERENCES packages(package_id) 
  ON DELETE SET NULL ON UPDATE CASCADE;
```

This is intentionally not included in this migration to keep changes minimal and safe.
