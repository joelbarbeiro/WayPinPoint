package pt.ipleiria.estg.dei.waypinpoint;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;

import androidx.appcompat.app.AppCompatActivity;

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
            etHostname.setText(sharedPreferences.getString("API_HOSTNAME", "35.179.107.54"));
            hostname = "http://" + etHostname + ":8080/api";
        }

    }
    public void onClickSaveHostname(View view){

        hostname = "http://" + etHostname.getText().toString() + ":8080/api";

        SharedPreferences sharedPreferences = getSharedPreferences("API_HOSTNAME", Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString("API_HOSTNAME", hostname);
        editor.apply();

    }


    public void onClickHostnameBack(View view) {
        Intent intent = new Intent(this, LoginActivity.class);
        startActivity(intent);
        finish();
    }
}