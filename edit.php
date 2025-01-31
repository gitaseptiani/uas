<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
$mysqli = new mysqli('localhost', 'root', '', 'uas-web');

if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

// Ambil data berdasarkan ID
$mahasiswa = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $mysqli->prepare("SELECT * FROM organisasi WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $mahasiswa = $result->fetch_assoc();
    $stmt->close();

    if (!$mahasiswa) {
        $_SESSION['error_message'] = 'Data tidak ditemukan!';
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = 'ID tidak valid!';
    header('Location: index.php');
    exit();
}

// Proses Edit Data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nim_mahasiswa = trim($_POST['nim_mahasiswa']);
    $nama_mahasiswa = trim($_POST['nama_mahasiswa']);
    $nama_organisasi = trim($_POST['nama_organisasi']);
    $jabatan = trim($_POST['jabatan']);

    if (empty($nim_mahasiswa) || empty($nama_mahasiswa) || empty($nama_organisasi) || empty($jabatan)) {
        $_SESSION['error_message'] = 'Semua field harus diisi!';
    } else {
        $stmt = $mysqli->prepare("UPDATE organisasi SET nim_mahasiswa = ?, nama_mahasiswa = ?, nama_organisasi = ?, jabatan = ? WHERE id = ?");
        $stmt->bind_param('ssssi', $nim_mahasiswa, $nama_mahasiswa, $nama_organisasi, $jabatan, $id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Data berhasil diedit!';
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error_message'] = 'Data tidak bisa diedit: ' . $stmt->error;
        }
        $stmt->close();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, rgb(218, 135, 204), rgb(4, 89, 110));
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .form-container h2 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            color: #555;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #f9f9f9;
        }
        .form-group input:focus {
            border-color: #007BFF;
            background-color: #fff;
            outline: none;
        }
        .form-group button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #0f0c29;
            box-shadow: 0px 4px 8px rgb(13, 137, 199);
        }
        .message {
            text-align: center;
            margin-top: 10px;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: red;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>EDIT DATA MAHASISWA</h2>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="message error">
            <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php elseif (isset($_SESSION['success_message'])): ?>
        <div class="message success">
            <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($mahasiswa['id']); ?>">
        
        <div class="form-group">
            <label for="nim_mahasiswa">NIM</label>
            <input type="text" id="nim_mahasiswa" name="nim_mahasiswa" value="<?= htmlspecialchars($mahasiswa['nim_mahasiswa']); ?>" required>
        </div>

        <div class="form-group">
            <label for="nama_mahasiswa">Nama Mahasiswa</label>
            <input type="text" id="nama_mahasiswa" name="nama_mahasiswa" value="<?= htmlspecialchars($mahasiswa['nama_mahasiswa']); ?>" required>
        </div>

        <div class="form-group">
            <label for="nama_organisasi">Organisasi</label>
            <input type="text" id="nama_organisasi" name="nama_organisasi" value="<?= htmlspecialchars($mahasiswa['nama_organisasi']); ?>" required>
        </div>

        <div class="form-group">
            <label for="jabatan">Jabatan</label>
            <input type="text" id="jabatan" name="jabatan" value="<?= htmlspecialchars($mahasiswa['jabatan']); ?>" required>
        </div>

        <div class="form-group">
            <button type="submit">EDIT</button>
        </div>
    </form>
</div>

</body>
</html>
