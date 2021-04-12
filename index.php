<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="utf-8">
	<link href="main.css" type="text/css" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script>
		
	</script>
</head>
<body>
	<div class="topnav">WeLove Test
		<a class="active" href="index.php">Projektlista</a>
		<a href="edit_create.php">Szerkesztés/Létrehozás</a>
	</div>
</body>
<?php
include_once 'db.php';
global $connection;
$query = 'SELECT projects.id AS id, projects.title AS title, statuses.name AS status, owners.name AS owner, owners.email AS email
		  FROM projects
		  LEFT JOIN project_status_pivot ON projects.id = project_status_pivot.project_id
		  LEFT JOIN statuses ON statuses.id = project_status_pivot.status_id
		  LEFT JOIN project_owner_pivot ON projects.id = project_owner_pivot.project_id
		  LEFT JOIN owners ON owners.id = project_owner_pivot.owner_id';
$result = $connection->prepare($query);
$result->execute();
while ($row = $result->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
	print('<form class="center">');
	print('<div class="status">'.$row["status"].'</div>');
    print('<div class="title">'.$row["title"].'</div>');
	print('<div class="contact">'.$row["owner"].' ('.$row["email"].')</div>');
	print('</p>');
	print('<button type="button"><a href="edit_create.php">Szerkesztés</a></button><button type="button"><a href="deleteProject.php?id='.$row["id"].'">Törlés</a></button>');
	print('</form>');
}
