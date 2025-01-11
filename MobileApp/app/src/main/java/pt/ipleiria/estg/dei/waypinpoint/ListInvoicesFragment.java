package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.EDIT;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ID_INVOICE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getUserId;

import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.SearchView;
import android.widget.TextView;

import java.util.ArrayList;

import Adapters.InvoiceListAdapter;
import Listeners.InvoicesListener;
import Model.Invoice;
import Model.Review;
import Model.SingletonManager;
import Model.WaypinpointDbHelper;

public class ListInvoicesFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, InvoicesListener {

    private ListView lvInvoices;
    private ArrayList<Review> invoices;
    private SwipeRefreshLayout swipeRefreshLayout;
    private SearchView searchView;
    private View emptyView;
    private TextView tvEmptyMessage;
    private WaypinpointDbHelper waypinpointDbHelper;


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_list_invoices, container, false);
        lvInvoices = view.findViewById(R.id.lvInvoices);
        emptyView = view.findViewById(R.id.emptyViewLayoutInvoices);

        int userId = getUserId(getContext());
        if (requireActivity() instanceof AppCompatActivity) {
            ((AppCompatActivity) requireActivity()).getSupportActionBar().setTitle("Invoices");
        }


        lvInvoices.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Invoice invoice = (Invoice) parent.getItemAtPosition(position);
                Intent intent = new Intent(getContext(), InvoiceDetailsActivity.class);
                intent.putExtra(ID_INVOICE, invoice.getId());
                startActivityForResult(intent, EDIT);
            }
        });

        swipeRefreshLayout = view.findViewById(R.id.srl_invoices);
        swipeRefreshLayout.setOnRefreshListener(this);

        SingletonManager.getInstance(getContext()).setInvoicesListener(this);
        SingletonManager.getInstance(getContext()).getInvoicesForUserApi(getContext(), userId);

        return view;
    }

    private void dialogRemoveDownload(int id) {
        AlertDialog.Builder builder = new AlertDialog.Builder(getContext());
        builder.setTitle(R.string.dialog_invoice_title);
        builder.setMessage(R.string.dialog_invoice_message);
        builder.setPositiveButton(R.string.menu_delete, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        SingletonManager.getInstance(getContext()).removeInvoiceDb(id);
                    }
                })
                .setNegativeButton(R.string.dialog_download, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        dialog.cancel();
                    }
                })
                .show();
    }

    @Override
    public void onRefresh() {
        int userId = getUserId(getContext());
        SingletonManager.getInstance(getContext()).getInvoicesForUserApi(getContext(), userId);
        swipeRefreshLayout.setRefreshing(false);
    }

    @Override
    public void onRefreshInvoicesList(ArrayList<Invoice> listInvoices) {
        if (listInvoices != null || !listInvoices.isEmpty()) {
            lvInvoices.setVisibility(View.VISIBLE);
            emptyView.setVisibility(View.GONE);
            lvInvoices.setAdapter(new InvoiceListAdapter(getContext(), listInvoices));
        }
        if (listInvoices.isEmpty()) {
            lvInvoices.setVisibility(View.GONE);
            emptyView.setVisibility(View.VISIBLE);
            tvEmptyMessage = emptyView.findViewById(R.id.tvEmptyMessage);
            tvEmptyMessage.setText(R.string.empty_placeholder_message);
        }
    }
}