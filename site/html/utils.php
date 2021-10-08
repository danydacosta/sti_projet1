<?php
    include 'dbConnect.php';

    function dbConnection() {
        try {
            /**************************************
             * Create databases and                *
             * open connections                    *
             **************************************/

            // Create (connect to) SQLite database in file
            $file_db = new PDO('sqlite:../databases/database.sqlite');
            // Set errormode to exceptions
            $file_db->setAttribute(PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION);
            return $file_db;

        } catch (PDOException $e) {
            // Print PDOException message
            echo $e->getMessage();
        }
    }

    function checkAdmin($login) {
        $file_db = dbConnection();
        $userAdmin = $file_db->query("SELECT admin FROM user WHERE login = '{$login}'")->fetch();
        return $userAdmin['admin'] == 1;    // true si le user est admin
    }

?>