<?php 
require('database.php');

$itemnum = filter_input(INPUT_POST, 'itemnum', FILTER_VALIDATE_INT);
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


if($itemnum) {
    $query = 'UPDATE todoitems
                SET Title = :title, Description = :description
                    WHERE ItemNum = :itemnum';
    $statement = $db->prepare($query);
    $statement->bindValue(':itemnum', $itemnum);
    $statement->bindValue(':title', $title);
    $statement->bindValue(':description', $description);
    $success = $statement->execute();
    $statement->closeCursor();
}

$updated = true;

include('index.php');
