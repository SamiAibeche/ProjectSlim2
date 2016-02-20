<?php

/*
Before all : Create  a .htacess file -> 
	-> In the same folder of index.php
	-> On slim/docs.com
	-> Click on "web serveur" -> apache config
*/

//Twig - Slim / Include & Load
require 'vendor/autoload.php';

//Slim
$app = new \Slim\App;

//Twig
Twig_Autoloader::register();

//Red Beans
require "rb.php";


//Initializing SQL connection
R::setup( 'mysql:host=localhost;dbname=wine','root', 'root' );

$loader = new Twig_Loader_Filesystem('templates');

//Creating and configutation the template engine
$twig = new Twig_Environment($loader,[
  'cache' => false,           //Donner le chemin du dossier pour activer le cache
  'debug' => true,
  'charset' => 'utf-8',       //Default value
  'auto_reload' => true,      //Regénère le template si la source a été modifiée
  'strict_variables' => true, //Génère une exception si une variable du template n'est pas déclarée
  'autoescape' => true        //Default value
]);

//Routes Access
require 'src/routes.php';
   

$app->run();