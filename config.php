<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clashroyale_project";

$clash_api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjY0MDgxYTBkLTFiMGItNDBlYy05NTFmLTM4YjJmM2Y0YzI2ZSIsImlhdCI6MTc3MTkzMDI2OCwic3ViIjoiZGV2ZWxvcGVyLzRkYmVlMGIyLTZiYmItODUxOS1hZDBkLWY3Y2RhZWM1YWVlYyIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyI3OC4yMDguNDMuMjAiXSwidHlwZSI6ImNsaWVudCJ9XX0.H0ebyMZ7ySIUSPfWjZfqxdD_x4kqhx-M-Vh3MIBgt7q1ejphRSva-Cx5tZbc9nX-qy7g_Z6bNzveTZfLZwvTUQ";
$conn = mysqli_connect($host, $user, $pass, $db);

if($conn -> connect_error){
    die("Errore di connessione al database");
}
?>