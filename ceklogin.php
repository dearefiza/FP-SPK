<?php
require 'connect.php';

$user = @$_POST['username'];
$pass = @$_POST['password'];

if (empty($user) && empty($pass)) {
    $result = "Username dan password tidak boleh kosong";
} elseif (empty($user)) {
    $result = "Username tidak boleh kosong";
} elseif (empty($pass)) {
    $result = "Password tidak boleh kosong";
} else {

    $query = "SELECT * FROM admin WHERE username='$user'";
    $execute = $konek->query($query);

    if ($execute->num_rows > 0) {

        $data = $execute->fetch_array(MYSQLI_ASSOC);

        if ($pass == $data['password']) {

            session_start();
            $_SESSION['user'] = $data['username'];

            $result = "success";

        } else {
            $result = "Username dan Password tidak cocok";
        }

    } else {
        $result = "Username tidak terdaftar";
    }
}

echo json_encode($result);