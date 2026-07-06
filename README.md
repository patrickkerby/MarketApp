# MarketApp - Farmers' Market Management System

A comprehensive Laravel application for managing farmers' market operations, sales tracking, and business analytics.

## About

**Riverbend Gardens** is an established farming business based in Edmonton, Alberta, with a product line of twenty responsibly-farmed vegetables. They serve local residents interested in local and independently produced vegetables through both community supported agriculture programs and farmers' markets.

### The Challenge

Traditionally, sales through farmers' markets have been strong, but two areas needed improvement:
1. **Sales day operations** - Currently managed in real-time and in person by the owners
2. **Product line optimization** - Market participation decisions based on intuition rather than data

### The Solution

MarketApp is a web application that features:
- **Sales day management** - Streamline planning, packing, and market operations
- **Data collection** - Comprehensive sales and product tracking
- **Analytics & Insights** - Transform data into actionable business intelligence

---

## Features

### Core Functionality
- ✅ **Market Day Management** - Plan and track market days through multi-stage workflow
- ✅ **Product Inventory** - Manage vegetables with categories and pricing
- ✅ **Quantity Tracking** - Track packed quantities vs. returned (unsold) items
- ✅ **Revenue Tracking** - Compare estimated vs. actual revenue
- ✅ **Multi-Market Support** - Manage multiple farmers' market locations
- ✅ **Notes System** - Admin, packing, and market notes for each market day
- ✅ **User Authentication** - Secure login with role-based access control

### Market Day Workflow
```
Draft → Ready to Pack → Packed → Returned → Completed
  0           1            2         3           4
```

### Analytics & Reporting (Enhanced)
- 📊 Summary dashboard with key metrics
- 📈 Year-over-year comparisons
- 📉 Revenue trends and visualizations
- 🥕 Product performance analytics
- 📁 Excel export functionality
- 📊 Variance analysis (estimated vs actual)

---

## Tech Stack

- **Framework:** Laravel 8.83
- **PHP:** 8.2+
- **Database:** SQLite (dev) / MySQL (production)
- **Frontend:** Blade templates, Bootstrap 3, jQuery
- **Data Tables:** Yajra DataTables
- **Charts:** Chart.js (planned)
- **Export:** Maatwebsite Excel

---

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL (production) or SQLite (development)

### Quick Start

```bash
# Clone the repository
git clone <repository-url>
cd MarketApp

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup (SQLite for development)
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# Compile frontend assets
npm run dev

# Start development server
php artisan serve
```

Visit http://127.0.0.1:8000 in your browser.

### Default Login
- **Email:** `admin@marketapp.local`
- **Password:** `password`

---

## Documentation

Comprehensive documentation is available in the `docs` folder:

### 📚 Core Documentation
- **[SYSTEM_DOCUMENTATION.md](SYSTEM_DOCUMENTATION.md)** - Complete technical reference
  - System architecture
  - Database schema
  - Business logic & workflows
  - Security considerations

### 🐛 Development Guides
- **[CODE_ISSUES_REPORT.md](CODE_ISSUES_REPORT.md)** - Technical debt analysis
  - Critical, medium, and low priority issues
  - Code examples and fixes
  - Prioritization guidance

### 📊 Enhancement Plans
- **[ANALYTICS_ENHANCEMENT_PLAN.md](ANALYTICS_ENHANCEMENT_PLAN.md)** - 6-phase analytics roadmap
  - Core metrics dashboard
  - Product & market analytics
  - Export & reporting features
  - Interactive visualizations

- **[QUICK_START_IMPLEMENTATION.md](QUICK_START_IMPLEMENTATION.md)** - Immediate improvements guide
  - Quick wins (< 30 minutes)
  - Summary cards implementation
  - Step-by-step code examples

### 📋 Executive Summary
- **[PROJECT_REVIEW_SUMMARY.md](PROJECT_REVIEW_SUMMARY.md)** - High-level overview
  - Current state assessment
  - Key findings
  - Recommended priorities

---

## Database Schema

### Core Tables

**markets** - Farmers' market locations  
**market_days** - Individual market day events (central table)  
**products** - Vegetable inventory with pricing  
**categories** - Product categorization  
**product_quantities** - Quantity tracking (packed/returned) per market day  
**users** - User accounts  
**roles & abilities** - RBAC system  

### Key Relationships
```
markets ──┬── market_days ──┬── product_quantities ──┬── products ──── categories
          │                 │
          └─ date, revenue   └─ packed, returned
```

See [SYSTEM_DOCUMENTATION.md](SYSTEM_DOCUMENTATION.md) for detailed schema.

---

## Usage

### Creating a Market Day

1. **Navigate to:** Market Days → Create
2. **Step 1:** Select markets and products
3. **Step 2:** Set packed quantities for each product
4. **Publish:** Creates market day records (state: Ready to Pack)

### During Market Day

1. **Update state to:** Packed (products loaded)
2. **At market:** Record any notes
3. **After market:** Update state to Returned
4. **Enter:** Returned quantities for unsold items

### Completing a Market Day

1. **Enter:** Actual revenue collected
2. **Update state to:** Completed
3. **View in:** Completed Markets analytics dashboard

---

## Analytics Dashboard

The completed markets view provides:

### Summary Cards
- Total revenue (with year-over-year comparison)
- Average revenue per market
- Total market days
- Variance % (estimated vs actual)

### Filters
- Year dropdown (dynamically populated)
- Month selector
- Market selector
- Combined filtering

### Data Table
- Sortable, paginated list
- Date, market name, revenue
- Quick links to detailed views

### Upcoming Features
- Revenue trend charts
- Top products analysis
- Market comparison visualizations
- Excel export with summary sheets
- Waste analysis
- Predictive recommendations

---

## Development

### Project Structure
```
app/
├── Http/Controllers/
│   ├── MarketDaysController.php  # Core business logic
│   ├── ProductsController.php
│   ├── MarketsController.php
│   └── CategoriesController.php
├── Models/
│   ├── market_days.php
│   ├── Products.php
│   ├── Markets.php
│   └── ...
resources/views/
├── layout.blade.php
└── market_days/
    ├── index.blade.php
    ├── completed-index.blade.php  # Analytics view
    ├── create-setup.blade.php
    ├── create.blade.php
    └── edit.blade.php
```

### Common Commands

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Database
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed  # Reset database

# Assets
npm run dev         # Development build
npm run watch       # Watch for changes
npm run production  # Production build

# Testing
php artisan test

# Create new user via Tinker
php artisan tinker
>>> User::create(['name' => 'Name', 'email' => 'email@example.com', 'password' => bcrypt('password')])
```

---

## Roadmap

### Current Phase: Analytics Enhancement
- [ ] Implement summary cards
- [ ] Add Chart.js visualizations
- [ ] Product performance metrics
- [ ] Excel export functionality

### Next Phase: Code Quality
- [ ] Refactor model naming conventions
- [ ] Implement state enums
- [ ] Add comprehensive validation
- [ ] Performance optimization

### Future Enhancements
- [ ] Mobile-responsive improvements
- [ ] REST API for mobile app
- [ ] Predictive analytics
- [ ] Automated packing recommendations
- [ ] Integration with accounting software

See [ANALYTICS_ENHANCEMENT_PLAN.md](ANALYTICS_ENHANCEMENT_PLAN.md) for detailed roadmap.

---

## Contributing

### Getting Started
1. Review [CODE_ISSUES_REPORT.md](CODE_ISSUES_REPORT.md) for known issues
2. Check [SYSTEM_DOCUMENTATION.md](SYSTEM_DOCUMENTATION.md) for architecture
3. Follow Laravel coding standards
4. Write tests for new features

### Code Standards
- PSR-12 coding style
- Model names: PascalCase, singular (e.g., `MarketDay`)
- Use `$fillable` instead of `$guarded`
- Type hint return values
- Add PHPDoc blocks

---

## Security

### Current Implementation
- Laravel authentication
- CSRF protection enabled
- Password hashing (bcrypt)
- Role-based access control (RBAC) system in place

### Known Issues
- Mass assignment protection needs hardening (see CODE_ISSUES_REPORT.md)
- RBAC not fully implemented in controllers
- Consider adding Laravel Sanctum for API authentication

---

## Performance

### Optimizations Implemented
- Server-side pagination (DataTables)
- Eager loading for relationships
- Indexed foreign keys

### Planned Optimizations
- Add composite indexes
- Query result caching
- Asset optimization
- CDN for static files

---

## Support & Maintenance

### Regular Tasks
- Update year dropdown annually (or use dynamic implementation)
- Review and archive old products
- Database backups (production)
- Monitor Laravel security updates

### Known Limitations
- Web-only (no mobile app yet)
- No real-time updates (requires refresh)
- Weather integration commented out (API key needed)

---

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Credits

**Developed for:** Riverbend Gardens  
**Principals:** Aaron and Janelle Herbert  
**Location:** Edmonton, Alberta, Canada  
**Framework:** Laravel  
**Purpose:** Empowering local agriculture through data-driven decisions

---

## Contact & Support

For questions about this application, please refer to the documentation files listed above or contact the development team.

**Documentation Last Updated:** January 13, 2026

---

## Quick Links

- 📖 [Complete System Documentation](SYSTEM_DOCUMENTATION.md)
- 🐛 [Code Issues & Fixes](CODE_ISSUES_REPORT.md)
- 📊 [Analytics Enhancement Plan](ANALYTICS_ENHANCEMENT_PLAN.md)
- 🚀 [Quick Start Implementation Guide](QUICK_START_IMPLEMENTATION.md)
- 📋 [Executive Summary](PROJECT_REVIEW_SUMMARY.md)
