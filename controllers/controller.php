<?php

require 'models/model.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = ' ';
}

if ($action === ' ') {
    require 'views/index.php';
} elseif ($action === 'register') {
    require 'views/register.php';
} elseif ($action === 'process_register') {
    
    $obj = new account;

    $hovaten = $_POST['hovaten'];
    $gioitinh = $_POST['gioitinh'];
    $ngaysinh = $_POST['ngaysinh'];
    $email = $_POST['email'];
    $matkhau = $_POST['matkhau'];

    $params = [
        'hovaten' => $hovaten,
        'gioitinh' => $gioitinh,
        'ngaysinh' => $ngaysinh,
        'email' => $email,
        'matkhau' => $matkhau
    ];

    $obj->insertAccount('taikhoan', $params);
} elseif ($action === 'signOut') {
    $obj = new account;

    $email = $_SESSION['email'];
    $matkhau = $_SESSION['matkhau'];

    $logoutSuccess = $obj->signOut($hovaten, $email);

    header('location: ./index.php');

    exit();
} elseif ($action === 'processLogin') {
    $obj = new account;

    $email = $_POST['email'];
    $matkhau = $_POST['matkhau'];
    $hovaten = $_POST['hovaten'];
    $quyen = $_POST['quyen'];

    $obj->processLogin($email, $matkhau, $hovaten, $quyen);
} elseif ($action === 'login') {
    require 'views/login.php';
}