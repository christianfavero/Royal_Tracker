<?php
session_start();
require "config.php"; // Include DB e chiave API
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Royal Tracker</title>
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

    <header class="hero">
        <br><br><br>
        <h1>Benvenuto su Royal Tracker!</h1><br>
        <p>Scopri i deck più forti, partecipa alle sfide, interagisci con la community e guarda video dei migliori player.</p><br>
        <?php if(!isset($_SESSION["user_id"])): ?>
            <a href="register.php" class="hero-btn">Inizia ora</a>
        <?php endif; ?>
    </header>

    <main class="home-sections-wrapper">
        <img src="img/ReBlu.png" alt="Immagine laterale" class="side-image">
        <div class="home-sections">
            <section class="section" style = "text-align: center;">
                <h2>Cos'è Royale Tracker?</h2>
                <p> <br>Royal Tracker è la piattaforma dedicata agli appassionati di Clash Royale 
                        che vogliono portare il proprio livello di gioco al gradino successivo.<br>
                        Analizza le tue statistiche, esplora le carte, confrontati con i tuoi amici 
                        e guarda video di gameplay.</p>
            </section>

            <section class="section" style = "text-align: center;">
                <h2>Cosa si può fare su Royale Tracker?</h2>
                <p> <br>Cercare qualsiasi giocatore tramite <a href ="login.php">gamertag</a><br>
                        Parlare con altri giocatori tramite <a href = "Social.php">chat</a><br>
                        Visualizzare le <a href = "Cards.php">carte</a> con livello reale ed evoluzioni<br>
                        Analizzare <a href="Dashboard.php">battle log</a> e performance<br>
                        Partecipare alle <a href = "Challenges.php">challenges</a><br>
                        Guardare <a href = "Video.php">video</a> di youtubers ufficiali<br>
                </p>
            </section>
        </div>
        <img src="img/ReRosso.png" alt="Immagine laterale" class="side-image">
    </main>

    <script>
        //avvisa gli utenti non loggati
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