package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ADD;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.APIHOST;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EMAIL;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.NO_TOKEN;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.SNACKBAR_MESSAGE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.TOKEN;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.USER_DATA;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.checkAndRequestPermissions;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getApiHost;

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

import Listeners.LoginListener;
import Model.SingletonManager;

public class LoginActivity extends AppCompatActivity implements LoginListener {

    private EditText etEmail;
    private EditText etPassword;

    private String email, password;
    private String apiHost = null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_login);
        etEmail = findViewById(R.id.textviewUsername);
        etPassword = findViewById(R.id.registerTvPassword);

        FloatingActionButton fabApiHost;
        checkAndRequestPermissions(getApplicationContext(), LoginActivity.this);

        fabApiHost = findViewById(R.id.fabApiHostnameConfig);
        fabApiHost.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(getApplicationContext(), ApiHostnameSetupActivity.class);
                startActivity(intent);
            }
        });

        if (getApiHost(getApplicationContext()) == null) {
            View rootView = findViewById(R.id.loginView);
            Snackbar.make(rootView, R.string.Api_hostname_message_before_login, Snackbar.LENGTH_SHORT).show();

            int toastDuration = 1000;
            new Handler(getMainLooper()).postDelayed(() -> {
                Intent intent = new Intent(getApplicationContext(), ApiHostnameSetupActivity.class);
                startActivity(intent);
                finish();
            }, toastDuration);

        } else {
            apiHost = getApiHost(getApplicationContext());
            View rootView = findViewById(R.id.loginView);
            Snackbar.make(rootView, "Hostname: " + apiHost, Snackbar.LENGTH_SHORT).show();
        }

        if (isTokenValid()) {
            SharedPreferences sharedPreferences = getApplicationContext().getSharedPreferences(USER_DATA, Context.MODE_PRIVATE);
            SharedPreferences.Editor editor = sharedPreferences.edit();
            editor.apply();

            Intent intent = new Intent(getApplicationContext(), MenuMainActivity.class);
            intent.putExtra(EMAIL, sharedPreferences.getString(EMAIL, getString(R.string.error_no_email_provided)));
            startActivity(intent);
            finish();
        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        Intent intent = getIntent();
        if (intent != null && intent.hasExtra(SNACKBAR_MESSAGE)) {
            String message = intent.getStringExtra(SNACKBAR_MESSAGE);
            if (message != null) {
                View rootView = findViewById(android.R.id.content);
                Snackbar.make(rootView, message, Snackbar.LENGTH_SHORT).show();
                //REMOVER DO INTENT PARA NAO MOSTRAR OUTRA VEZ
                intent.removeExtra(SNACKBAR_MESSAGE);
            }
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
            if (requestCode == ADD) {
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
        intent.putExtra(APIHOST, apiHost);

        if (isEmailValid) {
            intent.putExtra(EMAIL, etEmail.getText().toString());
            startActivityForResult(intent, ADD);
        } else {
            startActivityForResult(intent, ADD);
        }
    }

    public boolean isTokenValid() {
        SharedPreferences sharedPreferences = getApplicationContext().getSharedPreferences(USER_DATA, Context.MODE_PRIVATE);
        if (sharedPreferences.getString(TOKEN, NO_TOKEN).matches(NO_TOKEN)) {
            System.out.println(getString(R.string.error_invalid_token));
            return false;
        } else {
            return true;
        }
    }

    @Override
    public void onValidateLogin(String token) {
        Intent intent = new Intent(this, MenuMainActivity.class);
        intent.putExtra(EMAIL, etEmail.getText().toString());
        startActivity(intent);
    }

    @Override
    public void onErrorLogin(String errorMessage) {
        Toast.makeText(this, errorMessage, Toast.LENGTH_SHORT).show();
    }

}