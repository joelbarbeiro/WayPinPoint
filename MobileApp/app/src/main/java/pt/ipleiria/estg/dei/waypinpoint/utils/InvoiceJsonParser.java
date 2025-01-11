package pt.ipleiria.estg.dei.waypinpoint.utils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import Model.Invoice;
import Model.Review;

public class InvoiceJsonParser {

    public static ArrayList<Invoice> parserJsonInvoices(JSONArray response) {
        ArrayList<Invoice> invoices = new ArrayList<>();
        try {
            for (int c = 0; c < response.length(); c++) {
                JSONObject invoice = (JSONObject) response.get(c);
                int id = invoice.getInt("id");
                String participant = invoice.getString("username");
                String address = invoice.getString("adddress");
                String activityName = invoice.getString("activityName");
                String activityDescription = invoice.getString("activityDescription");
                int price = invoice.getInt("activityPrice");
                String date = invoice.getString("date");
                String hour = invoice.getString("time");
                int nif = invoice.getInt("nif");

                Invoice auxInvoice = new Invoice(
                        id,
                        participant,
                        address,
                        date,
                        hour,
                        activityName,
                        activityDescription,
                        price,
                        nif
                );
                invoices.add(auxInvoice);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return invoices;
    }

    public static Invoice parserJsonInvoice(String response) {
        Invoice auxInvoice = null;

        try {
            JSONObject invoice = new JSONObject(response);
            int id = invoice.getInt("id");
            String participant = invoice.getString("username");
            String address = invoice.getString("adddress");
            String activityName = invoice.getString("activityName");
            String activityDescription = invoice.getString("activityDescription");
            int price = invoice.getInt("activityPrice");
            String date = invoice.getString("date");
            String hour = invoice.getString("time");
            int nif = invoice.getInt("nif");

            auxInvoice = new Invoice(
                    id,
                    participant,
                    address,
                    date,
                    hour,
                    activityName,
                    activityDescription,
                    price,
                    nif
            );
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return auxInvoice;
    }
}
