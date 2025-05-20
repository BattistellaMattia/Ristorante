<!-- Battistella Mattia 5IA - OrderFlow by BEM -->

<?php
include "database.php";
include "controlloLogin.php";

$id_comanda = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$id_comanda) 
{
    die("ID comanda non valido.");
}

// Gestione eliminazione comanda
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_comanda'])) 
{
    // Elimino i dettagli della comanda
    $sql_delete_dettagli = "DELETE FROM dettaglio_comanda WHERE ID_Comanda = $id_comanda";
    if (!$conn->query($sql_delete_dettagli)) 
    {
        die("Errore nell'eliminazione dei dettagli: " . $conn->error);
    }

    // Elimino la comanda
    $sql_delete_comanda = "DELETE FROM comanda WHERE ID_Comanda = $id_comanda";
    if (!$conn->query($sql_delete_comanda)) 
    {
        die("Errore nell'eliminazione della comanda: " . $conn->error);
    }

    header("Location: comande.php");
    exit();
}

// Gestione eliminazione singolo piatto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_piatto'])) 
{
    $id_dettaglio = isset($_POST['id_dettaglio']) ? intval($_POST['id_dettaglio']) : null;
    
    if ($id_dettaglio) 
    {
        // Elimino il singolo dettaglio
        $sql_delete_piatto = "DELETE FROM dettaglio_comanda WHERE ID_Dettaglio = $id_dettaglio AND ID_Comanda = $id_comanda";
        if (!$conn->query($sql_delete_piatto)) 
        {
            die("Errore nell'eliminazione del piatto: " . $conn->error);
        }
        
        // Controllo se ci sono ancora piatti nella comanda
        $sql_check = "SELECT COUNT(*) as count FROM dettaglio_comanda WHERE ID_Comanda = $id_comanda";
        $result_check = $conn->query($sql_check);
        $row_check = $result_check->fetch_assoc();
        
        // Se non ci sono più piatti, elimino anche la comanda
        if ($row_check['count'] == 0) 
        {
            $sql_delete_comanda = "DELETE FROM comanda WHERE ID_Comanda = $id_comanda";
            $conn->query($sql_delete_comanda);
            header("Location: comande.php");
            exit();
        }
        
        // Redirect per aggiornare la pagina
        header("Location: dettaglioComande.php?id=$id_comanda");
        exit();
    }
}

// Gestione modifica piatto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_piatto'])) 
{
    $id_dettaglio = isset($_POST['id_dettaglio']) ? intval($_POST['id_dettaglio']) : null;
    $quantita = isset($_POST['quantita']) ? intval($_POST['quantita']) : 1;
    $numero_uscita = isset($_POST['numero_uscita']) ? intval($_POST['numero_uscita']) : 1;
    
    if ($id_dettaglio) 
    {
        // Aggiorno i dati del piatto
        $sql_update_piatto = "UPDATE dettaglio_comanda SET Quantita = $quantita, Numero_Uscita = $numero_uscita 
                              WHERE ID_Dettaglio = $id_dettaglio AND ID_Comanda = $id_comanda";
        
        if (!$conn->query($sql_update_piatto)) 
        {
            die("Errore nell'aggiornamento del piatto: " . $conn->error);
        }
        
        // Redirect per aggiornare la pagina
        header("Location: dettaglioComande.php?id=$id_comanda");
        exit();
    }
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

// Recupero i dettagli della comanda
$sql_comanda = "SELECT c.ID_Comanda, c.Numero_Tavolo, c.Ora, c.Data, c.Stato, c.Numero_Coperti, cm.Nome, cm.Cognome 
                FROM comanda c
                LEFT JOIN cameriere cm ON c.CODICE_Cameriere = cm.CODICE_Cameriere
                WHERE c.ID_Comanda = $id_comanda";
$result_comanda = $conn->query($sql_comanda);
$comanda_info = ($result_comanda && $result_comanda->num_rows > 0) ? $result_comanda->fetch_assoc() : null;

if (!$comanda_info) 
{
    die("Comanda non trovata.");
}

// Query per i dettagli della comanda
$sql = "SELECT dc.ID_Dettaglio, dc.Nota, dc.Stato, dc.Costo, dc.Prezzo, dc.Quantita, dc.Numero_Uscita, p.Descrizione_Piatto 
        FROM dettaglio_comanda dc
        JOIN piatto p ON dc.ID_Piatto = p.ID_Piatto
        WHERE dc.ID_Comanda = $id_comanda AND dc.Stato = 1";
$result = $conn->query($sql);

// Calcolo il totale del prezzo
$totale_prezzo = 0;
$dettagli = [];
while ($row = $result->fetch_assoc()) 
{
    $totale_prezzo += $row['Prezzo'];
    $dettagli[] = $row;
}

// Raggruppo i dettagli per numero di uscita
$dettagli_per_uscita = [];
foreach ($dettagli as $dettaglio) 
{
    $uscita = $dettaglio['Numero_Uscita'];
    if (!isset($dettagli_per_uscita[$uscita])) 
    {
        $dettagli_per_uscita[$uscita] = [];
    }
    $dettagli_per_uscita[$uscita][] = $dettaglio;
}
ksort($dettagli_per_uscita); // Ordinamento per numero di uscita

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
                <input type = "hidden" name = "id" value = "<?php echo $id_comanda; ?>">
                <button type = "submit" name = "toggle_stato" class = "pulsante-stato">
                    Stato attuale: <?php echo ($stato_comanda == 1) ? '🟢 Attiva' : '🔴 Conclusa'; ?>
                </button>
            </form>

            <!-- Form per eliminare la comanda -->
            <form method = "POST" action = "dettaglioComande.php?id=<?php echo $id_comanda; ?>" onsubmit="return confirm('Sei sicuro di voler rimuovere questa comanda?');">
                <input type = "hidden" name = "delete_comanda" value = "1">
                <button type = "submit" class = "pulsante-annulla"> ANNULLA TUTTA LA COMANDA </button>
            </form>

        </div>

        <!-- Tabella per mostrare i dettagli (desktop)-->
        <table>
            <tr>
                <th>Piatto</th>
                <th>Nota</th>
                <th>Stato</th>
                <th>Costo</th>
                <th>Prezzo</th>
                <th>Quantita</th>
                <th>Numero di uscita</th>
                <th>Azioni</th>
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
                    <td class="azioni-container">
                        <!-- Pulsante MODIFICA -->
                        <button type="button" class="pulsante-modifica" onclick="apriModalModifica(<?php echo $row['ID_Dettaglio']; ?>, '<?php echo $row['Descrizione_Piatto']; ?>', <?php echo $row['Quantita']; ?>, <?php echo $row['Numero_Uscita']; ?>)">MODIFICA</button>
                        
                        <!-- Pulsante ANNULLA -->
                        <form method="POST" action="dettaglioComande.php?id=<?php echo $id_comanda; ?>" onsubmit="return confirm('Sei sicuro di voler rimuovere questo piatto?');">
                            <input type="hidden" name="id_dettaglio" value="<?php echo $row['ID_Dettaglio']; ?>">
                            <input type="hidden" name="delete_piatto" value="1">
                            <button type="submit" class="pulsante-annulla-piatto">ANNULLA</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>

            <!-- Riga per il totale -->
            <tr class="totale">
                <td colspan="4">Totale Prezzo</td>
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
                <div class="button-container">
                    <!-- Pulsante MODIFICA per mobile -->
                    <button type="button" class="pulsante-modifica" onclick="apriModalModifica(<?php echo $row['ID_Dettaglio']; ?>, '<?php echo $row['Descrizione_Piatto']; ?>', <?php echo $row['Quantita']; ?>, <?php echo $row['Numero_Uscita']; ?>)">MODIFICA</button>
                    
                    <!-- Pulsante ANNULLA per mobile -->
                    <form method="POST" action="dettaglioComande.php?id=<?php echo $id_comanda; ?>" onsubmit="return confirm('Sei sicuro di voler rimuovere questo piatto?');">
                        <input type="hidden" name="id_dettaglio" value="<?php echo $row['ID_Dettaglio']; ?>">
                        <input type="hidden" name="delete_piatto" value="1">
                        <button type="submit" class="pulsante-annulla-piatto">ANNULLA</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Totale per dispositivi mobili -->
        <div class="row totale">
            <div><span>Totale Prezzo:</span> <?php echo number_format($totale_prezzo, 2, ',', '.'); ?> €</div>
        </div>

        <a href="comande.php" class="pulsanteRitorno">Torna Indietro</a>
        <!-- Modal per la modifica del piatto -->
        <div id="modalModifica" class="modal">
            <div class="modal-content">
                <h2 id="titoloPiatto">Modifica Piatto</h2>
                <form id="formModifica" method="POST" action="dettaglioComande.php?id=<?php echo $id_comanda; ?>">
                    <input type="hidden" name="id_dettaglio" id="id_dettaglio">
                    <input type="hidden" name="update_piatto" value="1">
                    
                    <div class="form-group">
                        <label for="quantita">Quantità:</label>
                        <input type="number" id="quantita" name="quantita" min="1" value="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="numero_uscita">Numero di Uscita:</label>
                        <input type="number" id="numero_uscita" name="numero_uscita" min="1" value="1" required>
                    </div>
                    
                    <div class="modal-buttons">
                        <button type="button" class="pulsante-annulla-modifica" onclick="chiudiModal()">Annulla</button>
                        <button type="submit" class="pulsante-salva">Salva Modifiche</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Funzioni per gestire il modal
        function apriModalModifica(id, nomePiatto, quantita, numeroUscita) {
            document.getElementById('titoloPiatto').textContent = 'Modifica ' + nomePiatto;
            document.getElementById('id_dettaglio').value = id;
            document.getElementById('quantita').value = quantita;
            document.getElementById('numero_uscita').value = numeroUscita;
            document.getElementById('modalModifica').style.display = 'block';
        }
        
        function chiudiModal() {
            document.getElementById('modalModifica').style.display = 'none';
        }
        
        // Chiudi il modal se si clicca fuori da esso
        window.onclick = function(event) {
            var modal = document.getElementById('modalModifica');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>

<?php $conn->close(); ?>

</html>