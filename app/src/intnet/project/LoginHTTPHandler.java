package intnet.project;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLEncoder;
import java.util.Observable;
import java.util.Observer;

public class LoginHTTPHandler extends Observable implements Runnable {
	private final String domain = "HTTP://SET_DOMAIN_TO_WEB_APP";
	private String user;
	private String password;
	private String cookie;
	public LoginHTTPHandler(String user, String password, Observer obs){
		this.user = user;
		this.password = password;
		addObserver(obs);
	}
	public String getCookie(){
		return cookie;
	}
	@Override
	public void run() {
		HttpURLConnection conn = null;
		try {
			conn = (HttpURLConnection) (new URL(domain + "/api/newSession.php")).openConnection();
			conn.setRequestMethod("POST");
			conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
			
			String POSTdata = "user="+URLEncoder.encode(user)+"&password="+URLEncoder.encode(password);
			
			conn.setRequestProperty("Content-Length", Integer.toString(POSTdata.getBytes().length));
			conn.setDoInput(true);
		    conn.setDoOutput(true);
		    
			DataOutputStream out = new DataOutputStream(conn.getOutputStream());
			out.writeBytes(POSTdata);
			out.close();
			
			InputStream is = conn.getInputStream();
		    BufferedReader rd = new BufferedReader(new InputStreamReader(is));
		    String line;
		    while((line = rd.readLine()) != null) {
		    	if(line.equals("Logged in!")){
		    		cookie = conn.getHeaderField("Set-Cookie");
		    	}
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

}
