public class AppuntamentoPersonale extends Appuntamento {

	private Persona persona;
	
	public AppuntamentoPersonale(String data, int ora, int durata, String nome, String cognome) {
	
		super(data, ora, durata);
		Persona per = new Persona(nome, cognome);
		this.persona = per;
	
	}
	
	public Persona getPersona() {
	
		return this.persona;
	
	}
	
	public String toString() {
	
		return super.toString() + " con " + this.persona.getNome() + " " + this.persona.getCognome();
	
	}


}
