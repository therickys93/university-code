public class AgendaTester {

	public static void main(String[] args) {
	
		AppuntamentoLavoro appLa1 = new AppuntamentoLavoro("03-12", 13, 2, "sala 3");
		AppuntamentoPersonale appPe1 = new AppuntamentoPersonale("02-05", 20, 1, "Mario", "Bianchi");
		
		Agenda agenda = new Agenda("Mario", "Rossi");
		//System.out.println(appLa1.toString());
		//System.out.println(appPe1.toString());
		agenda.aggiungiAppuntamento(appLa1);
		agenda.aggiungiAppuntamento(appPe1);
		System.out.println(agenda.vectorSize());
		System.out.println(agenda.toString());
		
	
	}

}
