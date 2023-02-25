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
    'password' => 123,
    'name' => 'Fooster',
];

/****** EXAMPLE BEGINS ******/

$userGateway = new UserGateway($pdo);

$id = $userGateway->create($data);
echo "Create: $id",PHP_EOL;

$user = $userGateway->read($id);
echo "Read: ". json_encode($user),PHP_EOL;

$userGateway->update(['name' => 'Wooster'], $id);
$user = $userGateway->read($id);
echo "Update:".json_encode($user),PHP_EOL;

$users = $userGateway->listBySql("SELECT * from gw_users");
echo "List: ". json_encode($users),PHP_EOL;

$user = $userGateway->getByField("email", $data['email']);
echo "Find by column: ".json_encode($user),PHP_EOL;

$userGateway->delete($id);
$user = $userGateway->read($id);
echo "Delete: ".json_encode($user),PHP_EOL;

// attempt to use a non-existent column;
$user = $userGateway->getByField("; drop table users", $data['email']);

