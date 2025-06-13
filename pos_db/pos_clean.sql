-- POS Database Clean Structure
-- Only POS-relevant tables and sample data

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Table: audits
CREATE TABLE `audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event` varchar(255) NOT NULL,
  `auditable_type` varchar(255) NOT NULL,
  `auditable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(1023) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `audits` VALUES (1, NULL, NULL, 'updated', 'App\\Models\\Acl\\ModuleModel', 7, '{\"module_category_ID\":12}', '{\"module_category_ID\":\"18\"}', 'http://127.0.0.1:8000/acl/module/edit/7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36', NULL, '2024-03-14 03:42:07', '2024-03-14 03:42:07');

-- Table: cache
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: migrations
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: password_reset_tokens
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: sessions
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` VALUES ('neStL8aNQQzILrKuKXWXliX8zYBgxovIME8HoV3y', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVWVsVndhYkZ0V013VTBBT0NZSlJGWk41SU1QSXFJYmxKRW13SldObCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MTp7aTowO3M6MjE6ImZsYXNoX3N1Y2Nlc3NfbWVzc2FnZSI7fXM6MzoibmV3IjthOjA6e319czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjYzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcmVzdW1lL2ltYWdlLWdhbGxlcnktc2VjdGlvbnMvZG93bmxvYWQvNTAiO31zOjIxOiJmbGFzaF9zdWNjZXNzX21lc3NhZ2UiO3M6MTc6IkRvd25sb2FkIHN0YXJ0ZWQuIjt9', 1746470481);

-- Table: tbl_admin
CREATE TABLE `tbl_admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT 1 COMMENT '0=No, 1=Yes',
  `remember_token` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `theme_color` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tbl_admin` VALUES (2, 'kashif.ali', 1, 'U2dEbelI44EME87IwlwPpAIiTxMQcNJ0H0dWdIZ2Ih3Zdvbyw5Ey0pDH2R5Y', '$2y$12$EJIXj0lYWTmU/7l6MGRjcuo8vPHWus9e4Excv4pdImoKV1UKf1OF2', NULL, '2021-11-16 13:01:46', '2025-01-06 12:34:48', 'maroon');

-- Table: tbl_admin_user_roles
CREATE TABLE `tbl_admin_user_roles` (
  `ID` int(10) UNSIGNED NOT NULL,
  `admin_ID` int(10) UNSIGNED DEFAULT NULL,
  `role_ID` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `tbl_admin_user_roles_role_id_foreign` (`role_ID`),
  KEY `tbl_admin_user_roles_admin_id_foreign` (`admin_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tbl_admin_user_roles` VALUES (41, 38, 1, '2024-08-29 18:06:35', '2024-08-29 18:06:35');

-- Table: tbl_categories
CREATE TABLE `tbl_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `added_by` varchar(50) NOT NULL,
  `date_added` date NOT NULL,
  `last_modified_by` varchar(50) DEFAULT NULL,
  `last_modified_date` date DEFAULT NULL,
  `category_icon` varchar(150) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tbl_categories` VALUES (8, 'kashif.ali', '2024-12-22', NULL, NULL, NULL, NULL, 1, '2024-12-22 00:08:51', '2024-12-22 00:08:51');

-- Table: tbl_categories_lang
CREATE TABLE `tbl_categories_lang` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT 0,
  `category_name` varchar(256) DEFAULT NULL,
  `meta_title` varchar(256) DEFAULT NULL,
  `meta_keywords` varchar(256) DEFAULT NULL,
  `meta_description` varchar(256) DEFAULT NULL,
  `page_url` varchar(164) DEFAULT NULL,
  `canonical_tag` varchar(164) DEFAULT NULL,
  `main_picture` varchar(164) DEFAULT NULL,
  `main_picture_alt_tag` varchar(256) DEFAULT NULL,
  `key_information` varchar(512) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `full_description` text DEFAULT NULL,
  `featured` enum('1','0') DEFAULT '0',
  `top_navigation` enum('1','0') DEFAULT '0',
  `footer_navigation` enum('1','0') DEFAULT '0',
  `left_navigation` enum('1','0') DEFAULT '0',
  `right_navigation` enum('1','0') DEFAULT '0',
  `added_by` varchar(50) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  `last_modified_time` datetime DEFAULT NULL,
  `status` enum('1','0') DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `tbl_categories_lang` VALUES (1, 8, 7, 0, 'Test Category', 'test-category', 'test-category', 'test-category', 'test-category', 'test-category', NULL, NULL, 'test-category', -2, NULL, '0', '0', '0', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2024-12-22 00:08:51', '2024-12-22 00:08:51');

-- Table: tbl_cities
CREATE TABLE `tbl_cities` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(128) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `status` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `tbl_cities` VALUES (1, 'Lahore', 1, 1, 1, '2019-02-08 18:22:46', '1');

-- Table: tbl_countries
CREATE TABLE `tbl_countries` (
  `country_id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `status` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `tbl_countries` VALUES (1, 'Pakistan', '2019-02-08 18:18:30', 1, '1');

-- Table: tbl_departments
CREATE TABLE `tbl_departments` (
  `id` int(11) NOT NULL,
  `department_name` varchar(80) DEFAULT NULL,
  `department_status` enum('Active','In-Active') DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_departments` VALUES (1, 'IT', 'In-Active', 'kashif.ali', NULL, NULL, '2024-03-14 09:10:08', '2024-12-22 04:52:35');

-- Table: tbl_designations
CREATE TABLE `tbl_designations` (
  `id` int(11) NOT NULL,
  `designation_name` varchar(80) DEFAULT NULL,
  `designation_status` enum('Active','In-Active') DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_designations` VALUES (1, 'HR Manager', 'Active', 'kashif.ali', NULL, NULL, '0000-00-00 00:00:00', '2024-12-22 04:53:20');

-- Table: tbl_employees
CREATE TABLE `tbl_employees` (
  `ID` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(100) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `report_to` varchar(150) DEFAULT NULL,
  `deletedBy` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `custom_photo` varchar(155) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `tbl_employees_employee_ad_id_unique` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tbl_employees` VALUES (1, 'kashif.ali', 'Kashif', 'Ali', 'kashifali.com', '0582897047', 1, 1, '', NULL, 2, 1, 0, 'user_management/13-11_300-1.jpg', NULL, '2020-05-31 13:30:19', '2024-08-29 20:11:53');

-- Table: tbl_languages
CREATE TABLE `tbl_languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(155) DEFAULT NULL,
  `lang` varchar(255) DEFAULT NULL,
  `native` varchar(255) DEFAULT NULL,
  `iso_code` varchar(10) DEFAULT NULL,
  `short_code` varchar(155) DEFAULT NULL,
  `is_rtl` tinyint(1) NOT NULL DEFAULT 0,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` varchar(50) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `icon` varchar(512) DEFAULT NULL,
  `status` enum('Active','In-Active') DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

INSERT INTO `tbl_languages` VALUES (6, 'Urdu', 'Urdu', 'عربى', 'ar', 'UR', 1, 0, 'kashif.ali', NULL, '2022-06-27 13:42:15', '2024-08-22 00:32:10', '6PTe45cte5ZdgiCTDVeeVrpoVkCG4yTZJUF5oGcS.png', 'Active', 'uae-flag.png');

-- Table: tbl_modules
CREATE TABLE `tbl_modules` (
  `ID` int(10) UNSIGNED NOT NULL,
  `module_category_ID` int(10) UNSIGNED DEFAULT NULL,
  `module_name` varchar(155) DEFAULT NULL,
  `route` varchar(155) DEFAULT NULL,
  `show_in_menu` tinyint(4) DEFAULT 1 COMMENT '0=No, 1=Yes',
  `css_class` varchar(100) DEFAULT NULL,
  `display_order` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `tbl_modules_module_category_id_foreign` (`module_category_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tbl_modules` VALUES (2, 6, 'Audit Log', 'audit-trail', 1, 'fa fa-history', 0, '2020-08-03 15:15:47', '2022-02-05 20:39:23');

-- Table: tbl_module_categories
CREATE TABLE `tbl_module_categories` (
  `ID` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(155) DEFAULT NULL,
  `icon` varchar(155) DEFAULT NULL,
  `display_order` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tbl_roles
CREATE TABLE `tbl_roles` (
  `ID` int(10) UNSIGNED NOT NULL,
  `role_name` varchar(155) DEFAULT NULL,
  `display_order` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tbl_roles` VALUES (1, 'Super Admin', 2, '2020-07-09 06:52:29', '2024-12-21 22:37:05');

-- Table: tbl_role_privileges
CREATE TABLE `tbl_role_privileges` (
  `ID` int(10) UNSIGNED NOT NULL,
  `role_ID` int(10) UNSIGNED DEFAULT NULL,
  `module_ID` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `tbl_role_privileges_role_id_foreign` (`role_ID`),
  KEY `tbl_role_privileges_module_id_foreign` (`module_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: users
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- POS Specific Tables

-- Table: pos_products
CREATE TABLE `pos_products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cost_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `alert_quantity` decimal(10,2) DEFAULT 0.00,
  `tax_rate` decimal(5,2) DEFAULT 0.00,
  `tax_type` enum('inclusive','exclusive') DEFAULT 'exclusive',
  `image` varchar(255) DEFAULT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_products_product_code_unique` (`product_code`),
  KEY `pos_products_category_id_foreign` (`category_id`),
  KEY `pos_products_unit_id_foreign` (`unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pos_product_units
CREATE TABLE `pos_product_units` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `short_name` varchar(20) NOT NULL,
  `base_unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `operator` varchar(10) DEFAULT NULL,
  `operation_value` decimal(10,2) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pos_product_units_base_unit_id_foreign` (`base_unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pos_customers
CREATE TABLE `pos_customers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `tax_number` varchar(50) DEFAULT NULL,
  `credit_limit` decimal(10,2) DEFAULT 0.00,
  `opening_balance` decimal(10,2) DEFAULT 0.00,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pos_customers_city_id_foreign` (`city_id`),
  KEY `pos_customers_country_id_foreign` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pos_sales
CREATE TABLE `pos_sales` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_no` varchar(50) NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_date` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash','card','bank','cheque') DEFAULT 'cash',
  `payment_status` enum('paid','partial','due') DEFAULT 'paid',
  `sale_status` enum('final','draft','cancelled') DEFAULT 'final',
  `note` text DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_sales_invoice_no_unique` (`invoice_no`),
  KEY `pos_sales_customer_id_foreign` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pos_sale_items
CREATE TABLE `pos_sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `tax_rate` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pos_sale_items_sale_id_foreign` (`sale_id`),
  KEY `pos_sale_items_product_id_foreign` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pos_purchases
CREATE TABLE `pos_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(50) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash','card','bank','cheque') DEFAULT 'cash',
  `payment_status` enum('paid','partial','due') DEFAULT 'paid',
  `purchase_status` enum('received','partial','pending','cancelled') DEFAULT 'received',
  `note` text DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_purchases_reference_no_unique` (`reference_no`),
  KEY `pos_purchases_supplier_id_foreign` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pos_purchase_items
CREATE TABLE `pos_purchase_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `tax_rate` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pos_purchase_items_purchase_id_foreign` (`purchase_id`),
  KEY `pos_purchase_items_product_id_foreign` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pos_suppliers
CREATE TABLE `pos_suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `tax_number` varchar(50) DEFAULT NULL,
  `credit_limit` decimal(10,2) DEFAULT 0.00,
  `opening_balance` decimal(10,2) DEFAULT 0.00,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pos_suppliers_city_id_foreign` (`city_id`),
  KEY `pos_suppliers_country_id_foreign` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pos_payments
CREATE TABLE `pos_payments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_no` varchar(50) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_type` enum('sale','purchase','customer','supplier') NOT NULL,
  `reference_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','bank','cheque') DEFAULT 'cash',
  `note` text DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_payments_payment_no_unique` (`payment_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pos_expenses
CREATE TABLE `pos_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `expense_category_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method` enum('cash','card','bank','cheque') DEFAULT 'cash',
  `reference` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pos_expenses_expense_category_id_foreign` (`expense_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pos_expense_categories
CREATE TABLE `pos_expense_categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_expense_categories_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add sample data for POS tables
INSERT INTO `pos_product_units` (`name`, `short_name`, `status`, `created_by`, `created_at`) VALUES
('Piece', 'PCS', 'active', 'kashif.ali', NOW()),
('Kilogram', 'KG', 'active', 'kashif.ali', NOW()),
('Liter', 'L', 'active', 'kashif.ali', NOW());

INSERT INTO `pos_expense_categories` (`name`, `code`, `status`, `created_by`, `created_at`) VALUES
('Rent', 'RENT', 'active', 'kashif.ali', NOW()),
('Utilities', 'UTIL', 'active', 'kashif.ali', NOW()),
('Salaries', 'SAL', 'active', 'kashif.ali', NOW()),
('Maintenance', 'MAINT', 'active', 'kashif.ali', NOW());

-- Sample Products
INSERT INTO `pos_products` (`product_code`, `name`, `description`, `category_id`, `unit_id`, `cost_price`, `selling_price`, `quantity`, `alert_quantity`, `tax_rate`, `status`, `created_by`, `created_at`) VALUES
('P001', 'Laptop', 'High-performance laptop', 8, 1, 800.00, 1000.00, 10.00, 2.00, 5.00, 'active', 'kashif.ali', NOW()),
('P002', 'Mouse', 'Wireless mouse', 8, 1, 10.00, 15.00, 50.00, 5.00, 5.00, 'active', 'kashif.ali', NOW()),
('P003', 'Keyboard', 'Mechanical keyboard', 8, 1, 30.00, 45.00, 20.00, 3.00, 5.00, 'active', 'kashif.ali', NOW());

-- Sample Customers
INSERT INTO `pos_customers` (`name`, `email`, `phone`, `address`, `city_id`, `country_id`, `tax_number`, `credit_limit`, `status`, `created_by`, `created_at`) VALUES
('John Doe', 'john@example.com', '1234567890', '123 Main St', 1, 1, 'TAX001', 1000.00, 'active', 'kashif.ali', NOW()),
('Jane Smith', 'jane@example.com', '0987654321', '456 Oak St', 1, 1, 'TAX002', 2000.00, 'active', 'kashif.ali', NOW()),
('Bob Wilson', 'bob@example.com', '5555555555', '789 Pine St', 1, 1, 'TAX003', 1500.00, 'active', 'kashif.ali', NOW());

-- Sample Suppliers
INSERT INTO `pos_suppliers` (`name`, `email`, `phone`, `address`, `city_id`, `country_id`, `tax_number`, `credit_limit`, `status`, `created_by`, `created_at`) VALUES
('Tech Supplies Inc', 'tech@example.com', '1112223333', '100 Tech Park', 1, 1, 'STAX001', 5000.00, 'active', 'kashif.ali', NOW()),
('Office Solutions', 'office@example.com', '4445556666', '200 Business Ave', 1, 1, 'STAX002', 3000.00, 'active', 'kashif.ali', NOW()),
('Global Electronics', 'global@example.com', '7778889999', '300 Trade Center', 1, 1, 'STAX003', 10000.00, 'active', 'kashif.ali', NOW());

-- Sample Sales
INSERT INTO `pos_sales` (`invoice_no`, `customer_id`, `sale_date`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `paid_amount`, `due_amount`, `payment_method`, `payment_status`, `sale_status`, `created_by`, `created_at`) VALUES
('INV001', 1, CURDATE(), 1000.00, 50.00, 0.00, 1050.00, 1050.00, 0.00, 'cash', 'paid', 'final', 'kashif.ali', NOW()),
('INV002', 2, CURDATE(), 1500.00, 75.00, 100.00, 1475.00, 1000.00, 475.00, 'card', 'partial', 'final', 'kashif.ali', NOW()),
('INV003', 3, CURDATE(), 2000.00, 100.00, 0.00, 2100.00, 2100.00, 0.00, 'bank', 'paid', 'final', 'kashif.ali', NOW());

-- Sample Sale Items
INSERT INTO `pos_sale_items` (`sale_id`, `product_id`, `quantity`, `unit_price`, `tax_rate`, `tax_amount`, `discount_amount`, `subtotal`) VALUES
(1, 1, 1.00, 1000.00, 5.00, 50.00, 0.00, 1000.00),
(2, 2, 2.00, 15.00, 5.00, 1.50, 10.00, 20.00),
(2, 3, 1.00, 45.00, 5.00, 2.25, 0.00, 45.00),
(3, 1, 2.00, 1000.00, 5.00, 100.00, 0.00, 2000.00);

-- Sample Purchases
INSERT INTO `pos_purchases` (`reference_no`, `supplier_id`, `purchase_date`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `paid_amount`, `due_amount`, `payment_method`, `payment_status`, `purchase_status`, `created_by`, `created_at`) VALUES
('PUR001', 1, CURDATE(), 800.00, 40.00, 0.00, 840.00, 840.00, 0.00, 'cash', 'paid', 'received', 'kashif.ali', NOW()),
('PUR002', 2, CURDATE(), 1200.00, 60.00, 50.00, 1210.00, 1000.00, 210.00, 'bank', 'partial', 'received', 'kashif.ali', NOW()),
('PUR003', 3, CURDATE(), 1600.00, 80.00, 0.00, 1680.00, 1680.00, 0.00, 'card', 'paid', 'received', 'kashif.ali', NOW());

-- Sample Purchase Items
INSERT INTO `pos_purchase_items` (`purchase_id`, `product_id`, `quantity`, `unit_price`, `tax_rate`, `tax_amount`, `discount_amount`, `subtotal`) VALUES
(1, 1, 1.00, 800.00, 5.00, 40.00, 0.00, 800.00),
(2, 2, 10.00, 10.00, 5.00, 5.00, 50.00, 100.00),
(2, 3, 5.00, 30.00, 5.00, 7.50, 0.00, 150.00),
(3, 1, 2.00, 800.00, 5.00, 80.00, 0.00, 1600.00);

-- Sample Payments
INSERT INTO `pos_payments` (`payment_no`, `payment_date`, `payment_type`, `reference_id`, `amount`, `payment_method`, `created_by`, `created_at`) VALUES
('PAY001', CURDATE(), 'sale', 1, 1050.00, 'cash', 'kashif.ali', NOW()),
('PAY002', CURDATE(), 'sale', 2, 1000.00, 'card', 'kashif.ali', NOW()),
('PAY003', CURDATE(), 'purchase', 1, 840.00, 'cash', 'kashif.ali', NOW());

-- Sample Expenses
INSERT INTO `pos_expenses` (`expense_category_id`, `amount`, `expense_date`, `payment_method`, `reference`, `note`, `created_by`, `created_at`) VALUES
(1, 1000.00, CURDATE(), 'bank', 'RENT001', 'Monthly rent payment', 'kashif.ali', NOW()),
(2, 500.00, CURDATE(), 'cash', 'UTIL001', 'Electricity bill', 'kashif.ali', NOW()),
(3, 2000.00, CURDATE(), 'bank', 'SAL001', 'Staff salaries', 'kashif.ali', NOW());

-- Add foreign key constraints
ALTER TABLE `pos_products`
  ADD CONSTRAINT `pos_products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `tbl_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pos_products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `pos_product_units` (`id`) ON DELETE SET NULL;

ALTER TABLE `pos_product_units`
  ADD CONSTRAINT `pos_product_units_base_unit_id_foreign` FOREIGN KEY (`base_unit_id`) REFERENCES `pos_product_units` (`id`) ON DELETE SET NULL;

ALTER TABLE `pos_customers`
  ADD CONSTRAINT `pos_customers_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `tbl_cities` (`city_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pos_customers_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `tbl_countries` (`country_id`) ON DELETE SET NULL;

ALTER TABLE `pos_sales`
  ADD CONSTRAINT `pos_sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `pos_customers` (`id`) ON DELETE SET NULL;

ALTER TABLE `pos_sale_items`
  ADD CONSTRAINT `pos_sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `pos_sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pos_sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `pos_products` (`id`) ON DELETE RESTRICT;

ALTER TABLE `pos_purchases`
  ADD CONSTRAINT `pos_purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `pos_suppliers` (`id`) ON DELETE SET NULL;

ALTER TABLE `pos_purchase_items`
  ADD CONSTRAINT `pos_purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `pos_purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pos_purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `pos_products` (`id`) ON DELETE RESTRICT;

ALTER TABLE `pos_suppliers`
  ADD CONSTRAINT `pos_suppliers_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `tbl_cities` (`city_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pos_suppliers_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `tbl_countries` (`country_id`) ON DELETE SET NULL;

ALTER TABLE `pos_expenses`
  ADD CONSTRAINT `pos_expenses_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `pos_expense_categories` (`id`) ON DELETE RESTRICT;

COMMIT;
