<?php
session_start();
echo "DEBUG - ID in sessione: " . ($_SESSION["username"] ?? "NON ESISTE");
require "config.php";
/* =========================
   RECUPERO DATI UTENTE
========================= */
$stmt = $conn->prepare("SELECT player_tag, username FROM users WHERE id_user = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc(); // Cambiamo nome in $user_data per non confonderlo con "root"

if ($user_data) {
    // Ora assegniamo i valori alle variabili singole
    $mio_tag_reale = $user_data["player_tag"];
    $mio_nickname = $user_data["username"];
    
    // Invece di echo $user_data (che dà errore), stampiamo il valore specifico:
    echo "Bentornato, " . $mio_nickname; 
} else {
    echo "Errore: Utente non trovato.";
}

?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Royal Tracker - Social</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar">
        <div class="logo">
            <a href="index.php">Royal Tracker</a>
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="Cards.php">Carte</a></li>
            <li><a href = "Leaderboard.php">Leaderboard</a></li>
            <li><a href="challenges.php" class="requires-login">Challenges</a></li>
            <li><a href="Social.php" class="requires-login">Community</a></li>
            <li><a href="videos.php" class="requires-login">Video</a></li>
        
        </ul>

        <ul class="nav-links">
            <?php if(isset($_SESSION["user_id"])): ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Accedi</a></li>
                <li><a href="register.php">Registrati</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <br><br><br><br>
<div class="social-container">
    <div class="chat-sidebar">
        <div class="sidebar-header">
            <h2 style="color: var(--accent); margin: 0;">Social</h2>
        </div>
        <div class="chat-list">
            <div class="chat-list-item active" onclick="changeChat('GLOBAL', 'Chat Globale')">
                <strong style="color: white;">🌍 Chat Globale</strong><br>
                <small style="color: #888;">Tutti i player</small>
            </div>
            </div>
    </div>

    <div class="chat-main">
        <div class="chat-header">
            <h3 id="chat-title" style="margin: 0; color: white;">Chat Globale</h3>
        </div>

        <div id="chat-window">
            </div>

       <form id="chat-form" class="input-area">
    <input type="hidden" id="active-chat-id" value="GLOBAL">
    
    <input type="hidden" id="my-tag" value="<?php echo htmlspecialchars($mio_tag_reale); ?>">
    
    <input type="text" id="message-text" placeholder="Scrivi come <?php echo htmlspecialchars($mio_nickname); ?>..." autocomplete="off" required>
    <button type="submit">INVIA</button>
</form>
    </div>
</div>



<script>
// Carica i messaggi
function loadMessages() {
    const idChat = document.getElementById('active-chat-id').value;
    const myTag = document.getElementById('my-tag').value;

    fetch(`get_messages.php?id_chat=${idChat}&my_tag=${encodeURIComponent(myTag)}`)
        .then(res => res.text())
        .then(html => {
            const window = document.getElementById('chat-window');
            window.innerHTML = html;
            window.scrollTop = window.scrollHeight; // Sempre in fondo
        });
}

// Invia messaggio
document.getElementById('chat-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const textInput = document.getElementById('message-text');
    const formData = new FormData();
    
    formData.append('id_chat', document.getElementById('active-chat-id').value);
    formData.append('id_user_sender', document.getElementById('my-tag').value);
    formData.append('text', textInput.value);

    fetch('save_chat.php', { method: 'POST', body: formData })
    .then(() => {
        textInput.value = '';
        loadMessages();
    });
});

// Cambia stanza (Globale o Privata)
function changeChat(id, title) {
    document.getElementById('active-chat-id').value = id;
    document.getElementById('chat-title').innerText = title;
    // Rimuovi/Aggiungi classe active
    document.querySelectorAll('.chat-list-item').forEach(el => el.classList.remove('active'));
    event.currentTarget.classList.add('active');
    loadMessages();
}

function openPrivateChat(targetTag, targetNickname) {
    const myTag = document.getElementById('my-tag').value;
    
    // Genera ID unico (es: #TAG1_#TAG2)
    const chatID = [myTag, targetTag].sort().join('_');
    
    // Cambia la chat attiva
    changeChat(chatID, "Chat con " + targetNickname);
}

// Update ogni 3 secondi
setInterval(loadMessages, 3000);
loadMessages();
</script>

</body>
</html>