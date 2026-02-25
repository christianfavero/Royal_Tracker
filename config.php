<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clashroyale_project";

$youtube_api_key = "AIzaSyA1YqTcnreHsibyNVeSZj6tLXZpVVA1oUg";
$clash_api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjBkYWZjOGYwLTdkYjMtNGU3Zi05N2IyLWVmNjE0MzY0ODk3NyIsImlhdCI6MTc3MjAwMjQ3OSwic3ViIjoiZGV2ZWxvcGVyLzU2ZmVkYWQ0LTBmNDktNTIxMi00NzE5LTY3MzNkMjVlMDU1MCIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyIzMS4yNi4yMTguMTkzIl0sInR5cGUiOiJjbGllbnQifV19.TqStjaz5PiOLOh7-QmpLXIx4_UmR9S8SXVMPTqw52EmyhZifnt_iv_kaLxyuLi1DOmxWYQJNGNa3M0iU_6kALA";
$conn = mysqli_connect($host, $user, $pass, $db);

if($conn -> connect_error){
    die("Errore di connessione al database");
}
?>