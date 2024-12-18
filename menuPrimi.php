<?php
include "database.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <title>PRIMI</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <?php
        $sql = "SELECT ID_Piatto, Descrizione_Piatto, Descrizione_Ingredienti, ATTIVO, Costo, Prezzo FROM piatto WHERE true ";
        $sql .= "AND ID_Tipologia = 2";
        echo "$sql";
        ?>

        
    </body>
</html>