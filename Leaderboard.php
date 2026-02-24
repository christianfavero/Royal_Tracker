<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard Clash Royale</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Aggiunta per il caricamento */
        .loading { opacity: 0.5; pointer-events: none; }
    </style>
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

    <br><br><br>

    <div class="leaderboard-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 id="tableTitle">Top Players (Global)</h2>
            
            <select id="locationSelect" class="custom-select">
                <option value="global">Caricamento nazioni...</option>
            </select>
        </div>

        <table id="rankTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Trofei</th>
                    <th>Clan</th>
                </tr>
            </thead>
            <tbody id="rankBody">
                </tbody>
        </table>
    </div>

<script>
async function updateLeaderboard(id, name) {
    const container = document.querySelector('.leaderboard-container');
    container.classList.add('loading');
    title.innerText = `Top Players (${name})`;
    
    try {
        // Nota: action=rankings serve per far capire al PHP cosa fare
        const res = await fetch(`get_leaderboard.php?action=rankings&location=${id}`);
        const data = await res.json();
        
        tbody.innerHTML = "";
        
        // Verifichiamo che l'API abbia restituito l'array "items"
        if(!data.items || data.items.length === 0) {
            tbody.innerHTML = "<tr><td colspan='4'>Nessun giocatore trovato in questa classifica.</td></tr>";
            return;
        }

        data.items.forEach((player, idx) => {
            // Assegnazione classi per il podio
            let className = '';
            if (idx === 0) className = 'top1';
            else if (idx === 1) className = 'top2';
            else if (idx === 2) className = 'top3';

            // Estraiamo il nome del clan in modo sicuro
            const clanName = (player.clan && player.clan.name) ? player.clan.name : '-';
            
            // Usiamo player.rank dall'API o idx + 1 come fallback
            const rank = player.rank || (idx + 1);

            tbody.innerHTML += `
                <tr class="${className}">
                    <td>${rank}</td>
                    <td><strong>${player.name}</strong></td>
                    <td><span class="trophy-icon">🏆</span> ${player.trophies}</td>
                    <td>${clanName}</td>
                </tr>
            `;
        });
    } catch (e) {
        console.error("Errore fetch:", e);
        tbody.innerHTML = "<tr><td colspan='4'>Errore nel caricamento dei dati dall'API.</td></tr>";
    } finally {
        container.classList.remove('loading');
    }
}
</script>
</body>
</html>