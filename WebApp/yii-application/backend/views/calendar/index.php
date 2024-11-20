<?php

use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\CalendarSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Calendars';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="calendar-index">

    <table class="table">
        <thead>
        <tr>
            <th scope="col">Activity</th>
            <th scope="col">Date</th>
            <th scope="col">Hour</th>
            <th scope="col">Active</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php


        foreach ($dataProvider->getModels() as $activities) {

            echo '<tr>';
            echo '<th scope="row" rowspan="' . count($activities->calendar) . '">' . $activities->name . '</th>';
            $first = false;
            foreach ($activities->calendar as $calendar) {
                if ($first) {
                    echo '<tr>';
                }
                echo '<td>' . $calendar->date->date . '</td>';
                echo '<td>' . $calendar->time->hour . '</td>';
                echo '<td><input type="checkbox" class="btn-check" id="checkbox-' . $calendar->id . '" data-id="' . $calendar->id . '" ' . ($calendar->status ? 'checked' : '') . ' autocomplete="off"></td>';
                //echo '<td><a href="' . Url::to(['activity/update', 'id' => $activity->id, 'cal' => $calendar->id]) . '" class="btn btn-warning mr-3">Update</a></td>';
                echo '</tr>';

                $first = true;
            }
            echo '</tr>';
        }

        ?>

        </tbody>
    </table>

</div>
<?php
$this->registerJs("
    $(document).on('change', '.btn-check', function() {
        var checkbox = $(this);
        var calendarId = checkbox.data('id');
        var status = checkbox.prop('checked') ? 1 : 0; 

        $.ajax({
            url: '" . Url::to(['calendar/update-status']) . "',
            type: 'POST',
            data: {
                id: calendarId, 
                status: status,
                _csrf: yii.getCsrfToken()
            },
            
            success: function(response) {
                if (response.success) {
                    alert('Data state updated successfully');
                } else {
                    alert('Error updating status');
                }
            },
            error: function() {
                alert('There was an error processing your request');
            }
        });
    });
");
?>
