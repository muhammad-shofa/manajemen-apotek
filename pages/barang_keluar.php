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

    <title>Apotek | Barang Keluar</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard Barang Keluar</h1>

                    </div>

                    <!-- DataTales -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary mb-3">Barang Keluar</h6>
                            <hr>
                            <div class="d-flex flex-wrap justify-content-between">
                                <!-- btn trigger modal tambah barang masuk -->
                                <!-- <button type="button" class="btn btn-primary my-2" data-toggle="modal"
                                    data-target="#modalTambahBarang">
                                    Tambah Barang
                                </button> -->
                                <a href="#0" class="btn btn-sm my-2 py-2 btn-info">
                                    <i class="fas fa-download fa-sm text-white-50"></i> Unduh Excel
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-dark" id="tableBarangKeluar" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Kategori</th>
                                            <th>Jumlah Keluar</th>
                                            <th>Harga Satuan</th>
                                            <th>Total Harga</th>
                                            <th>Tanggal Keluar</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Modal edit barang keluar start -->
                        <div class="modal fade" id="modalEditBarangKeluar">
                            <div class="modal-dialog">
                                <div class="modal-content text-dark">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Edit Barang</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form id="formEditBarangKeluar">
                                        <div class="modal-body">
                                            <input type="hidden" name="barang_keluar_id" id="edit_barang_keluar_id">
                                            <div class="form-group">
                                                <label for="edit_barang_id">Nama Barang :</label>
                                                <input type="hidden" class="form-control" id="edit_barang_id"
                                                    name="barang_id">
                                                <input type="text" class="form-control" id="edit_nama" name="nama"
                                                    disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_kategori">Kategori :</label>
                                                <input type="text" class="form-control" id="edit_kategori"
                                                    name="kategori">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_jumlah_keluar">Jumlah Keluar :</label>
                                                <input type="number" class="form-control" id="edit_jumlah_keluar"
                                                    name="jumlah_keluar">
                                                <input type="hidden" class="form-control" id="edit_jumlah_keluar_old"
                                                    name="jumlah_keluar_old">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_harga_satuan">Harga Satuan :</label>
                                                <input type="number" class="form-control" id="edit_harga_satuan"
                                                    name="harga_satuan">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_total_harga">Total Harga :</label>
                                                <input type="number" class="form-control" id="edit_total_harga"
                                                    name="total_harga">
                                            </div>

                                            <div class="form-group">
                                                <label for="edit_tanggal_keluar">Tanggal Keluar :</label>
                                                <input type="date" class="form-control" id="edit_tanggal_keluar"
                                                    name="tanggal_keluar">
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
                        <!-- Modal edit barang keluar End -->

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
            var table = $('#tableBarangKeluar').DataTable({
                "ajax": "../service/ajax/ajax-barang-keluar.php",
                "columns": [{
                    "data": "no"
                },
                {
                    "data": "nama"
                },
                {
                    "data": "kategori"
                },
                {
                    "data": "jumlah_keluar"
                },
                {
                    "data": "harga_satuan"
                },
                {
                    "data": "total_harga"
                },
                {
                    "data": "tanggal_keluar"
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
            // $('#tambahkanBarang').click(function () {
            //     var data = $('#formTambahBarang').serialize();
            //     $.ajax({
            //         url: '../service/ajax/ajax-barang-masuk.php',
            //         type: 'POST',
            //         data: data,
            //         success: function (response) {
            //             $('#modalTambahBarang').modal('hide');
            //             table.ajax.reload();
            //             $('#formTambahBarang')[0].reset();
            //             alert(response);
            //         }
            //     });
            // });

            // Menampilkan modal Edit barang masuk
            $('#tableBarangKeluar').on('click', '.edit', function () {
                let barang_keluar_id = $(this).data('barang_keluar_id');
                let barang_id = $(this).data('barang_id');

                $.ajax({
                    url: '../service/ajax/ajax-barang-keluar.php?barang_keluar_id=' + barang_keluar_id + '&' + 'barang_id=' + barang_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#edit_barang_keluar_id').val(data.barang_keluar_id);
                        $('#edit_barang_id').val(data.barang_id);
                        $('#edit_nama').val(data.nama);
                        $('#edit_kategori').val(data.kategori);
                        $('#edit_jumlah_keluar').val(data.jumlah_keluar);
                        $('#edit_harga_satuan').val(data.harga_satuan);
                        $('#edit_total_harga').val(data.total_harga);
                        $('#edit_tanggal_keluar').val(data.tanggal_keluar);
                        $('#edit_keterangan').val(data.keterangan);
                        $('#modalEditBarangKeluar').modal('show');
                    }
                });
            });

            // Menyimpan edit
            $('#simpanEdit').click(function () {
                var data = $('#formEditBarangKeluar').serialize();
                $.ajax({
                    url: '../service/ajax/ajax-barang-keluar.php',
                    type: 'PUT',
                    data: data,
                    success: function (response) {
                        $('#modalEditBarangKeluar').modal('hide');
                        table.ajax.reload();
                        $('#formEditBarangKeluar')[0].reset();
                        alert(response);
                    }
                });
            });

            // Delete barang masuk
            $('#tableBarangKeluar').on('click', '.delete', function () {
                var barang_keluar_id = $(this).data('barang_keluar_id');
                if (confirm('Kamu yakin ingin menghapus data ini?')) {
                    $.ajax({
                        url: '../service/ajax/ajax-barang-keluar.php',
                        type: 'DELETE',
                        data: {
                            barang_keluar_id: barang_keluar_id
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