# TheZylpheonsFilament

A modern E-Commerce management system built with Laravel 12, Filament v3, and integrated with Spatie Permissions & Filament Shield for comprehensive role-based access control.

## 🚀 Features

### Core Functionality
- **Product Management** - Complete CRUD operations with category assignment, stock tracking, pricing, and product attributes (sizes, colors, features, materials)
- **Category Management** - Organized product categorization with automatic product counting
- **Customer Management** - Customer database with order history tracking and spending analytics
- **Order Management** - Full order lifecycle management with status tracking (Pending, Processing, Shipped, Completed, Cancelled)

### Advanced Features
- **Role-Based Access Control (RBAC)** - Powered by Spatie Laravel Permission with pre-configured roles:
  - Super Admin - Full system access
  - Owner - Complete management capabilities
  - Manager - Product, order, and customer management
  - Staff - Limited create and update permissions
  - Guest - View-only access
- **Permission Management** - Granular permission system with policy-based authorization
- **Dashboard Analytics** - Real-time statistics with interactive charts:
  - Orders trend analysis with time filters (Today, Week, Month, Year, All Time)
  - Revenue breakdown by order status
  - Overview statistics (Categories, Products, Customers, Orders)
- **Dynamic Filtering** - Advanced table filters for efficient data management
- **Relational Data** - Seamless navigation between related entities
- **Automatic Calculations** - Auto-calculating order totals and customer spending
- **Search & Sort** - Powerful search capabilities across all modules
- **Responsive UI** - Modern, mobile-friendly interface built with Tailwind CSS v4

### Security Features
- Multi-level authentication with Filament Shield
- Policy-based authorization for all resources
- Protected routes and actions based on user permissions
- Secure password hashing with bcrypt

## 📋 Requirements

- **PHP**: ^8.2
- **Composer**: Latest version
- **Node.js**: ^20.19.0 or >=22.12.0
- **NPM**: ^8.0.0
- **Database**: MySQL 8.0+ / MariaDB 10.3+ / PostgreSQL 13+ / SQLite 3.35+
- **Web Server**: Apache / Nginx / Laragon

### PHP Extensions Required
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- Fileinfo

## 🛠️ Installation

### 1. Clone the Repository

```bash
git clone <repository-url> TheZylpheonsFilament
cd TheZylpheonsFilament
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit your `.env` file with your database credentials:

**For MySQL/MariaDB:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=thezylpheonsfilament
DB_USERNAME=root
DB_PASSWORD=
```

**For PostgreSQL:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=thezylpheonsfilament
DB_USERNAME=postgres
DB_PASSWORD=
```

**For SQLite:**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

### 5. Create Database

**For MySQL (using Laragon or command line):**
```sql
CREATE DATABASE thezylpheonsfilament;
```

**For SQLite:**
```bash
touch database/database.sqlite
```

### 6. Run Migrations & Seeders

```bash
# Run migrations
php artisan migrate

# Seed database with roles, permissions, and sample users
php artisan db:seed
```

### 7. Build Assets

```bash
# Build for production
npm run build

# Or run development server with hot reload
npm run dev
```

### 8. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000/admin` to access the admin panel.

## 👥 Default User Credentials

After running the seeder, you can login with these accounts:

| Role | Email | Password | Permissions |
|------|-------|----------|-------------|
| Owner | ridhwan@example.com | password | Full Access |
| Manager | ahsan@example.com | password | Manage Products, Orders, Customers |
| Staff | rizal@example.com | password | View & Create Orders/Customers |
| Guest | rapip@example.com | password | View Only |

> ⚠️ **Important**: Change these passwords immediately in production!

## 🔧 Additional Commands

### Run Development Environment (Concurrent)
```bash
composer run dev
```
This will start:
- PHP Development Server
- Queue Worker
- Pail Logs
- Vite Dev Server

### Run Tests
```bash
composer test
# or
php artisan test
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Create New Admin User
```bash
php artisan make:filament-user
```

### Generate Permissions & Policies
```bash
php artisan shield:generate --all
```

## 📁 Project Structure

```
TheZylpheonsFilament/
├── app/
│   ├── Filament/
│   │   ├── Resources/      # Filament CRUD Resources
│   │   └── Widgets/        # Dashboard Widgets
│   ├── Models/             # Eloquent Models
│   └── Policies/           # Authorization Policies
├── database/
│   ├── migrations/         # Database Migrations
│   └── seeders/           # Database Seeders
├── resources/
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript Files
└── routes/
    └── web.php            # Web Routes
```

## 🎨 Customization

### Change Primary Color
Edit `app/Providers/Filament/AdminPanelProvider.php`:
```php
->colors([
    'primary' => Color::Amber, // Change this to your preferred color
])
```

### Add New Resource
```bash
php artisan make:filament-resource ModelName --generate
```

### Add New Widget
```bash
php artisan make:filament-widget WidgetName
```

## 🔐 Security Best Practices

1. Change all default passwords after installation
2. Set strong `APP_KEY` in production
3. Use HTTPS in production environment
4. Keep dependencies up to date: `composer update` and `npm update`
5. Review and adjust permissions based on your organization's needs
6. Enable CSRF protection (enabled by default)
7. Configure proper file permissions on storage and bootstrap/cache directories

## 🐛 Troubleshooting

### Permission Denied Errors
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Assets Not Loading
```bash
npm run build
php artisan storage:link
```

### Database Connection Issues
- Verify database credentials in `.env`
- Ensure database server is running
- Check firewall settings

### Composer Memory Limit
```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs/12.x)
- [Filament Documentation](https://filamentphp.com/docs/3.x)
- [Filament Shield Documentation](https://github.com/bezhanSalleh/filament-shield)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6)

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the issues page.

## 📝 Changelog

### Version 1.0.0
- Initial release
- Complete CRUD operations for Products, Categories, Customers, Orders
- Role-based access control with Filament Shield
- Dashboard analytics with charts
- Multi-user authentication system

---

Made with ❤️ using Laravel 12, Filament v3, and Spatie Permissions