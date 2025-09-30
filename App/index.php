<!DOCTYPE html>
<html>
<head>
    <title>LOTTO </title>
    <style type="text/css">
        @font-face { font-family: Lato; src: url(css/Lato/Lato-Regular.ttf); }
        @font-face { font-family: Lato; font-weight: bold; src: url(css/Lato/Lato-Bold.ttf); }
        body { font-family: Lato; background: #fff; margin: 0px; }
        .header { width: 100%; background: #ffffff; padding: 15px 0 15px 0; border-bottom: 2px solid #FF5C01; margin-bottom: 25px; }
        .item { display: inline-block; background: #ffffff; width: 200px; height: 150px; margin: 0 20px 0 20px; border-radius: 5px; box-shadow: 1px 1px 3px 1px rgba(0,0,0,.2); vertical-align: top; cursor: pointer; transition: opacity .4s ease-in-out; }
        .item:hover { opacity: 1; transition: opacity .4s ease-in-out; }
        .title { font-weight: bold; text-transform: uppercase; padding: 7px 0 7px 0; width: 90%; color: #555; border-bottom: 1px solid rgba(255,92,1,.3); }
        .content { width: 90%; text-align: center; padding-top: 20px; color: #777; }
    </style>
</head>
<body>

<?php
require_once '../Classes/DBM.php';

class LottoFetcher {
    private $cacheDir;

    public function __construct($cacheDir = __DIR__ . '/../cache') {
        $this->cacheDir = $cacheDir;
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    public function fetchTXT($year) {
        $cacheFile = "{$this->cacheDir}/estrazioni_$year.txt";
        if (file_exists($cacheFile) && filemtime($cacheFile) > strtotime('-1 day')) {
            return $cacheFile;
        }
        $url = "https://www.lottologia.com/lotto/archivio-estrazioni/?as=TXT&year=$year";
        $context = stream_context_create(['http' => ['header' => 'User-Agent: Mozilla compatible']]);
        $response = @file_get_contents($url, false, $context);
        if ($response === false) {
            throw new Exception("Impossibile scaricare il file TXT da $url");
        }
        file_put_contents($cacheFile, $response);
        return $cacheFile;
    }

    public function parseTXT($txtFile) {
        $rows = [];
        $lines = file($txtFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Trova la riga d'intestazione (quella che inizia con DATE)
        $headerLine = null;
        foreach ($lines as $idx => $line) {
            if (preg_match('/^DATE(\s|$)/', $line)) {
                $headerLine = $idx;
                break;
            }
        }
        if ($headerLine === null) {
            throw new Exception("Intestazione con 'DATE' non trovata nel file TXT.");
        }

        // Prendi le ruote dall'intestazione
        $header = preg_split('/\s+/', trim($lines[$headerLine]));
        $ruote = array_slice($header, 1); // esclude 'DATE'
        $hasNazionale = in_array('Nazionale', $ruote);

        // Processa ogni riga dati dopo l'intestazione
        for ($i = $headerLine + 1; $i < count($lines); $i++) {
            $data = preg_split('/\s+/', trim($lines[$i]));
            if (count($data) < 1 + count($ruote) * 5) continue; // riga incompleta
            $row = [];
            $row['DATE'] = $data[0];
            $offset = 1;
            foreach ($ruote as $ruota) {
                for ($j = 1; $j <= 5; $j++) {
                    $row[$ruota . $j] = $data[$offset++];
                }
            }
            $rows[] = $row;
        }

        return $rows;
    }
}

// Aggiorna solo le ultime estrazioni dell'anno corrente senza cancellare la tabella
$currentYear = (int)date('Y');
try {
    $fetcher = new LottoFetcher();
    $dbm = new DBM();
    $mesi = [
        'January' => 'Gennaio', 'February' => 'Febbraio', 'March' => 'Marzo',
        'April' => 'Aprile', 'May' => 'Maggio', 'June' => 'Giugno',
        'July' => 'Luglio', 'August' => 'Agosto', 'September' => 'Settembre',
        'October' => 'Ottobre', 'November' => 'Novembre', 'December' => 'Dicembre'
    ];
    $ruote = [
        "Bari", "Cagliari", "Firenze", "Genova", "Milano",
        "Napoli", "Palermo", "Roma", "Torino", "Venezia", "Nazionale"
    ];
    $txtFile = $fetcher->fetchTXT($currentYear);
    $estrazioni = $fetcher->parseTXT($txtFile);
    usort($estrazioni, function($a, $b) {
        return strtotime($a['DATE']) <=> strtotime($b['DATE']);
    });
    $progressivo = 1;
    foreach ($estrazioni as $estrazione) {
        $timestamp = strtotime($estrazione['DATE']);
        $mese_en = date('F', $timestamp);
        $mese_it = $mesi[$mese_en];
        $date = date('d', $timestamp) . ' ' . $mese_it . ' ' . date('Y', $timestamp);
        // Controlla se la data è già presente
        $check = $dbm->read("SELECT COUNT(*) as cnt FROM year$currentYear WHERE data = '" . addslashes($date) . "'");
        if (isset($check[0]['cnt']) && $check[0]['cnt'] > 0) {
            continue; // già presente, salta
        }
        $data = [];
        foreach ($ruote as $ruota) {
            $nums = [];
            for ($i = 1; $i <= 5; $i++) {
                $col = $ruota . $i;
                $nums[] = isset($estrazione[$col]) ? $estrazione[$col] : '';
            }
            $data[$ruota] = implode('.', $nums);
        }
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
        $sql = "INSERT INTO year$currentYear (estrazione, data, valori) VALUES ('$progressivo', '$date', '$jsonData')";
        $dbm->write($sql);
        $progressivo++;
    }

} catch (Exception $e) {
    echo "<b>Errore:</b> " . htmlspecialchars($e->getMessage());
}

$output = $dbm->read("SELECT distinct * FROM year$currentYear");
        
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

						$sql = "INSERT INTO quad$currentYear VALUES('$data','$ruota', $estrazione, '$distanza', '$tripla', $val1, $val2)";	

						$dbm->write($sql);

					}

			next($est);

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