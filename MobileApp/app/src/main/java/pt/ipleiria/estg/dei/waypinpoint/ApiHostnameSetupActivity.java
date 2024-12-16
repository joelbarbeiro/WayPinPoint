package pt.ipleiria.estg.dei.waypinpoint;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import java.net.URL;

public class ApiHostnameSetupActivity extends AppCompatActivity {

    private EditText etHostname;
    private String hostname;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_api_hostname_setup);

        etHostname = findViewById(R.id.textEditHostname);
        SharedPreferences sharedPreferences = getSharedPreferences("API_HOSTNAME", Context.MODE_PRIVATE);

        if(sharedPreferences.contains("API_HOSTNAME")) {
            etHostname.setText(extractDns(sharedPreferences.getString("API_HOSTNAME", "35.179.107.54")));
        } else {
            Toast.makeText(this, "No shared preferences on this field.", Toast.LENGTH_SHORT).show();
        }

    }
    public void onClickSaveHostname(View view){

        hostname = "http://" + etHostname.getText().toString() + ":8080/api/";

        SharedPreferences sharedPreferences = getSharedPreferences("API_HOSTNAME", Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString("API_HOSTNAME", hostname);
        editor.apply();

        Toast.makeText(this, "New hostname " + hostname, Toast.LENGTH_SHORT).show();
    }

    public void onClickHostnameBack(View view) {
        Intent intent = new Intent(this, LoginActivity.class);
        startActivity(intent);
        finish();
    }

    public String extractDns(String hostname) {
        try {
            URL url = new URL(hostname);  // Parse the URL
            String host = url.getHost(); // Extract the host (IP address)

            return host;
        } catch (Exception e) {
            e.printStackTrace();
        }
        return null;
    }
}