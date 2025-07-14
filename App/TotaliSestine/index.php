<html>
<head>
        <title>LOTTO</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- TEMPLATE -->
    	<link rel="stylesheet" type="text/css" href="../css/style.css">
        <link rel="stylesheet" type="text/css" href="custom.css">
        
        <script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="../js/jQuery.print.js"></script>
        <script type="text/javascript" src="script.js"></script>
    <!-- END TEMPLATE -->

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

<!--  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">-->
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet">
    

</head>
<body>
    <div id="printer">
    
    </div>
<div class="spinner">
<div class="bounce1"></div>
<div class="bounce2"></div>
<div class="bounce3"></div>
</div>
    <div id="wrapper">

	<div id="header">
    	<img src="../img/back.png" class="btnBack" onClick="window.location.href = '..'">
        <div class="title">Modulo Tre</div>
    </div>

    <!------------------------------------------------------------------->
   
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
            <input type="text" id="txtSestina" class="TextboxCustom" readonly="true" style="margin-right: 100px">
            
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
             
            
            <input type="button" value="ESTRAI" id="btnRicerca" title="ESTRAI">

            <input type="button" value="AGGIORNA" id="btnEsegui" title="AGGIORNA">
            <div style="clear:both"></div>
        </div>
     
      
        <div id="container" class="nascondimi" style="display:none;" >
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
        </div>   
 <div class="table-responsive">
            <table id="sestine" style="width: 100%; " class="table hover table-striped table-bordered "  >
                        <thead>
                            <tr>
                                <th>Sestina</th>
                                <th>Esiti</th>                              
                                <th>EsitiPositivi</th>
                                <th>EsitiNegativi</th>                                
                                <th>Ambi</th>
                                <th>Terni</th>
                                <th>Quaterne</th>
                                <th>Tripla</th>
                                <th>Ordine</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                    </table>
            <!--<div class="sestinaItem sestinaItemBorderRight">0</div><div class="sestinaItem sestinaItemBorderRight">0</div><div class="sestinaItem sestinaItemBorderRight">1</div><div class="sestinaItem sestinaItemBorderRight">2</div><div class="sestinaItem sestinaItemBorderRight">3</div><div class="sestinaItem">4</div>
            --></div>
   <div id="noData" style="font-size:30px;font-weight:bold;color:#CCC;padding-top:300px;text-align:center;display:none;">
       	 	RICERCA FINITA SENZA RISULTATI
        </div>
  

</body>
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
        
        	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

</html>

<script type="text/javascript">

$("#btnRicerca").click(function(){
    
   $('.spinner').show();
        var anno= $('#cmb_Year').val();
        var tripla= $('#cmb_Tripla').val();
                var ord= $('#cmb_Ordine').val();
    
                  
    $.ajax({
        url: "stampa.php",
        type: "get",
        data: { anno: anno, tripla: tripla, ord: ord },
        success: function(result){
            $('#sestine').DataTable().clear();
            
            var jsdata = JSON.parse(result);
            if(jsdata.length > 0)
                $('#sestine').dataTable().fnAddData(jsdata);
            $('#sestine').DataTable().draw();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert('Errore');
        }
    }).done(function() {
       $('#noData').hide();  
         $('.spinner').hide();   
     });
});
       
       




// needs to implement if it fails
    
    
    
    
    
    
  $(document).ready(function(){
      window.setInterval(function(){
          
$.active  == 0 ?   $('.spinner').hide() : "";
}, 5000);
        
 $("#sestine").DataTable({
 "dataType": "json",
  "cache": false,
"columns": [
    { "data": "sestina" },
    { "data": "Esiti" },    
    { "data": "EsitiPositivi" },
    { "data": "EsitiNegativi" },
    { "data": "Ambi" },
    { "data": "nTerni" },
    { "data": "nQuaterne" },
    { "data": "trip" },
    { "data": "ord" },
    { "data": "isotopi" }

  ],
  dom: 'Bfrtip',
        buttons: [
            'print' 
            
        ],
         "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Italian.json",
             buttons: {
                print: 'Stampa'
            }
        },
          
processing: true,
retrieve: true
});
  
      
      
  });  
    
    
 
 </script>
 
