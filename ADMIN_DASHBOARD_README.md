# Admin Dashboard Implementation Guide

## Overview
A complete, secure admin dashboard for Guillaume Housing with separate `/admin` section featuring authentication, property management, user administration, booking management, analytics, and CSV export capabilities.

## Features Implemented

### 1. Authentication & Security
- **Session-based authentication** with `$_SESSION['user_role']`
- **AuthController** for login/logout with password verification
- **Admin middleware** (`requireAdmin()`) protecting all admin routes
- **Security helpers** with CSRF token generation, input sanitization, and image file validation
- Password hashing with PHP's `password_hash()` (BCRYPT)

### 2. Admin Routes & Layout
- **Protected routes** at `/admin/*` requiring admin role
- **Sidebar navigation** with links to all admin sections
- **Responsive layout** with dark sidebar and main content area
- **Top bar** showing logged-in admin name and logout link

### 3. Property Management
- **Create/Edit/Delete** properties with full details (title, description, price, location, bedrooms, bathrooms, area, type, status)
- **Multiple image uploads** to `images/properties/` folder with filesystem storage
- **Featured properties** toggle with `is_featured` flag in database
- **Image management** via `images` table with primary image support
- **Server-side validation** for file uploads (MIME type, size limits)

### 4. User Management
- **List all users** with name, email, phone, role, and join date
- **Promote/demote users** between admin and user roles
- **Delete users** (with confirmation)
- **Role-based display** with colored badges

### 5. Booking Management
- **List all bookings** with property, dates, guests, and total price
- **Filter by status** (pending, confirmed, cancelled, completed)
- **Update booking status** via dropdown
- **Delete bookings** with confirmation

### 6. Messages/Inquiries
- **List all contact form submissions** with sender details
- **View full message** content in popup
- **Delete messages** with confirmation
- Table shows message preview, sender email, and date

### 7. Analytics & Reports
- **Dashboard statistics** (total properties, bookings, messages, users)
- **Revenue analytics** (total revenue, average booking value)
- **Occupancy rate** calculation
- **30-day bookings timeline** for trend visualization
- **Chart.js integration** for data visualization
- **CSV export** for bookings and properties data

### 8. Admin Settings
- Placeholder for site configuration (site name, contact info, maintenance mode)

## Database Schema Updates

### Required Migration
Run the following SQL to add the `is_featured` column:

```sql
ALTER TABLE properties ADD COLUMN is_featured BOOLEAN DEFAULT FALSE AFTER status;
CREATE INDEX idx_featured ON properties(is_featured);
ALTER TABLE images MODIFY file_path VARCHAR(500) NOT NULL;
ALTER TABLE images ADD INDEX idx_property (property_id);
ALTER TABLE images ADD INDEX idx_primary (is_primary);
```

Or use the provided migration file: `add_is_featured_column.sql`

### Updated Tables
- **properties**: Added `is_featured` boolean column
- **images**: Existing table used for storing property images
- **users**: Already has `role` column (ENUM: 'user', 'admin')

## How to Use

### 1. Access Admin Area
- Navigate to: `http://localhost/GuillaumeHousing/admin`
- If not logged in, you'll be redirected to: `http://localhost/GuillaumeHousing/admin/login`

### 2. Login
- **Email**: admin@guillaumehousing.com
- **Password**: (must match the hash in your database)
- If password doesn't work, update it using SQL or reset script

### 3. Reset Admin Password
```php
// Generate a new hash
$hash = password_hash('newpassword', PASSWORD_BCRYPT);

// Update in MySQL:
UPDATE users SET password = '$hash' WHERE email = 'admin@guillaumehousing.com';
```

### 4. Navigate Dashboard
Once logged in, use the sidebar to access:
- **Dashboard**: Quick overview of statistics
- **Properties**: Add, edit, delete, and feature properties
- **Users**: Manage user roles and permissions
- **Bookings**: Track and update booking statuses
- **Messages**: View and manage contact inquiries
- **Analytics**: View reports and export data
- **Settings**: Site configuration

## File Structure

```
/controllers/
  - AdminController.php       (All admin logic and data retrieval)
  - AuthController.php        (Login/logout handling)
  - PropertyController.php    (Enhanced with image upload support)

/models/
  - Property.php              (Enhanced with is_featured and image methods)
  - User.php                  (Extended with verifyCredentials method)
  - Booking.php               (No changes needed)
  - Message.php               (No changes needed)

/views/admin/
  - login.php                 (Admin login form)
  - header.php                (Top bar with logout)
  - sidebar.php               (Navigation menu)
  - footer.php                (Closing tags)
  - dashboard.php             (Statistics overview)
  - properties.php            (Property list and management)
  - property_form.php         (Create/edit property form)
  - users.php                 (User list and role management)
  - bookings.php              (Booking list with filters)
  - messages.php              (Message list and viewer)
  - analytics.php             (Reports and charts)
  - settings.php              (Site settings)

/helpers/
  - auth.php                  (isLoggedIn, isAdmin, requireAdmin)
  - security.php              (CSRF tokens, input sanitization, file validation)

/images/
  - properties/               (Uploaded property images)
```

## API Endpoints

### Authentication
- `POST /admin/login` - Login with email/password
- `GET /admin/logout` - Logout and destroy session

### Admin Pages (all require admin role)
- `GET /admin` - Dashboard
- `GET /admin/properties` - Properties management
- `GET /admin/users` - Users management
- `GET /admin/bookings` - Bookings management
- `GET /admin/messages` - Messages management
- `GET /admin/analytics` - Analytics and reports
- `GET /admin/settings` - Site settings

### Admin API Endpoints
- `GET /api/admin/stats` - Dashboard statistics
- `GET /api/admin/users` - List all users (JSON)
- `POST /api/admin/user/role/{id}` - Update user role
- `POST /api/admin/user/delete/{id}` - Delete user
- `GET /api/admin/analytics` - Detailed analytics data
- `GET /api/admin/export/bookings` - Download bookings CSV
- `GET /api/admin/export/properties` - Download properties CSV

### Property API (admin-protected)
- `POST /api/property/featured/{id}` - Toggle featured status
- `GET /api/properties` - List properties (available to public)

## Security Features

1. **Authentication**: Session-based login with password verification
2. **Authorization**: Admin role required for all admin routes
3. **Input Validation**: 
   - Email validation
   - File type/size validation (images only, max 5MB)
   - Input sanitization and escaping
4. **Prepared Statements**: All database queries use prepared statements
5. **CSRF Protection**: Helper functions for token generation/verification
6. **Password Hashing**: BCRYPT hashing for all user passwords

## Image Upload

- **Location**: `/images/properties/` folder
- **Allowed types**: JPG, PNG, GIF, WebP
- **Max size**: 5MB per file
- **Multiple uploads**: Supported, first image is primary
- **Storage**: Filesystem + database record in `images` table

## Testing Checklist

- [ ] Login with admin credentials
- [ ] View dashboard and verify statistics load
- [ ] Create new property with multiple image uploads
- [ ] Edit property and verify is_featured flag
- [ ] Delete property
- [ ] List users and update roles
- [ ] View bookings and filter by status
- [ ] Update booking status
- [ ] View and delete messages
- [ ] View analytics and export data
- [ ] Logout and verify redirect
- [ ] Test login with wrong password
- [ ] Test accessing `/admin` without login (should redirect)

## Future Enhancements

- CSRF tokens in forms (partially implemented)
- Two-factor authentication
- Admin activity logging
- Email notifications for new bookings/messages
- Bulk operations (delete multiple, CSV import)
- Advanced analytics with date ranges
- Property templates
- Automated backups

## Notes

- The admin dashboard reuses the site's base layout for visual consistency
- All admin pages are protected by `requireAdmin()` middleware
- Image uploads are server-side validated
- CSV exports are generated on-the-fly
- The analytics endpoint calculates metrics in real-time
- Sidebar is sticky for easy navigation while scrolling

---

**Last Updated**: June 12, 2026
**Version**: 1.0
