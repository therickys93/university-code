-- phpMyAdmin SQL Dump
-- version 4.4.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Feb 16, 2016 alle 11:39
-- Versione del server: 5.6.24
-- Versione PHP: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `matchup`
--
CREATE DATABASE IF NOT EXISTS `matchup` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `matchup`;

DELIMITER $$
--
-- Procedure
--
DROP PROCEDURE IF EXISTS `aggiorna_posizione`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `aggiorna_posizione`(IN `torneo` INT(11))
    NO SQL
BEGIN
DECLARE position INT(11) DEFAULT 0;
DECLARE users VARCHAR(225) DEFAULT "";
DECLARE finito INT(11) DEFAULT 0;

DECLARE cursore CURSOR FOR SELECT @s:=@s+1 as posizione, utente FROM Iscrizione, (SELECT @s:= 0) AS s WHERE torneo_id = torneo AND approvato = 1 ORDER BY punteggio DESC;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finito = 1;
OPEN cursore;

mio_loop: LOOP

FETCH cursore INTO position, users;

IF finito = 1 THEN
LEAVE mio_loop;
END IF;

UPDATE Iscrizione SET posizione=position WHERE utente=users AND torneo_id=torneo;

END LOOP mio_loop;

CLOSE cursore;

END$$

DROP PROCEDURE IF EXISTS `crea_prossime_partite`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `crea_prossime_partite`(IN `torneo_id` INT(11), IN `turno` INT(11))
    NO SQL
BEGIN

DECLARE prima VARCHAR(255) DEFAULT "";
DECLARE seconda VARCHAR(255) DEFAULT "";
DECLARE finito INT(11) DEFAULT 0;
DECLARE risultato INT(11) DEFAULT 0;
DECLARE partite INT(11) DEFAULT 0;
DECLARE numero_squadre INT(11) DEFAULT 0;
DECLARE numero_partite INT(11) DEFAULT 0;

DECLARE cursore CURSOR FOR SELECT s.vincitore AS casa, t.vincitore AS trasferta FROM Gara AS s, Gara AS t WHERE s.vincitore != t.vincitore AND s.torneo = torneo_id AND t.torneo = torneo_id AND s.giornata = turno AND t.giornata = turno ORDER BY RAND();
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finito = 1;
SET numero_squadre = (SELECT COUNT(*) FROM Gara WHERE torneo=torneo_id AND giornata=turno AND vincitore IS NOT NULL);
SET numero_partite = (numero_squadre * numero_squadre) - numero_squadre;
OPEN cursore;

mio_loop: LOOP

FETCH cursore INTO prima, seconda;

IF finito = 1 THEN
LEAVE mio_loop;
END IF;

SET partite = 0;

ciclo: WHILE (partite < numero_partite) DO
SET partite = partite + 1;

CALL inserisci_valori(prima, seconda, turno+1, torneo_id,risultato);

IF risultato = 1 THEN
LEAVE ciclo;
END IF;

END WHILE;

END LOOP mio_loop;

CLOSE cursore;

END$$

DROP PROCEDURE IF EXISTS `crea_torneo_eliminazione_diretta`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `crea_torneo_eliminazione_diretta`(IN `torneo` INT(11))
    NO SQL
BEGIN

DECLARE prima VARCHAR(255) DEFAULT "";
DECLARE seconda VARCHAR(255) DEFAULT "";
DECLARE finito INT(11) DEFAULT 0;
DECLARE turno INT(11) DEFAULT 0;
DECLARE risultato INT(11) DEFAULT 0;
DECLARE numero_squadre INT(11) DEFAULT 0;
DECLARE numero_partite INT(11) DEFAULT 0;

DECLARE cursore CURSOR FOR SELECT s.utente AS casa, t.utente AS trasferta FROM Iscrizione AS s, Iscrizione AS t WHERE s.utente != t.utente AND s.approvato = 1 AND t.approvato = 1 AND s.torneo_id = torneo AND t.torneo_id = torneo ORDER BY RAND();
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finito = 1;
SET numero_squadre = (SELECT COUNT(*) FROM Iscrizione WHERE approvato = 1 AND torneo_id = torneo);
SET numero_partite = (numero_squadre * numero_squadre) - numero_squadre;
OPEN cursore;

mio_loop: LOOP

FETCH cursore INTO prima, seconda;

IF finito = 1 THEN
LEAVE mio_loop;
END IF;

SET turno = 0;

ciclo: WHILE (turno < numero_partite) DO
SET turno = turno + 1;

CALL inserisci_valori(prima, seconda, 1, torneo,risultato);

IF risultato = 1 THEN
LEAVE ciclo;
END IF;

END WHILE;

END LOOP mio_loop;


CLOSE cursore;


END$$

DROP PROCEDURE IF EXISTS `crea_torneo_italiana`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `crea_torneo_italiana`(IN `torneo` INT(11))
    NO SQL
BEGIN

DECLARE prima VARCHAR(255) DEFAULT "";
DECLARE seconda VARCHAR(255) DEFAULT "";
DECLARE finito INT(11) DEFAULT 0;
DECLARE turno INT(11) DEFAULT 0;
DECLARE risultato INT(11) DEFAULT 0;
DECLARE numero_squadre INT(11) DEFAULT 0;
DECLARE numero_partite INT(11) DEFAULT 0;

DECLARE cursore CURSOR FOR SELECT s.utente AS casa, t.utente AS trasferta FROM Iscrizione AS s, Iscrizione AS t WHERE s.utente != t.utente AND s.approvato = 1 AND t.approvato = 1 AND s.torneo_id = torneo AND t.torneo_id = torneo ORDER BY RAND();
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finito = 1;
SET numero_squadre = (SELECT COUNT(*) FROM Iscrizione WHERE approvato = 1 AND torneo_id = torneo);
SET numero_partite = (numero_squadre * numero_squadre) - numero_squadre;
OPEN cursore;

mio_loop: LOOP

FETCH cursore INTO prima, seconda;

IF finito = 1 THEN
LEAVE mio_loop;
END IF;

SET turno = 0;

ciclo: WHILE (turno < numero_partite) DO
SET turno = turno + 1;

CALL inserisci_valori(prima, seconda, turno, torneo,risultato);

IF risultato = 1 THEN
LEAVE ciclo;
END IF;

END WHILE;

END LOOP mio_loop;

CLOSE cursore;

END$$

DROP PROCEDURE IF EXISTS `crea_torneo_misto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `crea_torneo_misto`(IN `torneo` INT)
    NO SQL
BEGIN

CALL crea_torneo_italiana(torneo);
CALL crea_torneo_eliminazione_diretta(torneo);

END$$

DROP PROCEDURE IF EXISTS `inserisci_risultato`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `inserisci_risultato`(IN `gara_id` INT(11), IN `risultato_casa` INT(11), IN `risultato_trasferta` INT(11))
    NO SQL
BEGIN
DECLARE winner VARCHAR(255) DEFAULT "";
DECLARE result VARCHAR(255) DEFAULT NULL;
DECLARE tournament INT(11) DEFAULT 0;

SET tournament = (SELECT torneo FROM Gara WHERE ID=gara_id);

SET result = (SELECT CONCAT(risultato_casa, '-'));
SET result = (SELECT CONCAT(result, risultato_trasferta));

UPDATE Gara SET risultato=result WHERE ID=gara_id;
IF risultato_casa > risultato_trasferta THEN
	SET winner = (SELECT casa FROM Gara WHERE ID=gara_id);
    UPDATE Gara SET vincitore = winner WHERE ID = gara_id;
    UPDATE Iscrizione SET punteggio=punteggio+3 WHERE torneo_id=tournament AND utente=winner;
ELSEIF risultato_trasferta > risultato_casa THEN
	SET winner = (SELECT trasferta FROM Gara WHERE ID=gara_id);
    UPDATE Gara SET vincitore = winner WHERE ID = gara_id;
    UPDATE Iscrizione SET punteggio=punteggio+3 WHERE torneo_id=tournament AND utente=winner;
ELSE
	SET winner = (SELECT casa FROM Gara WHERE ID=gara_id);
	UPDATE Iscrizione SET punteggio=punteggio+1 WHERE torneo_id=tournament AND utente=winner;
    SET winner = (SELECT trasferta FROM Gara WHERE ID=gara_id);
	UPDATE Iscrizione SET punteggio=punteggio+1 WHERE torneo_id=tournament AND utente=winner;
END IF;

CALL aggiorna_posizione(tournament);

END$$

DROP PROCEDURE IF EXISTS `inserisci_valori`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `inserisci_valori`(IN `prima` VARCHAR(255), IN `seconda` VARCHAR(255), IN `turno` INT(11), IN `torneo_id` INT(11), OUT `risultato` INT(11))
    NO SQL
BEGIN

IF NOT EXISTS (SELECT casa FROM Gara WHERE giornata=turno AND (casa=prima OR casa=seconda OR trasferta=prima OR trasferta=seconda) AND torneo=torneo_id) THEN
INSERT INTO Gara (casa, trasferta, giornata, torneo) VALUES (prima, seconda, turno, torneo_id);
SET risultato = 1;
ELSE 
SET risultato = 0;
END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `Gara`
--

DROP TABLE IF EXISTS `Gara`;
CREATE TABLE IF NOT EXISTS `Gara` (
  `ID` int(11) NOT NULL,
  `casa` varchar(255) NOT NULL,
  `trasferta` varchar(255) NOT NULL,
  `giornata` int(11) NOT NULL,
  `torneo` int(11) NOT NULL,
  `risultato` varchar(255) DEFAULT NULL,
  `vincitore` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=749 DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `Gara`:
--   `casa`
--       `Utenti` -> `username`
--   `trasferta`
--       `Utenti` -> `username`
--   `torneo`
--       `Torneo` -> `ID`
--   `vincitore`
--       `Utenti` -> `username`
--

--
-- Svuota la tabella prima dell'inserimento `Gara`
--

TRUNCATE TABLE `Gara`;
--
-- Dump dei dati per la tabella `Gara`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Gara_torneo_libero`
--

DROP TABLE IF EXISTS `Gara_torneo_libero`;
CREATE TABLE IF NOT EXISTS `Gara_torneo_libero` (
  `ID` int(11) NOT NULL,
  `torneo` int(11) NOT NULL,
  `giornata` int(11) NOT NULL,
  `utente` varchar(255) NOT NULL,
  `risultato` varchar(255) DEFAULT NULL,
  `punti` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `Gara_torneo_libero`:
--   `torneo`
--       `Torneo` -> `ID`
--   `utente`
--       `Utenti` -> `username`
--

--
-- Svuota la tabella prima dell'inserimento `Gara_torneo_libero`
--

TRUNCATE TABLE `Gara_torneo_libero`;
--
-- Dump dei dati per la tabella `Gara_torneo_libero`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Iscrizione`
--

DROP TABLE IF EXISTS `Iscrizione`;
CREATE TABLE IF NOT EXISTS `Iscrizione` (
  `utente` varchar(100) NOT NULL,
  `torneo_id` int(11) NOT NULL,
  `punteggio` int(11) NOT NULL DEFAULT '0',
  `posizione` int(11) NOT NULL DEFAULT '0',
  `prezzo` decimal(5,2) NOT NULL DEFAULT '0.00',
  `premio` decimal(8,2) NOT NULL DEFAULT '0.00',
  `approvato` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `Iscrizione`:
--   `utente`
--       `Utenti` -> `username`
--   `torneo_id`
--       `Torneo` -> `ID`
--

--
-- Svuota la tabella prima dell'inserimento `Iscrizione`
--

TRUNCATE TABLE `Iscrizione`;
--
-- Dump dei dati per la tabella `Iscrizione`
--

--
-- Trigger `Iscrizione`
--
DROP TRIGGER IF EXISTS `aggiorna_valori`;
DELIMITER $$
CREATE TRIGGER `aggiorna_valori` AFTER UPDATE ON `iscrizione`
 FOR EACH ROW IF OLD.prezzo != NEW.prezzo THEN
	UPDATE Utenti SET credito = credito - NEW.prezzo WHERE username=NEW.utente;
ELSEIF OLD.premio != NEW.premio THEN
	UPDATE Utenti SET credito = credito + NEW.premio WHERE username=NEW.utente;
END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `Ruolo`
--

DROP TABLE IF EXISTS `Ruolo`;
CREATE TABLE IF NOT EXISTS `Ruolo` (
  `ruolo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `Ruolo`:
--

--
-- Svuota la tabella prima dell'inserimento `Ruolo`
--

TRUNCATE TABLE `Ruolo`;
--
-- Dump dei dati per la tabella `Ruolo`
--

INSERT INTO `Ruolo` (`ruolo`) VALUES
('amministratore'),
('giocatore'),
('organizzatore');

-- --------------------------------------------------------

--
-- Struttura della tabella `Tipo`
--

DROP TABLE IF EXISTS `Tipo`;
CREATE TABLE IF NOT EXISTS `Tipo` (
  `tipo` varchar(255) NOT NULL,
  `descrizione` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `Tipo`:
--

--
-- Svuota la tabella prima dell'inserimento `Tipo`
--

TRUNCATE TABLE `Tipo`;
--
-- Dump dei dati per la tabella `Tipo`
--

INSERT INTO `Tipo` (`tipo`, `descrizione`) VALUES
('eliminazione diretta', 'ogni giocatore/squadra gioca contro una squadra e se vince passa al turno successivo altrimenti viene eliminata'),
('italiana', 'ogni giocatore/squadra gioca contro tutte le altre. Vince chi guadagna più punti'),
('libero', 'l''organizzatore è libero di scegliere che cosa fare. Scegliendo manualmente le gare.'),
('misto', 'Viene affrontata una prima fase a gironi con il metodo del torneo all''italiana. Una volta finiti i gironi le squadre vincitrici passano nella fase ad eliminazione diretta');

-- --------------------------------------------------------

--
-- Struttura della tabella `Torneo`
--

DROP TABLE IF EXISTS `Torneo`;
CREATE TABLE IF NOT EXISTS `Torneo` (
  `ID` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `edizione` int(11) NOT NULL DEFAULT '1',
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `min_giocatori` int(11) NOT NULL DEFAULT '2',
  `max_giocatori` int(11) NOT NULL,
  `numero_gare` int(11) DEFAULT NULL,
  `tipo` varchar(255) NOT NULL,
  `organizzatore` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `Torneo`:
--   `organizzatore`
--       `Utenti` -> `username`
--   `tipo`
--       `Tipo` -> `tipo`
--

--
-- Svuota la tabella prima dell'inserimento `Torneo`
--

TRUNCATE TABLE `Torneo`;
--
-- Dump dei dati per la tabella `Torneo`
--

--
-- Trigger `Torneo`
--
DROP TRIGGER IF EXISTS `aggiorna_edizione`;
DELIMITER $$
CREATE TRIGGER `aggiorna_edizione` BEFORE INSERT ON `torneo`
 FOR EACH ROW BEGIN
DECLARE prima INT(11) DEFAULT 1;
SET prima = (SELECT MAX(edizione) + 1 FROM Torneo WHERE nome=NEW.nome);
IF (prima IS NULL) THEN
	SET NEW.edizione = 1;
ELSE 
	SET NEW.edizione = (SELECT MAX(edizione) + 1 FROM Torneo WHERE nome=NEW.nome);
END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `ins_org_in_iscr`;
DELIMITER $$
CREATE TRIGGER `ins_org_in_iscr` AFTER INSERT ON `torneo`
 FOR EACH ROW INSERT INTO Iscrizione (utente, torneo_id) VALUES (NEW.organizzatore, NEW.ID)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `Utenti`
--

DROP TABLE IF EXISTS `Utenti`;
CREATE TABLE IF NOT EXISTS `Utenti` (
  `ID` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `cognome` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `ruolo` varchar(255) NOT NULL,
  `abilitato` tinyint(1) NOT NULL,
  `credito` int(11) NOT NULL DEFAULT '100'
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `Utenti`:
--   `ruolo`
--       `Ruolo` -> `ruolo`
--

--
-- Svuota la tabella prima dell'inserimento `Utenti`
--

TRUNCATE TABLE `Utenti`;
--
-- Dump dei dati per la tabella `Utenti`
--

INSERT INTO `Utenti` (`ID`, `nome`, `cognome`, `email`, `username`, `password`, `ruolo`, `abilitato`, `credito`) VALUES
(2, NULL, NULL, 'mia@email.it', 'therickys93', '5f4dcc3b5aa765d61d8327deb882cf99', 'amministratore', 1, 100);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Gara`
--
ALTER TABLE `Gara`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `casa` (`casa`),
  ADD KEY `trasferta` (`trasferta`),
  ADD KEY `torneo` (`torneo`),
  ADD KEY `vincitore` (`vincitore`);

--
-- Indici per le tabelle `Gara_torneo_libero`
--
ALTER TABLE `Gara_torneo_libero`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `torneo` (`torneo`),
  ADD KEY `utente` (`utente`);

--
-- Indici per le tabelle `Iscrizione`
--
ALTER TABLE `Iscrizione`
  ADD PRIMARY KEY (`utente`,`torneo_id`),
  ADD KEY `torneo_id` (`torneo_id`),
  ADD KEY `utente` (`utente`),
  ADD KEY `torneo_id_2` (`torneo_id`);

--
-- Indici per le tabelle `Ruolo`
--
ALTER TABLE `Ruolo`
  ADD PRIMARY KEY (`ruolo`);

--
-- Indici per le tabelle `Tipo`
--
ALTER TABLE `Tipo`
  ADD PRIMARY KEY (`tipo`);

--
-- Indici per le tabelle `Torneo`
--
ALTER TABLE `Torneo`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `organizzatore` (`organizzatore`),
  ADD KEY `tipo` (`tipo`);

--
-- Indici per le tabelle `Utenti`
--
ALTER TABLE `Utenti`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `ruolo` (`ruolo`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Gara`
--
ALTER TABLE `Gara`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=749;
--
-- AUTO_INCREMENT per la tabella `Gara_torneo_libero`
--
ALTER TABLE `Gara_torneo_libero`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT per la tabella `Torneo`
--
ALTER TABLE `Torneo`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT per la tabella `Utenti`
--
ALTER TABLE `Utenti`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Gara`
--
ALTER TABLE `Gara`
  ADD CONSTRAINT `Gara_ibfk_1` FOREIGN KEY (`casa`) REFERENCES `Utenti` (`username`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `Gara_ibfk_2` FOREIGN KEY (`trasferta`) REFERENCES `Utenti` (`username`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `Gara_ibfk_3` FOREIGN KEY (`torneo`) REFERENCES `Torneo` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `Gara_ibfk_4` FOREIGN KEY (`vincitore`) REFERENCES `Utenti` (`username`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Limiti per la tabella `Gara_torneo_libero`
--
ALTER TABLE `Gara_torneo_libero`
  ADD CONSTRAINT `Gara_torneo_libero_ibfk_1` FOREIGN KEY (`torneo`) REFERENCES `Torneo` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `Gara_torneo_libero_ibfk_2` FOREIGN KEY (`utente`) REFERENCES `Utenti` (`username`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Limiti per la tabella `Iscrizione`
--
ALTER TABLE `Iscrizione`
  ADD CONSTRAINT `Iscrizione_ibfk_1` FOREIGN KEY (`utente`) REFERENCES `Utenti` (`username`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `Iscrizione_ibfk_2` FOREIGN KEY (`torneo_id`) REFERENCES `Torneo` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Limiti per la tabella `Torneo`
--
ALTER TABLE `Torneo`
  ADD CONSTRAINT `Torneo_ibfk_1` FOREIGN KEY (`organizzatore`) REFERENCES `Utenti` (`username`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `Torneo_ibfk_2` FOREIGN KEY (`tipo`) REFERENCES `Tipo` (`tipo`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Limiti per la tabella `Utenti`
--
ALTER TABLE `Utenti`
  ADD CONSTRAINT `Utenti_ibfk_1` FOREIGN KEY (`ruolo`) REFERENCES `Ruolo` (`ruolo`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
