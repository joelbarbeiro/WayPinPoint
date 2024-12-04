<?php
/** @var User $user */

/** @var Cart $cart */

use common\models\Cart;
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
<p>
<h1>Receipt</h1>
<p><strong>User:</strong> <?= $cart->user->username ?></p>
<p><strong>Activity Name:</strong> <?= $cart->activity->name ?></p>
<p><strong>Activity Description:</strong> <?= $cart->activity->description ?></p>
<p><strong>Price per Pax:</strong> <?= ($cart->activity->priceperpax) . "€" ?></p>
<p><strong>Day:</strong> <?= $cart->calendar->date->date ?> </p>
<p><strong>Hour:</strong> <?= $cart->calendar->time->hour ?> </p>
<p><strong>Quantity:</strong> <?= $cart->quantity ?></p>
</body>
</html>