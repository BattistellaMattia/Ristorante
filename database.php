<!-- Battistella Mattia 5IA - OrderFlow by BEM -->

<?php
//parametri per la connessione al database
$server_name = "localhost";
$username = "root";
$password = "";
$db_name = "esercizio_comande"; // Nome del database

//connessione al database
$conn = new mysqli($server_name, $username, $password, $db_name);

//verifica della connessione
if ($conn->connect_error) 
{
    die("Connessione fallita: " . $conn->connect_error);
}
?>