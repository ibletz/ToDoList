<?php
$newtitle = filter_input(INPUT_POST, "newtitle", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$title = filter_input(INPUT_GET, "title", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <main>
        <header>
            <h1>ToDo List</h1>
        </header>
        <?php
        if (!$newtitle) { ?>
            
            <section>
                <h2>Add a New ToDo Item</h2>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                    <label for='newtitle'>New ToDo Item:</label>
                    <input type="text" id="newtitle" name="newtitle" max_length="20" required>
                    <label for='description'>Description:</label>
                    <input type="text" id="description" name="description" max_length="50" required>

                    <button>Submit</button>
                </form>
            </section>
        <?php  } else { ?>
            <?php require("database.php") ?>
            <?php
            if ($newtitle) {
                $query = 'INSERT INTO todoitems
                                (Title, Description)
                                    VALUES
                                    (:newtitle, :description)';
                $statement = $db->prepare($query);
                $statement->bindValue(':newtitle', $newtitle);
                $statement->bindValue(':description', $description);

                $statement->execute();
                $statement->closeCursor();
            }
            ?>
            <?php
            if ($title || $newtitle) {
                $query = 'SELECT * FROM todoitems
                                WHERE TITLE = :title
                                ORDER BY ItemNum DESC';
                $statement = $db->prepare($query);
                if ($title) {
                    $statement->bindValue(':title', $title);
                } else {
                    $statement->bindValue(':title', $newtitle);
                }
                $statement->execute();
                $results = $statement->fetchAll();
                $statement->closeCursor();
            }
            ?>
            <?php
            if (!empty($results)) { ?>
                <section>
                    <h2>Delete an Item</h2>
                    <?php foreach ($results as $result) {
                        $itemnum = $result["ItemNum"];
                        $title = $result["Title"];
                        $description = $result["Description"];

                    ?>
                    <form class="update" action="update_record.php" method="POST">
                        <input type="hidden" name="itemnum" value="<?php echo $itemnum ?>">
                        <label for="title-<?php echo $itemnum ?>">Title:</label>
                        <input type="text" id="title-<?php echo $itemnum ?>" name="title" value="-<?php echo $title ?>" required>
                        <label for="description-<?php echo $itemnum ?>">Description:</label>
                        <input type="text" id="description-<?php echo $itemnum ?>" name="description" value="-<?php echo $description ?>" required>

                        <button>Update</button>
                    </form>
                    <form class="delete" action="delete_record.php" method="POST">
                        <input type="hidden" name="itemnum" value="<?php echo $itemnum ?>">
                        <button class ="red">Delete</button>
                    </form>
                    <?php } ?>
                </section>
            <?php } else { ?>
                <p>Sorry, no results.</p>
            <?php } ?>
            <a href="<?php echo $_SERVER['PHP_SELF']?>">Go to request form</a>
        <?php } ?>
    </main>
</body>

</html>