<?php
session_start();
if(isset($_SESSION["username"])) {
$logged = true;
}
if(!isset($_SESSION["username"])) {
$logged = false;
}
if (isset($_SESSION["message"])) {
  $message = $_SESSION["message"];
  echo "<script>alert('$message');</script>";
  unset($_SESSION["message"]);
}
include_once 'DBC.php';
$profileId = DBC::getProfileId($_SESSION["username"]);

$jsonFilePath = "chat.json";
$messages = [];
if (file_exists($jsonFilePath)) {
    $existingData = file_get_contents($jsonFilePath);
    $messages = json_decode($existingData, true);
    if ($messages === null) {
        $messages = [];
    }
    echo filemtime($jsonFilePath);
} else {
    echo 0;
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="x-icon" href="../assets/icon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css">
    <title>GCC</title>
  </head>
  <body>
    <div class="site">
      <div class="topnav">
        <a class="icon" href="../index.php">&nbsp;GCC</a>
        <div class="topnav2">
          <a href="about_us.php">About us</a>
          <a href="../index.php" style=" background-color: rgba(0, 0, 0, 0.2);">Chat</a>
          <a href="../index.php">Home</a>
            <a href="<?php if($logged) {echo "profile.php";}else{ echo "login.php";}?>" class="<?php if($logged) {echo "logged-profile-icon-cont";}else{ echo "profile-icon";}?>">
            <?php if($logged) {
              echo '<img src="../profile_pics/pic' . $profileId . '.jpg" class="logged-profile-icon">';
          } else {
             echo '';
          }?>
            </a>
          </div>
        </div>
        <div class="chat-container">
            <div class="messages" id="messages">
            <?php
            if($logged){
            foreach ($messages as $message) { //vygeneroval chat
                $isUserMessage = $message['username'] === $_SESSION['username'] ? 'user' : '';
                echo '<div class="message ' . $isUserMessage . '">';
                echo '<img src="../profile_pics/pic' . htmlspecialchars($message['picture_id']) . '.jpg" class="logged-profile-icon">';
                echo '<p><strong>' . htmlspecialchars($message['username']) . ':&nbsp;</strong></p>';
                echo '<p>' . htmlspecialchars($message['text']) . '</p>';
                echo '</div>';
                }
                }else{
              echo "<p>login to acces</p>";
              }
            ?>
            </div>
            <div class="chat-input">
                <form method="post" action="sendMessage.php" class="login-form">
                <input type="text" id="Inputmessage" name="Inputmessage" placeholder="Type a message..." style="background-color:rgba(0, 0, 0, 0.1); color:black;" class="button" maxlength="2000">
                <button class="button" style="background-color:purple;">Send</button>
                </form>
            </div>
        </div>
      <footer>Petr Taller 2024 ©</footer>
    </div>
    <script src="script.js">
        let lastModifiedTime = <?php echo file_exists($jsonFilePath) ? filemtime($jsonFilePath) : 0; ?>; // nasel jsem jak kontrolovat jestli se meni soubor a pak refreshne stranku
        function checkForUpdates() {
        fetch('checkMessages.php')
        .then(response => response.text())
        .then(data => {
        let modifiedTime = parseInt(data);
            if (modifiedTime > lastModifiedTime) {
                location.reload();
            }
        })
        .catch(error => console.error('Error fetching the JSON file:', error));
    }
    setInterval(checkForUpdates, 5000);
    </script>
  </body>
</html>