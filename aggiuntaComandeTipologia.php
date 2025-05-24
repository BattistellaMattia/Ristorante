<!-- Battistella Mattia 5IA - OrderFlow by BEM -->

<?php
include "database.php";
include "controlloLogin.php";

$resetRecap = false;

// Verifica se la pagina è stata aperta senza parametri GET 'recap' 
// e se proviene da comande.php o dalla stessa pagina (dopo un'azione)
if (isset($_SERVER['HTTP_REFERER'])) 
{
    $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
    $refererPage = basename($referer);
    
    // Reset se arrivi da comande.php o da una ricarica della pagina (senza 'recap')
    if (in_array($refererPage, ['comande.php', 'aggiuntaComandeTipologia.php']) && !isset($_GET['recap'])) 
    {
        $resetRecap = true;
    }
} else 
{
    // Se non c'è referer (es. accesso diretto), resetta
    $resetRecap = true;
}

// Svuota il recap se necessario
if ($resetRecap) {
    $_SESSION['recap_comanda'] = "";
}

if (isset($_GET['recap']) && !empty($_GET['recap'])) 
{
    $parts = explode(" - ", $_GET['recap']);
    if (count($parts) === 2) 
    {
        $piatto = trim($parts[0]);
        $quantita = trim($parts[1]);
        $_SESSION['recap_comanda'] .= "$quantita x $piatto\n";
    }
}

// Gestione della cancellazione del recap
if (isset($_GET['clear_recap']) && $_GET['clear_recap'] == 'true') {
    $_SESSION['recap_comanda'] = "";
    header("Location: aggiuntaComandeTipologia.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Aggiunta Comanda - Tipologia</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <div class="tipologia-container">

            <?php
            $sql = "SELECT ID_Tipologia, Descrizione_Tipologia FROM tipologia_piatto";
            $result = $conn->query($sql);

            //se esistono delle tipologia di piatti
            if ($result->num_rows > 0): ?>

                <!-- creazione dei pulsanti con i nomi di tipologia che porteranno al file 'menu.php' con il metodo POST -->
                <div class="tipologiaPulsanti">
                    <form method="POST" action="menu.php">
                        <!-- il '< ?=' sarebbe un '< ?php echo $ ?>' -->
                        <?php while($row = $result->fetch_assoc()): ?>
                            <button type="submit" name="ID_Tipologia" value="<?= $row['ID_Tipologia'] ?>" class="tipologiaPulsante">
                                <?= $row['Descrizione_Tipologia'] ?>
                            </button>
                        <?php endwhile; ?>
                    </form>
                </div>

            <?php else: ?>
                <p>Non ci sono tipologie disponibili.</p>
            <?php endif; ?>

            <form method="POST" action="addComanda.php">
                
                <button type="submit" class="pulsanteFineComanda" <?php echo empty($_SESSION['recap_comanda']) ? 'disabled' : ''; ?>>
                    FINE COMANDA
                </button>

                <a href="comande.php" class="pulsanteRitorno">Torna Indietro</a>

                <label for="recap">Recap dei piatti aggiunti:</label>
                <textarea id="recap" name="recap" rows="5" cols="50" readonly><?php echo $_SESSION['recap_comanda']; ?></textarea>

                <label for="nota">Aggiungi una nota alla comanda:</label>
                <textarea id="nota" name="nota" rows="2" cols="50" placeholder="Scrivi una nota qui..."></textarea>
                
                <div class="numero-tavolo-container">
                <label for="numero_tavolo">Numero del tavolo:</label>
                <input type="number" id="numero_tavolo" name="numero_tavolo" min="1" placeholder="Inserisci il numero del tavolo" required>
                </div>

                <div class="numero-tavolo-container">
                <label for="numero_uscita">Numero di uscita:</label>
                <input type="number" id="numero_uscita" name="numero_uscita" min="1" value="1" placeholder="Inserisci numero di uscita" required>
                </div>
            

                <?php if (!empty($_SESSION['recap_comanda'])): ?>
                <div class="recap-actions">
                    <a href="aggiuntaComandeTipologia.php?clear_recap=true" class="pulsante-cancella-comanda">Cancella Comanda</a>
                </div>
                <?php endif; ?>

            </form>

        </div>
    </body>

    <?php 
    $conn->close(); 
    ?>
</html>