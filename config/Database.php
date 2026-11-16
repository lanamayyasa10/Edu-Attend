<?php

class Database
{
    private mysqli $connection;

    public function __construct()
    {
        $host = getenv('DB_HOST') ?: 'localhost';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
        $name = getenv('DB_NAME') ?: 'db_absensi';
        $port = (int)(getenv('DB_PORT') ?: 3306);

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->connection = new mysqli($host, $user, $pass, $name, $port);
            $this->connection->set_charset('utf8mb4');
        } catch (mysqli_sql_exception $error) {
            http_response_code(500);
            die(
                'Koneksi database gagal. Periksa pengaturan di config/Database.php ' .
                'dan pastikan database "' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') .
                '" sudah dibuat. Detail: ' .
                htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8')
            );
        }
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}
