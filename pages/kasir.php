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

    <title>Apotek | Barang Masuk</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Kasir</h1>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between">
                        <!-- daftar barang kasir start -->
                        <div class="card pencarian shadow mb-4 p-3">
                            <input type="text" id="searchInput" class="form-control w-100" placeholder="Cari produk...">
                            <div id="resultContainer" class="d-flex flex-wrap mt-2 text-center">
                                <?php
                                $result = $connected->query("SELECT * FROM barang LIMIT 4");
                                if ($result->num_rows > 0) {
                                    while ($data = $result->fetch_assoc()) {
                                        ?>
                                        <div class="card m-2 p-2">
                                            <h5 class="text-dark namaBarang"><?= $data['nama'] ?></h5>
                                            <p class="text-dark hargaSatuan"><?= $data['harga_satuan'] ?></p>
                                            <button
                                                onclick="tambahKeKeranjang('<?= $data['barang_id'] ?>', '<?= $data['nama'] ?>', <?= $data['harga_satuan'] ?>)"
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
                        <div class="card keranjang shadow mb-4 p-3">
                            <h4>Keranjang</h4>
                            <div id="keranjangContainer" class="w-100 d-flex flex-column p-2">
                                <!-- Produk akan ditambahkan di sini -->
                            </div>
                        </div>
                        <!-- keranjang end -->
                    </div>

                </div>
                <!-- /.container-fluid -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; mshofadev 2024</span>
                        </div>
                    </div>
                </footer>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

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

    <script>
        function tambahKeKeranjang(barangId, namaBarang, hargaSatuan) {
            const keranjangContainer = document.getElementById('keranjangContainer');

            // Cek apakah produk sudah ada di keranjang
            const existingItem = Array.from(keranjangContainer.children).find(item => item.getAttribute('data-id') === barangId);

            if (existingItem) {
                // Jika ada, perbarui jumlah dan total harga
                const jumlahElement = existingItem.querySelector('.jumlahBarang');
                const totalHargaElement = existingItem.querySelector('.totalHarga');

                let jumlah = parseInt(jumlahElement.innerText);
                jumlah++;
                jumlahElement.innerText = jumlah;

                const totalHarga = jumlah * hargaSatuan;
                totalHargaElement.innerText = totalHarga;
            } else {
                // Jika belum ada, buat item baru di keranjang
                const keranjangItem = document.createElement('div');
                keranjangItem.classList.add('card', 'w-100', 'd-flex', 'p-2', 'm-1');
                keranjangItem.setAttribute('data-id', barangId); // Set ID produk

                keranjangItem.innerHTML = `
            <h5 class="text-dark">${namaBarang}</h5>
            <p>Jumlah: <span class="jumlahBarang">1</span></p>
            <p>Harga Satuan: Rp ${hargaSatuan}</p>
            <p>Total Harga: Rp <span class="totalHarga">${hargaSatuan}</span></p>
        `;

                keranjangContainer.appendChild(keranjangItem);
            }
        }


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
                                            <p class="text-dark">Rp ${item.harga_satuan}</p>
                                         <button onclick="tambahKeKeranjang('${item.barang_id}', '${item.nama}', ${item.harga_satuan})" 
            class="rounded rounded-5 btn btn-primary w-50 m-auto">
        <i class="fas fa-tada"></i> <i class="fas fa-plus"></i>
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
                } else {
                    $('#resultContainer').html('Cari nama barang di search bar...');
                }
            });



            // Tambah Barang
            $('#tambahkanBarang').click(function () {
                var data = $('#formTambahBarang').serialize();
                $.ajax({
                    url: '../service/ajax/ajax-barang-masuk.php',
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        $('#modalTambahBarang').modal('hide');
                        table.ajax.reload();
                        $('#formTambahBarang')[0].reset();
                        alert(response);
                    }
                });
            });

            // Menampilkan modal Edit barang masuk
            $('#tableBarangMasuk').on('click', '.edit', function () {
                let barang_masuk_id = $(this).data('barang_masuk_id');
                let barang_id = $(this).data('barang_id');

                $.ajax({
                    url: '../service/ajax/ajax-barang-masuk.php?barang_masuk_id=' + barang_masuk_id + '&' + 'barang_id=' + barang_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#edit_barang_masuk_id').val(data.barang_masuk_id);
                        $('#edit_barang_id').val(data.barang_id);
                        $('#edit_nama').val(data.nama);
                        $('#edit_nomor_bacth').val(data.nomor_bacth);
                        $('#edit_tanggal_masuk').val(data.tanggal_masuk);
                        $('#edit_jumlah_masuk').val(data.jumlah_masuk);
                        $('#edit_exp').val(data.exp);
                        $('#edit_supplier').val(data.supplier);
                        $('#edit_keterangan').val(data.keterangan);
                        $('#modalEditBarangMasuk').modal('show');
                    }
                });
            });

            // Menyimpan edit
            $('#simpanEdit').click(function () {
                var data = $('#formEditBarangMasuk').serialize();
                $.ajax({
                    url: '../service/ajax/ajax-barang-masuk.php',
                    type: 'PUT',
                    data: data,
                    success: function (response) {
                        $('#modalEditBarangMasuk').modal('hide');
                        table.ajax.reload();
                        $('#formEditBarangMasuk')[0].reset();
                        alert(response);
                    }
                });
            });

            // Delete barang masuk
            $('#tableBarangMasuk').on('click', '.delete', function () {
                var barang_masuk_id = $(this).data('barang_masuk_id');
                if (confirm('Kamu yakin ingin menghapus data tambah barang ini?')) {
                    $.ajax({
                        url: '../service/ajax/ajax-barang-masuk.php',
                        type: 'DELETE',
                        data: {
                            barang_masuk_id: barang_masuk_id
                        },
                        success: function (response) {
                            table.ajax.reload();
                            alert(response);
                        }
                    });
                }
            });
        });

    </script>

</body>

</html>