<?php
include "database.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Comande</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
    
    <div class = "comande">
        <?php
        $sql = "SELECT * from comanda";
        $result = $conn->query($sql);
        ?>
        <table>
            <tr>
            <th>Numero del tavolo</th>
            <th>Ora</th>
            <th>Data</th>
            <th>Stato</th>
            <th>Numero dei Coperti</th>
            <th>Codice del cameriere</th>
            <th>Maggiori dettagli</th>
            </tr>

            <?php foreach ($result as $row):
            echo "<tr>";
            echo "<td> $row[Numero_Tavolo] </td>";
            echo "<td> $row[Ora] </td>";
            echo "<td> $row[Data] </td>";
            echo "<td> $row[Stato] </td>";
            echo "<td> $row[Numero_Coperti] </td>";
            echo "<td> $row[CODICE_Cameriere] </td>";
            echo "<td> <button id = 'dettaglio_comanda'>DETTAGLI</button> </td>";
            echo "</tr>";
            endforeach;
            ?>

        </table>

        <button id = "rimozione_comanda">RIMUOVI COMANDA</button>
        <button id = "aggiunta_comanda">AGGIUNGI COMANDA</button>

    </div>
    </body>

    <?php
    $conn -> close();
    ?>

</html>