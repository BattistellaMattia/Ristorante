<!-- Battistella Mattia 5IA - OrderFlow by BEM -->

<?php
include "database.php";
include "controlloLogin.php";

//-------------------------------------

//recupero i dati dal form di "aggiuntaComandeTipologia.php"
$recap = isset($_POST['recap']) ? $_POST['recap'] : "";
$nota = isset($_POST['nota']) ? $_POST['nota'] : "";
$numero_tavolo = isset($_POST['numero_tavolo']) ? intval($_POST['numero_tavolo']) : 0;

// Parsing del recap per estrarre piatti, quantità e numeri di uscita
$linee = explode("\n", trim($recap));
$piatti = array();
$totalePiatti = 0;

foreach ($linee as $linea) 
{
    $linea = trim($linea);
    
    // Controlla se la linea contiene il numero di uscita
    if (preg_match('/^(\d+)\s*x\s*(.+?)\s*\(Uscita:\s*(\d+)\)$/', $linea, $matches)) {
        $quantita = intval($matches[1]);
        $nomePiatto = trim($matches[2]);
        $numeroUscita = intval($matches[3]);
        $totalePiatti += $quantita;
        $piatti[] = array(
            "nomePiatto" => $nomePiatto, 
            "quantita" => $quantita, 
            "numeroUscita" => $numeroUscita
        );
    }
    // Fallback per il formato vecchio (senza numero di uscita)
    elseif (preg_match('/^(\d+)\s*x\s*(.+)$/', $linea, $matches)) {
        $quantita = intval($matches[1]);
        $nomePiatto = trim($matches[2]);
        $totalePiatti += $quantita;
        $piatti[] = array(
            "nomePiatto" => $nomePiatto, 
            "quantita" => $quantita, 
            "numeroUscita" => 1 // Default a 1 se non specificato
        );
    }
}

//-------------------------------------

if(isset($_SESSION['username'])) 
{
    $username = $_SESSION['username'];
    //recupera il codice del cameriere dalla tabella "cameriere"
    $sql = "SELECT CODICE_Cameriere FROM cameriere WHERE username = '" . $conn->real_escape_string($username) . "'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) 
    {
        $row = $result->fetch_assoc();
        $codiceCameriere = $row['CODICE_Cameriere'];
    } 
    else 
    {
        die("Errore: utente non trovato nel database.");
    }
} 
else 
{
    die("Utente non loggato.");
}

//-------------------------------------

// Verifico se ci sono piatti selezionati
if (empty($piatti)) 
{
    die("Nessun piatto selezionato. Impossibile creare la comanda.");
}

//inserimento nella comanda
$sqlInsertComanda = "INSERT INTO comanda (Numero_Tavolo, Ora, Data, Stato, Numero_Coperti, CODICE_Cameriere)
                     VALUES ($numero_tavolo, CURRENT_TIME(), CURRENT_DATE(), 1, $totalePiatti, $codiceCameriere)";

if(!$conn->query($sqlInsertComanda))
{
    die("Errore nell'inserimento della comanda: " . $conn->error);
}
$orderID = $conn->insert_id; //$orderID viene usata per memorizzare l'id della comanda appena creata

if (!$orderID) //controllo
{
    die("Errore: ID comanda non esiste.");
}

//-------------------------------------

//contatore per verificare quanti dettagli sono stati inseriti
$dettagliInseriti = 0;

//inserimento nei dettagli della comanda
foreach($piatti as $piatto) 
{
    $nomePiatto = $piatto['nomePiatto'];
    $quantita = $piatto['quantita'];
    $numeroUscita = $piatto['numeroUscita'];
    
    // Recupera i dati del piatto dalla tabella "piatto"
    $sqlPiatto = "SELECT ID_Piatto, Costo, Prezzo FROM piatto WHERE Descrizione_Piatto = '" . $conn->real_escape_string($nomePiatto) . "'";
    $resultPiatto = $conn->query($sqlPiatto);
    if ($resultPiatto && $resultPiatto -> num_rows > 0) 
    {
        $rigaPiatto = $resultPiatto->fetch_assoc();
        $idPiatto = $rigaPiatto['ID_Piatto'];
        $costo = $rigaPiatto['Costo'];
        $prezzo = $rigaPiatto['Prezzo'];
        
        //calcolo i totali in base alla quantità scelta
        $costoTotale = $costo * $quantita;
        $prezzoTotale = $prezzo * $quantita;
        
        //inserisco i dettagli nella tabella "dettaglio_comanda" con il numero di uscita specifico
        $sqlInsertDettaglio = "INSERT INTO dettaglio_comanda (Nota, Stato, Costo, Prezzo, Quantita, Numero_Uscita, ID_Piatto, ID_Comanda)
                               VALUES ('" . $conn->real_escape_string($nota) . "', 1, $costoTotale, $prezzoTotale, $quantita, $numeroUscita, $idPiatto, $orderID)";
        if(!$conn->query($sqlInsertDettaglio)) 
        {
            die("Errore nell'inserimento del dettaglio della comanda: " . $conn->error);
        }
        $dettagliInseriti++;
    } 
    else 
    {
        //se il piatto non viene trovato
        die("Errore: piatto '$nomePiatto' non trovato nel database.");
    }
}

//verifico che almeno un dettaglio sia stato inserito
if ($dettagliInseriti === 0) 
{
    //se non ci sono dettagli, elimino la comanda appena creata
    $sqlDeleteComanda = "DELETE FROM comanda WHERE ID_Comanda = $orderID";
    $conn->query($sqlDeleteComanda);
    die("Errore: nessun dettaglio valido inserito. La comanda è stata annullata.");
}

header("Location: comande.php?id=" . $orderID);
$conn->close();
?>