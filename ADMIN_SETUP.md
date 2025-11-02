# 🚀 Admin Authentication Setup

This document explains how to use the separate admin authentication system for your Filament admin panel.

## 📋 Admin Accounts

### Default Admin Accounts Created:

#### 🎯 Super Admin
- **Email:** `admin@spaceportfolio.com`
- **Password:** `password`
- **Permissions:** Full access to all features
  - `manage_users` - Manage user accounts
  - `manage_blogs` - Manage blog posts
  - `manage_projects` - Manage projects
  - `manage_services` - Manage services
  - `manage_contacts` - Manage contact messages
  - `manage_settings` - Manage system settings
  - `view_analytics` - View analytics and reports
  - `manage_admins` - Manage other admin accounts

#### 📝 Content Manager
- **Email:** `content@spaceportfolio.com`
- **Password:** `password`
- **Permissions:** Content management only
  - `manage_blogs` - Manage blog posts
  - `manage_projects` - Manage projects
  - `manage_services` - Manage services
  - `view_analytics` - View analytics and reports

#### 🎧 Support Admin
- **Email:** `support@spaceportfolio.com`
- **Password:** `password`
- **Permissions:** Support features only
  - `manage_contacts` - Manage contact messages
  - `view_analytics` - View analytics and reports

## 🔐 Accessing the Admin Panel

1. **URL:** `http://your-domain.com/admin`
2. **Login:** Use any of the admin accounts above
3. **Dashboard:** You'll see the Filament admin dashboard with your space theme

## 🛠️ Admin Panel Features

### 🎨 Space-Themed Design
- **Cyan & Purple colors** matching your portfolio theme
- **Dark background** with gradient effects
- **Sidebar navigation** with collapsible menu
- **Modern UI components** consistent with your brand

### 📊 Resources Available
- **Blogs** - Manage blog posts, categories, and content
- **Projects** - Manage portfolio projects and showcases
- **Services** - Manage service offerings
- **Contacts** - View and manage contact messages

### 🔧 Security Features
- **Separate authentication** from regular users
- **Role-based permissions** system
- **Session-based authentication**
- **Password hashing** with Laravel's built-in security

## 🚀 Adding New Admins

### Method 1: Using Tinker
```bash
php artisan tinker
```
```php
use App\Models\Admin;

Admin::create([
    'name' => 'New Admin Name',
    'email' => 'newadmin@example.com',
    'password' => Hash::make('secure_password'),
    'is_active' => true,
    'permissions' => ['manage_blogs', 'manage_projects'],
]);
```

### Method 2: Create a Seeder
Create a new seeder or update the existing `AdminSeeder.php` file.

## 🔑 Important Security Notes

⚠️ **CHANGE DEFAULT PASSWORDS** immediately after setup!

1. **Change passwords** for all default admin accounts
2. **Use strong passwords** with uppercase, lowercase, numbers, and symbols
3. **Limit admin access** to only necessary personnel
4. **Regular security audits** of admin accounts

## 🗂️ Database Structure

The `admins` table includes:
- `id` - Primary key
- `name` - Admin's full name
- `email` - Unique email address
- `password` - Hashed password
- `avatar` - Profile picture URL (optional)
- `is_active` - Account status (active/inactive)
- `permissions` - JSON array of permissions
- `email_verified_at` - Email verification timestamp
- `remember_token` - "Remember me" token
- `created_at`, `updated_at`, `deleted_at` - Timestamps

## 🔄 Customization Options

### Adding New Permissions
1. Add permission to the `permissions` array in AdminSeeder
2. Check permissions in your code using: `$admin->hasPermission('permission_name')`

### Customizing Admin Panel
- **Panel Provider:** `app/Providers/Filament/AdminPanelProvider.php`
- **Colors:** Change primary/secondary colors in the panel provider
- **Branding:** Update brand name, logo, and favicon

### Permission Checking
```php
// In your controllers or middleware
if (auth('admin')->check() && auth('admin')->user()->hasPermission('manage_blogs')) {
    // Allow access
}
```

## 📞 Support

If you encounter any issues:
1. Clear caches: `php artisan config:clear && php artisan cache:clear`
2. Check logs: `storage/logs/laravel.log`
3. Verify admin guard configuration in `config/auth.php`

## 🎯 Next Steps

1. ✅ Change default passwords
2. ✅ Test admin panel functionality
3. ✅ Customize permissions as needed
4. ✅ Set up proper admin workflows
5. ✅ Configure backup and recovery

---

**🚀 Your Space Portfolio Admin Panel is ready to use!**