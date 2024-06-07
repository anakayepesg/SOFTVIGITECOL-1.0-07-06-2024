<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // El usuario está autenticado, redirigir a comprar.php
    header("Location: comprar.php");
    exit;
} else {
    // El usuario no está autenticado, redirigir a login.php
    header("Location: login.php");
    exit;
}

