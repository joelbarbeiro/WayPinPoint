<?php
/** @var User $user */

/** @var \common\models\Activity $activity */

use common\models\User;

?>
<!DOCTYPE html>
<html>
<title>Invoice</title>
<head>
    <style>
        h1 {
            text-align: center;
            color: #336699;
        }

        p {
            font-size: 14px;
            margin: 10px 0;
        }

        .highlight {
            font-weight: bold;
            color: #336699;
        }
    </style>
</head>
<body>
<h1>Receipt</h1>
<p><strong>User:</strong> <?= $invoice->user->username ?></p>
<p><strong>Activity:</strong> <?= $invoice->booking->activity->description ?></p>
<p><strong>Price per Pax:</strong> <?= $invoice->booking->activity->priceperpax . "€" ?></p>
<p><strong>Quantity:</strong> <?= $invoice->booking->activity->maxpax ?></p>
<p><strong>Total: </strong><?= $invoice->sale->total . '€' ?></p>
<p><strong>Purchase Date:</strong> <?= $invoice->sale->purchase_date ?></p>
<p><strong>Activity Date:</strong> <?= $invoice->booking->calendar->date->date ?> </p>
<p><strong>Activity Hour:</strong> <?= $invoice->booking->calendar->time->hour ?> </p>

</body>
</html>