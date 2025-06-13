# POS (Point of Sale) System Documentation

## Table of Contents
1. [Overview](#overview)
2. [Features](#features)
3. [System Requirements](#system-requirements)
4. [Installation](#installation)
5. [Database Structure](#database-structure)
6. [User Roles and Permissions](#user-roles-and-permissions)
7. [Modules and Functionality](#modules-and-functionality)
8. [API Documentation](#api-documentation)
9. [Security Features](#security-features)
10. [Troubleshooting](#troubleshooting)

## Overview
The POS system is a comprehensive point of sale solution designed for retail businesses. It provides a complete set of features for managing sales, inventory, customers, suppliers, and financial transactions.

## Features
- **Product Management**
  - Product creation and categorization
  - Stock management with alerts
  - Unit management
  - Price management with tax support
  - Barcode support

- **Sales Management**
  - Quick sales processing
  - Multiple payment methods
  - Tax calculation
  - Discount management
  - Sales history and reports
  - Invoice generation

- **Purchase Management**
  - Supplier management
  - Purchase order creation
  - Stock receiving
  - Purchase history and reports

- **Customer Management**
  - Customer profiles
  - Credit management
  - Purchase history
  - Customer loyalty tracking

- **Inventory Management**
  - Stock tracking
  - Low stock alerts
  - Stock adjustments
  - Stock transfer between locations

- **Financial Management**
  - Payment tracking
  - Expense management
  - Financial reports
  - Tax reports

- **User Management**
  - Role-based access control
  - User activity logging
  - Audit trails

## System Requirements
- PHP 8.0 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer for PHP dependencies
- Node.js and NPM for frontend dependencies

## Installation
1. Clone the repository:
   ```bash
   git clone [repository-url]
   cd pos-system
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install frontend dependencies:
   ```bash
   npm install
   ```

4. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure database in .env file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pos_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. Run database migrations:
   ```bash
   php artisan migrate
   ```

7. Seed the database:
   ```bash
   php artisan db:seed
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

## Database Structure

### Core Tables
- `users` - System users
- `tbl_admin` - Admin users
- `tbl_roles` - User roles
- `tbl_modules` - System modules
- `tbl_role_privileges` - Role permissions

### POS Tables
- `pos_products` - Product information
- `pos_product_units` - Product units
- `pos_customers` - Customer information
- `pos_sales` - Sales transactions
- `pos_sale_items` - Sales line items
- `pos_purchases` - Purchase transactions
- `pos_purchase_items` - Purchase line items
- `pos_suppliers` - Supplier information
- `pos_payments` - Payment records
- `pos_expenses` - Expense records
- `pos_expense_categories` - Expense categories

## User Roles and Permissions

### Super Admin
- Full system access
- User management
- Role management
- System configuration

### Admin
- Product management
- Customer management
- Sales management
- Reports access

### Cashier
- Process sales
- View products
- View customers
- Basic reports

### Stock Manager
- Inventory management
- Purchase management
- Stock reports

## Modules and Functionality

### 1. Dashboard
- Sales overview
- Stock alerts
- Recent transactions
- Key performance indicators

### 2. Products
- Product listing
- Add/Edit products
- Stock management
- Category management
- Unit management

### 3. Sales
- New sale
- Sales history
- Sales reports
- Customer invoices

### 4. Purchases
- New purchase
- Purchase history
- Supplier management
- Stock receiving

### 5. Customers
- Customer listing
- Add/Edit customers
- Customer history
- Credit management

### 6. Reports
- Sales reports
- Purchase reports
- Inventory reports
- Financial reports
- Tax reports

### 7. Settings
- System configuration
- User management
- Role management
- Tax settings
- Payment methods

## API Documentation

### Authentication
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password"
}
```

### Products
```http
GET /api/products
GET /api/products/{id}
POST /api/products
PUT /api/products/{id}
DELETE /api/products/{id}
```

### Sales
```http
GET /api/sales
POST /api/sales
GET /api/sales/{id}
PUT /api/sales/{id}
```

### Customers
```http
GET /api/customers
POST /api/customers
GET /api/customers/{id}
PUT /api/customers/{id}
```

## Security Features
- Role-based access control
- Password hashing
- API authentication
- Input validation
- XSS protection
- CSRF protection
- SQL injection prevention
- Audit logging

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in .env
   - Verify database server is running
   - Check database user permissions

2. **Permission Issues**
   - Verify user role permissions
   - Check file permissions
   - Clear cache: `php artisan cache:clear`

3. **Stock Calculation Errors**
   - Verify stock transactions
   - Check for duplicate entries
   - Run stock reconciliation

4. **Payment Processing Issues**
   - Verify payment gateway configuration
   - Check transaction logs
   - Verify customer credit limits

### Support
For technical support, please contact:
- Email: support@example.com
- Phone: +1-234-567-8900
- Support Hours: 9 AM - 5 PM EST

## License
This project is licensed under the MIT License - see the LICENSE file for details.
