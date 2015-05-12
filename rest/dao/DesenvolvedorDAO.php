<?php
class DesenvolvedorDAO 
{
  private $_select = "select * from tab_desenv";
  //private $_select = "SELECT nome name, 'svg-2' avatar, 'Desenvolvedor' content, time FROM tab_desenv ";
  private $_insert = "INSERT INTO tab_desenv( nome, time ) VALUES( :nome, :time )";
  private $_update = "UPDATE tab_desenv SET nome = :nome, time= :time WHERE nome = :nome";
  private $_update_senha = "UPDATE tab_desenv SET senha = :senha WHERE nome = :nome";
  private $_delete = "DELETE FROM tab_desenv WHERE nome = :nome";
  
  private function getDBConn() 
  {
    //$dbhost="172.27.10.246";
    $dbhost="localhost";
    $dbuser="root";
    $dbpass="1234";
    $dbname="enquete_dev";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);  
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
  }

  public function getAll() 
  {
    $sql = $this->_select . " ORDER BY nome";
    
    try {
      $db = $this->getDBConn();
      $stmt = $db->query($sql);  
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
      $db = null;
      //echo json_encode($result);
      echo '{"records":'.json_encode($result).'}';
      
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
  }

  public function getByName($name) 
  {
    $sql = $this->_select . " WHERE nome LIKE UPPER(:nome) ORDER BY nome";

    try {
      $db = $this->getDBConn();
      $stmt = $db->prepare($sql);
      $name = "%".$name."%";  
      $stmt->bindParam("nome", $name);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
      $db = null;
      echo  '{"records":'.json_encode($result).'}'; 
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
  }
  
  
  public function getByTime($nome) 
  {
    $sql = $this->_select . " WHERE time = :nome ORDER BY nome";

    try {
      $db = $this->getDBConn();
      $stmt = $db->prepare($sql);  
      $stmt->bindParam("nome", $nome);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_OBJ); 
      $db = null;
      echo  '{"records":'.json_encode($result).'}'; 
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
  }

  public function insert($vo) 
  {
    
    try {
      $db = $this->getDBConn();
      $stmt = $db->prepare($this->_insert);  
      $stmt->bindParam("nome", $vo->nome);
      $stmt->bindParam("time", $vo->time);
      $stmt->execute();
      $db = null;
      echo json_encode($vo); 
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
  }

  public function update($vo) 
  {
    try {
      $db = $this->getDBConn();
      $stmt = $db->prepare($this->_update);  
      $stmt->bindParam("nome", $vo->nome);
      $stmt->bindParam("time", $vo->time);
      $stmt->execute();
      $db = null;
      echo json_encode($vo); 
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    } 
  }

  
  public function updateSenha($vo) 
  {
      
    try {
      $db = $this->getDBConn();
      $stmt = $db->prepare($this->_update_senha);  
      $stmt->bindParam("nome", $vo->nome);
      $stmt->bindParam("senha", $vo->senha);
      $stmt->execute();
      $db = null;
      echo json_encode($vo); 
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    } 
  }
  
  
  public function delete($nome) 
  {
    try {
      $db = $this->getDBConn();
      $stmt = $db->prepare($this->_delete);  
      $stmt->bindParam("nome", $nome);
      $stmt->execute();
      $db = null;
      echo 'ok';
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
  }

}
