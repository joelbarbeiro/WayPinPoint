package pt.ipleiria.estg.dei.waypinpoint;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.util.Patterns;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.snackbar.Snackbar;

public class LoginActivity extends AppCompatActivity {

    private EditText etEmail;
    private EditText etPassword;

    public static final int REGISTER = 100;
    public static final String OP_CODE = "DETAIL_OPERATION";


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        etEmail = findViewById(R.id.textviewUsername);
        etPassword = findViewById(R.id.registerTvPassword);
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
        isEmailValid = isEmailValid(etEmail.getText().toString());
        isPasswordValid = isPasswordValid(String.valueOf(etPassword.getText()));

        Intent intent = new Intent(this, MenuMainActivity.class);

        if (isEmailValid && isPasswordValid) {
            intent.putExtra(MenuMainActivity.EMAIL, etEmail.getText().toString());
            startActivity(intent);
        } else {
            Toast.makeText(this, R.string.login_error_message, Toast.LENGTH_SHORT).show();
        }
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
}