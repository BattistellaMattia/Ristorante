<!-- Battistella Mattia 5IA - OrderFlow by BEM -->

<?php
include "database.php";

//inserimento della comanda nel database
$sql = "INSERT into comanda (Numero_Tavolo, Ora, Data, Stato, Numero_Coperti) VALUES (5, CURRENT_TIME(), CURRENT_DATE(), 1, 5 )";
$conn->query($sql);
header("location: ./comande.php?id=".$conn->insert_id);


$conn -> close();    
?>