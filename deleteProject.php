<?php
include_once 'db.php';
$delete = 'DELETE FROM project_owner_pivot WHERE project_id = '.$_GET["id"];
$deleteResult = $connection->prepare($delete);
$deleteResult->execute();
$delete = 'DELETE FROM project_status_pivot WHERE project_id = '.$_GET["id"];
$deleteResult = $connection->prepare($delete);
$deleteResult->execute();
$delete = 'DELETE FROM projects WHERE id = '.$_GET["id"];
$deleteResult = $connection->prepare($delete);
$deleteResult->execute();		
header('Location: index.php');