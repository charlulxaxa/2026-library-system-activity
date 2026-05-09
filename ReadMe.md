# Student Library Management System

A refactored object-oriented PHP application for managing library operations such as book management, borrowing/returning records, and overdue fine computation.

The system follows PSR-12 coding standards and clean architecture principles (Entity–Repository–Service pattern).

---

## 👨‍💻 Author
- Charlo Marco

---

## ⚙️ Requirements
- PHP 8.0 or higher  
- MySQL 5.7 or higher  
- Composer  
- Git  
- XAMPP / Apache server (recommended for local development)

---

## 📦 Installation

## Installation
1. Clone the repository:
```bash
git clone https://github.com/charlulxaxa/2026-library-system-activity
2. Import `LibraryDatabase.sql` into MySQL
3. Copy `.env.example` to `.env` and configure database credentials
4. Run `composer install` (if dependencies exist)

src/
├── Entity/         # Data models (Book, BorrowRecord, Student)
├── Repository/     # Database access layer (CRUD operations)
├── Service/        # Business logic (fines, reports, sanitization)
├── Config/         # Configuration and constants
├── Exceptions/     # Custom exception handling
├── View/           # UI / HTML templates

Public/             # Entry point (index.php, forms, views)
vendor/             # Composer dependencies