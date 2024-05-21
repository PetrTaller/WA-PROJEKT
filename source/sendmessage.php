<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Inputmessage'])) {
        $message = $_POST['Inputmessage'];
    } else {
        header("Location: chat.php");
        $_SESSION["message"] = "Sending message was not succesful.";
        exit();
    }
    require_once 'DBC.php';
    $profileId = DBC::getProfileId($_SESSION["username"]);
    $data = array(
        "username" => $_SESSION['username'],
        "picture_id" => $profileId,
        "text" => $message
    );
    $jsonFilePath = "chat.json";
    if (file_exists($jsonFilePath)) { //toto jsem nasel na www.geeksforgeeks
        $existingData = file_get_contents($jsonFilePath);
        $messages = json_decode($existingData, true);
        if ($messages === null) {
            $messages = [];
        }
    } else {
        $messages = [];
    }
    $maxMessages = 25;
    if (count($messages) >= $maxMessages) {
        array_shift($messages);
    }
    $messages[] = $data;
    $jsonData = json_encode($messages);
    file_put_contents($jsonFilePath, $jsonData);
    header("Location: chat.php");
    exit();
}
?>