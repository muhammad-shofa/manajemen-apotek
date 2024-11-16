<?php
include "../connection.php";
include "../select.php";

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $sql = $select->selectTable($table_name = "barang", $fields = "barang_id, nama, harga_satuan, harga_butir", $condition = "WHERE nama LIKE ?");
    // $sql = "SELECT nama, harga_satuan FROM barang WHERE nama LIKE ?";
    $stmt = $connected->prepare($sql);
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $barang = [];
    while ($row = $result->fetch_assoc()) {
        $barang[] = $row;
    }

    echo json_encode($barang);
    $stmt->close();
}

