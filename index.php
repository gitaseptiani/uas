<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uas-web";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM organisasi";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Organisasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right,rgb(218, 135, 204),rgb(4, 89, 110));
            min-height: 100vh;
            margin: 0;
            color: #fff;
        }
        .container {
            background: rgba(255, 255, 255, 0.9); 
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .table {    
            background-color: #fff; 
            color: #000;
        }
        .btn {
            color: #fff; 
        }
        h2{
            text-align: center;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>DAFTAR ORGANISASI MAHASISWA</h2>
        <?php
        if (isset($_SESSION['success_message'])) {
            echo "<div class='alert alert-success' role='alert'>{$_SESSION['success_message']}</div>";
            unset($_SESSION['success_message']);
        }

        if (isset($_SESSION['error_message'])) {
            echo "<div class='alert alert-danger' role='alert'>{$_SESSION['error_message']}</div>";
            unset($_SESSION['error_message']);
        }
        ?>
        <a href="mahasiswa.php" class="btn btn-secondary mb-3">Kembali</a>
        <table class="table table-striped table-bordered">
            <thead>
                <tr style="text-align: center">
                    <th>ID</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Organisasi</th>
                    <th>Jabatan</th>
                    <th>Pilihan</th>
                </tr>
            </thead>
            <tbody style="text-align: center">
                <?php
                    $sql = "SELECT * FROM organisasi"; 
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['nim_mahasiswa']}</td>
                                    <td>{$row['nama_mahasiswa']}</td>
                                    <td>{$row['nama_organisasi']}</td>
                                    <td>{$row['jabatan']}</td>
                                    <td>
                                        <a href='edit.php?id={$row['id']}' class='btn btn-primary btn-sm'> Edit </a>
                                        <a href='hapus.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data {$row['nama_mahasiswa']}?\")'>Hapus</a>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>Tidak ada data.</td></tr>";
                    }
                ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <a href="logout.php" class="btn btn-dark mb-3">Logout</a>
        </div>
    </div>
</body>

    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>