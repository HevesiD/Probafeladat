<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="utf-8">
	<link href="main.css" type="text/css" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<div class="topnav">WeLove Test
		<a href="index.php">Projektlista</a>
		<a class="active" href="edit_create.php?id=">Szerkesztés/Létrehozás</a>
	</div>
</body>
<?php
include_once 'db.php';
global $connection;
if($_GET["id"] != "") {
	$queryProject = 'SELECT projects.id AS id, projects.title AS title, projects.description AS description,
									 project_status_pivot.status_id AS statusid, owners.name AS owner, owners.email AS email
		  					 	 FROM projects
		  					 	 LEFT JOIN project_status_pivot ON projects.id = project_status_pivot.project_id
		  					 	 LEFT JOIN project_owner_pivot ON projects.id = project_owner_pivot.project_id
		  					 	 LEFT JOIN owners ON owners.id = project_owner_pivot.owner_id
									 WHERE projects.id = '.$_GET["id"];
	$resultProject = $connection->prepare($queryProject);
	$resultProject->execute();
	while($rowProject = $resultProject->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
		print('<form class="project" method="GET">');
		print('<div>Cím</div>');
		print('<div><input name="projecttitle" type="text" value="'.$rowProject["title"].'"></input></div>');
print('<div>Leírás</div>');
		print('<div><textarea name="projectdesc">'.$rowProject["description"].'</textarea></div>');
		print('<div>Státusz</div>');
		$queryStatus = 'SELECT statuses.id, statuses.name
										FROM statuses';
		$resultStatus = $connection->prepare($queryStatus);
		$resultStatus->execute();
		print('<div><select name="statusid" size = 1>');
		while ($rowStatus = $resultStatus->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
			if($rowStatus["id"] == $rowProject["statusid"]){
				print('<option value='.$rowStatus["id"].'selected="selected">'.$rowStatus["name"].'</option>');
			} else {
				print('<option value='.$rowStatus["id"].'>'.$rowStatus["name"].'</option>');
			}
		}
		print('</select></div>');
		print('<div>Kapcsolattartó neve</div>');
		print('<div><input name="owmer" type="text" value="'.$rowProject["owner"].'"></input></div>');
		print('<div>Kapcsolattartó e-mail címe</div>');
		print('<div><input name="email" type="text" value="'.$rowProject["email"].'"></input></div>');
	}
} else {
		print('<form class="project" method="GET">');
		print('<div>Cím</div>');
		print('<div><input name="projecttitle" type="text" value=""></input></div>');
		print('<div>Leírás</div>');
		print('<div><textarea name="projectdesc"></textarea></div>');
		print('<div>Státusz</div>');
		$queryStatus = 'SELECT statuses.id, statuses.name
										FROM statuses';
		$resultStatus = $connection->prepare($queryStatus);
		$resultStatus->execute();
		print('<div><select id="statusid" size = 1>');
		while ($rowStatus = $resultStatus->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
			print('<option value='.$rowStatus["id"].'>'.$rowStatus["name"].'</option>');
		}
		print('</select></div>');
		print('<div>Kapcsolattartó neve</div>');
		print('<div><input name="owner" type="text" value=""></input></div>');
		print('<div>Kapcsolattartó e-mail címe</div>');
		print('<div><input name="email" type="text" value=""></input></div>');
}
print('<button class="buttonblue"><a href="save.php?
			 projectid='.$_GET['projectid'].'&
			 projecttitle='.$_GET['projecttitle'].'&
			 projectdesc='.$_GET['projectdesc'].'&
			 statusid='.$_GET['statusid'].'&
			 owner='.$_GET['owner'].'&
			 email='.$_GET['email'].'">Mentés</a></button>');
print('</form>');
