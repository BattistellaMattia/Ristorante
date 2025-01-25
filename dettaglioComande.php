<!-- Battistella Mattia 5IA - OrderFlow by BEM -->

<?php
include "database.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dettagli Comanda</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">    
    </head>

    <body>

    <div class = "comande">
        <?php
            $id_comanda = $_POST['ID_Comanda'];

            $sql = "SELECT ID_Dettaglio, Nota, Stato, Costo, Prezzo, Quantita, Numero_Uscita, ID_Piatto 
                    FROM dettaglio_comanda 
                    WHERE ID_Comanda = $id_comanda AND Stato = 1";
            $result = $conn->query($sql); 
        ?>

        <!-- Tabella per mostrare tutte le comande (desktop)-->
        <table>
            <tr>
            <th>Nota</th>
            <th>Stato</th>
            <th>Costo</th>
            <th>Prezzo</th>
            <th>Quantita</th>
            <th>Numero di uscita</th>
            </tr>

            <?php foreach ($result as $row): ?>
            <tr>
                <td><?php echo $row['Nota']; ?></td>
                <td><?php echo $row['Stato']; ?></td>
                <td><?php echo number_format($row['Costo'], 2, ',', '.'); ?> €</td>
                <td><?php echo number_format($row['Prezzo'], 2, ',', '.'); ?> €</td>
                <td><?php echo $row['Quantita']; ?></td>
                <td><?php echo $row['Numero_Uscita']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <!-- Se schermo piccolo le mostra come card -->
        <?php foreach ($result as $row): ?>
            <div class="row">
                <div><span>Nota:</span> <?php echo $row['Nota']; ?></div>
                <div><span>Stato:</span> <?php echo $row['Stato']; ?></div>
                <div><span>Costo:</span> <?php echo number_format($row['Costo'], 2, ',', '.'); ?> €</div>
                <div><span>Prezzo:</span> <?php echo number_format($row['Prezzo'], 2, ',', '.'); ?> €</div>
                <div><span>Quantita:</span> <?php echo $row['Quantita']; ?></div>
                <div><span>Numero di uscita:</span> <?php echo $row['Numero_Uscita']; ?></div>
            </div>
        <?php endforeach; ?>

        <a href = "comande.php" class = "pulsanteRitorno" > Torna Indietro </a>

        </div>
    </body>

    <?php
    $conn -> close();
    ?>

    </html>
