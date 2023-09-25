<?php
require 'init.php';
require 'BasicTableGateway.php';
require 'UserGateway.php';

/****** SETUP ******/
$sql = "create temporary table gw_users (
    id int primary key auto_increment, 
    email varchar(255),
    password varchar(255),
    name varchar(255)
)";
$pdo->query($sql);

$data = [
    'email' => 'foo@bar.com',
    'password' => password_hash('Curious Elk * 38', PASSWORD_DEFAULT),
    'name' => 'Fooster',
];

/****** EXAMPLE BEGINS ******/

$userGW = new UserGateway($pdo);

$id = $userGW->create($data);
echo "Create: $id",PHP_EOL;

$user = $userGW->read($id);
echo "Read: ". json_encode($user),PHP_EOL;

$userGW->update(['name' => 'Wooster'], $id);
$user = $userGW->read($id);
echo "Update:".json_encode($user),PHP_EOL;

$users = $userGW->listBySql("SELECT * from gw_users");
echo "List: ". json_encode($users),PHP_EOL;

$user = $userGW->getByField("email", $data['email']);
echo "Find by column: ".json_encode($user),PHP_EOL;

$userGW->delete($id);
$user = $userGW->read($id);
echo "Delete: ".json_encode($user),PHP_EOL;

// attempt to use a non-existent column;
$user = $userGW->getByField("; drop table users", $data['email']);

