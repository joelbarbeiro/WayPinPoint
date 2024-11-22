<?php
/** @var User $user */

/** @var \common\models\Activity $activity */

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
<h1>Receipt</h1>
<p><strong>User:</strong> <?= $user->username ?></p>
<p><strong>Activity:</strong> <?= $activity->description ?></p>
<p><strong>Price per Pax:</strong> <?= ($activity->priceperpax) . "€" ?></p>
<p><strong>Quantity:</strong> <?= $activity->maxpax ?></p>
<!-- QR Code Image -->
<!--    <p><strong>QR Code:</strong></p>-->
<!--    <img src="--><?php //= $qrCode->writeDataUri() ?><!--" alt="QR Code">-->
</body>
</html>