<html>
<head>
	<title>Modulo3</title>
    
    <!-- TEMPLATE -->
    	<link rel="stylesheet" type="text/css" href="../css/style.css">
        <link rel="stylesheet" type="text/css" href="custom.css">
        
        <script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="../js/jQuery.print.js"></script>
        <script type="text/javascript" src="script.js"></script>
    <!-- END TEMPLATE -->
    
</head>
<body>
<!-- DONT TOUCH THIS DIV -->
<div id="printer">
    
</div>
<!-- /////////// -->
<div class="spinner">
<div class="bounce1"></div>
<div class="bounce2"></div>
<div class="bounce3"></div>
</div>
<div id="wrapper">

	<div id="header">
    	<img src="../img/back.png" class="btnBack" onClick="window.location.href = '..'">
        <div class="title">Modulo Tre</div>
    </div><!-- header -->
  
    <div id="content" style="padding:20px">
    	
        <div id="HMI">
        	Seleziona Anno
            <select id="cmb_Year" class="selectCustom" style="margin-right: 100px">
				<?php
                for($i=date("Y"); $i>=1871; $i--)
                	echo '<option value="' . $i . '">' . $i . '</option>';
                ?>
            </select>
            Composizione
            <input type="text" id="txtSestina" class="TextboxCustom" style="margin-right: 100px">
            
            Ordine
            <select id="cmb_Ordine" class="selectCustom" style="margin-right: 100px">
                <option value="destroso">Destroso</option>
                <option value="sinistroso">Sinistroso</option>
            </select>
            
            Tripla
            <select id="cmb_Tripla" class="selectCustom">
                <option value="1-4-7">1-4-7</option>
                <option value="2-5-8">2-5-8</option>
                <option value="3-6-9">3-6-9</option>
            </select>
            
            <input type="button" value="ESEGUI" id="btnEsegui" title="Esegui">
            <div style="clear:both"></div>
        </div>
        <div id="noData" style="font-size:30px;font-weight:bold;color:#CCC;padding-top:300px;text-align:center;display:none;">
       	 	RICERCA FINITA SENZA RISULTATI
        </div>
        <div id="container" style="padding-left:120px;padding-top:20px;display:none;">
        
        	<div id="sestinaValues">
            <!--<div class="sestinaItem sestinaItemBorderRight">0</div><div class="sestinaItem sestinaItemBorderRight">0</div><div class="sestinaItem sestinaItemBorderRight">1</div><div class="sestinaItem sestinaItemBorderRight">2</div><div class="sestinaItem sestinaItemBorderRight">3</div><div class="sestinaItem">4</div>
            --></div>
           	<div style="clear:both"></div>
            <div id="sestinaResult" style="float:left">
           
            </div>
        	<div id="casiFalliti">
            	Casi falliti [5]
            </div>
       	</div>
    </div>
    
</div> <!-- wrapper -->



</body>
</html>