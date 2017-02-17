# inizio parte dati
.data

# 3 campi * 4 bytes = 12 bytes * 5 atleti = 60 bytes
ATHLETES: .space 60

# numero massimo degli atleti
ATHLETE_MAX: .word 5

# numero degli atleti salvati
ATHLETE_COUNT: .space 4

# 4 bytes * 5 atleti = 20 bytes
# in questo array vengono salvati solamente gli indirizzi degli atleti che non devono essere più stampati nell'ordinamento
ATHLETES_ADDRESSES: .space 20

# per cambiare il numero degli atleti basta solamente modificare rifare il calcolo per ATHLETES, ATHLETE_MAX e ATHLETES_ADDRESSES

# stringhe usate nel corso del programma
MENU: .asciiz "\nAthlete Database!\n\n0 - exit\n1 - insert athlete\n2 - insert time\n3 - print database\n4 - print min time athlete\n5 - print classification\n\nYour choise? "
NEW_ATHLETE_STRING: .asciiz "Insert new Athlete with ID: "
INSERT_NEW_TIME_STRING_ONE: .asciiz "Insert new Time for Athlete ID: "
INSERT_NEW_TIME_STRING_TWO: .asciiz "seconds: "
INSERT_NEW_TIME_STRING_THREE: .asciiz "centiseconds: "
PRINTING_DATABASE_HEAD_STRING: .asciiz "Athlete ID | Time | Number of Races \n"
PRINT_DATABASE_SEPARATOR_STRING: .asciiz " | "
PRINT_DATABASE_POINT_STRING: .asciiz "."
PRINT_DATABASE_NO_TIME_STRING: .asciiz "No Time"
PRINT_DATABASE_NEW_LINE_STRING: .asciiz "\n"
PRINT_NO_MIN_TIME_STRING: .asciiz "No Athlete Found\n"
BYE: .asciiz "Bye!\n"

# inizio parte testo
.text
.globl main

# funzione principale
main:

# Ciclo principale --> presentazione del menu
Main_Loop:

# stampo la stringa del menu
li $v0, 4
la $a0, MENU
syscall

# chiedo all'utente di inserire un numero, cioè la sua scelta
li $v0, 5
syscall

# vedo a cosa corrisponde la scelta
beq $v0, $zero, End
beq $v0, 1, Insert_Athlete_Prompt
beq $v0, 2, New_Time_Prompt
beq $v0, 3, Print_Database_Prompt
beq $v0, 4, Min_Athlete
beq $v0, 5, Min_Classification

# se la scelta non esiste ristampo il menu
j Main_Loop

# se la scelta == 0, esco dal menu stampando la stringa Bye
End:
li $v0, 4
la $a0, BYE
syscall

# salto all'indirizzo di ritorno del main che in questo caso termina il programma
jr $ra

# se la scelta == 5
# stampa la classifica degli atleti
Min_Classification:

# prima di tutto svuoto array degli atleti da saltare
add $s4, $ra, $zero
jal EmptyAddressesArray
add $ra, $s4, $zero

# chiama funzione che stampa intestazione della tabella
add $s4, $ra, $zero
jal Print_Database_Head
add $ra, $s4, $zero

# inizializzo variabile del ciclo
add $s1, $zero, $zero

# prendo il numero degli atleti che sono salvati
la $s0, ATHLETE_COUNT
lw $t0, 0($s0)

# prendo indirizzo base array degli atleti da escludere
la $s5, ATHLETES_ADDRESSES

# inizio ciclo
Min_Classification_Loop:

# ogni volta vado a vedere se il la variabile è minore del numero degli atleti
slt $s2, $s1, $t0
bne $s2, 1, Min_Classification_End

# chiama funzione di stampa del min e ritorna indirizzo stampato in $v0
add $s4, $ra, $zero
jal Min
add $ra, $s4, $zero

# $s2 contiene indirizzo atleta appena stampato
add $s2, $v0, $zero

# verifico che se sono nel primo ciclo e che l'indirizzo di ritorno della funzione Min è uguale a 0
# perchè in questo caso stamperei la stringa "No Athlete Found"
# se la variabile del ciclo = 0
beq $s1, $zero, Min_Classification_First_Cicle

# altrimenti vado avanti
j Min_Classification_Go

# sono al primo ciclo
Min_Classification_First_Cicle:

# se la variabile di ritorno della funzione Min = 0
beq $s2, $zero, Min_Classification_No_Athlete_Found

# altrimenti vado avanti
j Min_Classification_Go

# entrambe le condizioni di prima sono verificate quindi
# stampo che non ho trovato nessun atleta
Min_Classification_No_Athlete_Found:
li $v0, 4
la $a0, PRINT_NO_MIN_TIME_STRING
syscall

# continuo esecuzione della funzione
Min_Classification_Go:

# vado a cercare indirizzo per il primo posto disponibile dove salvare indirizzo atleta
mul $s3, $s1, 4
add $s3, $s5, $s3

# salvo indirizzo atleta
sw $s2, 0($s3)

# aumento la variabile del ciclo di uno
addi $s1, $s1, 1

# ripeto il ciclo
j Min_Classification_Loop

# fine della funzione
Min_Classification_End:

# ritorna al menu principale
j Main_Loop

# funzione che svuota l'array con tutti gli indirizzi per ordinamento
EmptyAddressesArray:

# vado a prendere i valori che mi servono: indirizzo degli atleti da non considerare e numero degli atleti da non considerare
la $s0, ATHLETE_MAX
la $t1, ATHLETES_ADDRESSES
lw $t0, 0($s0)

# creo variabile per il ciclo
add $s2, $zero, $zero

# comincio il ciclo
Empty_Loop:

# variabile del ciclo minore del numero degli atleti?
slt $s3, $s2, $t0
bne $s3, 1, Empty_End

# se si 
# calcolo nuovo indirizzo facendo indirizzo base + ( variabile ciclo * 4 )
mul $s3, $s2, 4
add $s3, $t1, $s3

# salvo nella nuova posizione il valore 0
sw $zero, 0($s3)

# aggiungo uno alla variabile del ciclo in modo tale che mi passa all'elemento successivo
addi $s2, $s2, 1

# rieseguo il ciclo
j Empty_Loop

# se no
Empty_End:

# ritorno alla funzione che mi ha chiamato
jr $ra

# se la scelta == 4
Min_Athlete:

# prima di chiamare la funzione min, in questo caso,
# devo svuotare l'array degli indirizzi da saltare
add $s4, $ra, $zero
jal EmptyAddressesArray
add $ra, $s4, $zero

# chiama funzione che stampa intestazione della tabella
add $s4, $ra, $zero
jal Print_Database_Head
add $ra, $s4, $zero

# chiama funzione min
add $s0, $ra, $zero
jal Min
add $ra, $s0, $zero

# sposto il valore di ritorno della funzione Min in $s2
add $s2, $v0, $zero

# se il valore di ritorno = 0 allora stampo la stringa no athlete found
beq $s2, $zero, Min_No_Athlete

# altrimenti ritorno al menu principale
j Main_Loop

# stampo che non ho trovato l'atleta
Min_No_Athlete:
li $v0, 4
la $a0, PRINT_NO_MIN_TIME_STRING
syscall

# ritorna al menu principale
j Main_Loop

# funzione che cerca il minimo tempo e stampa atleta corrispondente
Min:

# salvo il valore di alcuni registri sullo stack
subu $sp, $sp, 32
sw $fp, 28($sp)
sw $s0, 24($sp)
sw $s1, 20($sp)
sw $s2, 16($sp)
sw $s3, 12($sp)
sw $s4, 8($sp)
sw $s5, 4($sp)
sw $s6, 0($sp)

# vado a prendere come valori utili: il numero degli atleti e l'indirizzo del primo atleta
la $s0, ATHLETE_COUNT
la $s1, ATHLETES
lw $t0, 0($s0)

# prendo anche l'array con gli indirizzi da escludere
la $s0, ATHLETES_ADDRESSES
lw $t3, 0($s0)

# inizializzo variabile del ciclo
add $s2, $zero, $zero

# setto il minor tempo uguale a 6000 che è il massimo
addi $s5, $zero, 0x1770

# setto come indirizzo iniziale il valore 0
add $s6, $zero, $zero

# loop per cercare il minimo tempo
Min_Loop:

# inizializzo variabile che mi serve per scorrere gli indirizzi da escludere
add $t4, $zero, $zero

# controllo che la variabile del ciclo sia minore del numero degli atleti
slt $s3, $s2, $t0
bne $s3, 1, Min_End

# vado a cercare l'indirizzo dell'atleta successivo
mul $s3, $s2, 12
add $s3, $s1, $s3

# ciclo per vedere se indirizzo coincide con qualche indirizzo da escludere
Addresses_Loop:
slt $t5, $t4, $t0
bne $t5, 1, Addresses_Loop_End

# calcolo il nuovo indice
mul $t5, $t4, 4
add $t5, $s0, $t5

# prendo il contenuto del nuovo indice
lw $t5, 0($t5)

# vedo se sono uguali
beq $s3, $t5, Min_End_If

# sommo uno alla variabile del ciclo
addi $t4, $t4, 1

# salto per concludere array
j Addresses_Loop

# finito il ciclo vado avanti
Addresses_Loop_End:

# carico in $s4 il tempo dell'atleta
lw $s4, 4($s3)

# confronto il nuovo tempo con quello salvato
slt $t1, $s4, $s5
bne $t1, 1, Min_End_If

# nel caso il nuovo tempo è minore del tempo salvato salvo nuovo tempo e salvo indirizzo id dell'atleta
add $s5, $s4, $zero
add $s6, $s3, $zero

# nel caso non sia minore aumento in ogni caso la variabile del ciclo
Min_End_If:
addi $s2, $s2, 1

# ripeto il ciclo
j Min_Loop

# quando ho finito tutti gli atleti
Min_End:

# vado a vedere se non ho salvato nessun indirizzo
beq $s6, $zero, Min_Return

# se ho salvato qualcosa stampo l'atleta
add $s4, $ra, $zero
add $a0, $s6, $zero
jal Print_Athlete
add $ra, $s4, $zero

# fine della procedura
Min_Return:

# salvo come valore di ritorno l'indirizzo che ho appena stampato
add $v0, $s6, $zero

# risistemo alcuni registri come erano prima della chiamata della funzione
lw $fp, 28($sp)
lw $s0, 24($sp)
lw $s1, 20($sp)
lw $s2, 16($sp)
lw $s3, 12($sp)
lw $s4, 8($sp)
lw $s5, 4($sp)
lw $s6, 0($sp)
addi $sp, $sp, 32

# ritorno al menu principale
jr $ra

# se la scelta == 3
Print_Database_Prompt:

# salvo indirizzo di ritorno della funzione in $s0 in modo tale da non sovrascriversi nella chiamata alla funzione successiva
add $s0, $ra, $zero

# chiamo la funzione PRINT_DATABASE
jal PRINT_DATABASE

# reimposto indirizzo di ritorno in modo tale che posso ritornare sempre indietro
add $ra, $s0, $zero

# una volta stampato il database ritorno al menu principale
j Main_Loop

# funzione PRINT_DATABASE
PRINT_DATABASE:

# salvo sullo stack i valori di alcuni registri che userò internamente
subu $sp, $sp, 28
sw $fp, 24($sp)
sw $s0, 20($sp)
sw $s1, 16($sp)
sw $s2, 12($sp)
sw $s3, 8($sp)
sw $s4, 4($sp)
sw $s5, 0($sp)

# chiamo funzione di stampa intestazione del database
add $s4, $ra, $zero
jal Print_Database_Head
add $ra, $s4, $zero

# salvo in $s0 l'indirizzo in cui è salvato il numero degli atleti
la $s0, ATHLETE_COUNT

# carico in $t0 il numero degli atleti
lw $t0, 0($s0)

# salvo in $s1 l'indirizzo in cui è salvato il primo atleta
la $s1, ATHLETES

# dichiaro una variabile in $s2 e la inizializzo uguale a 0
add $s2, $zero, $zero

# entro in un ciclo while
Print_Database_Loop:

# testo che la variabile appena creata sia minore del numero degli atleti
slt $s3, $s2, $t0
bne $s3, 1, Print_Database_Loop_End

# ogni volta vado a prendere l'indirizzo dell'atleta successivo, sommando l'indirizzo principale con l'offset
mul $s3, $s2, 12
add $s3, $s1, $s3

# mi preparo a chiamare la funzione di stampa dell'atleta
add $s4, $ra, $zero

# passo alla funzione l'indirizzo dell'atleta da stampare
add $a0, $s3, $zero
jal Print_Athlete
add $ra, $s4, $zero

# aumento di una unità il valore della variabile del loop
addi $s2, $s2, 1

# ripeto nuovamente il ciclo
j Print_Database_Loop

# alla fine ritorno alla situazione prima di aver chiamato la funzione
Print_Database_Loop_End:

lw $fp, 24($sp)
lw $s0, 20($sp)
lw $s1, 16($sp)
lw $s2, 12($sp)
lw $s3, 8($sp)
lw $s4, 4($sp)
lw $s5, 0($sp)
addi $sp, $sp, 28

# ritorno alla funzione chiamante
jr $ra

# funzione che dato indirizzo dell'atleta in $a0 stampa l'atleta id, tempo e numero gare
Print_Athlete:

subu $sp, $sp, 20
sw $fp, 16($sp)
sw $s0, 12($sp)
sw $s1, 8($sp)
sw $s2, 4($sp)
sw $s3, 0($sp)

# vado a salvare l'indirizzo dell'atleta
add $s0, $a0, $zero

# carico id dell'atleta
lw $a0, 0($s0)

# stampo l'id
li $v0, 1
syscall

# chiamo la funzione Print_Sepator
add $s3, $ra, $zero
jal Print_Sepator
add $ra, $s3, $zero

# carico dalla memoria il tempo dell'atleta
lw $s1, 4($s0)

# testo che il tempo sia uguale a 6000 ( 0x1770 in esadecimale ) in tal caso stampo la stringa no time
beq $s1, 0x1770, Print_Athlete_No_Time

# converto il tempo salvato in secondi e centesimi di secondo
li $s2, 100
div $s1, $s2
mflo $a0

# stampo i secondi
li $v0, 1
syscall

# stampo il punto
li $v0, 4
la $a0, PRINT_DATABASE_POINT_STRING
syscall

# stampo i centesimi di secondo
# carico il resto della divisione in $s2 che in questo caso sono i centesimi di secondo
mfhi $s2

# vado a testare se sono minori di 10 in modo tale da aggiungere uno 0 davanti al numero
slti $s2, $s2, 10

# se il numero non è minore di 10 salto
bne $s2, 1, Print_Normal_Centiseconds

# stampo lo zero
li $v0, 1
add $a0, $zero, $zero
syscall

# stampo il numero dei centesimi di secondo dato che se è minore di 10 ho già stampato uno 0
Print_Normal_Centiseconds:
mfhi $a0
li $v0, 1
syscall

# salto alla fine della stampa del tempo
j Print_Athlete_No_Time_End

# stampo nel caso in cui non ho il tempo la stringa no time
Print_Athlete_No_Time:
li $v0, 4
la $a0, PRINT_DATABASE_NO_TIME_STRING
syscall

# vado avanti con la stampa del numero delle gare
Print_Athlete_No_Time_End:

# chiamo la funzione Print_Sepator
add $s3, $ra, $zero
jal Print_Sepator
add $ra, $s3, $zero

# prendo numero delle gare dalla memoria e lo stampo
lw $a0, 8($s0)
li $v0, 1
syscall

# chiamo la funzione Print_New_Line
add $s3, $ra, $zero
jal Print_New_Line
add $ra, $s3, $zero

# risetto i valori di partenza
lw $fp, 16($sp)
lw $s0, 12($sp)
lw $s1, 8($sp)
lw $s2, 4($sp)
lw $s3, 0($sp)
addi $sp, $sp, 20

# torno alla funzione chiamante
jr $ra

# funzione che stampa intestazione della tabella
Print_Database_Head:

# stampo la stringa di intestazione del database e ritorno alla funzione chiamante
li $v0, 4
la $a0, PRINTING_DATABASE_HEAD_STRING
syscall

# torno alla funzione chiamante
jr $ra

# funzione Print_Sepator
Print_Sepator:

# stampo la stringa del separatore e ritorno alla funzione chiamante
li $v0, 4
la $a0, PRINT_DATABASE_SEPARATOR_STRING
syscall

# torno alla funzione chiamante
jr $ra

# funzione Print_New_Line
Print_New_Line:

# stampo la stringa di nuova linea e ritorno alla funzione chiamante
li $v0, 4
la $a0, PRINT_DATABASE_NEW_LINE_STRING
syscall

# torno alla funzione chiamante
jr $ra

# se la scelta == 2
New_Time_Prompt:

# chiedo all'utente di darmi l'ID dell'atleta stampando prima la frase di richiesta id
li $v0, 4
la $a0, INSERT_NEW_TIME_STRING_ONE
syscall
li $v0, 5
syscall

# salvo l'Id dell'atleta in $a0
add $a0, $v0, $zero

# inolte salvo l'ID dell'atleta in $s1 perchè mi servirà in seguito
add $s1, $a0, $zero

# mi preparo e chiamo la funzione UPDATE_RACE_NUMBER
add $s0, $ra, $zero
jal UPDATE_RACE_NUMBER
add $ra, $s0, $zero

# chiedo all'utente di inserire il numero dei secondi stampando prima la richiesta dei secondi
li $v0, 4
la $a0, INSERT_NEW_TIME_STRING_TWO
syscall
li $v0, 5
syscall

# salvo il numero dei secondi in $s0
add $s0, $v0, $zero

# chiedo all'utente di inserire il numero dei centesimi di secondo stampando prima la stringa dei centesimi di secondo
li $v0, 4
la $a0, INSERT_NEW_TIME_STRING_THREE
syscall
li $v0, 5
syscall

# salvo il numero dei centesimi di secondo in $s2
add $s2, $v0, $zero

# converto il valore dei secondi in centesimi di secondo e li sommo in $s0
mul $s0, $s0, 100
add $s0, $s0, $s2

# metto l'id dell'atleta in $a0
add $a0, $s1, $zero

# metto il nuovo tempo in $a1
add $a1, $s0, $zero

# chiamo la funzione NEW_TIME
add $s0, $ra, $zero
jal NEW_TIME
add $ra, $s0, $zero

# una volta terminata la funzione torno al menu principale
j Main_Loop

# funzione UPDATE_RACE_NUMBER che riceve in $a0 l'ID dell'atleta
UPDATE_RACE_NUMBER:

# mi preparo ad usare la funzione
subu $sp, $sp, 24
sw $fp, 20($sp)
sw $s0, 16($sp)
sw $s1, 12($sp)
sw $s2, 8($sp)
sw $s3, 4($sp)
sw $s4, 0($sp)

# carico dalla memoria i dati utili cioè il numero degli atleti e l'indirizzo del primo atleta
la $s0, ATHLETE_COUNT
lw $t0, 0($s0)
la $s1, ATHLETES

# inizializzo una variabile per il ciclo
add $s2, $zero, $zero

# comincio il ciclo do-while
Race_Number_Loop:

# calcolo l'offset del nuovo atleta e carico il suo id nel registro $t1
mul $s3, $s2, 12
add $s3, $s1, $s3
lw $t1, 0($s3)

# vado a testare che l'id del corrente atleta sia diverso da quello che mi viene passato come parametro
bne $t1, $a0, End_If_Of_Update_Race_Number

# vado a prendere il numero di gare che l'atleta ha disputato
addi $s4, $s3, 8
lw $t2, 0($s4)

# aggiungo 1 al numero di gare
addi $t2, $t2, 1

# risalvo in memoria il nuovo numero
sw $t2, 0($s4)

# se l'id non è uguale a quello passato come parametro
End_If_Of_Update_Race_Number:

# aggiungo 1 alla variabile del ciclo
addi $s2, $s2, 1

# controllo che il nuovo valore della variabile sia minore del numero degli atleti
slt $s3, $s2, $t0

# se la variabile è minore ripeto il ciclo
beq $s3, 1, Race_Number_Loop

# reimposto i registri
lw $fp, 20($sp)
lw $s0, 16($sp)
lw $s1, 12($sp)
lw $s2, 8($sp)
lw $s3, 4($sp)
lw $s4, 0($sp)
addi $sp, $sp, 24

# ritorno alla funzione chiamante
jr $ra

# funzione NEW_TIME che riceve in $a0 l'ID dell'atleta e in $a1 il nuovo tempo
NEW_TIME:

# mi preparo ad usare la nuova funzione
subu $sp, $sp, 24
sw $fp, 20($sp)
sw $s0, 16($sp)
sw $s1, 12($sp)
sw $s2, 8($sp)
sw $s3, 4($sp)
sw $s4, 0($sp)

# vado a prendere il numero degli atleti e l'indirizzo base dell'array degli atleti
la $s0, ATHLETE_COUNT
lw $t0, 0($s0)
la $s1, ATHLETES

# creo variabile per il ciclo
add $s2, $zero, $zero

# creo ciclo do-while
New_Time_Loop:

# calcolo l'offset del nuovo atleta e prendo l'id
mul $s3, $s2, 12
add $s3, $s1, $s3
lw $t1, 0($s3)

# confronto se l'id appena caricato è diverso da quello che mi è stato passato come parametro
bne $t1, $a0, End_If_Of_New_Time

# vado a caricare dalla memoria il miglior tempo
addi $s4, $s3, 4
lw $t2, 0($s4)

# confronto se il nuovo tempo è minore di quello che è stato passato come parametro
slt $s3, $a1, $t2
beq $s3, 0, End_If_Of_New_Time 

# se il nuovo tempo è minore lo vado a salvare
sw $a1, 0($s4)

End_If_Of_New_Time:

# aggiungo 1 alla variabile del ciclo e testo se rimanere o no nel ciclo in base al fatto che la variabile del ciclo sia minore del numero degli atleti
addi $s2, $s2, 1
slt $s3, $s2, $t0
beq $s3, 1, New_Time_Loop

# reimposto il valore dei registri 
lw $fp, 20($sp)
lw $s0, 16($sp)
lw $s1, 12($sp)
lw $s2, 8($sp)
lw $s3, 4($sp)
lw $s4, 0($sp)
addi $sp, $sp, 24

# ritorno alla funzione chiamante
jr $ra

# se la scelta == 1
Insert_Athlete_Prompt:

# stampo la stringa di richiesta del nuovo id 
li $v0, 4
la $a0, NEW_ATHLETE_STRING
syscall

# prendo il nuovo id dall'utente
li $v0, 5
syscall

# salvo il nuovo id in $a0
add $a0, $v0, $zero

# chiamo la funzione INSERT_ATHLETE
add $s0, $ra, $zero
jal INSERT_ATHLETE
add $ra, $s0, $zero

# ritorno al loop principale
j Main_Loop

# funzione INSERT_ATHLETE che riceve in $a0 il nuovo ID dell'atleta
INSERT_ATHLETE:

# salvo i valori sullo stack
subu $sp, $sp, 20
sw $fp, 16($sp)
sw $s0, 12($sp)
sw $s1, 8($sp)
sw $s2, 4($sp)
sw $s3, 0($sp)

# vado a prendere il numero degli atleti e l'indirizzo del primo atleta e il massimo degli atleti
la $s0, ATHLETE_COUNT
la $s1, ATHLETES
la $s2, ATHLETE_MAX
lw $t0, 0($s0)
lw $t3, 0($s2)

# controllo che il numero degli atleti non sia minore del massimo degli atleti
slt $t2, $t0, $t3
bne $t2, 1, Insert_Athlete_End

# creo variabile per il ciclo e la inizializzo a 0
add $s2, $zero, $zero

# creo ciclo do-while per andare a verificare che l'id che sto inserendo sia diverso da qualsiasi altro id già inserito
Insert_Athlete_Loop:

# vado a prendere il prossimo id
mul $s3, $s2, 12
add $s3, $s1, $s3
lw $t1, 0($s3)

# testo che l'id trovato sia uguale all'id passato come parametro in modo tale da terminare il ciclo e la funzione
beq $t1, $a0, Insert_Athlete_End

# aumento di 1 la variabile del ciclo
addi $s2, $s2, 1

# vado a testare che la variabile del ciclo sia minore del numero di atleti inseriti
slt $s3, $s2, $t0
beq $s3, 1, Insert_Athlete_Loop

# calcolo il nuovo offset in cui salvare l'id
mul $t1, $t0, 12
add $t1, $t1, $s1

# salvo l'id
sw $a0, 0($t1)

# imposto un tempo default 6000 (0x1770) che è maggiore di qualsiasi tempo inseribile
add $t2, $zero, $zero
addi $t2, $t2, 0x1770

# salvo il nuovo tempo default
sw $t2, 4($t1)

# aggiungo 1 al numero degli atleti
addi $t0, $t0, 1

# salvo il nuovo numero degli atleti
sw $t0, 0($s0)

# salto a questa label solo se il numero degli atleti inseriti è maggiore o uguale al numero massimo degli atleti
Insert_Athlete_End:

# reimposto i valori a prima della chiamata della funzione
lw $s3, 0($sp)
lw $s2, 4($sp)
lw $s1, 8($sp)
lw $s0, 12($sp)
lw $fp, 16($sp)
addi $sp, $sp, 20

# ritorno alla funzione chiamante
jr $ra