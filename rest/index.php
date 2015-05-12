<?php

// http://docs.slimframework.com/

require 'Slim/Slim.php';
require 'dao/PerguntaDAO.php';
require 'dao/DesenvolvedorDAO.php';
require 'dao/EnqueteDAO.php';
require 'dao/EnqueteItemDAO.php';
require 'dao/ExpertiseDAO.php';

$app = new Slim();
$dao = new DesenvolvedorDAO();
$perguntaDAO = new PerguntaDAO();
$enqueteDAO = new EnqueteDAO();
$enqueteItemDAO = new EnqueteItemDAO();
$expertiseDAO = new ExpertiseDAO();
 

// DESENVOLVEDOR
$app->get('/dev', function() use($dao) {
  $dao->getAll();
});

$app->get('/dev/:id', function($id) use($dao) {
  $dao->getById($id);
});

$app->get('/dev/search/:name', function($name) use($dao) {
  $dao->getByName($name);
});
//--
$app->delete('/dev/:id', function($id) use($dao) {
  $dao->delete($id);
});

$app->post('/dev', function() use($dao, $app) {
  //$request = Slim::getInstance()->request();
  $request = $app->request();
  $body = $request->getBody();
  $vo = json_decode($body);
  $dao->insert($vo);
});

$app->put('/dev/:id', function($id) use($dao, $app) {
  $request = $app->request();
  $body = $request->getBody();
  $vo = json_decode($body);
  $vo->id = $id;
  $dao->update($vo);
});


$app->get('/dev/senha/:vo', function($vo) use($dao, $app) {
    
  //var_dump($vo);
  $vo = json_decode($vo);
  $dao->updateSenha($vo);
});



//TIME DE DESENVOLVEDORES
$app->get('/time/:nome', function($nome) use($dao) {
  $dao->getByTime($nome);
});




// PERGUNTA
$app->get('/per', function() use($perguntaDAO) {
  $perguntaDAO->getAll();
});

$app->get('/per/:id', function($id) use($perguntaDAO) {
  $perguntaDAO->getById($id);
});

$app->get('/per/search/:name', function($name) use($perguntaDAO) {
  $perguntaDAO->getByName($name);
});
//--
$app->delete('/per/:id', function($id) use($perguntaDAO) {
  $perguntaDAO->delete($id);
});

$app->post('/per', function() use($perguntaDAO, $app) {
  //$request = Slim::getInstance()->request();
  $request = $app->request();
  $body = $request->getBody();
  $vo = json_decode($body);
  $perguntaDAO->insert($vo);
});

$app->put('/per/:id', function($id) use($perguntaDAO, $app) {
  $request = $app->request();
  $body = $request->getBody();
  $vo = json_decode($body);
  $vo->id = $id;
  $perguntaDAO->update($vo);
});


// ENQUETE
$app->get('/enq', function() use($enqueteDAO) {
  $enqueteDAO->getAll();
});

$app->get('/enq/:id', function($id) use($enqueteDAO) {
  $enqueteDAO->getById($id);
});

$app->get('/enq/search/:name', function($name) use($enqueteDAO) {
  $enqueteDAO->getByName($name);
});
//--
$app->delete('/enq/:id', function($id) use($enqueteDAO) {
  $enqueteDAO->delete($id);
});

$app->post('/enq', function() use($enqueteDAO, $app) {
  //$request = Slim::getInstance()->request();
  $request = $app->request();
  $body = $request->getBody();
  $vo = json_decode($body);
  $enqueteDAO->insert($vo);
});

$app->put('/enq/:id', function($id) use($enqueteDAO, $app) {
  $request = $app->request();
  $body = $request->getBody();
  $vo = json_decode($body);
  $vo->id = $id;
  $enqueteDAO->update($vo);
});


//GRAFICO
$app->get('/item/grafico/:time', function($vo) use($enqueteItemDAO) {
  $enqueteItemDAO->getGraficoExpertise($vo);
});

$app->get('/grafico', function() use($enqueteItemDAO) {
  $enqueteItemDAO->getGraficoPercentual();
});



// ENQUETE ITEM
$app->get('/item/merge/:vo', function($vo) use($enqueteItemDAO) {
  $enqueteItemDAO->merge($vo);
});

$app->get('/item/bydev/:vo', function($vo) use($enqueteItemDAO) {
  $enqueteItemDAO->getByDesenv($vo);
});

$app->get('/item/count/:vo', function($vo) use($enqueteItemDAO) {
  $enqueteItemDAO->getCount($vo);
});


$app->get('/item/:id', function($id) use($enqueteItemDAO) {
  $enqueteItemDAO->getAll($id);
});

$app->get('/item/i/:id', function($id) use($enqueteItemDAO) {
  $enqueteItemDAO->getById($id);
});

$app->get('/item/search/:name', function($name) use($enqueteItemDAO) {
  $enqueteItemDAO->getByName($name);
});
//--
$app->delete('/item/:id', function($id) use($enqueteItemDAO) {
  $enqueteItemDAO->delete($id);
});

$app->post('/item', function() use($enqueteItemDAO, $app) {
  //$request = Slim::getInstance()->request();
  $request = $app->request();
  $body = $request->getBody();
  $vo = json_decode($body);
  $enqueteItemDAO->insert($vo);
});

$app->put('/item/:id', function($id) use($enqueteItemDAO, $app) {
  $request = $app->request();
  $body = $request->getBody();
  $vo = json_decode($body);
  $vo->id = $id;
  $enqueteItemDAO->update($vo);
});


// PERGUNTA
$app->get('/exp', function() use($expertiseDAO) {
  $expertiseDAO->getAll();
});



$app->run();

?>