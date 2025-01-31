<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uas-web";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $users = $result->fetch_assoc();
        if ($password === $users['password']) {
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $users['id'];
            $_SESSION['email'] = $users['email'];
            $_SESSION['success_message'] = "Selamat, Anda berhasil login!";

            if ($remember) {
                setcookie("user_email", $users['email'], time() + (86400 * 1), "/"); 
            }

            header("Location: mahasiswa.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Password salah!";
        }
    } else {
        $_SESSION['error_message'] = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right,rgb(218, 135, 204),rgb(4, 89, 110));
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            border-radius: 15px;
        }
        h1 {
            color: #333;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-5 bg-light">
                    <h1 class="text-center mb-4">LOGIN</h1>
                    <?php
                        if (isset($_SESSION['success_message'])) {
                            echo "<div class='alert alert-success' role='alert' text-center>{$_SESSION['success_message']}</div>";
                            unset($_SESSION['success_message']);
                        }

                        if (isset($_SESSION['error_message'])) {
                            echo "<div class='alert alert-danger' role='alert' text-center>{$_SESSION['error_message']}</div>";
                            unset($_SESSION['error_message']);
                        }
                    ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" name="password" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1" name="remember">
                            <label class="form-check-label" for="exampleCheck1">Simpan Password!</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
