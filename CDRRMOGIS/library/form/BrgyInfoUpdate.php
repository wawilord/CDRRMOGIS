<?php
session_start();
include ('../classes/BrgyInfoUpdater.php');

$brgyInfoUpdater = new BrgyInfoUpdater();
$brgyInfoUpdater->dbSync();
?>