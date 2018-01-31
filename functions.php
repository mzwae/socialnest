<?php

$dbhost = 'localhost';
$dbname = 'socialnest';
$dbuser = 'socialbird';
$dbpass = 'sbpassword';
$appname = "Social Nest";

$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($connection->connect_err) {
    die($connection->connect_error);
}

// Checks whther a table already exists and, if not, creates it.
function createTable($name, $query)
{
    queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
    echo "Table '$name' created or already exists.<br>";
}

// Issues a query to MySQL, outputting an error message if it fails.
function queryMysql($query)
{
    global $connection;
    $result = $connection->query($query);
    if (!$result) {
        die($connection->error);
    }
    return $result;
}

// Destroys a PHP session and clears its data to log users out.
function destroySession()
{
    $SESSION = array();
    if (session_id() != "" || isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-2595000, '/');
    }

    session_destroy();
}

// Removes potentially malicious code or tags from user input.
function sanitizeString($var)
{
    global $connection;
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $connection->real_escape_string($var);
}

// Displays a user's image and "about me" message if he has one.
function showProfile($user)
{
    if (file_exists("$user.jpg")) {
        echo "<img src='$user.jpg' style='float:left;'>";
    }

    $result = queryMysql("SELECT * FROM profiles WHERE user='user'");

    if ($result->num_rows) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        echo stripslashes($row['text']) . "<br style='clear:left;'><br>";
    }
}
