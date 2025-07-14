<!DOCTYPE html>
<html>
<head>
    <title>LOTTO</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
        
        	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
       <script type="text/javascript" src="../js/dhtmlxSuite_v451/codebase/dhtmlx.js"></script>

        <script type="text/javascript" src="../js/jQuery.print.js"></script>
    <script type="text/javascript" src="main.js"></script>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
	<div id="header">
    	<img src="../img/back.png" class="btnBack" onClick="window.location.href = '..'">
        <div class="title">quadrature</div>
    </div>
    
    <!------------------------------------------------------------------->
    
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
           
            <input type="button" value="Aggiorna dati sestine" id="btnPagSestine" title="Aggiorna dati sestine">
            <input type="button" value="Visualizza dati sestine" id="btnRicerca" title="Visualizza dati sestine">

        </div><br><br>

         <div class="table-responsive">
            <table id="sestine" style="width: 100%; " class="table hover table-striped table-bordered "  >
                        <thead>
                            <tr>
                                <th>Anno</th>
                                <th>Ordine</th>                              
                                <th>Pagina</th>
                                <th>Tripla</th>                                
                               
                            </tr>
                        </thead>
                    </table>
            <!--<div class="sestinaItem sestinaItemBorderRight">0</div><div class="sestinaItem sestinaItemBorderRight">0</div><div class="sestinaItem sestinaItemBorderRight">1</div><div class="sestinaItem sestinaItemBorderRight">2</div><div class="sestinaItem sestinaItemBorderRight">3</div><div class="sestinaItem">4</div>
            --></div>
   <div id="noData" style="font-size:30px;font-weight:bold;color:#CCC;padding-top:300px;text-align:center;display:none;">
       	 	RICERCA FINITA SENZA RISULTATI
        </div>
    <div class="spinner">
<div class="bounce1"></div>
<div class="bounce2"></div>
<div class="bounce3"></div>
    <!------------------------------------------------------------------->
</body>

<!--	<script type="text/javascript" src="../quadratureTest/quadrature.js"></script>-->
<!--    <script type="text/javascript" src="main.js"></script>-->
         

<script type="text/javascript">
     

  $(document).ready(function(){
 $("#btnRicerca").click(function(){
    
   $('.spinner').show();
     $.ajax({
            url: 'stampa.php',
          dataType: "text",
            data:{
            model: JSON.stringify({
               
                "currentYear": $('#cmb_Year').val()
            })
        },
            success: function (elencoSestine) {
                
                     $('.spinner').hide();   

                       $('#sestine').DataTable().clear();
            
            var jsdata = JSON.parse(elencoSestine);
            if(jsdata.length > 0)
                $('#sestine').dataTable().fnAddData(jsdata);
            $('#sestine').DataTable().draw();

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $('.spinner').hide();   

            alert('Errore');
            
        }
        }).done(function() {
       $('#noData').hide();  
         $('.spinner').hide();   
     });
                  

});

    $("#sestine").DataTable({
 "dataType": "json",
  "cache": false,
"columns": [
    { "data": "anno" },
    { "data": "ordine" },    
    { "data": "pagina" },
    { "data": "tripla" },
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

</html>

