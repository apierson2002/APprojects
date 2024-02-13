<?php
// -----------------------------------------------
require("phpheader.php");
displayMemberList();
require("phpfooter.php");
// -----------------------------------------------
function displayMemberList() {
    $background = 0;
    echo <<<HTMLBLOCK
    <table class="center">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Experation date</th>
        </tr>
HTMLBLOCK;

    require("credentials.php");
    $db = mysqli_connect($hostname, $username, $password, $database);
    if (!$db) {
        die("Unable to connect to database: " . mysqli_error($db));
    }

	$useDB = mysqli_select_db($db, 'namb');
	if(!$useDB){
		die("select database failed" . mysqli_error());
	}

    $members = mysqli_query($db, 'SELECT name,email,expires FROM members ORDER BY name');
    if (!$members) {
        die("Query failed: " . mysqli_error($db));
    }
	
    while ($row = mysqli_fetch_array($members)) {
        $name = $row[0];
        $joinDate = $row[1];
        $status = $row[2];

        $rowStyle = ($background++ % 2 == 0) ? "background-color:white" : "background-color:lightgrey";
        echo <<<TABLEDATA
            <tr style="$rowStyle">
                <td>$name</td>
                <td>$joinDate</td>
                <td>$status</td>
            </tr>
TABLEDATA;
    }
    echo "</table>\n";
	echo "<br>";
    mysqli_close($db);
}
?>
