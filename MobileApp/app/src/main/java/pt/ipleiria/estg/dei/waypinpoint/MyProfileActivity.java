package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getApiHost;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getUserId;

import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.util.Patterns;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import Listeners.UserListener;
import Model.SingletonManager;
import Model.User;

public class MyProfileActivity extends AppCompatActivity implements UserListener {

    private Button saveButton, deleteButton;

    private User user;

    private String username, address, email, password, photo;
    private int nif, phone;
    private String apiHost = null;

    private EditText etEmail, etUsername, etAddress, etNif, etPhone;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_my_profile);

        int id = getUserId(getApplicationContext());
        System.out.println("--->USER ID: " + id);

        etUsername = findViewById(R.id.etProfileUsername);
        etEmail = findViewById(R.id.etProfileEmail);
        etAddress = findViewById(R.id.etProfileAddress);
        etNif = findViewById(R.id.etProfileNif);
        etPhone = findViewById(R.id.etProfilePhone);
        saveButton = findViewById(R.id.buttonSave);
        deleteButton = findViewById(R.id.buttonDelete);

        user = SingletonManager.getInstance(getApplicationContext()).getUser(id);
        apiHost = getApiHost(getApplicationContext());

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
            Toast.makeText(this, R.string.my_profile_fields_warning, Toast.LENGTH_SHORT).show();
            return;
        }

        if (!isPhoneValid && !isNifValid) {
            Toast.makeText(this, R.string.my_profile_nif_phone_warning, Toast.LENGTH_SHORT).show();
            return;
        }

        if (isNifValid && isEmailValid && isUsernameValid && isPhoneValid) {
            user.setUsername(username);
            user.setEmail(email);
            user.setAddress(address);
            user.setNif(nif);
            user.setPhone(phone);
            SingletonManager.getInstance(getApplicationContext()).editUserApi(apiHost, user, getApplicationContext());
        }
        SingletonManager.getInstance(getApplicationContext()).setUserListener(this);

    }

    public void onClickDelete(View view) {
        dialogRemoveUser();
    }

    private void dialogRemoveUser() {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle(R.string.dialog_delete_title);
        builder.setMessage(R.string.dialog_delete_message);
        builder.setPositiveButton(R.string.dialog_yes, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        SingletonManager.getInstance(getApplicationContext()).removeUserApi(apiHost, user, getApplicationContext());
                        SingletonManager.getInstance(getApplicationContext()).setUserListener(MyProfileActivity.this);
                    }
                })
                .setNegativeButton(R.string.no_string, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        dialog.cancel();
                    }
                })
                .setIcon(R.drawable.ic_delete)
                .show();
    }

    @Override
    public void onValidateOperation(int op) {
        Intent intent = new Intent();
        intent.putExtra(OP_CODE, op);
        setResult(RESULT_OK, intent);
        finish();
    }
}