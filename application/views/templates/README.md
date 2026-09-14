# Sistem Template / Tema CMS

Folder ini digunakan untuk menyimpan variasi template/tema halaman depan website.

## Struktur Template:
```
application/views/templates/
├── default/
│   └── home.php
├── katalog/       (Contoh: Toko / Jualan Online / Produk)
│   └── home.php
└── profil/        (Contoh: Profil Lembaga / Instansi / Desa)
    └── home.php
```

## Cara Kerja:
1. Pilihan template ditentukan oleh kolom `active_theme` di tabel `options` (diatur lewat menu Admin **Settings > Tema**).
2. Jika file `views/templates/{active_theme}/home.php` ada, sistem akan memuatnya.
3. Jika tidak ada atau diset ke `'default'`, sistem otomatis menggunakan `views/home.php` bawaan.

