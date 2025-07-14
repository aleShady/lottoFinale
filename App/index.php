<!DOCTYPE html>
<html>
<head>
    <title>LOTTO </title>
    <style type="text/css">
		@font-face
		{
			font-family: Lato;
			src: url(css/Lato/Lato-Regular.ttf);
		}
		@font-face
		{
			font-family: Lato;
			font-weight: bold;
			src: url(css/Lato/Lato-Bold.ttf);
		}
		body
		{
			font-family: Lato;
			background: #fff;
			margin: 0px;
		}
		.header
		{
			
			width: 100%;
			background: #ffffff;
			padding: 15px 0 15px 0;
			border-bottom: 2px solid #FF5C01;
			margin-bottom: 25px;
		}
		.item
		{
			display: inline-block;
			background: #ffffff;
			//border:1px solid #cccccc;
			width: 200px;
			height: 150px;
			margin: 0 20px 0 20px;
			border-radius: 5px;
			box-shadow: 1px 1px 3px 1px rgba(0,0,0,.2);
			vertical-align: top;
			//opacity: .7;
			cursor: pointer;
		  	transition: opacity .4s ease-in-out;
			
		}
		.item:hover
		{
			opacity: 1;
			transition: opacity .4s ease-in-out;
		}
		.title
		{
			font-weight: bold;
			text-transform: uppercase;
			padding: 7px 0 7px 0;
			width: 90%;
			color: #555;
			border-bottom: 1px solid rgba(255,92,1,.3);
		}
		.content
		{
			width: 90%;
			text-align: center;
			padding-top: 20px;
			color: #777;
		}
	</style>
</head>
<body>

<?php

	//header("content-type: text");

	 include '../Classes/DBM.php';

	 $myYear = date("Y");
     include_once('simple_html_dom.php');
      	 $dbm = new DBM();

	

	 $dbm->write("DELETE FROM year$myYear");

	 $dbm->write("DELETE FROM quad$myYear");



	$values = getFileMrk($myYear);
   
        $ruote = $values->find('th');
        $arrayruote = array();
        for ($i=1;$i<sizeof($ruote);$i++){
            $arrayruote[]=$ruote[$i]->innertext;
        }
            
        $estrazioni_Arr = $values->find('tr'); 
        
	$estrazioni = count($estrazioni_Arr);
        
        $f = 0;
        
	foreach($estrazioni_Arr as $key)

	{
                if($f!=0){
                    $tmpEst = --$estrazioni;
		if($key == '') continue;
                
                $tmp = $key->find('td[class=text-nowrap bleft bright]'); 

               
               
               
            
                
                //                foreach($html->find('span')) {
//                      if (0 === strpos($id, 'ptnum_')){
//                          
//                      }
//
//                }
//                $tmp2 = $tmp->find('span.ptnum_')
                
                $tmpData = getData($key->first_child()->children(0)->innertext) . $myYear;

		//unset($tmp[0]);

		$aux = [];

		$indexRuota = 0;
                    
                //$ind = key($est);
                        
                
                //$ruota = $ruote[$ind-1]->innertext;
		
                foreach($tmp as $keyt)

		{
                        
                    $estraz = $keyt->plaintext;
                    $cells = preg_replace('/\s+/', '.', trim($estraz));
                           if (!(((string) $cells == "00.00.00.00.00 ") || ((string) $cells == "00.00.00.00.00") || (strlen($cells) == 1))) {
                             $aux[$arrayruote[$indexRuota]] = str_replace(' ', '', $cells);
                            //$aux[$indexRuota] = str_replace(' ', '', $cells);
                            }
                	$indexRuota++;

		}

		$sql = "INSERT INTO year" . $myYear . " VALUES(" . $tmpEst . ", '". $tmpData ."', '". json_encode($aux) ."')";	
                if($sql != "")
                $dbm->write($sql);
                
                }
                $f++;

	}

	function getData($str)

	{

		$space = strpos($str, ' ');

		$day = substr($str, 0, $space);

		$tmp = substr($str, $space+1);

		$mon = substr($str, $space+1);

		if(strlen($day) == 1)

			$day = '0' . $day;

		return $day . ' ' . $mon . ' ';

	}

        function getFileMrk($year) {
            $link = 'https://www.lottologia.com/lotto/?do=past-draws-archive&table_view_type=default&year=' . $year . '&numbers=';
           // $link = 'http://www.lottologia.com/lotto/?do=archivio-estrazioni&tab=&year=' . $year . '&empty=&group_num_selector=selected&numbers_selector_mode=add&numbers_selected=#main';
//             $link = 'http://www.lottologia.com/lotto/?do=archivio-estrazioni&tab=&year=2017&empty=&group_num_selector=selected&numbers_selector_mode=add&numbers_selected=#main';

			//$link='lotto.html';
            
            $context = stream_context_create(array('http' => array('header' => 'User-Agent: Mozilla compatible')));
            $response = file_get_contents($link, false, $context);
            $html = str_get_html($response);
           // $html = file_get_html(link);
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_URL, $link);
            curl_setopt($curl, CURLOPT_REFERER, $link);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            $str = curl_exec($curl);
            curl_close($curl);

            $html_base = new simple_html_dom();
            
      
            // Load HTML from a string
            $html_base->load($str);
             //$prov= $html_base->find('a');
              //           echo($prov);
         
			//echo($html->find(class~="table") == null?"null":"aaa");

            // Find all images
            //foreach($html->find('img') as $element)
              //  echo $element->src . '<br>';
            $ret = $html_base->getElementsByTagName('table');
		return $ret;

        }
	/*function getFile($year)

	{

		$link = 'http://www.lottologia.com/lotto/?do=archivio-estrazioni&tab=&as=TXT&year=' . $year . '&group_num_selector=selected&numbers_selector_mode=add&numbers_selected=#main';

		//echo $link;

		$file = file_get_contents($link);

		$tmp = explode("\n", $file);

		array_splice($tmp, 0, 1);

		array_splice($tmp, 0, 1);

		

		

		

		//var_dump($tmp);          die();

		return $tmp;

	}*/

	

	$output = $dbm->read("SELECT distinct * FROM year$myYear");
        
        //$result = mysql_query("SELECT * FROM year$myYear",$connection);
        //$output = mysql_fetch_array($result, MYSQL_ASSOC);
        //$output=mysqli_fetch_all ($result, MYSQL_ASSOC);

	foreach($output as $row)
        //while($row=mysql_fetch_array($result, MYSQL_ASSOC))
	{

		$est = json_decode($row['valori'], true);

		$data = $row['data'];

		while($valori = current($est))

		{
                  
                    $estra = "";
        
			//$ruota = key($est);
                    
			
			
                        
            $ruota = key($est);
                        
                        //$ruote = $values[0]->find('th[class=blue_important text-right]');

			$estrat = explode('.', $valori);

			for($aux=0; $aux<5; $aux++)

				for($aux1=$aux+1; $aux1<5; $aux1++)

					if(getDistance($estrat[$aux], $estrat[$aux1]) == 'x')

					{

						$estrazione = $row['estrazione'];

						$distanza = ($aux+1) . '-' . ($aux1+1);

						$tripla = getTripla($estrat[$aux]);

						$val1 = $estrat[$aux];

						$val2 = $estrat[$aux1];

						$sql = "INSERT INTO quad$myYear VALUES('$data','$ruota', $estrazione, '$distanza', '$tripla', $val1, $val2)";	

						$dbm->write($sql);

					}

			next($est);
$i++;
                }	
                
                                        }



	function getDistance($x, $y) 

	{

		if($x > $y)

			$tmp = $x - $y;

		else

			$tmp = $y - $x;

		if($tmp > 45)

			$aux = 90 - intval($tmp);

		else

			$aux = $tmp;

		if($aux == 3)

			return "x";

		else

			return $aux;

	}

	

	function getTripla($val)

	{

		if(isset($val[1]))

			$value = $val[1] + $val[0];

		else

			$value = $val[0];

		if($value > 9)

			$value -= 9;

		if($value == 1 || $value == 4 || $value == 7)	return '1-4-7';

		if($value == 2 || $value == 5 || $value == 8)	return '2-5-8';

		if($value == 3 || $value == 6 || $value == 9)	return '3-6-9';

	}

?>
	<center>

    	<div class="header">
        	<img src="img/logo.png" width="30%"/>
        </div>
    	 
        <div class="item" url="estrazioni">
        	<div class="title">estrazioni</div>
            <div class="content">
            	Elenco di tutte le estrazioni dal 1871 ad oggi.
            </div>
        </div>
        <div class="item" url="quadrature">
        	<div class="title">quadrature</div>
            <div class="content">
            	Domenico
            </div>
        </div>
        <div class="item" url="modulo_uno">
        	<div class="title">modulo 1</div>
            <div class="content">
            	Calcolo di uscite in base a configurazione
            </div>
        </div>
        
         <div class="item" url="modulo_due">
        	<div class="title">modulo 2</div>
            <div class="content">
            	Calcolo delle sestine
            </div>
        </div>
		<div class="item" url="modulo_tre">
        	<div class="title">modulo 3</div>
            <div class="content">
            	Tabelloni ambi, terne e quaterne
            </div>
        </div>
		<div class="item" url="TotaliSestine">
        	<div class="title">Sestine</div>
            <div class="content">
            	elenco sestine
            </div>
        </div>
        <div class="item" style="margin-top: 30px;" url="elencoSestine">
        	<div class="title">Trova pagine sestine</div>
            <div class="content">
            	Trova pagine sestine
            </div>
        </div>
          <div class="item" style="margin-top: 30px;" url="nSestine">
        	<div class="title">Trova terni e quaterne</div>
            <div class="content">
            	Trova pagine sestine
            </div>
        </div>
    </center>
    
    <!------------------------------------------------------------------->
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript">
		$(document).ready(function() {
        	$('.item').click(function(){
				window.location.href = $(this).attr('url');
			});
        });
	</script>
</body>
</html>