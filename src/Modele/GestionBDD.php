<?php

/*
Class Gestion BDD dans GestionBDD.php
 */

class GestionBDD
{

    private string $user;
    private string $pass;
    private string $dsn;
    private string $db;

    private PDO $cnx;

    function __construct(string $db = 'DB_Ligue1', string $user = 'postgres', string $pass = 'P@ssw0rdsio')
    {
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;
        $this->dsn = 'pgsql:host=localhost;dbname=' . $db . ';';
        /* $this->dsn = 'pgsql:host=192.168.30.110;port=9876;dbname=' . $db . ';'; */
        /*46.18.320.10*/
    }

    /**
     * 
     * @return PDO
     */
    function connect(): PDO
    {

        try {
            $this->cnx = new PDO($this->dsn, $this->user, $this->pass);
            return $this->cnx;
        } catch (PDOException $e) {
            echo "Erreur de connexion à la base !: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}
