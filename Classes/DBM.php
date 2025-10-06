<?php
declare(strict_types=1);

class DBM {
    private PDO $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=localhost;dbname=dsantarella;charset=utf8mb4",
                "root",
                "",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false, // Sicurezza critica
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new RuntimeException("Impossibile connettersi al database");
        }
    }

    /**
     * Esegue una query SELECT con prepared statement
     * 
     * @param string $sql Query SQL con placeholder (?)
     * @param array $params Parametri da bindare
     * @return array Risultati della query
     */
    public function read(string $sql, array $params = []): array {
        if (empty($sql)) {
            return [];
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Query error in read(): " . $e->getMessage() . " | SQL: " . $sql);
            throw new RuntimeException("Errore durante la lettura dal database");
        }
    }

    /**
     * Esegue una query INSERT/UPDATE/DELETE con prepared statement
     * 
     * @param string $sql Query SQL con placeholder (?)
     * @param array $params Parametri da bindare
     * @return bool Successo dell'operazione
     */
    public function write(string $sql, array $params = []): bool {
        if (empty($sql)) {
            return false;
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Query error in write(): " . $e->getMessage() . " | SQL: " . $sql);
            throw new RuntimeException("Errore durante la scrittura nel database");
        }
    }

    /**
     * Inizia una transazione
     */
    public function beginTransaction(): bool {
        return $this->pdo->beginTransaction();
    }

    /**
     * Conferma una transazione
     */
    public function commit(): bool {
        return $this->pdo->commit();
    }

    /**
     * Annulla una transazione
     */
    public function rollBack(): bool {
        return $this->pdo->rollBack();
    }

    /**
     * Ottiene l'ultimo ID inserito
     */
    public function lastInsertId(): string {
        return $this->pdo->lastInsertId();
    }

    /**
     * Ritorna l'istanza PDO per query complesse
     */
    public function getPDO(): PDO {
        return $this->pdo;
    }
}
?>
