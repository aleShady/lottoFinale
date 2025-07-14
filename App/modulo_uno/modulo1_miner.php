<?php
	include '../../Classes/DBM.php';
	include '../../Classes/Quadrature.php';
	$Vfs2niaige2t = json_decode($_REQUEST['model']);
	
	$Vwvjd4q4sfbo = new Quadrature($Vfs2niaige2t->year, [$Vfs2niaige2t->distanza[0],$Vfs2niaige2t->distanza[1]], $Vfs2niaige2t->tripla);
	$Vyfxn1pwvxar = $Vwvjd4q4sfbo->getQuadrature();
	
	$Voungjt1lyll = array();
	$Voungjt1lyll['casiAnalizzati'] = 0;
	$Voungjt1lyll['casiPositivi'] = 0;
	$Voungjt1lyll['casiNegativi'] = 0;
	$Voungjt1lyll['ambo'] = 0;
	$Voungjt1lyll['terna'] = 0;
	$Voungjt1lyll['quaterna'] = 0;

	foreach($Vyfxn1pwvxar[$Vfs2niaige2t->ordine] as $V45qtgu112qa)
	{	
		if($Vfs2niaige2t->diagonale == $V45qtgu112qa['diagonale'])
		{
			$Vqysga1dl2af = $V45qtgu112qa["ruota_1"].';'.$V45qtgu112qa["ruota_2"];
			$Vpznyn3vg300 = $V45qtgu112qa["ruota_2"].';'.$V45qtgu112qa["ruota_1"];
			if($Vfs2niaige2t->ruote == $Vqysga1dl2af || $Vfs2niaige2t->ruote == $Vpznyn3vg300)
			{
				$Voungjt1lyll['casiAnalizzati']++;
				$V2qpbzlifjui = getValues($V45qtgu112qa, $Vfs2niaige2t->year);
				$Vkts4sgnj11p = 0;
				foreach($V2qpbzlifjui as $V0hs02vbbzd4)
				{
					$Vlkqk3bgndwz = 0;
					for($V3fceidmdbsb=0; $V3fceidmdbsb<5; $V3fceidmdbsb++)	if(checkValue($V45qtgu112qa, $V0hs02vbbzd4[$V3fceidmdbsb])) $Vlkqk3bgndwz++;
					if($Vlkqk3bgndwz == 2)	$Voungjt1lyll['ambo']++;
					if($Vlkqk3bgndwz == 3)	$Voungjt1lyll['terna']++;
					if($Vlkqk3bgndwz == 4)	$Voungjt1lyll['quaterna']++;
					if($Vlkqk3bgndwz >= 2)	$Vkts4sgnj11p = 1;
				}
				if($Vkts4sgnj11p === 1)
					$Voungjt1lyll['casiPositivi']++;
				else
					$Voungjt1lyll['casiNegativi']++;
			}
		}
	}
	
	echo json_encode($Voungjt1lyll);
	
	function checkValue($Vzbc0ryb3t5d, $Vm1my3thfa0y)
	{	
		if(intval($Vm1my3thfa0y) == intval($Vzbc0ryb3t5d["somma_1"])) return true;
		if(intval($Vm1my3thfa0y) == intval($Vzbc0ryb3t5d["somma_2"])) return true;
		if(intval($Vm1my3thfa0y) == intval($Vzbc0ryb3t5d["somma_diag_1"])) return true;
		if(intval($Vm1my3thfa0y) == intval($Vzbc0ryb3t5d["somma_diag_2"])) return true;
		if(intval($Vm1my3thfa0y) == intval($Vzbc0ryb3t5d["somma_comune"])) return true;
		if(intval($Vm1my3thfa0y) == intval($Vzbc0ryb3t5d["raddoppio_somma_comune"])) return true;
		return false;
	}
	
	function getValues($V45qtgu112qa, $Vzkdzprmnhzz)
	{
		$Vtppv1qqczva = new DBM();
		$Vdyd1lxnnxym = $V45qtgu112qa['estrazione_1'] > $V45qtgu112qa['estrazione_2'] ? $V45qtgu112qa['estrazione_1'] : $V45qtgu112qa['estrazione_2'];
		$V3uzfc1u1jkg = "SELECT * FROM year$Vzkdzprmnhzz WHERE estrazione > $Vdyd1lxnnxym and estrazione < " . ($Vdyd1lxnnxym+26);
		$Va1lvrwplkep = $Vtppv1qqczva->read($V3uzfc1u1jkg);
		$V3fceidmdbsbtems = count($Va1lvrwplkep);
		
		if($V3fceidmdbsbtems < 25)
		{
			$Vyfxn1pwvxar = $Va1lvrwplkep;
			$V231qekwmpqc = 25 - $V3fceidmdbsbtems;
			$Vzkdzprmnhzz++;
			$V3uzfc1u1jkg = "SELECT * FROM year$Vzkdzprmnhzz WHERE estrazione > 0 and estrazione <= " . (25 - $V3fceidmdbsbtems);
			$Vpwgtklazs4v = $Vtppv1qqczva->read($V3uzfc1u1jkg);
			foreach($Vpwgtklazs4v as $V5qrbtqmm2pa)
				$Vyfxn1pwvxar[] = $V5qrbtqmm2pa;
		}
		else
		{
			$Vyfxn1pwvxar = $Va1lvrwplkep;
		}
		
		$V1riot0zwstj = array();
		foreach($Vyfxn1pwvxar as $V3fceidmdbsbtem)
		{
			$Vlbjre5r3aqo = json_decode($V3fceidmdbsbtem["valori"], true);
			$V1riot0zwstj[] = explode('.', $Vlbjre5r3aqo[$V45qtgu112qa["ruota_1"]]);
			$V1riot0zwstj[] = explode('.', $Vlbjre5r3aqo[$V45qtgu112qa["ruota_2"]]);	
		}
		return $V1riot0zwstj;
	}
	
?>