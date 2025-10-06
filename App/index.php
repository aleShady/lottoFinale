<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOTTO</title>
    <style>
        @font-face { font-family: Lato; src: url(css/Lato/Lato-Regular.ttf); font-display: swap; }
        @font-face { font-family: Lato; font-weight: bold; src: url(css/Lato/Lato-Bold.ttf); font-display: swap; }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Lato, sans-serif; background: #f5f5f5; }
        
        .header { 
            width: 100%; 
            background: #fff; 
            padding: 15px 0; 
            border-bottom: 2px solid #FF5C01; 
            margin-bottom: 25px; 
            text-align: center;
        }
        
        .header img { max-width: 300px; height: auto; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        .grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
            gap: 20px; 
            justify-items: center;
        }
        
        .item { 
            background: #fff; 
            width: 100%; 
            max-width: 220px;
            min-height: 150px; 
            border-radius: 8px; 
            box-shadow: 0 2px 8px rgba(0,0,0,.15); 
            cursor: pointer; 
            transition: transform .2s, box-shadow .2s;
            padding: 1.5rem;
        }
        
        .item:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 4px 12px rgba(0,0,0,.2); 
        }
        
        .title { 
            font-weight: bold; 
            text-transform: uppercase; 
            padding-bottom: 10px; 
            color: #555; 
            border-bottom: 2px solid rgba(255,92,1,.3); 
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .content { 
            text-align: center; 
            color: #777; 
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .error-box {
            padding: 20px;
            background: #fee;
            border: 2px solid #c00;
            margin: 20px;
            border-radius: 8px;
            color: #c00;
        }

        @media (max-width: 768px) {
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// === CLASSE DBM SICURA ===
class DBM {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=localhost;dbname=dsantarella;charset=utf8mb4",
                "root",
                "",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );
        } catch (PDOException $e) {
            error_log("DB error: " . $e->getMessage());
            throw new RuntimeException("Connessione database fallita");
        }
    }

    public function read($sql, array $params = []) {
        if (empty($sql)) return [];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Read error: " . $e->getMessage());
            throw new RuntimeException("Errore lettura database");
        }
    }

    public function write($sql, array $params = []) {
        if (empty($sql)) return false;
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Write error: " . $e->getMessage());
            throw new RuntimeException("Errore scrittura database");
        }
    }

    public function beginTransaction() { 
        return $this->pdo->beginTransaction(); 
    }
    
    public function commit() { 
        return $this->pdo->commit(); 
    }
    
    public function rollBack() { 
        return $this->pdo->rollBack(); 
    }
}

// === CLASSE LOTTO MANAGER ===
class LottoManager {
    const CACHE_TTL = 3600;
    const SOURCE_URL = 'https://www.brightstarlottery.it/STORICO_ESTRAZIONI_LOTTO/storico.zip';
    
    private static $RUOTE_MAP = [
        'BA' => 'Bari', 'CA' => 'Cagliari', 'FI' => 'Firenze',
        'GE' => 'Genova', 'MI' => 'Milano', 'NA' => 'Napoli',
        'PA' => 'Palermo', 'RM' => 'Roma', 'TO' => 'Torino',
        'VE' => 'Venezia', 'RN' => 'Nazionale'
    ];
    
    private static $MESI_IT = [
        'January' => 'Gennaio', 'February' => 'Febbraio', 'March' => 'Marzo',
        'April' => 'Aprile', 'May' => 'Maggio', 'June' => 'Giugno',
        'July' => 'Luglio', 'August' => 'Agosto', 'September' => 'Settembre',
        'October' => 'Ottobre', 'November' => 'Novembre', 'December' => 'Dicembre'
    ];

    private $cacheDir;
    private $db;

    public function __construct($cacheDir = null) {
        if ($cacheDir === null) {
            $cacheDir = __DIR__ . '/../cache';
        }
        $this->cacheDir = $cacheDir;
        $this->db = new DBM();
        
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    public function processYear($year) {
        $txtFile = $this->fetchTXT($year);
        $estrazioni = $this->parseTXT($txtFile);
        $this->importEstrazioni($estrazioni, $year);
        $this->generateQuadrature($year);
    }

    private function fetchTXT($year) {
        $zipFile = "{$this->cacheDir}/storico.zip";
        $txtFile = "{$this->cacheDir}/storico.txt";
        $filteredFile = "{$this->cacheDir}/estrazioni_{$year}.txt";

        if (file_exists($filteredFile) && (time() - filemtime($filteredFile)) < self::CACHE_TTL) {
            return $filteredFile;
        }

        $context = stream_context_create([
            'http' => ['header' => 'User-Agent: Mozilla/5.0', 'timeout' => 30]
        ]);

        $data = @file_get_contents(self::SOURCE_URL, false, $context);
        if ($data === false) throw new RuntimeException('Download fallito');
        file_put_contents($zipFile, $data);

        $zip = new ZipArchive();
        if ($zip->open($zipFile) !== true) throw new RuntimeException('ZIP non apribile');
        $zip->extractTo($this->cacheDir);
        $zip->close();

        if (!file_exists($txtFile)) {
            $candidates = glob($this->cacheDir . '/*.txt');
            if (empty($candidates)) throw new RuntimeException('TXT non trovato');
            $txtFile = $candidates[0];
        }

        $this->filterByYear($txtFile, $year, $filteredFile);
        return $filteredFile;
    }

    private function filterByYear($source, $year, $dest) {
        $in = fopen($source, 'r');
        $out = fopen($dest, 'w');
        if (!$in || !$out) throw new RuntimeException('Errore file');

        $pattern = "/^{$year}\//";
        while (($line = fgets($in)) !== false) {
            if (preg_match($pattern, $line)) fwrite($out, $line);
        }
        fclose($in);
        fclose($out);
    }

    private function parseTXT($txtFile) {
        $lines = file($txtFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $estrazioni = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            $parts = preg_split('/\s+/', $line);
            if (count($parts) < 7) continue;

            $date = trim($parts[0]);
            $ruotaCode = strtoupper(trim($parts[1]));
            if (!isset(self::$RUOTE_MAP[$ruotaCode])) continue;

            $ruota = self::$RUOTE_MAP[$ruotaCode];
            $numeri = array_slice($parts, 2, 5);
            if (count(array_filter($numeri, 'is_numeric')) !== 5) continue;

            $dateKey = str_replace('/', '-', $date);
            if (!isset($estrazioni[$dateKey])) {
                $estrazioni[$dateKey] = ['DATE' => $dateKey];
            }

            for ($i = 0; $i < 5; $i++) {
                $estrazioni[$dateKey]["{$ruota}" . ($i + 1)] = (int)$numeri[$i];
            }
        }

        $rows = array_values($estrazioni);
        usort($rows, function($a, $b) {
            return strcmp($a['DATE'], $b['DATE']);
        });
        return $rows;
    }

    private function importEstrazioni($estrazioni, $year) {
        $table = "year{$year}";
        $maxRes = $this->db->read("SELECT MAX(estrazione) as max_estr FROM {$table}");
        $progressivo = isset($maxRes[0]['max_estr']) && $maxRes[0]['max_estr'] 
            ? (int)$maxRes[0]['max_estr'] + 1 : 1;

        foreach ($estrazioni as $estrazione) {
            $timestamp = strtotime($estrazione['DATE']);
            $meseIt = self::$MESI_IT[date('F', $timestamp)];
            $date = date('d', $timestamp) . ' ' . $meseIt . ' ' . date('Y', $timestamp);

            $check = $this->db->read("SELECT COUNT(*) as cnt FROM {$table} WHERE data = ?", [$date]);
            if ($check[0]['cnt'] > 0) continue;

            $valori = [];
            foreach (self::$RUOTE_MAP as $ruota) {
                if (!isset($estrazione["{$ruota}1"])) continue;
                
                $nums = [];
                for ($i = 1; $i <= 5; $i++) {
                    $nums[] = $estrazione["{$ruota}{$i}"];
                }
                $valori[$ruota] = implode('.', $nums);
            }

            $this->db->write(
                "INSERT INTO {$table} (estrazione, data, valori) VALUES (?, ?, ?)",
                [$progressivo, $date, json_encode($valori, JSON_UNESCAPED_UNICODE)]
            );
            $progressivo++;
        }
    }

    private function generateQuadrature($year) {
        $tableYear = "year{$year}";
        $tableQuad = "quad{$year}";

        $this->db->write("DELETE FROM {$tableQuad}");
        $output = $this->db->read("SELECT * FROM {$tableYear}");

        foreach ($output as $row) {
            $est = json_decode($row['valori'], true);
            if (!$est) continue;

            foreach ($est as $ruota => $valori) {
                $estrat = explode('.', $valori);
                if (count($estrat) !== 5) continue;

                for ($i = 0; $i < 4; $i++) {
                    for ($j = $i + 1; $j < 5; $j++) {
                        if ($this->getDistance((int)$estrat[$i], (int)$estrat[$j]) === 3) {
                            $this->db->write(
                                "INSERT INTO {$tableQuad} (data, ruota, estrazione, distanza, tripla, val1, val2) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)",
                                [
                                    $row['data'], 
                                    $ruota, 
                                    (int)$row['estrazione'],
                                    ($i + 1) . '-' . ($j + 1),
                                    $this->getTripla((int)$estrat[$i]),
                                    (int)$estrat[$i], 
                                    (int)$estrat[$j]
                                ]
                            );
                        }
                    }
                }
            }
        }
    }

    private function getDistance($x, $y) {
        $diff = abs($x - $y);
        return $diff > 45 ? 90 - $diff : $diff;
    }

    private function getTripla($val) {
        $sum = ($val >= 10) ? (int)($val / 10) + ($val % 10) : $val;
        if ($sum > 9) $sum -= 9;
        
        if (in_array($sum, [1, 4, 7])) return '1-4-7';
        if (in_array($sum, [2, 5, 8])) return '2-5-8';
        if (in_array($sum, [3, 6, 9])) return '3-6-9';
        throw new LogicException("Tripla non valida per valore: {$val}");
    }
}

// === ESECUZIONE ===
try {
    $manager = new LottoManager();
    $manager->processYear(2025);
} catch (Exception $e) {
    echo '<div class="error-box"><strong>Errore:</strong> ' 
        . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') 
        . '</div>';
}
?>

<div class="header">
    <img src="img/logo.png" alt="Logo Lotto" loading="lazy">
</div>

<div class="container">
    <div class="grid">
        <div class="item" data-url="estrazioni">
            <div class="title">Estrazioni</div>
            <div class="content">Elenco di tutte le estrazioni dal 1871 ad oggi.</div>
        </div>
        <div class="item" data-url="quadrature">
            <div class="title">Quadrature</div>
            <div class="content">Domenico</div>
        </div>
        <div class="item" data-url="modulo_uno">
            <div class="title">Modulo 1</div>
            <div class="content">Calcolo di uscite in base a configurazione</div>
        </div>
        <div class="item" data-url="modulo_due">
            <div class="title">Modulo 2</div>
            <div class="content">Calcolo delle sestine</div>
        </div>
        <div class="item" data-url="modulo_tre">
            <div class="title">Modulo 3</div>
            <div class="content">Tabelloni ambi, terne e quaterne</div>
        </div>
        <div class="item" data-url="TotaliSestine">
            <div class="title">Sestine</div>
            <div class="content">Elenco sestine</div>
        </div>
        <div class="item" data-url="elencoSestine">
            <div class="title">Trova pagine sestine</div>
            <div class="content">Trova pagine sestine</div>
        </div>
        <div class="item" data-url="nSestine">
            <div class="title">Trova terni e quaterne</div>
            <div class="content">Trova pagine sestine</div>
        </div>
    </div>
</div>

<script>
'use strict';
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.item').forEach(function(item) {
        item.addEventListener('click', function() {
            var url = this.dataset.url;
            if (url) window.location.href = url;
        });
    });
});
</script>
</body>
</html>
