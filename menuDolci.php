<?php
include "database.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>DOLCI</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script>

        //funzione per aggiornare il valore del contatore
        function updateCounter(button, increment) 
        {
            const counterElement = button.parentElement.querySelector('.counter');
            let currentValue = parseInt(counterElement.textContent, 10);
            if (increment) 
            {
                currentValue++;
            } else 
            {
                if (currentValue > 0) 
                {
                    currentValue--;
                }
            }
            counterElement.textContent = currentValue;
        }
        
    </script>
</head>

<body>
    <div class="menu-container">
        <h1>Menu Dolci</h1>
        <div class="menu-grid">
            <?php
            $sql = "SELECT Descrizione_Piatto, Prezzo 
                    FROM piatto 
                    WHERE ID_Tipologia = 4 AND ATTIVO = 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) 
            {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='menu-item'>";
                    echo "<h2>" . htmlspecialchars($row['Descrizione_Piatto']) . "</h2>";

                    echo "<div class='counter-container'>";
                    echo "<button class='decrement' onclick='updateCounter(this, false)' style='background-color: red; color: white;'>-</button>";
                    echo "<span class='counter'>0</span>";
                    echo "<button class='increment' onclick='updateCounter(this, true)' style='background-color: green; color: white;'>+</button>";
                    echo "</div>";

                    echo "<p>€ " . number_format($row['Prezzo'], 2) . "</p>";
                    echo "</div>";
                }
            } else 
            {
                echo "<p>Nessun piatto disponibile.</p>";
            }
            ?>

        </div>
        <button class = "creazioneComanda">AGGIUNGI ALLA COMANDA</button>
        <a href="aggiuntaComandeTipologia.php" class="pulsanteRitorno">Torna Indietro</a>

    </div>
</body>
</html>