<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clashroyale_project";

$clash_api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6ImE3YzE3ZjQ1LTdhY2ItNDM1OC1hODZlLWJmNzhkZDllMGQ4NSIsImlhdCI6MTc3MTcwMDYzNywic3ViIjoiZGV2ZWxvcGVyLzU2ZmVkYWQ0LTBmNDktNTIxMi00NzE5LTY3MzNkMjVlMDU1MCIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyI5NS4yMzIuMTg2LjIwOSJdLCJ0eXBlIjoiY2xpZW50In1dfQ.XB4X3Xy-eOgLRP4E81sbWb-V9KX_HhAjGxsaTMQonWteqGwGK7BwB4bl_xY81KvDanyJ4jfAgvSMSEsHZrKrFQ";
$conn = mysqli_connect($host, $user, $pass, $db);

if($conn -> connect_error){
    die("Errore di connessione al database");
}