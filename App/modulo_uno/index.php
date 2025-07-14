<html>
<head>
	<title>Modulo1</title>
    
    <!-- TEMPLATE -->
    	<link rel="stylesheet" type="text/css" href="../css/style.css">
        <link rel="stylesheet" type="text/css" href="custom.css">
        
        <script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="../js/jQuery.print.js"></script>
        <script type="text/javascript" src="script.js"></script>
    <!-- END TEMPLATE -->
    
</head>
<body>
  <div id="printer">
    	<div style="padding:20px">
        
        	1. <div class="titolo">Anno:</div>
            <hr color="red" width="20%" align="left">
            <div id="valueAnno" style="margin-left:150px">
            	2015
            </div><br><br>
            2. <div class="titolo">Tripla Figurale:</div>
            <hr color="red" width="25%" align="left">
            <div id="valueTripla" style="margin-left:150px">
            	1-4-7
            </div><br><br>
             3. <div class="titolo">Composizione di ruote:</div>
            <hr color="red" width="35%" align="left">
            <div id="valueRuote" style="margin-left:150px">
            	Gemellari
            </div><br><br>
             4. <div class="titolo">Composizione di ambi:</div>
            <hr color="red" width="35%" align="left">
            <div style="margin-left:150px">
            	<span id="valueAmbiUniti">23</span>
                
            </div><br><br>
           	  5. <div class="titolo">Diagonale:</div>
            <hr color="red" width="20%" align="left">
            <div id="valueDiagonale" style="margin-left:150px">
            	6
            </div><br><br>
             6. <div class="titolo">Ordine:</div>
            <hr color="red" width="15%" align="left">
            <div id="valueOrdine" style="margin-left:150px">
            	Destorso
            </div><br><br>
            <hr color="green"><br><br>
            	<table>
                	<tr>
                    	<td><div class="titolo">Casi analizzati:</div></td>
                        <td style="padding-left:20px" id="valueCasiAnalizzati">8</td>
                    </tr>
                    	<tr>
                    	<td><div class="titolo">Casi positivi:</div></td>
                      <td style="padding-left:20px" id="valueCasiPositivi">8</td>
                    </tr>
                    	<tr>
                    	<td><div class="titolo">Casi negativi:</div></td>
                        <td style="padding-left:20px" id="valueCasiNegativi">8</td>
                    </tr>
                    	<tr>
                    	<td><div class="titolo">Casi positivi di ambo:</div></td>
                       <td style="padding-left:20px" id="valuePositiviAmbo">8</td>
                    </tr>
                    	<tr>
                    	<td><div class="titolo">Casi positivi di terno:</div></td>
                       <td style="padding-left:20px" id="valuePositiviTerno">8</td>
                    </tr>
                     </tr>
                    	<tr>
                    	<td><div class="titolo">Casi positivi di quaterna:</div></td>
                        <td style="padding-left:20px" id="valuePositiviQuaterna">8</td>
                    </tr>
                     
                </table>
            	
        </div>
    </div>
    
    
<div id="wrapper">

	<div id="header">
    	<img src="../img/back.png" class="btnBack" onClick="window.location.href = '..'">
        <div class="title">Modulo Uno</div>
    </div>
  
    <div id="content" style="padding:20px">
    	
        <table id="tableWrapper">
        	<tr>
                <td class="tableWrapperTD">
                    <center>
                    	<br><br><br><br>
                        <table>
                        	<tr>
                                <td><span class="titleX">ANNO</span></td>
                                <td>
                                    <select id="cmb_Year" class="selectCustom">
										<?php
                                        	for($i=date("Y"); $i>=1871; $i--)
                                        		echo '<option value="' . $i . '">' . $i . '</option>';
                                        ?>
                                    </select>
                                 </td>
                            </tr>
                            
                           	<tr>
                                <td><span class="titleX">TRIPLA</span></td>
                                <td>
                                    <select id="cmb_Tripla" class="selectCustom">
                                        <option value="1-4-7">1-4-7</option>
                                        <option value="2-5-8">2-5-8</option>
                                        <option value="3-6-9">3-6-9</option>
                                    </select>
                               	</td>
                            </tr>
                            
                            <tr>
                                <td><span class="titleX">ORDINE</span></td>
                                <td>
                                    <select id="cmb_Ordine" class="selectCustom">
                                        <option value="destroso">Destroso</option>
                                        <option value="sinistroso">Sinistroso</option>
                                    </select>
                               	</td>
                            </tr>
                        </table>
                    <center>  
                </td>
                <!---->
                <td class="tableWrapperTD">
                	<center>
                        <span class="titleX">RUOTE</span>
                        <br><br>
                            <select id="cmb_Ruote" class="selectCustom">
                                <option value="unite">Unite</option>
                                <option value="diametrali">Diametrali</option>
                                <option value="gemellari">Gemellari</option>
                                <option value="normali">Normali</option>
                            </select>
                        <br><br>
                        <div id="contentRuote" class="border_unite" style="text-align:left !important"></div>
                 	<center>  
                </td>
                <!---->
                <td class="tableWrapperTD">
                	<center>
                    	<span class="titleX">AMBI UNITI</span>
                        <br><br>
                        <div id="contentUniti">
                        	<div class="cAmbiUniti backgroundGiallo activeU" value="1-2;1-5">1 _ 2<br>1 _ 5</div><br>
                            <div class="cAmbiUniti backgroundGiallo" value="1-2;2-3">1 _ 2<br>2 _ 3</div><br>
                            <div class="cAmbiUniti backgroundGiallo" value="1-2;3-4">1 _ 2<br>3 _ 4</div>
                         <hr color="E60000" width="50%">
                            <div class="cAmbiUniti backgroundGiallo" value="1-2;4-5">1 _ 2<br>4 _ 5</div>
                         <hr color="E60000" width="50%">
                            <div class="cAmbiUniti backgroundArancione" value="1-5;2-3">1 _ 5<br>2 _ 3</div><br>
                            <div class="cAmbiUniti backgroundArancione" value="1-5;3-4">1 _ 5<br>3 _ 4</div><br>
                            <div class="cAmbiUniti backgroundArancione" value="1-5;4-5">1 _ 5<br>4 _ 5</div>
                         <hr color="E60000" width="50%">
                         	<div class="cAmbiUniti backgroundVerde" value="2-3;3-4">2 _ 3<br>3 _ 4</div><br>
                            <div class="cAmbiUniti backgroundVerde" value="2-3;4-5">2 _ 3<br>4 _ 5</div>
                         <hr color="E60000" width="50%">
                         	<div class="cAmbiUniti backgroundVerde" value="3-4;4-5">3 _ 4<br>4 _ 5</div>
                        </div>
                    </center>
                </td>
                <!---->
                <td class="tableWrapperTD">
                
                	<center>
                    	<span class="titleX">AMBI NON UNITI, ISOTOPI</span>
                        <br><br>
                        <div id="contentUniti">
                        	<div class="cAmbiUniti backgroundGiallo" value="1-3;1-3">1 _ 3<br>1 _ 3</div><br>
                            <div class="cAmbiUniti backgroundGiallo" value="1-4;1-4">1 _ 4<br>1 _ 4</div>
                         <hr color="E60000" width="50%">
                            <div class="cAmbiUniti backgroundArancione" value="2-4;2-4">2 _ 4<br>2 _ 4</div><br>
                            <div class="cAmbiUniti backgroundArancione" value="2-5;2-5">2 _ 5<br>2 _ 5</div>
                         <hr color="E60000" width="50%">
                         	<div class="cAmbiUniti backgroundVerde" value="3-5;3-5">3 _ 5<br>3 _ 5</div><br>
                            <div class="cAmbiUniti backgroundVerde" value="1-2;1-2">1 _ 2<br>1 _ 2</div><br>
                            <div class="cAmbiUniti backgroundVerde" value="1-5;1-5">1 _ 5<br>1 _ 5</div><br>
                            <div class="cAmbiUniti backgroundVerde" value="2-3;2-3">2 _ 3<br>2 _ 3</div><br>
                            <div class="cAmbiUniti backgroundVerde" value="3-4;3-4">3 _ 4<br>3 _ 4</div><br>
                            <div class="cAmbiUniti backgroundVerde" value="4-5;4-5">4 _ 5<br>4 _ 5</div>
                        </div>
                    </center>
                </td>
                <!---->
                <td class="tableWrapperTD">
                	<center>
                    	<span class="titleX">DIAGONALI</span>
                         <br><br>
                         <div class="contentDiaognale activeD" value="6">6</div><br>
                         <div class="contentDiaognale" value="9">9</div><br>
                         <div class="contentDiaognale" value="12">12</div><br>
                         <div class="contentDiaognale" value="15">15</div><br>
                         <div class="contentDiaognale" value="18">18</div><br>
                         <div class="contentDiaognale" value="21">21</div><br>
                         <div class="contentDiaognale" value="24">24</div><br>
                         <div class="contentDiaognale" value="27">27</div><br>
                         <div class="contentDiaognale" value="30">30</div><br>
                         <div class="contentDiaognale" value="33">33</div><br>
                         <div class="contentDiaognale" value="36">36</div><br>
                         <div class="contentDiaognale" value="39">39</div><br>
                         <div class="contentDiaognale" value="42">42</div><br>
                         <div class="contentDiaognale" value="45">45</div>
                    </center>
                </td>
            <tr>
        </table>
        <br><br>
        <center><input type="button" value="ESEGUI" id="btnEsegui" title="Esegui"></center>
       <br><br>
    </div>
   
</div>
</body>
</html>