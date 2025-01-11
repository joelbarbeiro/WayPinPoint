package Adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import java.util.ArrayList;

import Model.Invoice;
import pt.ipleiria.estg.dei.waypinpoint.R;

public class InvoiceListAdapter extends BaseAdapter {
    private Context context;
    private LayoutInflater inflater;
    private ArrayList<Invoice> invoices;

    public InvoiceListAdapter(Context context, ArrayList<Invoice> invoices) {
        this.context = context;
        this.invoices = invoices;
    }

    @Override
    public int getCount() {
        return invoices.size();
    }

    @Override
    public Object getItem(int position) {
        return invoices.get(position);
    }

    @Override
    public long getItemId(int position) {
        return invoices.get(position).getId();
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        if (inflater == null) {
            inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        }

        if (convertView == null) {
            convertView = inflater.inflate(R.layout.item_list_invoice, null);
        }

        ViewHolderInvoice viewHolderInvoice = (ViewHolderInvoice) convertView.getTag();
        if (viewHolderInvoice == null) {
            viewHolderInvoice = new InvoiceListAdapter.ViewHolderInvoice(convertView);
            convertView.setTag(viewHolderInvoice);
        }
        viewHolderInvoice.update(invoices.get(position));

        return convertView;
    }

    private class ViewHolderInvoice {
        private TextView tvActivity,tvPrice, tvParticipant;

        public ViewHolderInvoice(View view) {
            tvParticipant = view.findViewById(R.id.tvParticipant);
            tvActivity = view.findViewById(R.id.tvActivity);
            tvPrice = view.findViewById(R.id.tvPrice);
        }

        public void update(Invoice invoice) {
            tvParticipant.setText(String.valueOf(invoice.getParticipant()));
            tvActivity.setText(invoice.getActivityName());
            tvPrice.setText(String.valueOf(invoice.getPrice()));
        }
    }
}
