<!-- Battistella Mattia 5IA - OrderFlow by BEM -->

<?php
include "database.php";
include "controlloLogin.php";

//-------------------------------------

//recupero i dati dal form di "aggiuntaComandeTipologia.php"
$recap = isset($_POST['recap']) ? $_POST['recap'] : "";
$nota = isset($_POST['nota']) ? $_POST['nota'] : "";
$numero_uscita = isset($_POST['numero_uscita']) ? intval($_POST['numero_uscita']) : 1;
$numero_tavolo = isset($_POST['numero_tavolo']) ? intval($_POST['numero_tavolo']) : 0;


//il recap è per esempio: "Ravioli cinesi al vapore - 2"
$linee = explode("\n", $recap);
$totalePiatti = 0;  //totale delle quantità (da usare per Numero_Coperti)
$piatti = array(); //array per tenere i dettagli di ciascun piatto

foreach ($linee as $linea) 
{
    $linea = trim($linea);

    //esplodo la riga nel formato "NomePiatto - Quantità"
    $parti = explode(" - ", $linea);
    if (count($parti) == 2) 
    {
        $nomePiatto = trim($parti[0]);
        $quantita = intval(trim($parti[1]));
        $totalePiatti += $quantita;
        $piatti[] = array("nomePiatto" => $nomePiatto, "quantita" => $quantita);
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

//inserimento nei dettagli della comanda
foreach($piatti as $piatto) 
{
    $nomePiatto = $piatto['nomePiatto'];
    $quantita = $piatto['quantita'];
    
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
        
        //inserisco i dettagli nella tabella "dettaglio_comanda"
        $sqlInsertDettaglio = "INSERT INTO dettaglio_comanda (Nota, Stato, Costo, Prezzo, Quantita, Numero_Uscita, ID_Piatto, ID_Comanda)
                               VALUES ('" . $conn->real_escape_string($nota) . "', 1, $costoTotale, $prezzoTotale, $quantita, $numero_uscita, $idPiatto, $orderID)";
        if(!$conn->query($sqlInsertDettaglio)) 
        {
            die("Errore nell'inserimento del dettaglio della comanda: " . $conn->error);
        }
    } 
    else 
    {
        //se il piatto non viene trovato
        die("Errore: piatto '$nomePiatto' non trovato nel database.");
    }
}

header("Location: comande.php?id=" . $orderID);
$conn->close();
?>
