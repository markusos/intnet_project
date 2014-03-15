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

import android.app.Activity;
import android.app.ListActivity;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.ServiceConnection;
import android.os.Bundle;
import android.os.Handler;
import android.os.IBinder;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.TextView;

public class FeedActivity extends Activity implements Observer{
	private final String domain = "HTTP://SET_DOMAIN_TO_WEB_APP";
	String cookie=null;
	GetMessagesHTTPHandler getMessages = null;
	FeedActivity thisInstance = this;
	Handler handler;
	@Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        
        handler = new Handler();
        
        Bundle b = getIntent().getExtras();
        cookie = b.getString("cookie");
        
        setContentView(R.layout.feed);
        
        getMessages = new GetMessagesHTTPHandler(cookie, this);
        new Thread(getMessages).start();
    }
	
	public void onMessageClick(View v){
		
		Intent intent = new Intent(this, CommentActivity.class);
		Bundle b = new Bundle();
		b.putString("cookie", cookie);
		b.putInt("messageID", Integer.parseInt(((TextView)v.findViewById(R.id.list_item_messageID)).getText().toString()));
		b.putString("name", ((TextView)v.findViewById(R.id.list_item_name)).getText().toString());
		b.putString("timestamp", ((TextView)v.findViewById(R.id.list_item_timestamp)).getText().toString());
		b.putString("text", ((TextView)v.findViewById(R.id.list_item_text)).getText().toString());
		intent.putExtras(b);
		startActivity(intent);
	}
	
	public void post(View v){
		HttpURLConnection conn = null;
		try {
			conn = (HttpURLConnection) (new URL(domain + "/api/createMessage.php")).openConnection();
			conn.setRequestMethod("POST");
			conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
			conn.setRequestProperty("Cookie", cookie);
			String POSTdata = "text="+URLEncoder.encode(((TextView)findViewById(R.id.textInput)).getText().toString());
			((TextView)findViewById(R.id.textInput)).setText("");
			conn.setRequestProperty("Content-Length", Integer.toString(POSTdata.getBytes().length));
			conn.setDoInput(true);
		    conn.setDoOutput(true);
		    
			DataOutputStream out = new DataOutputStream(conn.getOutputStream());
			out.writeBytes(POSTdata);
			out.close();
		
			
			InputStream is = conn.getInputStream();
		    BufferedReader rd = new BufferedReader(new InputStreamReader(is));
		    rd.close();
		    
		    getMessages = new GetMessagesHTTPHandler(cookie, this);
	        new Thread(getMessages).start();

		    
		} catch (MalformedURLException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}

	@Override
	public void update(Observable arg0, Object arg1) {
		
		handler.post(new Runnable() {
			@Override
			public void run() {
				String[] from = new String[] {"messageID", "name", "timestamp", "text"};
		        int[] to = new int[] {R.id.list_item_messageID,R.id.list_item_name, R.id.list_item_timestamp, R.id.list_item_text};
				SimpleAdapter adapter = new SimpleAdapter(thisInstance, getMessages.GetMessages(), R.layout.list_item, from, to);
				((ListView)findViewById(R.id.list)).setAdapter(adapter); 
			}
		});
	}
}
