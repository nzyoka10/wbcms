## Normalized Database Design

- Database name: **``wbcmd_bd``**

### ``Tables``

1. **``Users Table``**
    - user_id (PK) - `INT` 
    - username - `VARCHAR(50)`
    - password - `VARCHAR(255)`
    - email - `VARCHAR(100)`
    - phone - `VARCHAR(20)`
    - address - `TEXT`
    - role - ``ENUM`` (*'customer', 'admin'*) `DEFAULT` *'customer'*
    - `created_at`, `updated_at` (*TIMESTAMP*)

2. **``Meters Table``**
    - meter_id (PK)
    - `user_id (FK)`
    - meter_number
    - installation_date
    - status

3. **``Meter_Readings Table``**
    - reading_id (PK)
    - `meter_id (FK)`
    - reading_date
    - reading_value
    - created_at

4. **``Invoices table``**
    - invoice_id (PK)
    - `user_id (FK)`
    - `meter_id (FK)`
    - billing_date
    - due_date
    - amount
    - status
    - created_at
    - updated_at

5. **``Payments Table``**
    - payment_id (PK)
    - `invoice_id (FK)`
    - payment_date
    - amount
    - payment_method
    - transaction_id
    - status

6. **``Notifications table``**
    - notification_id (PK)
    - `user_id (FK)`
    - type
    - message
    - sent_at
    - status

7. **``Reports Table``**
    - report_id (PK)
    - report_type
    - generated_at
    - report_data

#### `Table Relationships`
- Each User can have multiple Meters.
- Each Meter can have multiple Meter_Readings.
- Each User can have multiple Invoices.
- Each Invoice is associated with one Meter and one User.
- Each Invoice can have multiple Payments.
- Each User can have multiple Notifications.
- Each Report is a standalone entity representing various types of reports.
