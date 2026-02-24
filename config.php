<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clashroyale_project";

$youtube_api_key = "AIzaSyA1YqTcnreHsibyNVeSZj6tLXZpVVA1oUg";
$clash_api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjM1OWMxNzFmLTExZTktNDYzMi1hZTg4LTY5NzM3Yjg1OWRlYSIsImlhdCI6MTc3MTk2NDgxMiwic3ViIjoiZGV2ZWxvcGVyLzU2ZmVkYWQ0LTBmNDktNTIxMi00NzE5LTY3MzNkMjVlMDU1MCIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyIzMS4yNi4xODkuNDAiXSwidHlwZSI6ImNsaWVudCJ9XX0.yi8ka96ONvOtmlEUxplvd3Q3jcR9_gpNM9rexa1RfPEyVfLz2spDzIyg8l-dChxinf93TR8JKdiSJGOD2YlTsw";
$conn = mysqli_connect($host, $user, $pass, $db);

if($conn -> connect_error){
    die("Errore di connessione al database");
}
?>