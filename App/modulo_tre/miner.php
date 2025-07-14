<?php
	header('Content-Type: application/json');
	ini_set('memory_limit', '-1');
	include '../../Classes/DBM.php';
	include '../../Classes/Quadrature.php';
	$Vfs2niaige2t = json_decode($_REQUEST['model']);
	
	$Vtppv1qqczva = new DBM();
	$Vwvjd4q4sfbo = new Quadrature($Vfs2niaige2t->year, '*', $Vfs2niaige2t->tripla);
	$Vyfxn1pwvxar = $Vfs2niaige2t->ordine == 'destroso' ? $Vwvjd4q4sfbo->getQuadratureDestroso() : $Vwvjd4q4sfbo->getQuadratureSinistroso();
	$Vfs2niaige2tYearEstrazioni = getMaxEstrazioneYear($Vfs2niaige2t->year, $Vtppv1qqczva);
	$Vskj4qd0hwyl	= getMaxEstrazioneYear($Vfs2niaige2t->year-1, $Vtppv1qqczva);
	$Vfs2niaige2t->composizione = explode('-', $Vfs2niaige2t->composizione);
	foreach($Vfs2niaige2t->composizione as $V45qtgu112qa => $Vm1my3thfa0y)	$Vfs2niaige2t->composizione[$V45qtgu112qa] = intval($Vm1my3thfa0y);
	
	$Voungjt1lyll = array();
	$Vuz3sbxdl25x = 0;
	foreach($Vyfxn1pwvxar as $V05a0cmbwbwq)
	{
		$Vwotg4wet5ef = array($V05a0cmbwbwq['somma_1'], $V05a0cmbwbwq['somma_2'], $V05a0cmbwbwq['somma_diag_1'], $V05a0cmbwbwq['somma_diag_2'], $V05a0cmbwbwq['somma_comune'], $V05a0cmbwbwq['raddoppio_somma_comune']);
		$Vgq5ewneu0y5 = $V05a0cmbwbwq['estrazione_1'] > $V05a0cmbwbwq['estrazione_2'] ? $V05a0cmbwbwq['estrazione_1'] : $V05a0cmbwbwq['estrazione_2'];
		
		$Vyfxn1pwvxar = getValoriEstratti($V05a0cmbwbwq['ruota_1'], $V05a0cmbwbwq['ruota_2'], $Vfs2niaige2t->year, $Vgq5ewneu0y5, $Vfs2niaige2tYearEstrazioni, $Vskj4qd0hwyl, $Vtppv1qqczva);
		
		$Vlbjre5r3aqo = isMyComposition($Vyfxn1pwvxar[0], $Vfs2niaige2t->composizione, $Vwotg4wet5ef);
		if($Vlbjre5r3aqo != false)
		{
			$Vovyrnelfi0z = array_merge(array(), $Vwotg4wet5ef);
			orderArrays($Vlbjre5r3aqo,$Vovyrnelfi0z);
			$Vkxbfwlelran = getResSestine($Vyfxn1pwvxar[0], $Vovyrnelfi0z);
			if($Vkxbfwlelran != false)
			{
				$Voungjt1lyll[] = array(
					'History' => $Vyfxn1pwvxar[0],
					'Quadratura' => $V05a0cmbwbwq,
					'Tipologia' => 1,
					'ValoriSestina' => $Vovyrnelfi0z,
					'resSestine' => $Vkxbfwlelran,
					'Comp' => $Vlbjre5r3aqo
				);
			}
			else
			{
				$Vuz3sbxdl25x++;
			}	
		}
		
		$Vlbjre5r3aqo = isMyComposition($Vyfxn1pwvxar[1], $Vfs2niaige2t->composizione, $Vwotg4wet5ef);
		if($Vlbjre5r3aqo != false)
		{
			$Vovyrnelfi0z = array_merge(array(), $Vwotg4wet5ef);
			orderArrays($Vlbjre5r3aqo,$Vovyrnelfi0z);
			$Vkxbfwlelran = getResSestine($Vyfxn1pwvxar[1], $Vovyrnelfi0z);
			if($Vkxbfwlelran != false)
			{
				$Voungjt1lyll[] = array(
					'History' => $Vyfxn1pwvxar[1],
					'Quadratura' => $V05a0cmbwbwq,
					'Tipologia' => 2,
					'ValoriSestina' => $Vovyrnelfi0z,
					'resSestine' => $Vkxbfwlelran,
					'Comp' => $Vlbjre5r3aqo
				);
			}
			else
			{
				$Vuz3sbxdl25x++;
			}	
		}	
	}
	$Voungjt1lyll['fails'] = $Vuz3sbxdl25x;
	$Voungjt1lyll['positivi'] = count($Voungjt1lyll);
	echo json_encode($Voungjt1lyll);



	function getValoriEstratti($Vjhqqhgfyiag, $Vkcfgrcfdwa5, $Vzkdzprmnhzz, $Vdbkqgspzztj, $Vbexasm3f1c0, $Vc12haoiuusd, $Vmjyb4nwthls)
	{
		$Voungjt1lyll = array();
		$Vyxqyeyyweu4 = $Vdbkqgspzztj + 25;
		$Vhr02kzwb2rk = $Vdbkqgspzztj - 24;
		
		if(($Vyxqyeyyweu4 <= $Vbexasm3f1c0) && ($Vhr02kzwb2rk >= 1))
			$V3uzfc1u1jkg = "SELECT * FROM year$Vzkdzprmnhzz WHERE estrazione >= $Vhr02kzwb2rk AND estrazione <= $Vyxqyeyyweu4 ORDER BY estrazione";
		else if(($Vyxqyeyyweu4 > $Vbexasm3f1c0))
			$V3uzfc1u1jkg = "SELECT *, 'id1' OrderKey FROM year$Vzkdzprmnhzz WHERE estrazione >= $Vhr02kzwb2rk UNION SELECT *, 'id2' OrderKey FROM year" . ($Vzkdzprmnhzz+1) . " WHERE estrazione <= " . ($Vyxqyeyyweu4 - $Vbexasm3f1c0) . " ORDER BY OrderKey, estrazione";
		else if(($Vhr02kzwb2rk < 1))
			$V3uzfc1u1jkg = "SELECT *, 'id1' OrderKey FROM year". ($Vzkdzprmnhzz-1) ." WHERE estrazione >= ". ($Vc12haoiuusd - abs($Vhr02kzwb2rk)) ." UNION SELECT *, 'id2' OrderKey FROM year$Vzkdzprmnhzz WHERE estrazione <= $Vyxqyeyyweu4 ORDER BY OrderKey, estrazione";
		else
			die("Errore getValoriEstratti");
		
		$Vlbjre5r3aqo = $Vmjyb4nwthls->read($V3uzfc1u1jkg);
		$V3fceidmdbsb = $V0hs02vbbzd4 = 0;
		$Voungjt1lyll[0] = $Voungjt1lyll[1] = array();
		
		foreach($Vlbjre5r3aqo as $V05a0cmbwbwq)
		{
			$VkxbfwlelranRuote = json_decode($V05a0cmbwbwq['valori'], true);
			while ($Vm1my3thfa0yori = current($VkxbfwlelranRuote))
			{
				if(key($VkxbfwlelranRuote) == $Vjhqqhgfyiag)
				{
					$Vftwmde4tnzi = explode('.', $Vm1my3thfa0yori);
					$Voungjt1lyll[0][$V3fceidmdbsb]["estrazione"] = $V05a0cmbwbwq['estrazione'];
					$Voungjt1lyll[0][$V3fceidmdbsb]["data"] = $V05a0cmbwbwq['data'];
					$Voungjt1lyll[0][$V3fceidmdbsb]["luogo"] = $Vjhqqhgfyiag;
					$Voungjt1lyll[0][$V3fceidmdbsb]["uno"] = $Vftwmde4tnzi[0];
					$Voungjt1lyll[0][$V3fceidmdbsb]["due"] = $Vftwmde4tnzi[1];
					$Voungjt1lyll[0][$V3fceidmdbsb]["tre"] = $Vftwmde4tnzi[2];
					$Voungjt1lyll[0][$V3fceidmdbsb]["quattro"] = $Vftwmde4tnzi[3];
					$Voungjt1lyll[0][$V3fceidmdbsb]["cinque"] = $Vftwmde4tnzi[4];
					$V3fceidmdbsb++;
				}
				else if(key($VkxbfwlelranRuote) == $Vkcfgrcfdwa5)
				{
					$Vftwmde4tnzi = explode('.', $Vm1my3thfa0yori);
					$Voungjt1lyll[1][$V0hs02vbbzd4]["estrazione"] = $V05a0cmbwbwq['estrazione'];
					$Voungjt1lyll[1][$V0hs02vbbzd4]["data"] = $V05a0cmbwbwq['data'];
					$Voungjt1lyll[1][$V0hs02vbbzd4]["luogo"] = $Vkcfgrcfdwa5;
					$Voungjt1lyll[1][$V0hs02vbbzd4]["uno"] = $Vftwmde4tnzi[0];
					$Voungjt1lyll[1][$V0hs02vbbzd4]["due"] = $Vftwmde4tnzi[1];
					$Voungjt1lyll[1][$V0hs02vbbzd4]["tre"] = $Vftwmde4tnzi[2];
					$Voungjt1lyll[1][$V0hs02vbbzd4]["quattro"] = $Vftwmde4tnzi[3];
					$Voungjt1lyll[1][$V0hs02vbbzd4]["cinque"] = $Vftwmde4tnzi[4];
					$V0hs02vbbzd4++;
				}
				next($VkxbfwlelranRuote);
			}
		}
		return $Voungjt1lyll;
	}
	
	function getMaxEstrazioneYear($Vzkdzprmnhzz, $Vtppv1qqczva)
	{
		$Vlbjre5r3aqo = $Vtppv1qqczva->read("SELECT MAX(estrazione) as 'myMax' FROM year" . $Vzkdzprmnhzz);
		return isset($Vlbjre5r3aqo[0]) ? $Vlbjre5r3aqo[0]['myMax'] : 0;
	}
	
	function isMyComposition($Vyfxn1pwvxar, $Vr5tej5r25u1, $Vwvjd4q4sfboValues)
	{
		if(count($Vyfxn1pwvxar) <= 25) return false;	
		$Vagdjnln1j1p = array(0,0,0,0,0,0);
		
		for($V3fceidmdbsb=0; $V3fceidmdbsb<25; $V3fceidmdbsb++)
			addOccurenceComp($Vwvjd4q4sfboValues, $Vagdjnln1j1p, array($Vyfxn1pwvxar[$V3fceidmdbsb]['uno'],$Vyfxn1pwvxar[$V3fceidmdbsb]['due'],$Vyfxn1pwvxar[$V3fceidmdbsb]['tre'],$Vyfxn1pwvxar[$V3fceidmdbsb]['quattro'],$Vyfxn1pwvxar[$V3fceidmdbsb]['cinque']));
			
		foreach($Vagdjnln1j1p as $Vm1my3thfa0y)
		{
			foreach($Vr5tej5r25u1 as $V3fceidmdbsbndex => $V45qtgu112qa)
			{
				if($V45qtgu112qa === $Vm1my3thfa0y)
				{
					unset($Vr5tej5r25u1[$V3fceidmdbsbndex]);
					break;
				}
			}
			if(!count($Vr5tej5r25u1))
			{	
				return $Vagdjnln1j1p;
			}
		}
		
		return false;
	}
	
	function addOccurenceComp($Vwvjd4q4sfboValues, &$Vagdjnln1j1p, $Vmqyrepuywv4)
	{
		for($V3fceidmdbsb=0; $V3fceidmdbsb<6; $V3fceidmdbsb++)
			foreach($Vmqyrepuywv4 as $V45qtgu112qa)
				if($Vwvjd4q4sfboValues[$V3fceidmdbsb] == $V45qtgu112qa)
					$Vagdjnln1j1p[$V3fceidmdbsb]++;
	}
	
	function orderArrays(&$Vgpbrdjsz50h, &$Vuxwwozuofie)
	{
		$Vukwiq0shgsy = false;
		while(!$Vukwiq0shgsy)
		{
			$Vukwiq0shgsy = true;
			for($V0hs02vbbzd4=0; $V0hs02vbbzd4<5; $V0hs02vbbzd4++)
			{
				if($Vgpbrdjsz50h[$V0hs02vbbzd4] > $Vgpbrdjsz50h[$V0hs02vbbzd4+1])
				{
					$Vukwiq0shgsy = false;
					$Vkxbfwlelran = $Vgpbrdjsz50h[$V0hs02vbbzd4];
					$Vgpbrdjsz50h[$V0hs02vbbzd4] = $Vgpbrdjsz50h[$V0hs02vbbzd4+1];
					$Vgpbrdjsz50h[$V0hs02vbbzd4+1] = $Vkxbfwlelran;
					
					$Vkxbfwlelran = $Vuxwwozuofie[$V0hs02vbbzd4];
					$Vuxwwozuofie[$V0hs02vbbzd4] = $Vuxwwozuofie[$V0hs02vbbzd4+1];
					$Vuxwwozuofie[$V0hs02vbbzd4+1] = $Vkxbfwlelran;
				}
			}
		}
	}
	
	function getResSestine($Vyfxn1pwvxar, $Vwvjd4q4sfboValues)
	{
		$V1riot0zwstj = array();
		$Vwmmg3o5pnns = false;
		$Vqkyqrmm4mxi = count($Vyfxn1pwvxar);
		for($V3fceidmdbsb=25; $V3fceidmdbsb<$Vqkyqrmm4mxi; $V3fceidmdbsb++)
		{
			$Vlbjre5r3aqo = array('','','','','','');
			
			$Vkxbfwlelran = checkThisValue($Vyfxn1pwvxar[$V3fceidmdbsb]['uno'], $Vwvjd4q4sfboValues);
			if($Vkxbfwlelran) $Vlbjre5r3aqo[$Vkxbfwlelran-1] = "<b>X</b><span style='font-size:12px'>(".($V3fceidmdbsb-24).")</span>";
			
			$Vkxbfwlelran = checkThisValue($Vyfxn1pwvxar[$V3fceidmdbsb]['due'], $Vwvjd4q4sfboValues);
			if($Vkxbfwlelran) $Vlbjre5r3aqo[$Vkxbfwlelran-1] = "<b>X</b><span style='font-size:12px'>(".($V3fceidmdbsb-24).")</span>";
			
			$Vkxbfwlelran = checkThisValue($Vyfxn1pwvxar[$V3fceidmdbsb]['tre'], $Vwvjd4q4sfboValues);
			if($Vkxbfwlelran) $Vlbjre5r3aqo[$Vkxbfwlelran-1] = "<b>X</b><span style='font-size:12px'>(".($V3fceidmdbsb-24).")</span>";
			
			$Vkxbfwlelran = checkThisValue($Vyfxn1pwvxar[$V3fceidmdbsb]['quattro'], $Vwvjd4q4sfboValues);
			if($Vkxbfwlelran) $Vlbjre5r3aqo[$Vkxbfwlelran-1] = "<b>X</b><span style='font-size:12px'>(".($V3fceidmdbsb-24).")</span>";
			
			$Vkxbfwlelran = checkThisValue($Vyfxn1pwvxar[$V3fceidmdbsb]['cinque'], $Vwvjd4q4sfboValues);
			if($Vkxbfwlelran) $Vlbjre5r3aqo[$Vkxbfwlelran-1] = "<b>X</b><span style='font-size:12px'>(".($V3fceidmdbsb-24).")</span>";
			
			
			$Vgpbrdjsz50hEmpty = array_count_values($Vlbjre5r3aqo);
			if($Vgpbrdjsz50hEmpty[""] != 6)
			{
				$V1riot0zwstj[] = $Vlbjre5r3aqo;
				if($Vgpbrdjsz50hEmpty[""] <= 4)
					$Vwmmg3o5pnns = true;
			}
		}
		
		if($Vwmmg3o5pnns)
			return $V1riot0zwstj;
		else
			return false;
	}
	
	function checkThisValue($Vm1my3thfa0yue, $Vuxwwozuofie)
	{
		
		
		foreach($Vuxwwozuofie as $V05a0cmbwbwq => $Vkcyei5rxehr)
			if($Vkcyei5rxehr == $Vm1my3thfa0yue)
				return $V05a0cmbwbwq+1;
		return 0;
		
	}
	
?>