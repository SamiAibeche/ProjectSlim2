<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

    /**     Function Search  - GET    **/

$app->get('/', function (Request $request, Response $response) use ($twig){
    //FindAll method from redBean (return an object with all wines )
    $wines = R::findAll( 'wine' );
    try{

      //ExportAll method (transform the object on an array)
      $arrays = R::exportAll( $wines );

      //Return data
      if(!empty($arrays)){


      //Show the page (from Twig and its template loader)
      echo $twig->render('home.twig',array('datas' => $arrays));

      } else {
          echo "unvalid";
      }

    //IF 404 - 400 
    } catch (ResourceNotFoundException $e) {

      //Rendu de la page (à partir de Twig et de son chargeur de template)
      echo $twig->render('error.twig',array('src' => '404'));

    } catch (Exception $e) {

      //Rendu de la page (à partir de Twig et de son chargeur de template)
      echo $twig->render('error.twig',array('src' => '400'));
    }
});

      /** Function OrderBy - GET **/
      
$app->get('/orderby/{order}', function (Request $request, Response $response) use ($twig) {
    
    try{

        //Recup - Initializing data 
        $order = $request->getAttribute('order');

        //FindAll method from redBean (return an object with all wines order by $order)
        $wines = R::findAll( 'wine' , ' ORDER BY '.$order.'' );

        //ExportAll method (transform the object on an array)
        $arrays = R::exportAll( $wines );

       //Return data
      if(!empty($arrays)){

       //Show the page (from Twig and its template loader)
       //var_dump($arrays);
        echo $twig->render('order.twig',array('datas' => $arrays, 'order' => $order));

      } else {
          echo "unvalid";
      }

    //IF 404 - 400 
    } catch (ResourceNotFoundException $e) {

      //Rendu de la page (à partir de Twig et de son chargeur de template)
      echo $twig->render('error.twig',array('src' => '404'));

    } catch (Exception $e) {

      //Rendu de la page (à partir de Twig et de son chargeur de template)
      echo $twig->render('error.twig',array('src' => '400'));
    }
});