<?php
include '../connection.php';

$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $barang_id = $_POST['barang_id'] ?? null;

    if ($barang_id === null || !is_numeric($barang_id)) {
        $response['message'] = 'ID barang tidak valid';
        echo json_encode($response);
        exit;
    }

    switch ($action) {
        case 'update':
            $barang_id = $_POST['barang_id'] ?? null;
            $kuantitas = $_POST['kuantitas'] ?? null;

            if (!$barang_id || !$kuantitas || $kuantitas < 1) {
                echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
                exit;
            }

            // Update kuantitas dan total harga di database
            $query = "UPDATE transaksi_kasir SET kuantitas = ?, total_harga = harga * ? WHERE barang_id = ? AND status = 'O'";
            $stmt = $connected->prepare($query);
            $stmt->bind_param('iii', $kuantitas, $kuantitas, $barang_id);

            if ($stmt->execute()) {
                // Ambil total harga semua barang
                $query_total = "SELECT SUM(total_harga) AS total_semua_harga FROM transaksi_kasir WHERE status = 'O'";
                $result_total = $connected->query($query_total);
                $total_harga = $result_total->fetch_assoc()['total_semua_harga'] ?? 0;

                echo json_encode([
                    'success' => true,
                    'message' => 'Kuantitas berhasil diperbarui.',
                    'totalHargaSemua' => 'Rp ' . number_format($total_harga, 0, ',', '.')
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui kuantitas.']);
            }

            $stmt->close();
            exit;

        case 'update_harga':
            $barang_id = $_POST['barang_id'] ?? null;
            $harga_baru = $_POST['harga_baru'] ?? null;

            if (!$barang_id || !$harga_baru || $harga_baru < 0) {
                echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
                exit;
            }

            // Update harga di database
            $query = "UPDATE transaksi_kasir SET harga = ?, total_harga = kuantitas * ? WHERE barang_id = ? AND status = 'O'";
            $stmt = $connected->prepare($query);
            $stmt->bind_param('dii', $harga_baru, $harga_baru, $barang_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Harga berhasil diperbarui.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui harga.']);
            }

            $stmt->close();
            exit;


        case 'update_quantity':
            // $barang_id = $_POST['barang_id'] ?? null;
            $kuantitas = $_POST['kuantitas'] ?? null;

            if (!$barang_id || !$kuantitas || $kuantitas < 1) {
                echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
                exit;
            }

            // Update kuantitas di database
            $query = "UPDATE transaksi_kasir SET kuantitas = ?, total_harga = kuantitas * harga WHERE barang_id = ? AND status = 'O'";
            $stmt = $connected->prepare($query);
            $stmt->bind_param('ii', $kuantitas, $barang_id);

            if ($stmt->execute()) {
                // Ambil total harga terbaru (opsional)
                $query_total = "SELECT SUM(total_harga) AS total_semua_harga FROM transaksi_kasir WHERE status = 'O'";
                $result_total = $connected->query($query_total);
                $total_harga = $result_total->fetch_assoc()['total_semua_harga'] ?? 0;

                echo json_encode([
                    'success' => true,
                    'message' => 'Kuantitas berhasil diperbarui.',
                    'totalHarga' => 'Rp ' . number_format($total_harga, 0, ',', '.')
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui kuantitas.']);
            }
            $stmt->close();
            exit;

        case 'tambah':
            // Tambahkan kuantitas
            $query = "UPDATE transaksi_kasir SET kuantitas = kuantitas + 1, total_harga = harga * kuantitas
                      WHERE barang_id = ? AND status = 'O'";
            $stmt = $connected->prepare($query);
            $stmt->bind_param('i', $barang_id);
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Barang berhasil ditambah';
            } else {
                $response['message'] = 'Gagal menambah barang';
            }
            break;

        case 'kurang':
            // Kurangi kuantitas jika lebih dari 1
            $query = "SELECT kuantitas FROM transaksi_kasir WHERE barang_id = ? AND status = 'O'";
            $stmt = $connected->prepare($query);
            $stmt->bind_param('i', $barang_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            if ($data && $data['kuantitas'] > 1) {
                $query = "UPDATE transaksi_kasir SET kuantitas = kuantitas - 1, total_harga = harga * kuantitas WHERE barang_id = ? AND status = 'O'";
                $stmt = $connected->prepare($query);
                $stmt->bind_param('i', $barang_id);
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Barang berhasil dikurangi';
                } else {
                    $response['message'] = 'Gagal mengurangi barang';
                }
            } else {
                $query = "DELETE FROM transaksi_kasir WHERE barang_id = ? AND status = 'O'";
                $stmt = $connected->prepare($query);
                $response['message'] = 'Kuantitas tidak bisa kurang dari 1';
                $stmt->bind_param('i', $barang_id);
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Barang berhasil dihapus karena kurang dari 0';
                } else {
                    $response['message'] = 'Gagal menghapus barang';
                }
            }
            break;

        case 'hapus':
            // Hapus barang dari keranjang
            $query = "DELETE FROM transaksi_kasir WHERE barang_id = ? AND status = 'O'";
            $stmt = $connected->prepare($query);
            $stmt->bind_param('i', $barang_id);
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Barang berhasil dihapus';
            } else {
                $response['message'] = 'Gagal menghapus barang';
            }
            break;

        // Case untuk menyelesaikan transaksi dan memindahkan barang ke barang_keluar
        // case 'selesaikan_transaksi':
        //     // Ambil data barang dengan status 'O' (Open) dari transaksi_kasir
        //     $result = $connected->query("SELECT t.barang_id, t.kuantitas, t.harga, t.total_harga, b.kategori FROM transaksi_kasir t JOIN barang b ON t.barang_id = b.barang_id WHERE t.status = 'O'");

        //     if ($result->num_rows > 0) {
        //         // Persiapkan query untuk memasukkan data ke dalam tabel barang_keluar
        //         $insertQuery = "INSERT INTO barang_keluar (barang_id, kategori, jumlah_keluar, harga_satuan, total_harga, tanggal, keterangan) VALUES (?, ?, ?, ?, ?, NOW(), 'Penjualan')";
        //         $stmt = $connected->prepare($insertQuery);

        //         // Perulangan untuk setiap barang yang ditemukan
        //         while ($data = $result->fetch_assoc()) {
        //             $barang_id = $data['barang_id'];
        //             $kategori = $data['kategori'];
        //             $jumlah_keluar = $data['kuantitas'];
        //             $harga = $data['harga'];
        //             $total_harga = $data['total_harga'];

        //             // Bind parameter untuk query insert
        //             $stmt->bind_param('isiii', $barang_id, $kategori, $jumlah_keluar, $harga, $total_harga);
        //             $stmt->execute(); // Jalankan query insert
        //         }

        //         $updateQuery = "UPDATE transaksi_kasir SET status = 'C' WHERE status = 'O'";
        //         $connected->query($updateQuery);

        //         // Kirimkan response sukses
        //         echo json_encode(['success' => true, 'message' => 'Transaksi selesai']);
        //     } else {
        //         echo json_encode(['success' => false, 'message' => 'Tidak ada barang di keranjang']);
        //     }
        //     break;


        default:
            $response['message'] = 'Aksi tidak dikenali';
    }
}

echo json_encode($response);
