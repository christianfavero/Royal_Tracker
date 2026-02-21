<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clashroyale_project";

$clash_api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjZiYTVjZTVlLWM5NjktNDM0Zi04Y2ZhLWMwNjk5Y2IzYmIxYSIsImlhdCI6MTc3MTY3NjAzOCwic3ViIjoiZGV2ZWxvcGVyLzRkYmVlMGIyLTZiYmItODUxOS1hZDBkLWY3Y2RhZWM1YWVlYyIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyIxODUuMTI2LjE0MS40NyJdLCJ0eXBlIjoiY2xpZW50In1dfQ.hG8eKXEXQhGuD3eGYgecajGMsIJsgqlsz2Vi_U9DUUbfhN1qQizweZvfmoDy8BY15V5l8iY2Y7FiajS6nDf0Ag";
$conn = mysqli_connect($host, $user, $pass, $db);

if($conn -> connect_error){
    die("Errore di connessione al database");
}