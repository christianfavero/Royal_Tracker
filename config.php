<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clashroyale_project";

$clash_api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjI1MzQ0N2RjLTNjM2EtNDk3YS05OWIwLTNmZDdhMTcwZGY5ZiIsImlhdCI6MTc3MTUyMzIwOCwic3ViIjoiZGV2ZWxvcGVyLzU2ZmVkYWQ0LTBmNDktNTIxMi00NzE5LTY3MzNkMjVlMDU1MCIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyI4Ny41LjE5MC4yNTIiXSwidHlwZSI6ImNsaWVudCJ9XX0.j3v_0v4ZnECHaVaotxpWfcbc83vnSLQ_AOxc9b9Efu-DA88GmOHO2qxa4W2Q6DFA2z8L6YrWCKHNf7kTdhT7yg";

$conn = mysqli_connect($host, $user, $pass, $db);

if($conn -> connect_error){
    die("Errore di connessione al database");
}