package pt.ipleiria.estg.dei.waypinpoint;

import android.content.Intent;
import android.os.Bundle;
import android.util.Patterns;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class LoginActivity extends AppCompatActivity {

    private EditText etEmail;
    private EditText etPassword;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        etEmail = findViewById(R.id.textviewUsername);
        etPassword = findViewById(R.id.textviewPassword);
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

    public void onClickLogin(View view) {
        boolean isEmailValid, isPasswordValid;
        isEmailValid = isEmailValid(etEmail.getText().toString());
        isPasswordValid = isPasswordValid(String.valueOf(etPassword.getText()));

        Intent intent = new Intent(this, MenuMainActivity.class);

        if (isEmailValid && isPasswordValid) {
            intent.putExtra(MenuMainActivity.EMAIL, etEmail.getText().toString());
            startActivity(intent);
        } else {
            Toast.makeText(this, "Something went wrong", Toast.LENGTH_SHORT).show();
        }
    }
}