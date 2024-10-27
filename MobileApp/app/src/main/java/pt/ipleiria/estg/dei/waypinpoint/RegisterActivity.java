package pt.ipleiria.estg.dei.waypinpoint;

import android.content.Intent;
import android.os.Bundle;
import android.util.Patterns;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import java.util.Objects;

public class RegisterActivity extends AppCompatActivity {

    public static final String EMAIL = "EMAIL";
    private String email = "";
    private EditText etEmail;
    private EditText etPassword;
    private EditText etConfirmPassword;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);
        etEmail = findViewById(R.id.registerTvEmail);
        etPassword = findViewById(R.id.registerTvPassword);
        etConfirmPassword = findViewById(R.id.registerConfirmPassword);

        loadEmail();
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

    private static boolean isConfirmPasswordEqual(String password, String secondPassword){
        return Objects.equals(password, secondPassword);
    }

    private void loadEmail() {
        email = getIntent().getStringExtra(EMAIL);
        etEmail.setText(email);
    }

    public void onClickRegister(View view) {
        boolean isEmailValid, isPasswordValid, isConfirmPasswordEqual;
        isEmailValid = isEmailValid(etEmail.getText().toString());
        isPasswordValid = isPasswordValid(String.valueOf(etPassword.getText()));
        isConfirmPasswordEqual= isConfirmPasswordEqual(etPassword.getText().toString(),etConfirmPassword.getText().toString());
        Intent intent = new Intent(this, LoginActivity.class);
        if (isEmailValid && isPasswordValid && isConfirmPasswordEqual) {
            startActivity(intent);
        } else {
            Toast.makeText(this, "Something went wrong", Toast.LENGTH_SHORT).show();
        }
    }
}