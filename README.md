# NovelEntity Visualizer - Backend API 🚀

Welcome to the backend infrastructure of **NovelEntity Visualizer**, developed by **ironist08**. This is a high-performance RESTful API built using **Laravel 12** and **PostgreSQL** to power the automated content scanner and visual entity lore detector for light novels.

## 🌟 Features

- **Blazing Fast Indexing with ULID**: Uses Universally Unique Lexicographically Sortable Identifiers (ULID) for all primary keys in PostgreSQL, guaranteeing optimal indexing performance and security.
- **Server-Side Caching**: Powered by Laravel Cache (`Cache::remember`) to drastically reduce database overhead, serving heavy dictionary arrays to Chrome Extensions instantly.
- **Robust Multi-Language Support (Localization)**: Architected with dedicated translation schemas to dynamically serve descriptions and entity types matching the reader's preferred language (e.g., English `en` and Indonesian `id`).
- **Gender & Metadata Processing**: Seamlessly manages unique entity properties such as gender symbols (`male` / `female`) and customized `display_aliases` to eliminate visual redundancy in the front-end user experience.

---

## 🛠️ Tech Stack

- **Framework**: Laravel 12
- **Database**: PostgreSQL
- **Authentication**: JWT (JSON Web Token) via `tymon/jwt-auth` *(In Progress)*
- **Data Identification**: ULID (Universally Unique Lexicographically Sortable Identifier)

---

## 🚀 Installation & Setup

Follow these steps to run this backend API locally on your environment:

### 1. Clone & Install Dependencies
```bash
git clone https://github.com/itlabil/NovelEntity-Visualizer.git
cd L12-novel-database-api
composer install
```

### 2. Environment Configuration
Copy the .env.example file and configure your PostgreSQL database credentials:
```bash
cp .env.example .env

Edit db connection .env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_novel_database_api
DB_USERNAME=your_postgres_username
DB_PASSWORD=your_postgres_password
```

### 3. Generate Application Keys & Seed Database
```bash
php artisan key:generate
php artisan migrate:fresh --seed
```

### 4. JWT Authentication Setup
```bash
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
```

### 5. Clear Cache & Clear Optimization
```bash
php artisan cache:clear
php artisan config:clear
```

### 6. Run the Server
```bash
php artisan serve
```

The API endpoint will be available at http://127.0.0.1:8000/api/novel-keywords.

### 🗃️ Database Structure Highlight
The core data synchronization uses a specialized dictionary mapper query:
- entities (Stores global unique data like type, gender, display_aliases, and image_url with ULID).
- entity_translations (Handles localized content block for descriptions mapped via entity ULID relationship).
- entity_aliases (Acts as the main reverse-indexing keyword repository for the Chrome extension engine).

### ✒️ Author
Developed with ❤️ by ironist08
