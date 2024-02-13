<?php
// -----------------------------------------------
require("phpheader.php");
if (isset($_POST['addButton'])) {
    addMember();
} else {
    displayForm();
}
require("phpfooter.php");
// -----------------------------------------------
function displayForm(){
    echo <<<FORMBLOCK
        <form method="POST" action="add.php">
        <table class="center">
            <tr>
                <th><label for="name">Member Name: </label></th>
				<th><label for="email">Email: </label></th>
                <th><label for="expDate">Experation Date: </label></th>
            </tr>
            
            <tr>
                <td><input type="text" id="name" name="name" required maxlength="64" placeholder="Member's Name" pattern="^[0-9a-zA-Z !-.]{1,64}$" autocomplete="off"></td>
                <td><input type="email" id="email" name="email" required maxlength="256" placeholder="Email" autocomplete="off"></td>
				<td><input type="date" id="expDate" name="expDate" required autocomplete="off"></td>
            </tr>
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="text-align:middle;"><input type="submit" name="addButton" value="Add Member"></td>
            </tr>
        </table>
        </form>
		<br>
FORMBLOCK;
}
// -----------------------------------------------
function addMember(){
    $name = $_POST['name'];
    $expDate = $_POST['expDate'];
    $email = $_POST['email'];
    $name = trim($name);
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $expDate = trim($expDate);
    $email = trim($email);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if ($name != false && $expDate != false && $email != false) {
        require("credentials.php");
        $db = mysqli_connect($hostname, $username, $password, $database);
        if (mysqli_connect_errno()) {
            die("unable to connect to DB" . mysqli_connect_error());
        }
        $query = mysqli_prepare($db, "INSERT INTO members (name, expires, email) VALUES(?,?,?)");
        mysqli_stmt_bind_param($query, 'sss', $name, $expDate, $email);
        if (mysqli_stmt_execute($query)) {
            echo <<<SUCCESSBLOCK
                <div class="center">
                <h2>Success! Member added to the database</h2>
                </div>
SUCCESSBLOCK;
        } else {
            echo <<<FAILBLOCK
                <div class="center">
                <h2>Error! Unable to add member to the database</h2>
                </div>
FAILBLOCK;
        }
        mysqli_close($db);
    } else {
        die("Invalid inputs");
    }
}
?>
