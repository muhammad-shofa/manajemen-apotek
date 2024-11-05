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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard Barang Masuk</h1>

                    </div>

                    <!-- DataTales -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary mb-3">Barang Masuk</h6>
                            <hr>
                            <div class="d-flex flex-wrap justify-content-between">
                                <!-- btn trigger modal tambah barang masuk -->
                                <button type="button" class="btn btn-primary my-2" data-toggle="modal"
                                    data-target="#modalTambahBarang">
                                    Tambah Barang
                                </button>
                                <a href="#0" class="btn btn-sm my-2 py-2 btn-info">
                                    <i class="fas fa-download fa-sm text-white-50"></i> Unduh Excel
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-dark" id="tableBarangMasuk" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Nomor Bacth</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Jumlah Masuk</th>
                                            <th>Exp</th>
                                            <th>Supplier</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Modal tambah barang masuk start -->
                        <div class="modal fade" id="modalTambahBarang">
                            <div class="modal-dialog">
                                <div class="modal-content text-dark">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Tambah Barang</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form id="formTambahBarang">
                                        <div class="modal-body">
                                            <!-- <div class="form-group">
                                                <label for="pilih_barang">Pilih Barang :</label>
                                                <input list="barang_list" class="form-control" id="pilih_barang"
                                                    name="pilih_barang" placeholder="Cari barang...">
                                                <datalist id="barang_list">
                                                    <php
                                                    $results_all_barang = $connected->query("SELECT * FROM barang");
                                                    if ($results_all_barang->num_rows > 0) {
                                                        while ($data_semua_barang = $results_all_barang->fetch_assoc()) {
                                                            ?>
                                                            <input type="text" value="<= $data_semua_barang['barang_id'] ?>">
                                                            <option value="<= $data_semua_barang['nama'] ?><= $data_semua_barang['barang_id'] ?>">
                                                                <= $data_semua_barang['nama'] ?>
                                                            </option>
                                                        <php }
                                                    } ?>
                                                </datalist>
                                            </div> -->

                                            <div class="form-group">
                                                <label for="pilih_barang">Pilih Barang :</label>
                                                <select class="form-control select2" name="pilih_barang"
                                                    id="pilih_barang" style="width: 100%;">
                                                    <?php
                                                    $results_all_barang = $connected->query("SELECT * FROM barang");
                                                    if ($results_all_barang->num_rows > 0) {
                                                        while ($data_semua_barang = $results_all_barang->fetch_assoc()) {
                                                            ?>
                                                            <option value="<?= $data_semua_barang['barang_id'] ?>">
                                                                <?= $data_semua_barang['nama'] ?>
                                                            </option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="nomor_bacth">Nomor Bacth :</label>
                                                <input type="text" class="form-control" id="nomor_bacth"
                                                    name="nomor_bacth">
                                            </div>
                                            <div class="form-group">
                                                <label for="tanggal_masuk">Tanggal Masuk :</label>
                                                <input type="date" class="form-control" id="tanggal_masuk"
                                                    name="tanggal_masuk">
                                            </div>
                                            <div class="form-group">
                                                <label for="jumlah_masuk">Jumlah Masuk :</label>
                                                <input type="number" class="form-control" id="jumlah_masuk"
                                                    name="jumlah_masuk">
                                            </div>
                                            <div class="form-group">
                                                <label for="exp">Exp :</label>
                                                <input type="date" class="form-control" id="exp" name="exp">
                                            </div>
                                            <div class="form-group">
                                                <label for="supplier">Supplier :</label>
                                                <input type="text" class="form-control" id="supplier" name="supplier">
                                            </div>
                                            <div class="form-floating">
                                                <label for="keterangan">
                                                    Keterangan :
                                                </label>
                                                <textarea class="form-control" id="keterangan" name="keterangan"
                                                    style="height: 85px; resize: none;"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-primary" name="tambahkanBarang"
                                                id="tambahkanBarang">Tambahkan</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- Modal tambah barang masuk End -->

                        <!-- Modal edit barang masuk start -->
                        <div class="modal fade" id="modalEditBarangMasuk">
                            <div class="modal-dialog">
                                <div class="modal-content text-dark">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Edit Barang</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form id="formEditBarangMasuk">
                                        <div class="modal-body">
                                            <!-- <div class="form-group">
                                                <input type="text" name="barang_id" id="edit_barang_id"
                                                    value="<= $barang_masuk_id ?>">
                                                <label for="pilih_barang">Pilih Barang :</label>
                                                <select class="form-control select2" name="pilih_barang"
                                                    id="pilih_barang" style="width: 100%;">
                                                    <php

                                                    
                                                    // Ambil barang_id dari tabel barang_masuk berdasarkan id yang sedang diedit
                                                    $barang_masuk_id = 1; // Sesuaikan dengan id barang_masuk yang sedang diedit atau bisa dinamis
                                                    $result_barang_masuk = $connected->query("SELECT barang_id FROM barang_masuk WHERE barang_masuk_id = $barang_masuk_id");
                                                    $selected_barang_id = ($result_barang_masuk->num_rows > 0) ? $result_barang_masuk->fetch_assoc()['barang_id'] : null;


                                                    $results_all_barang = $connected->query("SELECT * FROM barang");
                                                    if ($results_all_barang->num_rows > 0) {
                                                        while ($data_semua_barang = $results_all_barang->fetch_assoc()) {
                                                            ?>
                                                            <option value="<= $data_semua_barang['barang_id'] ?>">
                                                                <= $data_semua_barang['nama'] ?>
                                                            </option>
                                                        <php }
                                                    } ?>
                                                </select>
                                            </div> -->

                                            <input type="text" name="barang_masuk_id" id="edit_barang_masuk_id">
                                            <div class="form-group">
                                                <label for="edit_barang_id">Pilih Barang :</label>
                                                <input type="text" class="form-control" id="edit_barang_id"
                                                    name="barang_id">
                                                <input type="text" class="form-control" id="edit_nama" name="nama">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_nomor_bacth">Nomor Bacth :</label>
                                                <input type="text" class="form-control" id="edit_nomor_bacth"
                                                    name="nomor_bacth">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_tanggal_masuk">Tanggal Masuk :</label>
                                                <input type="date" class="form-control" id="edit_tanggal_masuk"
                                                    name="tanggal_masuk">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_jumlah_masuk">Jumlah Masuk :</label>
                                                <input type="number" class="form-control" id="edit_jumlah_masuk"
                                                    name="jumlah_masuk">
                                                <input type="hidden" class="form-control" id="edit_jumlah_masuk_old"
                                                    name="jumlah_masuk_old">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_exp">Exp :</label>
                                                <input type="date" class="form-control" id="edit_exp" name="exp">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_supplier">Supplier :</label>
                                                <input type="text" class="form-control" id="edit_supplier"
                                                    name="supplier">
                                            </div>
                                            <div class="form-floating">
                                                <label for="edit_keterangan">
                                                    Keterangan :
                                                </label>
                                                <textarea class="form-control" id="edit_keterangan" name="keterangan"
                                                    style="height: 85px; resize: none;"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-success" name="simpanEdit"
                                                id="simpanEdit">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- Modal edit barang masuk End -->

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
        $(document).ready(function () {
            var table = $('#tableBarangMasuk').DataTable({
                "ajax": "../service/ajax/ajax-barang-masuk.php",
                "columns": [{
                    "data": "no"
                },
                {
                    "data": "nama"
                },
                {
                    "data": "nomor_bacth"
                },
                {
                    "data": "tanggal_masuk"
                },
                {
                    "data": "jumlah_masuk"
                },
                {
                    "data": "exp"
                },
                {
                    "data": "supplier"
                },
                {
                    "data": "keterangan"
                },
                {
                    "data": "action",
                    "orderable": true,
                    "searchable": true
                }
                ],
                "responsive": true
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
                        $('#edit_jumlah_masuk_old').val(data.jumlah_masuk);
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