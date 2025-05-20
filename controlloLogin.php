<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); //avvio una sessione solo se non è già attiva
}
$timeout = 900; //imposto il timer a 15 min

if (!isset($_SESSION['login']) || $_SESSION['login'] !== "OK") //se l'utente non è autenticato
{
    header("Location: login.php"); 
    exit();
}

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) //se l'utente è inattivo da più di 15 min
{
    session_unset(); //rimozione variabili delle sessioni
    session_destroy(); //distruzione dati associati alla sessione corrente
    header("Location: login.php"); //reindirizzamento alla pagina di login
    exit(); //fine dello script
}

$_SESSION['last_activity'] = time(); //aggiorno il tempo per mantenere la sessione attiva
?>