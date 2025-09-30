<?php
require_once __DIR__ . '/../vendor/autoload.php'; // aggiungi questa riga in cima al file

use PhpOffice\PhpSpreadsheet\IOFactory;
use Shuchkin\SimpleXLS;

class LottoFetcher {
    private $cacheDir;

    public function __construct($cacheDir = __DIR__ . '/../cache') {
        $this->cacheDir = $cacheDir;
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    public function fetch($year) {
        $cacheFile = "{$this->cacheDir}/estrazioni_$year.html";
        if (file_exists($cacheFile) && filemtime($cacheFile) > strtotime('-1 day')) {
            return file_get_contents($cacheFile);
        }
        $url = "https://www.lottologia.com/lotto/?do=archivio-estrazioni&tab=&year=$year";
        $context = stream_context_create(['http' => ['header' => 'User-Agent: Mozilla compatible']]);
        $response = @file_get_contents($url, false, $context);
        if ($response === false) {
            throw new Exception("Impossibile scaricare i dati da $url");
        }
        file_put_contents($cacheFile, $response);
        // Dopo file_put_contents($cacheFile, $response);
        if (filesize($cacheFile) < 10000) { // ad esempio, meno di 10 KB è sospetto
            throw new Exception("Il file scaricato è troppo piccolo, probabile errore nel download.");
        }
        return $response;
    }

    public function parse($html) {
        require_once __DIR__ . "/simple_html_dom.php";
        $dom = str_get_html($html);
        if (!$dom) throw new Exception("Parsing HTML fallito");
        $table = $dom->find('table', 0);
        if (!$table) throw new Exception("Tabella non trovata");
        return $table;
    }

    public function fetchCSV($year) {
        $cacheFile = "{$this->cacheDir}/estrazioni_$year.csv";
        if (file_exists($cacheFile) && filemtime($cacheFile) > strtotime('-1 day')) {
            return file_get_contents($cacheFile);
        }
        // URL CSV dal nuovo sito
        $url = "https://www.estrazionilotto.it/lotto/archivio-storico/download/$year.csv";
        $context = stream_context_create(['http' => ['header' => 'User-Agent: Mozilla compatible']]);
        $response = @file_get_contents($url, false, $context);
        if ($response === false) {
            throw new Exception("Impossibile scaricare i dati da $url");
        }
        file_put_contents($cacheFile, $response);
        return $response;
    }

    public function parseCSV($csvContent) {
        $rows = [];
        $lines = explode("\n", $csvContent);
        $header = null;
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $data = str_getcsv($line, ';');
            if (!$header) {
                $header = $data;
            } else {
                // Solo se il numero di colonne corrisponde all'header
                if (count($data) === count($header)) {
                    $rows[] = array_combine($header, $data);
                }
            }
        }
        return $rows;
    }

    public function fetchXLS($year) {
        $cacheFile = "{$this->cacheDir}/estrazioni_$year.xls";
        if (file_exists($cacheFile) && filesize($cacheFile) > 1000 && filemtime($cacheFile) > strtotime('-1 day')) {
            return $cacheFile;
        }
        $url = "https://www.lottologia.com/lotto/archivio-estrazioni/?as=XLS&year=$year";
        $opts = [
            "http" => [
                "header" => "User-Agent: Mozilla/5.0\r\nAccept: application/vnd.ms-excel\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $response = @file_get_contents($url, false, $context);
        if ($response === false) {
            throw new Exception("Impossibile scaricare il file XLS da $url");
        }
        // Controllo se il file è HTML (errore)
        if (strpos(trim($response), '<!DOCTYPE') === 0 || strpos(trim($response), '<html') === 0) {
            throw new Exception("Il file scaricato non è un XLS valido (probabile blocco dal sito).");
        }
        file_put_contents($cacheFile, $response);
        return $cacheFile;
    }

    public function parseXLS($xlsFile) {
        if ($xls = SimpleXLS::parse($xlsFile)) {
            $rows = $xls->rows();
            // $rows è un array di righe
        } else {
            throw new Exception(SimpleXLS::parseError());
        }
        return $rows;
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

        // Ignora la prima riga (intestazione generale)
        array_shift($lines);

        // Usa la seconda riga come intestazione delle colonne (spazi multipli!)
        $header = preg_split('/\s+/', trim(array_shift($lines)));

        if (!in_array("DATE", $header)) {
            throw new Exception("La colonna DATE non è presente nell'intestazione del file TXT.");
        }

        foreach ($lines as $line) {
            $data = preg_split('/\s+/', trim($line));
            if (count($data) < count($header)) continue; // Salta righe vuote o incomplete
            $rows[] = array_combine($header, array_slice($data, 0, count($header)));
        }

        return $rows;
    }
}