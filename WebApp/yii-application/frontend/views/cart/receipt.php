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
<body>
<p>
<h1>Waypinpoint Receipt</h1>
<p><strong>User:</strong> <?= $cart->user->username ?></p>
<p><strong>NIF:</strong> <?= $cart->user->userextra->nif ?></p>
<p><strong>Address:</strong> <?= $cart->user->userextra->address ?></p>
<p><strong>Activity Name:</strong> <?= $cart->activity->name ?></p>
<p><strong>Activity Description:</strong> <?= $cart->activity->description ?></p>
<p><strong>Total</strong> <?= ($cart->activity->priceperpax * $cart->quantity) . "€" ?></p>
<p><strong>Day:</strong> <?= $cart->calendar->date->date ?> </p>
<p><strong>Hour:</strong> <?= $cart->calendar->time->hour ?> </p>
<p><strong>Quantity:</strong> <?= $cart->quantity ?></p>
</body>
</html>