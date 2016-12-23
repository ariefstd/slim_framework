<?php
require 'vendor/autoload.php';
require 'libs/NotORM.php'; 
//membuat dan mengkonfigurasi slim app
$app = new \Slim\app;

// konfigurasi database
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'db_slim';
$dbmethod = 'mysql:dbname=';

$dsn = $dbmethod.$dbname;
$pdo = new PDO($dsn, $dbuser, $dbpass);
$db  = new NotORM($pdo);

//mendefinisikan route app di home
$app-> get('/', function()use($app, $db){
    echo "Hello World by slipknot <br />";
	$produk = $db->products();
		foreach($produk as $dt){
			  $item[] = array(
			  "no serial"=>$dt["serial"],
			   "nama item"=>$dt["name"],
			   "keterangan item"=>$dt["description"],
			   "harga item"=>$dt["price"]
			   //"tanggal"=>$timenya
			);   
		}
	 $json = array(
		'Status' => 'Successfully to create data',
		'weleh' => array('item' => $item)
	   );	

	$datanya = json_encode($json);
	$input_bagus = preg_replace('/"([a-zA-Z_]+[a-zA-Z0-9_]*)":/','$1:',$datanya);
	echo $input_bagus; 
});

//run App
 
$app ->get('/produk/{id}', function($request, $response, $args) use($app, $db){
    $produk = $db->products()->where('serial',$args['id']);
    if($data = $produk->fetch()){
        echo json_encode(array(
            'serial' => $data['serial'],
            'name' => $data['name'],
            'price' => $data['price'],
            'picture' => $data['picture']
            ));
    }
    else{
        echo json_encode(array(
            'price' => '350',
            'message' => "ID produk tidak ada"
            ));
    }
});

$app->run();