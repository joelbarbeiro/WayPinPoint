package pt.ipleiria.estg.dei.waypinpoint;

import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import Model.Category;

public class ActivityCreateActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_create);

/*
        //region # Category spinner #
        // ######################################################################
        ArrayAdapter<Category> categoryAdapter = new ArrayAdapter<>(
                this,
                android.R.layout.simple_spinner_item,
                categories
        );
        categoryAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerCategories.setAdapter(categoryAdapter);
        int positionToSelect = -1;

        for (int i = 0; i < categories.size(); i++) {
            if (categories.get(i).getId() == activity.getCategory()) {
                positionToSelect = i;
                break;
            }
        }
        if (positionToSelect != -1) {
            spinnerCategories.setSelection(positionToSelect);
        }

        spinnerCategories.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                Category selectedCategory = (Category) parent.getItemAtPosition(position);
                int categoryId = selectedCategory.getId();
                String categoryDescription = selectedCategory.getDescription();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });
        //endregion
*/
    }


}