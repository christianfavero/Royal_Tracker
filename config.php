<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clashroyale_project";

$youtube_api_key = "AIzaSyA1YqTcnreHsibyNVeSZj6tLXZpVVA1oUg";
$clash_api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjZkZDFkM2ZjLWJjZTMtNDQ2NS05ZDA5LTdkMzc3YmY5MWE0YyIsImlhdCI6MTc3MjAwMjE3Miwic3ViIjoiZGV2ZWxvcGVyLzRkYmVlMGIyLTZiYmItODUxOS1hZDBkLWY3Y2RhZWM1YWVlYyIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyI3OC4yMTEuMTQ5LjE0MiJdLCJ0eXBlIjoiY2xpZW50In1dfQ.dwUtCY5aO35j6nnavSFCx4sE5nimIf_ZSSI_BeJ37wlxxsoMmE3cBzRcPaREaMIUrz8cAPY64suCpAB_YCnSLg
";
$conn = mysqli_connect($host, $user, $pass, $db);

if($conn -> connect_error){
    die("Errore di connessione al database");
}
?>