<?php
require "config.php";

$id_chat = $_POST['id_chat'] ?? 'GLOBAL';
$id_user_sender = $_POST['id_user_sender'] ?? '';
$text = $_POST['text'] ?? '';

if(!empty($text) && !empty($id_user_sender)){
    $stmt = $conn-> prepare("INSERT INTO messages (id_chat, id_user_sender, messaggio VALUES(?, ?, ?)");
    $stmt->bind_param("sss", $id_chat,$id_user_sender, $text);

    if($stmt->execute()){
        echo "Successo";
    }else echo "Errore";
}

?>
