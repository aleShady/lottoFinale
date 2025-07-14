<!DOCTYPE html>
<html>
<head><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOTTO</title>
    	<link rel="stylesheet" type="text/css" href="../css/style.css">

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

<!--  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">-->
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" rel="stylesheet">


    <link rel="stylesheet" type="text/css" href="../js/dhtmlxSuite_v451/skins/terrace/dhtmlx.css" />
</head>
<body>
     <div  class="container-fluid">
	<div id="header">
    	<img src="../img/back.png" class="btnBack" onClick="window.location.href = '..'">
        <div class="title">quadrature</div>
    </div>
    <!------------------------------------------------------------------->
   
    <div id="printer">
    	
    </div>
    <div id="toolbar" style="padding: 20px 0px 0px 20px">
        	<div style="display: inline-block; vertical-align:top">
                Selezione Anno
                <select id="cmb_Year" class="selectCustom">
                    <?php
                        for($i=date("Y"); $i>=1871; $i--) {
                            echo '<option value="' . $i . '">' . $i . '</option>';
                        }
                        
                    ?>
                </select>
             
            </div>
            <table style="vertical-align:top;display:inline-block;margin-left:100px;">
            	<tr>
                	<td align="right">Ambi uniti</td>
                    <td><input type="radio" name="ambi" value="uniti"checked="checked"></td>
                </tr>
                <tr>
                	<td>Ambi non uniti isotopi</td>
                    <td><input type="radio" name="ambi" value="unitiN"></td>
                </tr>
            </table>
            
            <table style="display:inline-block;margin-left:100px;">
            	<tr>
                	<td align="right">Tripla figurale 1-4-7</td>
                    <td><input type="radio" name="tripla" value="1-4-7" checked="checked"></td>
                </tr>
                <tr>
                	<td>Tripla figurale 2-5-8</td>
                    <td><input type="radio" name="tripla" value="2-5-8"></td>
                </tr>
                 <tr>
                	<td>Tripla figurale 3-6-9</td>
                    <td><input type="radio" name="tripla" value="3-6-9"></td>
                </tr>
            </table>
            
            <input type="button" value="ESEGUI" id="btnEsegui" title="Esegui">
        </div><br><br>
        <div id="myBoard" style="display:none;">
        	<div id="boardToolbar">
            	<div id="btnDestroso" title="Destroso"class="typeActive">DESTROSO</div>
                <div id="btnSinistroso" title="Sinistroso">SINISTROSO</div>
                <div id="btnNext" title="Next">>></div>
                <div id="btnPrev" title="Prev"><<</div>
            </div>
            <div id="contentDraw">
            <!-- <span style="font-weight:bold;font-size:13px;margin-left: 10px;color:#888;"><span id="actual">-</span>/<span id="total">-</span></span>
           		-->
                <span style="font-weight:bold;font-size:13px;margin-left: 10px;color:#888;"><input type="text" id="actual">/<span id="total">-</span></span>
                <br><br>
            	<table id="oldTable" align="center"  cellpadding="2" cellspacing="0">
                	<tr>
                    	<td>N:</td>
                        <td id="estrazione_1"></td>
                        <td>del</td>
                        <td id="data_1" style="width:150px"></td>
                        <td id="ruota_1" style="padding-right:20px"></td>
                        <td id="estratti_1" style="padding-right:15px"></td>
                        <td id="figure_1" style="padding-right:70px"></td>
                        <td id="val1_1" class="estratto" style="text-align:right;padding-right:60px;"></td>
                        <td id="sopra" class="contorno" style="text-align:right;padding-right:50px;"></td>
                        <td id="val2_1" class="estratto borderRight" style="text-align:right;padding-right:20px;"></td>
                        <td id="somma_1" class="sOrizzontali" style="text-align:right;padding-left:20px;"></td>
                        <td id="somma_diag_1" class="sVerticali" style="text-align:right;padding-left:30px;"></td>
                    </tr>
                    <tr>
                    	<td id="sinistra" class="contorno" colspan="8" style="text-align:right;padding-right:60px;"></td>
                        <td id="diagonale" class="diagonale" style="text-align:right;padding-right:42px;"></td>
                        <td id="destra" class="contorno borderRight" style="text-align:right;padding-right:20px;"></td>
                    </tr>
                   	<tr>
                    	<td class="borderBottom">N:</td>
                        <td id="estrazione_2" class="borderBottom"></td>
                        <td class="borderBottom">del</td>
                        <td id="data_2" class="borderBottom" style="width:150px"></td>
                        <td id="ruota_2" class="borderBottom" style="padding-right:20px"></td>
                        <td id="estratti_2" class="borderBottom" style="padding-right:15px"></td>
                        <td id="figure_2" class="borderBottom" style="padding-right:70px"></td>
                        <td id="val1_2" class="estratto borderBottom" style="text-align:right;padding-right:60px;"></td>
                        <td id="sotto" class="contorno borderBottom" style="text-align:right;padding-right:50px;"></td>
                        <td id="val2_2" class="estratto borderBottom borderRight" style="text-align:right;padding-right:20px;"></td>
                        <td id="somma_2" class="sOrizzontali borderBottom" style="text-align:right;padding-left:20px;"></td>
                        <td id="somma_diag_2" class="sVerticali borderBottom" style="text-align:right;padding-left:30px;"></td>
                    </tr>
                     <tr>
                    
                        <td id="somma_comune" class="sComune borderRight" style="text-align:right; padding-right:92px;" colspan="10"></td>
                        <td id="raddoppio_somma_comune" class="sComune" style="text-align:center" colspan="3"></td>
                    </tr>
                </table>
            </div><br><br>
            <div style="clear:both;text-align:center;">
            	<span style="color:#009900; font-weight:bold; font-size:12px; padding:0 10px 0 10px;">ESTRATTI</span>
                <span style="color:#009999; font-weight:bold; font-size:12px; padding:0 10px 0 10px;">CONTORNO</span>
                <span style="color:#CC00CC; font-weight:bold; font-size:12px; padding:0 10px 0 10px;">DIAGONALE</span>
                <span style="color:#800000; font-weight:bold; font-size:12px; padding:0 10px 0 10px;">S. ORIZZONTALE</span>
                <span style="color:#CC9900; font-weight:bold; font-size:12px; padding:0 10px 0 10px;">S. VERTICALE</span>
                <span style="color:#006699; font-weight:bold; font-size:12px; padding:0 10px 0 10px;">S. COMUNE</span>
            </div>
            
       <br>
            <div id="btnSingola" title="Stampa">STAMPA</div>
        </div>
        <div class="row">
                    <div class="col-md-12">

          <div class="table-responsive">
            <h3 id="anno"></h3>
            <table id="sestine" style="width: 100%;" class="table hover table-striped table-bordered "  >
                <thead>
            <tr>
                <td>Sestina</td>
                <td>Esiti</td>
                <td>EsitiPositivi</td>
                <td>EsitiNegativi</td>
                <td>Ambi</td>
                <td>Numero terni</td>
                <td>Terno</td>
            </tr></thead><tbody>
     <?php	
     include '../../Classes/DBM.php';

    include '../../Classes/Quadrature.php';
    
    $db = new DBM();
    $result = $db->read ( "SELECT * FROM sest2016" );
    foreach ( $result as $print )   {
    ?>
   
      
            <tr>
                <td id="sestina"><?php echo $print['sestina'];?></td>
                <td id="esiti"><?php echo $print['Esiti'];?></td>
                <td id="esitiPositivi"><?php echo $print['EsitiPositivi'];?></td>
                <td id="esitiNegativi"><?php echo $print['EsitiNegativi'];?></td>
                <td id="ambi"><?php echo $print['Ambi'];?></td>
                <td id="nTerni"><?php echo $print['nTerni'];?></td>
                <td id="terno"><?php echo $print['terno'];?></td>
            </tr>
           
	
   
        <?php }
  ?>           </tbody> </table>
                        </div> 

            </div> 
        </div>   
    </div>
    <!------------------------------------------------------------------->
    <script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="../js/jQuery.print.js"></script>
    <script type="text/javascript" src="../js/dhtmlxSuite_v451/codebase/dhtmlx.js"></script>
<!--	<script type="text/javascript" src="../quadratureTest/quadrature.js"></script>-->
<!--    <script type="text/javascript" src="main.js"></script>-->
	    <script type="text/javascript" src="../quadratureTest/quadrature.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>


</body>
</html>

<script type="text/javascript">
$(document).ready(function(){
    $('#sestine').DataTable( {
    paging: true,
     responsive: true,
     
} );
});
</script>