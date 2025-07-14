<?php
	include '../../Classes/DBM.php';
        include '../../Classes/Quadrature.php';

        $dbm = new DBM();

        $model = json_decode($_REQUEST['model']);
        $uniti=true;
        $isotopi = false;
        $arrayAmbi = array();
    

        $tripla = "1-4-7";
        $myYear="";
    $ternoM=false;
    $quaternaM=false;
        $ternoL=false;
    $quaternaL=false;
	for($i=$model->currentYear; $i<=$model->currentYear; $i++){
           for($unitiCount=0;$unitiCount<2;$unitiCount++){
               if($unitiCount == 0){
                   $arrayAmbi = array('1-2','1-5','2-3','3-4','4-5');
                    $isotopi = false;
               }
               else{
                   $arrayAmbi = array('1-3','1-4','2-4','2-5','3-5');
                    $isotopi = true;
               }
            for($j=0; $j<3; $j++){
                $myYear=strval($i);
                if($j==0)
                    $tripla = "1-4-7";
                else if ($j==1)
                    $tripla = "2-5-8";
                else    $tripla = "3-6-9";
                $quadratureModel = new Quadrature($myYear, $arrayAmbi, $tripla, $isotopi);
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
                        $ternoM = false;
                        $quaternaM=false;
                         $ternoL = false;
                        $quaternaL=false;
                      $result = getHistoryMiner($myYear,$sing,$dbm);
                        $m = [0, 0, 0, 0, 0, 0];
                        $l = [0, 0, 0, 0, 0, 0];
                        $o = [$sing["somma_1"], $sing["somma_diag_1"], $sing["somma_2"], $sing["somma_diag_2"], $sing["somma_comune"], $sing["raddoppio_somma_comune"]];
                        $n = [$sing["somma_1"], $sing["somma_diag_1"], $sing["somma_2"], $sing["somma_diag_2"], $sing["somma_comune"], $sing["raddoppio_somma_comune"]];

                    $p=0;
                    for($b=0; $b<sizeof($result[$sing["ruota_1"]]); $b++) {
                        if ($p < 25) {
                          calculateOccurence([$result[$sing["ruota_1"]][$b]["uno"], $result[$sing["ruota_1"]][$b]["due"], $result[$sing["ruota_1"]][$b]["tre"], $result[$sing["ruota_1"]][$b]["quattro"], $result[$sing["ruota_1"]][$b]["cinque"]], $o, $m);
                            calculateOccurence([$result[$sing["ruota_2"]][$b]["uno"], $result[$sing["ruota_2"]][$b]["due"], $result[$sing["ruota_2"]][$b]["tre"], $result[$sing["ruota_2"]][$b]["quattro"], $result[$sing["ruota_2"]][$b]["cinque"]], $n, $l);
                        }
                      
                      if($ternoM != true && $ternoL != true && $quaternaM != true && $quaqternaL != true){
                                               $countVincita=0;

                        if( checkValidated($sing, $result[$sing["ruota_1"]][$b]["uno"], $b))
                             $countVincita++;
                        if(checkValidated($sing, $result[$sing["ruota_1"]][$b]["due"], $b))
                             $countVincita++;    
                        if(checkValidated($sing, $result[$sing["ruota_1"]][$b]["tre"], $b))
                             $countVincita++;
                        if(checkValidated($sing, $result[$sing["ruota_1"]][$b]["quattro"], $b))
                             $countVincita++;
                        if(checkValidated($sing, $result[$sing["ruota_1"]][$b]["cinque"], $b))
                                $countVincita++;
                        if($countVincita == 3)
                            $ternoM=true;
                        if($countVincita == 4)
                            $quaternaM=true;
                         $countVincita=0;
                        if( checkValidated($sing, $result[$sing["ruota_2"]][$b]["uno"], $b))
                             $countVincita++;
                        if(checkValidated($sing, $result[$sing["ruota_2"]][$b]["due"], $b))
                             $countVincita++;    
                        if(checkValidated($sing, $result[$sing["ruota_2"]][$b]["tre"], $b))
                             $countVincita++;
                        if(checkValidated($sing, $result[$sing["ruota_2"]][$b]["quattro"], $b))
                             $countVincita++;
                        if(checkValidated($sing, $result[$sing["ruota_2"]][$b]["cinque"], $b))
                                $countVincita++;
                        if($countVincita == 3)
                            $ternoL=true;
                        if($countVincita == 4)
                            $quaternaL=true;
                      }
                        $p++;
                    }
                      
                        //MANCA RUOTA 2 FORSE BISOGNA CAMBIARE NOMI VALORI
                        
                    sort($m);
                    sort($l);
                    $sestinaStringM =implode("",array_map('strval', $m));
                    $sestinaStringL = implode("",array_map('strval', $l));
                    $q=0;
                    $countSestina = 1;
                    while($q<2){
                        if($q==0){
                           $sestinaTemp= $sestinaStringM;
                           $ternoTemp=$ternoM;
                           $quaternaTemp=$quaternaM;
                        }
                        else {$sestinaTemp = $sestinaStringL;
                         $ternoTemp=$ternoL;
                           $quaternaTemp=$quaternaL;
                        }
                        
                        $result =  $dbm->read("SELECT anno, sestina, dir, pagina, nSestina FROM nSestine where sestina =  '$sestinaTemp' and anno = '$myYear' and dir = '$ordine' and tripla = '$tripla'");
                      
                             
                        if(sizeof($result) <= 0 && $sestinaStringM == $sestinaStringL){
                            $countSestina =2;
                            $q++;
                        }else if(sizeof($result) > 0 && $sestinaStringM == $sestinaStringL)
                            $countSestina= intval($result[0]["nSestina"]) + 2;
                        else if(sizeof($result) <= 0)
                            $countSestina =1;
                        else if(sizeof($result) > 0)
                            $countSestina= intval($result[0]["nSestina"]) + 1;
                        $inTerno ="";
                        $inQuaterna ="";   

                        if($ternoTemp == true)
                            $inTerno = "si";
                         if($quaternaTemp == true)
                            $inQuaterna = "si";
                        
                        if(sizeof($result) > 0)
                            $dbm->write("UPDATE nSestine SET nSestina = '$countSestina' where sestina =  '$sestinaTemp' and anno = '$myYear' and dir = '$ordine' and tripla = '$tripla' and terno ='$inTerno' and quaterna = '$inQuaterna'"); 
                        else
                            $dbm->write("INSERT INTO nSestine (anno, sestina, dir, pagina, nSestina, tripla, terno, quaterna) VALUES ( '$myYear', '$sestinaTemp', '$ordine', '$count', '$countSestina', '$tripla','$inTerno','$inQuaterna')"); 

                                
                       $q++;
                    }
                   
                   $count++;
                }
                
                    $ordineCount++;
                }
            }
           }
}
echo "ok";
function checkValidated($values, $j, $index) {
    
    if ($j == $values["somma_1"]) {
        return true;
    };
    if ($j == $values["somma_2"]) {
        return true;
    };
    if ($j == $values["somma_diag_1"]) {
        return true;
    };
    if ($j == $values["somma_diag_2"]) {
        return true;
    };
    if ($j == $values["somma_comune"]) {
        return true;
    };
    if ($j == values["raddoppio_somma_comune"]) {
        return true;
    };
    return false; 
    
    //TODO: VERIFICARE COME PRENDE I CAMPI (SOMMA ECC) in debug
}
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