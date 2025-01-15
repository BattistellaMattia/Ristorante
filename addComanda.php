<?php
include "database.php";

$sql = "INSERT into comanda (Numero_Tavolo, Ora, Data, Stato, Numero_Coperti) VALUES (5, CURRENT_TIME(), CURRENT_DATE(), 1, 5 )";
$conn->query($sql);
header("location: ./comande.php?id=".$conn->insert_id);
?>