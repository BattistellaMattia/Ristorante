<!-- Battistella Mattia 5IA - OrderFlow by BEM -->

<?php
include "database.php";
include "controlloLogin.php";

// Inizializza o recupera la sessione del recap
if (!isset($_SESSION['recap_comanda'])) {
    $_SESSION['recap_comanda'] = "";
}

// Se arriva un nuovo recap dal menu, lo aggiungiamo a quello esistente
if (isset($_GET['recap']) && !empty($_GET['recap'])) {
    // Aggiungiamo il nuovo recap a quello esistente in sessione
    $_SESSION['recap_comanda'] .= $_GET['recap'];
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

            <h1>Seleziona Tipologia Piatto</h1>
            
            <?php if (!empty($_SESSION['recap_comanda'])): ?>
            <div class="status-message">
                <p>Stai componendo una comanda con più piatti. Puoi continuare ad aggiungere piatti o finalizzare la comanda.</p>
            </div>
            <?php endif; ?>

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
                <label for="recap">Recap dei piatti aggiunti:</label>
                <textarea id="recap" name="recap" rows="5" cols="50" readonly><?php echo $_SESSION['recap_comanda']; ?></textarea>

                <?php if (!empty($_SESSION['recap_comanda'])): ?>
                <div class="recap-actions">
                    <a href="aggiuntaComandeTipologia.php?clear_recap=true">Cancella Comanda</a>
                </div>
                <?php endif; ?>

                <label for="nota">Aggiungi una nota alla comanda:</label>
                <textarea id="nota" name="nota" rows="2" cols="50" placeholder="Scrivi una nota qui..."></textarea>

                <label for="numero_tavolo">Numero del tavolo:</label>
                <input type="number" id="numero_tavolo" name="numero_tavolo" min="1" placeholder="Inserisci il numero del tavolo" required>
                
                <label for="numero_uscita">Numero di uscita:</label>
                <input type="number" id="numero_uscita" name="numero_uscita" min="1" value="1" placeholder="Inserisci numero di uscita" required>

                <button type="submit" class="pulsanteFineComanda" <?php echo empty($_SESSION['recap_comanda']) ? 'disabled' : ''; ?>>
                    FINE COMANDA
                </button>
            </form>

            <a href="comande.php" class="pulsanteRitorno">Torna Indietro</a>
        </div>
    </body>

    <?php 
    $conn->close(); 
    ?>
</html>