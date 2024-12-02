<?php
/** @var $user common\models\User */
/** @var $activity common\models\Activity */
/** @var $qrCode string */

?>

<h1>Ticket for <?= $activity->description ?></h1>
<p>User: <?= $user->username ?></p>
<p>Activity : <?= $activity->description ?></p>
<p>QR:</p>
<p>
    <img src="<?= $qrCode->writeDataUri() ?>" alt="QR Code">
</p>