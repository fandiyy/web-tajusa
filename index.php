<?php
$conn = new mysqli("localhost", "root", "", "inventory_tajusa");

// Ambil parameter pencarian
$cari = isset($_GET['cari']) ? $conn->real_escape_string($_GET['cari']) : '';
$kategori_id = isset($_GET['kategori_id']) ? (int)$_GET['kategori_id'] : '';

// Bangun filter query
$filter = [];
if (!empty($kategori_id)) {
    $filter[] = "b.kategori_id = $kategori_id";
}
if (!empty($cari)) {
    $filter[] = "b.nama_barang LIKE '%$cari%'";
}
$where = count($filter) ? 'WHERE ' . implode(' AND ', $filter) : '';

// Ambil laporan stok
$query = "
SELECT 
    b.id,
    b.nama_barang,
    k.nama_kategori,
    IFNULL(SUM(bm.jumlah), 0) AS total_masuk,
    IFNULL((SELECT SUM(jumlah) FROM barang_keluar WHERE barang_id = b.id), 0) AS total_keluar
FROM barang b
LEFT JOIN kategori k ON k.id = b.kategori_id
LEFT JOIN barang_masuk bm ON bm.barang_id = b.id
$where
GROUP BY b.id
ORDER BY b.nama_barang
";
$laporan = $conn->query($query);

// Ambil semua kategori untuk dropdown
$kategori = $conn->query("SELECT * FROM kategori");
?>

<!DOCTYPE html>
<br>
<html>
    <title>Dashboard Tajusa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <a href="transaksi.php" class="btn btn-warning">🗂 Lihat Transaksi</a></br>
    </html>
</br>
    <title>Dashboard Tajusa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">Stok Barang Tajusa</h2>

    <div class="mb-3">
        <a href="input.php" class="btn btn-success">+ Barang Masuk</a>
        <a href="keluar.php" class="btn btn-danger">- Barang Keluar</a>
        <a href="laporan.php" class="btn btn-info">📊 Laporan Bulanan</a>
    </div>

    <!-- Form Search + Filter -->
    <form method="GET" class="row mb-4">
        <div class="col-md-4">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama barang..." value="<?= htmlspecialchars($cari) ?>">
        </div>
        <div class="col-md-3">
            <select name="kategori_id" class="form-select">
                <option value="">-- Semua Kategori --</option>
                <?php while($row = $kategori->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>" <?= ($kategori_id == $row['id']) ? 'selected' : '' ?>>
                        <?= $row['nama_kategori'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>
    </form>

    <!-- Tabel Data -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Total Masuk</th>
                <th>Total Keluar</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $laporan->fetch_assoc()): 
            $stok = $row['total_masuk'] - $row['total_keluar'];
        ?>
            <tr>
                <td><?= $row['nama_barang'] ?></td>
                <td><?= $row['nama_kategori'] ?></td>
                <td><?= $row['total_masuk'] ?></td>
                <td><?= $row['total_keluar'] ?></td>
                <td><strong><?= $stok ?></strong></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    
</div>
</body>
</html>
