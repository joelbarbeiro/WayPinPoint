<?php
/** @var User $user */

/** @var \common\models\Activity $activity */

use common\models\Activity;
use common\models\User;

?>
<!DOCTYPE html>
<html>
<title>Invoice</title>
<head>
    <style>
        h1 { text-align: center; color: #336699; }
        p { font-size: 14px; margin: 10px   0; }
        .highlight { font-weight: bold; color: #336699; }
    </style>
</head>
<body>
<h1>Receipt</h1>
<p><strong>User:</strong> <?= $user->username ?></p>
<p><strong>Activity:</strong> <?= $activity->description ?></p>
<p><strong>Price per Pax:</strong> <?= ($activity->priceperpax) . "€" ?></p>
<p><strong>Quantity:</strong> <?= $activity->maxpax ?></p>
<p><strong>Total: </strong><?= $sale->total . '€' ?></p>
<p><strong>Purchase Date:</strong> <?= $sale->purchase_date  ?></p>
<?php foreach ($activity->calendar as $calendar) {
    if ($calendar->status != 0) {
        echo '<p class="card-text"> <strong>Activity Date: </strong>' . $calendar->date->date . ' Time: ' . $calendar->time->hour . '</p>';
    }
} ?>
</body>
</html>