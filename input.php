<?php
$conn = new mysqli("localhost", "root", "", "inventory_tajusa");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $conn->real_escape_string($_POST['nama_barang']);
    $jumlah = (int)$_POST['jumlah'];
    $kategori_id = (int)$_POST['kategori_id'];
    $tanggal = date('Y-m-d');

    if ($jumlah <= 0 || !$kategori_id) die("Data tidak valid");

    // Cek apakah barang sudah ada
    $cek = $conn->query("SELECT id FROM barang WHERE nama_barang='$nama' AND kategori_id=$kategori_id");
    if ($cek->num_rows > 0) {
        $id = $cek->fetch_assoc()['id'];
    } else {
        $conn->query("INSERT INTO barang (nama_barang, kategori_id) VALUES ('$nama', $kategori_id)");
        $id = $conn->insert_id;
    }

    $conn->query("INSERT INTO barang_masuk (barang_id, tanggal, jumlah) VALUES ($id, '$tanggal', $jumlah)");
    header("Location: index.php");
    exit;
}

$kategori = $conn->query("SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html>
<head><title>Barang Masuk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<div class="container py-5">
    <h3>Input Barang Masuk</h3>
    <form method="POST" class="row g-3">
        <div class="col-md-5">
            <input type="text" name="nama_barang" class="form-control" placeholder="Nama Barang" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="jumlah" class="form-control" placeholder="Jumlah" required>
        </div>
        <div class="col-md-3">
            <select name="kategori_id" class="form-select" required>
                <option value="">-- Kategori --</option>
                <?php while($k = $kategori->fetch_assoc()): ?>
                    <option value="<?= $k['id'] ?>"><?= $k['nama_kategori'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Simpan</button>
        </div>
        <body>
<div class="container py-4">
    <a href="index.php" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>
</div>
</body>

    </form>
</div>
</body>
</html>
