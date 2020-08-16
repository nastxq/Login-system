<?php
$servername="localhost";
$login="root";
$password="";
$dbname="gamesdb";

$konekt=new mysqli($servername,$login,$password,$dbname);

if(!$konekt){
die("Connection Failed".mysqli_connect_error());
}
