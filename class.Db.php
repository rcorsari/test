<?php 

// https://fullstackwebstudio.com/locations/coding-blog/simple-database-connection-class-with-pdo/
// https://phpdelusions.net/pdo_examples/connect_to_mysql

class db 
{
    private $host;
    private $user;
    private $pw;
    private $database;
    private $charset;
    private $port;


    function __construct($host, $user, $pw, $database, $charset = 'utf8mb4', $port = '3306')
    {
        $this->host = $host;
        $this->user = $user;
        $this->pw = $pw;
        $this->database = $database;
        $this->charset = $charset;
        $this->port = $port;
    }


    public function connect()
    {
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $dsn = "mysql:host={$this->host};dbname={$this->database};charset={$this->charset};port={$this->port}";
        
        try {

            $pdo = new \PDO($dsn, $this->user, $this->pw, $options);

        } catch (\PDOException $e) {
            
            throw new \PDOException($e->getMessage(), (int)$e->getCode());

        }

        return $pdo;
    }

}