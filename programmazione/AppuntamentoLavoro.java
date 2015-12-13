public class AppuntamentoLavoro extends Appuntamento{

	private String luogo;
	
	public AppuntamentoLavoro(String data, int ora, int durata, String luogo) {
	
		super(data, ora, durata);
		this.luogo = luogo;
	
	}
	
	public String toString() {
	
		return super.toString() + " luogo " + this.luogo;
	
	}
	
	public String getLuogo() {
	
		return this.luogo;
	
	}

}
