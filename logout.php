<?php
session_start(); //richiesto per accedere alla sessione attuale
session_unset(); //rimozione variabili delle sessioni
session_destroy(); //distruzione dati associati alla sessione corrente
header("Location: login.php"); //reindirizzamento alla pagina di login
exit();
?>
