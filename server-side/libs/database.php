<?php

class database
{
    private string $db_host;
    private string $db_name;
    private string $db_user;
    private string $db_pass;

    public static $con = null;

    public function __construct($db_host, $db_name, $db_user, $db_pass)
    {
        $this->db_host = $db_host;
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
    }

    public function get_connection()
    {
        if (self::$con === null) {
            $dsn = "mysql:host={$this->db_host};dbname={$this->db_name};charset=utf8mb4";
            try {
                self::$con = new PDO($dsn, $this->db_user, $this->db_pass);
                self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new Exception("Erro de conexão: " . $e->getMessage());
            }
        }
        return self::$con;
    }

    public function query(string $sql, array $params = [])
    {
         try {
            $db = $this->get_connection();
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro na consulta: " . $e->getMessage());
        }
    }

    public function non_query(string $sql, array $params = [])
    {
        try {
            $db = $this->get_connection();
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Erro na execução: " . $e->getMessage());
        }
    }
}
