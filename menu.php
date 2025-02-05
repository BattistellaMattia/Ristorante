<!-- Battistella Mattia 5IA - OrderFlow by BEM -->

<?php
include "database.php";
include "controlloLogin.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>MENU</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script>

        //funzione per aggiornare il valore del contatore
        function aggiornaContatore(button, incremento) 
        {
            const elementoContatore = button.parentElement.querySelector('.contatore');
            let valoreAttuale = parseInt(elementoContatore.textContent, 10);
            if (incremento) 
            {
                valoreAttuale++;
            } 
            else 
            {
                if (valoreAttuale > 0) 
                {
                    valoreAttuale--;
                }
            }
            elementoContatore.textContent = valoreAttuale;
        }
        
    </script>
</head>

<body>
    <div class="menu-container">


        <?php //inserimento del titolo <h1> con il nome della tipologia

        $id_tipologia = $_POST['ID_Tipologia'];

        $sql2 = "SELECT Descrizione_Tipologia
                FROM tipologia_piatto
                WHERE ID_Tipologia = $id_tipologia";
        
        $result2 = $conn->query($sql2);

        while ($row = $result2->fetch_assoc()) 
        {
           echo "<h1>Menu " . $row['Descrizione_Tipologia'] . "</h1>";
        }
        ?>

        <button class = "creazioneComanda">AGGIUNGI ALLA COMANDA</button>
        <a href="aggiuntaComandeTipologia.php" class="pulsanteRitorno">Torna Indietro</a>


        <div class="menu-grid">
            <?php //inserimento dei piatti di una determinata tipologia
            $sql = "SELECT Descrizione_Piatto, Prezzo 
                    FROM piatto 
                    WHERE ID_Tipologia = $id_tipologia AND ATTIVO = 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) 
            {
                while ($row = $result->fetch_assoc()) 
                {
                    echo "<div class='menu-item'>";
                    echo "<h2>" . htmlspecialchars($row['Descrizione_Piatto']) . "</h2>";

                    echo "<div class='container-contatore'>";
                    echo "<button class='decremento' onclick='aggiornaContatore(this, false)' style='background-color: red; color: white;'>-</button>";
                    echo "<span class='contatore'>0</span>";
                    echo "<button class='incremento' onclick='aggiornaContatore(this, true)' style='background-color: green; color: white;'>+</button>";
                    echo "</div>";

                    echo "<p>€ " . number_format($row['Prezzo'], 2) . "</p>";
                    echo "</div>";
                }
            } 
            else //se non sono presenti piatti con questo ID
            {
                echo "<p>Nessun piatto disponibile.</p>";
            }
            ?>

        </div>
    </div>
</body>

    <?php
    $conn -> close();
    ?>
    
</html>