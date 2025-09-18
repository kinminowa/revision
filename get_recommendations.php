<?php
header("Content-Type: application/json");

// SkySQL connection details
$host = "serverless-northeurope.sysp0000.db3.skysql.com";
$port = 4120; // Use your current SkySQL port
$user = "dbpbf41763718"; // Your SkySQL username
$password = "X4e7a9I{JE9COlIu6lq9x8@2k"; // Your SkySQL password
$database = "recommendation_db"; // Your database name

// Connect to MariaDB
$conn = new mysqli($host, $user, $password, $database, $port, MYSQLI_CLIENT_SSL);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get the current category from URL
$currentCat = isset($_GET['currentCat']) ? $_GET['currentCat'] : '';

// Prepare SQL query to exclude current category and limit results
$sql = "SELECT title, img, cat, link FROM products";
if ($currentCat) {
    $sql .= " WHERE cat != ?";
}
$sql .= " LIMIT 8";

$stmt = $conn->prepare($sql);

if ($currentCat) {
    $stmt->bind_param("s", $currentCat);
}

$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Return JSON
echo json_encode($products);

$stmt->close();
$conn->close();
?>
