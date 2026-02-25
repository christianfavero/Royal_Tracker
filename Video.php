<?php
require "config.php";
require "cr-api.php";

$api = new ClashRoyaleAPI($clash_api_key);
// Cerchiamo video di Clash Royale in italiano o dirette
$videoData = $api->getYouTubeVideosCached("Clash Royale Italia live", 6);
$videos = $videoData['items'] ?? [];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Video & Live - Royal Tracker</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .video-card {
            background: #2c2f38;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
            border: 1px solid #444;
        }
        .video-card:hover { transform: scale(1.03); }
        .video-thumbnail { width: 100%; cursor: pointer; }
        .video-info { padding: 15px; }
        .video-title { color: #f5b700; font-size: 16px; margin-bottom: 10px; }
        .video-meta { color: #aaa; font-size: 12px; }
        
        /* Modal per il video player */
        .video-modal {
            display: none; position: fixed; z-index: 1000;
            left: 0; top: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.9);
        }
        .modal-content {
            position: relative; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 80%; max-width: 800px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="Logo.png">
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
    <main class="home-sections">
        <br><br><br>
        <h2 style="text-align:center;">Video Suggeriti & Live</h2>

        <div class="video-grid">
            <?php foreach($videos as $video): ?>
                <?php 
                    $vId = $video['id']['videoId'];
                    $title = $video['snippet']['title'];
                    $thumb = $video['snippet']['thumbnails']['high']['url'];
                    $channel = $video['snippet']['channelTitle'];
                ?>
                <div class="video-card">
                    <img src="<?php echo $thumb; ?>" class="video-thumbnail" onclick="openVideo('<?php echo $vId; ?>')">
                    <div class="video-info">
                        <h3 class="video-title"><?php echo htmlspecialchars($title); ?></h3>
                        <p class="video-meta">Canale: <?php echo htmlspecialchars($channel); ?></p>
                        <button onclick="openVideo('<?php echo $vId; ?>')" style="margin-top:10px; background:#f5b700; border:none; padding:5px 10px; border-radius:5px; cursor:pointer;">Guarda ora</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <div id="videoModal" class="video-modal" onclick="closeVideo()">
        <div class="modal-content">
            <div class="video-container" style="position: relative; padding-bottom: 56.25%; height: 0;">
                <iframe id="youtubeFrame" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <script>
        function openVideo(id) {
            document.getElementById('youtubeFrame').src = "https://www.youtube.com/embed/" + id + "?autoplay=1";
            document.getElementById('videoModal').style.display = "block";
        }
        function closeVideo() {
            document.getElementById('videoModal').style.display = "none";
            document.getElementById('youtubeFrame').src = "";
        }
    </script>
</body>
</html>