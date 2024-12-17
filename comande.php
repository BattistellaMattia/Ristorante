<?php
include "database.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Comande</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">    
    </head>

    <body>
    
    <div class = "comande">

        <!-- Form per filtrare lo stato delle comande-->
        <form action="comande.php" method ="post">
        <select name = "Filtri">
            <option value = ""> Tutte </option>
            <option value = "1"> Attive </option>
            <option value = "0"> Concluse </option>
        </select>
        <input type = "submit" value = "Cerca"> </input>
        </form>


        <?php
        $sql = "SELECT * from comanda WHERE true ";

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if ($_POST['Filtri'] != '')
            {
                $sql .= "AND stato=" . $_POST['Filtri'];
            }
        }

        $result = $conn->query($sql);
        ?>

        <!-- Tabella per mostrare tutte le comande-->
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

        <button id = "annulla_comanda">ANNULLA COMANDA</button>

        <a href = "aggiuntaComandeTipologia.php" class= "action-button">
        <button id = "aggiunta_comanda">AGGIUNGI COMANDA</button>
        </a>

    </div>
    </body>

    <?php
    $conn -> close();
    ?>

</html>
