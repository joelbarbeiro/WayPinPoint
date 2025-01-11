package Listeners;

import java.util.ArrayList;

import Model.Invoice;

public interface InvoicesListener {
    void onRefreshInvoicesList(ArrayList<Invoice> listInvoices);

}
