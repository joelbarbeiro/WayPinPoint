package pt.ipleiria.estg.dei.waypinpoint;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Patterns;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import Listeners.UserListener;
import Model.SingletonManager;
import Model.User;

public class MyProfileActivity extends AppCompatActivity implements UserListener {

    private Button saveButton;

    private User user;

    private String username, address, email, password, photo;
    private int nif, phone;

    private EditText etEmail, etUsername, etAddress, etNif, etPhone;

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

    private static boolean isUsernameValid(String username) {
        if (username != null) {
            return username.length() >= 2;
        }
        return false;
    }

    private static boolean isEmailValid(String email) {
        if (email != null) {
            return Patterns.EMAIL_ADDRESS.matcher(email).matches();
        }
        return false;
    }

    private static boolean isPhoneValid(int phone) {
        return phone != 0;
    }

    private static boolean isNifValid(int nif) {
        return nif <= 999999999 && nif >= 100000000;
    }

    private int safeParseInt(String value) {
        if (value == null || value.trim().isEmpty()) {
            return 0;
        }
        return Integer.parseInt(value);
    }

    public void onClickSave(View view) {
        boolean isNifValid, isEmailValid, isUsernameValid, isPhoneValid;

        username = etUsername.getText().toString();
        email = etEmail.getText().toString();
        address = etAddress.getText().toString();
        phone = safeParseInt(etPhone.getText().toString());
        nif = safeParseInt(etNif.getText().toString());

        isEmailValid = isEmailValid(email);
        isNifValid = isNifValid(nif);
        isUsernameValid = isUsernameValid(username);
        isPhoneValid = isPhoneValid(phone);

        if (username.isEmpty() || email.isEmpty() || address.isEmpty()) {
            Toast.makeText(this, "You have to fill all the fields", Toast.LENGTH_SHORT).show();
            return;
        }

        if (!isPhoneValid && !isNifValid) {
            Toast.makeText(this, "Nif/Phone has to be valid", Toast.LENGTH_SHORT).show();
            return;
        }

        if (isNifValid && isEmailValid && isUsernameValid && isPhoneValid) {
            user.setUsername(username);
            user.setEmail(email);
            user.setAddress(address);
            user.setNif(nif);
            user.setPhone(phone);
            SingletonManager.getInstance(getApplicationContext()).editUserApi(user, getApplicationContext());
        }
        SingletonManager.getInstance(getApplicationContext()).setUserListener(this);

    }

    @Override
    public void onValidateOperation(int op) {
        Intent intent = new Intent();
        intent.putExtra(LoginActivity.OP_CODE, op);
        setResult(RESULT_OK, intent);
        finish();
    }
}