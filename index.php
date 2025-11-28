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


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$brand = $_GET['brand'] ?? '';
$model = $_GET['model'] ?? '';
$year = $_GET['year'] ?? '';
$max_price = $_GET['max_price'] ?? '';


$sql = "SELECT * FROM cars WHERE 1=1";
$params = [];

if ($brand !== '') { 
    $sql .= " AND brand LIKE ?"; 
    $params[] = "%$brand%"; 
}
if ($model !== '') { 
    $sql .= " AND model LIKE ?"; 
    $params[] = "%$model%"; 
}
if ($year !== '') { 
    $sql .= " AND year = ?"; 
    $params[] = $year; 
}
if ($max_price !== '') { 
    $sql .= " AND price <= ?"; 
    $params[] = $max_price; 
}


$stmt = $conn->prepare($sql);
$stmt->execute($params);
$cars = $stmt->get_result();

echo $twig->render('index.html', [
    'cars' => $cars,
    'loggedIn' => isset($_SESSION['user_id']),
    'username' => $_SESSION['username'] ?? '',
    'search_brand' => $brand,
    'search_model' => $model,
    'search_year' => $year,
    'search_max_price' => $max_price
]);

$conn->close();
?>
