package Adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.bumptech.glide.Glide;

import java.util.ArrayList;

import pt.ipleiria.estg.dei.waypinpoint.R;

public class PhotoListAdapter extends BaseAdapter {
    private Context context;
    private LayoutInflater inflater;
    private ArrayList<String> photos;

    public PhotoListAdapter(Context context, ArrayList<String> photos) {
        this.context = context;
        this.photos = photos;
    }

    @Override
    public int getCount() {
        return photos.size();
    }

    @Override
    public Object getItem(int position) {
        return photos.get(position);
    }

    @Override
    public long getItemId(int position) {
        return 0;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        if (inflater == null) {
            inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        }

        if (convertView == null) {
            convertView = inflater.inflate(R.layout.item_photo_list, null);
        }

        ViewHolderPhoto viewHolderPhoto = (PhotoListAdapter.ViewHolderPhoto) convertView.getTag();
        if (viewHolderPhoto == null) {
            viewHolderPhoto = new PhotoListAdapter.ViewHolderPhoto(convertView);
            convertView.setTag(viewHolderPhoto);
        }
        viewHolderPhoto.update(photos.get(position));

        return convertView;
    }

    private class ViewHolderPhoto {
        private TextView tvPhotoDate;
        private ImageView ivPhoto;

        public ViewHolderPhoto(View view) {
            tvPhotoDate = view.findViewById(R.id.tvDate);
            ivPhoto = view.findViewById(R.id.ivPhoto);
        }

        public void update(String photoPath) {
            Glide.with(context)
                    .load(photoPath)
                    .into(ivPhoto);
        }
    }
}
