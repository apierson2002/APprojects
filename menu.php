<?php
	require("phpheader.php");
	echo <<<HTMLBLOCK
		<img src="bike.jpeg" alt="Andrew Pierson" class ="center3">
		<br>
		<div class="menuborder">
		<table class="center2">
			<tr>
				<a href="display.php">Display List of Members</a></td>
				<br>
			</tr>
			<tr>
				 <a href="add.php">Add New Member</a></td>
				 <br>
			</tr>
			<tr>
				 <a href="edit2.php">Edit Member Credentials</a></td>
				 <br>
			</tr>
			<tr>
				 <a href="delete.php">Delete Member(s)</a></td>
				 <br>
			</tr>
		</table>
		</div>
		<br>
HTMLBLOCK;
	require("phpfooter.php");
?>
