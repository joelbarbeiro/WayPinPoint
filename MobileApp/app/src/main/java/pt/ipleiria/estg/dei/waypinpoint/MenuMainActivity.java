package pt.ipleiria.estg.dei.waypinpoint;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.appcompat.app.ActionBarDrawerToggle;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import com.google.android.material.navigation.NavigationView;
import Model.UserDbHelper;

public class MenuMainActivity extends AppCompatActivity implements NavigationView.OnNavigationItemSelectedListener {

    public static final String EMAIL = "EMAIL";
    public static final String ID = "ID";
    private DrawerLayout drawer;
    private NavigationView navigationView;
    private String email;
    private FragmentManager fragmentManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu_main);
        SharedPreferences sharedPreferencesUser = getSharedPreferences("USER_DATA", MODE_PRIVATE);
        fragmentManager = getSupportFragmentManager();
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayShowTitleEnabled(false);
        }
        drawer = findViewById(R.id.drawerLayout);
        navigationView = findViewById(R.id.navView);
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(this, drawer, toolbar, R.string.ndOpen, R.string.ndClose);
        toggle.syncState();
        drawer.addDrawerListener(toggle);
        loadHeader( sharedPreferencesUser);
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
    }

    @Override
    public boolean onNavigationItemSelected(@NonNull MenuItem item) {
        Fragment fragment = null;
        SharedPreferences sharedPreferencesUser = getSharedPreferences("USER_DATA", MODE_PRIVATE);
        if (item.getItemId() == R.id.navMyProfile) {
            Intent intent = new Intent(this, MyProfileActivity.class);
            startActivity(intent);
        }
        if (item.getItemId() == R.id.navMyActivities) System.out.println("--> My Activities");
        if (item.getItemId() == R.id.navMyReceipts) System.out.println("--> My Receipts");
        if (item.getItemId() == R.id.navChangeHost) System.out.println("--> Change Host");
        if (item.getItemId() == R.id.navLogout) {
            UserDbHelper userDbHelper = new UserDbHelper(getApplicationContext());
            userDbHelper.removeAllUsersDb();
            SharedPreferences.Editor editorUser = sharedPreferencesUser.edit();
            editorUser.putString("TOKEN", "NO TOKEN");
            editorUser.apply();
            Intent intent = new Intent(this, LoginActivity.class);
            startActivity(intent);
            System.out.println("--> Logout");
        }
        if (item.getItemId() == R.id.navQrCode) System.out.println("--> Validate QR-Code");
        drawer.closeDrawer(GravityCompat.START);
        if (fragment != null)
            fragmentManager.beginTransaction().replace(R.id.contentFragment, fragment).commit();
        return true;
    }
}