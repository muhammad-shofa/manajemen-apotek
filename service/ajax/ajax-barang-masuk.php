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
    $nomor_bacth = $_POST["nomor_bacth"];
    $nama = $_POST["nama"];
    $kategori = $_POST["kategori"];
    $harga_satuan = $_POST["harga_satuan"];
    $exp = $_POST["exp"];
    $stok = $_POST["stok"];
    $satuan = $_POST["satuan"];

    $stmt = $connected->prepare($insert->selectTable($table_name = "barang_masuk", $condition = "(nomor_bacth, nama, kategori, harga_satuan, exp, stok, satuan) VALUES (?, ?, ?, ?, ?, ?, ?)"));
    $stmt->bind_param("sssssss", $username, $hash_password, $nama_lengkap, $email, $tanggal_lahir, $jenis_kelamin, $role);

    if ($stmt->execute()) {
        echo "Berhasil menambahkan pengguna";
    } else {
        echo "Gagal menambahkan pengguna: " . $stmt->error;
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

