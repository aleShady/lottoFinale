<?php
	include '../../Classes/DBM.php';
        include '../../Classes/Quadrature.php';

        $dbm = new DBM();

        $model = json_decode($_REQUEST['model']);

	$uniti = array('1-2','1-5','2-3','3-4','4-5');
        $tripla = "1-4-7";
        $myYear="";
	for($i=$model->currentYear; $i<=$model->currentYear; $i++){
            for($j=0; $j<3; $j++){
                $myYear=strval($i);
                if($j==0)
                    $tripla = "1-4-7";
                else if ($j==1)
                    $tripla = "2-5-8";
                else    $tripla = "3-6-9";
                $quadratureModel = new Quadrature($myYear, $uniti, $tripla, false);
                $quad = $quadratureModel->getQuadrature();
                //aggiungere sinistroso
              
               
                $ordineCount = 0;
                $ordine= "";
                while($ordineCount <2){
                 $count = 1;
                    if($ordineCount==0)
                      $ordine="destroso";
                    else
                        $ordine="sinistroso";
                    foreach ($quad[$ordine] as $sing){
                      $result = getHistoryMiner($myYear,$sing,$dbm);
                        $m = [0, 0, 0, 0, 0, 0];
                        $l = [0, 0, 0, 0, 0, 0];
                        $o = [$sing["somma_1"], $sing["somma_diag_1"], $sing["somma_2"], $sing["somma_diag_2"], $sing["somma_comune"], $sing["raddoppio_somma_comune"]];
                        $n = [$sing["somma_1"], $sing["somma_diag_1"], $sing["somma_2"], $sing["somma_diag_2"], $sing["somma_comune"], $sing["raddoppio_somma_comune"]];

                    $p=0;
                    for($b=0; $b<= sizeof($result[$sing["ruota_1"]]); $b++) {
                        if ($p < 25) {
                          calculateOccurence([$result[$sing["ruota_1"]][$b]["uno"], $result[$sing["ruota_1"]][$b]["due"], $result[$sing["ruota_1"]][$b]["tre"], $result[$sing["ruota_1"]][$b]["quattro"], $result[$sing["ruota_1"]][$b]["cinque"]], $o, $m);
                            calculateOccurence([$result[$sing["ruota_2"]][$b]["uno"], $result[$sing["ruota_2"]][$b]["due"], $result[$sing["ruota_2"]][$b]["tre"], $result[$sing["ruota_2"]][$b]["quattro"], $result[$sing["ruota_2"]][$b]["cinque"]], $n, $l);
                        }
                        $p++;
                    }
                    sort($m);
                    sort($l);
                    $sestinaStringM = implode("",array_map('strval', $m));
                    $sestinaStringL = implode("",array_map('strval', $l));

                   if($sestinaStringM == "011223" || $sestinaStringM == "001123"|| $sestinaStringL == "011223"|| $sestinaStringL == "001123") 
                                              // $dbm->write("DELETE FROM sest$myYear"); 

                       $dbm->write("INSERT ignore INTO sest$myYear (ordine, tripla, pagina, anno) VALUES ( '$ordine', '$tripla', '$count','$myYear')"); 

                   $count++;
                }
                
                    $ordineCount++;
                }
            }
}
echo "ok";

 function calculateOccurence($h, $f, &$g) {
        for ($i = 0; $i < 6; $i++) {
            for ($b=0; $b<count($h); $b++) {
                if (intval($f[$i]) == intVal($h[$b])) {
                    $g[$i]++;
                }
            }
        }
        return $g;
 }
	
       
        
  function getHistoryMiner($anno,$quad,$dbm){
      $Vjrtbrrwdfmt = 24;
	
	$Vzkdzprmnhzz = $anno;
            $el = getMax($quad["estrazione_2"], $quad["estrazione_1"]);
            $Vipwuwayqqjl =  $el - $Vjrtbrrwdfmt;
            $ruote[$quad["ruota_1"]] = array();
            $ruote[$quad["ruota_2"]] = array();
            if($Vipwuwayqqjl < 1)
            {
                    $Vzkdzprmnhzz--;
                    $Vipwuwayqqjl += getMaxEstrazioni($Vzkdzprmnhzz);
            }

            $Vkxbfwlelran = 0;
            $Vyaepvqhxhxc = getMaxEstrazioni($Vzkdzprmnhzz);

            for($V3fceidmdbsb=0, $V0hs02vbbzd4=0; $V3fceidmdbsb<51; $V3fceidmdbsb++, $V0hs02vbbzd4++)
            {	

                    if($Vipwuwayqqjl + $V3fceidmdbsb > $Vyaepvqhxhxc)
                    {
                            $Vipwuwayqqjl = 0;
                            $V0hs02vbbzd4 = 1;
                            $Vzkdzprmnhzz++;
                    }

                    $V3uzfc1u1jkg = "SELECT * FROM year" . $Vzkdzprmnhzz . " WHERE estrazione = " . ($Vipwuwayqqjl + $V0hs02vbbzd4);
                    $Vlbjre5r3aqo = $dbm->read($V3uzfc1u1jkg);
                    foreach($Vlbjre5r3aqo as $Vukau3qnkuvr)
                    {
                            $ruote[$quad["ruota_1"]][$Vkxbfwlelran]["estrazione"] = $Vukau3qnkuvr['estrazione'];
                            $ruote[$quad["ruota_1"]][$Vkxbfwlelran]["data"] = $Vukau3qnkuvr['data'];
                            $ruote[$quad["ruota_1"]][$Vkxbfwlelran]["luogo"] = $quad["ruota_1"];

                            $VkxbfwlelranRuote = json_decode($Vukau3qnkuvr['valori'], true);
                            while ($Va2u1syzilkw = current($VkxbfwlelranRuote))
                            {
                                    if(key($VkxbfwlelranRuote) == $quad["ruota_1"])
                                    {
                                            $Vftwmde4tnzi = explode('.', $Va2u1syzilkw);
                                            $ruote[$quad["ruota_1"]][$Vkxbfwlelran]["uno"] = $Vftwmde4tnzi[0];
                                            $ruote[$quad["ruota_1"]][$Vkxbfwlelran]["due"] = $Vftwmde4tnzi[1];
                                            $ruote[$quad["ruota_1"]][$Vkxbfwlelran]["tre"] = $Vftwmde4tnzi[2];
                                            $ruote[$quad["ruota_1"]][$Vkxbfwlelran]["quattro"] = $Vftwmde4tnzi[3];
                                            $ruote[$quad["ruota_1"]][$Vkxbfwlelran]["cinque"] = $Vftwmde4tnzi[4];
                                    }
                                    next($VkxbfwlelranRuote);
                            }
                    }


                    $V3uzfc1u1jkg = "SELECT * FROM year" . $Vzkdzprmnhzz . " WHERE estrazione = " . ($Vipwuwayqqjl + $V0hs02vbbzd4);
                    $Vlbjre5r3aqo = $dbm->read($V3uzfc1u1jkg);
                    foreach($Vlbjre5r3aqo as $Vukau3qnkuvr)
                    {
                            $ruote[$quad["ruota_2"]][$Vkxbfwlelran]["estrazione"] = $Vukau3qnkuvr['estrazione'];
                            $ruote[$quad["ruota_2"]][$Vkxbfwlelran]["data"] = $Vukau3qnkuvr['data'];
                            $ruote[$quad["ruota_2"]][$Vkxbfwlelran]["luogo"] = $quad["ruota_2"];

                            $VkxbfwlelranRuote = json_decode($Vukau3qnkuvr['valori'], true);
                            while ($Va2u1syzilkw = current($VkxbfwlelranRuote))
                            {
                                    if(key($VkxbfwlelranRuote) == $quad["ruota_2"])
                                    {
                                            $Vftwmde4tnzi = explode('.', $Va2u1syzilkw);
                                            $ruote[$quad["ruota_2"]][$Vkxbfwlelran]["uno"] = $Vftwmde4tnzi[0];
                                            $ruote[$quad["ruota_2"]][$Vkxbfwlelran]["due"] = $Vftwmde4tnzi[1];
                                            $ruote[$quad["ruota_2"]][$Vkxbfwlelran]["tre"] = $Vftwmde4tnzi[2];
                                            $ruote[$quad["ruota_2"]][$Vkxbfwlelran]["quattro"] = $Vftwmde4tnzi[3];
                                            $ruote[$quad["ruota_2"]][$Vkxbfwlelran++]["cinque"] = $Vftwmde4tnzi[4];
                                    }
                                    next($VkxbfwlelranRuote);
                            }
                    }
            }
        
	
	return $ruote;
  }
        
        
 function getMax($q, $r) {
    if (intval($q) > intval($r)) {
        return $q;
    } else {
        return $r;
    }
}
        
        
function getMaxEstrazioni($Vzkdzprmnhzz)
	{
		$Vtppv1qqczva = new DBM();
		$V3uzfc1u1jkg = "SELECT MAX(estrazione) as 'myMax' FROM year" . $Vzkdzprmnhzz;
		$Vlbjre5r3aqo = $Vtppv1qqczva->read($V3uzfc1u1jkg);
		return $Vlbjre5r3aqo[0]['myMax'];
	}        
        
 
//	$model = json_decode($_REQUEST['model']);
//	
//       	$dbm = new DBM();
       
//                $dbm->write("delete from   $model->year
//where trip = '$model->tripla' and ord = '$model->ordine' and pagina ='$model->pag' and anno ='$model->year'
// ");
//                try{
//                         $dbm->write("INSERT IGNORE INTO sest$model->year (Anno, Ordine, Tripla, Pagina) VALUES ( '$model->year', '$model->ordine', '$model->tripla','$model->pag')"); 
//
//                } catch (Exception $ex) {
//                        echo $ex;
//                }

//	echo json_encode("caricato");
?>