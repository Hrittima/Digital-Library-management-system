<?php
// ─────────────────────────────────────────────────────────────────────────────
// Database connection – PostgreSQL via PDO with a MySQLi compatibility shim
// Set environment variables: DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASSWORD
// ─────────────────────────────────────────────────────────────────────────────

$dbHost     = getenv('DB_HOST');
$dbPort     = getenv('DB_PORT');
$dbName     = getenv('DB_NAME');
$dbUser     = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');

$dbHost     = ($dbHost === false || $dbHost === '')     ? '127.0.0.1'     : $dbHost;
$dbPort     = ($dbPort === false || $dbPort === '')     ? 5432            : (int) $dbPort;
$dbName     = ($dbName === false || $dbName === '')     ? 'digital_library' : $dbName;
$dbUser     = ($dbUser === false || $dbUser === '')     ? 'postgres'      : $dbUser;
$dbPassword = ($dbPassword === false)                   ? ''              : $dbPassword;

$dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName}";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPassword, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection error. Please check your configuration.');
}

// ─────────────────────────────────────────────────────────────────────────────
// MySQLi compatibility shim
// Wraps PDO so all existing $mysqli->prepare() / mysqli_query() calls work
// without touching any of the 100+ application files.
// ─────────────────────────────────────────────────────────────────────────────

class PgStatement {
    private PDOStatement $stmt;
    private PDO $pdo;
    private array $boundValues = [];
    private array $resultRow   = [];
    private array $resultRows  = [];
    private int   $resultIndex = 0;
    private ?string $types     = null;
    private array   $refs      = [];

    public int $num_rows = 0;

    public function __construct(PDOStatement $stmt, PDO $pdo) {
        $this->stmt = $stmt;
        $this->pdo  = $pdo;
    }

    // Convert ?-placeholders to $1,$2,... for PostgreSQL
    public static function convertPlaceholders(string $sql): string {
        $i = 0;
        // Replace '?' not inside quotes with $1, $2, ...
        $result = '';
        $inSingle = false;
        $inDouble = false;
        $len = strlen($sql);
        for ($c = 0; $c < $len; $c++) {
            $ch = $sql[$c];
            if ($ch === "'" && !$inDouble) { $inSingle = !$inSingle; $result .= $ch; }
            elseif ($ch === '"' && !$inSingle) { $inDouble = !$inDouble; $result .= $ch; }
            elseif ($ch === '?' && !$inSingle && !$inDouble) { $result .= '$' . (++$i); }
            else { $result .= $ch; }
        }
        return $result;
    }

    // bind_param('ssi', $a, $b, $c) – capture references
    public function bind_param(string $types, &...$vars): void {
        $this->types = $types;
        $this->refs  = [];
        foreach ($vars as &$v) {
            $this->refs[] = &$v;
        }
    }

    // bind_result($a, $b, ...) – capture references for column output
    public function bind_result(&...$vars): void {
        $this->boundValues = [];
        foreach ($vars as &$v) {
            $this->boundValues[] = &$v;
        }
    }

    private array $resultRowsAssoc = [];

    public function execute(): bool {
        $params = [];
        foreach ($this->refs as &$r) {
            $params[] = $r;
        }
        $this->stmt->execute($params ?: null);
        // Fetch both numeric (for bind_result) and assoc (for get_result)
        $this->resultRowsAssoc = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->num_rows        = count($this->resultRowsAssoc);
        // Build numeric rows from assoc rows
        $this->resultRows  = array_map('array_values', $this->resultRowsAssoc);
        $this->resultIndex = 0;
        return true;
    }

    public function fetch(): bool|null {
        if ($this->resultIndex >= count($this->resultRows)) {
            return false;
        }
        $row = $this->resultRows[$this->resultIndex++];
        foreach ($this->boundValues as $i => &$ref) {
            $ref = $row[$i] ?? null;
        }
        return true;
    }

    public function close(): void {
        $this->stmt->closeCursor();
        $this->resultRows  = [];
        $this->resultIndex = 0;
        $this->refs        = [];
        $this->boundValues = [];
    }

    // Returns a result-set object compatible with $result->num_rows / fetch_object() / fetch_assoc()
    public function get_result(): PgResult {
        return new PgResult($this->resultRowsAssoc, $this->num_rows);
    }

    // Alias
    public function store_result(): void {}
    public function free_result(): void {}
}

class PgResult {
    private array $rows;
    private int   $index = 0;
    public  int   $num_rows;

    public function __construct(array $rows, int $count) {
        $this->rows     = $rows;
        $this->num_rows = $count;
    }

    public function fetch_assoc(): array|null {
        // rows fetched with FETCH_NUM; re-fetch with keys from stmt
        return $this->rows[$this->index++] ?? null;
    }

    public function fetch_object(): object|null {
        $row = $this->rows[$this->index++] ?? null;
        return $row ? (object) $row : null;
    }

    public function fetch_array(int $mode = MYSQLI_BOTH): array|null {
        return $this->rows[$this->index++] ?? null;
    }

    public function free(): void {}
}

class PgMysqliShim {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // $mysqli->prepare("SELECT ...") – converts ? to $1,$2,... for PostgreSQL
    public function prepare(string $sql): PgStatement {
        // Also convert backtick-quoted identifiers to double-quoted (PostgreSQL style)
        $sql = PgStatement::convertPlaceholders($this->normalizeSql($sql));
        $stmt = $this->pdo->prepare($sql);
        return new PgStatement($stmt, $this->pdo);
    }

    // mysqli_query($mysqli, $sql) support via magic method – also used for
    // legacy procedural calls that reach here as $mysqli->query()
    public function query(string $sql): PgResult|bool {
        $sql = $this->normalizeSql($sql);
        try {
            $stmt = $this->pdo->query($sql, PDO::FETCH_ASSOC);
            $rows = $stmt ? $stmt->fetchAll() : [];
            return new PgResult($rows, count($rows));
        } catch (PDOException $e) {
            error_log('DB query error: ' . $e->getMessage());
            return false;
        }
    }

    // Convert MySQL-isms to PostgreSQL
    private function normalizeSql(string $sql): string {
        // Strip backtick quoting – replace `identifier` with "identifier"
        $sql = preg_replace('/`([^`]+)`/', '"$1"', $sql);
        // Remove ENGINE=, CHARSET=, AUTO_INCREMENT= clauses (DDL compat)
        $sql = preg_replace('/\s+ENGINE\s*=\s*\w+/i', '', $sql);
        $sql = preg_replace('/\s+DEFAULT\s+CHARSET\s*=\s*\w+/i', '', $sql);
        return $sql;
    }

    // Real-escape-string becomes a no-op when using prepared statements,
    // but is kept for legacy code paths that build queries manually.
    public function real_escape_string(string $s): string {
        // PDO quote without surrounding quotes
        return substr($this->pdo->quote($s), 1, -1);
    }

    // Expose insert_id via LASTVAL()
    public function __get(string $name): mixed {
        if ($name === 'insert_id') {
            try {
                return (int) $this->pdo->lastInsertId();
            } catch (PDOException $e) {
                return 0;
            }
        }
        return null;
    }
}

// Global $mysqli shim (used throughout the codebase)
$mysqli = new PgMysqliShim($pdo);

// Global procedural function wrappers for the few files that use
// mysqli_query() / mysqli_num_rows() / mysqli_fetch_assoc() etc.
if (!function_exists('mysqli_query')) {
    function mysqli_query($link, string $sql): PgResult|bool {
        return $link->query($sql);
    }
    function mysqli_num_rows(PgResult $result): int {
        return $result->num_rows;
    }
    function mysqli_fetch_assoc(PgResult $result): array|null {
        return $result->fetch_assoc();
    }
    function mysqli_fetch_object(PgResult $result): object|null {
        return $result->fetch_object();
    }
    function mysqli_fetch_array(PgResult $result, int $mode = MYSQLI_BOTH): array|null {
        return $result->fetch_array($mode);
    }
    function mysqli_real_escape_string($link, string $s): string {
        return $link->real_escape_string($s);
    }
    function mysqli_affected_rows($link): int { return 0; }
    function mysqli_free_result(PgResult $result): void { $result->free(); }
}
