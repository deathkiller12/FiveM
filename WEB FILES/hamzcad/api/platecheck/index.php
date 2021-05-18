<?php
require_once(__DIR__ . "/../../config.php");

$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);

if (isset($_GET['q'])) {
    $pc = $_GET['q'];

    $result = $pdo->query("SELECT * FROM vehicles WHERE plate='$pc'");
    $count = $pdo->query("SELECT * FROM vehicles WHERE plate='$pc'");

    if (sizeof($count->fetchAll()) > 0) {

        foreach ($result as $row)
        {
            $charid = $row['charid'];
            $result2 = $pdo->query("SELECT * FROM characters WHERE ID='$charid'");
            foreach ($result2 as $row2)
            {
                $owner = $row2['name'];
            }
            $data = [
                'status' => 'Found',
                'info' => [
                    'plate' => $row['plate'],
                    'owner' => $owner,
                    'makemodel' => $row['makemodel'],
                    'vehcolor' => $row['color'],
                    'insurance' => $row['insurance'],
                    'regstate' => $row['regstate'],
                    'flags' => $row['flags'],
                ],
            ];
        }
    } else {
        $data = [
            'status' => 'Error 2',
        ];
    }
} else {
    $data = [
        'status' => 'Error 1',
    ];
}

header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>