<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clashroyale_project";

$clash_api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjlmMjI0YTMwLTE0NmQtNDAxNy05Y2I0LTQ3MDhiNDkyYzhhOCIsImlhdCI6MTc3MTY2MzAxNCwic3ViIjoiZGV2ZWxvcGVyLzRkYmVlMGIyLTZiYmItODUxOS1hZDBkLWY3Y2RhZWM1YWVlYyIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyIzNy4xNjIuMTcwLjMwIiwiOTUuMjMxLjIwMy4xMzYiXSwidHlwZSI6ImNsaWVudCJ9XX0.lVkWOHwamdut4gC3pUUM63YkMyXkxmsS9z_YNNvUtHMoSEqaPnOHbEfhbJbcWvFi0yqiEA-fv-STumWhMa2_Hg";
$conn = mysqli_connect($host, $user, $pass, $db);

if($conn -> connect_error){
    die("Errore di connessione al database");
}