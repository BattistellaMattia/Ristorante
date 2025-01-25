<!-- Battistella Mattia 5IA - OrderFlow by BEM -->

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


        <button id = "annulla_comanda">ANNULLA COMANDA</button>

        <!-- pulsante che apre la pagina 'aggiuntaComandeTipologia' per iniziare a creare una nuova comanda -->
        <a href = "aggiuntaComandeTipologia.php" class= "action-button">
        <button id = "aggiunta_comanda">AGGIUNGI COMANDA</button>
        </a>


        <?php
        $sql = "SELECT ID_Comanda, Numero_Tavolo, Ora, Data, Stato, Numero_Coperti, CODICE_Cameriere 
                FROM comanda 
                WHERE true ";

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
                
                <td>
                    <form method = "POST" action = "dettaglioComande.php">
                    <button type = "submit" name = "ID_Comanda" value = "<?= $row['ID_Comanda'] ?>" id="dettaglio_comanda">DETTAGLI</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
            
        <!-- Se schermo piccolo le mostra come card -->
        <?php foreach ($result as $row): ?>
            <div class="row">
                <div class="row-item">
                    <div class="item1"> <span>Numero Tavolo:</span> <?php echo $row['Numero_Tavolo']; ?> </div>
                    <div class="item2"> <span>Numero Coperti:</span> <?php echo $row['Numero_Coperti']; ?> </div>
                 </div>
             <div class="row-item">
                   <div class="item1"> <span>Ora:</span> <?php echo $row['Ora']; ?> </div>
                   <div class="item2"> <span>Data:</span> <?php echo $row['Data']; ?> </div>
             </div>
            <div class="row-item">
                    <div class="item1"> <span>Codice Cameriere:</span> <?php echo $row['CODICE_Cameriere']; ?> </div>
                    <div class="item2"> <span>Stato:</span> <?php echo $row['Stato']; ?> </div>
            </div>

            <div class="button-container">
                <form method="POST" action="dettaglioComande.php">
                    <button type="submit" name="ID_Comanda" value="<?= $row['ID_Comanda'] ?>" id="dettaglio_comanda">DETTAGLI</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
    </body>

    <?php
    $conn -> close();
    ?>

</html>
