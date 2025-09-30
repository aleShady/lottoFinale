<?php
	include '../../Classes/DBM.php';
	$Vfs2niaige2t = json_decode($_REQUEST['model']);
	$Vmjyb4nwthls = new DBM();
	$Vjrtbrrwdfmt = 24;
	
	$Vzkdzprmnhzz = $Vfs2niaige2t->year;
	$Vipwuwayqqjl = $Vfs2niaige2t->estrazione - $Vjrtbrrwdfmt;
	$Voungjt1lyll[$Vfs2niaige2t->ruota1] = array();
	$Voungjt1lyll[$Vfs2niaige2t->ruota2] = array();
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
		if (!empty($V3uzfc1u1jkg)) {
			$Vlbjre5r3aqo = $Vmjyb4nwthls->read($V3uzfc1u1jkg);
		} else {
			$Vlbjre5r3aqo = [];
		}
		foreach($Vlbjre5r3aqo as $Vukau3qnkuvr)
		{
			$Voungjt1lyll[$Vfs2niaige2t->ruota1][$Vkxbfwlelran]["estrazione"] = $Vukau3qnkuvr['estrazione'];
			$Voungjt1lyll[$Vfs2niaige2t->ruota1][$Vkxbfwlelran]["data"] = $Vukau3qnkuvr['data'];
			$Voungjt1lyll[$Vfs2niaige2t->ruota1][$Vkxbfwlelran]["luogo"] = $Vfs2niaige2t->ruota1;
			
			$VkxbfwlelranRuote = json_decode($Vukau3qnkuvr['valori'], true);
			while ($Va2u1syzilkw = current($VkxbfwlelranRuote))
			{
				if(key($VkxbfwlelranRuote) == $Vfs2niaige2t->ruota1)
				{
					$Vftwmde4tnzi = explode('.', $Va2u1syzilkw);
					$Voungjt1lyll[$Vfs2niaige2t->ruota1][$Vkxbfwlelran]["uno"] = $Vftwmde4tnzi[0];
					$Voungjt1lyll[$Vfs2niaige2t->ruota1][$Vkxbfwlelran]["due"] = $Vftwmde4tnzi[1];
					$Voungjt1lyll[$Vfs2niaige2t->ruota1][$Vkxbfwlelran]["tre"] = $Vftwmde4tnzi[2];
					$Voungjt1lyll[$Vfs2niaige2t->ruota1][$Vkxbfwlelran]["quattro"] = $Vftwmde4tnzi[3];
					$Voungjt1lyll[$Vfs2niaige2t->ruota1][$Vkxbfwlelran]["cinque"] = $Vftwmde4tnzi[4];
				}
				next($VkxbfwlelranRuote);
			}
		}
	
		
		$V3uzfc1u1jkg = "SELECT * FROM year" . $Vzkdzprmnhzz . " WHERE estrazione = " . ($Vipwuwayqqjl + $V0hs02vbbzd4);
		if (!empty($V3uzfc1u1jkg)) {
			$Vlbjre5r3aqo = $Vmjyb4nwthls->read($V3uzfc1u1jkg);
		} else {
			$Vlbjre5r3aqo = [];
		}
		foreach($Vlbjre5r3aqo as $Vukau3qnkuvr)
		{
			$Voungjt1lyll[$Vfs2niaige2t->ruota2][$Vkxbfwlelran]["estrazione"] = $Vukau3qnkuvr['estrazione'];
			$Voungjt1lyll[$Vfs2niaige2t->ruota2][$Vkxbfwlelran]["data"] = $Vukau3qnkuvr['data'];
			$Voungjt1lyll[$Vfs2niaige2t->ruota2][$Vkxbfwlelran]["luogo"] = $Vfs2niaige2t->ruota2;
			
			$VkxbfwlelranRuote = json_decode($Vukau3qnkuvr['valori'], true);
			while ($Va2u1syzilkw = current($VkxbfwlelranRuote))
			{
				if(key($VkxbfwlelranRuote) == $Vfs2niaige2t->ruota2)
				{
					$Vftwmde4tnzi = explode('.', $Va2u1syzilkw);
					$Voungjt1lyll[$Vfs2niaige2t->ruota2][$Vkxbfwlelran]["uno"] = $Vftwmde4tnzi[0];
					$Voungjt1lyll[$Vfs2niaige2t->ruota2][$Vkxbfwlelran]["due"] = $Vftwmde4tnzi[1];
					$Voungjt1lyll[$Vfs2niaige2t->ruota2][$Vkxbfwlelran]["tre"] = $Vftwmde4tnzi[2];
					$Voungjt1lyll[$Vfs2niaige2t->ruota2][$Vkxbfwlelran]["quattro"] = $Vftwmde4tnzi[3];
					$Voungjt1lyll[$Vfs2niaige2t->ruota2][$Vkxbfwlelran++]["cinque"] = $Vftwmde4tnzi[4];
				}
				next($VkxbfwlelranRuote);
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	echo json_encode($Voungjt1lyll);
	
	
	
	
	function getMaxEstrazioni($Vzkdzprmnhzz)
	{
		$Vtppv1qqczva = new DBM();
		$V3uzfc1u1jkg = "SELECT MAX(estrazione) as 'myMax' FROM year" . $Vzkdzprmnhzz;
		if (!empty($V3uzfc1u1jkg)) {
			$Vlbjre5r3aqo = $Vtppv1qqczva->read($V3uzfc1u1jkg);
		} else {
			$Vlbjre5r3aqo = [];
		}
		return $Vlbjre5r3aqo[0]['myMax'];
	}
?>