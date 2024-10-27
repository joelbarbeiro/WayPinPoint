package pt.ipleiria.estg.dei.waypinpoint;

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

import com.google.android.material.navigation.NavigationView;

public class MenuMainActivity extends AppCompatActivity implements NavigationView.OnNavigationItemSelectedListener {

    public static final String EMAIL = "EMAIL";
    private DrawerLayout drawer;
    private NavigationView navigationView;
    private String email = "";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu_main);

        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        drawer = findViewById(R.id.drawerLayout);
        navigationView = findViewById(R.id.navView);
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(this, drawer, toolbar, R.string.ndOpen, R.string.ndClose);
        toggle.syncState();
        drawer.addDrawerListener(toggle);
        loadHeader();
        navigationView.setNavigationItemSelectedListener(this);
    }

    private void loadHeader() {
        email = getIntent().getStringExtra(EMAIL).toString();
        View hView = navigationView.getHeaderView(0);
        TextView nav_tvEmail = hView.findViewById(R.id.headerMenuTextViewEmail);
        nav_tvEmail.setText(email);
    }

    @Override
    public boolean onNavigationItemSelected(@NonNull MenuItem item) {
        if (item.getItemId() == R.id.navMyProfile) System.out.println("--> My Profile");
        if (item.getItemId() == R.id.navMyActivities) System.out.println("--> My Activities");
        if (item.getItemId() == R.id.navMyReceipts) System.out.println("--> My Receipts");
        if (item.getItemId() == R.id.navChangeHost) System.out.println("--> Change Host");
        if (item.getItemId() == R.id.navLogout) System.out.println("--> Logout");
        if (item.getItemId() == R.id.navQrCode) System.out.println("--> Validate QR-Code");
        drawer.closeDrawer(GravityCompat.START);
        return true;
    }
}