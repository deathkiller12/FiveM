<?php
require_once(__DIR__ . "/../config.php");
session_start();

$user_discordid = $_SESSION['user_discordid'];

try{
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
	echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
}

$resetstatus = $pdo->query("UPDATE users SET currstatus='10-7', currdivision='None', currdept='None' WHERE discordid='$user_discordid'");

session_start();
session_unset();
session_destroy();
if(ENABLE_API_SECURITY === true)
    setcookie('yooodhaobga', null, -1, "/");

header("Location: ../login.php");
exit();
?>