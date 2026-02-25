<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard Clash Royale</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Effetto visivo durante il caricamento dei dati */
        .loading { opacity: 0.5; pointer-events: none; }
        .top1 { background-color: rgba(255, 215, 0, 0.1); font-weight: bold; }
        .top2 { background-color: rgba(192, 192, 192, 0.1); }
        .top3 { background-color: rgba(205, 127, 50, 0.1); }
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
    // DEFINIZIONE VARIABILI (Fondamentali per sapere dove stampare)
    const select = document.getElementById('locationSelect');
    const tbody = document.getElementById('rankBody');
    const title = document.getElementById('tableTitle');

    // 1. CARICAMENTO TENDINA NAZIONI
    async function loadLocations() {
        try {
            const res = await fetch('fetch_leaderboard.php?action=get_locations');
            const data = await res.json();
            
            select.innerHTML = '<option value="global">Global</option>';
            
            // Filtriamo solo le nazioni reali e ordiniamo alfabeticamente
            const countries = data.items.filter(loc => loc.isCountry).sort((a, b) => a.name.localeCompare(b.name));
            
            countries.forEach(loc => {
                const opt = document.createElement('option');
                opt.value = loc.id;
                opt.textContent = loc.name;
                select.appendChild(opt);
            });
        } catch (e) { 
            console.error("Errore caricamento location", e); 
        }
    }

    // 2. STAMPA EFFETTIVA DELLA CLASSIFICA
    async function updateLeaderboard(id, name) {
        const container = document.querySelector('.leaderboard-container');
        container.classList.add('loading'); // Scurisce la tabella mentre carica
        title.innerText = `Top Players (${name})`;
        
        try {
            // Facciamo la richiesta HTTP al PHP
           // 'id' è la variabile che contiene il codice location (es: 'global' o '57000140')
           const res = await fetch(`fetch_leaderboard.php?action=rankings&location=${id}`);
const data = await res.json();

console.log(data); // <--- GUARDA LA CONSOLE: vedi una proprietà "items"?

// Se data.items esiste, usa quello:
const listaGiocatori = data.items || []; 

if (listaGiocatori.length === 0) {
    console.log("Array items vuoto o mancante");
}
            
            tbody.innerHTML = ""; // Puliamo la tabella prima di stampare
            
            if(!data.items || data.items.length === 0) {
                tbody.innerHTML = "<tr><td colspan='4'>Nessun dato trovato.</td></tr>";
                return;
            }

            // Cicliamo il JSON e stampiamo ogni riga
            data.items.forEach((player, idx) => {
                let className = '';
                if (idx === 0) className = 'top1';
                else if (idx === 1) className = 'top2';
                else if (idx === 2) className = 'top3';

                // Gestione sicura del clan (se il giocatore non ne ha uno)
                const clanName = (player.clan && player.clan.name) ? player.clan.name : '-';
                
                // Creiamo la riga HTML
                const row = `
                    <tr class="${className}">
                        <td>${player.rank || (idx + 1)}</td>
                        <td><strong>${player.name}</strong></td>
                        <td>🏆 ${player.trophies}</td>
                        <td>${clanName}</td>
                    </tr>
                `;
                // STAMPA NELL'HTML
                tbody.innerHTML += row;
            });
        } catch (e) {
            tbody.innerHTML = "<tr><td colspan='4'>Errore nel caricamento del file JSON.</td></tr>";
            console.error(e);
        } finally {
            container.classList.remove('loading'); // Torna normale
        }
    }

    // EVENT LISTENER: Quando cambi nazione, aggiorna la classifica
    select.addEventListener('change', (e) => {
        const selectedName = e.target.options[e.target.selectedIndex].text;
        updateLeaderboard(e.target.value, selectedName);
    });

    // AVVIO INIZIALE
    document.addEventListener('DOMContentLoaded', () => {
        loadLocations();
        updateLeaderboard('global', 'Global');
    });
</script>

</body>
</html>