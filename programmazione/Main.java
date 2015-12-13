import java.util.*;
public class Main {

	public static void main(String[] args) {
	
		Scanner scan = new Scanner(System.in);
		System.out.println("inserisci il nome: ");
		
		String nome = scan.nextLine();
		System.out.println("inserisci il cognome: ");
		String cognome = scan.nextLine();
		Agenda agenda = new Agenda(nome, cognome);
		System.out.println("1 - aggiungi appuntamento");
		System.out.println("2 - stampa agenda");
		System.out.println("3 - ordina  appuntamenti");
		System.out.println("4 - cancella appuntamento");
		System.out.println("5 - esci");
		System.out.println("scegli un opzione (inserisci il numero)");
		
		int numero = scan.nextInt();
		
		while (numero != 5) {
		
			switch (numero) {
			
				case 1:
					System.out.println("1 - lavoro");
					System.out.println("2 - personale");
					int scelta = scan.nextInt();
					if (scelta == 1) {
					
						System.out.println("inserisci data (formato mm-dd): ");
						String data = scan.nextLine();
						System.out.println("inserisci ora (formato 24): ");
						int ora = scan.nextInt();
						System.out.println("inserisci durata: ");
						int durata = scan.nextInt();
						System.out.println("Inserisci luogo: ");
						String luogo = scan.nextLine();
						AppuntamentoLavoro app = new AppuntamentoLavoro(data, ora, durata, luogo);
						agenda.aggiungiAppuntamento(app);
					
					} else if (scelta == 2) {
					
						System.out.println("inserisci data (formato mm-dd): ");
						String data = scan.nextLine();
						System.out.println("inserisci ora (formato 24): ");
						int ora = scan.nextInt();
						System.out.println("inserisci durata: ");
						int durata = scan.nextInt();
						System.out.println("Inserisci nome della persona: ");
						String nome2 = scan.nextLine();
						System.out.println("inserisci cognome della persona: ");
						String cognome2 = scan.nextLine();
						AppuntamentoPersonale app = new AppuntamentoPersonale(data, ora, durata, nome2, cognome2);
						agenda.aggiungiAppuntamento(app);
					
					}
					
					break;
				case 2:
					agenda.toString();
					break;
				case 3: 
					agenda.ordina();
					break;
				case 4:
					//per mancanza di tempo non ho creato il "dialogo con l'utente" per questa sessione
					//e' stato inserito una cosa a caso
					AppuntamentoLavoro a = new AppuntamentoLavoro("02-15", 3, 1, "ufficio");
					agenda.cancellaAppuntamento(a);
					break;
				default:
				break;
			
			
			}
		
		
		}	
	}

}
