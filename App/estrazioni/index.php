<!DOCTYPE html>
<html>
<head>
    <title>LOTTO</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="../js/dhtmlxSuite_v451/skins/terrace/dhtmlx.css" />
</head>
<body>
	<div id="header">
    	<img src="../img/back.png" class="btnBack" onClick="window.location.href = '..'">
        <div class="title">Estrazioni</div>
    </div>
    <!------------------------------------------------------------------->
    
    <div id="toolbar">
    	
        <div class="label">
            Anno
            <select id="cmb_Year" class="selectCustom">
                <?php
                    for($i=date("Y"); $i>=1871; $i--)
                        echo '<option value="' . $i . '">' . $i . '</option>';
                ?>
            </select>
        </div>
		
        <div class="label">        
            Estrazione
            <select id="cmb_Est" class="selectCustom"></select>
        </div>
        
    </div>
    
    <div id="extraction_Table"></div>
    <div id="distance_Table"></div>
    
    
    <!------------------------------------------------------------------->
    <script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="../js/dhtmlxSuite_v451/codebase/dhtmlx.js"></script>
    <script type="text/javascript" src="main.js"></script>
</body>
</html>