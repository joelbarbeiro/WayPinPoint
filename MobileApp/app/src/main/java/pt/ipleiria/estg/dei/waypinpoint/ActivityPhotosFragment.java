package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.ActivityDetailsActivity.ID_ACTIVITY;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ENDPOINT_ACTIVITY;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.PICK_IMAGE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getApiHost;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.getUserId;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListView;
import android.widget.SearchView;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.util.ArrayList;

import Adapters.PhotoListAdapter;
import Listeners.PhotosListener;
import Model.SingletonManager;
import Model.User;
import pt.ipleiria.estg.dei.waypinpoint.utils.ImageSender;


public class ActivityPhotosFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, PhotosListener {

    private ListView lvPhotos;
    private ArrayList<String> photos;
    private SwipeRefreshLayout swipeRefreshLayout;
    private FloatingActionButton fabPhotos;
    private int id;
    private User user;
    private String apiHost, role;
    private View emptyView;
    private TextView tvEmptyMessage;


    public ActivityPhotosFragment() {
        // Required empty public constructor
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_activity_photos, container, false);
        lvPhotos = view.findViewById(R.id.lvPhotos);
        int userId = getUserId(getContext());
        int activityId = getArguments().getInt(ID_ACTIVITY);
        emptyView = view.findViewById(R.id.emptyViewLayoutPhotos);

        fabPhotos = view.findViewById(R.id.fabPhotos);
        if (requireActivity() instanceof AppCompatActivity) {
            ((AppCompatActivity) requireActivity()).getSupportActionBar().setTitle(R.string.photos_activity_title);
        }
        fabPhotos.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(Intent.ACTION_PICK, MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
                startActivityForResult(intent, PICK_IMAGE);
            }
        });

        swipeRefreshLayout = view.findViewById(R.id.srl_photos);
        swipeRefreshLayout.setOnRefreshListener(this);

        SingletonManager.getInstance(getContext()).setPhotosListener(this);
        SingletonManager.getInstance(getContext()).getAllPhotos(getContext(), activityId);

        return view;
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        if (requestCode == PICK_IMAGE && data != null) {
            int activityId = getArguments().getInt(ID_ACTIVITY);
            Uri imageUri = data.getData();
            id = getUserId(requireContext());
            apiHost = getApiHost(requireContext());
            ImageSender imageSender = new ImageSender(requireContext());
            imageSender.sendImageToServer(apiHost, ENDPOINT_ACTIVITY, activityId, imageUri, 600,
                    new Response.Listener<String>() {
                        @Override
                        public void onResponse(String response) {
                            SingletonManager.getInstance(getContext()).getAllPhotos(getContext(), activityId);
                        }
                    }, new Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            System.out.println("Error: " + error.getMessage());
                        }
                    });
        }
        super.onActivityResult(requestCode, resultCode, data);
    }

    @Override
    public void onRefresh() {
        int activityId = getArguments().getInt(ID_ACTIVITY);
        SingletonManager.getInstance(getContext()).getAllPhotos(getContext(), activityId);
        swipeRefreshLayout.setRefreshing(false);
    }

    @Override
    public void onRefreshPhotosList(ArrayList<String> listPhotos) {
        if (listPhotos != null) {
            lvPhotos.setVisibility(View.VISIBLE);
            emptyView.setVisibility(View.GONE);
            lvPhotos.setAdapter(new PhotoListAdapter(getContext(), listPhotos));
        }
        if (listPhotos.isEmpty()) {
            lvPhotos.setVisibility(View.GONE);
            emptyView.setVisibility(View.VISIBLE);
            tvEmptyMessage = emptyView.findViewById(R.id.tvEmptyMessage);
            tvEmptyMessage.setText(R.string.empty_photos_message);
        }
    }
}