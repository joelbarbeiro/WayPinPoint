package pt.ipleiria.estg.dei.waypinpoint;

import static Model.User.DEFAULT_IMG;

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

    public static final String EMAIL = "EMAIL";
    private User user;
    private String username,address,email,password,photo = "";
    private int nif,phone;
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

    private static boolean isNifValid(int nif){
        if(nif < 999999999 && nif > 111111111){
            return true;
        } else {
            return false;
        }
    }

    private void loadEmail() {
        email = getIntent().getStringExtra(EMAIL);
        etEmail.setText(email);
    }

    public void onClickRegister(View view) {
        boolean isNifValid ,isEmailValid, isPasswordValid, isConfirmPasswordEqual ;

        isEmailValid = isEmailValid(etEmail.getText().toString());
        isPasswordValid = isPasswordValid(String.valueOf(etPassword.getText()));
        isConfirmPasswordEqual= isConfirmPasswordEqual(etPassword.getText().toString(),etConfirmPassword.getText().toString());
        isNifValid = isNifValid(Integer.parseInt(etNif.getText().toString()));



        if (isNifValid && isEmailValid && isPasswordValid && isConfirmPasswordEqual) {
            System.out.println("#AQUI");
            user = new User(
                    0,
                    etUsername.getText().toString(),
                    etEmail.getText().toString(),
                    etPassword.getText().toString(),
                    etAddress.getText().toString(),
                    Integer.parseInt(etPhone.getText().toString()),
                    Integer.parseInt(etNif.getText().toString()),
                    DEFAULT_IMG,
                    0
            );
            SingletonManager.getInstance(getApplicationContext()).addUserApi(user, getApplicationContext());
        } else {
            Toast.makeText(this, "Something went wrong", Toast.LENGTH_SHORT).show();
        }
        SingletonManager.getInstance(getApplicationContext()).setUserListener(this);
    }

    @Override
    public void onValidateRegister(int op) {
        Intent intent = new Intent();
        intent.putExtra(LoginActivity.OP_CODE, op);
        setResult(RESULT_OK, intent);
        finish();
    }
}