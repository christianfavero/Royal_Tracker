<?php
session_start();
require "config.php"; // Include DB e chiave API
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royal Tracker - Home</title>
    <!-- Link corretto al CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="logo">
            <a href="index.php">Royal Tracker</a>
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="decks.php">Decks</a></li>
            <li><a href="challenges.php" class="requires-login">Challenges</a></li>
            <li><a href="community.php" class="requires-login">Community</a></li>
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

    <!-- HERO / BENVENUTO -->
    <header class="hero">
        <h1>Benvenuto su Royal Tracker!</h1>
        <p>Scopri i deck più forti, partecipa alle sfide, interagisci con la community e guarda video dei migliori player.</p>
        <?php if(!isset($_SESSION["user_id"])): ?>
            <a href="register.php" class="hero-btn">Inizia ora</a>
        <?php endif; ?>
    </header>

    <!-- SEZIONE HOME CARDS -->
    <main class="home-sections">
        <section class="section">
            <h2>Decks Popolari</h2>
            <p>Consulta i deck più utilizzati e i loro win rate tramite l'API di Clash Royale.</p>
            <a href="decks.php" class="btn">Vedi Decks</a>
        </section>

        <section class="section">
            <h2>Challenges</h2>
            <p>Partecipa alle sfide e accumula punti! Richiede accesso.</p>
            <a href="challenges.php" class="btn">Vai alle Challenges</a>
        </section>

        <section class="section">
            <h2>Community</h2>
            <p>Commenti, feedback e interazione tra player. Richiede accesso.</p>
            <a href="community.php" class="btn">Entra nella Community</a>
        </section>

        <section class="section">
            <h2>Video & Live</h2>
            <p>Guarda dirette e video dei canali ufficiali di Clash Royale. Richiede accesso.</p>
            <a href="videos.php" class="btn">Guarda Video</a>
        </section>
    </main>

    <!-- SCRIPT PER AVVISARE UTENTI NON LOGGATI -->
    <script>
        document.querySelectorAll('.requires-login').forEach(link => {
            link.addEventListener('click', function(e){
                <?php if(!isset($_SESSION["user_id"])): ?>
                    e.preventDefault();
                    alert("Devi accedere per usare questa sezione!");
                <?php endif; ?>
            });
        });
    </script>

</body>
</html>
