<?php
$conn = new mysqli("localhost", "root", "", "inventory_tajusa");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil transaksi barang masuk
$masuk = $conn->query("
    SELECT bm.id, b.nama_barang, bm.jumlah, bm.tanggal 
    FROM barang_masuk bm 
    JOIN barang b ON b.id = bm.barang_id 
    ORDER BY bm.tanggal DESC
");

// Ambil transaksi barang keluar
$keluar = $conn->query("
    SELECT bk.id, b.nama_barang, bk.jumlah, bk.tanggal 
    FROM barang_keluar bk 
    JOIN barang b ON b.id = bk.barang_id 
    ORDER BY bk.tanggal DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <a href="index.php" class="btn btn-secondary mb-4">← Kembali ke Dashboard</a>

    <!-- Notifikasi -->
    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] == 'success'): ?>
            <div class="alert alert-success">✅ Transaksi berhasil dihapus.</div>
        <?php elseif ($_GET['status'] == 'error'): ?>
            <div class="alert alert-danger">❌ Gagal menghapus transaksi.</div>
        <?php elseif ($_GET['status'] == 'invalid'): ?>
            <div class="alert alert-warning">⚠️ Parameter tidak valid.</div>
        <?php endif; ?>
    <?php endif; ?>

    <h3>📥 Transaksi Barang Masuk</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $masuk->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nama_barang'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td>
                        <a href="hapus_transaksi.php?tipe=masuk&id=<?= $row['id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                           Hapus
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3 class="mt-5">📤 Transaksi Barang Keluar</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $keluar->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nama_barang'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td>
                        <a href="hapus_transaksi.php?tipe=keluar&id=<?= $row['id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                           Hapus
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
