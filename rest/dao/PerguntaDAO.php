<?php
class PerguntaDAO 
{
  
  private $_select = "SELECT * FROM tab_pergunta ";
  private $_insert = "INSERT INTO tab_pergunta( nome ) VALUES( :nome  )";
  private $_update = "UPDATE tab_pergunta SET nome = :nome  WHERE id = :id";
  private $_delete = "DELETE FROM tab_pergunta WHERE id = :id";
  
  private function getDBConn() 
  {
    //$dbhost="172.27.10.246";
    $dbhost="localhost";
    $dbuser='root';
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
      echo json_encode($result); 
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
  }

  public function getById($id) 
  {
    $sql = $this->_select . " WHERE id = :id ";

    //var_dump( $sql);
    
    try {
      $db = $this->getDBConn();
      $stmt = $db->prepare($sql);  
      $stmt->bindParam("id", $id );
      $stmt->execute();
      $result =$stmt->fetchAll(PDO::FETCH_OBJ); 
      $db = null;
      echo '{"records":'.json_encode($result).'}'; 
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
      $stmt->bindParam("id", $vo->id);
      $stmt->execute();
      $db = null;
      echo json_encode($vo); 
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    } 
  }

  public function delete($id) 
  {
    try {
      $db = $this->getDBConn();
      $stmt = $db->prepare($this->_delete);  
      $stmt->bindParam("id", $id);
      $stmt->execute();
      $db = null;
      echo 'ok';
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
  }

}
