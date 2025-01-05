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
        $sql = "SELECT ID_Comanda, Numero_Tavolo, Ora, Data, Stato, Numero_Coperti, CODICE_Cameriere from comanda WHERE true ";

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if ($_POST['Filtri'] != '')
            {
                $sql .= "AND stato=" . $_POST['Filtri'];
            }
        }

        $result = $conn->query($sql);
        ?>

        <!-- Tabella per mostrare tutte le comande (desktop)-->
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

            <?php foreach ($result as $row): ?>
            <tr>
                <td><?php echo $row['Numero_Tavolo']; ?></td>
                <td><?php echo $row['Ora']; ?></td>
                <td><?php echo $row['Data']; ?></td>
                <td><?php echo $row['Stato']; ?></td>
                <td><?php echo $row['Numero_Coperti']; ?></td>
                <td><?php echo $row['CODICE_Cameriere']; ?></td>
                <td><button id="dettaglio_comanda">DETTAGLI</button></td>
            </tr>
            <?php endforeach; ?>
        </table>
            
        <!-- Se schermo piccolo le mostra come card -->
        <?php foreach ($result as $row): ?>
            <div class="row">
               <div><span>Numero Tavolo:</span> <?php echo $row['Numero_Tavolo']; ?></div>
               <div><span>Ora:</span> <?php echo $row['Ora']; ?></div>
               <div><span>Data:</span> <?php echo $row['Data']; ?></div>
               <div><span>Stato:</span> <?php echo $row['Stato']; ?></div>
               <div><span>Numero Coperti:</span> <?php echo $row['Numero_Coperti']; ?></div>
               <div><span>Codice Cameriere:</span> <?php echo $row['CODICE_Cameriere']; ?></div>
               <div class="button-container">
                   <button id="dettaglio_comanda">DETTAGLI</button>
               </div>
            </div>
        <?php endforeach; ?>

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
