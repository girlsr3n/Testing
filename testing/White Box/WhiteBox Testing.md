WHITE BOX TESTING

1\. Desk Checking

![Login Screenshot](login.png) 
(login.php)
Pemeriksaan manual oleh programmer terhadap logika dan nilai variabel.

![Login Screenshot](login1.gif) (tambah_produk.php)
Variabel utama langsung dideklarasikan dan digunakan tanpa kompleksitas tinggi, dan tidak ada manipulasi atau kalkulasi kompleks.

Validasi dilakukan eksplisit, mudah ditelusuri.


2\. Code Walkthrough

![Login Screenshot](wt1.png) Pemeriksaan struktur logika program secara kolektif (tim) untuk menemukan error.

(proses_transaksi.php)

- Setiap alur: mulai dari input form → validasi → eksekusi database → output, **terstruktur dan jelas**.
- Tidak ada logika yang membingungkan atau jalur kode yang tidak bisa dijangkau (unreachable).
- Developer bisa dengan mudah menjelaskan maksud setiap blok kode jika dilakukan review bersama tim.

![Login Screenshot](wt2.png) 

3\. Formal Inspection

![Login Screenshot](fi1.png) Evaluasi struktur kode, validasi format, ketepatan input/output, dan error handling.

Dibagian login, **tidak ada pembatasan panjang karakter atau karakter ilegal**.

![Login Screenshot](fi2.png) 

Dibagian tambah produk, Langsung dikonversi tanpa cek apakah input itu benar-benar angka dari sisi user.

4\. Control Flow Testing

![Login Screenshot](cf1.png) ![Login Screenshot](cf2.png) 

![Login Screenshot](cf3.png) 

Pengujian dilakukan terhadap semua kondisi tersebut dengan input yang sesuai.

5\. Data Flow Testing

| Komponen | Definisi | Penggunaan | Deskripsi |
| --- | --- | --- | --- |
| Creat Admin | Username – Password | Login | Admin membuat username dan password |
| Login | Username - Password | dashboard | Admin memasukan username dan password yang sudah didaftarkan |
| Dashboard | Daftar produk – Transaksi Produk – Riwayat Transaksi – Tambah Produk – Logout | Daftar produk – Transaksi Produk – Riwayat Transaksi – Tambah Produk – Logout | Admin dapat memilih fungsi Daftar Produk, Transaksi Produk, Riwayat Transaksi, Tambah Produk, Logout. |
| Daftar Produk | Produk (nama, harga, stok, aksi) | Menampilkan daftar semua produk | Komponen ini menampilkan informasi produk yang tersimpan di Daftar Produk kepada pengguna |
| Transaksi Produk | Produk (nama, harga, jumlah) | Melakukan transaksi produk | Admin melakukan transaksi, sehingga mempengaruhi jumlah stok produk yang dapat dilihat di Daftar produk |
| Riwayat Transaksi | Total – Waktu – Detail | Menampilkan daftar riwayat transaksi | Admin dapat melihat riwayat transaksi yang telah dilakukan berdasarkan Transaksi Produk |
| Tambah Produk | Nama Produk – Harga – Stok | Menambahkan produk baru ke daftar produk | Admin menambahkan data produk baru ke daftar produk |
| Logout | Logout | Keluar dari dashboard | Admin akan keluar dari dashboard dan harus login kembali |


![Login Screenshot](df.png) 


6\. Basic Path Testing

Identifikasi semua jalur eksekusi yang mungkin dijalankan dalam suatu program

Contoh dalam fungsi Tambah Produk

Rumus Cyclomatic Complexity

V(G) = E – N + 2P

Node (N) = 6

1. Start
2. Form Submitted? (if)
3. Input Valid? (if)
4. Simpan ke DB
5. Tampilkan Error
6. End

Edges (E) = 7

1. Sart → Form Submitted?
2. Form Submitted? → Input Valid?
3. Input Valid? → Simpan ke DB
4. Input Valid? → Tampilkan Error
5. Simpan ke DB → End
6. Tampilkan Error → End
7. Form Submitted? → End (jika form tidak disubmit)

Komponen (P) = 1

P = 1 karena hanya ada satu fungsi logika yang terhubung penuh (tidak terpisah).

V (G) = E – N + 2P = 7 – 6 + 2(1) = 3

Nilai V(G) = 3 artinya :

Ada **3 jalur independen** logika yang perlu diuji:

1. Form tidak disubmit → langsung keluar
2. Form disubmit → input valid → simpan
3. Form disubmit → input tidak valid → tampilkan error

7\. Loop Testing

Tidak ada struktur loop (perulangan) di login.php, pengujian ini tidak relevan. Bukan berarti error — tapi teknik ini tidak diterapkan karena tidak ada loop.

- **Alasan:** Tidak mengandung struktur loop (perulangan) seperti for, while, atau foreach.
- Hanya berisi logika **kondisional** (if-else) untuk memverifikasi username dan password.
- **Loop Testing tidak relevan** untuk file ini karena **tidak ada jalur berulang** untuk diuji.

![Login Screenshot](loop.png) 

proses_transaksi.php – LULUS Loop Testing

- ![Login Screenshot](loop1.png)
- Menggunakan loop foreach untuk memproses setiap produk yang dibeli.
- Setiap iterasi mempengaruhi:
  - Pengurangan stok produk
  - Pencatatan transaksi ke sistem
- Telah diuji dengan berbagai kondisi:
  - 0 produk: Loop dilewati tanpa error
  - 1 produk: Loop berjalan satu kali dengan hasil benar
  - \>1 produk: Semua produk diproses sesuai jumlahnya
- Tidak ditemukan: infinite loop, data corrupt, atau error logika

### Tambah_produk.php – **LULUS Loop Testing**

**Loop Testing tidak relevan untuk** tambah_produk.php **karena tidak ada struktur perulangan.** Semua input diproses secara langsung tanpa pengulangan.
