package pt.ipleiria.estg.dei.waypinpoint;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.Handler;
import android.util.Patterns;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.android.material.snackbar.Snackbar;
import java.util.ArrayList;

import Listeners.LoginListener;
import Listeners.UsersListener;
import Model.SingletonManager;
import Model.User;

public class LoginActivity extends AppCompatActivity implements LoginListener, UsersListener {

    private EditText etEmail;
    private EditText etPassword;

    public static final int REGISTER = 100;
    public static final String OP_CODE = "DETAIL_OPERATION";
    private String email, password;
    private String apiHost = null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_login);
        etEmail = findViewById(R.id.textviewUsername);
        etPassword = findViewById(R.id.registerTvPassword);

        FloatingActionButton fabApiHost;

        fabApiHost = findViewById(R.id.fabApiHostnameConfig);
        fabApiHost.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(getApplicationContext(), ApiHostnameSetupActivity.class);
                startActivity(intent);
            }
        });

        if (getApiHost() == null) {
            View rootView = findViewById(R.id.loginView);
            Snackbar.make(rootView, "Please config api hostname before login or register", Snackbar.LENGTH_SHORT).show();

            int toastDuration = 1000;
            new Handler(getMainLooper()).postDelayed(() -> {
                Intent intent = new Intent(getApplicationContext(), ApiHostnameSetupActivity.class);
                startActivity(intent);
                finish();
            }, toastDuration);

        } else {
            apiHost = getApiHost();
            View rootView = findViewById(R.id.loginView);
            Snackbar.make(rootView, "Hostname: " + apiHost, Snackbar.LENGTH_SHORT).show();
        }

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
            SingletonManager.getInstance(getApplicationContext()).loginAPI(apiHost, email, password, getApplicationContext(), this);
        } else {
            Toast.makeText(this, R.string.login_error_message, Toast.LENGTH_SHORT).show();
        }
        SingletonManager.getInstance(getApplicationContext()).setLoginListener(this);
    }

    public void onClickRegisterLabel(View view) {
        boolean isEmailValid;
        isEmailValid = isEmailValid(etEmail.getText().toString());
        Intent intent = new Intent(this, RegisterActivity.class);
        intent.putExtra(RegisterActivity.APIHOST, apiHost);

        if (isEmailValid) {
            intent.putExtra(RegisterActivity.EMAIL, etEmail.getText().toString());
            startActivityForResult(intent, REGISTER);
        } else {
            startActivityForResult(intent, REGISTER);
        }
    }

    public boolean isTokenValid() {
        SharedPreferences sharedPreferences = getApplicationContext().getSharedPreferences("USER_DATA", Context.MODE_PRIVATE);
        System.out.println("TOKEN: --->" + sharedPreferences.getString("TOKEN", "NO TOKEN"));
        if (sharedPreferences.getString("TOKEN", "NO TOKEN").matches("NO TOKEN")) {
            System.out.println(getString(R.string.error_invalid_token));
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

    @Override
    public void onRefreshUserList(ArrayList<User> usersList) {
        if (usersList != null) {
            Toast.makeText(this, "Users available", Toast.LENGTH_SHORT).show();
        } else {
            Toast.makeText(this, "No users available", Toast.LENGTH_SHORT).show();
        }
    }

    public String getApiHost() {
        SharedPreferences sharedPreferences = getSharedPreferences("API_HOSTNAME", Context.MODE_PRIVATE);
        System.out.println("--> Setted Host " + sharedPreferences.getString("API_HOSTNAME", null));
        return sharedPreferences.getString("API_HOSTNAME", null);
    }
}