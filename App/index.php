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
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
        $zipFile = "{$this->cacheDir}/storico.zip";
        $txtFile = "{$this->cacheDir}/storico.txt";

        // Scarica lo zip solo se non aggiornato da 1 giorno
        if (!file_exists($zipFile) || filemtime($zipFile) < strtotime('-1 day')) {
            $url = "https://www.brightstarlottery.it/STORICO_ESTRAZIONI_LOTTO/storico.zip";
            $context = stream_context_create(['http' => ['header' => 'User-Agent: Mozilla compatible']]);
            $data = @file_get_contents($url, false, $context);
            if ($data === false) {
                throw new Exception("Impossibile scaricare $url");
            }
            file_put_contents($zipFile, $data);

            // Estrai lo zip
            $zip = new ZipArchive;
            if ($zip->open($zipFile) === TRUE) {
                $zip->extractTo($this->cacheDir);
                $zip->close();
            } else {
                throw new Exception("Impossibile aprire l'archivio ZIP.");
            }
        }

        // Individua il file TXT estratto (il nome può cambiare)
        if (!file_exists($txtFile)) {
            $txtCandidates = glob($this->cacheDir . '/*.txt');
            if (!$txtCandidates) {
                throw new Exception("Nessun file TXT trovato nello zip.");
            }
            $txtFile = $txtCandidates[0];
        }

        return $this->filterByYear($txtFile, $year);
    }

    private function filterByYear($txtFile, $year) {
        $filteredFile = "{$this->cacheDir}/estrazioni_$year.txt";
        $in = fopen($txtFile, 'r');
        $out = fopen($filteredFile, 'w');
        if (!$in || !$out) {
            throw new Exception("Errore apertura file in lettura/scrittura.");
        }

        while (($line = fgets($in)) !== false) {
            if (preg_match("/^$year\//", $line)) {
                fwrite($out, $line);
            }
        }

        fclose($in);
        fclose($out);
        return $filteredFile;
    }

    public function parseTXT($txtFile)
{
    $lines = file($txtFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Mappa codici ruota -> nome ruota
    $map = [
        'BA' => 'Bari',
        'CA' => 'Cagliari',
        'FI' => 'Firenze',
        'GE' => 'Genova',
        'MI' => 'Milano',
        'NA' => 'Napoli',
        'PA' => 'Palermo',
        'RM' => 'Roma',
        'TO' => 'Torino',
        'VE' => 'Venezia',
        'RN' => 'Nazionale' // ✅ BrightStar usa RN per Nazionale
    ];

    $estrazioni = [];

    foreach ($lines as $line) {
        // Pulisce eventuali caratteri invisibili e spazi doppi
        $line = trim($line);
        if ($line === '') continue;

        // Divide la riga in colonne (separatore = spazi o tab)
        $parts = preg_split('/\s+/', $line);
        if (count($parts) < 7) continue; // riga incompleta

        // Estrai i campi
        $date = trim($parts[0]);
        $ruotaCode = strtoupper(trim($parts[1])); // <-- pulizia chiave importante

        // Se la ruota non è mappata, ignorala
        if (!isset($map[$ruotaCode])) continue;

        $ruota = $map[$ruotaCode];
        $n1 = trim($parts[2] ?? '');
        $n2 = trim($parts[3] ?? '');
        $n3 = trim($parts[4] ?? '');
        $n4 = trim($parts[5] ?? '');
        $n5 = trim($parts[6] ?? '');

        // Se mancano numeri validi, ignora la riga
        if (!is_numeric($n1) || !is_numeric($n2) || !is_numeric($n3) || !is_numeric($n4) || !is_numeric($n5)) {
            continue;
        }

        // Crea la data se non esiste ancora
        if (!isset($estrazioni[$date])) {
            $estrazioni[$date] = ['DATE' => str_replace('/', '-', $date)];
        }

        // Inserisce solo le ruote realmente presenti
        for ($i = 1; $i <= 5; $i++) {
            $estrazioni[$date]["{$ruota}{$i}"] = ${"n$i"};
        }
    }

    // Trasforma l'array associativo in una lista ordinata per data
    $rows = array_values($estrazioni);
    usort($rows, fn($a, $b) => strcmp($a['DATE'], $b['DATE']));

    return $rows;
}
}

// Aggiorna solo le ultime estrazioni dell'anno corrente senza cancellare la tabella
$currentYear = 2015;
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
    // Se la ruota non esiste in questa estrazione, salta
    if (!isset($estrazione[$ruota . '1'])) {
        continue;
    }

    $nums = [];
    for ($i = 1; $i <= 5; $i++) {
        $col = $ruota . $i;
        $nums[] = $estrazione[$col];
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
// Pulisce la tabella quadrature dell'anno corrente prima di rigenerarla
$dbm->write("DELETE FROM quad$currentYear");

$output = $dbm->read("SELECT distinct * FROM year$currentYear");
        
        //$result = mysql_query("SELECT * FROM year$myYear",$connection);
        //$output = mysql_fetch_array($result, MYSQL_ASSOC);
        //$output=mysqli_fetch_all ($result, MYSQL_ASSOC);

	foreach($output as $row) {
    $est = json_decode($row['valori'], true);
    $data = $row['data'];

    while($valori = current($est)) {
        $ruota = key($est);
        $estrat = explode('.', $valori);

        for($aux=0; $aux<5; $aux++)
            for($aux1=$aux+1; $aux1<5; $aux1++)
                if(getDistance($estrat[$aux], $estrat[$aux1]) == 'x') {
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
    if (!is_numeric($x) || !is_numeric($y)) return null;

    $tmp = abs($x - $y);
    if ($tmp > 45) $aux = 90 - intval($tmp);
    else $aux = $tmp;

    return ($aux == 3) ? 'x' : $aux;
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
