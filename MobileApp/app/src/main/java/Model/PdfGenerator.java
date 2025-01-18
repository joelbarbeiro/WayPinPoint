package Model;

import static android.os.Environment.DIRECTORY_DOWNLOADS;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.pdf.PdfDocument;
import android.os.Environment;
import android.widget.Toast;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;

public class PdfGenerator {
    public static void generate(Context context, Invoice invoice) {
        PdfDocument pdfDocument = new PdfDocument();

        PdfDocument.PageInfo pageInfo = new PdfDocument.PageInfo.Builder(595, 842, 1).create();
        PdfDocument.Page page = pdfDocument.startPage(pageInfo);

        Canvas canvas = page.getCanvas();
        Paint paint = new Paint();
        paint.setTextSize(16);
        paint.setColor(android.graphics.Color.BLACK);

        Paint heading = new Paint();
        heading.setTextSize(24);
        heading.setColor(Color.BLUE);

        int newY = 100, constLine = 25, x = 50;

        canvas.drawText("WayPinPoint Receipt", 180, newY, heading);
        newY = newY + 60;
        canvas.drawText("User: " + invoice.getParticipant(), x, newY, paint);
        newY = newY + constLine;
        canvas.drawText("NIF: " + invoice.getNif(), x, newY, paint);
        newY = newY + constLine;
        canvas.drawText("Address: " + invoice.getAddress(), x, newY, paint);
        newY = newY + constLine;
        canvas.drawText("Activity Name: " + invoice.getActivityName(), x, newY, paint);
        newY = newY + constLine;
        newY = drawWrappedText("Activity description: " + invoice.getActivityDescription(), canvas, paint, x, newY, 400);
        newY = newY + constLine;
        canvas.drawText("Total: " + invoice.getPrice(), x, newY, paint);
        newY = newY + constLine;
        //canvas.drawText("Purchase date: " + invoice., x, newY, paint);
        //newY = newY + constLine;
        canvas.drawText("Date: " + invoice.getDay(), x, newY, paint);
        newY = newY + constLine;
        canvas.drawText("Hour: " + invoice.getHour(), x, newY, paint);
        newY = newY + constLine;
        //canvas.drawText("Quantity: " + invoice.getQuantity(), x, newY, paint);
        //newY = newY + constLine;

        pdfDocument.finishPage(page);

        File file = new File(Environment.getExternalStoragePublicDirectory(DIRECTORY_DOWNLOADS), "sample.pdf");
        try {
            pdfDocument.writeTo(new FileOutputStream(file));
            System.out.println("-->> PDF saved to: " + file.getAbsolutePath());
            Toast.makeText(context, "Pdf saved to " + file.getPath(), Toast.LENGTH_SHORT).show();
        } catch (IOException e) {
            e.printStackTrace();
            System.err.println("-->> Error while saving PDF: " + e.getMessage());
        }

        pdfDocument.close();
    }

    public static int drawWrappedText(String text, Canvas canvas, Paint paint, int x, int y, int maxWidth) {
        int startY = y;

        String[] words = text.split(" ");
        StringBuilder line = new StringBuilder();
        for (String word : words) {
            if (paint.measureText(line + " " + word) <= maxWidth) {
                line.append(word).append(" ");
            } else {
                canvas.drawText(line.toString(), x, startY, paint);
                startY += 20;
                line = new StringBuilder(word).append(" ");
            }
        }
        if (!line.toString().isEmpty()) {
            canvas.drawText(line.toString(), x, startY, paint);
        }
        return startY;
    }
}