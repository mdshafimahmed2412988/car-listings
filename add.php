<?php
session_start();
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, ['cache' => false]);

$servername = "localhost";
$username = "2412988"; 
$password = "shafim@wolv2024"; 
$dbname = "db2412988"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $mileage = $_POST['mileage'];
    $fuel_type = $_POST['fuel_type'];

    if (empty($brand) || empty($model) || empty($year) || empty($price) || empty($mileage) || empty($fuel_type)) {
        $message = "All fields are required!";
    } else {
 
        $sql = "INSERT INTO cars (brand, model, year, price, mileage, fuel_type) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiiis", $brand, $model, $year, $price, $mileage, $fuel_type);

        if ($stmt->execute()) {
            $message = "Car added successfully!";
        } else {
            $message = "Error adding car: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();

echo $twig->render('add.html', ['message' => $message]);
?>
