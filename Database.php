<?php
    // set max_execution_time to unlimit
    ini_set('max_execution_time', 0);

    // set memory_limit to unlimit
    ini_set('memory_limit', '-1');

    class Database
    {
        protected $db;
        protected $message;

        public function __construct()
        {
            // $this->db= $config;
            // $this->check_db_variable();
        }

        protected function check_db_variable()
        {
            // $this->message .= empty($this->db['host']) ? 'host undefined ' : false ;
            // $this->message .= empty($this->db['user']) ? ', user undefined ' : false ;
            // $this->message .= empty($this->db['pass']) ? null : false ;
            // $this->message .= empty($this->db['dbname']) ? ', database name undefined ' : false ;
            // $this->message .= empty($this->db['sqldump']) ? ', file .sql undefined ' : false ;
            // if (empty($this->message)) {
            //     $this->db['port'] = empty($this->db['port']) ? 3306 : $this->db['port'] ;
            //     $this->conn = @new mysqli($this->db['host'], $this->db['user'], $this->db['pass'], $this->db['dbname'], $this->db['port']);
            //     // Check connection
            //     if ($this->conn->connect_errno) {
            //         echo "Failed to connect to MySQL: " . $this->conn->connect_errno;
            //         echo "<br/>Error: " . $this->conn->connect_error;
            //     }
            // }
            // print_r($this->db);
        }
        protected function drop_table_from_db()
        {
            $this->conn->query('SET foreign_key_checks = 0');
            if ($result = $this->conn->query("SHOW TABLES")) {
                while ($row = $result->fetch_array(MYSQLI_NUM)) {
                    $this->conn->query('DROP TABLE IF EXISTS '.$row[0]);
                }
            }

            $this->conn->query('SET foreign_key_checks = 1');
            // $this->conn->close();
            return true;
        }
        public function imports()
        {
            $tes;
            $this->drop_table_from_db();
            // Connect to MySQL server
            
            // Temporary variable, used to store current query
            $templine = '';
            // Read in entire file
            $lines = file($this->db['sqldump']);
            // Loop through each line
            foreach ($lines as $line) {
                // Skip it if it's a comment
                if (substr($line, 0, 2) == '--' || $line == '') {
                    continue;
                }

                // Add this line to the current segment
                $templine .= $line;
                // If it has a semicolon at the end, it's the end of the query
                if (substr(trim($line), -1, 1) == ';') {
                    // Perform the query
                    $tes= $this->conn->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . $this->conn->error() . '<br /><br />');
                    // Reset temp variable to empty
                    $templine = '';
                }
            }
            // echo "Tables imported successfully";
            // $this->conn->close($this->conn);
            return true;
        }

        public function createDatabase()
        {
            $servername = $this->db['host'];
            $port       = $this->db['port'];
            $dbname     = $this->db['dbname'];
            $username   = $this->db['user'];
            $password   = $this->db['pass'];

            try {
                $conn = new PDO("mysql:host={$servername};port={$port}", $username, $password);

                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "CREATE DATABASE IF NOT EXISTS {$dbname}";

                // use exec() because no results are returned
                $conn->exec($sql);
                echo "Database created successfully<br>";
            } catch (PDOException $e) {
                echo $sql . "<br>" . $e->getMessage();
                die();
            }
        }
    }

    // config import
    /* $import = new Database([
        'host'=> 'localhost',
        'user'=> 'root',
        'pass'=> '',
        'dbname'=> 'restore_ecotranstye',
        'port'=> '3307', // default 3306
        'sqldump'=> 'sql_dump/restore_2021.sql',
    ]);

    $import->createDatabase();

    if ($import->imports()) {
        echo "Database berhasil diimport";
    } else {
        echo "Database gagal diimport";
    } */
    $db = new Database();

    echo '<pre>';
    print_r($db);
    echo '</pre>';
