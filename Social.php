<?php
session_start();
require "config.php";

// 1. Controllo Login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// 2. Recupero dati dell'utente loggato
$stmt = $conn->prepare("SELECT player_tag, username FROM users WHERE id_user = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data = $result = $stmt->get_result()->fetch_assoc();

if (!$user_data) {
    die("Errore: Profilo non trovato.");
}

$mio_tag_reale = $user_data["player_tag"];
$mio_nickname = $user_data["username"];

// 3. Recupero lista utenti per la sidebar (escludendo me stesso)
$users_stmt = $conn->query("SELECT id_user, username FROM users WHERE id_user != $user_id ORDER BY username ASC");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Royal Tracker - Social</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .social-container { display: flex; height: 70vh; margin: 20px; background: #2c2f38; border-radius: 10px; overflow: hidden; }
        .chat-sidebar { width: 30%; background: #23272a; border-right: 1px solid #444; overflow-y: auto; }
        .chat-main { width: 70%; display: flex; flex-direction: column; }
        
        #chat-box { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; background: #1e2124; }
        
        .chat-list-item { padding: 15px; border-bottom: 1px solid #333; cursor: pointer; transition: 0.3s; }
        .chat-list-item:hover { background: #32353b; }
        .chat-list-item.active { background: #f5b700; }
        .chat-list-item.active strong { color: black !important; }

        .input-area { padding: 15px; background: #2c2f38; display: flex; gap: 10px; }
        .input-area input { flex: 1; padding: 12px; border-radius: 5px; border: none; outline: none; }
        .input-area button { background: #f5b700; color: black; border: none; padding: 0 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo"><img src="Logo.png"></div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="Leaderboard.php">Leaderboard</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
<br><br>
    <div style="margin-top: 100px;">
        <h2 style="text-align:center; color: white;">Centro Sociale Royale</h2>
        
        <div class="social-container">
            <div class="chat-sidebar">
                <div class="chat-list">
                    <div class="chat-list-item active" id="btn-GLOBAL" onclick="switchChat('GLOBAL', 'Chat Globale')">
                        <strong style="color: white;">🌍 Chat Globale</strong><br>
                        <small style="color: #888;">Tutti i player</small>
                    </div>

                    <?php while($u = $users_stmt->fetch_assoc()): ?>
                        <div class="chat-list-item" id="btn-<?= $u['id_user'] ?>" onclick="switchChat(<?= $u['id_user'] ?>, '<?= addslashes($u['username']) ?>')">
                            <strong style="color: white;">👤 <?= htmlspecialchars($u['username']) ?></strong><br>
                            <small style="color: #888;">Messaggio privato</small>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="chat-main">
                <div class="chat-header" style="padding: 15px; background: #23272a; border-bottom: 1px solid #444;">
                    <h3 id="chat-title" style="margin: 0; color: #f5b700;">Chat Globale</h3>
                </div>

                <div id="chat-box">
                    </div>

                <form id="chat-form" class="input-area">
                    <input type="hidden" id="active-chat-id" value="GLOBAL">
                    <input type="hidden" id="my-id" value="<?= $user_id ?>">
                    
                    <input type="text" id="message-text" placeholder="Scrivi un messaggio..." autocomplete="off" required>
                    <button type="submit">INVIA</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    let lastChatContent = "";

    function loadMessages() {
        const chatBox = document.getElementById('chat-box');
        const activeChat = document.getElementById('active-chat-id').value;
        const myId = document.getElementById('my-id').value;
        
        const isAtBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox.scrollTop + 50;

        // In Social.php assicurati che questa riga sia così:
fetch(`get_messages.php?id_chat=${activeChat}&my_tag=<?= $_SESSION['user_id'] ?>`)
            .then(res => res.text())
            .then(data => {
                if (data.trim() !== lastChatContent.trim()) {
                    lastChatContent = data;
                    chatBox.innerHTML = data;
                    if (isAtBottom) {
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }
                }
            });
    }

    function switchChat(id, name) {
        document.getElementById('active-chat-id').value = id;
        document.getElementById('chat-title').innerText = (id === 'GLOBAL') ? "Chat Globale" : "Chat con " + name;
        
        // Gestione classi CSS
        document.querySelectorAll('.chat-list-item').forEach(el => el.classList.remove('active'));
        document.getElementById('btn-' + id).classList.add('active');
        
        lastChatContent = ""; 
        document.getElementById('chat-box').innerHTML = "<p style='color:gray; text-align:center;'>Caricamento conversazione...</p>";
        loadMessages();
    }

    // Timer aggiornamento
    setInterval(loadMessages, 3000);
    loadMessages();

    // Invio messaggio
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const textInput = document.getElementById('message-text');
        const activeChat = document.getElementById('active-chat-id').value;
        
        const formData = new FormData();
        formData.append('id_chat', activeChat);
        formData.append('id_user_sender', document.getElementById('my-id').value);
        formData.append('text', textInput.value);

        fetch('save_chat.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(data => {
            if(data.trim() === "OK") {
                textInput.value = '';
                loadMessages();
                setTimeout(() => {
                    const chatBox = document.getElementById('chat-box');
                    chatBox.scrollTop = chatBox.scrollHeight;
                }, 500);
            }
        });
    });
    </script>
</body>
</html>