import java.util.*;
public class Agenda {

	private Persona titolare;
	private Vector<Appuntamento> appuntamenti = new Vector<Appuntamento>();
	
	
	public Agenda(String nome, String cognome) {
		
		Persona per = new Persona(nome, cognome);
		
		this.titolare = per;
	
	}
	
	public void aggiungiAppuntamento(Appuntamento a) {
	
		for (Appuntamento app : appuntamenti) {
		
			if (app.inConflitto(a)) {
			
				//appuntamenti.add(a);
				//System.out.println("aggiunto");
				//break;
			
			} else {
			
				appuntamenti.addElement(a);
				System.out.println("aggiunto");
				break;
				
			
			}
		
		}
	
	}
	
	public int statistiche(String tipo) {
	
		int percentualeLavoro = 0;
		int percentualePersonale = 0;
		if (tipo.equals("lavoro")) {
		
			for(Appuntamento app : appuntamenti) {
			
				if (app instanceof AppuntamentoLavoro) {
				
					percentualeLavoro++;
				
				}

			
			}
			
			return percentualeLavoro * 100 / appuntamenti.size();
		
		} else if (tipo.equals("personale")) {
		
			for (Appuntamento app : appuntamenti) {
			
				if (app instanceof AppuntamentoPersonale) {
				
					percentualePersonale++;
				
				}
			
			}
			
			return percentualePersonale * 100 / appuntamenti.size();
		
		} else {
		
			return -1;
		
		}
		
	
	}
	
	
	public void ordina(){
	
		for (Appuntamento app : appuntamenti) {
		
			int numero = appuntamenti.indexOf(app);
			int numero2 = numero++;
			
			
			Appuntamento uno = appuntamenti.elementAt(numero);
			Appuntamento due = appuntamenti.elementAt(numero2);
			
			int comp = uno.compareTo(due);
			
			
			if (comp == 1 || comp == -1) {
			
				Appuntamento a = uno;
				Appuntamento b = due;
				app = b;
				b = a;
				
			
			}
		
		
		}
	
	}
	
	public String toString() {
	
		return appuntamenti.toString();
		
	
	}
	
	public int vectorSize() {
	
		return appuntamenti.size();
	
	}
	
	public void cancellaAppuntamento(Appuntamento a) {
	
		for (Appuntamento app : appuntamenti) {
		
			if (app == a) {
			
				appuntamenti.remove(app);
			
			}
		
		}
	
	}
	
	

}
