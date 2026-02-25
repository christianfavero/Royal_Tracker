<?php
require "config.php";

$id_chat = $_GET['id_chat'];
$my_id = $_GET['my_tag']; // Questo deve essere l'ID numerico (es. $_SESSION['user_id'])

if ($id_chat === "GLOBAL") {
    // JOIN per recuperare lo username e l'ID numerico del mittente
    $query = "SELECT m.*, u.username, u.id_user 
              FROM messages m 
              LEFT JOIN users u ON (m.id_user_sender = u.player_tag OR m.id_user_sender = u.id_user)
              ORDER BY m.sent_at ASC LIMIT 50";
    $result = $conn->query($query);
} else {
    $other_id = intval($id_chat);
    $query = "SELECT pm.*, u.username, u.id_user 
              FROM private_messages pm
              LEFT JOIN users u ON pm.sender_id = u.id_user
              WHERE (pm.sender_id = ? AND pm.receiver_id = ?) 
              OR (pm.sender_id = ? AND pm.receiver_id = ?) 
              ORDER BY pm.sent_at ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiii", $my_id, $other_id, $other_id, $my_id);
    $stmt->execute();
    $result = $stmt->get_result();
}

while ($row = $result->fetch_assoc()): 
    /* CONTROLLO IDENTITÀ:
       Verifichiamo se l'ID utente che ha inviato (preso dalla JOIN con 'users') 
       corrisponde al mio ID in sessione.
    */
    $sender_actual_id = $row['id_user'] ?? null; 
    $isMe = ($sender_actual_id == $my_id);
    
    $messageClass = $isMe ? 'message-sent' : 'message-received';
    $display_name = $isMe ? "Tu" : ($row['username'] ?? "Player");
    
    // Supporto per entrambi i nomi colonna (globale/privata)
    $testo = $row['messaggio'] ?? $row['message'];
?>
    <div class="message-bubble <?= $messageClass ?>">
        <span class="message-user"><?= htmlspecialchars($display_name) ?></span>
        <div class="message-text">
            <?= htmlspecialchars($testo) ?>
        </div>
        <span class="message-time"><?= date("H:i", strtotime($row['sent_at'])) ?></span>
    </div>
<?php endwhile; ?>