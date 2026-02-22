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
    <link rel="stylesheet" href="style.css"> <style>
        :root {
            --bg-dark: #1a1c23;
            --panel-dark: #23262d;
            --accent: #f5b700;
            --text-light: #ffffff;
        }

        .social-container {
            display: flex;
            max-width: 1200px;
            margin: 20px auto;
            height: 80vh;
            background: var(--panel-dark);
            border-radius: 15px;
            border: 1px solid #444;
            overflow: hidden;
        }

        /* Sidebar delle conversazioni */
        .chat-sidebar {
            width: 300px;
            background: #2c2f38;
            border-right: 1px solid #444;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header { padding: 20px; border-bottom: 1px solid #444; text-align: center; }
        
        .chat-list-item {
            padding: 15px;
            border-bottom: 1px solid #383c47;
            cursor: pointer;
            transition: 0.3s;
        }
        .chat-list-item:hover { background: #3d424d; }
        .chat-list-item.active { border-left: 4px solid var(--accent); background: #3d424d; }

        /* Area Chat Principale */
        .chat-main { flex: 1; display: flex; flex-direction: column; background: var(--bg-dark); }
        
        .chat-header { padding: 15px 25px; background: #2c2f38; border-bottom: 1px solid #444; }

        #chat-window {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* Bolle Messaggi (Classi usate da get_messages.php) */
        .message { max-width: 70%; padding: 12px; border-radius: 12px; position: relative; color: white; }
        .incoming { align-self: flex-start; background: #3d424d; border-bottom-left-radius: 2px; }
        .outgoing { align-self: flex-end; background: var(--accent); color: black; border-bottom-right-radius: 2px; }
        
        .message small { display: block; font-size: 0.7em; margin-bottom: 4px; font-weight: bold; }
        .message .time { display: block; font-size: 0.6em; text-align: right; margin-top: 5px; opacity: 0.7; }

        /* Input Area */
        .input-area { padding: 20px; background: #2c2f38; display: flex; gap: 10px; }
        .input-area input {
            flex: 1;
            background: #1a1c23;
            border: 1px solid #555;
            color: white;
            padding: 12px;
            border-radius: 8px;
            outline: none;
        }
        .input-area button {
            background: var(--accent);
            border: none;
            padding: 0 25px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>

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