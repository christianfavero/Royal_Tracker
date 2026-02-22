<?php
require "config.php";
// Proviamo a inserire un messaggio fisso senza passare dal form
$sql = "INSERT INTO messages (id_chat, id_user_sender, messaggio) VALUES ('GLOBAL', '#TEST_TAG', 'Se vedi questo, il DB funziona!')";
if ($conn->query($sql)) {
    echo "✅ IL DATABASE FUNZIONA! Controlla la tabella messages.";
} else {
    echo "❌ ERRORE DB: " . $conn->error;
}
?>