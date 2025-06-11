<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = 'localhost';
$dbname = 'iot_sensors';
$username = 'iot_user';
$password = 'iot_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("
        SELECT 
            device_name,
            room,
            building,
            floor,
            temperature,
            humidity,
            co2,
            pressure,
            timestamp
        FROM sensor_data s1
        WHERE timestamp = (
            SELECT MAX(timestamp) 
            FROM sensor_data s2 
            WHERE s2.device_name = s1.device_name
        )
        ORDER BY room ASC
    ");
    
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($results);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de base de données: ' . $e->getMessage()]);
}