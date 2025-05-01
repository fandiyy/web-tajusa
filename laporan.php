<?php
$conn = new mysqli("localhost", "root", "", "inventory_tajusa");

// Ambil bulan & tahun dari form (default: bulan sekarang)
$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');

// Query laporan masuk
$masuk = $conn->query("
    SELECT b.nama_barang, k.nama_kategori, bm.tanggal, bm.jumlah
    FROM barang_masuk bm
    JOIN barang b ON b.id = bm.barang_id
    JOIN kategori k ON k.id = b.kategori_id
    WHERE MONTH(bm.tanggal) = $bulan AND YEAR(bm.tanggal) = $tahun
    ORDER BY bm.tanggal ASC
");

// Query laporan keluar
$keluar = $conn->query("
    SELECT b.nama_barang, k.nama_kategori, bk.tanggal, bk.jumlah
    FROM barang_keluar bk
    JOIN barang b ON b.id = bk.barang_id
    JOIN kategori k ON k.id = b.kategori_id
    WHERE MONTH(bk.tanggal) = $bulan AND YEAR(bk.tanggal) = $tahun
    ORDER BY bk.tanggal ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bulanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">Laporan Bulanan - <?= date('F Y', strtotime("$tahun-$bulan-01")) ?></h2>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="bulan" class="form-select">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= $bulan == $m ? 'selected' : '' ?>>
                        <?= date('F', strtotime("2023-$m-01")) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="tahun" class="form-select">
                <?php for ($y = 2022; $y <= date('Y'); $y++): ?>
                    <option value="<?= $y ?>" <?= $tahun == $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>
    </form>

    <div class="row">
        <div class="col-md-6">
            <h4>Barang Masuk</h4>
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th>Tanggal</th><th>Nama Barang</th><th>Kategori</th><th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $totalMasuk = 0; while($m = $masuk->fetch_assoc()): $totalMasuk += $m['jumlah']; ?>
                        <tr>
                            <td><?= $m['tanggal'] ?></td>
                            <td><?= $m['nama_barang'] ?></td>
                            <td><?= $m['nama_kategori'] ?></td>
                            <td><?= $m['jumlah'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <th><?= $totalMasuk ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="col-md-6">
            <h4>Barang Keluar</h4>
            <table class="table table-bordered">
                <thead class="table-danger">
                    <tr>
                        <th>Tanggal</th><th>Nama Barang</th><th>Kategori</th><th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $totalKeluar = 0; while($k = $keluar->fetch_assoc()): $totalKeluar += $k['jumlah']; ?>
                        <tr>
                            <td><?= $k['tanggal'] ?></td>
                            <td><?= $k['nama_barang'] ?></td>
                            <td><?= $k['nama_kategori'] ?></td>
                            <td><?= $k['jumlah'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <th><?= $totalKeluar ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <body>
<div class="container py-4">
    <a href="index.php" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>
</div>
</body>

    </div>
</div>
</body>
</html>
