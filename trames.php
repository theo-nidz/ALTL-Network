<?php
require('inc/fonctions.php');
require('inc/PDO.php');

//debug($_GET);

$sql = "SELECT * FROM trames WHERE protocol_name = :protocol_name";
$query = $pdo->prepare($sql);
$query -> bindValue(':protocol_name',$_GET['protocol']);
$query->execute();
$trameCurrent = $query->fetchAll();

$sql2 = "SELECT DISTINCT protocol_name FROM trames";
$query = $pdo->prepare($sql2);
$query->execute();
$trames = $query->fetchAll();

//debug($trameCurrent);
if (empty($trameCurrent)) {
    header('Location: logs.php');
}

include('inc/headerdashboard.php');



?>

<h1 class="dashboard_title">Détails de toutes les trames du protocol <?= $_GET['protocol'] ?></h1>
<div class="separator"></div>
<ul class="protocols">
    <?php foreach ($trames as $trame) { ?>
        <li><a class="btn_trames" href="trames.php?protocol=<?= $trame['protocol_name'] ?>"><?= $trame['protocol_name'] ?></a></li>
    <?php } ?>
</ul>
<table class="details_table wrap">
    <tr>
        <thead>
            <td>Etat</td>
            <td>Date de réception</td>
            <td>Identification</td>
            <td>Code</td>
            <td>Protocole</td>
            <td>IP de départ</td>
            <td>IP de destination</td>
            <td>TTL</td>
        </thead>
    </tr>
    <?php foreach ($trameCurrent as $oneTrame) { ?>
    <tr>
        <tbody>
            <?php if ($oneTrame['status'] == 'timeout') {
                echo '<td><img src="asset/img/cross.png" alt="Erreur : timeout" class="img_status"></td>';
            }
            else {
                echo '<td><img src="asset/img/tick.png" alt="OK" class="img_status"></td>';
            }?>

            <td><?=date("d/m/Y", strtotime($oneTrame['date']));?> à <?=date("H:i:s", strtotime($oneTrame['date']));?></td>
            <td><?=$oneTrame['identification'];?></td>
            <td><?=$oneTrame['flags_code'];?></td>
            <td><?=$oneTrame['protocol_name'];?></td>
            <td><?=$oneTrame['ip_from'];?></td>
            <td><?=$oneTrame['ip_dest'];?></td>
            <td><?=$oneTrame['ttl'];?></td>
        </tbody>
    </tr>
    <?php } ?>
</table>



<?php

include('inc/footer.php');
