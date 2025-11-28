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

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $car_id = $_GET['id'];

    $sql = "SELECT * FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $car = $result->fetch_assoc();
    } else {
        echo "Car not found.";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $brand = $_POST['brand'];
        $model = $_POST['model'];
        $year = $_POST['year'];
        $price = $_POST['price'];
        $mileage = $_POST['mileage'];
        $fuel_type = $_POST['fuel_type'];

        $update_sql = "UPDATE cars SET brand = ?, model = ?, year = ?, price = ?, mileage = ?, fuel_type = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssiiisi", $brand, $model, $year, $price, $mileage, $fuel_type, $car_id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error updating car: " . $stmt->error;
        }
    }
} else {
    echo "No car ID provided.";
    exit();
}

$conn->close();

echo $twig->render('edit.html', ['car' => $car]);
?>
