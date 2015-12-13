import java.lang.*;
public abstract class Appuntamento implements Comparable<Appuntamento> {

	private String data;
	private int day;
	private int month;
	private int ora;
	private int durata;
	
	public Appuntamento(String data, int ora, int durata) {
	
		int separatore = data.indexOf('-');
		String mese = data.substring(0, separatore);
		String giorno = data.substring(separatore + 1);
		//System.out.println(mese);
		//System.out.println(giorno);
		Integer integer1 = new Integer(mese);
		int month = integer1.intValue();
		Integer integer2 = new Integer(giorno);
		int day = integer2.intValue();
		
		if (day > 31 || day < 1 || month > 12 || month < 1 || ora < 1 || ora > 23) {
		
			throw new IllegalArgumentException("La data non e' stata inserita correttamente");
		
		} else {
		
			this.data = data;
			this.ora = ora;
			this.durata = durata;
			this.day = day;
			this.month = month;
		
		}
	
	}
	
	public boolean inConflitto(Appuntamento altro) {
	
		if (this.data.equals(altro.data) && this.ora == altro.ora) {
		
			return true;
		
		} else {
		
			return false;
		
		}
	
	}
	
	public int compareTo(Appuntamento a) {
	
		if (this.day > a.day || this.month > a.month && this.ora > a.ora ) {
		
			return 1;
		
		} else if (this.day == a.day && this.month == a.month && this.ora == a.ora) {
			
			return 0;
		
		} else {
		
			return -1;
		
		}
	
	}
	
	public String getData() {
	
		return this.data;
	
	}
	
	public int getOra() {
	
		return this.ora;
	
	}
	
	public int getDurata() {
	
		return this.durata;	
	
	}
	
	public int getDay() {
	
		return this.day;
	
	}
	
	public int getMonth() {
	
		return this.month;
	
	}
	
	public String toString() {
	
		return "Appuntamento in data " + this.data + " ora " + this.ora + " durata " + this.durata;
	
	}

}



