<?php
include "../connection.php";
include "../select.php";
include "../insert.php";
include "../update.php";
include "../delete.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // barang
    if (isset($_GET["barang_masuk_id"])) {
        $barang_masuk_id = $_GET["barang_masuk_id"];
        $stmt = $connected->prepare($select->selectTable($table_name = "barang_masuk", $fields = "*", $condition = "WHERE barang_masuk_id = ?"));
        $stmt->bind_param("i", $barang_masuk_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        $result = $connected->query($select->selectTable($table_name = "barang_masuk", $fields = "bm.*, b.nama ", $condition = "bm JOIN barang b ON bm.barang_id = b.barang_id"));

        // $result = $connected->query("SELECT bm.*, b.nama FROM barang_masuk bm JOIN barang b ON bm.barang_id = b.barang_id");

        $data = [];

        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $i++;
            $row['no'] = $i;

            $row['action'] = '<button type="button" class="edit btn btn-primary btn-sm" data-barang_id="' . $row['barang_id'] . '" data-toggle="modal"><i class="fas fa-pen"></i></button>
            <button type="button" class="delete btn btn-danger btn-sm" data-barang_id="' . $row['barang_id'] . '" data-toggle="modal"><i class="fas fa-trash"></i></button>';

            $data[] = $row;
        }

        echo json_encode(["data" => $data]);
    }

} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // tambah barang
    $barang_id = $_POST["barang_id"]; // value id
    $tanggal_masuk = $_POST["tanggal_masuk"];
    $jumlah_masuk = $_POST["jumlah_masuk"];
    $supplier = $_POST["supplier"];
    $keterangan = $_POST["keterangan"];

    $stmt = $connected->prepare($insert->selectTable($table_name = "barang_masuk", $condition = "(barang_id, tanggal_masuk, jumlah_masuk, supplier, keterangan) VALUES (?, ?, ?, ?, ?)"));
    $stmt->bind_param("isiss", $barang_id, $tanggal_masuk, $jumlah_masuk, $supplier, $keterangan);

    if ($stmt->execute()) {
        // Query untuk memperbarui stok di tabel barang
        $update_stmt = $connected->prepare("UPDATE barang SET stok = stok + ? WHERE barang_id = ?");
        $update_stmt->bind_param("ii", $jumlah_masuk, $barang_id);

        // Eksekusi query update
        if ($update_stmt->execute()) {
            // Jika kedua query berhasil, commit transaksi
            $connected->commit();
            echo "Barang berhasil ditambahkan dan stok diperbarui.";
        } else {
            // Jika update stok gagal, rollback transaksi
            $connected->rollback();
            echo "Gagal memperbarui stok. Transaksi dibatalkan.";
        }

        echo "Barang berhasil ditambahkan dan stok diperbarui.";
    } else {
        echo "Gagal memperbarui stok. Transaksi dibatalkan." . $stmt->error;
        // echo "Gagal menambahkan pengguna: " . $stmt->error;
    }
    $stmt->close();
}

// else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
//     // Update 
//     parse_str(file_get_contents("php://input"), $data);
//     $temuan_id = $data["temuan_id"];
//     $rekomendasi_tindak_lanjut = $data["rekomendasi_tindak_lanjut"];
//     $status = $data["status"];
//     $dokumentasi_tl = $data["dokumentasi_tl"];

//     $stmt = $connected->prepare($update->selectTable($table_name = "temuan", $condition = "rekomendasi_tindak_lanjut = ?, status = ?, dokumentasi_tl = ? WHERE temuan_id = ?"));
//     $stmt->bind_param("sssi", $rekomendasi_tindak_lanjut, $status, $dokumentasi_tl, $temuan_id);

//     if ($stmt->execute()) {
//         echo "Berhasil mengupdate";
//     } else {
//         echo "Gagal mengupdate " . $stmt->error;
//     }

//     $stmt->close();
// }


