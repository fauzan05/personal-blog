# Personal Blog

## Description
Aplikasi ini menggunakan CKEditor dalam membantu proses pembuatan postingan. Admin dapat melakukan CRUD terhadap postingan, kategori, tag, alamat, sosial media, dll. Jika password lupa, maka bisa dipulihkan dan secara otomatis aplikasi ini akan mengirimkan email menggunakan protokol smtp berupa kode verifikasi untuk memastikan bahwa email yang lupa password-nya itu aktif dan valid. Setelah valid kode-nya, maka user tersebut bisa mereset ulang password-nya. User guest bisa berkomentar tanpa harus membuat akun terlebih dahulu, karena data user disimpan di database dan di cookies pada saat user guest mengisi identitas. Untuk lebih lengkapnya, bisa dibaca bagian README.md beserta tutorial instal-nya. 

# Requirements
- PHP 8.2
- Laravel 10
- Livewire 3
- Docker (optional)
- CKEditor 5
- HTML 5
- CSS 3
- Javascript
- Bootstrap 5
- MariaDB
## How to install

Jika anda menggunakan MariaDB yang terinstal di komputer anda entah itu menggunakan XAMPP, MAMP, atau sejenisnya maka nyalakan terlebih dahulu dan buat database sesuai dengan nama database di file .env. Jika menggunakan docker, disini saya sudah membuat docker compose dengan nama file-nya adalah compose.yaml dan sudah secara otomatis dibuatkan database tanpa membuatnya manual. Pastikan sudah mengunduh image MariaDB:latest pada Docker-nya. Caranya masukkan perintah berikut untuk menjalankan docker compose pada root direktori :

```
docker compose up 
```

Setelah berhasil, tinggal nyalakan server laravelnya dengan cara ketikkan perintah berikut :

```
php artisan serve --host=localhost --port=8000
```

Untuk port dan host-nya sendiri bebas, saya hanya merekomendasikannya saja agar tidak terjadi error nantinya saat proses upload gambar ke CKEditor 5. Jika ingin menggantinya, maka ganti juga pada proses pembuatan postingannya yang ada di **app/Livewire/AdminPost.php** dan cari method post(). Disitu sudah ada penjelasan berupa komentar untuk menggunakan 127.0.0.1 atau localhost.
<br>

Setelah proses instalasi selesai, saatnya akses di browser dengan mengetikkan **http://localhost:8000** untuk mengakses halaman utama. Untuk routing sudah ada di direktori **routes/web.php** dan silahkan untuk diekslplor sendiri.