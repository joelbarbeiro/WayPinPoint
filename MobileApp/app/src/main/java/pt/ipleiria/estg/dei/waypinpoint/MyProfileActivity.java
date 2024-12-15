package pt.ipleiria.estg.dei.waypinpoint;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;

import androidx.appcompat.app.AppCompatActivity;

import Model.SingletonManager;
import Model.User;

public class MyProfileActivity extends AppCompatActivity {

    private Button saveButton;

    private User user;

    private EditText etPassword, etEmail, etUsername, etAddress, etNif, etPhone;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_my_profile);
        SharedPreferences sharedPreferencesUser = getSharedPreferences("USER_DATA", MODE_PRIVATE);
        int id = sharedPreferencesUser.getInt(MenuMainActivity.ID, 0);
        System.out.println("---> ID: " + id);
        saveButton = findViewById(R.id.buttonSave);
        user = SingletonManager.getInstance(getApplicationContext()).getUser(id);

        etUsername = findViewById(R.id.etProfileUsername);
        etEmail = findViewById(R.id.etProfileEmail);
        etAddress = findViewById(R.id.etProfileAddress);
        etNif = findViewById(R.id.etProfileNif);
        etPhone = findViewById(R.id.etProfilePhone);
        loadProfile();
    }

    private void loadProfile() {
        etUsername.setText(user.getUsername());
        etEmail.setText(user.getEmail());
        etAddress.setText(user.getAddress());
        etNif.setText("" + user.getNif());
        etPhone.setText("" + user.getPhone());
    }
}