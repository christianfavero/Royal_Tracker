<?php
session_start();
require "config.php";

// Controllo Login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

/* =========================
   RECUPERO DATI UTENTE
========================= */
$stmt = $conn->prepare("SELECT player_tag, username FROM users WHERE id_user = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
    die("Errore: Profilo non configurato. Assicurati di avere un Player Tag associato.");
}

$mio_tag_reale = $user_data["player_tag"];
$mio_nickname = $user_data["username"];
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
        <div class="logo"><a href="index.php">Royal Tracker</a></div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="Cards.php">Carte</a></li>
            <li><a href="Leaderboard.php">Leaderboard</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <br><br><br><br>

    <div class="social-container">
        <div class="chat-sidebar">
            <div class="sidebar-header">
                <h2 style="color: #f5b700; margin: 0;">Social</h2>
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

            <div id="chat-window" style="height: 400px; overflow-y: auto; padding: 15px; background: #1e2124; display: flex; flex-direction: column;">
                </div>

            <form id="chat-form" class="input-area" style="display: flex; gap: 10px; padding: 15px; background: #2c2f38;">
                <input type="hidden" id="active-chat-id" value="GLOBAL">
                <input type="hidden" id="my-tag" value="<?php echo htmlspecialchars($mio_tag_reale); ?>">
                
                <input type="text" id="message-text" placeholder="Scrivi come <?php echo htmlspecialchars($mio_nickname); ?>..." 
                       style="flex: 1; padding: 10px; border-radius: 5px; border: none;" autocomplete="off" required>
                <button type="submit" style="background: #f5b700; color: black; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">INVIA</button>
            </form>
        </div>
    </div>

    <script>
    const chatWindow = document.getElementById('chat-window');

    function loadMessages() {
        const idChat = document.getElementById('active-chat-id').value;
        const myTag = document.getElementById('my-tag').value;

        fetch(`get_messages.php?id_chat=${idChat}&my_tag=${encodeURIComponent(myTag)}`)
            .then(res => res.text())
            .then(html => {
                chatWindow.innerHTML = html;
                // Scroll automatico verso il basso
                chatWindow.scrollTop = chatWindow.scrollHeight;
            });
    }

    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const textInput = document.getElementById('message-text');
        const myTag = document.getElementById('my-tag').value;
        const chatId = document.getElementById('active-chat-id').value;

        const formData = new FormData();
        formData.append('id_chat', chatId);
        formData.append('id_user_sender', myTag);
        formData.append('text', textInput.value);

        fetch('save_chat.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(data => {
            if(data.trim() === "OK") {
                textInput.value = '';
                loadMessages();
            } else {
                alert("Errore nell'invio: " + data);
            }
        });
    });

    // Aggiornamento ogni 3 secondi
    setInterval(loadMessages, 3000);
    loadMessages();
    </script>
</body>
</html>