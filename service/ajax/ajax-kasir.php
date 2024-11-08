<?php
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari AJAX
    $barangIds = isset($_POST['barangIds']) ? $_POST['barangIds'] : [];
    $jumlahKeluar = isset($_POST['jumlahKeluar']) ? $_POST['jumlahKeluar'] : [];
    $totalHarga = isset($_POST['totalHarga']) ? $_POST['totalHarga'] : 0;

    // Validasi input
    if (empty($barangIds) || empty($jumlahKeluar)) {
        echo "Data barang atau jumlah keluar tidak valid.";
        exit;
    }

    $transaksi_berhasil;

    // Proses setiap barang_id dan jumlah_keluar
    foreach ($barangIds as $index => $barang_id) {
        // Ambil jumlah_keluar untuk barang_id yang sesuai
        $jumlah = isset($jumlahKeluar[$index]) ? $jumlahKeluar[$index] : 0;

        // Ambil data barang dari tabel barang berdasarkan barang_id
        $stmt = $connected->prepare("SELECT kategori, harga_satuan FROM barang WHERE barang_id = ?");
        $stmt->bind_param("i", $barang_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data_barang = $result->fetch_assoc();

            // Data barang yang dibutuhkan
            $kategori = $data_barang['kategori'];
            $harga_satuan = $data_barang['harga_satuan'];
            $tanggal_keluar = date('Y-m-d');
            $keterangan = "Penjualan";

            // Hitung total harga berdasarkan jumlah dan harga satuan
            $total_harga_item = $jumlah * $harga_satuan;

            // Masukkan data ke tabel barang_keluar
            $insertStmt = $connected->prepare("INSERT INTO barang_keluar (barang_id, kategori, jumlah_keluar, harga_satuan, total_harga, tanggal_keluar, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insertStmt->bind_param("isidiss", $barang_id, $kategori, $jumlah, $harga_satuan, $total_harga_item, $tanggal_keluar, $keterangan);

            if ($insertStmt->execute()) {
                $transaksi_berhasil = "Transaksi Berhasil, Data telah masuk ke barang keluar. ";
            } else {
                echo "Gagal menambahkan data untuk barang ID $barang_id: " . $insertStmt->error . "<br>";
            }

            $insertStmt->close();
        } else {
            echo "Barang dengan ID $barang_id tidak ditemukan.<br>";
        }
        $stmt->close();
    }

    // Tampilkan status transaksi dan total harga semua barang
    echo $transaksi_berhasil;
    echo "Total harga semua barang: Rp " . number_format($totalHarga, 0, ',', '.');
} else {
    echo "Invalid request method.";
}
