<?php
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['total-semua-harga-keranjang'])) {
        // Query untuk menghitung total semua harga dari tabel transaksi_kasir
        $query = "SELECT SUM(total_harga) AS total_semua_harga FROM transaksi_kasir WHERE status = 'O'";
        $result = $connected->query($query);

        if ($result && $row = $result->fetch_assoc()) {
            $total_harga = $row['total_semua_harga'] ?: 0; // Jika NULL, default ke 0
            // Format angka sebagai mata uang
            echo 'Rp ' . number_format($total_harga, 0, ',', '.');
        } else {
            echo 'Rp 0'; // Jika gagal atau tidak ada data
        }
        exit;
    } else {
        // Query untuk mendapatkan data keranjang
        $query = "SELECT b.nama, t.kuantitas, t.harga, t.total_harga, t.barang_id FROM transaksi_kasir t JOIN barang b ON t.barang_id = b.barang_id WHERE t.status = 'O'";
        $result = $connected->query($query);

        // Mulai membangun respons HTML
        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                ?>
                <div class="d-flex justify-content-between align-items-center mt-2 p-2 border-bottom">
                    <h5 class="text-dark"><?= htmlspecialchars($data['nama']) ?></h5>
                    <div class="d-flex align-items-center">
                        <!-- Tombol untuk mengurangi kuantitas -->
                        <button class="btn btn-sm btn-outline-secondary me-2 btn-decrease" data-id="<?= $data['barang_id'] ?>">
                            <i class="fas fa-minus"></i>
                        </button>
                        <!-- Input jumlah kuantitas -->
                        <input type="number" style="max-width: 80px;" class="jumlahKeluar form-control mx-2"
                            value="<?= $data['kuantitas'] ?>" min="1" data-id="<?= $data['barang_id'] ?>">
                        <!-- Tombol untuk menambah kuantitas -->
                        <button class="btn btn-sm btn-outline-secondary ms-2 btn-increase" data-id="<?= $data['barang_id'] ?>">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <p class="harga text-dark me-2" data-barang_id="<?= $data['barang_id'] ?>">Rp
                        <?= number_format($data['harga'], 0, ',', '.') ?>
                    </p>
                    <p class="totalHarga text-dark">Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></p>
                    <!-- Tombol untuk menghapus barang -->
                    <button class="btn btn-sm btn-outline-danger ms-2 btn-delete" data-id="<?= $data['barang_id'] ?>">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <?php
            }
        } else {
            // Jika tidak ada data di keranjang
            echo "<p class='text-center text-muted'>Keranjang kosong. Tambahkan barang terlebih dahulu.</p>";
        }
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET['mulai_transaksi'])) {
        if (isset($_POST['barang_id'])) {
            $barang_id = $_POST['barang_id'] ?? null; // barang_id dari daftar harga ketika icon tambah diklik

            if (!$barang_id) {
                echo json_encode(['success' => false, 'message' => 'Barang ID tidak valid.']);
                exit;
            }

            // Ambil data barang berdasarkan barang_id
            $query_barang = "SELECT nama, harga_satuan, harga_butir FROM barang WHERE barang_id = ?";
            $stmt_barang = $connected->prepare($query_barang);
            $stmt_barang->bind_param("s", $barang_id);
            $stmt_barang->execute();
            $result = $stmt_barang->get_result();

            if ($result->num_rows === 0) {
                echo json_encode(['success' => false, 'message' => 'Barang tidak ditemukan.']);
                exit;
            }

            $barang = $result->fetch_assoc();
            $harga = $barang['harga_satuan'] ? $barang['harga_satuan'] : $barang['harga_butir']; // Prioritas harga_satuan

            if (!$harga) {
                echo json_encode(['success' => false, 'message' => 'Harga barang tidak ditemukan.']);
                exit;
            }

            // Periksa apakah barang sudah ada dalam transaksi aktif (status = 'O')
            $query_check = "SELECT kuantitas, total_harga FROM transaksi_kasir WHERE barang_id = ? AND status = 'O'";
            $stmt_check = $connected->prepare($query_check);
            $stmt_check->bind_param("s", $barang_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                // Barang sudah ada, update kuantitas dan total harga
                $row = $result_check->fetch_assoc();
                $new_quantity = $row['kuantitas'] + 1;
                $new_total_harga = $new_quantity * $harga;

                $query_update = "UPDATE transaksi_kasir SET kuantitas = ?, total_harga = ? WHERE barang_id = ? AND status = 'O'";
                $stmt_update = $connected->prepare($query_update);
                $stmt_update->bind_param("iis", $new_quantity, $new_total_harga, $barang_id);

                if ($stmt_update->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Kuantitas barang diperbarui.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui kuantitas barang.']);
                }

                $stmt_update->close();
            } else {
                // Barang belum ada, tambahkan sebagai baris baru
                $query_transaksi = "SELECT MAX(transaksi_ke) AS max_transaksi FROM transaksi_kasir";
                $result_transaksi = $connected->query($query_transaksi);
                $transaksi_ke = $result_transaksi->num_rows > 0 ? $result_transaksi->fetch_assoc()['max_transaksi'] + 1 : 1;

                $query_insert = "INSERT INTO transaksi_kasir (barang_id, kuantitas, harga, total_harga, transaksi_ke, status) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_insert = $connected->prepare($query_insert);

                $kuantitas = 1; // Hardcoded
                $total_harga = $harga * $kuantitas;
                $status = 'O'; // Hardcoded

                $stmt_insert->bind_param("iiiiis", $barang_id, $kuantitas, $harga, $total_harga, $transaksi_ke, $status);

                if ($stmt_insert->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Barang berhasil ditambahkan.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal menambahkan barang ke transaksi.']);
                }

                $stmt_insert->close();
            }

            $stmt_barang->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
        }
    } else if (isset($_GET['selesaikan-transaksi'])) {
        // Ambil semua data yang masih open dari tabel transaksi_kasir
        $status_open = 'O';
        $stmt_transaksi_open = $connected->prepare("SELECT * FROM transaksi_kasir WHERE status = ?");
        $stmt_transaksi_open->bind_param("s", $status_open);

        $data = [];
        if ($stmt_transaksi_open->execute()) {
            $result = $stmt_transaksi_open->get_result();

            // Looping untuk setiap transaksi dengan status 'O'
            foreach ($result as $item) {
                $barang_id = $item['barang_id'];
                $jumlah_keluar = $item['kuantitas']; // kolom kuantitas adalah jumlah keluar

                // Debug data barang_id dan jumlah keluar
                echo "Barang ID: $barang_id, Jumlah Keluar: $jumlah_keluar<br>";

                // Validasi data barang_id dan jumlah keluar
                if (empty($barang_id) || $jumlah_keluar <= 0) {
                    echo "Data barang atau jumlah keluar tidak valid.<br>";
                    continue; // Skip transaksi jika data tidak valid
                }

                // Ambil kategori barang dari tabel barang
                $stmt_kategori = $connected->prepare("SELECT kategori FROM barang WHERE barang_id = ?");
                $stmt_kategori->bind_param("i", $barang_id);


                if ($stmt_kategori->execute()) {
                    $result_kategori = $stmt_kategori->get_result();
                    if ($result_kategori->num_rows > 0) {
                        $kategori = $result_kategori->fetch_assoc()['kategori'];
                    } else {
                        echo "Kategori tidak ditemukan untuk barang_id: $barang_id<br>";
                        continue; // Skip jika kategori tidak ditemukan
                    }
                } else {
                    echo "Error pada query kategori: " . $stmt_kategori->error . "<br>";
                    continue; // Skip jika query gagal
                }

                // Data lainnya
                $harga = $item['harga'];
                $total_harga = $item['total_harga'];
                $tanggal_keluar = date('Y-m-d H:i:s');
                $keterangan = 'Penjualan';

                // Debug query insert
                echo "Data yang akan diinsert: Barang ID: $barang_id, Kategori: $kategori, Jumlah Keluar: $jumlah_keluar<br>";

                // Insert data ke tabel barang_keluar
                $stmt_insert = $connected->prepare("INSERT INTO barang_keluar (barang_id, kategori, jumlah_keluar, harga_satuan, total_harga, tanggal_keluar, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt_insert->bind_param("isiiiss", $barang_id, $kategori, $jumlah_keluar, $harga, $total_harga, $tanggal_keluar, $keterangan);

                if ($stmt_insert->execute()) {
                    echo "Data berhasil diinsert untuk barang_id: $barang_id<br>";

                    // Update stok barang
                    $stmt_update_stok = $connected->prepare("UPDATE barang SET stok = stok - ? WHERE barang_id = ?");
                    $stmt_update_stok->bind_param("ii", $jumlah_keluar, $barang_id);

                    if ($stmt_update_stok->execute()) {
                        echo "Stok berhasil dikurangi untuk barang_id: $barang_id<br>";
                    } else {
                        echo "Error saat update stok: " . $stmt_update_stok->error . "<br>";
                    }

                    // Update status transaksi_kasir
                    $stmt_update = $connected->prepare("UPDATE transaksi_kasir SET status = 'C' WHERE barang_id = ? AND status = 'O'");
                    $stmt_update->bind_param("i", $barang_id);
                    $stmt_update->execute();
                } else {
                    echo "Error saat insert barang_keluar: " . $stmt_insert->error . "<br>";
                }
            }
        } else {
            echo "Error: " . $stmt_transaksi_open->error . "<br>";
        }

    } else {
        // Mengambil data dari AJAX
        $barangIds = isset($_POST['barangIds']) ? $_POST['barangIds'] : [];
        $jumlahKeluar = isset($_POST['jumlahKeluar']) ? $_POST['jumlahKeluar'] : [];
        $totalHarga = isset($_POST['totalHarga']) ? $_POST['totalHarga'] : 0;

        // Validasi input
        if (empty($barangIds) || empty($jumlahKeluar)) {
            echo "Data barang atau jumlah keluar tidak valid.";
            exit;
        }

        $transaksi_berhasil = "";

        // Proses setiap barang_id dan jumlah_keluar
        foreach ($barangIds as $index => $barang_id) {
            // Ambil jumlah_keluar untuk barang_id yang sesuai
            $jumlah = isset($jumlahKeluar[$index]) ? $jumlahKeluar[$index] : 0;

            // Ambil data barang dari tabel barang berdasarkan barang_id
            $stmt = $connected->prepare("SELECT kategori, harga_satuan, harga_butir, stok FROM barang WHERE barang_id = ?");
            $stmt->bind_param("i", $barang_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data_barang = $result->fetch_assoc();

                // Data barang yang dibutuhkan
                $kategori = $data_barang['kategori'];
                $harga_satuan = $data_barang['harga_satuan'];
                $harga_butir = $data_barang['harga_butir'];
                $stok = $data_barang['stok']; // Stok yang tersedia
                $tanggal_keluar = date('Y-m-d');
                $keterangan = "Penjualan";

                // Tentukan harga yang valid (tidak nol)
                $harga = $harga_satuan > 0 ? $harga_satuan : $harga_butir;

                // Hitung total harga berdasarkan jumlah dan harga yang valid
                $total_harga_item = $jumlah * $harga;

                // Masukkan data ke tabel barang_keluar
                $insertStmt = $connected->prepare("INSERT INTO barang_keluar (barang_id, kategori, jumlah_keluar, harga_satuan, total_harga, tanggal_keluar, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $insertStmt->bind_param("isidiss", $barang_id, $kategori, $jumlah, $harga, $total_harga_item, $tanggal_keluar, $keterangan);

                if ($insertStmt->execute()) {
                    // Mengurangi stok barang
                    $new_stok = $stok - $jumlah; // Mengurangi stok sesuai dengan jumlah keluar
                    $updateStmt = $connected->prepare("UPDATE barang SET stok = ? WHERE barang_id = ?");
                    $updateStmt->bind_param("ii", $new_stok, $barang_id);

                    $updateStmt->execute();
                    $transaksi_berhasil .= "Transaksi berhasil untuk barang ID $barang_id.<br>";

                    $updateStmt->close();
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
    }
} else {
    echo "Invalid request method.";
}
