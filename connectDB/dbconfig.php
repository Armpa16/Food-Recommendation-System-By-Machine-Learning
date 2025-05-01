<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$host = "localhost";
$user = "root";
$password = "";
$database = "food_recommend_system";

$link= new mysqli($host,$user,$password,$database);
$conn=$link; 
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
//$link->query("set names utf8");