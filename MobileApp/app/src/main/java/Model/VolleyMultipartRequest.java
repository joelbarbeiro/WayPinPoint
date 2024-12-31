package Model;

import com.android.volley.AuthFailureError;
import com.android.volley.NetworkResponse;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.toolbox.HttpHeaderParser;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.ByteArrayOutputStream;
import java.io.DataOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.util.Iterator;
import java.util.Map;

public class VolleyMultipartRequest extends Request<String> {

    private final JSONObject mParams;
    private final  Map<String, File> mFiles;
    private final Response.Listener<String> mListener;
    private final Response.ErrorListener mErrorListener;

    public VolleyMultipartRequest(int method, String url, JSONObject params,  Map<String, File> files,
                                  Response.Listener<String> listener, Response.ErrorListener errorListener) {
        super(method, url, errorListener);
        this.mParams = params;
        this.mFiles = files;
        this.mListener = listener;
        this.mErrorListener = errorListener;
    }

    @Override
    public Map<String, String> getHeaders() {
        try {
            return super.getHeaders();
        } catch (AuthFailureError e) {
            throw new RuntimeException(e);
        }
    }

    @Override
    public String getBodyContentType() {
        return "multipart/form-data; boundary=boundary";
    }

    @Override
    protected Response<String> parseNetworkResponse(NetworkResponse response) {
        String json = new String(response.data);
        return Response.success(json, HttpHeaderParser.parseCacheHeaders(response));
    }

    @Override
    protected void deliverResponse(String response) {
        mListener.onResponse(response);
    }

    @Override
    public byte[] getBody() {
        ByteArrayOutputStream byteArrayOutputStream = new ByteArrayOutputStream();
        try {
            DataOutputStream dataOutputStream = new DataOutputStream(byteArrayOutputStream);

            // Add parameters if any (mParams)
            if (mParams != null) {
                for (Iterator<String> it = mParams.keys(); it.hasNext(); ) {
                    String key = it.next();
                    String value = mParams.getString(key);
                    dataOutputStream.writeBytes("--boundary\r\n");
                    dataOutputStream.writeBytes("Content-Disposition: form-data; name=\"" + key + "\"\r\n\r\n");
                    dataOutputStream.writeBytes(value);
                    dataOutputStream.writeBytes("\r\n");
                }
            }

            if (mFiles != null && !mFiles.isEmpty()) {
                for (Map.Entry<String, File> entry : mFiles.entrySet()) {
                    if(entry.getValue() != null) {
                        String key = entry.getKey();
                        File file = entry.getValue();

                        String fileName = file.getName();
                        String mimeType = "application/octet-stream";

                        dataOutputStream.writeBytes("--boundary\r\n");
                        dataOutputStream.writeBytes("Content-Disposition: form-data; name=\"" + key + "\"; filename=\"" + fileName + "\"\r\n");
                        dataOutputStream.writeBytes("Content-Type: " + mimeType + "\r\n\r\n");

                        FileInputStream fileInputStream = new FileInputStream(file);
                        byte[] buffer = new byte[1024];
                        int bytesRead;
                        while ((bytesRead = fileInputStream.read(buffer)) != -1) {
                            dataOutputStream.write(buffer, 0, bytesRead);
                        }
                        fileInputStream.close();

                        dataOutputStream.writeBytes("\r\n");
                    }
                }
            }

            dataOutputStream.writeBytes("--boundary--\r\n");

        } catch (IOException | JSONException e) {
            e.printStackTrace();
        }

        return byteArrayOutputStream.toByteArray();
    }
}
