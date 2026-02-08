<?php
session_start();
require "config.php";

// Controllo login opzionale
$logged_in = isset($_SESSION["user_id"]);
$gamertag = $logged_in ? $_SESSION["gamertag"] : null;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clash Royale Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo">Clash Royale Hub</div>
        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="#">Deck</a></li>
            <li><a href="#">Challenges</a></li>
            <li><a href="#">Community</a></li>
            <li><a href="#">Video</a></li>
        </ul>
        <div class="user-gamertag">
            <?php if($logged_in): ?>
                Benvenuto, <?php echo $gamertag; ?>
            <?php else: ?>
                <a href="login.php" style="color: #f5b700;">Accedi</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<main>
    <section class="hero">
        <h1>Benvenuto nell’Arena!</h1>
        <p>Scopri deck, sfide e video ufficiali</p>
    </section>

    <section class="deck-section">
        <h2>Deck più usati</h2>
        <div class="cards-container">
            <div class="card">Deck 1</div>
            <div class="card">Deck 2</div>
            <div class="card">Deck 3</div>
        </div>
    </section>

    <section class="challenge-section">
        <h2>Challenge del giorno</h2>
        <?php if($logged_in): ?>
            <div class="challenge-box">
                Sfida: Vinci 3 partite con Goblin  
                <button>Partecipa</button>
            </div>
        <?php else: ?>
            <div class="challenge-box">
                Devi <a href="login.php">accedere</a> per partecipare alle sfide.
            </div>
        <?php endif; ?>
    </section>

    <section class="community-section">
        <h2>Commenti della Community</h2>
        <?php if($logged_in): ?>
            <div class="comments-box">
                <p><b>Player1:</b> Grande deck!</p>
                <p><b>Player2:</b> Consiglio di usare arcieri!</p>
            </div>
        <?php else: ?>
            <div class="comments-box">
                Devi <a href="login.php">accedere</a> per vedere i commenti.
            </div>
        <?php endif; ?>
    </section>

    <section class="video-section">
        <h2>Video / Live</h2>
        <div class="video-box">
            <iframe width="300" height="170" src="https://www.youtube.com/embed/example" frameborder="0" allowfullscreen></iframe>
        </div>
    </section>
</main>

<footer>
    <p>Non affiliato ufficialmente a Supercell</p>
</footer>

</body>
</html>
