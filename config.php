<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clashroyale_project";

$youtube_api_key = "AIzaSyA1YqTcnreHsibyNVeSZj6tLXZpVVA1oUg";
$clash_api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjkxNDJmNjAwLTViMTItNGZiYS1iYmM3LWRjYzJjNWE3N2MzMCIsImlhdCI6MTc3MjA5MTgzNywic3ViIjoiZGV2ZWxvcGVyLzRkYmVlMGIyLTZiYmItODUxOS1hZDBkLWY3Y2RhZWM1YWVlYyIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyIzNy4xNjAuMTY4LjE2OCJdLCJ0eXBlIjoiY2xpZW50In1dfQ.CZx97SSWstAQ9Uf6NYHvA-O680WgttmtxkeqQAzhUhS9hVk1r1wOwnbytBKJg85_5zVbae3ZVWRGjCQwcMuOug";
$conn = mysqli_connect($host, $user, $pass, $db);

if($conn -> connect_error){
    die("Errore di connessione al database");
}
?>