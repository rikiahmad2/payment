<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require 'conn.php';


$app = AppFactory::create();
function connect(){
    $con = new dash('localhost', 'root', '', 'supportsystem');
    return $con;
}

$countries[0] = array(
    'no_order' => '77777878',
    'nama' => 'Ryan Ananda',
    'no_hp' => '08992733213',
    'vac' => '777777777',
    'total_bayar' => '1500000'
);

$countries[1] = array(
    'no_order' => '099988123',
    'nama' => 'james rahadi',
    'no_hp' => '098899233',
    'vac' => '888778298373',
    'total_bayar' => '200000000'
);


$app->get('/countries', function (Request $request, Response $response, $args) use ($countries) {

    $result = $countries;

    $isi = array('status' => true, "message" => "Valid request", "data" => $result);
    $body = json_encode($isi);
    $response->getBody()->write($body);
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

//Get all data prospects
$app->get('/prospects/', function (Request $request, Response $response, array $args) {
    $con = connect();
    $query = $con->query("SELECT * FROM prospect");
    $result = array();
    while($hasil = @mysqli_fetch_assoc($query)){
        $result[] = $hasil;
    }

        $isi = array('status' => true, "message" => "Valid request", "data" => $result );
        $body = json_encode($isi);
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

//Get data detail 1 prospect
$app->get('/prospects/{id}', function (Request $request, Response $response, array $args) {
	
    $id = $request->getAttribute('id');

    $con = connect();
    $query = $con->query("SELECT * FROM prospect where id = '".$id."'");
    $result = array();
    while($hasil = @mysqli_fetch_assoc($query)){
        $result[] = $hasil;
    }

        $isi = array('status' => true, "message" => "Valid request", "data" => $result );
        $body = json_encode($isi);
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

//Fungsi handle penambahan data prospect (FORM 1)
$app->post('/prospect/', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    if(isset($data["id"]) && isset($data["name"]) && isset($data["email"]) && isset($data["telp"]) && isset($data["kota"])){
        $con = connect();
        $checkUser = @mysqli_num_rows($con->query("SELECT * FROM user WHERE id_user = '".$_POST["id"]."'"));

        if($checkUser == 0){
            $isi = array('status' => false, "message" => "Invalid id user", "data" => null );
            $body = json_encode($isi);
            $response->getBody()->write($body);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $con->query("INSERT INTO prospect(id_user,id_content,nama,email,telp,kota,status) VALUES ('".$_POST["id"]."','".$_POST["id_content"]."','".$_POST["name"]."', '".$_POST["email"]."', '".$_POST["telp"]."', '".$_POST["kota"]."','Pending')");

        //Message Response
        $isi = array('status' => true, "message" => "Success", "data" => null );
        $body = json_encode($isi);
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }else{

        //Message Response
        $isi = array('status' => false, "message" => "Invalid request", "data" => null );
        $body = json_encode($isi);
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

$app->setBasePath("/slim2/public");

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->run();