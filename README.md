# User Management System - Admin Gudang

Sistem Manajemen Pengguna untuk Admin Gudang dengan fitur lengkap registrasi, aktivasi email, login, dan CRUD produk.

## 🚀 Fitur Utama

### Pengguna
- ✅ **Registrasi Pengguna**: Daftar sebagai Admin Gudang dengan email sebagai username
- ✅ **Aktivasi Email**: Sistem mengirim link aktivasi ke email pengguna
- ✅ **Login**: Masuk ke sistem setelah akun aktif
- ✅ **Lupa Password**: Reset password via email dengan link yang kadaluarsa
- ✅ **Manajemen Profil**: Edit nama lengkap dan nomor telepon
- ✅ **Ubah Password**: Ganti password dengan verifikasi password lama

### Produk
- ✅ **CRUD Produk**: Create, Read, Update, Delete data produk
- ✅ **Kode Produk Unik**: Setiap user memiliki kode produk unik
- ✅ **Kategori & Stok**: Kelola kategori, jumlah stok, satuan, dan harga
- ✅ **Dashboard Statistik**: Total produk, stok, dan nilai inventori

## 🛠️ Teknologi

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Email**: PHPMailer (SMTP)
- **Frontend**: HTML, CSS (dengan styling modern)
- **Server**: XAMPP / Apache + MySQL

## 📋 Persyaratan Sistem

- PHP 7.4 atau lebih baru
- MySQL 5.7+ atau MariaDB 10.0+
- XAMPP (untuk development lokal)
- Email SMTP (Gmail, Outlook, dll.)

## 🚀 Instalasi & Setup

### 1. Clone atau Download Project
```bash
# Jika menggunakan Git
git clone https://github.com/username/user-management-system.git

# Atau download ZIP dan ekstrak ke folder XAMPP
# c:\xampp\htdocs\user-management-system\
```

### 2. Setup Database
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Buat database baru: `user_management_system`
3. Import file `database.sql` yang ada di root project
4. Atau jalankan `create_database.php` di browser untuk setup otomatis

### 3. Konfigurasi Email (PHPMailer)
Edit file `config.php`:
```php
// Ganti dengan email dan password aplikasi Anda
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password'); // Bukan password biasa!
```

**Untuk Gmail:**
1. Aktifkan 2-Factor Authentication
2. Buat App Password: https://myaccount.google.com/apppasswords
3. Gunakan App Password di SMTP_PASSWORD

### 4. Jalankan Sistem
1. Start XAMPP (Apache & MySQL)
2. Buka browser: `http://localhost/user-management-system/`
3. Daftar akun baru atau login dengan admin default:
   - Email: `admin@example.com`
   - Password: `admin123`

## 📁 Struktur File

```
user-management-system/
├── config.php          # Konfigurasi database & email
├── connect.php         # Koneksi database
├── create_tables.php   # Setup tabel database
├── database.sql        # Schema database
├── session.php         # Manajemen session
├── mailer.php          # PHPMailer functions
├── index.php           # Landing page
├── register.php        # Form registrasi
├── activate.php        # Aktivasi akun via email
├── login.php           # Form login
├── forgot_password.php # Lupa password
├── reset_password.php  # Reset password
├── dashboard.php       # Dashboard utama
├── products.php        # CRUD produk
├── profile.php         # Edit profil
├── change_password.php # Ubah password
├── logout.php          # Logout
├── PHPMailer/          # Library PHPMailer
└── README.md           # Dokumentasi ini
```

## 🔧 Konfigurasi Database

Tabel yang dibuat:
- `users`: Data pengguna (id, email, password, dll.)
- `products`: Data produk (id, user_id, product_code, dll.)

Foreign key: `products.user_id` → `users.id` (ON DELETE CASCADE)

## 📧 Konfigurasi Email

Sistem menggunakan PHPMailer untuk:
- Email aktivasi akun
- Email reset password

Pastikan SMTP settings di `config.php` benar.

## 🎯 Cara Penggunaan

1. **Registrasi**: Isi form di `register.php`, cek email untuk aktivasi
2. **Login**: Masuk dengan email & password setelah aktivasi
3. **Dashboard**: Lihat statistik produk
4. **Produk**: Tambah, edit, hapus produk
5. **Profil**: Edit data diri
6. **Password**: Ubah password atau reset jika lupa

## 🔒 Keamanan

- Password di-hash dengan `password_hash()` (BCRYPT)
- Session management untuk login state
- Email verification untuk aktivasi & reset
- Input sanitization dengan `mysqli_real_escape_string()`
- CSRF protection dengan session tokens
- SQL Injection prevention

## 🐛 Troubleshooting

### Email tidak terkirim
- Cek SMTP settings di `config.php`
- Pastikan firewall tidak blokir port 587/465
- Untuk Gmail, gunakan App Password

### Database error
- Pastikan MySQL running di XAMPP
- Cek kredensial di `config.php`
- Import ulang `database.sql`

### Permission error
- Pastikan folder project ada di `c:\xampp\htdocs\`
- Berikan permission write ke folder jika perlu

## 📝 Catatan Development

- Sistem menggunakan session untuk state management
- Error logging ke `error_log` PHP
- UI responsive dengan CSS modern
- Kode terstruktur dan berkomentar

## 🤝 Kontribusi

1. Fork repository
2. Buat branch fitur baru
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request

## 📄 Lisensi

Project ini untuk keperluan edukasi dan development.

---

**Dibuat untuk**: Tugas Sistem Manajemen Pengguna Admin Gudang
**Author**: CALVIN STVEN
**Versi**: 1.0.0
