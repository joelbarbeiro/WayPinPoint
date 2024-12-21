package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DELETE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EMAIL;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.PICK_IMAGE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.SNACKBAR_MESSAGE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.TOKEN;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.USER_DATA;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getUserId;

import android.app.Activity;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.ActionBarDrawerToggle;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;

import com.google.android.material.navigation.NavigationView;
import com.google.android.material.snackbar.Snackbar;

import Model.SingletonManager;
import Model.UserDbHelper;
import pt.ipleiria.estg.dei.waypinpoint.utils.Utilities;

public class MenuMainActivity extends AppCompatActivity implements NavigationView.OnNavigationItemSelectedListener {

    private ImageView photoProfile;
    private DrawerLayout drawer;
    private NavigationView navigationView;
    private String email;
    private FragmentManager fragmentManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu_main);
        SharedPreferences sharedPreferencesUser = getSharedPreferences(USER_DATA, MODE_PRIVATE);
        fragmentManager = getSupportFragmentManager();
        Toolbar toolbar = findViewById(R.id.toolbar);
        photoProfile = findViewById(R.id.profilePhoto);
        setSupportActionBar(toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayShowTitleEnabled(false);
        }
        drawer = findViewById(R.id.drawerLayout);
        navigationView = findViewById(R.id.navView);
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(this, drawer, toolbar, R.string.ndOpen, R.string.ndClose);
        toggle.syncState();
        drawer.addDrawerListener(toggle);
        loadHeader(sharedPreferencesUser);
        navigationView.setNavigationItemSelectedListener(this);
    }

    private void loadHeader(SharedPreferences sharedPreferencesUser) {
        email = getIntent().getStringExtra(EMAIL).toString();

        if (email != null) {
            SharedPreferences.Editor editorUser = sharedPreferencesUser.edit();
            editorUser.putString(EMAIL, email);
            editorUser.apply();
        } else {
            email = sharedPreferencesUser.getString(EMAIL, "No Email Provided");
        }

        View hView = navigationView.getHeaderView(0);
        TextView nav_tvEmail = hView.findViewById(R.id.headerMenuTextViewEmail);
        nav_tvEmail.setText(email);

        ImageView profileImageView = hView.findViewById(R.id.profilePhoto);
        profileImageView.setOnClickListener(v -> {
            Intent intent = new Intent(Intent.ACTION_PICK, MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
            startActivityForResult(intent, PICK_IMAGE);
        });
    }


    @Override
    public boolean onNavigationItemSelected(@NonNull MenuItem item) {
        Fragment fragment = null;
        SharedPreferences sharedPreferencesUser = getSharedPreferences("USER_DATA", MODE_PRIVATE);
        if (item.getItemId() == R.id.navMyProfile) {
            Intent intent = new Intent(this, MyProfileActivity.class);
            startActivityForResult(intent, EDIT);
        }
        if (item.getItemId() == R.id.navMyActivities) System.out.println("--> My Activities");
        if (item.getItemId() == R.id.navMyReceipts) System.out.println("--> My Receipts");
        if (item.getItemId() == R.id.navChangeHost) System.out.println("--> Change Host");
        if (item.getItemId() == R.id.navLogout) {
            dialogLogout(sharedPreferencesUser);
        }
        if (item.getItemId() == R.id.navQrCode) System.out.println("--> Validate QR-Code");
        drawer.closeDrawer(GravityCompat.START);
        if (fragment != null)
            fragmentManager.beginTransaction().replace(R.id.contentFragment, fragment).commit();
        return true;
    }

    private void dialogLogout(SharedPreferences sharedPreferencesUser) {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle(R.string.menu_logout_label);
        builder.setMessage(R.string.dialog_logout_message);
        builder.setPositiveButton(R.string.dialog_yes, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        UserDbHelper userDbHelper = new UserDbHelper(getApplicationContext());
                        userDbHelper.removeAllUsersDb();
                        SharedPreferences.Editor editorUser = sharedPreferencesUser.edit();
                        editorUser.putString(TOKEN, "NO TOKEN");
                        editorUser.apply();
                        Intent intent = new Intent(MenuMainActivity.this, LoginActivity.class);
                        startActivity(intent);
                        System.out.println("--> Logout");
                    }
                })
                .setNegativeButton(R.string.no_string, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        dialog.cancel();
                    }
                })
                .setIcon(R.drawable.ic_logout_menu)
                .show();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_cart, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(@NonNull MenuItem item) {
        if (item.getItemId() == R.id.navCart) {
            Intent intent = new Intent(this, CartActivity.class);
            startActivity(intent);
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        if (resultCode == Activity.RESULT_OK) {
            if (requestCode == EDIT) {
                if (data.getIntExtra(OP_CODE, 0) == DELETE) {
                    SharedPreferences sharedPreferencesUser = getSharedPreferences(USER_DATA, MODE_PRIVATE);
                    UserDbHelper userDbHelper = new UserDbHelper(getApplicationContext());
                    userDbHelper.removeAllUsersDb();
                    SharedPreferences.Editor editorUser = sharedPreferencesUser.edit();
                    editorUser.putString(TOKEN, "NO TOKEN");
                    editorUser.apply();
                    Intent intent = new Intent(MenuMainActivity.this, LoginActivity.class);
                    intent.putExtra(SNACKBAR_MESSAGE, R.string.my_profile_deleted);
                    startActivity(intent);
                    finish();
                } else {
                    View rootview = findViewById(R.id.drawerLayout);
                    Snackbar.make(rootview, R.string.menu_main_user_edited, Snackbar.LENGTH_SHORT).show();
                }
            }
            if (requestCode == PICK_IMAGE && data != null) {
                int id = getUserId(getApplicationContext());
                Uri imageUri = data.getData();

                // Get the file path from the Uri
                String filePath = getPathFromUri(imageUri);

                if (filePath != null) {
                    String apiHost = Utilities.getApiHost(getApplicationContext());
                    SingletonManager.getInstance(getApplicationContext()).addPhotoApi(apiHost, id, filePath, getApplicationContext());
                } else {
                    Toast.makeText(getApplicationContext(), "Unable to get the file path", Toast.LENGTH_SHORT).show();
                }
            }
        }
        super.onActivityResult(requestCode, resultCode, data);
    }

    private String getPathFromUri(Uri uri) {
        String[] projection = {MediaStore.Images.Media.DATA};
        Cursor cursor = getContentResolver().query(uri, projection, null, null, null);

        if (cursor != null) {
            int columnIndex = cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
            cursor.moveToFirst();
            String filePath = cursor.getString(columnIndex);
            cursor.close();
            return filePath;
        }

        return null;
    }
}