<?php
require "config.php";

$id_chat = $_GET['id_chat'] ?? 'GLOBAL';
$my_tag = $_GET['my_tag'] ?? '';

// Recupera i messaggi unendo la tabella utenti per avere il nickname
$sql = "SELECT m.messaggio, m.sent_at, m.id_user_sender, u.username 
        FROM messages m
        LEFT JOIN users u ON m.id_user_sender = u.player_tag 
        WHERE m.id_chat = ? 
        ORDER BY m.sent_at ASC LIMIT 100";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $id_chat);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    // Se il mittente è il mio tag, allora è un messaggio 'outgoing' (destra)
    $isMe = ($row['id_user_sender'] === $my_tag);
    $class = $isMe ? 'outgoing' : 'incoming';
    
    // Mostra il nickname, se non esiste mostra il tag
    $displayName = !empty($row['username']) ? $row['username'] : $row['id_user_sender'];
    
    // Stile CSS in linea per i blocchi messaggio
    $align = $isMe ? 'align-self: flex-end; background: #005c4b; color: white;' : 'align-self: flex-start; background: #3b3e46; color: white;';
    
    echo '<div class="message-wrapper" style="display: flex; flex-direction: column; margin-bottom: 10px; max-width: 70%; ' . ($isMe ? 'align-self: flex-end;' : 'align-self: flex-start;') . '">';
    echo '<small style="font-size: 11px; margin-bottom: 2px; color: #f5b700; ' . ($isMe ? 'text-align: right;' : '') . '">' . htmlspecialchars($displayName) . '</small>';
    echo '<div style="padding: 10px; border-radius: 10px; ' . $align . '">';
    echo htmlspecialchars($row['messaggio']);
    echo '<div style="font-size: 9px; text-align: right; opacity: 0.6; margin-top: 5px;">' . date("H:i", strtotime($row['sent_at'])) . '</div>';
    echo '</div>';
    echo '</div>';
}
?>