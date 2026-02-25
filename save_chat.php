<?php
require "config.php";

$id_chat = $_POST['id_chat']; // Sarà "GLOBAL" oppure un numero (es. "5")
$sender_id = $_POST['id_user_sender']; // Il tuo ID/Tag
$text = trim($_POST['text']);

if (empty($text)) die("Messaggio vuoto");

if ($id_chat === "GLOBAL") {
    // Salva nella tabella globale (quella che hai già)
    $stmt = $conn->prepare("INSERT INTO global_messages (sender_tag, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $sender_id, $text);
} else {
    // CHAT PRIVATA: id_chat è l'ID del destinatario
    $receiver_id = intval($id_chat);
    $stmt = $conn->prepare("INSERT INTO private_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $sender_id, $receiver_id, $text);
}

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "Errore: " . $conn->error;
}
?>