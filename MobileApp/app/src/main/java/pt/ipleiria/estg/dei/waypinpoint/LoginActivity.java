package pt.ipleiria.estg.dei.waypinpoint;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Patterns;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.snackbar.Snackbar;

import Listeners.LoginListener;
import Model.SingletonManager;

public class LoginActivity extends AppCompatActivity implements LoginListener {

    private EditText etEmail;
    private EditText etPassword;

    public static final int REGISTER = 100;
    public static final String OP_CODE = "DETAIL_OPERATION";
    private String email, password;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        etEmail = findViewById(R.id.textviewUsername);
        etPassword = findViewById(R.id.registerTvPassword);

        if (isTokenValid()) {
            SharedPreferences sharedPreferences = getApplicationContext().getSharedPreferences("USER_DATA", Context.MODE_PRIVATE);
            SharedPreferences.Editor editor = sharedPreferences.edit();
            editor.apply();

            Intent intent = new Intent(getApplicationContext(), MenuMainActivity.class);
            intent.putExtra(MenuMainActivity.EMAIL, sharedPreferences.getString("EMAIL", "No Email Provided"));
            startActivity(intent);
            finish();
        }
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

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        if (resultCode == Activity.RESULT_OK) {
            if (requestCode == REGISTER) {
                View rootView = findViewById(R.id.loginView);
                Snackbar.make(rootView, R.string.login_register_success_message, Snackbar.LENGTH_SHORT).show();
            }
        }
        super.onActivityResult(requestCode, resultCode, data);
    }

    public void onClickLogin(View view) {
        boolean isEmailValid, isPasswordValid;

        email = etEmail.getText().toString();
        password = String.valueOf(etPassword.getText());
        isEmailValid = isEmailValid(email);
        isPasswordValid = isPasswordValid(password);

        if (isEmailValid && isPasswordValid) {
            SingletonManager.getInstance(getApplicationContext()).loginAPI(email, password, getApplicationContext(), this);
        } else {
            Toast.makeText(this, R.string.login_error_message, Toast.LENGTH_SHORT).show();
        }
        SingletonManager.getInstance(getApplicationContext()).setLoginListener(this);
    }

    public void onClickRegisterLabel(View view) {
        boolean isEmailValid;
        isEmailValid = isEmailValid(etEmail.getText().toString());
        Intent intent = new Intent(this, RegisterActivity.class);

        if (isEmailValid) {
            intent.putExtra(RegisterActivity.EMAIL, etEmail.getText().toString());
            startActivityForResult(intent, REGISTER);
        } else {
            startActivityForResult(intent, REGISTER);
        }
    }

    public boolean isTokenValid() {
        SharedPreferences sharedPreferences = getApplicationContext().getSharedPreferences("USER_DATA", Context.MODE_PRIVATE);
        System.out.println("TOKEN: --->" + sharedPreferences.getString("TOKEN", "TOKEN"));
        if (sharedPreferences.getString("TOKEN", "TOKEN").matches("TOKEN")) {
            System.out.println("--->token invalido, não faz login automatico");
            return false;
        } else {
            return true;
        }
    }

    @Override
    public void onValidateLogin(String token) {
        Intent intent = new Intent(this, MenuMainActivity.class);
        intent.putExtra(MenuMainActivity.EMAIL, etEmail.getText().toString());
        startActivity(intent);
    }

    @Override
    public void onErrorLogin(String errorMessage) {
        Toast.makeText(this, errorMessage, Toast.LENGTH_SHORT).show();
    }
}