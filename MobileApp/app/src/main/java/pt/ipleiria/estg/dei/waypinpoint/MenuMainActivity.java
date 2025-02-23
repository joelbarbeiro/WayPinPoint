package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.DELETE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EMAIL;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ENDPOINT_USER;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.NO_TOKEN;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.PICK_IMAGE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.PROFILE_PIC;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.SNACKBAR_MESSAGE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.TOKEN;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.USER_DATA;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.checkAndRequestCameraPermission;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getApiHost;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getImgUriUser;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getUserId;

import android.app.Activity;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

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

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.bumptech.glide.Glide;
import com.google.android.material.navigation.NavigationView;
import com.google.android.material.snackbar.Snackbar;

import Model.SingletonManager;
import Model.User;
import Model.WaypinpointDbHelper;
import pt.ipleiria.estg.dei.waypinpoint.utils.ImageSender;

public class MenuMainActivity extends AppCompatActivity implements NavigationView.OnNavigationItemSelectedListener {

    private ImageView photoProfile;
    private DrawerLayout drawer;
    private NavigationView navigationView;
    private String email;
    private FragmentManager fragmentManager;
    private int id;
    private User user;
    private String apiHost, role;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu_main);

        id = getUserId(getApplicationContext());
        user = SingletonManager.getInstance(getApplicationContext()).getUser(id);
        String profilePic = getImgUriUser(getApplicationContext()) + user.getId() + "/" + user.getPhoto();
        role = user.getRole();

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
        loadHeader(sharedPreferencesUser, profilePic);
        navigationView.setNavigationItemSelectedListener(this);

        Menu menu = navigationView.getMenu();
        MenuItem employeeSection = menu.findItem(R.id.navQrCode);
        MenuItem supplierSection = menu.findItem(R.id.navMyActivities);

        supplierSection.setVisible("supplier".equals(role));
        employeeSection.setVisible(!"client".equals(role));
        loadDefaultFragment();
    }

    private void loadDefaultFragment() {
        Fragment fragment = new ListActivitiesFragment();
        fragmentManager.beginTransaction().replace(R.id.contentFragment, fragment).commit();
    }

    private void loadHeader(SharedPreferences sharedPreferencesUser, String profilePic) {
        email = getIntent().getStringExtra(EMAIL);

        if (email != null) {
            SharedPreferences.Editor editorUser = sharedPreferencesUser.edit();
            editorUser.putString(EMAIL, email);
            editorUser.apply();
        } else {
            email = sharedPreferencesUser.getString(EMAIL, getString(R.string.error_no_email_provided));
        }

        View hView = navigationView.getHeaderView(0);
        TextView nav_tvEmail = hView.findViewById(R.id.headerMenuTextViewEmail);
        nav_tvEmail.setText(email);

        ImageView profileImageView = hView.findViewById(R.id.profilePhoto);

        if (profilePic == null || profilePic.isEmpty() || profilePic.contains("None")) {
            Glide.with(this)
                    .load(R.drawable.ic_default_profile)
                    .placeholder(R.drawable.ic_default_profile)
                    .error(R.drawable.ic_default_profile)
                    .into(profileImageView);
        } else {
            Glide.with(this)
                    .load(profilePic)
                    .override(600, 600)
                    .circleCrop()
                    .into(profileImageView);
        }
        profileImageView.setOnClickListener(v -> {
            Intent intent = new Intent(Intent.ACTION_PICK, MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
            startActivityForResult(intent, PICK_IMAGE);
        });
    }


    @Override
    public boolean onNavigationItemSelected(@NonNull MenuItem item) {
        Fragment fragment = null;
        SharedPreferences sharedPreferencesUser = getSharedPreferences(USER_DATA, MODE_PRIVATE);
        if (item.getItemId() == R.id.navMyProfile) {
            Intent intent = new Intent(this, MyProfileActivity.class);
            startActivityForResult(intent, EDIT);
        }
        if (item.getItemId() == R.id.navListActivities) {
            fragment = new ListActivitiesFragment();
            fragmentManager.beginTransaction()
                    .addToBackStack(null)
                    .replace(R.id.contentFragment, fragment).commit();
        }
        if (item.getItemId() == R.id.navMyActivities) {
            fragment = new MyActivitiesFragment();
            fragmentManager.beginTransaction()
                    .addToBackStack(null)
                    .replace(R.id.contentFragment, fragment).commit();
        }
        if (item.getItemId() == R.id.navMyInvoices) {
            fragment = new ListInvoicesFragment();
            fragmentManager.beginTransaction()
                    .addToBackStack(null)
                    .replace(R.id.contentFragment, fragment).commit();
        }
        if (item.getItemId() == R.id.navLogout) {
            dialogLogout(sharedPreferencesUser);
        }
        if (item.getItemId() == R.id.navQrCode) {
            checkAndRequestCameraPermission(getApplicationContext(), MenuMainActivity.this);
            Intent intent = new Intent(this, QRCodeScannerActivity.class);
            startActivity(intent);
        }
        drawer.closeDrawer(GravityCompat.START);
        if (fragment != null)
            fragmentManager.beginTransaction().replace(R.id.contentFragment, fragment).commit();
        return true;
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        if (resultCode == Activity.RESULT_OK) {
            if (requestCode == EDIT) {
                if (data.getIntExtra(OP_CODE, 0) == DELETE) {
                    SharedPreferences sharedPreferencesUser = getSharedPreferences(USER_DATA, MODE_PRIVATE);
                    WaypinpointDbHelper waypinpointDbHelper = new WaypinpointDbHelper(getApplicationContext());
                    waypinpointDbHelper.removeAllUsersDb();
                    SharedPreferences.Editor editorUser = sharedPreferencesUser.edit();
                    editorUser.putString(TOKEN, NO_TOKEN);
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
                Uri imageUri = data.getData();
                id = getUserId(getApplicationContext());
                apiHost = getApiHost(getApplicationContext());

                ImageSender imageSender = new ImageSender(getApplicationContext());
                imageSender.sendImageToServer(apiHost, ENDPOINT_USER, id, imageUri, 600,
                        new Response.Listener<String>() {
                            @Override
                            public void onResponse(String response) {
                                View rootview = findViewById(R.id.drawerLayout);
                                Snackbar.make(rootview, R.string.menu_main_pic_edited, Snackbar.LENGTH_SHORT).show();
                            }
                        }, new Response.ErrorListener() {
                            @Override
                            public void onErrorResponse(VolleyError error) {
                                View rootview = findViewById(R.id.drawerLayout);
                                Snackbar.make(rootview, R.string.error_default, Snackbar.LENGTH_SHORT).show();
                            }
                        }
                );
                // Save the selected image URI for future use
                SharedPreferences sharedPreferences = getSharedPreferences(USER_DATA, MODE_PRIVATE);
                SharedPreferences.Editor editor = sharedPreferences.edit();
                editor.putString(PROFILE_PIC, imageUri.toString());
                editor.apply();
                View hView = navigationView.getHeaderView(0);
                ImageView photoProfile = hView.findViewById(R.id.profilePhoto);

                Glide.with(this)
                        .load(imageUri)
                        .placeholder(R.drawable.ic_default_profile)
                        .error(R.drawable.ic_default_profile)
                        .into(photoProfile);
                user = SingletonManager.getInstance(getApplicationContext()).getUser(id);
                user.setPhoto(String.valueOf(imageUri));
                SingletonManager.getInstance(getApplicationContext()).editUserApi(user, getApplicationContext());
            }
        }
        super.onActivityResult(requestCode, resultCode, data);
    }

    private void dialogLogout(SharedPreferences sharedPreferencesUser) {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle(R.string.menu_logout_label);
        builder.setMessage(R.string.dialog_logout_message);
        builder.setPositiveButton(R.string.yes_string, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        SingletonManager.getInstance(getApplicationContext()).removeAllUsers();
                        SharedPreferences.Editor editorUser = sharedPreferencesUser.edit();
                        editorUser.putString(TOKEN, NO_TOKEN);
                        editorUser.apply();
                        Intent intent = new Intent(MenuMainActivity.this, LoginActivity.class);
                        // Clear the back stack to prevent the user from going back to the previous activity after logging out
                        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                        startActivity(intent);
                        finish();
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
    public void onBackPressed() {
        FragmentManager fragmentManager = getSupportFragmentManager();
        if (fragmentManager.getBackStackEntryCount() > 0) {
            fragmentManager.popBackStack(); // Go to the previous fragment
        } else {
            super.onBackPressed(); // Close the app
        }
    }
}