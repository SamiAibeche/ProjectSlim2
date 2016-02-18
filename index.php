<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

    /*
    Before all : Create  a .htacess file -> 
    	-> In the same folder of index.php
    	-> On slim/docs.com
    	-> Click on "web serveur" -> apache config
    */

//Twig - Include & Load
require 'vendor/autoload.php';
Twig_Autoloader::register();

//Red Beans
require "rb.php";

$app = new \Slim\App;

//Initializing SQL connection
R::setup( 'mysql:host=localhost;dbname=wine','root', 'root' );


    /** Function GetAllWine  - GET **/

$app->get('/', function (Request $request, Response $response) {

    //FindAll method from redBean (return an object with all wines )
    $wines = R::findAll( 'wine' );
    try{

      //ExportAll method (transform the object on an array)
      $arrays = R::exportAll( $wines );

      //Return data
      if(!empty($arrays)){

       /*
        * Creating a  template loader
        * (with the path to the folder containing templates)
        */ 
       $loader = new Twig_Loader_Filesystem('templates');

       //Creating and configutation the template engine
       $twig = new Twig_Environment($loader);

       //Show the page (from Twig and its template loader)
      //var_dump($arrays);
      echo $twig->render('home.twig',array('datas' => $arrays));

      } else {
          echo "unvalid";
      }

    //IF 404 - 400 
    } catch (ResourceNotFoundException $e) {

      /*
       * Création d'un chargeur de template
       * (avec chemin vers le dossier contenant les templates)
       */ 
      $loader = new Twig_Loader_Filesystem('templates');

      //Création et configuration du moteur de template Twig
      $twig = new Twig_Environment($loader,[
        'cache' => false,           //Donner le chemin du dossier pour activer le cache
        'debug' => true,
        'charset' => 'utf-8',       //Default value
        'auto_reload' => true,      //Regénère le template si la source a été modifiée
        'strict_variables' => true, //Génère une exception si une variable du template n'est pas déclarée
        'autoescape' => true        //Default value
      ]);

      //Rendu de la page (à partir de Twig et de son chargeur de template)
      echo $twig->render('error.twig',array('src' => '404'));

    } catch (Exception $e) {

      /*
       * Création d'un chargeur de template
       * (avec chemin vers le dossier contenant les templates)
       */ 
      $loader = new Twig_Loader_Filesystem('templates');

      //Création et configuration du moteur de template Twig
      $twig = new Twig_Environment($loader,[
        'cache' => false,           //Donner le chemin du dossier pour activer le cache
        'debug' => true,
        'charset' => 'utf-8',       //Default value
        'auto_reload' => true,      //Regénère le template si la source a été modifiée
        'strict_variables' => true, //Génère une exception si une variable du template n'est pas déclarée
        'autoescape' => true        //Default value
      ]);

      //Rendu de la page (à partir de Twig et de son chargeur de template)
      echo $twig->render('error.twig',array('src' => '400'));
    }
});

    
      /** Function OrderBy - GET **/

$app->get('/orderby/{order}', function (Request $request, Response $response) {
    
    try{

        //Recup - Initializing data 
        $order = $request->getAttribute('order');

        //FindAll method from redBean (return an object with all wines order by $order)
        $wines = R::findAll( 'wine' , ' ORDER BY '.$order.'' );

        //ExportAll method (transform the object on an array)
        $arrays = R::exportAll( $wines );

       //Return data
      if(!empty($arrays)){

       /*
        * Creating a  template loader
        * (with the path to the folder containing templates)
        */ 
       $loader = new Twig_Loader_Filesystem('templates');

       //Creating and configutation the template engine
       $twig = new Twig_Environment($loader);

       //Show the page (from Twig and its template loader)
      //var_dump($arrays);
      echo $twig->render('order.twig',array('datas' => $arrays));

      } else {
          echo "unvalid";
      }

    //IF 404 - 400 
    } catch (ResourceNotFoundException $e) {

      /*
       * Création d'un chargeur de template
       * (avec chemin vers le dossier contenant les templates)
       */ 
      $loader = new Twig_Loader_Filesystem('templates');

      //Création et configuration du moteur de template Twig
      $twig = new Twig_Environment($loader,[
        'cache' => false,           //Donner le chemin du dossier pour activer le cache
        'debug' => true,
        'charset' => 'utf-8',       //Default value
        'auto_reload' => true,      //Regénère le template si la source a été modifiée
        'strict_variables' => true, //Génère une exception si une variable du template n'est pas déclarée
        'autoescape' => true        //Default value
      ]);

      //Rendu de la page (à partir de Twig et de son chargeur de template)
      echo $twig->render('error.twig',array('src' => '404'));

    } catch (Exception $e) {

      /*
       * Création d'un chargeur de template
       * (avec chemin vers le dossier contenant les templates)
       */ 
      $loader = new Twig_Loader_Filesystem('templates');

      //Création et configuration du moteur de template Twig
      $twig = new Twig_Environment($loader,[
        'cache' => false,           //Donner le chemin du dossier pour activer le cache
        'debug' => true,
        'charset' => 'utf-8',       //Default value
        'auto_reload' => true,      //Regénère le template si la source a été modifiée
        'strict_variables' => true, //Génère une exception si une variable du template n'est pas déclarée
        'autoescape' => true        //Default value
      ]);

      //Rendu de la page (à partir de Twig et de son chargeur de template)
      echo $twig->render('error.twig',array('src' => '400'));
    }
});


    /**     Function Search  - GET    **/

$app->get('/search/{word}', function (Request $request, Response $response) {
    try{

        //Recup - Initializing data 
        $word = $request->getAttribute('word');

        //FindOne method from redBean (return an object )
        $wines = R::findOne( 'wine' , ' name = ? ', [ $word ]);

        //ExportAll method (transform the object on an array)
        $arrays = R::exportAll( $wines );

        //Return data
        if(!empty($arrays)){
            $dataStr = json_encode($arrays, JSON_PRETTY_PRINT);
            echo $dataStr;
        } else {
            echo "unvalid";
        }


    //IF 404 - 400 
    } catch (ResourceNotFoundException $e) {
      echo "404";
    } catch (Exception $e) {
      echo "400";
    }

});

    /**     Function Search  - GET    **/

$app->get('/searchBy/{letter}', function (Request $request, Response $response) {
    try{

        //Recup - Initializing data 
        $letter = $request->getAttribute('letter');

        //FindOne method from redBean (return an object with all wines specify )
        $wines = R::find( 'wine' , ' name LIKE ? ', [ $letter.'%' ]);

        //ExportAll method (transform the object on an array)
        $arrays = R::exportAll( $wines );
        //var_dump($arrays);
        //Return data
        if(!empty($arrays)){
            $dataStr = json_encode($arrays, JSON_PRETTY_PRINT);
            echo $dataStr;
        } else {
            echo "unvalid";
        }

    //IF 404 - 500 
    } catch (ResourceNotFoundException $e) {
      echo "404";
    } catch (Exception $e) {
      echo "400";
    }
});
    /** Génère tous les vins de la BDD selon l'ID donné en paramètre  - GET **/

$app->get('/{id}', function (Request $request, Response $response) {

    try {
      
        //Recup - Initializing data 
        $id = $request->getAttribute('id');

        //Load method (return an object)
        $wine = R::load('wine',$id);

        //ExportAll method (transform the object on an array)
        $arrays = R::exportAll( $wine );

        //Return data
        if(!empty($arrays)){
            $dataStr = json_encode($arrays, JSON_PRETTY_PRINT);
            echo $dataStr;
        } else {
            echo "unvalid";
        }
    //IF 404 - 500 
    } catch (ResourceNotFoundException $e) {
      echo "404";
    } catch (Exception $e) {
      echo "400";
    }
});


    /**     Function AddWine - POST   **/

$app->post('/', function (Request $request, Response $response) {
    //flag
    $id = false;

    try {
      
        //Recup & Initializing data 
        $name = strtoupper($_POST["name"]);
        $grapes = $_POST["grapes"];
        $country = $_POST["country"];
        $region = $_POST["region"];
        $year = $_POST["year"];
        $description = $_POST["description"];

        if(isset($_POST["picture"])){
            $img = $_POST["picture"];
        } else {
            $img = "default.jpg";
        }

        //Create a newWine
        $newWine = R::dispense( 'wine' );

        //Add properties
        $newWine->name = $name;
        $newWine->grapes = $grapes;
        $newWine->country = $country;
        $newWine->region = $region;
        $newWine->year = $year;
        $newWine->description = $description;
        $newWine->picture = $img;

        //Store in the database
        $id = R::store( $newWine );

        //Return data
        if($id!==false){
            echo "valid";
        } else {
            echo "unvalid";
        }
    //IF 404 - 500 
    } catch (ResourceNotFoundException $e) {
      echo "404";
    } catch (Exception $e) {
      echo "400";
    }
    
});


    /**     Function EditWine  - PUT  **/

$app->put('/{id}', function (Request $request, Response $response) {
  $id = false;
    try {
      
      //Recup data
      $idWine = $request->getAttribute('id');
      $mediaType = $request->getMediaType();
      $body = $request->getBody();  
      if ($mediaType == 'application/xml') {
        $input = simplexml_load_string($body);
      } elseif ($mediaType == 'application/json') {    
        $input = json_decode($body);
      }
      
      //Initializing data
      $name = $input->nameVal;
      $grapes = $input->grapesVal;
      $country = $input->countryVal;
      $region = $input->regionVal;
      $year = $input->yearVal;
      $description = $input->textVal; 
      
      //Load the wine by ID
      $idWine = (int) $idWine;
      $wineToUpdate = R::load('wine', $idWine);

      //Add properties
      
      $wineToUpdate->name = $name;
      $wineToUpdate->grapes = $grapes;
      $wineToUpdate->country = $country;
      $wineToUpdate->region = $region;
      $wineToUpdate->year = $year;
      $wineToUpdate->description = $description;

      //Store in the database
     $id =  R::store( $wineToUpdate );
      //Return data
     
      if($id!==false){
          echo "valid";
      } else {
          echo "unvalid";
      }
      
    //IF 404 - 500  
    } catch (ResourceNotFoundException $e) {
      echo "404";
    } catch (Exception $e) {
      echo "400";
    }
});

    /**     Function DeleteWine - DELETE   **/

$app->delete('/{id}', function (Request $request, Response $response) {
    $id = false;
    try {
        //Recup data
        $id = $request->getAttribute('id');
        $idWine = (int) $id;

        //Load the wine by id
        $wineToDelete = R::load('wine', $idWine);

        //Delete in the database
        $id =  R::trash($wineToDelete);

        if($id!==false){
            echo "valid";
        } else {
            echo "unvalid";
        }
    //IF 404 - 500 
    } catch (ResourceNotFoundException $e) {
      echo "404";
    } catch (Exception $e) {
      echo "400";
    }
});


$app->run();