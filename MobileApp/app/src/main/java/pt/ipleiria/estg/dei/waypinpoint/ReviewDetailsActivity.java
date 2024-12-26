package pt.ipleiria.estg.dei.waypinpoint;

import static pt.ipleiria.estg.dei.waypinpoint.utils.StatusJsonParser.isConnectionInternet;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ACTIVITY_ID;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.ID_REVIEW;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.OP_CODE;
import static pt.ipleiria.estg.dei.waypinpoint.utils.Utilities.USER_ID;

import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.EditText;
import android.widget.RatingBar;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

import Listeners.ReviewListener;
import Model.Review;
import Model.SingletonManager;

public class ReviewDetailsActivity extends AppCompatActivity implements ReviewListener {

    private Review review;
    private EditText etMessage;
    private RatingBar rbScore;
    private FloatingActionButton fabSave;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_review_details);
        int userId = getIntent().getIntExtra(USER_ID, 0);
        int id = getIntent().getIntExtra(ID_REVIEW, 0);
        int activityId = getIntent().getIntExtra(ACTIVITY_ID, 0);
        review = SingletonManager.getInstance(getApplicationContext()).getReview(id);

        fabSave = findViewById(R.id.fabSave);
        etMessage = findViewById(R.id.etMessage);
        rbScore = findViewById(R.id.ratingBarScore);

        if (review != null) {
            loadReview();
            fabSave.setImageResource(R.drawable.ic_save);
        } else {
            setTitle(getString(R.string.review_add_message));
            fabSave.setImageResource(R.drawable.ic_action_add);
        }

        fabSave.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                float rating = rbScore.getRating();
                String message = etMessage.getText().toString().trim();

                if (rating == 0) {
                    Toast.makeText(ReviewDetailsActivity.this, R.string.error_rating_required, Toast.LENGTH_SHORT).show();
                    return;
                }

                if (message.isEmpty()) {
                    Toast.makeText(ReviewDetailsActivity.this, R.string.error_message_empty, Toast.LENGTH_SHORT).show();
                    return;
                }

                if (review != null) {
                    if (userId == review.getUserId()) {
                        review.setScore((int) rbScore.getRating());
                        review.setMessage(etMessage.getText().toString());
                        SingletonManager.getInstance(getApplicationContext()).editReviewApi(review, getApplicationContext());
                    } else {
                        Toast.makeText(ReviewDetailsActivity.this, R.string.review_user_mismatch, Toast.LENGTH_SHORT).show();
                    }
                } else {
                    review = new Review(
                            0,
                            userId,
                            activityId,
                            (int) rating,
                            message,
                            null,
                            null
                    );
                    SingletonManager.getInstance(getApplicationContext()).addReviewApi(review, getApplicationContext());
                }
            }
        });
        SingletonManager.getInstance(getApplicationContext()).setReviewListener(this);
    }

    private void loadReview() {
        setTitle(getString(R.string.review_details_title));
        rbScore.setRating(review.getScore());
        etMessage.setText(review.getMessage());
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        if (review != null) {
            getMenuInflater().inflate(R.menu.menu_delete, menu);
            return super.onCreateOptionsMenu(menu);
        }
        return false;
    }

    @Override
    public boolean onOptionsItemSelected(@NonNull MenuItem item) {
        int userId = getIntent().getIntExtra(USER_ID, 0);
        if (item.getItemId() == R.id.itemRemover) {
            if (!isConnectionInternet(getApplicationContext())) {
                Toast.makeText(this, R.string.error_no_internet, Toast.LENGTH_SHORT).show();
            } else {
                if (userId == review.getUserId()) {
                    dialogRemove();
                } else {
                    Toast.makeText(this, R.string.review_user_mismatch, Toast.LENGTH_SHORT).show();
                }
            }
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    private void dialogRemove() {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle(R.string.dialog_review_remove);
        builder.setMessage(R.string.dialog_review_message);
        builder.setPositiveButton(R.string.yes_string, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        SingletonManager.getInstance(getApplicationContext()).removeReviewApi(review, getApplicationContext());
                    }
                })
                .setNegativeButton(R.string.no_string, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        dialog.cancel();
                    }
                })
                .setIcon(R.drawable.ic_dialog_remove)
                .show();
    }

    @Override
    public void onValidateReviewOperation(int op) {
        Intent intent = new Intent();
        intent.putExtra(OP_CODE, op);
        setResult(RESULT_OK, intent);
        finish();
    }
}