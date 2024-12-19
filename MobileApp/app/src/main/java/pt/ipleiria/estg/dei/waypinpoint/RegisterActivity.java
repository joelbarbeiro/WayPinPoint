package pt.ipleiria.estg.dei.waypinpoint;

import static Model.User.DEFAULT_IMG;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.APIHOST;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EMAIL;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;

import android.content.Intent;
import android.os.Bundle;
import android.util.Patterns;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import java.util.Objects;

import Listeners.UserListener;
import Model.SingletonManager;
import Model.User;

public class RegisterActivity extends AppCompatActivity implements UserListener {


    private User user;
    private String apiHost, username, address, email, password, photo = "";
    private int nif, phone;
    private EditText etConfirmPassword, etPassword, etEmail, etUsername, etAddress, etNif, etPhone;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);

        etUsername = findViewById(R.id.registerTvUsername);
        etEmail = findViewById(R.id.registerTvEmail);
        etPassword = findViewById(R.id.registerTvPassword);
        etConfirmPassword = findViewById(R.id.registerConfirmPassword);
        etAddress = findViewById(R.id.registerTvAddress);
        etPhone = findViewById(R.id.registerTvPhone);
        etNif = findViewById(R.id.registerTvNif);

        loadEmail();
        loadApiHost();
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

    private static boolean isPasswordValid(String password) {
        if (password != null) {
            return password.length() >= 4;
        }
        return false;
    }

    private static boolean isConfirmPasswordEqual(String password, String secondPassword) {
        return Objects.equals(password, secondPassword);
    }

    private static boolean isPhoneValid(int phone) {
        return phone != 0;
    }

    private static boolean isNifValid(int nif) {
        return nif <= 999999999 && nif >= 100000000;
    }

    private void loadEmail() {
        email = getIntent().getStringExtra(EMAIL);
        etEmail.setText(email);
    }

    private int safeParseInt(String value) {
        if (value == null || value.trim().isEmpty()) {
            return 0;
        }
        return Integer.parseInt(value);
    }

    private void loadApiHost() {
        apiHost = getIntent().getStringExtra(APIHOST);
    }

    public void onClickRegister(View view) {
        boolean isNifValid, isEmailValid, isPasswordValid, isConfirmPasswordEqual, isUsernameValid, isPhoneValid;

        username = etUsername.getText().toString();
        email = etEmail.getText().toString();
        password = etPassword.getText().toString();
        address = etAddress.getText().toString();
        phone = safeParseInt(etPhone.getText().toString());
        nif = safeParseInt(etNif.getText().toString());

        isEmailValid = isEmailValid(email);
        isPasswordValid = isPasswordValid(password);
        isConfirmPasswordEqual = isConfirmPasswordEqual(password, etConfirmPassword.getText().toString());
        isNifValid = isNifValid(nif);
        isUsernameValid = isUsernameValid(username);
        isPhoneValid = isPhoneValid(phone);

        if (username.isEmpty() || email.isEmpty() || password.isEmpty() || address.isEmpty()) {
            Toast.makeText(this, R.string.my_profile_fields_warning, Toast.LENGTH_SHORT).show();
            return;
        }

        if (!isPhoneValid && !isNifValid) {
            Toast.makeText(this, R.string.my_profile_nif_phone_warning, Toast.LENGTH_SHORT).show();
            return;
        }

        if (isNifValid && isEmailValid && isPasswordValid && isConfirmPasswordEqual && isUsernameValid && isPhoneValid) {
            user = new User(
                    0,
                    username,
                    email,
                    password,
                    address,
                    phone,
                    nif,
                    DEFAULT_IMG,
                    0,
                    ""
            );
            SingletonManager.getInstance(getApplicationContext()).addUserApi(apiHost, user, getApplicationContext());
        } else {
            Toast.makeText(this, R.string.error_default, Toast.LENGTH_SHORT).show();
        }
        SingletonManager.getInstance(getApplicationContext()).setUserListener(this);
    }

    @Override
    public void onValidateOperation(int op) {
        Intent intent = new Intent();
        intent.putExtra(OP_CODE, op);
        setResult(RESULT_OK, intent);
        finish();
    }
}