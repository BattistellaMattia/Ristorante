<!-- Battistella Mattia 5IA - OrderFlow by BEM -->

<?php
include "database.php";
include "controlloLogin.php";

$id_comanda = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$id_comanda) 
{
    die("ID comanda non valido.");
}

// Gestione toggle stato
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_stato'])) 
{
    // Ottengo lo stato attuale
    $sql_stato = "SELECT Stato FROM comanda WHERE ID_Comanda = $id_comanda";
    $result_stato = $conn->query($sql_stato);
    
    if ($result_stato->num_rows > 0) 
    {
        $row = $result_stato->fetch_assoc();
        $nuovo_stato = ($row['Stato'] == 1) ? 0 : 1; //sarebbe un if-else
        
        // Aggiorno lo stato
        $sql_update = "UPDATE comanda SET Stato = $nuovo_stato WHERE ID_Comanda = $id_comanda";
        $conn->query($sql_update);
        
        // Redirect
        header("Location: dettaglioComande.php?id=$id_comanda");
        exit();
    }
}

// Query per i dettagli della comanda
$sql = "SELECT dc.ID_Dettaglio, dc.Nota, dc.Stato, dc.Costo, dc.Prezzo, dc.Quantita, dc.Numero_Uscita, p.Descrizione_Piatto 
        FROM dettaglio_comanda dc
        JOIN piatto p ON dc.ID_Piatto = p.ID_Piatto
        WHERE dc.ID_Comanda = $id_comanda AND dc.Stato = 1";
$result = $conn->query($sql);

// Calcolo il totale del prezzo
$totale_prezzo = 0;
foreach ($result as $row) 
{
    $totale_prezzo += $row['Prezzo'];
}
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
    <div class="comande">

        <!-- Pulsante per cambiare lo stato -->
        <div class="stato-comanda">

            <?php
            // Recupero lo stato attuale della comanda
            $sql_stato_comanda = "SELECT Stato FROM comanda WHERE ID_Comanda = $id_comanda";
            $result_stato_comanda = $conn->query($sql_stato_comanda);
            $stato_comanda = ($result_stato_comanda->num_rows > 0) ? $result_stato_comanda->fetch_assoc()['Stato'] : 0;
            ?>

            <form method="POST" action="dettaglioComande.php?id=<?php echo $id_comanda; ?>">
                <input type="hidden" name="id" value="<?php echo $id_comanda; ?>">
                <button type="submit" name="toggle_stato" class="pulsante-stato">
                    Stato attuale: <?php echo ($stato_comanda == 1) ? '🟢 Attiva' : '🔴 Conclusa'; ?>
                </button>
            </form>

        </div>

        <!-- Tabella per mostrare tutte le comande (desktop)-->
        <table>
            <tr>
                <th>Piatto</th>
                <th>Nota</th>
                <th>Stato</th>
                <th>Costo</th>
                <th>Prezzo</th>
                <th>Quantita</th>
                <th>Numero di uscita</th>
            </tr>
            <?php foreach ($result as $row): ?>
                <tr>
                    <td><?php echo $row['Descrizione_Piatto']; ?></td>
                    <td><?php echo $row['Nota']; ?></td>
                    <td><?php echo $row['Stato']; ?></td>
                    <td><?php echo number_format($row['Costo'], 2, ',', '.'); ?> €</td>
                    <td><?php echo number_format($row['Prezzo'], 2, ',', '.'); ?> €</td>
                    <td><?php echo $row['Quantita']; ?></td>
                    <td><?php echo $row['Numero_Uscita']; ?></td>
                </tr>
            <?php endforeach; ?>

            <!-- Riga per il totale -->
            <tr class="totale">
                <td colspan="3">Totale Prezzo</td>
                <td><?php echo number_format($totale_prezzo, 2, ',', '.'); ?> €</td>
                <td colspan="3"></td>
            </tr>

        </table>

        <!-- Se schermo piccolo le mostra come card -->
        <?php foreach ($result as $row): ?>
            <div class="row">
                <div><span>Piatto:</span> <?php echo $row['Descrizione_Piatto']; ?></div>
                <div><span>Nota:</span> <?php echo $row['Nota']; ?></div>
                <div><span>Stato:</span> <?php echo $row['Stato']; ?></div>
                <div><span>Costo:</span> <?php echo number_format($row['Costo'], 2, ',', '.'); ?> €</div>
                <div><span>Prezzo:</span> <?php echo number_format($row['Prezzo'], 2, ',', '.'); ?> €</div>
                <div><span>Quantita:</span> <?php echo $row['Quantita']; ?></div>
                <div><span>Numero di uscita:</span> <?php echo $row['Numero_Uscita']; ?></div>
            </div>
        <?php endforeach; ?>

        <!-- Totale per dispositivi mobili -->
        <div class="row totale">
            <div><span>Totale Prezzo:</span> <?php echo number_format($totale_prezzo, 2, ',', '.'); ?> €</div>
        </div>

        <a href="comande.php" class="pulsanteRitorno">Torna Indietro</a>
    </div>
</body>

<?php $conn->close(); ?>

</html>
