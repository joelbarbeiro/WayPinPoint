package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.ActivityDetailsActivity.ID_ACTIVITY;
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

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.util.ArrayList;

import Listeners.PhotosListener;
import Model.SingletonManager;
import Model.User;
import adaptors.PhotoListAdapter;
import pt.ipleiria.estg.dei.waypinpoint.utils.ImageSender;


public class ActivityPhotosFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, PhotosListener {

    private ListView lvPhotos;
    private ArrayList<String> photos;
    private SwipeRefreshLayout swipeRefreshLayout;
    private SearchView searchView;
    private FloatingActionButton fabPhotos;
    private int id;
    private User user;
    private String apiHost, role;


    public ActivityPhotosFragment() {
        // Required empty public constructor
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_activity_photos, container, false);
        lvPhotos = view.findViewById(R.id.lvPhotos);
        int userId = getUserId(getContext());
        fabPhotos = view.findViewById(R.id.fabPhotos);
        if (requireActivity() instanceof AppCompatActivity) {
            ((AppCompatActivity) requireActivity()).getSupportActionBar().setTitle("Photos for Activity");
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
            imageSender.sendPhotosToServer(apiHost, activityId, imageUri, 600);
        }
        super.onActivityResult(requestCode, resultCode, data);
    }

    @Override
    public void onRefresh() {
        SingletonManager.getInstance(getContext()).getAllPhotos(getContext());
        swipeRefreshLayout.setRefreshing(false);
    }

    @Override
    public void onRefreshPhotosList(ArrayList<String> listPhotos) {
        if(listPhotos != null) {
            lvPhotos.setAdapter(new PhotoListAdapter(getContext(), listPhotos));
        }
    }
}