<!-- Battistella Mattia 5IA - OrderFlow by BEM -->

<?php
include "database.php";
include "controlloLogin.php";
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

        <h2> Benvenuto, <?php echo $_SESSION['nome']; ?> ! </h2>
        <a href = "logout.php"> Logout </a>

        <!-- Form per filtrare lo stato e le date delle comande -->
        <<form action="comande.php" method="post" class="form-filtri">
            <div class="filtri-container">
                <select name="Filtri">
                    <option value=""> Tutte </option>
                    <option value="1"> Attive </option>
                    <option value="0"> Concluse </option>
                </select>

                <div class="filtro-data">
                    <div class="data-group">
                        <label for="data_inizio">Da:</label>
                        <input type="date" id="data_inizio" name="data_inizio" class="date-input">
                    </div>
                    <div class="data-group">
                        <label for="data_fine">A:</label>
                        <input type="date" id="data_fine" name="data_fine" class="date-input">
                    </div>
                </div>

            <input type="submit" value="Cerca" class="pulsante-cerca">
            </div>
        </form>

        <!-- pulsante che apre la pagina 'aggiuntaComandeTipologia' per iniziare a creare una nuova comanda -->
        <a href = "aggiuntaComandeTipologia.php" class= "action-button">
        <button id = "aggiunta_comanda">AGGIUNGI COMANDA</button>
        </a>


        <?php
        $sql = "SELECT ID_Comanda, Numero_Tavolo, Ora, Data, Stato, Numero_Coperti, CODICE_Cameriere 
                FROM comanda 
                WHERE true ";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') //real_escape_string serve per i caratteri speciali
        {
            // Filtro per stato
            if (!empty($_POST['Filtri'])) 
            {
                $sql .= " AND Stato = " . $conn->real_escape_string($_POST['Filtri']);
            }

            // Filtro per data
            if (!empty($_POST['data_inizio'])) 
            {
                $data_inizio = $conn->real_escape_string($_POST['data_inizio']);
                $sql .= " AND Data >= '$data_inizio'";
            }
            if (!empty($_POST['data_fine'])) 
            {
                $data_fine = $conn->real_escape_string($_POST['data_fine']);
                $sql .= " AND Data <= '$data_fine'";
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
                    <form method = "GET" action = "dettaglioComande.php">
                        <input type = "hidden" name = "id" value = "<?= $row['ID_Comanda'] ?>">
                        <button type = "submit" id = "dettaglio_comanda"> DETTAGLI </button>
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

            <div class = "button-container">
                <form method = "GET" action = "dettaglioComande.php">
                    <input type = "hidden" name = "id" value = "<?= $row['ID_Comanda'] ?>">
                    <button type = "submit" id = "dettaglio_comanda"> DETTAGLI </button>
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
