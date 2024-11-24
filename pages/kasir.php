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
                                                onclick="tambahKeKeranjang('<?= $data['barang_id'] ?>', '<?= $data['nama'] ?>', <?= $data['harga_satuan'] ?>, <?= $data['harga_butir'] ?>)"
                                                class="rounded rounded-5 btn btn-primary w-50 m-auto">
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
                                <!-- Produk akan ditambahkan di sini -->
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

    <!-- Page level custom scripts -->
    <!-- <script src="../js/demo/datatables-demo.js"></script> -->

    <!-- <script src="../js/kasir-13.js"></script> -->
    <!-- <script src="../js/kasir-12.js"></script> -->
    <!-- 11 masih experiment -->
    <!-- <script src="../js/kasir-11.js"></script> -->
    <!-- kasir-10 BISA DAN NORMAL -->
    <script src="../js/kasir-10.js"></script>
    <!-- BISA kasir-9 -->
    <!-- <script src="../js/kasir-9.js"></script> -->
    <!-- 8 bisa -->
    <!-- <script src="../js/kasir-8.js"></script> -->


    <!-- <script src="../js/kasir-7.js"></script> -->
    <!-- <script src="../js/kasir-6.js"></script> -->
    <!-- 6 bisa -->
    <script>
        $(document).ready(function () {
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
                                            <h5 class="text-dark">${item.nama}</h5>
                                            <p class="text-dark">Rp ${item.harga_satuan || item.harga_butir}</p>
                                         <button onclick="tambahKeKeranjang('${item.barang_id}', '${item.nama}', ${item.harga_satuan}, ${item.harga_butir})" 
                                            class="rounded rounded-5 btn btn-primary w-50 m-auto">
                                            <i class="fas fa-tada"></i><i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                `;
                                });
                            } else {
                                html = '<p>Produk tidak ditemukan.</p>';
                            }
                            $('#resultContainer').html(html);
                        }
                    });
                }
            });

            // 
            // Selesaikan transaksi
            // $('#btnSelesai').on('click', function (e) {
            //     e.preventDefault(); // Mencegah form submit default

            //     // Array untuk menampung barangId, jumlahKeluar, dan hargaAktual
            //     let barangIds = [];
            //     let jumlahKeluar = [];
            //     let hargaAktuals = [];

            //     // Ambil data barangId, jumlahKeluar, dan hargaAktual dari setiap item dalam keranjangContainer
            //     $('#keranjangContainer .card').each(function () {
            //         let barangId = $(this).attr('data-id'); // Ambil data-id
            //         let jumlah = $(this).find('.jumlahKeluar').val(); // Ambil nilai jumlahKeluar dari input
            //         let hargaAktual = $(this).find('.hargaAktual').val();

            //         // Validasi data sebelum ditambahkan ke array
            //         if (barangId && jumlah && hargaAktual) {
            //             barangIds.push(barangId);
            //             jumlahKeluar.push(jumlah);
            //             hargaAktuals.push(hargaAktual);
            //         }
            //     });

            //     // Ambil total harga dari elemen HTML dan bersihkan formatnya
            //     let totalHarga = $('#totalHargaSemuaBarang').text()
            //         .replace('Rp ', '') // Hapus simbol 'Rp '
            //         .replace(/\./g, '') // Hapus titik pemisah ribuan
            //         .replace(/,/g, ''); // Hapus koma, jika ada

            //     // Validasi data sebelum pengiriman
            //     if (barangIds.length === 0 || jumlahKeluar.length === 0 || hargaAktuals.length === 0) {
            //         alert("Keranjang kosong atau data tidak valid. Mohon periksa kembali.");
            //         return;
            //     }

            //     // Kirim data ke server menggunakan AJAX
            //     $.ajax({
            //         url: '../service/ajax/ajax-kasir-2.php', // Ganti dengan URL yang sesuai
            //         type: 'POST',
            //         data: {
            //             barangIds: barangIds,         // Array ID barang
            //             jumlahKeluar: jumlahKeluar,   // Array jumlah barang keluar
            //             hargaAktuals: hargaAktuals,   // Array harga aktual
            //             totalHarga: totalHarga        // Total harga yang dihitung
            //         },
            //         success: function (response) {
            //             // Berikan feedback kepada pengguna
            //             alert(response); // Menampilkan respons dari server

            //             // Jika transaksi berhasil, hapus data keranjang di localStorage
            //             localStorage.removeItem('keranjang'); // Menghapus data barang dari localStorage

            //             // Reset keranjang setelah berhasil mengirim data
            //             $('#keranjangContainer').empty(); // Menghapus semua barang di keranjang
            //             $('#totalHargaSemuaBarang').text('Rp 0'); // Reset total harga

            //             // Logika tambahan jika perlu, seperti redirect atau refresh
            //             console.log("Transaksi selesai dan keranjang direset.");
            //         },
            //         error: function (jqXHR, textStatus, errorThrown) {
            //             // Menampilkan pesan error jika gagal
            //             alert("Gagal mengirim data: " + textStatus + " - " + errorThrown);
            //         }
            //     });
            // });


            // DARI GITHUB
            // Selesaikan transaksi
            $('#btnSelesai').on('click', function (e) {
                e.preventDefault(); // Mencegah form submit default

                // Array untuk menampung barangId dan jumlahKeluar
                let barangIds = [];
                let jumlahKeluar = [];

                // Ambil data barangId dan jumlahKeluar dari setiap item dalam keranjangContainer
                $('#keranjangContainer .card').each(function () {
                    let barangId = $(this).attr('data-id'); // Ambil data-id
                    let jumlah = $(this).find('.jumlahKeluar').val(); // Ambil nilai jumlahKeluar dari input .jumlahKeluar
                    barangIds.push(barangId);
                    jumlahKeluar.push(jumlah); // Simpan jumlahKeluar sesuai urutan barangId
                });

                // Ambil total harga dari tampilan dan bersihkan formatnya (hapus 'Rp' dan titik)
                let totalHarga = $('#totalHargaSemuaBarang').text().replace('Rp ', '').replace('.', '').replace(',', '');

                // Kirim data ke server menggunakan AJAX
                $.ajax({
                    url: '../service/ajax/ajax-kasir-2.php', // Ganti dengan URL yang sesuai
                    type: 'POST',
                    data: {
                        barangIds: barangIds,         // Array ID barang
                        jumlahKeluar: jumlahKeluar,   // Array jumlah barang keluar
                        totalHarga: totalHarga        // Total harga yang dihitung
                    },
                    success: function (response) {
                        // alert(response); // Menampilkan respons dari server

                        // Jika transaksi berhasil, hapus data keranjang di localStorage
                        localStorage.removeItem('keranjang'); // Menghapus data barang dari localStorage

                        // Reset keranjang setelah berhasil mengirim data
                        $('#keranjangContainer').empty(); // Menghapus semua barang di keranjang
                        $('#totalHargaSemuaBarang').text('Rp 0'); // Reset total harga

                        // Anda bisa menambahkan logika tambahan di sini setelah transaksi selesai
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("Gagal mengirim data: " + textStatus); // Menampilkan pesan error jika gagal
                    }
                });
            });

            // DARI GITHUB



            // // Selesaikan transaksi
            // $('#btnSelesai').on('click', function (e) {
            //     e.preventDefault(); // Mencegah form submit default

            //     // Array untuk menampung barangId dan jumlahKeluar
            //     let barangIds = [];
            //     let jumlahKeluar = [];

            //     // Ambil data barangId dan jumlahKeluar dari setiap item dalam keranjangContainer
            //     $('#keranjangContainer .card').each(function () {
            //         let barangId = $(this).attr('data-id'); // Ambil data-id
            //         let jumlah = $(this).find('.jumlahKeluar').val(); // Ambil nilai jumlahKeluar dari input .jumlahKeluar

            //         barangIds.push(barangId);
            //         jumlahKeluar.push(jumlah); // Simpan jumlahKeluar sesuai urutan barangId
            //     });

            //     // Ambil total harga dari tampilan dan bersihkan formatnya (hapus 'Rp' dan titik)
            //     // let totalHarga = $('#totalHargaSemuaBarang').text().replace('Rp ', '').replace('.', '').replace(',', '');

            //     // Kirim data ke server menggunakan AJAX
            //     $.ajax({
            //         url: '../service/ajax/ajax-kasir-2.php', // Ganti dengan URL yang sesuai
            //         type: 'POST',
            //         data: {
            //             barangIds: barangIds,         // Array ID barang
            //             jumlahKeluar: jumlahKeluar,   // Array jumlah barang keluar
            //             totalHarga: totalHarga        // Total harga yang dihitung
            //         },
            //         success: function (response) {
            //             // alert(response); // Menampilkan respons dari server

            //             // Jika transaksi berhasil, hapus data keranjang di localStorage
            //             localStorage.removeItem('keranjang'); // Menghapus data barang dari localStorage

            //             // Reset keranjang setelah berhasil mengirim data
            //             $('#keranjangContainer').empty(); // Menghapus semua barang di keranjang
            //             $('#totalHargaSemuaBarang').text('Rp 0'); // Reset total harga

            //             // Anda bisa menambahkan logika tambahan di sini setelah transaksi selesai
            //         },
            //         error: function (jqXHR, textStatus, errorThrown) {
            //             alert("Gagal mengirim data: " + textStatus); // Menampilkan pesan error jika gagal
            //         }
            //     });
            // });

            // selesaikan transaksi
            // $('#btnSelesai').on('click', function (e) {
            //     e.preventDefault(); // Mencegah form submit default

            //     // Ambil semua data-id dan data-jumlah-keluar dari setiap item dalam keranjangContainer
            //     // Array untuk menampung barangId dan jumlahKeluar
            //     let barangIds = [];
            //     let jumlahKeluar = [];
            //     // Ambil data barangId dan jumlahKeluar dari setiap item dalam keranjangContainer
            //     $('#keranjangContainer .card').each(function () {
            //         let barangId = $(this).attr('data-id'); // Ambil data-id
            //         let jumlah = $(this).attr('data-jumlah-keluar'); // Ambil data-jumlah-keluar
            //         barangIds.push(barangId);
            //         jumlahKeluar.push(jumlah); // Simpan jumlahKeluar sesuai urutan barangId
            //     });

            //     // Ambil total harga
            //     let totalHarga = $('#totalHargaSemuaBarang').text().replace('Rp ', '').replace('.', '');

            //     $.ajax({
            //         url: '../service/ajax/ajax-kasir.php',
            //         type: 'POST',
            //         data: {
            //             barangIds: barangIds,
            //             jumlahKeluar: jumlahKeluar,
            //             totalHarga: totalHarga
            //         },
            //         success: function (response) {
            //             alert(response); // Menampilkan respons dari server

            //             // Reset keranjang setelah berhasil mengirim data
            //             $('#keranjangContainer').empty(); // Menghapus semua barang di keranjang
            //             $('#totalHargaSemuaBarang').text('Rp 0'); // Reset total harga

            //         },
            //         error: function (jqXHR, textStatus, errorThrown) {
            //             alert("Gagal mengirim data: " + textStatus);
            //         }
            //     });
            // });

        });

    </script>

</body>

</html>