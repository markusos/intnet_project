package intnet.project;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLDecoder;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Observable;
import java.util.Observer;

public class GetMessagesHTTPHandler extends Observable implements Runnable {
	private final String domain = "HTTP://SET_DOMAIN_TO_WEB_APP";
	private List<HashMap<String, String>> list;
	private String cookie;
	public GetMessagesHTTPHandler(String cookie, Observer obs){
		this.cookie = cookie;
		addObserver(obs);
	}
	@Override
	public void run() {
		list = new ArrayList<HashMap<String, String>>();

		HttpURLConnection conn = null;
		try {
			conn = (HttpURLConnection) (new URL(domain + "/api/getMessagesMobile.php")).openConnection();
			conn.setRequestMethod("GET");
			conn.setRequestProperty("Cookie", cookie);
		    conn.setDoOutput(true);
			
			InputStream is = conn.getInputStream();
		    BufferedReader rd = new BufferedReader(new InputStreamReader(is));
		    String line;
		    while((line = rd.readLine()) != null) {
		    	String[] data = line.split(";");
		    	HashMap<String, String> map = new HashMap<String, String>();
		    	map.put("messageID", URLDecoder.decode(data[0], "ISO-8859-1"));
	        	map.put("name", URLDecoder.decode(data[1], "ISO-8859-1"));
	        	map.put("timestamp", URLDecoder.decode(data[2], "ISO-8859-1"));
	        	map.put("text", URLDecoder.decode(data[3], "ISO-8859-1"));
	        	list.add(map);
	        	
		    }
		    rd.close();
		    
		} catch (MalformedURLException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		
		setChanged();
		notifyObservers();
	}
	
	public List<HashMap<String, String>> GetMessages(){
		return list;
	}

}
