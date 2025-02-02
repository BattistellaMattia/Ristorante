<!-- Battistella Mattia 5IA - OrderFlow by BEM -->

<?php
include "database.php";
session_start(); //avvio una nuova sessione

$timeout = 900; //importo un timer limite di 15 minuti

if ($_SERVER["REQUEST_METHOD"] == "POST") //controllo se il form è stato inviato in metodo POST
{
    //recupero dei valori dal POST
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM cameriere WHERE Username = ? AND Password = ?"; //query per cercare un utente nella tabella con i valori forniti
    $query = $conn->prepare($sql);
    $query->bind_param("ss", $username, $password); //associo i parametri alla query, s = stringa
    $query->execute(); //eseguo la query
    $result = $query->get_result(); //ottengo il risultato della query
    
    //se trovo un risultato nel database
    if ($result->num_rows == 1) 
    {
        //ricavo la riga in base al risultato ricavato
        $row = $result->fetch_assoc();
        
        //mi salvo i valori della sessione
        $_SESSION['login'] = "OK"; 
        $_SESSION['nome'] = $row['Nome'];
        $_SESSION['username'] = $row['Username'];
        $_SESSION['last_activity'] = time(); 

        //dato che il login ha avuto successo possono passare alla prossima pagina
        header("Location: comande.php");
        exit();
    } 
    else //sennò è un errore
    {
        $errore = "Credenziali errate!";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">    
    </head>

    <body>
    
    <div class = "login-container">
        <h1 class = "login-titolo"> Benvenuto</h1>
        
        <form action = "login.php" method = "post" class = "login-form">
            <label for = "username" class = "login-label"> Username </label>
            <input type = "text" id="username" name="username" class = "login-input" required>
            
            <label for = "password" class="login-label"> Password </label>
            <input type = "password" id = "password" name = "password" class = "login-input" required>
            
            <button type="submit" class = "login-pulsante"> ACCEDI </button>
        </form>

        <!-- se la variabile $errore è stata dichiarata -->
        <?php if (isset($errore)) echo "<p style='color: red;'>$errore</p>"; ?>

    </div>
    
    </body>
</html>
