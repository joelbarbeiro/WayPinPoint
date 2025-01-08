<?php
/** @var User $user */

/** @var Invoice $invoice */

use common\models\Invoice;
use common\models\User;

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
<p><strong>User:</strong> <?= $invoice->sale->buyer0->username ?></p>
<p><strong>NIF:</strong> <?= $invoice->sale->buyerextra->nif ?></p>
<p><strong>Address:</strong> <?= $invoice->sale->buyerextra->address ?></p>
<p><strong>Activity Name:</strong> <?= $invoice->sale->activity->name ?></p>
<p><strong>Activity Description:</strong> <?= $invoice->sale->activity->description ?></p>
<p><strong>Total</strong> <?= $invoice->sale->total . "€" ?></p>
<p><strong>Purchase Date: <?= $invoice->sale->purchase_date ?> </strong></p>
<p><strong>Day:</strong> <?= $invoice->booking->calendar->date->date ?> </p>
<p><strong>Hour:</strong> <?= $invoice->booking->calendar->time->hour ?> </p>
<p><strong>Quantity:</strong> <?= $invoice->sale->quantity ?></p>
</body>
</html>