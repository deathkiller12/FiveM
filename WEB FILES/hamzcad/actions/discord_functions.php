<?php
require_once __DIR__ . '/../config.php';
session_start();
/**
 * Get a Discord User object
 *
 * @param string $id A user's Discord ID
 * @return object see Discord "User" documentation
 * @url https://discordapp.com/developers/docs/resources/user
 */
function getDiscordUser($id) {

    $ch = curl_init();

    curl_setopt_array($ch, array (
        CURLOPT_URL => 'https://discordapp.com/api/v6/users/' . $id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'DiscordBot ('.BASE_URL.', 1.0.0)',
        CURLOPT_HTTPHEADER => array('Authorization: Bot ' . TOKEN)
    ));

    $user = json_decode(curl_exec($ch));

    curl_close($ch);

    return $user;
}

/**
 * Get a Discord User's avatar URL
 *
 * @param string $id A user's Discord ID
 * @return string avatar URL
 * @url https://discordapp.com/developers/docs/reference#image-formatting
 */
function getDiscordAvatarByID($id, $resolution, $format) {

    if ($resolution % 16 != 0 || $resolution > 2048 || $resolution < 16) {
        throw new InvalidArgumentException('The resolution must be a power of two between 16 and 2048 inclusive.');
    }

    $user = getDiscordUser($id);
    $hash = $user->avatar;

    $gif = "a_";

    if(strpos($hash, $gif) !== false) {
        $format = "gif";
    } 
    else {
        $format = "jpg";
    }


    return "https://cdn.discordapp.com/avatars/" . $id . "/". $hash ."." . $format . "?size=" . $resolution;
}

/**
 * Get the guild object
 *
 * @return object see Discord "Guild" documentation
 * @url https://discordapp.com/developers/docs/resources/guild
 */
function getGuild() {
    $ch = curl_init();

    curl_setopt_array($ch, array (
        CURLOPT_URL => 'https://discordapp.com/api/v6/guilds/' . GUILD_ID,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'DiscordBot ('.BASE_URL.', 1.0.0)',
        CURLOPT_HTTPHEADER => array('Authorization: Bot ' . TOKEN)
    ));

    $guild = json_decode(curl_exec($ch));

    curl_close($ch);

    return $guild;
}

/**
 * Get a guild member object
 *
 * @param string $id A user's Discord ID
 * @return object see Discord "Guild Member" documentation
 * @url https://discordapp.com/developers/docs/resources/guild#guild-member-object
 */
function getGuildMember($id) {
    $ch = curl_init();

    curl_setopt_array($ch, array (
        CURLOPT_URL => 'https://discordapp.com/api/v6/guilds/' . GUILD_ID . '/members/' . $id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'DiscordBot ('.BASE_URL.', 1.0.0)',
        CURLOPT_HTTPHEADER => array('Authorization: Bot ' . TOKEN)
    ));

    $guildMember = json_decode(curl_exec($ch));

    curl_close($ch);

    return $guildMember;
}

/**
 * Get's a guild member's roles as an array
 *
 * @param string $id A user's Discord ID
 * @return string[] array of user's roles
 */
function getGuildMemberRoles($id) {
    $guildMember = getGuildMember($id);
    return $guildMember->roles;
}

/**
 * Get permissions
 *
 *
 * @param string $id A user's Discord ID
 * @return int permission level
 */
function checkAdminPermissions($id) {
    global $ADMINROLES;
    $roles = getGuildMemberRoles($id);

    foreach ($ADMINROLES as $roleid) {
        if (in_array($roleid, $roles)) {
            return 1;
        }
    }

}

function checkDiscordPermissions($id, $type) {
    $roles = getGuildMemberRoles($id);

    try{
        $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
    } catch(PDOException $ex)
    {
        echo "Could not connect -> ".$ex->getMessage();
        die();
    }

    if ($type == "LEO")
    {
        $leo = $pdo->query("SELECT * FROM permissions WHERE permission='LEO'");

        foreach ($leo as $row)
        {
            $roleid = $row['roleid'];
            $permission = $row['permission'];

            if (in_array($roleid, $roles)) {
                return 1;
            } 
        }

    }

    else if ($type == "Civilian")
    {
        $civilian = $pdo->query("SELECT * FROM permissions WHERE permission='Civilian'");

        foreach ($civilian as $row)
        {
            $roleid = $row['roleid'];
            $permission = $row['permission'];

            if (in_array($roleid, $roles)) {
                return 1;
            } 
        }

    }

    else if ($type == "FIREEMS")
    {
        $fireems = $pdo->query("SELECT * FROM permissions WHERE permission='FIREEMS'");

        foreach ($fireems as $row)
        {
            $roleid = $row['roleid'];
            $permission = $row['permission'];

            if (in_array($roleid, $roles)) {
                return 1;
            } 
        }

    }

    else if ($type == "DISPATCH")
    {
        $dispatch = $pdo->query("SELECT * FROM permissions WHERE permission='DISPATCH'");

        foreach ($dispatch as $row)
        {
            $roleid = $row['roleid'];
            $permission = $row['permission'];

            if (in_array($roleid, $roles)) {
                return 1;
            } 
        }

    }

    else if ($type == "COURT")
    {
        $court = $pdo->query("SELECT * FROM permissions WHERE permission='COURT'");

        foreach ($court as $row)
        {
            $roleid = $row['roleid'];
            $permission = $row['permission'];

            if (in_array($roleid, $roles)) {
                return 1;
            } 
        }

    }

    else if ($type == "SUPERVISOR")
    {
        $supervisor = $pdo->query("SELECT * FROM permissions WHERE permission='SUPERVISOR'");

        foreach ($supervisor as $row)
        {
            $roleid = $row['roleid'];
            $permission = $row['permission'];

            if (in_array($roleid, $roles)) {
                return 1;
            } 
        }

    }

    else if ($type == "DMV")
    {
        $dmv = $pdo->query("SELECT * FROM permissions WHERE permission='DMV'");

        foreach ($dmv as $row)
        {
            $roleid = $row['roleid'];
            $permission = $row['permission'];

            if (in_array($roleid, $roles)) {
                return 1;
            } 
        }

    }

    // else if ($type == "DOT")
    // {
    //     $dmv = $pdo->query("SELECT * FROM permissions WHERE permission='DOT'");

    //     foreach ($dmv as $row)
    //     {
    //         $roleid = $row['roleid'];
    //         $permission = $row['permission'];

    //         if (in_array($roleid, $roles)) {
    //             return 1;
    //         } 
    //     }

    // }
}

/**
 * Sends a message to the server, accepts both richEmbed objects and plaintext strings
 *
 * @param mixed $content
 */
function sendLog($content, $channelid) {
    $ch = curl_init();
    $body = array();

    if (is_string($content)) {
        $body = array("content" => $content);
    } else {
        $body = array("embed" => $content);
    }

    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://discord.com/api/v6/channels/".$channelid."/messages",
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($body),
        CURLOPT_HTTPHEADER => array(
            "Content-Type:application/json",
            "Authorization: Bot ". TOKEN .""
        ),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'DiscordBot ('. BASE_URL .', 1.0.0)'
    ));

    $res = curl_exec($ch);
    curl_close($ch);
}

/**
 * Class richEmbed
 * @url https://discordapp.com/developers/docs/resources/channel#embed-object
 */
class richEmbed {
    private $title;
    private $description;
    private $fields;

    /**
     * richEmbed constructor.
     * @param $title
     * @param $content
     */
    function __construct( $title, $content ) {

        if (!is_string($title)) { throw new InvalidArgumentException("Title value must be a string"); }
        if (!is_string($content)) { throw new InvalidArgumentException("Title value must be a string"); }

        $this->title = $title;
        $this->description = $content;
        $this->fields = array();
    }

    /**
     * Adds a field to the array
     *
     * @param $title
     * @param $content
     * @param $inline
     */
    public function addField($title, $content, $inline) {
        if (is_bool($inline)) {
            array_push($this->fields, array(
                "name" => $title,
                "value" => $content,
                "inline" => $inline
            ));
        } else {
            throw new InvalidArgumentException("Inline must be a boolean value");
        }
    }

    /**
     * Builds array structure for sending
     *
     * @return array
     */
    public function build() {

        return array(
            "title" => $this->title,
            "description" => $this->description,
            "color" => hexdec(LOGS_COLOR),
            "fields" => $this->fields,
            "footer" => array("text" => "Hamz CAD | By Hamz#0001", "icon_url" => "" . LOGS_IMAGE . "")
        );
    }
}


if (isset($_GET['refreshdepartmentsessions']))
{
    refreshDepartmentSessions();
}

function refreshDepartmentSessions()
{
    $user_discordid = $_SESSION['user_discordid'];
    // ASSIGN DEPARTMENT SESSIONS;
    $_SESSION['civilianperms'] = 0;
    $_SESSION['leoperms'] = 0;
    $_SESSION['fireemsperms'] = 0;
    $_SESSION['dispatchperms'] = 0;
    $_SESSION['courtperms'] = 0;
    $_SESSION['adminperms'] = 0;
    $_SESSION['supervisor'] = 0;

    $_SESSION['adminperms'] = checkAdminPermissions($user_discordid);
    $_SESSION['civilianperms'] = checkDiscordPermissions($user_discordid, "Civilian");
    $_SESSION['leoperms'] = checkDiscordPermissions($user_discordid, "LEO");
    $_SESSION['fireemsperms'] = checkDiscordPermissions($user_discordid, "FIREEMS");
    $_SESSION['dispatchperms'] = checkDiscordPermissions($user_discordid, "DISPATCH");
    $_SESSION['courtperms'] = checkDiscordPermissions($user_discordid, "COURT");
    $_SESSION['supervisor'] = checkDiscordPermissions($user_discordid, "SUPERVISOR");
    $_SESSION['dmvperms'] = checkDiscordPermissions($user_discordid, "DMV");
    //$_SESSION['dotperms'] = checkDiscordPermissions($user_discordid, "DOT");

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

    header('Location: ../index.php');
}

function checkBan()
{
    $user_discordid = $_SESSION['user_discordid'];

    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);

    $result = $pdo->query("SELECT * FROM bans WHERE discordid='$user_discordid'");

    if (sizeof($result->fetchAll()) > 0)
    {
        header('Location: '.BASE_URL.'/actions/logout.php');
    }

}