<?php
session_start();
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, ['cache' => false]);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['is_admin'] !== 1) {
    echo "You do not have permission to access this page.";
    exit;
}

$servername = "localhost";
$username = "2412988"; 
$password = "shafim@wolv2024"; 
$dbname = "db2412988";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM cars";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <p><a href="add.php">➕ Add New Car</a> | <a href="logout.php">Logout</a></p>

    <table border="1" cellpadding="8">
        <tr>
            <th>Brand</th>
            <th>Model</th>
            <th>Year</th>
            <th>Price</th>
            <th>Mileage</th>
            <th>Fuel</th>
            <th>Actions</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['brand']; ?></td>
            <td><?php echo $row['model']; ?></td>
            <td><?php echo $row['year']; ?></td>
            <td>£<?php echo $row['price']; ?></td>
            <td><?php echo $row['mileage']; ?> miles</td>
            <td><?php echo $row['fuel_type']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>">✏ Edit</a> |
                <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this car?');">🗑 Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
