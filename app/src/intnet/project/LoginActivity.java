package intnet.project;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.Socket;
import java.net.URL;
import java.net.URLEncoder;
import java.net.UnknownHostException;
import java.util.Observable;
import java.util.Observer;

import android.app.Activity;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.ServiceConnection;
import android.os.Bundle;
import android.os.IBinder;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;

public class LoginActivity extends Activity implements Observer{
	LoginHTTPHandler loginThread;
    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.login);
    }
    
    public void login(View v){
    	EditText usernameField =  (EditText) findViewById(R.id.usernameField);
    	EditText passwordField =  (EditText) findViewById(R.id.passwordField);
    	if(!usernameField.getEditableText().toString().equals("") && !passwordField.getEditableText().toString().equals("")){
	    	loginThread = new LoginHTTPHandler(usernameField.getEditableText().toString(), passwordField.getEditableText().toString(), this);
	    	Thread t = new Thread(loginThread);
	    	t.start();
    	}
    }

	@Override
	public void update(Observable arg0, Object arg1) {
		if(loginThread.getCookie() != null){
    		//change to standard feed view
    		Intent intent = new Intent(this, FeedActivity.class);
    		Bundle b = new Bundle();
    		b.putString("cookie", loginThread.getCookie());
    		intent.putExtras(b);
    		startActivity(intent);
    	}
		
	}
}