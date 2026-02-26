<?php
session_start();
require "config.php";

// Controllo Login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Recupero dati dell'utente loggato
$stmt = $conn->prepare("SELECT player_tag, username FROM users WHERE id_user = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data = $result = $stmt->get_result()->fetch_assoc();

if (!$user_data) {
    die("Errore: Profilo non trovato.");
}

$mio_tag_reale = $user_data["player_tag"];
$mio_nickname = $user_data["username"];

// Recupero lista utenti per la sidebar (escludendo me stesso)
$users_stmt = $conn->query("SELECT id_user, username FROM users WHERE id_user != $user_id ORDER BY username ASC");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Social - Royale Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="img/Logo.png">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="Cards.php">Carte</a></li>
            <li><a href="Challenges.php" class="requires-login">Challenges</a></li>
            <li><a href="Social.php" class="requires-login">Community</a></li>
            <li><a href="Video.php" class="requires-login">Video</a></li>
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