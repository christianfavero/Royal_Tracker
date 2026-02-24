<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clashroyale_project";

$clash_api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjJiNGJmODYyLTEwZjMtNGRiNy1iYWY1LWVlZWYyMmVlNjFjYiIsImlhdCI6MTc3MTkzMjgyOSwic3ViIjoiZGV2ZWxvcGVyLzU2ZmVkYWQ0LTBmNDktNTIxMi00NzE5LTY3MzNkMjVlMDU1MCIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyI5NS4yMzEuMjAzLjEzNiJdLCJ0eXBlIjoiY2xpZW50In1dfQ.QT7aWnlVnFIdEjZ--3z78V20q96AOj4i56H7gL_j6Ftzvg-mWIdVpsw8I8vb71Z0Cbf7JIm23hnhZ0WHOf0HNQ";
$conn = mysqli_connect($host, $user, $pass, $db);

if($conn -> connect_error){
    die("Errore di connessione al database");
}
?>