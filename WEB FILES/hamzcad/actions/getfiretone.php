<?php
require_once(__DIR__ . "/../config.php");

try{
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
    echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
}

$result = $pdo->query("SELECT * FROM users WHERE currfiretone='1'");
foreach ($result as $row)
{
    $currfiretone = $row['currfiretone'];

    if ($currfiretone == 1) {
?>
        <br><h4 class="text-center text-danger">FIRE TONE PRESSED</span></h4>

        <script>
            var audio = new Audio('../assets/audio/fireTone.mp3');
            audio.volume = 0.2;
            audio.play();
        </script>
<?php
        sleep(3);
        $result2 = $pdo->query("UPDATE users SET currfiretone='0'");
    }
}
?>