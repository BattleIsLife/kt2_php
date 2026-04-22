<?php
class Database
{
    private $conn;
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASS;
    private $database = DB_NAME;

    public function __construct() {
        $this->connect();
    }

    public function connect()
    {
        $this->conn=null;
        try {
            $dsn = "mysql:host=$this->host; dbname=$this->database; charset=utf8";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $this->conn = new PDO($dsn, $this->user, $this->password, $options);

            // echo "Kết nối thành công";
        } catch (PDOException $th) {
            echo "Kết nối thất bại: $th";
        }
    }

    public function query($sql, $params= [])
    {
        $this->conn->beginTransaction();

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($params);

        $this->conn->commit();

        return $stmt;
    }

    public function close()
    {
        try {
            $this->conn = null;
        } catch (PDOException $th) {
            echo $th;
        }
    }
}
