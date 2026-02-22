<?php
require "config.php";


$id_chat = $_GET['id_chat'] ?? 'GLOBAL';
$my_tag = $_GET['my_tag'] ?? '';

// Se la chat non è GLOBAL, controlla se l'utente ha il permesso
if ($id_chat !== 'GLOBAL') {
    // Se il mio tag NON è presente dentro l'ID della chat, allora non posso leggere
    if (strpos($id_chat, $my_tag) === false) {
        die("Accesso negato: questa chat è privata.");
    }
}

// Questa query unisce (JOIN) la tabella messaggi con quella utenti
// Cambia 'users' con il nome reale della tua tabella utenti
$sql = "SELECT m.messaggio, m.sent_at, m.id_user_sender, u.username 
        FROM messages m
        LEFT JOIN users u ON m.id_user_sender = u.player_tag 
        WHERE m.id_chat = ? 
        ORDER BY m.sent_at ASC LIMIT 50";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $id_chat);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $class = ($row['id_user_sender'] === $my_tag) ? 'outgoing' : 'incoming';
    
    // Se l'utente non viene trovato nel DB, usiamo il tag come ruota di scorta
    $displayName = !empty($row['username']) ? $row['username'] : $row['id_user_sender'];
    
    echo '<div class="message ' . $class . '">';
    echo '<small>' . htmlspecialchars($displayName) . '</small>';
    echo '<div>' . htmlspecialchars($row['messaggio']) . '</div>';
    echo '<span class="time">' . date("H:i", strtotime($row['sent_at'])) . '</span>';
    echo '</div>';
}
?>