<?php
include "database.php";
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

            <h1> Seleziona Tipologia Piatto </h1>

            <?php
            $sql = "SELECT ID_Tipologia, Descrizione_Tipologia FROM tipologia_piatto";
            $result = $conn->query($sql);

            //se esistono delle tipologia di piatti
            if ($result->num_rows > 0): ?>

                <!-- creazione dei pulsanti con i nomi di tipologia che porteranno al file menu.php con il metodo POST -->
                <div class="tipologiaPulsanti">
                    <form method="POST" action="menu.php">
                        <?php while($row = $result->fetch_assoc()): ?>
                            <button type="submit" name="ID_Tipologia" value="<?= $row['ID_Tipologia'] ?>" class="tipologiaPulsante">
                                <?= $row['Descrizione_Tipologia'] ?>
                            </button>
                        <?php endwhile; ?>
                    </form>
                </div>

            <?php else: ?>
                <p> Non ci sono tipologie disponibili. </p>
            <?php endif; ?>

            
            <form method = "POST" action = "addComanda.php">
                <label for = "nota">Aggiungi una nota alla comanda:</label>
                <textarea id = "nota" name = "nota" rows = "4" cols = "50" placeholder = "Scrivi una nota qui..."></textarea>
                <a href = "addComanda.php">
                <button type="submit" class = "pulsanteFineComanda">FINE COMANDA</button>
                </a>
            </form>

            <a href = "comande.php" class = "pulsanteRitorno" > Torna Indietro </a>
        </div>
    </body>

    <?php 
    $conn->close(); 
    ?>

</html>
