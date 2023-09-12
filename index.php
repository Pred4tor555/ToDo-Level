<?php
require 'configDB.php';
session_start();

if (isset($_SESSION['logged_user']) && $_SESSION['logged_user']) {
    echo "<div style='text-align: center;'>";
    echo "<a href='/tasks.php' style='text-decoration: none;'><button style='background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer;'>Задачи</button></a><br>";
    echo "<a href='/logout.php' style='text-decoration: none;'><button style='background-color: #f44336; color: white; padding: 10px 20px; border: none; cursor: pointer;'>Выйти</button></a>";
    echo "</div>";
} else {
    echo "<div style='text-align: center;'>";
    echo "<a href='/login.php' style='text-decoration: none;'><button style='background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer;'>Авторизация</button></a><br>";
    echo "<a href='/signup.php' style='text-decoration: none;'><button style='background-color: #f44336; color: white; padding: 10px 20px; border: none; cursor: pointer;'>Регистрация</button></a><br>";
    echo "</div>";
}
?>