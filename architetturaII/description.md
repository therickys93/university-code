# Descrizione del progetto

## Cosa voglio realizzare

Il mio progetto consiste nel realizzare un database per la gestione del miglior tempo di
ogni atleta e del numero di gare disputate. Per ogni atleta il database salva un ID, che è
l’identificativo dell’atleta all’interno della FIDAL ( Federazione Italiana di Atletica Leggera ), 
il miglior tempo salvato in secondi e centesimi di secondo e il numero di gare
effettuate che viene incrementato ogni volta che viene inserito un nuovo tempo.

## Come deve essere utilizzato 

Appena il programma viene avviato si visualizza un menu con sei scelte che
corrispondono ad un numero ognuno con una funzione diversa. In base al numero
scelto il programma esegue la funzione richiesta. Se l’utente sceglie "0" il programma
termina; se sceglie "1" il programma mostra un messaggio dove l’utente deve inserire
l’ID dell’atleta da salvare all’interno del database; se l’utente vuole inserire un nuovo
tempo deve premere "2": il programma chiede inizialmente a quale atleta si vuole
inserire il nuovo tempo e poi chiede di inserire prima i secondi e poi i centesimi di
secondo; scegliendo "3" l’utente stampa l’elenco degli atleti nell’ordine in cui sono stati
inseriti; scegliendo "4" stampa l’atleta con il miglior tempo ed infine scegliendo "5"
stampa l’elenco degli atleti ordinati in base al miglior tempo.

## Scelte implementative

Quando viene inserito un nuovo atleta l'applicazione inserisce un tempo predefinito di 60 secondi che corrispondono ad 1 minuto. In questo caso il tempo non può essere accettato perchè l'applicazione tiene conto solo dei secondi e dei centesimi di secondo quindi stampa la stringa "No Time", inoltre viene impostato come numero gare 0.

Quando si inserisce un nuovo tempo viene aggiornato prima il numero di gare e poi viene impostato il nuovo tempo solo se inferiore al precedente.

Il tempo all'interno del database viene salvato in centesimi di secondo per evitare di utilizzare numeri in virgola mobile. Per convertire secondi e centesimi di secondo in centesimi di secondo viene fatto questo calcolo: ```( secondi * 100 ) + centesimi di secondo```. Viceversa per trasformare i centesimi di secondo in secondi viene fatto questo calcolo: ```centesimi / 100 = secondi``` e ```centesimi % 100 = centesimi di secondo```.

In memoria l'atleta occupa 32 bit per ID, 32 bit per il tempo e 32 bit per il numero di gare, in modo tale che l'istruzione ```lw $t0, 0(INDIRIZZO_BASE_ATLETA)```carica dalla memoria l'ID, l'istruzione ```lw $t0, 4(INDIRIZZO_BASE_ATLETA)``` carica il tempo ed infine l'istruzione ```lw $t0, 8(INDIRIZZO_BASE_ATLETA)``` carica il numero di gare. L'indirizzo dell'atleta successivo è dato da ```INDIRIZZO_BASE_ATLETA_PRECEDENTE + 12```. 