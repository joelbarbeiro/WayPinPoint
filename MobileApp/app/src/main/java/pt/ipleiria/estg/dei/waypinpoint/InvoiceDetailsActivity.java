package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ID_INVOICE;

import android.os.Bundle;
import android.widget.TextView;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import Model.Invoice;
import Model.SingletonManager;

public class InvoiceDetailsActivity extends AppCompatActivity {

    private Invoice invoice;
    private TextView tvParticipant, tvAddress, tvDay, tvHour, tvActivityName, tvActivityDescription, tvPrice, tvNif;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_invoice_details);
        int id = getIntent().getIntExtra(ID_INVOICE, 0);

        invoice = SingletonManager.getInstance(this).getInvoice(id);

        tvParticipant = findViewById(R.id.tvParticipant);
        tvAddress = findViewById(R.id.tvAddress);
        tvDay = findViewById(R.id.tvDateInvoice);
        tvHour = findViewById(R.id.tvHourInvoice);
        tvActivityName = findViewById(R.id.tvActivityNameInvoice);
        tvActivityDescription = findViewById(R.id.tvActivityDescriptionInvoice);
        tvPrice = findViewById(R.id.tvPriceInvoice);
        tvNif = findViewById(R.id.tvNifInvoice);

        loadInvoice();
    }

    private void loadInvoice() {

        tvParticipant.setText(invoice.getParticipant());
        tvAddress.setText(invoice.getAddress());
        tvDay.setText(invoice.getDay());
        tvHour.setText(invoice.getHour());
        tvActivityName.setText(invoice.getActivityName());
        tvActivityDescription.setText(invoice.getActivityDescription());
        tvPrice.setText(String.valueOf(invoice.getPrice()));
        tvNif.setText(String.valueOf(invoice.getNif()));

    }
}