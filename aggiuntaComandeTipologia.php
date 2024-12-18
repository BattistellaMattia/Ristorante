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
            $sql = "SELECT * FROM tipologia_piatto";
            $result = $conn->query($sql);

            if ($result->num_rows > 0): ?>

                <div class="tipologiaPulsanti">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <form action="aggiuntaComandaPiatti.php" method="GET">
                            <input type = "hidden" name = "ID_Tipologia" value = "<?php echo $row['ID_Tipologia']; ?>">
                            <button type = "submit" class = "tipologiaPulsante">
                                <?php echo $row["Descrizione_Tipologia"]; ?>
                            </button>
                        </form>
                    <?php endwhile; ?>
                </div>

            <?php else: ?>
                <p> Non ci sono tipologie disponibili. </p>
            <?php endif; ?>

            
    
            <a href = "comande.php" class = "pulsanteRitorno" > Torna Indietro </a>
        </div>
    </body>

    <?php 
    $conn->close(); 
    ?>

</html>
