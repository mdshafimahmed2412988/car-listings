<?php

$servername = "localhost";
$username = "2412988"; 
$password = "shafim@wolv2024"; 
$dbname = "db2412988"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$q = isset($_GET['q']) ? $_GET['q'] : ""; 

$q = "%" . $conn->real_escape_string($q) . "%"; 

$sql = "SELECT brand, model, year, price FROM cars WHERE brand LIKE ? OR model LIKE ? OR year LIKE ? OR price LIKE ? LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $q, $q, $q, $q); 
$stmt->execute(); 
$result = $stmt->get_result(); 

if ($result->num_rows > 0) {
 
    while ($row = $result->fetch_assoc()) {

        echo "<div class='suggestion' onclick='selectCar(\"" . htmlspecialchars($row["brand"]) . " " . htmlspecialchars($row["model"]) . "\")'>" . htmlspecialchars($row["brand"]) . " " . htmlspecialchars($row["model"]) . " (" . htmlspecialchars($row["year"]) . ") - £" . htmlspecialchars($row["price"]) . "</div>";
    }
} else {

    echo "<div class='suggestion'>No results found</div>";
}

$stmt->close();
$conn->close();

?>
