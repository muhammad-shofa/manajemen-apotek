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

    <title>Apotek | Tipe & Stok Barang</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard Tipe & Stok Barang</h1>
                    </div>

                    <!-- btn trigger modal tambah berita -->
                    <button type="button" class="btn btn-primary my-2" data-toggle="modal" data-target="#modalTambah">
                        Tambah Tipe Barang
                    </button>

                    <!-- DataTales -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Stok Barang</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-dark" id="tableBarang" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <!-- <th>Nomor Bacth</th> -->
                                            <th>Nama</th>
                                            <th>Kategori</th>
                                            <th>Harga Satuan</th>
                                            <th>Harga Butir</th>
                                            <!-- <th>Exp</th> -->
                                            <th>Stok</th>
                                            <th>Satuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

                <!-- Modal tambah barang start -->
                <div class="modal fade" id="modalTambah">
                    <div class="modal-dialog">
                        <div class="modal-content text-dark">
                            <div class="modal-header">
                                <h4 class="modal-title">Tambah Tipe Barang</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="formTambah" method="POST">
                                <div class="modal-body">
                                    <input type="hidden" class="form-control" id="tambah_barang_id" name="barang_id">
                                    <div class="form-group">
                                        <label for="tambah_nama">Nama :</label>
                                        <input type="text" class="form-control" id="tambah_nama" name="nama">
                                    </div>
                                    <div class="form-group">
                                        <label for="tambah_kategori">Kategori :</label>
                                        <select class="form-control select2" name="kategori" id="tambah_kategori"
                                            style="width: 100%;">
                                            <option value="Obat">Obat</option>
                                            <option value="Makanan">Makanan</option>
                                            <option value="Minuman">Minuman</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="tambah_harga_satuan">Harga Satuan :</label>
                                        <input type="number" class="form-control" id="tambah_harga_satuan"
                                            name="harga_satuan">
                                    </div>
                                    <div class="form-group">
                                        <label for="tambah_harga_butir">Harga Butir :</label>
                                        <input type="number" class="form-control" id="tambah_harga_butir"
                                            name="harga_butir">
                                    </div>
                                    <div class="form-group">
                                        <label for="tambah_stok">Stok :</label>
                                        <input type="number" class="form-control" id="tambah_stok" name="stok">
                                    </div>
                                    <div class="form-group">
                                        <label for="tambah_satuan">Satuan :</label>
                                        <select class="form-control select2" name="satuan" id="tambah_satuan"
                                            style="width: 100%;">
                                            <option value="Pcs">Pcs</option>
                                            <option value="Butir">Butir</option>
                                            <option value="Strip">Strip</option>
                                            <option value="Botol">Botol</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-primary" name="simpanTambah"
                                        id="simpanTambah">Tambahkan</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- Modal tambah barang End -->

                <!-- Modal edit barang start -->
                <div class="modal fade" id="modalEdit">
                    <div class="modal-dialog">
                        <div class="modal-content text-dark">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Barang</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="formEdit" method="POST">
                                <div class="modal-body">
                                    <input type="hidden" class="form-control" id="edit_barang_id" name="barang_id">
                                    <div class="form-group">
                                        <label for="edit_nama">Nama :</label>
                                        <input type="text" class="form-control" id="edit_nama" name="nama">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_kategori">Kategori :</label>
                                        <select class="form-control select2" name="kategori" id="edit_kategori"
                                            style="width: 100%;">
                                            <option value="Obat">Obat</option>
                                            <option value="Makanan">Makanan</option>
                                            <option value="Minuman">Minuman</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_harga_satuan">Harga Satuan :</label>
                                        <input type="number" class="form-control" id="edit_harga_satuan"
                                            name="harga_satuan">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_harga_butir">Harga Butir :</label>
                                        <input type="number" class="form-control" id="edit_harga_butir"
                                            name="harga_butir">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_stok">Stok :</label>
                                        <input type="number" class="form-control" id="edit_stok" name="stok">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_satuan">Satuan :</label>
                                        <select class="form-control select2" name="satuan" id="edit_satuan"
                                            style="width: 100%;">
                                            <option value="Pcs">Pcs</option>
                                            <option value="Butir">Butir</option>
                                            <option value="Strip">Strip</option>
                                            <option value="Botol">Botol</option>
                                        </select>
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
                <!-- Modal edit barang End -->

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

    <!-- Page level custom scripts -->
    <!-- <script src="../js/demo/datatables-demo.js"></script> -->

    <script>
        $(document).ready(function () {
            var table = $('#tableBarang').DataTable({
                "ajax": "../service/ajax/ajax-barang.php",
                "columns": [{
                    "data": "no"
                },
                // {
                //     "data": "nomor_bacth"
                // },
                {
                    "data": "nama"
                },
                {
                    "data": "kategori"
                },
                {
                    "data": "harga_satuan"
                },
                {
                    "data": "harga_butir"
                },
                {
                    "data": "stok"
                },
                {
                    "data": "satuan"
                },
                {
                    "data": "action",
                    "orderable": true,
                    "searchable": true
                }
                ],
                "responsive": true,
                paging: false
            });

            // Tambah tipe barang
            $('#simpanTambah').click(function () {
                var data = $('#formTambah').serialize();
                $.ajax({
                    url: '../service/ajax/ajax-barang.php',
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        $('#modalTambah').modal('hide');
                        table.ajax.reload();
                        $('#formTambah')[0].reset();
                        alert(response);
                    }
                });
            });

            // Menampilkan modal edit
            $('#tableBarang').on('click', '.edit', function () {
                let barang_id = $(this).data('barang_id');
                $.ajax({
                    url: '../service/ajax/ajax-barang.php?barang_id=' + barang_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#edit_barang_id').val(data.barang_id);
                        $('#edit_nama').val(data.nama);
                        $('#edit_kategori').val(data.kategori);
                        $('#edit_harga_satuan').val(data.harga_satuan);
                        $('#edit_harga_butir').val(data.harga_butir);
                        $('#edit_stok').val(data.stok);
                        $('#edit_satuan').val(data.satuan);
                        $('#modalEdit').modal('show');
                    }
                });
            });

            // Menyimpan edit
            $('#simpanEdit').click(function () {
                var data = $('#formEdit').serialize();
                $.ajax({
                    url: '../service/ajax/ajax-barang.php',
                    type: 'PUT',
                    data: data,
                    success: function (response) {
                        $('#modalEdit').modal('hide');
                        table.ajax.reload();
                        $('#formEdit')[0].reset();
                        alert(response);
                    }
                });
            });

            // Delete barang
            $('#tableBarang').on('click', '.delete', function () {
                var barang_id = $(this).data('barang_id');
                if (confirm('Kamu yakin ingin menghapus data barang ini?')) {
                    $.ajax({
                        url: '../service/ajax/ajax-barang.php',
                        type: 'DELETE',
                        data: {
                            barang_id: barang_id
                        },
                        success: function (response) {
                            table.ajax.reload();
                            alert(response);
                        }
                    });
                }
            });
            // Menampilkan modal Edit User
            // $('#pengguna_table').on('click', '.edit', function () {
            //     let user_id = $(this).data('user_id');
            //     $.ajax({
            //         url: '../service/ajax/ajax-pengguna.php?user_id=' + user_id,
            //         type: 'GET',
            //         dataType: 'json',
            //         success: function (data) {
            //             $('#edit_user_id').val(data.user_id);
            //             $('#edit_username').val(data.username);
            //             $('#edit_nama_lengkap').val(data.nama_lengkap);
            //             $('#edit_email').val(data.email);
            //             $('#edit_tanggal_lahir').val(data.tanggal_lahir);
            //             $('#edit_jenis_kelamin').val(data.jenis_kelamin);
            //             $('#edit_role').val(data.role);
            //             $('#modalEdit').modal('show');
            //         }
            //     });
            // });

            // Simpan edit user
            // $('#simpanEdit').click(function () {
            //     var data = $('#formEdit').serialize();
            //     $.ajax({
            //         url: '../service/ajax/ajax-pengguna.php',
            //         type: 'PUT',
            //         data: data,
            //         success: function (response) {
            //             $('#modalEdit').modal('hide');
            //             table.ajax.reload();
            //             alert(response);
            //         }
            //     });
            // });

            // Delete User
            // $('#pengguna_table').on('click', '.delete', function () {
            //     var user_id = $(this).data('user_id');
            //     if (confirm('Kamu yakin ingin menghapus pengguna ini?')) {
            //         $.ajax({
            //             url: '../service/ajax/ajax-pengguna.php',
            //             type: 'DELETE',
            //             data: {
            //                 user_id: user_id
            //             },
            //             success: function (response) {
            //                 table.ajax.reload();
            //                 alert(response);
            //             }
            //         });
            //     }
            // });

        });

    </script>

</body>

</html>