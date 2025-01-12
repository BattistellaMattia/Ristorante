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
                    <button type = "submit" class = "tipologiaPulsante" id = "antipasto"> ANTIPASTI </button>
                    <button type = "submit" class = "tipologiaPulsante" id = "primo"> PRIMI </button>
                    <button type = "submit" class = "tipologiaPulsante" id = "secondo"> SECONDI </button>
                    <button type = "submit" class = "tipologiaPulsante" id = "dolce"> DOLCI </button>
                    <button type = "submit" class = "tipologiaPulsante" id = "bevanda"> BEVANDE </button>

                </div>

            <?php else: ?>
                <p> Non ci sono tipologie disponibili. </p>
            <?php endif; ?>

            <script>
                const antipasto1 = document.getElementById("antipasto");
                const primo1 = document.getElementById("primo");
                const secondo1 = document.getElementById("secondo");
                const dolce1 = document.getElementById("dolce");
                const bevanda1 = document.getElementById("bevanda");

                antipasto1.addEventListener("click", () =>
                {
                    window.location.href = "menuAntipasti.php";
                });
                primo1.addEventListener("click", () =>
                {
                    window.location.href = "menuPrimi.php";
                });
                secondo1.addEventListener("click", () =>
                {
                    window.location.href = "menuSecondi.php";
                });
                dolce1.addEventListener("click", () =>
                {
                    window.location.href = "menuDolci.php";
                });
                bevanda1.addEventListener("click", () =>
                {
                    window.location.href = "menuBevande.php";
                });
            </script>

            <form method="POST" action="aggiuntaComandaTipologia.php">
                <label for="nota">Aggiungi una nota alla comanda:</label>
                <textarea id="nota" name="nota" rows="4" cols="50" placeholder="Scrivi una nota qui..."></textarea>
                <button type="submit" class="pulsanteFineComanda">FINE COMANDA</button>
            </form>

            <a href = "comande.php" class = "pulsanteRitorno" > Torna Indietro </a>
        </div>
    </body>

    <?php 
    $conn->close(); 
    ?>

</html>
