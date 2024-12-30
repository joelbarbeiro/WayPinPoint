package Model;

import com.android.volley.NetworkResponse;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.toolbox.HttpHeaderParser;

import java.io.ByteArrayOutputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.util.Map;

public class VolleyMultipartRequest extends Request<String> {
    private final Map<String, String> mHeaders;
    private final Map<String, DataPart> mFiles;
    private final Response.Listener<String> mListener;
    private final Response.ErrorListener mErrorListener;

    public VolleyMultipartRequest(int method, String url, Map<String, String> params, Map<String, DataPart> files,
                                  Response.Listener<String> listener, Response.ErrorListener errorListener) {
        super(method, url, errorListener);
        this.mHeaders = params;
        this.mFiles = files;
        this.mListener = listener;
        this.mErrorListener = errorListener;
    }

    @Override
    public Map<String, String> getHeaders() {
        return mHeaders;
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
            for (Map.Entry<String, DataPart> entry : mFiles.entrySet()) {
                dataOutputStream.writeBytes("--boundary\r\n");
                dataOutputStream.writeBytes("Content-Disposition: form-data; name=\"" + entry.getKey() + "\"; filename=\"" + entry.getValue().getFileName() + "\"\r\n");
                dataOutputStream.writeBytes("Content-Type: " + entry.getValue().getMimeType() + "\r\n\r\n");
                dataOutputStream.write(entry.getValue().getData());
                dataOutputStream.writeBytes("\r\n");
            }
            dataOutputStream.writeBytes("--boundary--\r\n");
        } catch (IOException e) {
            e.printStackTrace();
        }
        return byteArrayOutputStream.toByteArray();
    }
}
