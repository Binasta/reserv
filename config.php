<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$host = 'localhost';
$dbname = 'reservation_system';
$user = 'admin'; // Modifier si nécessaire
$password = 'admin'; // Modifier si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>