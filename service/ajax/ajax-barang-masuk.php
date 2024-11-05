<?php
include "../connection.php";
include "../select.php";
include "../insert.php";
include "../update.php";
include "../delete.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // barang
    // if (isset($_GET["barang_masuk_id"])) {
    //     if (isset($_GET["barang_masuk_id"]) && isset($_GET['barang_id'])) {
    //         $barang_id = $_GET['barang_id'];
    //         $sql_nama_barang = $connected->query("SELECT nama FROM barang WHERE barang_id = $barang_id");
    //         if ($sql_nama_barang->num_rows > 0) {
    //             $data_nama = $sql_nama_barang->fetch_assoc();
    //             echo json_encode($data_nama);
    //         }
    //     }
    //     $barang_masuk_id = $_GET["barang_masuk_id"];
    //     $stmt = $connected->prepare($select->selectTable($table_name = "barang_masuk", $fields = "*", $condition = "WHERE barang_masuk_id = ?"));
    //     $stmt->bind_param("i", $barang_masuk_id);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     $data = $result->fetch_assoc();
    //     echo json_encode($data);
    // }
    if (isset($_GET["barang_masuk_id"])) {
        if (isset($_GET["barang_masuk_id"]) && isset($_GET['barang_id'])) {
            $barang_id = $_GET['barang_id'];
            $sql_nama_barang = $connected->query("SELECT nama FROM barang WHERE barang_id = $barang_id");
            if ($sql_nama_barang->num_rows > 0) {
                $data_nama = $sql_nama_barang->fetch_assoc();
            }
        }
        $barang_masuk_id = $_GET["barang_masuk_id"];
        $stmt = $connected->prepare($select->selectTable($table_name = "barang_masuk", $fields = "*", $condition = "WHERE barang_masuk_id = ?"));
        $stmt->bind_param("i", $barang_masuk_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        // Gabungkan $data_nama dan $data menjadi satu variabel $data
        if (isset($data_nama)) {
            $data = array_merge($data, $data_nama);
        }

        echo json_encode($data);
    } else {
        $result = $connected->query($select->selectTable($table_name = "barang_masuk", $fields = "bm.*, b.nama ", $condition = "bm JOIN barang b ON bm.barang_id = b.barang_id"));

        $data = [];

        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $i++;
            $row['no'] = $i;

            $row['tanggal_masuk'] = date("d/m/Y", strtotime($row['tanggal_masuk']));
            if (!empty($row['exp'])) {
                $row['exp'] = date("d/m/Y", strtotime($row['exp']));
            }

            $row['action'] = '<button type="button" class="edit btn btn-primary btn-sm" data-barang_masuk_id="' . $row['barang_masuk_id'] . '" data-barang_id="' . $row['barang_id'] . '" data-toggle="modal"><i class="fas fa-pen"></i></button>
            <button type="button" class="delete btn btn-danger btn-sm" data-barang_masuk_id="' . $row['barang_masuk_id'] . '" data-toggle="modal"><i class="fas fa-trash"></i></button>';

            $data[] = $row;
        }

        echo json_encode(["data" => $data]);
    }

} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // tambah barang
    $barang_id = htmlspecialchars($_POST["pilih_barang"]); // value id
    $nomor_bacth = htmlspecialchars($_POST["nomor_bacth"]);
    $tanggal_masuk = htmlspecialchars($_POST["tanggal_masuk"]);
    $jumlah_masuk = htmlspecialchars($_POST["jumlah_masuk"]);
    $exp = htmlspecialchars($_POST["exp"]);
    if (empty($exp)) {
        $exp = NULL;
    }
    $supplier = $_POST["supplier"];
    $keterangan = $_POST["keterangan"];

    $stmt = $connected->prepare($insert->selectTable($table_name = "barang_masuk", $condition = "(barang_id, nomor_bacth, tanggal_masuk, jumlah_masuk, exp, supplier, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?)"));
    $stmt->bind_param("ississs", $barang_id, $nomor_bacth, $tanggal_masuk, $jumlah_masuk, $exp, $supplier, $keterangan);

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
        // echo "Barang berhasil ditambahkan dan stok diperbarui.";
    } else {
        echo "Gagal memperbarui stok. Transaksi dibatalkan." . $stmt->error;
    }
    $stmt->close();
} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    parse_str(file_get_contents("php://input"), $data);
    $barang_masuk_id = htmlspecialchars($data["barang_masuk_id"]); // i
    $barang_id = htmlspecialchars($data["barang_id"]); // i
    $nomor_bacth = htmlspecialchars($data["nomor_bacth"]);
    $tanggal_masuk = htmlspecialchars($data["tanggal_masuk"]);
    $jumlah_masuk = htmlspecialchars($data["jumlah_masuk"]); // i 
    $jumlah_masuk_old = htmlspecialchars($data["jumlah_masuk_old"]); // i untuk mengurangi stok pada tabel barang lalu ditambahkan dengan jumlah masuk yang baru
    $exp = htmlspecialchars($data["exp"]);
    if (empty($exp)) {
        $exp = NULL;
    }
    $supplier = htmlspecialchars($data["supplier"]);
    $keterangan = htmlspecialchars($data["keterangan"]);

    // update data pada tabel barang_masuk
    $stmt_barang_masuk = $connected->prepare($update->selectTable($table_name = "barang_masuk", $condition = "nomor_bacth = ?, tanggal_masuk = ?, jumlah_masuk = ?, exp = ?, supplier = ?, keterangan = ? WHERE barang_masuk_id = ?"));
    $stmt_barang_masuk->bind_param("ssisssi", $nomor_bacth, $tanggal_masuk, $jumlah_masuk, $exp, $supplier, $keterangan, $barang_masuk_id);

    // ambil stok pada barang masuk lalu kurangi dengan jumlah_masuk_old
    $result_stok_barang = $connected->query("SELECT stok FROM barang WHERE barang_id = $barang_id");
    $jumlah_stok_barang = $result_stok_barang->fetch_assoc()['stok'];

    // kurangi stok barang saat ini dengan jumlah_masuk_old
    $jumlah_real_stok_barang = (int) $jumlah_stok_barang - (int) $jumlah_masuk_old + (int) $jumlah_masuk;

    // update jumlah_real_stok_barang ke tabel barang
    $stmt_stok_barang = $connected->prepare($update->selectTable($table_name = "barang", $condition = "stok = ? WHERE barang_id = ?"));
    $stmt_stok_barang->bind_param("ii", $jumlah_real_stok_barang, $barang_id);

    if ($stmt_barang_masuk->execute() && $stmt_stok_barang->execute()) {
        echo "Berhasil mengedit data barang masuk";
    } else {
        echo "Gagal mengedit data barang masuk " . $stmt->error;
    }

    $stmt_barang_masuk->close();
    $stmt_stok_barang->close();
} else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    // Hapus barang masuk
    parse_str(file_get_contents("php://input"), $data);
    $barang_masuk_id = $data["barang_masuk_id"];

    // Langkah 1: Ambil barang_id dan jumlah_masuk dari tabel barang_masuk sebelum dihapus
    $select_stmt = $connected->prepare("SELECT barang_id, jumlah_masuk FROM barang_masuk WHERE barang_masuk_id = ?");
    $select_stmt->bind_param("i", $barang_masuk_id);
    $select_stmt->execute();
    $result = $select_stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $barang_id = $row['barang_id'];
        $jumlah_masuk = $row['jumlah_masuk'];

        // Langkah 2: Kurangi stok barang di tabel barang
        $update_stmt = $connected->prepare("UPDATE barang SET stok = stok - ? WHERE barang_id = ?");
        $update_stmt->bind_param("ii", $jumlah_masuk, $barang_id);

        if ($update_stmt->execute()) {
            // Langkah 3: Jika stok berhasil dikurangi, hapus data dari tabel barang_masuk
            $stmt = $connected->prepare("DELETE FROM barang_masuk WHERE barang_masuk_id = ?");
            $stmt->bind_param("i", $barang_masuk_id);

            if ($stmt->execute()) {
                echo "Berhasil menghapus barang masuk dan stok telah diperbarui.";
            } else {
                // Jika gagal menghapus dari tabel barang_masuk
                echo "Gagal menghapus dari barang_masuk: " . $stmt->error;
            }

            $stmt->close();
        } else {
            // Jika gagal memperbarui stok
            echo "Gagal memperbarui stok: " . $update_stmt->error;
        }

        $update_stmt->close();
    } else {
        // Jika data tidak ditemukan di tabel barang_masuk
        echo "Data tidak ditemukan untuk barang_masuk_id: " . $barang_masuk_id;
    }

    $select_stmt->close();
}





// else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
//     // Delete barang
//     parse_str(file_get_contents("php://input"), $data);
//     $barang_masuk_id = $data["barang_masuk_id"];

//     $stmt = $connected->prepare($delete->select_table($table_name = "barang_masuk", $condition = "WHERE barang_masuk_id = ?"));
//     $stmt->bind_param("i", $barang_masuk_id);

//     if ($stmt->execute()) {
//         echo "Berhasil menghapus";
//     } else {
//         echo "Gagal menghapus: " . $stmt->error;
//     }

//     $stmt->close();
// }

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


