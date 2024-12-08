<?php
include "../connection.php";
include "../select.php";
include "../insert.php";
include "../update.php";
include "../delete.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["barang_keluar_id"])) {
        if (isset($_GET["barang_keluar_id"]) && isset($_GET['barang_id'])) {
            $barang_id = $_GET['barang_id'];
            $sql_nama_barang = $connected->query("SELECT nama FROM barang WHERE barang_id = $barang_id");
            if ($sql_nama_barang->num_rows > 0) {
                $data_nama = $sql_nama_barang->fetch_assoc();
            }
        }
        $barang_keluar_id = $_GET["barang_keluar_id"];
        $stmt = $connected->prepare($select->selectTable($table_name = "barang_keluar", $fields = "*", $condition = "WHERE barang_keluar_id = ?"));
        $stmt->bind_param("i", $barang_keluar_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        // Gabungkan $data_nama dan $data menjadi satu variabel $data
        if (isset($data_nama)) {
            $data = array_merge($data, $data_nama);
        }

        echo json_encode($data);
    } else {
        if (isset($_GET['tanggalFilter'])) {
            $tanggalFilter = $_GET['tanggalFilter']; // String, ex "dd/mm/yy = 29/12/2024"

            // Format tanggal dari inputan agar sesuai dengan yang ada di dalam databse
            // Konversi format tanggal dari dd/mm/yyyy menjadi yyyy-mm-dd
            $dateObject = DateTime::createFromFormat('d/m/Y', $tanggalFilter);
            if ($dateObject) {
                $tanggalFormatted = $dateObject->format('Y-m-d'); // Format ke yyyy-mm-dd
            } else {
                die("Format tanggal tidak valid!"); // Handle jika format tanggal salah
            }


            $result = $connected->query($select->selectTable($table_name = "barang_keluar", $fields = "bk.*, b.nama ", $condition = "bk JOIN barang b ON bk.barang_id = b.barang_id WHERE bk.tanggal_keluar = '$tanggalFormatted'"));

            $data = [];
            $totalKeseluruhan = 0; // Variabel untuk menghitung total keseluruhan

            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $i++;
                $row['no'] = $i;

                $row['tanggal_keluar'] = date("d/m/Y", strtotime($row['tanggal_keluar']));

                // Tambahkan ke total keseluruhan
                $totalKeseluruhan += $row['total_harga'];

                if (!empty($row['exp'])) {
                    $row['exp'] = date("d/m/Y", strtotime($row['exp']));
                }

                $row['action'] = '<button type="button" class="edit btn btn-primary btn-sm" data-barang_keluar_id="' . $row['barang_keluar_id'] . '" data-barang_id="' . $row['barang_id'] . '" data-toggle="modal"><i class="fas fa-pen"></i></button>
            <button type="button" class="delete btn btn-danger btn-sm" data-barang_keluar_id="' . $row['barang_keluar_id'] . '" data-toggle="modal"><i class="fas fa-trash"></i></button>';

                $data[] = $row;
            }

            echo json_encode(["data" => $data, "total_harga" => $totalKeseluruhan]);
        } else {
            $result = $connected->query($select->selectTable($table_name = "barang_keluar", $fields = "bk.*, b.nama ", $condition = "bk JOIN barang b ON bk.barang_id = b.barang_id"));

            $data = [];

            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $i++;
                $row['no'] = $i;

                $row['tanggal_keluar'] = date("d/m/Y", strtotime($row['tanggal_keluar']));
                if (!empty($row['exp'])) {
                    $row['exp'] = date("d/m/Y", strtotime($row['exp']));
                }

                $row['action'] = '<button type="button" class="edit btn btn-primary btn-sm" data-barang_keluar_id="' . $row['barang_keluar_id'] . '" data-barang_id="' . $row['barang_id'] . '" data-toggle="modal"><i class="fas fa-pen"></i></button>
            <button type="button" class="delete btn btn-danger btn-sm" data-barang_keluar_id="' . $row['barang_keluar_id'] . '" data-toggle="modal"><i class="fas fa-trash"></i></button>';

                $data[] = $row;
            }

            echo json_encode(["data" => $data]);
        }
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

    $stmt = $connected->prepare($insert->selectTable($table_name = "barang_keluar", $condition = "(barang_id, nomor_bacth, tanggal_masuk, jumlah_masuk, exp, supplier, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?)"));
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
    // Ambil data dari input yang akan digunakan untuk update barang_keluar
    $barang_keluar_id = htmlspecialchars($data["barang_keluar_id"]); // i
    $barang_id = htmlspecialchars($data["barang_id"]); // i
    $kategori = htmlspecialchars($data["kategori"]);
    $jumlah_keluar = htmlspecialchars($data["jumlah_keluar"]); // i
    $jumlah_keluar_old = htmlspecialchars($data["jumlah_keluar_old"]); // i untuk menyesuaikan stok pada tabel barang lalu ditambahkan/dikurangi sesuai jumlah_keluar baru
    $harga_satuan = htmlspecialchars($data["harga_satuan"]);
    $total_harga = htmlspecialchars($data["total_harga"]);
    $tanggal_keluar = htmlspecialchars($data["tanggal_keluar"]);
    $keterangan = htmlspecialchars($data["keterangan"]);

    // Step 1: Ambil stok saat ini dan jumlah_keluar awal dari database
    $barang_keluar_id = htmlspecialchars($data["barang_keluar_id"]);
    $jumlah_keluar_baru = htmlspecialchars($data["jumlah_keluar"]);

    $stmt_select = $connected->prepare("SELECT jumlah_keluar, barang_id FROM barang_keluar WHERE barang_keluar_id = ?");
    $stmt_select->bind_param("i", $barang_keluar_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $row = $result->fetch_assoc();

    $jumlah_keluar_awal = $row['jumlah_keluar'];
    $barang_id = $row['barang_id'];

    // Step 2: Hitung selisih jumlah_keluar
    $selisih = $jumlah_keluar_awal - $jumlah_keluar_baru; // Selisih bisa positif atau negatif

    // Step 3: Update stok di tabel barang
    $stmt_stok = $connected->prepare("UPDATE barang SET stok = stok + ? WHERE barang_id = ?");
    $stmt_stok->bind_param("ii", $selisih, $barang_id);

    if ($stmt_stok->execute()) {
        // Step 4: Update jumlah_keluar di tabel barang_keluar
        $stmt_update_barang_keluar = $connected->prepare("UPDATE barang_keluar SET jumlah_keluar = ? WHERE barang_keluar_id = ?");
        $stmt_update_barang_keluar->bind_param("ii", $jumlah_keluar_baru, $barang_keluar_id);

        if ($stmt_update_barang_keluar->execute()) {
            echo "Berhasil memperbarui jumlah keluar dan stok barang.";
        } else {
            echo "Gagal memperbarui jumlah keluar: " . $stmt_update_barang_keluar->error;
        }
    } else {
        echo "Gagal memperbarui stok barang: " . $stmt_stok->error;
    }

    // Tutup statement
    $stmt_select->close();
    $stmt_stok->close();
    $stmt_update_barang_keluar->close();


    // Update data pada tabel barang_keluar
    // $stmt_barang_keluar = $connected->prepare("UPDATE barang_keluar SET kategori = ?, jumlah_keluar = ?, harga_satuan = ?, total_harga = ?, tanggal_keluar = ?, keterangan = ? WHERE barang_keluar_id = ?");
    // $stmt_barang_keluar->bind_param("siiissi", $kategori, $jumlah_keluar, $harga_satuan, $total_harga, $tanggal_keluar, $keterangan, $barang_keluar_id);

    // // Ambil stok barang dari tabel barang sebelum melakukan perubahan
    // $result_stok_barang = $connected->query("SELECT stok FROM barang WHERE barang_id = $barang_id");
    // $jumlah_stok_barang = $result_stok_barang->fetch_assoc()['stok'];

    // // Hitung stok baru: kurangi stok saat ini dengan jumlah_keluar_old, lalu tambahkan jumlah_keluar yang baru
    // $jumlah_real_stok_barang = (int) $jumlah_stok_barang - (int) $jumlah_keluar_old + (int) $jumlah_keluar;

    // // Update stok baru ke tabel barang
    // $stmt_stok_barang = $connected->prepare("UPDATE barang SET stok = ? WHERE barang_id = ?");
    // $stmt_stok_barang->bind_param("ii", $jumlah_real_stok_barang, $barang_id);

    // // Eksekusi query dan cek keberhasilan
    // if ($stmt_barang_keluar->execute() && $stmt_stok_barang->execute()) {
    //     echo "Berhasil mengedit data barang keluar dan memperbarui stok barang.";
    // } else {
    //     echo "Gagal mengedit data barang keluar: " . $stmt_barang_keluar->error;
    // }

    // // Tutup statement
    // $stmt_barang_keluar->close();
    // $stmt_stok_barang->close();
} else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    // Hapus barang keluar
    parse_str(file_get_contents("php://input"), $data);
    $barang_keluar_id = $data["barang_keluar_id"];

    // Langkah 1: Ambil barang_id dan jumlah_keluar dari tabel barang_keluar sebelum dihapus
    $select_stmt = $connected->prepare("SELECT barang_id, jumlah_keluar FROM barang_keluar WHERE barang_keluar_id = ?");
    $select_stmt->bind_param("i", $barang_keluar_id);
    $select_stmt->execute();
    $result = $select_stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $barang_id = $row['barang_id'];
        $jumlah_keluar = $row['jumlah_keluar'];

        // Langkah 2: Tambah stok barang di tabel barang
        $update_stmt = $connected->prepare("UPDATE barang SET stok = stok + ? WHERE barang_id = ?");
        $update_stmt->bind_param("ii", $jumlah_keluar, $barang_id);

        if ($update_stmt->execute()) {
            // Langkah 3: Jika stok berhasil ditambahkan, hapus data dari tabel barang_keluar
            $delete_stmt = $connected->prepare("DELETE FROM barang_keluar WHERE barang_keluar_id = ?");
            $delete_stmt->bind_param("i", $barang_keluar_id);

            if ($delete_stmt->execute()) {
                echo "Berhasil menghapus barang keluar dan stok telah diperbarui.";
            } else {
                // Jika gagal menghapus dari tabel barang_keluar
                echo "Gagal menghapus dari barang_keluar: " . $delete_stmt->error;
            }

            $delete_stmt->close();
        } else {
            // Jika gagal memperbarui stok
            echo "Gagal memperbarui stok: " . $update_stmt->error;
        }

        $update_stmt->close();
    } else {
        // Jika data tidak ditemukan di tabel barang_keluar
        echo "Data tidak ditemukan untuk barang_keluar_id: " . $barang_keluar_id;
    }

    $select_stmt->close();
}








