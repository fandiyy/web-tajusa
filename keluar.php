<?php
$conn = new mysqli("localhost", "root", "", "inventory_tajusa");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barang_id = (int)$_POST['barang_id'];
    $jumlah = (int)$_POST['jumlah'];
    $tanggal = date('Y-m-d');

    if ($jumlah <= 0 || !$barang_id) die("Data tidak valid");

    $conn->query("INSERT INTO barang_keluar (barang_id, tanggal, jumlah) VALUES ($barang_id, '$tanggal', $jumlah)");
    header("Location: index.php");
    exit;
}

$barang = $conn->query("SELECT b.id, b.nama_barang, k.nama_kategori FROM barang b JOIN kategori k ON k.id = b.kategori_id");
?>

<!DOCTYPE html>
<html>
<head><title>Barang Keluar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h3>Input Barang Keluar</h3>
    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <select name="barang_id" class="form-select" required>
                <option value="">-- Pilih Barang --</option>
                <?php while($b = $barang->fetch_assoc()): ?>
                    <option value="<?= $b['id'] ?>"><?= $b['nama_barang'] ?> (<?= $b['nama_kategori'] ?>)</option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" name="jumlah" class="form-control" placeholder="Jumlah" required>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-danger w-100">Simpan</button>
        </div>
        <div class="container py-4">
    <a href="index.php" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>
</div>
    </form>
</div>
</body>
</html>
