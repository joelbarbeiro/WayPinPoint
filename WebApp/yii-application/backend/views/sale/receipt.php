<?php
/** @var User $user */

/** @var Sale $sale */

use common\models\User;
use common\models\Sale;
use yii\helpers\Html;

?>
<!DOCTYPE html>
<html>
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
<p>
<h1>Receipt</h1>
<p><strong>User:</strong> <?= $sale->buyer0->username ?></p>
<p><strong>NIF:</strong> <?= $sale->buyerextra->nif ?></p>
<p><strong>Address:</strong> <?= $sale->buyerextra->address ?></p>
<p><strong>Activity Name:</strong> <?= $sale->activity->name ?></p>
<p><strong>Activity Description:</strong> <?= $sale->activity->description ?></p>
<p><strong>Total</strong> <?= ($sale->total) . "€" ?></p>
<p><strong>Purchase Date: <?= $sale->purchase_date ?> </strong></p>
<p><strong>Day:</strong> <?= $invoice->booking->calendar->date->date ?> </p>
<p><strong>Hour:</strong> <?= $invoice->booking->calendar->time->hour ?> </p>
<p><strong>Quantity:</strong> <?= $sale->quantity ?></p>
</body>
</html>