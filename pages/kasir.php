<?php
include "../service/connection.php";
include "../service/select.php";
include "../service/insert.php";
include "../service/update.php";
include "../service/delete.php";
session_start();

// check login
if ($_SESSION["is_login"] == false) {
    header("location: ../index.php");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Apotek | Kasir</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        /* Untuk browser berbasis WebKit (Chrome, Safari, Edge terbaru) */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            /* Hilangkan spinner */
            margin: 0;
            /* Atur margin ke 0 */
        }

        /* Untuk Firefox */
        input[type=number] {
            -moz-appearance: textfield;
            /* Ubah tampilan ke teks biasa */
        }

        /* Untuk Edge (jika WebKit belum diterapkan sepenuhnya) */
        input[type=number]::-ms-inner-spin-button,
        input[type=number]::-ms-outer-spin-button {
            -ms-appearance: none;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- sidebar -->
        <?php include "../layout/sidebar.php" ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- navbar -->
                <?php include "../layout/navbar.php" ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Kasir Apotek</h1>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between">
                        <!-- daftar barang kasir start -->
                        <div class="card pencarian shadow mb-4 p-3 text-dark">
                            <input type="text" id="searchInput" class="form-control w-100" placeholder="Cari produk...">
                            <div id="resultContainer" class="d-flex flex-wrap mt-2 text-center">
                                <?php
                                $result = $connected->query("SELECT * FROM barang LIMIT 4");
                                if ($result->num_rows > 0) {
                                    while ($data = $result->fetch_assoc()) {
                                        ?>
                                        <div class="card m-2 p-2">
                                            <h5 class="text-dark namaBarang"><?= $data['nama'] ?></h5>
                                            <p class="text-dark hargaSatuan">
                                                <?php
                                                if ($data['harga_satuan']) {
                                                    echo $data['harga_satuan'];
                                                } else {
                                                    echo $data['harga_butir'];
                                                }
                                                ?>
                                            </p>
                                            <button
                                                class="tambahBarangKeKeranjang rounded rounded-5 btn btn-primary w-50 m-auto"
                                                data-barang_id="<?= $data['barang_id'] ?>">
                                                <i class="fas fa-tada"></i><i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    <?php }
                                } ?>
                            </div>
                        </div>
                        <!-- daftar barang kasir end -->

                        <!-- keranjang start -->
                        <div class="card keranjang shadow mb-4 p-3 text-dark">
                            <h4 class="text-warning">Keranjang <i class="fa fa-shopping-cart"></i>
                            </h4>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <p>Nama Barang</p>
                                <p>|</p>
                                <p>Kuantitas</p>
                                <p>|</p>
                                <p>Harga</p>
                                <p>|</p>
                                <p>Total Harga</p>
                            </div>
                            <hr>
                            <div id="keranjangContainer" class="w-100 d-flex flex-column p-2">
                                <!-- Produk dari daftar produk akan ditambahkan di sini -->
                                <?php
                                $result = $connected->query("SELECT b.nama, t.kuantitas, t.harga, t.total_harga FROM transaksi_kasir t JOIN barang b ON t.barang_id = b.barang_id WHERE t.status = 'O'");
                                if ($result->num_rows > 0) {
                                    while ($data = $result->fetch_assoc()) {
                                        ?>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="text-dark">
                                                <?= $data['nama'] ?>
                                            </h5>
                                            <div class="d-flex align-items-center">
                                                <button class=" btn btn-sm btn-outline-secondary me-2">
                                                    <i class=" fas fa-minus"></i>
                                                </button>
                                                <input type="number" style="max-width: 80px;"
                                                    class="jumlahKeluar form-control mx-2" value="<?= $data['kuantitas'] ?>"
                                                    min="1">
                                                <button class="btn btn-sm btn-outline-secondary ms-2">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <p class="harga" data-barang_id="<?= $data['barang_id'] ?>">
                                                <?= $data['harga'] ?>
                                            </p>
                                            <!-- <p><= $data['harga'] ?></p> -->
                                            <p class="totalHarga">Rp <?= $data['total_harga'] ?></p>
                                            <button class="btn btn-sm btn-outline-danger ms-2">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    <?php }
                                } ?>
                            </div>
                            <!-- Total Harga Semua Barang -->
                            <div class="mt-3">
                                <form method="POST" id="formKeranjang" onsubmit="return false;">
                                    <h4>Total Harga: <span id="totalHargaSemuaBarang">Rp 0</span></h4>
                                    <button type="button" class="btn btn-success" name="btnSelesai"
                                        id="btnSelesai">Selesai</button>
                                </form>
                            </div>
                        </div>
                        <!-- keranjang end -->
                    </div>

                </div>
                <!-- /.container-fluid -->

                <!-- Footer -->
                <?php include "../layout/footer.php" ?>
                <!-- End of Footer -->

            </div>
            <!-- End of Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Include Select2 CSS and JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        // Fungsi untuk memperbarui keranjang
        function loadKeranjang() {
            $.ajax({
                url: '../service/ajax/ajax-kasir-2.php', // File PHP backend
                type: 'GET',
                success: function (response) {
                    $('#keranjangContainer').html(response); // Perbarui kontainer dengan data baru
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('Gagal memuat keranjang.');
                }
            });
        }

        // Fungsi untuk memperbarui total harga semua barang yang ada di dalam keranjang
        function loadTotalSemuaHargaKeranjang() {
            $.ajax({
                url: '../service/ajax/ajax-kasir-2.php?total-semua-harga-keranjang=1', // File PHP backend
                type: 'GET',
                success: function (response) {
                    $('#totalHargaSemuaBarang').html(response); // Perbarui kontainer dengan data baru
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('Gagal memuat keranjang.');
                }
            });
        }

        // Panggil fungsi saat halaman pertama kali dimuat
        $(document).ready(function () {
            loadKeranjang();
            loadTotalSemuaHargaKeranjang();

            // cari nama barang
            $('#searchInput').on('keyup', function () {
                var query = $(this).val();

                if (query.length > 0) {
                    $.ajax({
                        url: '../service/ajax/ajax-cari-barang.php',
                        type: 'GET',
                        data: { query: query },
                        success: function (data) {
                            var results = JSON.parse(data);
                            var html = '';

                            if (results.length > 0) {
                                results.forEach(function (item) {
                                    html += `
                                        <div class="card m-2 p-2">
                                            <h5 class="text-dark namaBarang">${item.nama}</h5>
                                            <p class="text-dark">Rp ${item.harga_satuan || item.harga_butir}</p>                                            
                                            <button
                                                class="tambahBarangKeKeranjang rounded rounded-5 btn btn-primary w-50 m-auto"
                                                data-barang_id=${item.barang_id}
                                                <i class="fas fa-tada"></i><i class="fas fa-plus"></i>
                                            </button>
                                        </div>`;
                                });
                            } else {
                                html = '<p>Produk tidak ditemukan.</p>';
                            }
                            $('#resultContainer').html(html);
                        }
                    });
                }
            });

            // Malam sabtu, 7 Desember 2024 TERBARU
            $('#resultContainer').on('click', '.tambahBarangKeKeranjang', function () {
                let barang_id = $(this).data('barang_id'); // Mengambil barang_id dari data-atribut

                $.ajax({
                    url: '../service/ajax/ajax-kasir-2.php?mulai_transaksi=1', // File backend untuk memproses
                    type: 'POST',
                    data: { barang_id: barang_id }, // Mengirimkan barang_id ke backend
                    success: function (response) {
                        // Tampilkan pesan berhasil atau update UI
                        loadKeranjang();
                        loadTotalSemuaHargaKeranjang();
                    },
                    error: function (xhr, status, error) {
                        // Tampilkan pesan error jika gagal
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Coba lagi.' + barang_id);
                    }
                });
            });

            $(document).on('click', '.btn-decrease, .btn-increase, .btn-delete', function () {
                const barang_id = $(this).data('id');
                let action = '';

                if ($(this).hasClass('btn-decrease')) {
                    action = 'kurang';
                } else if ($(this).hasClass('btn-increase')) {
                    action = 'tambah';
                } else if ($(this).hasClass('btn-delete')) {
                    action = 'hapus';
                }

                $.ajax({
                    url: '../service/ajax/keranjang-handler.php',
                    type: 'POST',
                    data: { action: action, barang_id: barang_id },
                    success: function (response) {
                        const res = JSON.parse(response);
                        if (res.success) {
                            // alert(res.message);
                            loadKeranjang(); // Memperbarui keranjang
                            loadTotalSemuaHargaKeranjang();
                        } else {
                            alert(res.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan.');
                    }
                });
            });

            // Event listener untuk input kuantitas
            $('#keranjangContainer').on('change', '.jumlahKeluar', function () {
                let barang_id = $(this).data('id');
                let kuantitas = $(this).val();

                $.ajax({
                    url: '../service/ajax/keranjang-handler.php', // Backend untuk update data
                    type: 'POST',
                    data: { action: 'update', barang_id: barang_id, kuantitas: kuantitas },
                    success: function () {
                        loadKeranjang(); // Perbarui keranjang setelah operasi berhasil
                        loadTotalSemuaHargaKeranjang();
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        alert('Gagal memperbarui kuantitas.');
                    }
                });
            });

            // Event listener untuk mode edit harga
            $('#keranjangContainer').on('dblclick', '.harga', function () {
                let hargaElement = $(this);
                let barang_id = hargaElement.data('barang_id'); // Ambil barang_id dari data-id
                let hargaLama = hargaElement.text().trim(); // Ambil teks harga lama

                // Hilangkan "Rp" dan format angka (titik sebagai pemisah ribuan)
                hargaLama = hargaLama.replace(/[^\d]/g, ''); // Menghapus semua karakter kecuali angka

                // Ubah <p> menjadi input number
                let input = $(`<input type="number" min="0" value="${hargaLama}" class="form-control" style="max-width: 100px;">`);
                hargaElement.replaceWith(input);

                // Fokus pada input
                input.focus();

                // Ketika user menekan Enter
                input.on('keypress', function (e) {
                    if (e.which === 13) { // Enter key
                        let hargaBaru = input.val();

                        // Kirim data ke backend melalui AJAX
                        $.ajax({
                            url: '../service/ajax/keranjang-handler.php',
                            type: 'POST',
                            data: {
                                action: 'update_harga',
                                barang_id: barang_id,
                                harga_baru: hargaBaru
                            },
                            success: function (response) {
                                const data = JSON.parse(response);
                                if (data.success) {
                                    // Format kembali harga dengan "Rp" dan titik
                                    let hargaFormatted = `Rp ${parseInt(hargaBaru).toLocaleString('id-ID')}`;
                                    input.replaceWith(`<p class="harga" data-barang_id="${barang_id}">${hargaFormatted}</p>`);
                                    loadKeranjang(); // Perbarui keranjang setelah operasi berhasil
                                    loadTotalSemuaHargaKeranjang();
                                } else {
                                    alert(data.message);
                                }
                            },
                            error: function () {
                                alert('Gagal memperbarui harga.');
                            }
                        });
                    }
                });

                // Jika kehilangan fokus, kembalikan ke mode teks dengan harga lama
                input.on('blur', function () {
                    let hargaFormatted = `Rp ${parseInt(hargaLama).toLocaleString('id-ID')}`;
                    input.replaceWith(`<p class="harga" data-barang_id="${barang_id}">${hargaFormatted}</p>`);
                });
            });


        });

    </script>

</body>

</html>