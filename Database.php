<?php
    class Database{
        //Add your connection
        private $host = "";
        private $dbname = "";
        private $username = "";
        private $password = "";

        private $charset = "utf8mb4";
        private $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);

        protected $conn;
        
        public function openConnection(){

            try{
                $dsn = "mysql:host=".$this->host.";dbname=".$this->dbname.";charset=".$this->charset;
                $this->conn = new PDO($dsn, $this->username, $this->password, $this->options);

                return $this->conn;

            }catch(PDOException $e){
                echo "Connection failed: ".$e->getMessage();
            }
        }

        public function closeConnection(){
            $this->conn = null;
        }   
    }  
?>
