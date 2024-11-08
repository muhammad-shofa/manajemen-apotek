<?php
include "../connection.php";
include "../select.php";
include "../insert.php";
include "../update.php";
include "../delete.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // barang
    if (isset($_GET["barang_id"])) {
        $barang_id = $_GET["barang_id"];
        $stmt = $connected->prepare($select->selectTable($table_name = "barang", $fields = "*", $condition = "WHERE barang_id = ?"));
        $stmt->bind_param("i", $barang_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        $result = $connected->query($select->selectTable($table_name = "barang", $fields = "*", $condition = ""));

        $data = [];

        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $i++;
            $row['no'] = $i;
            $row['harga_satuan'] = number_format($row['harga_satuan'], 0, ',', '.');

            if (!empty($row['exp'])) {
                $row['exp'] = date("d/m/Y", strtotime($row['exp']));
            }

            $row['action'] = '<button type="button" class="edit btn btn-primary btn-sm" data-barang_id="' . $row['barang_id'] . '" data-toggle="modal"><i class="fas fa-pen"></i></button>
            <button type="button" class="delete btn btn-danger btn-sm" data-barang_id="' . $row['barang_id'] . '" data-toggle="modal"><i class="fas fa-trash"></i></button>';

            $data[] = $row;
        }

        echo json_encode(["data" => $data]);
    }

} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // $nomor_bacth = htmlspecialchars($_POST["nomor_bacth"]);
    $nama = htmlspecialchars($_POST["nama"]);
    $kategori = htmlspecialchars($_POST["kategori"]);
    $harga_satuan = htmlspecialchars($_POST["harga_satuan"]); // i
    $harga_butir = htmlspecialchars($_POST["harga_butir"]); // i
    // $exp = htmlspecialchars($_POST["exp"]);
    // if (empty($exp)) {
    //     $exp = NULL;
    // }
    $stok = htmlspecialchars($_POST["stok"]); // i
    $satuan = htmlspecialchars($_POST["satuan"]);

    $stmt = $connected->prepare($insert->selectTable($table_name = "barang", $condition = "(nama, kategori, harga_satuan, harga_butir, stok, satuan) VALUES (?, ?, ?, ?, ?, ?)"));
    $stmt->bind_param("ssiiis", $nama, $kategori, $harga_satuan, $harga_butir, $stok, $satuan);

    if ($stmt->execute()) {
        echo "Berhasil menambah tipe barang baru";
    } else {
        echo "Gagal menambah tipe barang baru " . $stmt->error;
    }

} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    parse_str(file_get_contents("php://input"), $data);
    $barang_id = $data["barang_id"];
    $nama = $data["nama"];
    $kategori = $data["kategori"];
    $harga_satuan = $data["harga_satuan"]; // i
    $harga_butir = $data["harga_butir"]; // i
    $stok = $data["stok"]; // i
    $satuan = $data["satuan"];

    $stmt = $connected->prepare($update->selectTable($table_name = "barang", $condition = "nama = ?, kategori = ?, harga_satuan = ?, harga_butir = ?, stok = ?, satuan = ? WHERE barang_id = ?"));
    $stmt->bind_param("ssiiisi", $nama, $kategori, $harga_satuan, $harga_butir, $stok, $satuan, $barang_id);

    if ($stmt->execute()) {
        echo "Berhasil mengedit data barang";
    } else {
        echo "Gagal mengedit data barang " . $stmt->error;
    }

    $stmt->close();
} else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    // Hapus barang 
    parse_str(file_get_contents("php://input"), $data);
    $barang_id = $data["barang_id"];

    $stmt = $connected->prepare("DELETE FROM barang WHERE barang_id = ?");
    $stmt->bind_param("i", $barang_id);

    if ($stmt->execute()) {
        echo "Berhasil menghapus tipe barang";
    } else {
        echo "Gagal menghapus tipe barang" . $stmt->error;
    }
}


// else if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // tambah temua
//     $username = $_POST["username"];
//     $password = $_POST["password"];
//     $nama_lengkap = $_POST["nama_lengkap"];
//     $email = $_POST["email"];
//     $tanggal_lahir = $_POST["tanggal_lahir"];
//     $jenis_kelamin = $_POST["jenis_kelamin"];
//     $role = $_POST["role"];

//     $hash_password = hash("sha256", $password);

//     $stmt = $connected->prepare($insert->selectTable($table_name = "users", $condition = "(username, password, nama_lengkap, email, tanggal_lahir, jenis_kelamin, role) VALUES (?, ?, ?, ?, ?, ?, ?)"));
//     $stmt->bind_param("sssssss", $username, $hash_password, $nama_lengkap, $email, $tanggal_lahir, $jenis_kelamin, $role);

//     if ($stmt->execute()) {
//         echo "Berhasil menambahkan pengguna";
//     } else {
//         echo "Gagal menambahkan pengguna: " . $stmt->error;
//     }
//     $stmt->close();
// }