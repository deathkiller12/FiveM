<?php
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/discord_functions.php");

$USR_name; 
$USR_discriminator; 
$USR_discordid; 
$USR_SERVER = false; 
$USR_guild_active = "false"; 
$redirect_uri = BASE_URL."/actions/register.php";
$DISCORD_LOGIN_URL = "https://discord.com/api/oauth2/authorize?client_id=".OAUTH2_CLIENT_ID."&redirect_uri=".BASE_URL."%2Factions%2Fregister.php&response_type=code&scope=identify%20email%20guilds"; 

session_start([
    'cookie_lifetime' => 86400,
]);
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 100);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 100);
error_reporting(E_ALL);
if (isset($_GET["error"])) {
    echo json_encode(array("message" => "Authorization Error"));
} elseif (isset($_GET["code"])) {
	
	$data = array(
			"client_id" => OAUTH2_CLIENT_ID,
			"client_secret" => OAUTH2_CLIENT_SECRET,
			"grant_type" => "authorization_code",
			"code" => $_GET["code"],
			"redirect_uri" => $redirect_uri,
			"scope" => "identify guilds"
		);
		
		$token = curl_init();
		curl_setopt($token, CURLOPT_URL, "https://discord.com/api/oauth2/token");
		curl_setopt($token, CURLOPT_POST, 1);
		curl_setopt($token, CURLOPT_POSTFIELDS, http_build_query($data));		
		curl_setopt($token, CURLOPT_RETURNTRANSFER, true);
		$resp = json_decode(curl_exec($token));
		curl_close($token);
	
	// Get user object
    if (isset($resp->access_token)) {
        $access_token = $resp->access_token;
        $info_request = "https://discord.com/api/users/@me";
		$headers = array("Authorization: Bearer {$access_token}");
		
		$info = curl_init();
		curl_setopt($info, CURLOPT_URL, $info_request);
		curl_setopt($info, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($info, CURLOPT_RETURNTRANSFER, true);
		
        $user = json_decode(curl_exec($info));
        curl_close($info);
        $USR_name = $user->username;
		$USR_discriminator = $user->discriminator;
		$USR_discordid = $user->id;

    } else {
        echo json_encode(array("message" => "Couldn't get user object!"));
    }
	
	// Get guild object
	if (isset($resp->access_token)) {
        $access_token = $resp->access_token;
        $info_request = "https://discord.com/api/users/@me/guilds";
		$headers = array("Authorization: Bearer {$access_token}");
		
		$info = curl_init();
		curl_setopt($info, CURLOPT_URL, $info_request);
		curl_setopt($info, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($info, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($info, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($info, CURLOPT_VERBOSE, 1);
		curl_setopt($info, CURLOPT_SSL_VERIFYPEER, 0);
		
        $guilds = curl_exec($info);
        curl_close($info);
		
		// Convert JSON string to Array
		$NewArray = json_decode($guilds, true);
		foreach ($NewArray as $key => $value) {
			if($value["id"] == GUILD_ID)
			{
				$USR_SERVER = true;
				
			} else{}
		}
		
		if($USR_SERVER == false)
		{
			 header("Location: ../login.php?notInDiscord");

		} else {

			try{
				$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
			} catch(PDOException $ex)
			{
				echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
			}
			
			$result = $pdo->query("SELECT discordid FROM users WHERE discordid='$USR_discordid'");
			$exisits = false;
			foreach($result as $row)
			{
				$exisits = true;					
				setupAndSendOnline();
			}

			if($exisits == false)
			{
				$stmt = $pdo->prepare("INSERT INTO users (name, discordid, avatar, identifier) VALUES (?, ?, ?, ?)");
				$result = $stmt->execute(array($USR_name, $USR_discordid, "AVATAR STRING", $USR_name));
				if (!$result) {
				    echo "\nPDO::errorInfo():\n";
				    print_r($stmt->errorInfo());
				}
				setupAndSendOnline();
			}
		}
		
    } else {
        echo json_encode(array("message" => "Couldn't get guilds object!"));
    }
	
	
} else {
    header("Location:" . $DISCORD_LOGIN_URL);
}
	
function setupAndSendOnline(){
	
	global $USR_discordid, $USR_name, $USR_discriminator, $USR_identifier;
	
	$USR_avatar = getDiscordAvatarByID($USR_discordid, "512", "gif");
	
	//ASSIGN THE SESSIONS
	$_SESSION['logged_in'] = 'YES';
    $_SESSION['user_discordid'] = $USR_discordid;
    $_SESSION['user_name'] = $USR_name;
	$_SESSION['user_avatar'] = $USR_avatar; 
	$_SESSION['user_discriminator'] = $USR_discriminator; 

	// ASSIGN DEPARTMENT SESSIONS;
	$_SESSION['adminperms'] = checkAdminPermissions($USR_discordid);
	$_SESSION['civilianperms'] = checkDiscordPermissions($USR_discordid, "Civilian");
	$_SESSION['leoperms'] = checkDiscordPermissions($USR_discordid, "LEO");
	$_SESSION['fireemsperms'] = checkDiscordPermissions($USR_discordid, "FIREEMS");
	$_SESSION['dispatchperms'] = checkDiscordPermissions($USR_discordid, "DISPATCH");
	$_SESSION['courtperms'] = checkDiscordPermissions($USR_discordid, "COURT");
	$_SESSION['supervisor'] = checkDiscordPermissions($USR_discordid, "SUPERVISOR");
	$_SESSION['dmvperms'] = checkDiscordPermissions($USR_discordid, "DMV");
	//$_SESSION['dotperms'] = checkDiscordPermissions($USR_discordid, "DOT");
	$showsupervisor = $_SESSION['supervisor'];

    if (CIVILIAN_PERM_PUBLIC == 1)
    {
        $_SESSION['civilianperms'] = 1;
    }

    if ($_SESSION['adminperms'] == 1) 
    {
        $_SESSION['civilianperms'] = 1;
        $_SESSION['leoperms'] = 1;
        $_SESSION['fireemsperms'] = 1;
        $_SESSION['dispatchperms'] = 1;
        $_SESSION['courtperms'] = 1;
        $_SESSION['dmvperms'] = 1;
        //$_SESSION['dotperms'] = 1;
    }

	try{
		$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
	} catch(PDOException $ex)
	{
		echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
	}
	
	// UPDATE USERS INFO
	$result = $pdo->query("UPDATE users SET name='$USR_name', avatar='$USR_avatar', showsupervisor='$showsupervisor' WHERE discordid='$USR_discordid'");
	
	header("Location: ../index.php");
}
	
?>

