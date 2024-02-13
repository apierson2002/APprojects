<?php
require("phpheader.php");
require("credentials.php");
$db = mysqli_connect($hostname, $username, $password, $database);
$useDB = mysqli_select_db($db, 'namb');
if(!$useDB){
	die("select database failed" . mysqli_error());
}
if(mysqli_connect_errno()){
    die("Unable to connect to the database: " . mysqli_connect_error());
}
if(isset($_POST['updateButton'])){
	
    displayEditBlock($db);
}
elseif(isset($_POST['EditButton'])){
    processEditBlock($db);
}
else{
    displaySelection($db);
}

// -----------------------------------------------
function displaySelection($db){
	$result = mysqli_query($db, " SELECT ID,name FROM members ORDER BY name");
	echo <<<FORMBLOCK
		<form method="POST" name="Updated" action="edit2.php">
			<div class="updateLabel">
			<label for="evSelect"><h3>Select an Member to edit:</h3></label>
			</div>
			<br>
			<select name="memberid">
FORMBLOCK;
    while($row = mysqli_fetch_array($result)){
        $id = $row[0];
        $name = $row[1];
        echo "<option value=\"$id\">$name</option>";
    }
	
    echo <<<FORMBLOCK
			</select>
			<br>
			<br>
            <input type="submit" name="updateButton" value="Edit Member">
        </form>
		<br>
FORMBLOCK;
	require("phpfooter.php");
}
// -----------------------------------------------
function displayEditBlock($db){
    if (mysqli_connect_error()){
        die("Unable to connect to the database" . mysqli_connect_error());
    }

    if (isset($_POST['updateButton']))
		$id = $_POST['memberid'];
	
    $query = mysqli_prepare($db, "SELECT name, email, expires FROM members WHERE ID=?");
    mysqli_stmt_bind_param($query, "i", $id);

    if (mysqli_stmt_execute($query)){
        $result = mysqli_stmt_get_result($query);
		$row = mysqli_fetch_array($result);
		$name = $row[0];
		$email = $row[1];
		$expDate = $row[2];
		
		
		echo <<<FORMBLOCK
			<form method="POST" action="edit2.php">
				<table class="center">
					<tr>
						<th><label for="name">Member Name: </label></th>
						<th><label for="email">Email: </label></th>
						<th><label for="expDate">Experation Date: </label></th>
					</tr>
           
					<tr>
						<td><input type="text" id="name" name="name" required maxlength="64" value="$name" pattern="^[0-9a-zA-Z !-.]{1,64}$" autocomplete="off"></td>
						<td><input type="text" id="email" name="email" required value="$email" pattern="^[A-Za-z0-9]+@[A-Za-z0-9]+\.[A-Za-z]{2,}$" autocomplete="off"></td>
						<td><input type="date" id="expDate" name="expDate" required maxlength="64" value="$expDate" pattern="^\d{4}-\d{2}-\d{2}$" autocomplete="off"></td>
						<td><input type="hidden" id="id" name="id" value="$id" autocomplete="off"></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td style="text-align:middle;"><input type="submit" name="EditButton" value="Update Member"></td>
					</tr>
				</table>
			</form>
			<br>
FORMBLOCK;
mysqli_close($db);
}
}
// -----------------------------------------------
function processEditBlock($db){
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $expires = mysqli_real_escape_string($db, $_POST['expDate']);

    $query = mysqli_prepare($db, "UPDATE members SET name=?, email=?, expires=? WHERE ID=?");
    mysqli_stmt_bind_param($query, "sssi",$name, $email, $expires, $id);

    if(mysqli_stmt_execute($query)){
        echo "Update successful";
    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
    mysqli_stmt_close($query);
	mysqli_close($db);
}
?>
