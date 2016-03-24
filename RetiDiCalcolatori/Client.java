import java.net.*;
import java.io.*;

public class Client
{
	public static void main(String[] args)
	{
		if(args.length == 2)
		{
			// creo un buffer 
			int dim = 1024;
			byte[] buffer = new byte[dim];
			InetAddress ia;
			InetSocketAddress isa;
			DatagramSocket ds;
			// prendo il nome del server che mi viene passato come parametro
			String server = args[0];
			// prende la porta del server che mi viene passata come parametro
			int porta = Integer.parseInt(args[1]);
			try
			{
				// inizializzo una socketInetAddress che mi permette di inviare i dati al server
				ia = InetAddress.getByName(server);
				isa = new InetSocketAddress(ia, porta);
				ds = new DatagramSocket();
				// prendo i dati da tastiera e creo un pacchetto pronto per essere inviato
				InputStreamReader keyboard = new InputStreamReader(System.in);
				BufferedReader br = new BufferedReader(keyboard);
				String s;
				DatagramPacket dp = new DatagramPacket(buffer, dim);
				String data;
				while(true){
					s = br.readLine();
					dp.setData(s.getBytes(), 0, s.length());
					// setto la destinazione del pacchetto
					dp.setSocketAddress(isa);
					// invio il pacchetto
					ds.send(dp);
					// rimango in attesa che il server mi invii cosa devo fare
					ds.receive(dp);
					data = new String(dp.getData(), 0, dp.getLength());
					// in base a quello che il server mi comunica stampo a schermo quello che il sensore dovrebbe far scattare
					if(data.equals("aa"))
					{	
						System.out.println("accendi aria condizionata");
					}
					else if(data.equals("si"))
					{
						System.out.println("spegni l'impianto");
					}
					else
					{
						System.out.println("accendi riscaldamento");
					}
				}
			}
			catch(Exception e)
			{
				e.printStackTrace();
			}
		} 
		else
		{
			System.out.println("usage: java Client [nome_server] [porta]");
		}
	}
}
