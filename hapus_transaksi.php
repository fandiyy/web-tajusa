<?php
$conn = new mysqli("localhost", "root", "", "inventory_tajusa");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$tipe = isset($_GET['tipe']) ? $_GET['tipe'] : '';
$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$tipe || !$id) {
    header("Location: /stock_tajusa/index.php?status=invalid");
    exit;
}

// Ambil barang_id yang terkait dengan transaksi
if ($tipe === 'masuk') {
    $result = $conn->query("SELECT barang_id FROM barang_masuk WHERE id = $id");
} elseif ($tipe === 'keluar') {
    $result = $conn->query("SELECT barang_id FROM barang_keluar WHERE id = $id");
} else {
    header("Location: /stock_tajusa/index.php?status=invalid");
    exit;
}

if ($result && $row = $result->fetch_assoc()) {
    $barang_id = $row['barang_id'];
} else {
    header("Location: /stock_tajusa/index.php?status=invalid");
    exit;
}

// Hapus transaksi
if ($tipe === 'masuk') {
    $query = "DELETE FROM barang_masuk WHERE id = $id";
} elseif ($tipe === 'keluar') {
    $query = "DELETE FROM barang_keluar WHERE id = $id";
}

if ($conn->query($query) === TRUE) {
    // Cek apakah masih ada transaksi lainnya untuk barang ini
    $cekBarang = $conn->query("
        SELECT barang_id FROM barang_masuk WHERE barang_id = $barang_id
        UNION
        SELECT barang_id FROM barang_keluar WHERE barang_id = $barang_id
    ");

    // Jika tidak ada transaksi lainnya, hapus barang dari tabel barang
    if ($cekBarang->num_rows === 0) {
        $conn->query("DELETE FROM barang WHERE id = $barang_id");
    }

    // Redirect setelah penghapusan
    header("Location: /stock_tajusa/index.php?status=deleted");
    exit;
} else {
    // Jika gagal menghapus transaksi
    header("Location: /stock_tajusa/index.php?status=error");
    exit;
}
?>
