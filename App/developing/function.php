<?php

	function getFileinFolder($path){
		
		$files = scandir($path);
		
		return $files;
	}

?>