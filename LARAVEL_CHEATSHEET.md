# 🚀 Laravel Artisan Cheatsheet

## 📦 Installation & Setup

```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Link storage folder
php artisan storage:link
```

---

## 🗄️ Database Commands

```bash
# Run all migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Reset & re-run all migrations
php artisan migrate:fresh

# Reset & re-run + seed
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status

# Run seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder
```

---

## 🔧 Make Commands (Generate Files)

```bash
# === MODEL ===
php artisan make:model NamaModel
php artisan make:model NamaModel -m          # + migration
php artisan make:model NamaModel -mc         # + migration + controller
php artisan make:model NamaModel -mcr        # + migration + resource controller
php artisan make:model NamaModel -a          # + migration + controller + seeder + factory + policy

# === CONTROLLER ===
php artisan make:controller NamaController
php artisan make:controller NamaController -r           # Resource controller (CRUD)
php artisan make:controller NamaController --resource   # Same as -r
php artisan make:controller NamaController --model=Nama # Resource + model binding
php artisan make:controller NamaController --api        # API controller (no create/edit)

# === MIGRATION ===
php artisan make:migration create_nama_table
php artisan make:migration add_kolom_to_nama_table

# === SEEDER ===
php artisan make:seeder NamaSeeder

# === FACTORY ===
php artisan make:factory NamaFactory

# === MIDDLEWARE ===
php artisan make:middleware NamaMiddleware

# === REQUEST (Form Request Validation) ===
php artisan make:request NamaRequest

# === RESOURCE (API Response Formatting) ===
php artisan make:resource NamaResource

# === POLICY (Authorization) ===
php artisan make:policy NamaPolicy --model=Nama

# === MAIL ===
php artisan make:mail NamaMail

# === EVENT & LISTENER ===
php artisan make:event NamaEvent
php artisan make:listener NamaListener --event=NamaEvent

# === JOB (Queue) ===
php artisan make:job NamaJob

# === COMMAND (Custom Artisan) ===
php artisan make:command NamaCommand

# === COMPONENT (Blade) ===
php artisan make:component NamaComponent
```

---

## 🛣️ Route Commands

```bash
# List all routes
php artisan route:list

# List routes with specific name
php artisan route:list --name=transaksi

# List routes for specific path
php artisan route:list --path=api

# Cache routes (production)
php artisan route:cache

# Clear route cache
php artisan route:clear
```

---

## 🧹 Cache & Clear Commands

```bash
# Clear all caches (recommended for debugging)
php artisan optimize:clear

# Individual clear commands
php artisan cache:clear      # Application cache
php artisan config:clear     # Config cache
php artisan view:clear       # Compiled views
php artisan route:clear      # Route cache
php artisan event:clear      # Event cache

# Cache for production
php artisan optimize         # Cache everything
php artisan config:cache     # Cache config
php artisan view:cache       # Cache views
```

---

## 🖥️ Serve & Tinker

```bash
# Start development server
php artisan serve

# Start on specific port
php artisan serve --port=8080

# Start on specific host
php artisan serve --host=0.0.0.0 --port=8000

# Interactive shell (REPL)
php artisan tinker
```

---

## 🔍 Tinker Commands (Inside php artisan tinker)

```php
// Create user
User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password')]);

// Find user
User::find(1);
User::where('email', 'admin@test.com')->first();

// Count records
Transaksi::count();

// Get all with relationships
Transaksi::with('items')->get();

// Delete all
Transaksi::truncate();

// Factory (generate fake data)
User::factory()->count(10)->create();

// Run raw SQL
DB::select('SELECT * FROM users');

// Check auth
Auth::loginUsingId(1);
Auth::user();

// Exit tinker
exit
```

---

## 📋 Queue Commands

```bash
# Run queue worker
php artisan queue:work

# Run single job then stop
php artisan queue:work --once

# Retry failed jobs
php artisan queue:retry all

# List failed jobs
php artisan queue:failed

# Clear failed jobs
php artisan queue:flush
```

---

## 🔐 Auth / Breeze / Fortify

```bash
# Install Laravel Breeze (simple auth)
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
php artisan migrate

# Install Laravel UI (alternative)
composer require laravel/ui
php artisan ui bootstrap --auth
npm install && npm run build
```

---

## 📊 Debugging & Info

```bash
# Show Laravel version
php artisan --version

# List all artisan commands
php artisan list

# Get help for specific command
php artisan help migrate

# Show environment
php artisan env

# Show loaded config
php artisan config:show database
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter=TransaksiTest

# Run with verbose output
php artisan test --verbose
```

---

## 📁 Common Folder Structure

```
app/
├── Http/
│   ├── Controllers/     # Controllers
│   ├── Middleware/      # Custom middleware
│   └── Requests/        # Form requests
├── Models/              # Eloquent models
├── Policies/            # Authorization policies
└── Providers/           # Service providers

database/
├── migrations/          # Database migrations
├── seeders/             # Database seeders
└── factories/           # Model factories

resources/
├── views/               # Blade templates
│   ├── layouts/         # Master layouts
│   ├── components/      # Blade components
│   └── pdf/             # PDF templates
└── css/js/              # Assets

routes/
├── web.php              # Web routes
└── api.php              # API routes
```

---

## ⚡ Quick Workflow

```bash
# 1. Buat fitur baru (Model + Migration + Controller)
php artisan make:model Produk -mcr

# 2. Edit migration di database/migrations/
# 3. Run migration
php artisan migrate

# 4. Edit model di app/Models/
# 5. Edit controller di app/Http/Controllers/
# 6. Tambah route di routes/web.php:
#    Route::resource('produk', ProdukController::class);

# 7. Buat views di resources/views/produk/
#    - index.blade.php
#    - create.blade.php
#    - edit.blade.php
#    - show.blade.php

# 8. Test di browser: php artisan serve
```

---

## 🔥 Troubleshooting

```bash
# "Class not found" error
composer dump-autoload

# Config not updating
php artisan config:clear

# Views not updating
php artisan view:clear

# Permission error (Linux/Mac)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Fresh start (reset everything)
php artisan migrate:fresh --seed
php artisan optimize:clear
```

---

## 📝 Contoh Validasi (di Controller)

```php
$request->validate([
    'nama'      => 'required|string|max:255',
    'email'     => 'required|email|unique:users,email',
    'password'  => 'required|min:8|confirmed',
    'tanggal'   => 'required|date',
    'harga'     => 'required|numeric|min:0',
    'status'    => 'required|in:pending,selesai,batal',
    'foto'      => 'nullable|image|mimes:jpg,png|max:2048',
    'items'     => 'required|array|min:1',
    'items.*.id'=> 'required|exists:master_data,id',
]);
```

---

## 🎯 Eloquent Relationships

```php
// Di Model User
public function transaksis() {
    return $this->hasMany(Transaksi::class);
}

// Di Model Transaksi
public function user() {
    return $this->belongsTo(User::class);
}

public function items() {
    return $this->hasMany(TransaksiItem::class);
}

// Di Model TransaksiItem
public function transaksi() {
    return $this->belongsTo(Transaksi::class);
}

public function masterData() {
    return $this->belongsTo(MasterData::class);
}
```

---

**💡 Tips:**
- Selalu jalankan `php artisan migrate:fresh --seed` setelah perubahan besar
- Gunakan `php artisan tinker` untuk debugging cepat
- Gunakan `php artisan route:list` untuk melihat semua routes
- Jangan lupa `php artisan optimize:clear` saat development
