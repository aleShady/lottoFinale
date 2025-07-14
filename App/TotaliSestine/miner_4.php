<?php
	header('Content-Type: application/json');
	ini_set('memory_limit', '-1');
	include '../../Classes/DBM.php';
	include '../../Classes/Quadrature.php';
	$Vfs2niaige2t = json_decode($_REQUEST['model']);
//	             <input type="button" value="Ricerca" id="btnRicerca" title="Ricerca">

        
	$Vtppv1qqczva = new DBM();
        $isotopi = false;
        $count = 0;
                $Vtppv1qqczva->write("delete from   sest$Vfs2niaige2t->year
where trip = '$Vfs2niaige2t->tripla' and ord = '$Vfs2niaige2t->ordine'
 ");

        
        
            if($isotopi == false)
		$Vv02uoebc004 = array('1-2','1-5','2-3','3-4','4-5');
            else
		$Vv02uoebc004 = array('1-3','1-4','2-4','2-5','3-5');
        $Vzkdzprmnhzz = $Vfs2niaige2t->year;
        $Vwvjd4q4sfbo = new Quadrature($Vfs2niaige2t->year, $Vv02uoebc004, $Vfs2niaige2t->tripla, $isotopi);
	$V1riot0zwstj = $Vwvjd4q4sfbo->getQuadrature();
        $tipo="destroso";
        $Vjrtbrrwdfmt = 24;
       
    while($count <= 1){
        foreach($V1riot0zwstj[$tipo] as $quad){
            

            $Vipwuwayqqjl = intval($quad["estrazione_1"]) > intval($quad["estrazione_2"]) ?  intval($quad["estrazione_1"])  - $Vjrtbrrwdfmt : intval($quad["estrazione_2"]) - $Vjrtbrrwdfmt;
            $Voungjt1lyll[$quad["ruota_1"]] = array();
            $Voungjt1lyll[$quad["ruota_1"]] = array();
            if($Vipwuwayqqjl < 1)
            {
                    $Vzkdzprmnhzz--;
                    $Vipwuwayqqjl += getMaxEstrazioni($Vfs2niaige2t->year);
            }

            $Vkxbfwlelran = 0;
            $Vyaepvqhxhxc = getMaxEstrazioni($Vfs2niaige2t->year);
                        $Vyaepvqhxhxc = getMaxEstrazioni($Vfs2niaige2t->year);

            for($V3fceidmdbsb=0, $V0hs02vbbzd4=0; $V3fceidmdbsb<51; $V3fceidmdbsb++, $V0hs02vbbzd4++)
            {	
		
//		if($Vipwuwayqqjl + $V3fceidmdbsb > $Vyaepvqhxhxc)
//		{
//			$Vipwuwayqqjl = 0;
//			$V0hs02vbbzd4 = 1;
//			$Vzkdzprmnhzz++;
//		}
			
		$V3uzfc1u1jkg = "SELECT * FROM year" . $Vzkdzprmnhzz . " WHERE estrazione = " . ($Vipwuwayqqjl + $V0hs02vbbzd4);
		$Vlbjre5r3aqo = $Vtppv1qqczva->read($V3uzfc1u1jkg);
		foreach($Vlbjre5r3aqo as $Vukau3qnkuvr)
		{
			$Voungjt1lyll[$quad["ruota_1"]][$Vkxbfwlelran]["estrazione"] = $Vukau3qnkuvr['estrazione'];
			$Voungjt1lyll[$quad["ruota_1"]][$Vkxbfwlelran]["data"] = $Vukau3qnkuvr['data'];
			$Voungjt1lyll[$quad["ruota_1"]][$Vkxbfwlelran]["luogo"] = $quad["ruota_1"];
			
			$VkxbfwlelranRuote = json_decode($Vukau3qnkuvr['valori'], true);
			while ($Va2u1syzilkw = current($VkxbfwlelranRuote))
			{
				if(key($VkxbfwlelranRuote) == $quad["ruota_1"])
				{
					$Vftwmde4tnzi = explode('.', $Va2u1syzilkw);
					$Voungjt1lyll[$quad["ruota_1"]][$Vkxbfwlelran]["uno"] = $Vftwmde4tnzi[0];
					$Voungjt1lyll[$quad["ruota_1"]][$Vkxbfwlelran]["due"] = $Vftwmde4tnzi[1];
					$Voungjt1lyll[$quad["ruota_1"]][$Vkxbfwlelran]["tre"] = $Vftwmde4tnzi[2];
					$Voungjt1lyll[$quad["ruota_1"]][$Vkxbfwlelran]["quattro"] = $Vftwmde4tnzi[3];
					$Voungjt1lyll[$quad["ruota_1"]][$Vkxbfwlelran]["cinque"] = $Vftwmde4tnzi[4];
				}
				next($VkxbfwlelranRuote);
			}
		}
	
		
		$V3uzfc1u1jkg = "SELECT * FROM year" . $Vzkdzprmnhzz . " WHERE estrazione = " . ($Vipwuwayqqjl + $V0hs02vbbzd4);
		$Vlbjre5r3aqo = $Vtppv1qqczva->read($V3uzfc1u1jkg);
		foreach($Vlbjre5r3aqo as $Vukau3qnkuvr)
		{
			$Voungjt1lyll[$quad["ruota_2"]][$Vkxbfwlelran]["estrazione"] = $Vukau3qnkuvr['estrazione'];
			$Voungjt1lyll[$quad["ruota_2"]][$Vkxbfwlelran]["data"] = $Vukau3qnkuvr['data'];
			$Voungjt1lyll[$quad["ruota_2"]][$Vkxbfwlelran]["luogo"] = $quad["ruota_2"];
			
			$VkxbfwlelranRuote = json_decode($Vukau3qnkuvr['valori'], true);
			while ($Va2u1syzilkw = current($VkxbfwlelranRuote))
			{
				if(key($VkxbfwlelranRuote) == $quad["ruota_2"])
				{
					$Vftwmde4tnzi = explode('.', $Va2u1syzilkw);
					$Voungjt1lyll[$quad["ruota_2"]][$Vkxbfwlelran]["uno"] = $Vftwmde4tnzi[0];
					$Voungjt1lyll[$quad["ruota_2"]][$Vkxbfwlelran]["due"] = $Vftwmde4tnzi[1];
					$Voungjt1lyll[$quad["ruota_2"]][$Vkxbfwlelran]["tre"] = $Vftwmde4tnzi[2];
					$Voungjt1lyll[$quad["ruota_2"]][$Vkxbfwlelran]["quattro"] = $Vftwmde4tnzi[3];
					$Voungjt1lyll[$quad["ruota_2"]][$Vkxbfwlelran++]["cinque"] = $Vftwmde4tnzi[4];
				}
				next($VkxbfwlelranRuote);
			}
		}
            }
                 
            $Voungjt1lyll;
            
           
                getSestine($quad, $Voungjt1lyll[$quad["ruota_1"]], $Vtppv1qqczva, $Vfs2niaige2t->year, $tipo, $Vfs2niaige2t->tripla, $isotopi);
                getSestine($quad, $Voungjt1lyll[$quad["ruota_2"]], $Vtppv1qqczva, $Vfs2niaige2t->year, $tipo, $Vfs2niaige2t->tripla, $isotopi);

        }
        $count++;
        $tipo == "destroso" ? "sinistroso" : "destroso0";
        
    }    
        
         	function getMaxEstrazioni($Vzkdzprmnhzz)
                {
                    $Vtppv1qqczva = new DBM();
                    $V3uzfc1u1jkg = "SELECT MAX(estrazione) as 'myMax' FROM year" . $Vzkdzprmnhzz;
                    $Vlbjre5r3aqo = $Vtppv1qqczva->read($V3uzfc1u1jkg);
                    return $Vlbjre5r3aqo[0]['myMax'];
                }       
          
        
        function getSestine($quad, $estrazioni, $db, $myYear, $ord, $trip, $isotopi){
            $sestinaObj= array(      
                array("somma1", $quad["somma_1"]),
                array("somma2", $quad["somma_2"]),
                array("sommaComune",$quad["somma_comune"]),
                array("sommaDiagonale1",$quad["somma_diag_1"]),
                array("sommaDiagonale2",$quad["somma_diag_2"]),
                array("raddoppioSommaComune",$quad["raddoppio_somma_comune"]),
            );
            
           $sestina = array(0,0,0,0,0,0);
           
           
        
                    
            for($i=0; $i<25; $i++){
                foreach($sestinaObj as $index => $el){
                    if(intval($el[1]) == intval($estrazioni[$i]["uno"]) || 
                       intval($el[1]) == intval($estrazioni[$i]["due"]) ||
                       intval($el[1]) == intval($estrazioni[$i]["tre"]) ||
                       intval($el[1]) == intval($estrazioni[$i]["quattro"]) ||
                       intval($el[1]) == intval($estrazioni[$i]["cinque"]))
                    
                    $sestina[$index]++;
                }
            }
            for($j=26; $j<=51; $j++){
              $EsitiPositivi = 0;
              $EsitiNegativi = 0;
              $countVincita = 0;
              $numAmbi = 0;
              $numTerni = 0;
              $numQuaterne = 0;    
                foreach($sestinaObj as $el){
                    if(intval($el) == intval($estrazioni[$j]["uno"]) || 
                        intval($el) == intval($estrazioni[$j]["due"]) ||
                        intval($el) == intval($estrazioni[$j]["tre"]) ||
                        intval($el) == intval($estrazioni[$j]["quattro"]) ||
                        intval($el) == intval($estrazioni[$j]["cinque"]))

                         $countVincita++;
                }
                   if($countVincita == 2)
                       $numAmbi++;
                   if($countVincita == 3)
                       $numTerni++;
                   if($countVincita == 4)
                       $numQuaterne++;
                   if($countVincita < 2)
                       $EsitiNegativi++;
                   if($countVincita >= 2 && $countVincita < 5)
                       $EsitiPositivi++;
            
            $sestinaString = implode(" ",array_map('strval', $sestina));
            $iso = $isotopi == false ? "Uniti" : "Non Uniti";
            
            $result =  $db->read("SELECT sestina, Ambi, nTerni, nQuaterne, EsitiPositivi, EsitiNegativi FROM sest$myYear where sestina =  '$sestinaString' AND trip = '$trip' AND ord = '$ord' and isotopi = '$iso' ");
                     if(sizeof($result) <= 0)
                            $db->write("INSERT INTO sest$myYear (EsitiPositivi, EsitiNegativi, Ambi, nTerni, sestina, nQuaterne, trip, ord, isotopi) VALUES ('$EsitiPositivi', '$EsitiNegativi','$numAmbi','$numTerni', '$sestinaString', '$numQuaterne', '$trip', '$ord', '$iso')"); 
                     else{
                           $numAmbi += intval($result[0]["Ambi"]);
                           $numTerni += intval($result[0]["nTerni"]);
                           $numQuaterne += intval($result[0]["nQuaterne"]);
                           $EsitiPositivi += intval($result[0]["EsitiPositivi"]);
                           $EsitiNegativi += intval($result[0]["EsitiNegativi"]);
                            $db->write("UPDATE sest$myYear SET  EsitiPositivi = '$EsitiPositivi', EsitiNegativi = '$EsitiNegativi', ambi = '$numAmbi', nTerni = '$numTerni', nQuaterne = '$numQuaterne' where sestina = '$sestinaString' AND trip = '$trip' AND ord = '$ord' and isotopi = '$iso'"); 

                     }       
        }
        
    }
        
//        
////                
////        while($count <= 1){
////            if($isotopi == false)
////		$Vv02uoebc004 = array('1-2','1-5','2-3','3-4','4-5');
////            else
////		$Vv02uoebc004 = array('1-3','1-4','2-4','2-5','3-5');
////
////	$Vwvjd4q4sfbo = new Quadrature($Vfs2niaige2t->year, $Vv02uoebc004, $Vfs2niaige2t->tripla, $isotopi);
////	$Vyfxn1pwvxar = $Vfs2niaige2t->ordine == 'destroso' ? $Vwvjd4q4sfbo->getQuadratureDestroso() : $Vwvjd4q4sfbo->getQuadratureSinistroso();
////	$Vfs2niaige2tYearEstrazioni = getMaxEstrazioneYear($Vfs2niaige2t->year, $Vtppv1qqczva);
////	$Vskj4qd0hwyl	= getMaxEstrazioneYear($Vfs2niaige2t->year-1, $Vtppv1qqczva);	
////	$Vfs2niaige2t->composizione = explode('-', $Vfs2niaige2t->composizione);
////	foreach($Vfs2niaige2t->composizione as $V45qtgu112qa => $Vm1my3thfa0y)	$Vfs2niaige2t->composizione[$V45qtgu112qa] = intval($Vm1my3thfa0y);
////	
////	$Voungjt1lyll = array();
////	$Vuz3sbxdl25x = 0;
////	foreach($Vyfxn1pwvxar as $V05a0cmbwbwq)
////	{
////		$Vwotg4wet5ef = array($V05a0cmbwbwq['somma_1'], $V05a0cmbwbwq['somma_2'], $V05a0cmbwbwq['somma_diag_1'], $V05a0cmbwbwq['somma_diag_2'], $V05a0cmbwbwq['somma_comune'], $V05a0cmbwbwq['raddoppio_somma_comune']);
////		$Vgq5ewneu0y5 = $V05a0cmbwbwq['estrazione_1'] > $V05a0cmbwbwq['estrazione_2'] ? $V05a0cmbwbwq['estrazione_1'] : $V05a0cmbwbwq['estrazione_2'];
////		
////		$Vyfxn1pwvxar = getValoriEstratti($V05a0cmbwbwq['ruota_1'], $V05a0cmbwbwq['ruota_2'], $Vfs2niaige2t->year, $Vgq5ewneu0y5, $Vfs2niaige2tYearEstrazioni, $Vskj4qd0hwyl, $Vtppv1qqczva);
////		
////		$Vlbjre5r3aqo = isMyComposition($Vyfxn1pwvxar[0], $Vfs2niaige2t->composizione, $Vwotg4wet5ef);
////		if($Vlbjre5r3aqo != false)
////		{
////			$Vovyrnelfi0z = array_merge(array(), $Vwotg4wet5ef);
////			orderArrays($Vlbjre5r3aqo,$Vovyrnelfi0z);
////			$Vkxbfwlelran = getResSestine($Vyfxn1pwvxar[0], $Vovyrnelfi0z, $Vwotg4wet5ef, $Vtppv1qqczva, $Vfs2niaige2t->year,$Vlbjre5r3aqo, $Vfs2niaige2t->tripla, $Vfs2niaige2t->ordine, $isotopi);
////			if($Vkxbfwlelran != false)
////			{
////				$Voungjt1lyll[] = array(
////					'History' => $Vyfxn1pwvxar[0],
////					'Quadratura' => $V05a0cmbwbwq,
////					'Tipologia' => 1,
////					'ValoriSestina' => $Vovyrnelfi0z,
////					'resSestine' => $Vkxbfwlelran,
////					'Comp' => $Vlbjre5r3aqo
////				);
////			}
////			else
////			{
////				$Vuz3sbxdl25x++;
////			}	
////		}
////		
////		$Vlbjre5r3aqo = isMyComposition($Vyfxn1pwvxar[1], $Vfs2niaige2t->composizione, $Vwotg4wet5ef);
////		if($Vlbjre5r3aqo != false)
////		{
////			$Vovyrnelfi0z = array_merge(array(), $Vwotg4wet5ef);
////			orderArrays($Vlbjre5r3aqo,$Vovyrnelfi0z);
////			$Vkxbfwlelran = getResSestine($Vyfxn1pwvxar[1], $Vovyrnelfi0z, $Vwotg4wet5ef, $Vtppv1qqczva, $Vfs2niaige2t->year,$Vlbjre5r3aqo, $Vfs2niaige2t->tripla, $Vfs2niaige2t->ordine, $isotopi );
////			if($Vkxbfwlelran != false)
////			{
////				$Voungjt1lyll[] = array(
////					'History' => $Vyfxn1pwvxar[1],
////					'Quadratura' => $V05a0cmbwbwq,
////					'Tipologia' => 2,
////					'ValoriSestina' => $Vovyrnelfi0z,
////					'resSestine' => $Vkxbfwlelran,
////					'Comp' => $Vlbjre5r3aqo
////				);
////			}
////			else
////			{
////				$Vuz3sbxdl25x++;
////			}	
////		}	
////	}
////	$Voungjt1lyll['fails'] = $Vuz3sbxdl25x;
////	$Voungjt1lyll['positivi'] = count($Voungjt1lyll);  
////        $count++;
////	$isotopi = true;
////        }
//        echo json_encode($Voungjt1lyll);
// 
//
//	function getValoriEstratti($Vjhqqhgfyiag, $Vkcfgrcfdwa5, $Vzkdzprmnhzz, $Vdbkqgspzztj, $Vbexasm3f1c0, $Vc12haoiuusd, $Vmjyb4nwthls)
//	{
//		$Voungjt1lyll = array();
//		$Vyxqyeyyweu4 = $Vdbkqgspzztj + 25;
//		$Vhr02kzwb2rk = $Vdbkqgspzztj - 24;
//		
//		if(($Vyxqyeyyweu4 <= $Vbexasm3f1c0) && ($Vhr02kzwb2rk >= 1))
//			$V3uzfc1u1jkg = "SELECT * FROM year$Vzkdzprmnhzz WHERE estrazione >= $Vhr02kzwb2rk AND estrazione <= $Vyxqyeyyweu4 ORDER BY estrazione";
//		else if(($Vyxqyeyyweu4 > $Vbexasm3f1c0))
//			$V3uzfc1u1jkg = "SELECT *, 'id1' OrderKey FROM year$Vzkdzprmnhzz WHERE estrazione >= $Vhr02kzwb2rk UNION SELECT *, 'id2' OrderKey FROM year" . ($Vzkdzprmnhzz+1) . " WHERE estrazione <= " . ($Vyxqyeyyweu4 - $Vbexasm3f1c0) . " ORDER BY OrderKey, estrazione";
//		else if(($Vhr02kzwb2rk < 1))
//			$V3uzfc1u1jkg = "SELECT *, 'id1' OrderKey FROM year". ($Vzkdzprmnhzz-1) ." WHERE estrazione >= ". ($Vc12haoiuusd - abs($Vhr02kzwb2rk)) ." UNION SELECT *, 'id2' OrderKey FROM year$Vzkdzprmnhzz WHERE estrazione <= $Vyxqyeyyweu4 ORDER BY OrderKey, estrazione";
//		else
//			die("Errore getValoriEstratti");
//		
//		$Vlbjre5r3aqo = $Vmjyb4nwthls->read($V3uzfc1u1jkg);
//		$V3fceidmdbsb = $V0hs02vbbzd4 = 0;
//		$Voungjt1lyll[0] = $Voungjt1lyll[1] = array();
//		
//		foreach($Vlbjre5r3aqo as $V05a0cmbwbwq)
//		{
//			$VkxbfwlelranRuote = json_decode($V05a0cmbwbwq['valori'], true);
//			while ($Vm1my3thfa0yori = current($VkxbfwlelranRuote))
//			{
//				if(key($VkxbfwlelranRuote) == $Vjhqqhgfyiag)
//				{
//					$Vftwmde4tnzi = explode('.', $Vm1my3thfa0yori);
//					$Voungjt1lyll[0][$V3fceidmdbsb]["estrazione"] = $V05a0cmbwbwq['estrazione'];
//					$Voungjt1lyll[0][$V3fceidmdbsb]["data"] = $V05a0cmbwbwq['data'];
//					$Voungjt1lyll[0][$V3fceidmdbsb]["luogo"] = $Vjhqqhgfyiag;
//					$Voungjt1lyll[0][$V3fceidmdbsb]["uno"] = $Vftwmde4tnzi[0];
//					$Voungjt1lyll[0][$V3fceidmdbsb]["due"] = $Vftwmde4tnzi[1];
//					$Voungjt1lyll[0][$V3fceidmdbsb]["tre"] = $Vftwmde4tnzi[2];
//					$Voungjt1lyll[0][$V3fceidmdbsb]["quattro"] = $Vftwmde4tnzi[3];
//					$Voungjt1lyll[0][$V3fceidmdbsb]["cinque"] = $Vftwmde4tnzi[4];
//					$V3fceidmdbsb++;
//				}
//				else if(key($VkxbfwlelranRuote) == $Vkcfgrcfdwa5)
//				{
//					$Vftwmde4tnzi = explode('.', $Vm1my3thfa0yori);
//					$Voungjt1lyll[1][$V0hs02vbbzd4]["estrazione"] = $V05a0cmbwbwq['estrazione'];
//					$Voungjt1lyll[1][$V0hs02vbbzd4]["data"] = $V05a0cmbwbwq['data'];
//					$Voungjt1lyll[1][$V0hs02vbbzd4]["luogo"] = $Vkcfgrcfdwa5;
//					$Voungjt1lyll[1][$V0hs02vbbzd4]["uno"] = $Vftwmde4tnzi[0];
//					$Voungjt1lyll[1][$V0hs02vbbzd4]["due"] = $Vftwmde4tnzi[1];
//					$Voungjt1lyll[1][$V0hs02vbbzd4]["tre"] = $Vftwmde4tnzi[2];
//					$Voungjt1lyll[1][$V0hs02vbbzd4]["quattro"] = $Vftwmde4tnzi[3];
//					$Voungjt1lyll[1][$V0hs02vbbzd4]["cinque"] = $Vftwmde4tnzi[4];
//					$V0hs02vbbzd4++;
//				}
//				next($VkxbfwlelranRuote);
//			}
//		}
//		return $Voungjt1lyll;
//	}
//	
//	function getMaxEstrazioneYear($Vzkdzprmnhzz, $Vtppv1qqczva)
//	{
//		$Vlbjre5r3aqo = $Vtppv1qqczva->read("SELECT MAX(estrazione) as 'myMax' FROM year" . $Vzkdzprmnhzz);
//		return isset($Vlbjre5r3aqo[0]) ? $Vlbjre5r3aqo[0]['myMax'] : 0;
//	}
//	
//	function isMyComposition($Vyfxn1pwvxar, $Vr5tej5r25u1, $Vwvjd4q4sfboValues)
//	{
//		if(count($Vyfxn1pwvxar) <= 25) return false;	
//		$Vagdjnln1j1p = array(0,0,0,0,0,0);
//		
//		for($V3fceidmdbsb=0; $V3fceidmdbsb<25; $V3fceidmdbsb++)
//		addOccurenceComp($Vwvjd4q4sfboValues, $Vagdjnln1j1p, array($Vyfxn1pwvxar[$V3fceidmdbsb]['uno'],$Vyfxn1pwvxar[$V3fceidmdbsb]['due'],$Vyfxn1pwvxar[$V3fceidmdbsb]['tre'],$Vyfxn1pwvxar[$V3fceidmdbsb]['quattro'],$Vyfxn1pwvxar[$V3fceidmdbsb]['cinque']));
//			
//		
//		
//		return $Vagdjnln1j1p;
//	}
//	
//	function addOccurenceComp($Vwvjd4q4sfboValues, &$Vagdjnln1j1p, $Vmqyrepuywv4)
//	{
//		for($V3fceidmdbsb=0; $V3fceidmdbsb<6; $V3fceidmdbsb++)
//			foreach($Vmqyrepuywv4 as $V45qtgu112qa)
//				if($Vwvjd4q4sfboValues[$V3fceidmdbsb] == $V45qtgu112qa)
//					$Vagdjnln1j1p[$V3fceidmdbsb]++;                            
//	}
//	
//	function orderArrays(&$Vgpbrdjsz50h, &$Vuxwwozuofie)
//	{
//		$Vukwiq0shgsy = false;
//		while(!$Vukwiq0shgsy)
//		{
//			$Vukwiq0shgsy = true;
//			for($V0hs02vbbzd4=0; $V0hs02vbbzd4<5; $V0hs02vbbzd4++)
//			{
//				if($Vgpbrdjsz50h[$V0hs02vbbzd4] > $Vgpbrdjsz50h[$V0hs02vbbzd4+1])
//				{
//					$Vukwiq0shgsy = false;
//					$Vkxbfwlelran = $Vgpbrdjsz50h[$V0hs02vbbzd4];
//					$Vgpbrdjsz50h[$V0hs02vbbzd4] = $Vgpbrdjsz50h[$V0hs02vbbzd4+1];
//					$Vgpbrdjsz50h[$V0hs02vbbzd4+1] = $Vkxbfwlelran;
//					
//					$Vkxbfwlelran = $Vuxwwozuofie[$V0hs02vbbzd4];
//					$Vuxwwozuofie[$V0hs02vbbzd4] = $Vuxwwozuofie[$V0hs02vbbzd4+1];
//					$Vuxwwozuofie[$V0hs02vbbzd4+1] = $Vkxbfwlelran;
//				}
//			}
//		}
//	}
//    function sestinaValidator($estraz, $quad, $array){
//  
//            foreach ($estraz as $key => $var) {
//                 $estraz[$key] = (int)$var;
//            }
//            for($i=0; $i<6; $i++)
//            { 
//              for($j=0; $j<sizeof($estraz); $j++){  
//                if($quad[$i] == $estraz[$j])
//                 {
//                     $array[0][1]++;
//                     if($array[0][1] <= 3)
//                         $array[1][1] += (string)$estraz[$j] . "-";
//                }
//            }
//           }
//        return $array;
//    }
//        function checkSestina($sestinaObj, $sestina, $quad, $estraz, $esitiPositivi, $esitiNegativi, $ambi, $nTerni, $nQuaterne)
//        { //DA METTERE DENTRO UN CICLO CHE SCORRA LA SESTINA e le estrazioni.
//           
//            $array= array(      
//                  array("countVincita",0),
//                  array("terno",""));
//            //CERCARE NEL DB SE ESISTE LA SESTINA SE SI GLI AGGIORNO I VALORI
//             if($estraz[0] != null){
//                 $array = sestinaValidator($estraz, $quad, $array);
//             }
//               $esiti++;
//          //     $colpi++;
//              if($array[0][1] < 2){
//                          $esitiNegativi++;
//                          $colpi++;
//              }
//              else if($array[0][1] == 2)
//              {
//                  $esitiPositivi++;
//                  $ambi++;
//              }
//              else if($array[0][1] == 3)
//              {
//                  $esitiPositivi++;
//                  $nterni++;
//                  $array[1][1] += ";";
//
//
//              }else if($array[0][1] == 4)
//              {
//                  $esitiPositivi++;
//                  $nQuaterne++;
//
//
//              }
//                   $sestinaObj[0][1] += $esiti;
//                   $sestinaObj[1][1] += $esitiPositivi;
//                   $sestinaObj[2][1] += $esitiNegativi;
//                   $sestinaObj[3][1] += $nterni;
//                   $sestinaObj[4][1] += $ambi;
//                   $sestinaObj[5][1] = array_map('strval', $sestina);
//                   $sestinaObj[8][1] += $nQuaterne;
//
//                   if($array[0][1] == 3){
//                       $sestinaObj[7][1] = $array[1][1];
//                   }      
//                   else{
//                       $sestinaObj[6][1] = 0;
//                       $sestinaObj[7][1] = "";
//
//                   }
//                     return $sestinaObj; 
//        }
//        
//	function getResSestine($Vyfxn1pwvxar, $Vwvjd4q4sfboValues, $quad, $db, $myYear, $sestina, $trip, $ord, $isotopi)
//	{
//                $numAmbi =0;
//                $numTerni = 0;
//                $numQuaterne =0;
//                $EsitiP =0;
//                $EsitiN =0;
//                $sestinaObj= array(      
//                array("esiti",0),
//                array("esitiPositivi",0),
//                array("esitiNegativi",0),
//                array("nterni",0),
//                array("ambi",0),
//                array("sestina",array(0,0,0,0,0,0)),
//                array("colpi",0),
//                array("terno","")
//                          );
//		$V1riot0zwstj = array();
//		$Vwmmg3o5pnns = false;
//		$Vqkyqrmm4mxi = count($Vyfxn1pwvxar);
//		for($V3fceidmdbsb=25; $V3fceidmdbsb<$Vqkyqrmm4mxi; $V3fceidmdbsb++)
//		{
////                    $sestinaObj = checkSestina($sestinaObj, $sestina, $quad, array($Vyfxn1pwvxar[$V3fceidmdbsb]['uno'],
////                        $Vyfxn1pwvxar[$V3fceidmdbsb]['due'],
////                            $Vyfxn1pwvxar[$V3fceidmdbsb]['tre'],
////                            $Vyfxn1pwvxar[$V3fceidmdbsb]['quattro'],
////                            $Vyfxn1pwvxar[$V3fceidmdbsb]['cinque']), $esitiPositivi, $esitiNegativi, $ambi, $nterni, $nQuaterne );
////                    $sestinaString = implode(" ",array_map('strval', $sestinaObj[5][1]));
////                    $numTerni = (string)$sestinaObj[3][1];
////                    $numAmbi = (string)$sestinaObj[4][1];
////                    $numQuaterne = (string)$sestinaObj[8][1];
////                    $EsitiN = (string)$sestinaObj[2][1];
////                    $EsitiP = (string)$sestinaObj[1][1];
//
//			$Vlbjre5r3aqo = array('','','','','','');
//			
//			$Vkxbfwlelran = checkThisValue($Vyfxn1pwvxar[$V3fceidmdbsb]['uno'], $Vwvjd4q4sfboValues);
//			if($Vkxbfwlelran) $Vlbjre5r3aqo[$Vkxbfwlelran-1] = "<b>X</b><span style='font-size:12px'>(".($V3fceidmdbsb-24).")</span>";
//			
//			$Vkxbfwlelran = checkThisValue($Vyfxn1pwvxar[$V3fceidmdbsb]['due'], $Vwvjd4q4sfboValues);
//			if($Vkxbfwlelran) $Vlbjre5r3aqo[$Vkxbfwlelran-1] = "<b>X</b><span style='font-size:12px'>(".($V3fceidmdbsb-24).")</span>";
//			
//			$Vkxbfwlelran = checkThisValue($Vyfxn1pwvxar[$V3fceidmdbsb]['tre'], $Vwvjd4q4sfboValues);
//			if($Vkxbfwlelran) $Vlbjre5r3aqo[$Vkxbfwlelran-1] = "<b>X</b><span style='font-size:12px'>(".($V3fceidmdbsb-24).")</span>";
//			
//			$Vkxbfwlelran = checkThisValue($Vyfxn1pwvxar[$V3fceidmdbsb]['quattro'], $Vwvjd4q4sfboValues);
//			if($Vkxbfwlelran) $Vlbjre5r3aqo[$Vkxbfwlelran-1] = "<b>X</b><span style='font-size:12px'>(".($V3fceidmdbsb-24).")</span>";
//			
//			$Vkxbfwlelran = checkThisValue($Vyfxn1pwvxar[$V3fceidmdbsb]['cinque'], $Vwvjd4q4sfboValues);
//			if($Vkxbfwlelran) $Vlbjre5r3aqo[$Vkxbfwlelran-1] = "<b>X</b><span style='font-size:12px'>(".($V3fceidmdbsb-24).")</span>";
//			
//			
//			$Vgpbrdjsz50hEmpty = array_count_values($Vlbjre5r3aqo);
//			if($Vgpbrdjsz50hEmpty[""] != 6)
//			{
//                            
//				$V1riot0zwstj[] = $Vlbjre5r3aqo;
//                                if($Vgpbrdjsz50hEmpty[""] == 4){
//                                    $EsitiP++;
//                                    $numAmbi++;
//                                }
//                                if($Vgpbrdjsz50hEmpty[""] == 3){
//                                    $EsitiP++;
//                                    $numTerni++;
//                                }
//                                if($Vgpbrdjsz50hEmpty[""] == 2){
//                                    $EsitiP++;
//                                    $numQuaterne++;
//                                }
//                                if($Vgpbrdjsz50hEmpty[""] == 5)$EsitiN++;
//				if($Vgpbrdjsz50hEmpty[""] <= 4)
//					$Vwmmg3o5pnns = true;
//			}else $EsitiN++;
//		}
//                  $sestinaString = implode(" ",array_map('strval', $sestina));
//if($isotopi == true)
//    $iso = "Non uniti";
//else
//    $iso = "Uniti";
//        
//		$result =  $db->read("SELECT sestina, Ambi, nTerni, nQuaterne, EsitiPositivi, EsitiNegativi FROM sest$myYear where sestina =  '$sestinaString' AND trip = '$trip' AND ord = '$ord' and isotopi = '$iso' ");
//                     if(sizeof($result) <= 0)
//                            $db->write("INSERT INTO sest$myYear (EsitiPositivi, EsitiNegativi, Ambi, nTerni, sestina, nQuaterne, trip, ord, isotopi) VALUES ('$EsitiP', '$EsitiN','$numAmbi','$numTerni', '$sestinaString', '$numQuaterne', '$trip', '$ord', '$iso')"); 
//                     else{
//                           $numAmbi += intval($result[0]["Ambi"]);
//                           $numTerni += intval($result[0]["nTerni"]);
//                           $numQuaterne += intval($result[0]["nQuaterne"]);
//                           $EsitiP += intval($result[0]["EsitiPositivi"]);
//                           $EsitiN += intval($result[0]["EsitiNegativi"]);
//                            $db->write("UPDATE sest$myYear SET  EsitiPositivi = '$EsitiP', EsitiNegativi = '$EsitiN', ambi = '$numAmbi', nTerni = '$numTerni', nQuaterne = '$numQuaterne' where sestina = '$sestinaString' AND trip = '$trip' AND ord = '$ord' and isotopi = '$iso'"); 
//
//                     }
//		if($Vwmmg3o5pnns)
//			return $V1riot0zwstj;
//		else
//			return false;
//	}
//	
//	function checkThisValue($Vm1my3thfa0yue, $Vuxwwozuofie)
//	{
//		
//		
//		foreach($Vuxwwozuofie as $V05a0cmbwbwq => $Vkcyei5rxehr)
//			if($Vkcyei5rxehr == $Vm1my3thfa0yue)
//				return $V05a0cmbwbwq+1;
//		return 0;
//		
//	}
//	
?>