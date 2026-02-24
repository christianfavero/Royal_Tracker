<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clashroyale_project";

$clash_api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6ImI3YmU4ZDA1LTVkMDgtNGU4Ny04OTM0LTAxNmNiOTc5NjEwOSIsImlhdCI6MTc3MTkzMzYyNiwic3ViIjoiZGV2ZWxvcGVyLzU2ZmVkYWQ0LTBmNDktNTIxMi00NzE5LTY3MzNkMjVlMDU1MCIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyI5NS4yMzEuMjAzLjEzNiJdLCJ0eXBlIjoiY2xpZW50In1dfQ.trxBNOFkdBpgf0LxP6VPh7MJpv57Zhq026LwAH2UEu2H6bnOiMAfq8lm2tvZS4em3K1i7qTI6OByNTC_3AKmwg";
$conn = mysqli_connect($host, $user, $pass, $db);

if($conn -> connect_error){
    die("Errore di connessione al database");
}
?>