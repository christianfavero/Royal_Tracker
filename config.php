<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clashroyale_project";

$youtube_api_key = "AIzaSyA1YqTcnreHsibyNVeSZj6tLXZpVVA1oUg";
$clash_api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjQ0MDQyZDNhLTAxMGMtNDc3NC1iYmQ5LWRmOTVlNzQ4YjMyYiIsImlhdCI6MTc3MjA0ODk3OSwic3ViIjoiZGV2ZWxvcGVyLzU2ZmVkYWQ0LTBmNDktNTIxMi00NzE5LTY3MzNkMjVlMDU1MCIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyI5NS4yNDQuMTc2LjQzIl0sInR5cGUiOiJjbGllbnQifV19.av1SAFTr3j5zUBphfbb-6RIRNy0mIRWsr17O1cNFGFWaVB5hJBWsJ5Qp5KwnicxWA746B65O40RZzjlkYRmkcw";
$conn = mysqli_connect($host, $user, $pass, $db);

if($conn -> connect_error){
    die("Errore di connessione al database");
}
?>