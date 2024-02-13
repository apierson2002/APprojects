<?php
// ---------------------------------------------------------------------------
require("phpheader.php");
require("credentials.php");
$db = mysqli_connect($hostname, $username, $password, $database);
if (!$db) {
    die("Unable to connect to database" . mysqli_error($db));
}

if(isset($_POST['delete_button'])) {
    deleteMember($db);
}
displayList($db);
require("phpfooter.php");

// --------------------------------------------------------------------------
function deleteMember($db) {
    $query = "DELETE FROM members WHERE ID = ?";
    $stmt = mysqli_prepare($db, $query);

    foreach ($_POST['delete'] as $id) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
    }

    mysqli_stmt_close($stmt);
}

// -------------------------------------------------------------------------
function displayList($db) {
    $background = 0;
    echo <<<HTMLBLOCK
    <table class="center">
        <tr>
        <form method="POST" action="delete.php">
            <th>Delete</th> 
            <th>Name</th>
            <th>Email</th>
            <th>Expiration Date</th>
        </tr>
HTMLBLOCK;
    $members = mysqli_query($db, 'SELECT ID, name, email, expires FROM members ORDER BY name');
    if(!$members) {
        die("Query failed: " . mysqli_error($db));
    }

    while($row = mysqli_fetch_array($members)) {
        [$id, $name, $email, $expires] = $row;
        $bgcolor = $background++ % 2 == 0 ? "white" : "lightgrey";
        echo <<<TABLEDATA
            <tr style="background-color:$bgcolor;">
                <td><input type="checkbox" name="delete[]" value="$id"></td>
                <td>$name</td>
                <td>$email</td>
                <td>$expires</td>
            </tr>
TABLEDATA;
    }
    echo "      </table>\n";
    echo "<br>";
    echo "<button type='submit' name='delete_button' class='delete_button'>Delete Selected</button>";
    echo "</form>";
	echo"<br>";
	echo"<br>";
}
mysqli_close($db);
?>
