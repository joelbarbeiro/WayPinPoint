package pt.ipleiria.estg.dei.waypinpoint.utils;

import android.content.Context;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.provider.MediaStore;
import android.util.Base64;
import android.util.Log;

import com.android.volley.Response;
import com.android.volley.VolleyError;

import java.io.BufferedOutputStream;
import java.io.BufferedReader;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;

public class ImageSender {
    private Context context;

    public ImageSender(Context context) {
        this.context = context;
    }

    // Resize image and save it as a temporary file
    public static File resizeImageToTempFile(Context context, Uri imageUri, int width) throws IOException {
        // Decode image from URI
        Bitmap bitmap = MediaStore.Images.Media.getBitmap(context.getContentResolver(), imageUri);
        if (bitmap == null) {
            throw new IOException("Failed to decode bitmap from URI.");
        }

        // Calculate new height to maintain aspect ratio
        int originalWidth = bitmap.getWidth();
        int originalHeight = bitmap.getHeight();
        int height = (width * originalHeight) / originalWidth;

        // Resize Bitmap
        Bitmap resizedBitmap = Bitmap.createScaledBitmap(bitmap, width, height, true);

        // Save resized bitmap to a temporary file
        File tempFile = new File(context.getCacheDir(), "temp_image.jpg");
        try (FileOutputStream fos = new FileOutputStream(tempFile)) {
            resizedBitmap.compress(Bitmap.CompressFormat.JPEG, 90, fos);
        }

        return tempFile;
    }

    // Send image file to the server using multipart/form-data
    public void sendImageToServer(String apiHost, String endpoint, int idOfFolder, Uri imageUri, int targetWidth, Response.Listener<String> successListener,
                                  Response.ErrorListener errorListener) {
        new Thread(() -> {
            try {
                // Resize the image and save it as a temporary file
                File tempFile = resizeImageToTempFile(context.getApplicationContext(), imageUri, targetWidth);

                // Prepare the connection for multipart/form-data
                URL url = new URL(apiHost + endpoint);
                HttpURLConnection conn = (HttpURLConnection) url.openConnection();
                conn.setRequestMethod("POST");
                conn.setRequestProperty("Content-Type", "multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW");
                conn.setDoOutput(true);

                try (OutputStream os = new BufferedOutputStream(conn.getOutputStream())) {
                    // Write the multipart form-data
                    writeMultipartFormData(os, tempFile, idOfFolder);
                    os.flush();
                }

                // Get response from the server
                int responseCode = conn.getResponseCode();
                if (responseCode >= 200 && responseCode < 300) {
                    System.out.println("Response Code: " + responseCode + " Success");
                    successListener.onResponse("Image Saved with Success");
                } else {
                    // Read the error stream to get the server's response
                    InputStream errorStream = conn.getErrorStream();
                    if (errorStream != null) {
                        String errorMessage = readStream(errorStream);
                        System.err.println("Error Response Code: " + responseCode + ", Error: " + errorMessage);
                    } else {
                        System.err.println("Error Response Code: " + responseCode + ", No additional error message.");
                    }
                    errorListener.onErrorResponse(new VolleyError(String.valueOf(errorStream)));
                }
                conn.disconnect();

                // Optionally delete the temporary file after sending the request
                if (tempFile.exists()) {
                    tempFile.delete();
                }

            } catch (Exception e) {
                e.printStackTrace();
            }
        }).start();
    }

    private void writeMultipartFormData(OutputStream os, File tempFile, int id) throws IOException {
        String boundary = "----WebKitFormBoundary7MA4YWxkTrZu0gW";
        String lineEnd = "\r\n";
        String twoHyphens = "--";

        os.write((twoHyphens + boundary + lineEnd).getBytes());

        os.write("Content-Disposition: form-data; name=\"id\"".getBytes());
        os.write(lineEnd.getBytes());
        os.write(lineEnd.getBytes());
        os.write(String.valueOf(id).getBytes());
        os.write(lineEnd.getBytes());

        os.write((twoHyphens + boundary + lineEnd).getBytes());
        os.write("Content-Disposition: form-data; name=\"photoFile\"; filename=\"temp_image.jpg\"".getBytes());
        os.write(lineEnd.getBytes());
        os.write("Content-Type: image/jpeg".getBytes());
        os.write(lineEnd.getBytes());
        os.write(lineEnd.getBytes());

        try (InputStream fileInputStream = new java.io.FileInputStream(tempFile)) {
            byte[] buffer = new byte[4096];
            int bytesRead;
            while ((bytesRead = fileInputStream.read(buffer)) != -1) {
                os.write(buffer, 0, bytesRead);
            }
        }

        // Write the ending boundary
        os.write(lineEnd.getBytes());
        os.write((twoHyphens + boundary + twoHyphens + lineEnd).getBytes());
    }

    private String readStream(InputStream stream) throws IOException {
        StringBuilder builder = new StringBuilder();
        try (BufferedReader reader = new BufferedReader(new InputStreamReader(stream))) {
            String line;
            while ((line = reader.readLine()) != null) {
                builder.append(line).append("\n");
            }
        }
        return builder.toString();
    }
}