<?php
session_start();
require "config.php";

if(!isset($_SESSION["user_id"])) {
    die("Devi essere loggato.");
}

$user_id = $_SESSION["user_id"];

/* AVVIA CHALLENGE */
if(isset($_GET['start'])) {
    $challenge_id = intval($_GET['start']);

    $check = $conn->prepare("SELECT * FROM user_challenges 
                             WHERE user_id=? AND challenge_id=?");
    $check->bind_param("ii", $user_id, $challenge_id);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO user_challenge (user_id, challenge_id) VALUES (?,?)");
        $stmt->bind_param("ii", $user_id, $challenge_id);
        $stmt->execute();
    }
}

/* PRENDE TUTTE LE CHALLENGE */
$query = " SELECT c.*, uc.completed
FROM challenges c
LEFT JOIN user_challenge uc 
ON c.id_challenge = uc.id_challenge AND uc.id_user = ?
WHERE uc.completed = 0;
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$challenges = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challeng</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo"><a href="index.php">Royal Tracker</a></div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="Cards.php">Carte</a></li>
            <li><a href="Leaderboard.php">Leaderboard</a></li>
            <li><a href="challenges.php">Challenges</a></li>
        </ul>
    </nav>
    <main class="home-section">
        <br><br><br>
    <h2 style="text-align:center;">Scegli la tua prossima sfida</h2>
    <h1>Challenges</h1>

<?php while($row = $challenges->fetch_assoc()): ?>

<div class="challenge-card">

    <h2><?= $row['title'] ?></h2>
    <p><?= $row['description'] ?></p>

    <?php if(is_null($row['progress'])): ?>
        <!-- NON AVVIATA -->
        <a href="?start=<?= $row['id'] ?>" class="btn">Avvia</a>

    <?php elseif(!$row['completed']): ?>
        <!-- IN CORSO -->
        <?php
            $percent = ($row['progress'] / $row['target']) * 100;
            if($percent > 100) $percent = 100;
        ?>

        <div class="progress-bar">
            <div class="progress" style="width: <?= $percent ?>%"></div>
        </div>
        <p><?= $row['progress'] ?> / <?= $row['target'] ?></p>

    <?php else: ?>
        <!-- COMPLETATA -->
        <p class="completed">✅ Completata!</p>
    <?php endif; ?>

</div>

<?php endwhile; ?> 
</main>
</body>
</html>