-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 02, 2025 alle 20:23
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `esercizio_comande`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `cameriere`
--

CREATE TABLE `cameriere` (
  `CODICE_Cameriere` int(11) NOT NULL,
  `Nome` varchar(20) NOT NULL,
  `Cognome` varchar(30) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `PassID` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `cameriere`
--

INSERT INTO `cameriere` (`CODICE_Cameriere`, `Nome`, `Cognome`, `Username`, `Password`, `PassID`) VALUES
(1, 'Mattia', 'Battistella', 'Batti', 'Mattia06', 'cad8e1e309aa3d54a9c319aa7b17528f9b8ffb62adbdbad910b2f35ef6587c7a'),
(2, 'Diego', 'Moras', 'Dmo', 'Diego06', 'aca7371420813912e81d98e72edd91fa5783243df53d725c0da43335aabd1c48'),
(3, 'Eduard', 'Giugiuc', 'Pepino', 'Eduard06', '3e76a1f9f23e50f579f991783359d6c252b6ef5585ea3bdec3b5289f42fd82f3');

-- --------------------------------------------------------

--
-- Struttura della tabella `comanda`
--

CREATE TABLE `comanda` (
  `ID_Comanda` int(11) NOT NULL,
  `Numero_Tavolo` int(11) NOT NULL,
  `Ora` time NOT NULL,
  `Data` date NOT NULL,
  `Stato` tinyint(1) NOT NULL,
  `Numero_Coperti` int(11) NOT NULL,
  `CODICE_Cameriere` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `comanda`
--

INSERT INTO `comanda` (`ID_Comanda`, `Numero_Tavolo`, `Ora`, `Data`, `Stato`, `Numero_Coperti`, `CODICE_Cameriere`) VALUES
(1, 1, '20:03:00', '2024-05-25', 1, 3, 1),
(2, 4, '18:36:23', '2024-12-04', 0, 7, 1),
(5, 5, '20:13:08', '2025-01-19', 1, 5, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `dettaglio_comanda`
--

CREATE TABLE `dettaglio_comanda` (
  `ID_Dettaglio` int(11) NOT NULL,
  `Nota` varchar(400) NOT NULL,
  `Stato` tinyint(1) NOT NULL,
  `Costo` float NOT NULL,
  `Prezzo` float NOT NULL,
  `Quantita` int(11) NOT NULL,
  `Numero_Uscita` int(11) NOT NULL,
  `ID_Piatto` int(11) NOT NULL,
  `ID_Comanda` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `dettaglio_comanda`
--

INSERT INTO `dettaglio_comanda` (`ID_Dettaglio`, `Nota`, `Stato`, `Costo`, `Prezzo`, `Quantita`, `Numero_Uscita`, `ID_Piatto`, `ID_Comanda`) VALUES
(1, 'La milanese deve essere senza patatine fritte.', 1, 4.56, 12, 1, 1, 7, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `piatto`
--

CREATE TABLE `piatto` (
  `ID_Piatto` int(11) NOT NULL,
  `Descrizione_Piatto` varchar(100) NOT NULL,
  `Descrizione_Ingredienti` tinytext NOT NULL,
  `ATTIVO` tinyint(1) NOT NULL,
  `Costo` float NOT NULL,
  `Prezzo` float NOT NULL,
  `ID_Tipologia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `piatto`
--

INSERT INTO `piatto` (`ID_Piatto`, `Descrizione_Piatto`, `Descrizione_Ingredienti`, `ATTIVO`, `Costo`, `Prezzo`, `ID_Tipologia`) VALUES
(1, 'Ravioli cinesi al vapore', 'Farina 00, sale fino, maiale, cipollotto fresco, cavolo cappuccio, vino di riso, salsa di soia, pepe bianco.', 1, 2, 5, 1),
(2, 'Polpette di tonno e ricotta', 'Tonno sott\'olio, acciughe, pangrattato, uova, sale fino, ricotta vaccina, capperi sotto sale, prezzemolo, parmigiano reggiano DOP, pepe nero, olio di semi di arachide.', 1, 2, 5, 1),
(3, 'Acqua 1/2 L', 'Acqua', 1, 0.3, 1, 5),
(4, 'Bibita analcolica bottiglia 1/2 L', 'Bibita a scelta tra le disponibili', 1, 0.5, 2, 5),
(5, 'Tiramisù', 'Mascarpone, uova, zucchero, savoiardi, caffè', 1, 1.5, 4, 4),
(6, 'Pasticcio al ragù', 'Latte, farina, burro, sale, carne, pomodoro, vino rosso, olio, cipolla, carota, pepe, sedano, sfoglie', 1, 3.23, 9, 2),
(7, 'Milanese con patate fritte', 'Carne, patate, uova, sale, farina, pangrattato, burro', 1, 4.56, 12, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `tipologia_piatto`
--

CREATE TABLE `tipologia_piatto` (
  `ID_Tipologia` int(11) NOT NULL,
  `Descrizione_Tipologia` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `tipologia_piatto`
--

INSERT INTO `tipologia_piatto` (`ID_Tipologia`, `Descrizione_Tipologia`) VALUES
(1, 'Antipasti'),
(2, 'Primi'),
(3, 'Secondi'),
(4, 'Dolce'),
(5, 'Bevande');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `cameriere`
--
ALTER TABLE `cameriere`
  ADD PRIMARY KEY (`CODICE_Cameriere`);

--
-- Indici per le tabelle `comanda`
--
ALTER TABLE `comanda`
  ADD PRIMARY KEY (`ID_Comanda`),
  ADD KEY `CODICE_Cameriere` (`CODICE_Cameriere`);

--
-- Indici per le tabelle `dettaglio_comanda`
--
ALTER TABLE `dettaglio_comanda`
  ADD PRIMARY KEY (`ID_Dettaglio`),
  ADD KEY `ID_Piatto` (`ID_Piatto`),
  ADD KEY `ID_Comanda` (`ID_Comanda`);

--
-- Indici per le tabelle `piatto`
--
ALTER TABLE `piatto`
  ADD PRIMARY KEY (`ID_Piatto`),
  ADD KEY `ID_Tipologia` (`ID_Tipologia`);

--
-- Indici per le tabelle `tipologia_piatto`
--
ALTER TABLE `tipologia_piatto`
  ADD PRIMARY KEY (`ID_Tipologia`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `cameriere`
--
ALTER TABLE `cameriere`
  MODIFY `CODICE_Cameriere` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `comanda`
--
ALTER TABLE `comanda`
  MODIFY `ID_Comanda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `dettaglio_comanda`
--
ALTER TABLE `dettaglio_comanda`
  MODIFY `ID_Dettaglio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `piatto`
--
ALTER TABLE `piatto`
  MODIFY `ID_Piatto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `tipologia_piatto`
--
ALTER TABLE `tipologia_piatto`
  MODIFY `ID_Tipologia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
