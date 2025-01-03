package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.BACKEND_PORT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.setBrokerUrl;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.setImgUri;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.setImgUriUser;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.setPhotoUri;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.Handler;
import android.view.View;
import android.widget.EditText;

import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.snackbar.Snackbar;

import java.net.URL;

public class ApiHostnameSetupActivity extends AppCompatActivity {

    private EditText etHostname;
    private String hostname;
    private String url = "35.179.107.54";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_api_hostname_setup);

        etHostname = findViewById(R.id.textEditHostname);
        SharedPreferences sharedPreferences = getSharedPreferences("API_HOSTNAME", Context.MODE_PRIVATE);

        if (sharedPreferences.getString("API_HOSTNAME", null) != null) {
            etHostname.setText(extractDns(sharedPreferences.getString("API_HOSTNAME", url)));
        } else {
            etHostname.setText(url);
        }

    }

    public void onClickSaveHostname(View view) {
        hostname = "http://" + etHostname.getText().toString() + ":8080/api/";
        setImgUri(etHostname.getText().toString(), getApplicationContext());
        setPhotoUri(etHostname.getText().toString(), getApplicationContext());
        setImgUriUser(etHostname.getText().toString() + BACKEND_PORT, getApplicationContext());
        setBrokerUrl(etHostname.getText().toString(), getApplicationContext());
        try {
            SharedPreferences sharedPreferences = getSharedPreferences("API_HOSTNAME", Context.MODE_PRIVATE);
            SharedPreferences.Editor editor = sharedPreferences.edit();
            editor.putString("API_HOSTNAME", hostname);
            editor.apply();

            //Toast.makeText(this, "New hostname " + hostname, Toast.LENGTH_SHORT).show();
            View rootView = findViewById(R.id.hostnameApiConfigView);
            Snackbar.make(rootView, "Hostname: " + hostname, Snackbar.LENGTH_SHORT).show();

            int toastDuration = 1000;
            new Handler(getMainLooper()).postDelayed(() -> {
                Intent intent = new Intent(getApplicationContext(), LoginActivity.class);
                startActivity(intent);
                finish();
            }, toastDuration);
        } catch (Exception e) {
            throw new RuntimeException(e);
        }
    }

    public String extractDns(String hostname) {
        try {
            URL url = new URL(hostname);
            return url.getHost();
        } catch (Exception e) {
            throw new RuntimeException(e);
        }
    }
}