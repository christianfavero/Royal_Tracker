<?php
// Forza la visualizzazione degli errori per il debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include la connessione ($conn)
require "config.php"; 

// Recuperiamo i dati dal POST
$id_chat = $_POST['id_chat'] ?? 'GLOBAL';
$id_user_sender = $_SESSION["gamertag"];
$text = $_POST['text'] ?? '';

// LOG DI DEBUG: Vediamo se i dati arrivano al PHP
// Se questo non compare nella "Response" di F12, il file non riceve dati
echo "Dati ricevuti: Sender=$id_user_sender, Text=$text | ";

if (!empty($id_user_sender) && !empty($text)) {
    
    // Usiamo lo stile procedurale che si sposa meglio con il tuo mysqli_connect
    $sql = "INSERT INTO messages (id_chat, id_user_sender, messaggio) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $id_chat, $id_user_sender, $text);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "OK_SUCCESS";
        } else {
            echo "Errore Database: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Errore Preparazione Query: " . mysqli_error($conn);
    }
} else {
    echo "Errore: Campi vuoti. Assicurati che il Tag e il Messaggio non siano nulli.";
}
?>