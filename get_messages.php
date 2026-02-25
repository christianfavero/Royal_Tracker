<?php
require "config.php";

$id_chat = $_GET['id_chat'] ?? 'GLOBAL';
$my_tag = $_GET['my_tag'] ?? '';

// Questa query collega m.id_user_sender (tag nel messaggio) a u.player_tag (tag nella tabella utenti)
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
    $isMe = ($row['id_user_sender'] === $my_tag);
    $displayName = !empty($row['username']) ? $row['username'] : $row['id_user_sender'];

    // Contenitore che sposta tutto a destra (flex-end) o sinistra (flex-start)
    echo '<div style="display: flex; flex-direction: column; margin-bottom: 15px; width: 100%; ' . ($isMe ? 'align-items: flex-end;' : 'align-items: flex-start;') . '">';
        
        // NICKNAME: allineato coerentemente
        echo '<div style="color: #f5b700; font-weight: bold; font-size: 13px; margin-bottom: 2px; text-align: ' . ($isMe ? 'right' : 'left') . ';">' . htmlspecialchars($displayName) . '</div>';
        
        // BOLLA MESSAGGIO
        echo '<div style="background: ' . ($isMe ? '#005c4b' : '#3b3e46') . '; padding: 10px; border-radius: 10px; color: white; max-width: 80%; shadow: 0 1px 2px rgba(0,0,0,0.3);">';
            echo htmlspecialchars($row['messaggio']);
            // ORARIO
            echo '<div style="font-size: 9px; opacity: 0.6; margin-top: 4px; text-align: right;">' . date("H:i", strtotime($row['sent_at'])) . '</div>';
        echo '</div>';

    echo '</div>';
}
?>