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
									 project_status_pivot.status_id AS statusid, owners.id AS owid, owners.name AS owner, owners.email AS email
		  					 	 FROM projects
		  					 	 LEFT JOIN project_status_pivot ON projects.id = project_status_pivot.project_id
		  					 	 LEFT JOIN project_owner_pivot ON projects.id = project_owner_pivot.project_id
		  					 	 LEFT JOIN owners ON owners.id = project_owner_pivot.owner_id
									 WHERE projects.id = '.$_GET["id"];
	$resultProject = $connection->prepare($queryProject);
	$resultProject->execute();
	while($rowProject = $resultProject->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
		print('<form class="project" action="save.php?id='.$rowProject["id"].'" method="GET">');
		print('<input name="id" type="hidden" value="'.$rowProject["id"].'"></input>');
		print('<input name="owid" type="hidden" value="'.$rowProject["owid"].'"></input>');
		print('<div>Cím</div>');
		print('<div><input id="projecttitle" name="projecttitle" type="text" value="'.$rowProject["title"].'" required></input></div>');
		print('<div>Leírás</div>');
		print('<div><textarea name="projectdesc" required>'.$rowProject["description"].'</textarea></div>');
		print('<div>Státusz</div>');
		$queryStatus = 'SELECT statuses.id, statuses.name
										FROM statuses';
		$resultStatus = $connection->prepare($queryStatus);
		$resultStatus->execute();
		print('<div><select name="statusid" size = 1>');
		while ($rowStatus = $resultStatus->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
			if($rowStatus["id"] == $rowProject["statusid"]){
				print('<option value="'.$rowStatus["id"].'" selected="selected">'.$rowStatus["name"].'</option>');
			} else {
				print('<option value="'.$rowStatus["id"].'">'.$rowStatus["name"].'</option>');
			}
		}
		print('</select></div>');
		print('<div>Kapcsolattartó neve</div>');
		print('<div><input name="owner" type="text" value="'.$rowProject["owner"].'" required></input></div>');
		print('<div>Kapcsolattartó e-mail címe</div>');
		print('<div><input name="email" type="text" value="'.$rowProject["email"].'" required></input></div>');
		print('<div><button class="buttonblue" type="submit">Mentés</button></div>');
		print('</form>');
	}
} else {
		print('<form class="project" action="save.php?id=" method="GET">');
		print('<input name="id" type="hidden" value=""></input>');
		print('<input name="owid" type="hidden" value=""></input>');
		print('<div>Cím</div>');
		print('<div><input id="projecttitle" name="projecttitle" type="text" value="" required></input></div>');
		print('<div>Leírás</div>');
		print('<div><textarea name="projectdesc" required></textarea></div>');
		print('<div>Státusz</div>');
		$queryStatus = 'SELECT statuses.id, statuses.name
										FROM statuses';
		$resultStatus = $connection->prepare($queryStatus);
		$resultStatus->execute();
		print('<div><select id="statusid" size = 1>');
		$i = 0;
		while ($rowStatus = $resultStatus->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
			if( $i == 0){
				print('<option value="'.$rowStatus["id"].'" selected="selected">'.$rowStatus["name"].'</option>');
			} else {
				print('<option value="'.$rowStatus["id"].'">'.$rowStatus["name"].'</option>');
			}
			$i += 1;
		}
		print('</select></div>');
		print('<div>Kapcsolattartó neve</div>');
		print('<div><input name="owner" type="text" value="" required></input></div>');
		print('<div>Kapcsolattartó e-mail címe</div>');
		print('<div><input name="email" type="text" value="" required></input></div>');
		print('<div><button class="buttonblue" type="submit">Mentés</button></div>');
		print('</form>');
}
