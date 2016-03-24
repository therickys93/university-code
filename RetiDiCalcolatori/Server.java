import java.net.*;
import java.io.*;

public class Server
{
	public static void main(String[] args)
	{
		if(args.length == 2)
		{
			// variabile che mi prende il sensore
			int sensore = 0;
			// imposta il numero dei sensori
			int numeroSensori = Integer.parseInt(args[1]);
			// memorizza la media per ogni sensore
			double[] media = new double[numeroSensori];
			// memorizza per ogni sensore il suo indirizzo
			String[] sensori = new String[numeroSensori];
			// dimensione del buffer
			int dim = 1024;
			// imposta il numero di porta del server
			int porta = Integer.parseInt(args[0]);
			// creo il buffer
			byte[] buffer = new byte[dim];
			try
			{
				DatagramPacket dp = new DatagramPacket(buffer, dim);
				DatagramSocket ds = new DatagramSocket(porta);
				// stampo la porta a cui faccio le richieste
				System.out.println("Porta allocata: "+porta);
				System.out.println("Numero sensori: "+numeroSensori);
				String data;
				double dato;
				while(true)
				{
					// ricevo il pacchetto
					ds.receive(dp);
					// stampo l'indirizzo da cui proviene il pacchetto
					System.out.println("indirizzo : "+dp.getAddress().getHostName());
					// vado a vedere nel mio array sensore se esiste già un sensore con quel nome 
					// se si la variabile sensore assume il valore della posizione di tale sensore
					// altrimenti mi aggiunge l'indirizzo del sensore all'interno della mia lista e termino il ciclo
					for(int i = 0; i < numeroSensori; i++)
					{
						if(sensori[i] != null)
						{
							if(sensori[i].equals(dp.getAddress().getHostName()))
							{
								sensore = i;
							}
						}
						else
						{
							sensori[i] = dp.getAddress().getHostName();
							break;
						}
					}
					// recupero il nuovo valore dal pacchetto
					data = new String(dp.getData(), 0, dp.getLength());
					dato = Double.parseDouble(data);
					// calcolo la nuova media
					media[sensore] = (media[sensore]*0.6) + (dato*0.4);
					if(media[sensore] < 19)
					{
						// accendi il riscaldamento
						data = "ar";
					}
					else if(media[sensore] >= 19 && media[sensore] <= 24)
					{	
						// spegni il sistema
						data = "si";
					}
					else
					{
						// accendi aria condizionata
						data = "aa";
					}
					// stampo la media e invio la risposta al client per essere "elaborata"
					dp.setData(data.getBytes(), 0, data.length());
					System.out.println(Double.toString(media[sensore]));
					ds.send(dp);
				}
			}
			catch(Exception e)
			{
				e.printStackTrace();
			}
		}
		else
		{
			System.out.println("usage: java Server [porta] [numero_sensori_da_collegare]");
		}
	}
}
