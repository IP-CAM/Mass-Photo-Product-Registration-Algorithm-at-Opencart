<?php

namespace models;

class Banco
{ 
    private $_login;
    private $_senha;
    // private $_query;
    private $_base;
    private $mysqli;
    
    function __construct() 
    {
    	$this->_login = "intob312_opentes";
		$this->_senha = "senha@opencart";
		$this->_base = "intob312_openteste";

        $this->mysqli = new \MySQLi('localhost', $this->_login, $this->_senha, $this->_base);
    } 
    public function select($query)  //recebe uma query
    {
        $result = $this->mysqli->query($query);  //faz a pesquisa no banco e joga resultado em $result
        
        $countResult = $result->num_rows;
        for($i=0;$i<$countResult;$i++)
        {
            $resultado = $result->fetch_assoc();
            $resultados[] = $resultado;           
        }
        return $resultados;
    }
    public function insert($query)  //recebe uma query
    {
        $this->mysqli->query($query);  //faz a pesquisa no banco e joga resultado em $result
        return $this->mysqli->insert_id; // retornar o id do ultimo insert
    }   
}
?>
