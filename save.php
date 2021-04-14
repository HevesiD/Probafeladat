<?php
include_once 'db.php';
global $connection;
if($_GET["id"] != "") {
  $queryUpdate = 'UPDATE projects
                  SET title = "'.$_GET["projecttitle"].'",
                  description = "'.$_GET["projectdesc"].'"
                  WHERE id = '.$_GET["id"];
  $resultUpdate = $connection->prepare($queryUpdate);
  $resultUpdate->execute();
  $queryUpdate = 'UPDATE project_status_pivot
                  SET status_id = '.$_GET["statusid"].'
                  WHERE project_id = '.$_GET["id"];
  $resultUpdate = $connection->prepare($queryUpdate);
  $resultUpdate->execute();
  $queryOwner = 'SELECT id
                 FROM owners
                 WHERE email = "'.$_GET["email"].'"';
  $resultOwner = $connection->prepare($queryOwner);
  $resultOwner->execute();
  if($resultOwner->rowCount() > 0) {
    $rowOwner = $resultOwner->fetch();
    if($rowOwner["id"] != $_GET["owid"]){
      $queryUpdate = 'UPDATE project_owner_pivot
                      SET owner_id = '.$_GET["owid"].'
                      WHERE project_id = '.$_GET["id"];
      $resultUpdate = $connection->prepare($queryUpdate);
      $resultUpdate->execute();

      $resultUpdate = 'UPDATE owners
                       SET name = "'.$_GET["owner"].'",
                       WHERE email = ".'.$_GET["email"].'."';
    }
  } else {
    $queryOwnerId = 'SELECT id
                     FROM owners
                     ORDER BY id DESC';
    $resultOwnerId = $connection->prepare($queryOwnerId);
    $resultOwnerId->execute();
    $rowOwnerId = $resultOwnerId->fetch();
    $rowOwnerId["id"] += 1;
    $queryInsert = 'INSERT INTO owner(id, name, email) VALUES("'.$rowOwnerId["id"].'","'.$_GET["owner"].'","'.$_GET["email"].'")
                    INSERT INTO project_owner_pivot(project_id, owner_id) VALUES('.$_GET["id"].','.$rowOwnerId["id"].')';
  }
} else {
  $queryId = 'SELECT id
              FROM projects
              ORDER BY id DESC
              LIMIT 1';
  $resultId = $connection->prepare($queryId);
  $resultId->execute();
  $rowId = $resultId->fetch();

  $rowId["id"] += 1;
  $queryInsert = 'INSERT INTO projects(id, title, description) VALUES('.$rowId["id"].',"'.$_GET["projecttitle"].'","'.$_GET["projectdesc"].'")';
  $resultInsert = $connection->prepare($queryInsert);
  $resultInsert->execute();
  echo $_GET["statusid"];
  $queryInsert = 'INSERT INTO project_status_pivot(project_id, status_id) VALUES('.$rowId["id"].','.$_GET["statusid"].')';
  $resultInsert = $connection->prepare($queryInsert);
  $resultInsert->execute();
  $queryOwner = 'SELECT id
                 FROM owners
                 WHERE email = '.$_GET["email"];
  $resultOwner = $connection->prepare($queryOwner);
  if($resultOwner->fetchColumn() > 0) {
    $rowOwner = $resultOwner->fetch();
    $queryUpdate = 'INSERT INTO project_owner_pivot(project_id, owner_id) VALUES('.$rowOwner["id"].','.$rowId["id"].')';
    $resultUpdate = $connection->prepare($queryUpdate);
    $resultUpdate->execute();
  } else {
    $queryOwnerId = 'SELECT id
                     FROM owners
                     ORDER BY id DESC';
    $resultOwnerId = $connection->prepare($queryOwnerId);
    $resultOwnerId->execute();
    $rowOwnerId = $resultOwnerId->fetch();
    $rowOwnerId["id"] = $rowOwnerId["id"] + 1;
    $queryInsert = 'INSERT INTO owner(id, name, email) VALUES('.$rowOwnerId["id"].','.$_GET["owner"].','.$_GET["email"].')
                    INSERT INTO project_owner_pivot(project_id, owner_id) VALUES('.$rowId["id"].','.$rowOwnerId["id"].')';
  }
}
header('Location: index.php');
