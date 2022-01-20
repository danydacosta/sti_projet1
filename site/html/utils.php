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
        $sth = $file_db->prepare('SELECT admin FROM user WHERE login = ?');
        $sth->execute(array($login));
        $userAdmin = $sth->fetch();

        return $userAdmin['admin'] == 1;    // true si le user est admin
    }

    function checkValid($login) {
        $file_db = dbConnection();
        $sth = $file_db->prepare('SELECT validite FROM user WHERE login = ?');
        $sth->execute(array($login));
        $userValid = $sth->fetch();
        
        return $userValid['validite'] == 1;    // true si le user est valide
    }

    function passwordPolicy($password){
        $policy = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/';
        $policy2 =  '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/';
        if(!preg_match($policy,$password)){
            return false;
        }
        return true;
    }
?>