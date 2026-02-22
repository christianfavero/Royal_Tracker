<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clashroyale_project";

$youtube_api_key = "AIzaSyA1YqTcnreHsibyNVeSZj6tLXZpVVA1oUg";
$clash_api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6ImQ3ZGU3ZmU4LTcwZmItNDk2ZC04NWJmLWUxZjMxMGJlMDhkMCIsImlhdCI6MTc3MTk1NTE5OSwic3ViIjoiZGV2ZWxvcGVyLzU2ZmVkYWQ0LTBmNDktNTIxMi00NzE5LTY3MzNkMjVlMDU1MCIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyI3OS41NC4xODIuMTciXSwidHlwZSI6ImNsaWVudCJ9XX0.S98-g36vvjNEZbU8bLy67fsat-AXNA2R8sSibBBN5H-Fi9RyZcJQVxhVLKzZpx8fHCKGSE0XDpLQYdxEbe4xsw";
$conn = mysqli_connect($host, $user, $pass, $db);

if($conn -> connect_error){
    die("Errore di connessione al database");
}
?>