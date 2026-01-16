# Admin UI Guide

## Overview
This guide explains how to use the administrative interfaces for managing users, roles, and permissions in the DataJemaat application.

## Table of Contents
1. [Accessing the Admin Panel](#accessing-the-admin-panel)
2. [User Management](#user-management)
3. [Role Management](#role-management)
4. [Default Credentials](#default-credentials)
5. [Security Best Practices](#security-best-practices)

---

## Accessing the Admin Panel

The Admin panel is **only accessible to users with the Superadmin role**.

### Admin Menu Location
After logging in as a superadmin, you'll find the Admin menu in the left sidebar with the following sections:
- **Lingkungan** - Manage church communities
- **Manage Users** - User administration
- **Manage Roles** - Role and permissions management
- **Pending Approvals** - Review and approve draft submissions (shows badge count)

---

## User Management

### Viewing All Users
1. Navigate to **Admin > Manage Users**
2. You'll see a table with:
   - User ID
   - Name
   - Email
   - Assigned Roles (as badges)
   - Account Creation Date
   - Edit Action Button

### Editing User Information
1. Click the **Edit** button next to any user
2. You can modify:
   - **Name** - User's full name
   - **Email** - User's email address (must be unique)
   - **Roles** - Assign or remove roles using checkboxes

### Assigning Lingkungan to Users
When you assign the **Lingkungan Admin** role to a user:
1. A "Assigned Lingkungan" section will appear automatically
2. Select which lingkungan(s) the user can manage
3. This determines which church communities they can access

**Note:** The lingkungan section only appears when the "Lingkungan Admin" role is selected.

### Resetting User Passwords
1. On the user edit page, scroll to the "Reset Password" section
2. Enter a new password (minimum 8 characters)
3. Confirm the password
4. Click **Reset Password**

The user will be able to log in with the new password immediately.

---

## Role Management

### Viewing All Roles
1. Navigate to **Admin > Manage Roles**
2. You'll see a table showing:
   - Role Name
   - Slug (internal identifier)
   - Description
   - Number of assigned users
   - Number of permissions
   - System badge (if it's a protected system role)
   - Edit/Delete actions

### Creating a New Role
1. Click **Create New Role** button
2. Fill in the form:
   - **Name** - Display name (e.g., "Data Entry Clerk")
   - **Slug** - URL-friendly identifier (e.g., "data-entry-clerk")
   - **Description** - Brief explanation of the role's purpose
3. Select permissions by module:
   - Permissions are grouped by module (jemaat, simpatisan, laporan, etc.)
   - Check the boxes for permissions you want to grant
4. Click **Create Role**

### Editing a Role
1. Click the **Edit** button next to any role
2. Modify name, slug, description, or permissions
3. Click **Update Role**

**Important:** System roles (marked with "System" badge) cannot be modified to prevent breaking core functionality.

### Deleting a Role
1. Click the **Delete** button next to a custom role
2. Confirm the deletion when prompted

**Restrictions:**
- System roles cannot be deleted
- Roles with assigned users cannot be deleted (reassign users first)

### Available Permission Modules

Permissions are organized by these modules:
- **jemaat** - Church member data management
- **simpatisan** - Sympathizer data management
- **laporan** - Reports and statistics
- **export** - Data export capabilities

Each module has permissions like:
- `view` - View data
- `create` - Create new records
- `update` - Edit existing records
- `delete` - Delete records
- `export` - Export data

---

## Default Credentials

### Initial Superadmin Account
After running the database seeders, a default superadmin account is created:

**Email:** `admin@datajemaat.com`
**Password:** `password`

**CRITICAL SECURITY WARNING:**
Change this password immediately after first login!

### Running the Seeder
If the superadmin account doesn't exist, run:
```bash
composer install
php artisan db:seed --class=SuperAdminUserSeeder
```

---

## Security Best Practices

### Password Requirements
- Minimum 8 characters
- Change default passwords immediately
- Use strong, unique passwords

### Role Assignment Best Practices
1. **Principle of Least Privilege**: Only grant permissions users actually need
2. **Regular Audits**: Periodically review user roles and remove unnecessary access
3. **Separate Duties**: Don't assign all permissions to every user
4. **System Roles**: Never delete or modify system roles

### Lingkungan Assignment
- Only assign lingkungan to users who genuinely need localized access
- Superadmins have access to all lingkungan by default
- Review lingkungan assignments when users change positions

### Monitoring
- Track who has superadmin access
- Limit the number of superadmin accounts
- Review the user list regularly to ensure no unauthorized accounts exist

---

## Common Tasks

### Creating a New Staff Member Account
1. Have an admin create the user account (via registration or direct creation)
2. Go to **Admin > Manage Users**
3. Find and edit the new user
4. Assign appropriate roles (e.g., SNK, Lingkungan Admin)
5. If Lingkungan Admin, assign their specific lingkungan
6. Save changes

### Removing User Access
1. Go to **Admin > Manage Users**
2. Edit the user
3. Uncheck all roles
4. Save changes

**Note:** You cannot delete user accounts, only remove their access by removing roles.

### Creating a Custom Role for Specific Tasks
Example: Creating a "Report Viewer" role:
1. Go to **Admin > Manage Roles**
2. Click **Create New Role**
3. Name: "Report Viewer"
4. Slug: "report-viewer"
5. Description: "Can only view reports and statistics"
6. Select only these permissions:
   - jemaat.view
   - laporan.view
7. Save the role

Now you can assign this role to users who only need read-only report access.

---

## Troubleshooting

### "Cannot access admin panel"
- Ensure you're logged in as a user with the superadmin role
- Check that the role middleware is working correctly

### "Lingkungan section not showing"
- Make sure you've selected the "Lingkungan Admin" checkbox
- The JavaScript toggle may need a page refresh

### "Cannot delete role"
- Check if users are still assigned to the role
- System roles cannot be deleted

### "Permission denied" errors
- Verify the user has the correct permissions assigned via their roles
- Check that the role has the necessary permissions attached

---

## Support

For technical issues or questions:
- Check the main documentation in `.kanban/docs/`
- Review the codebase structure
- Contact your system administrator
