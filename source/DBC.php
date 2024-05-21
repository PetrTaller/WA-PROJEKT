<?php
class DBC {
public const SERVER_IP = "localhost";
public const USER = "root";
public const PASSWORD = "student";
public const DATABASE = "WAlogin";
private static $connection = null;

protected function __construct()
{
}

public static function getInstance()
{
    if (!self::$connection) {
        self::$connection = new DBC();
    }

    return self::$connection;
}

public static function getConnection()
{
    if (!self::$connection) {
        self::$connection = mysqli_connect(
            self::SERVER_IP,
            self::USER,
            self::PASSWORD,
            self::DATABASE
        );
        if (!self::$connection) {
            die('Could not connect to DB');
        }
    }
    return self::$connection;
}

public static function closeConnection()
{
    if (self::$connection) {
        mysqli_close(self::$connection);
        self::$connection = null;
    }
}

public static function insertUser($username, $password)
{
    $connection = self::getConnection();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $profile_id = 1;
    $query = "INSERT INTO users (username, password,profile_id) VALUES ('$username','$hashedPassword','$profile_id')";
    return mysqli_query($connection, $query);
}

public static function getUser($username)
{
    $connection = self::getConnection();
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($connection, $query);
    return mysqli_fetch_assoc($result);
}

public static function updateUserProfileId($username, $profile_id)
{
    $connection = self::getConnection();
    $username = mysqli_real_escape_string($connection, $username);
    $pictureTitle = mysqli_real_escape_string($connection, $profile_id);
    $query = "UPDATE users SET profile_id = '$profile_id' WHERE username = '$username'";
    return mysqli_query($connection, $query);
}
public static function getProfileId($username)
{
    $connection = self::getConnection();
    $query = "SELECT profile_id FROM users WHERE username='$username'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['profile_id'];
}
}
?>