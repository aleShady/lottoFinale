<?php

require ('function.php');

$files = getFileInFolder('pdf');

?>
<form method="POST" action="?setFile">
	<select name="file">
	<?php
		foreach($files as &$file){
			if($file != '.' && $file != '..'){
				?>
				<option value="<?= $file; ?>"><?= $file; ?></option>
				<?php
			}
		}
	?>
	</select>
	<input type="submit" value="Invia">
</form>

<?php

	if(isset($_GET['setFie']){
		getFileInfo($_POST['file']);
	}
?>