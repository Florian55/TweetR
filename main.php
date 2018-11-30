<?php
//ini_set('display_errors', 1);

session_start();

/* pour le chargement automatique des classes dans vendor */
require_once ('vendor\autoload.php');
require_once ('src\mf\utils\ClassLoader.php');
$loader = new ClassLoader('src');
$loader->register();

use tweeterapp\model\Tweet as tweet;
use tweeterapp\model\Follow as follow;
use tweeterapp\model\Like as like;
use tweeterapp\model\User as user;


$config = parse_ini_file('conf/config.ini');
    
/* une instance de connexion  */
$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection( $config ); /* configuration avec nos paramètres */
$db->setAsGlobal();            /* visible de tout fichier */
$db->bootEloquent();           /* établir la connexion */


// instancier tous les model
//$T = new tweet();
//$F = new follow();
//$L = new like();
//$U = new user();


//$requete = user::select();
//$lignes = $requete->get();
//
//foreach ($lignes as $u){
//   echo $u;
//}
    
//$ctrl = new tweeterapp\control\TweeterController();
//echo $ctrl->viewHome();
//echo $ctrl->viewTweet(59);
//echo $ctrl->viewUserTweets();

$router = new \mf\router\Router();


$router->addRoute('home',
                  '/home/',
                  '\tweeterapp\control\TweeterController',
                  'viewHome',
                   \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('view',
                  '/view/',
                  '\tweeterapp\control\TweeterController',
                  'viewTweet',
                   \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('user',
                  '/user/',
                  '\tweeterapp\control\TweeterController',
                  'viewUserTweets',
                   \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('post',
                  '/post/',
                  '\tweeterapp\control\TweeterController',
                  'viewPostTweet',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('send',
                  '/send/',
                  '\tweeterapp\control\TweeterController',
                  'viewSendTweet',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('login',
                  '/login/',
                  '\tweeterapp\control\TweeterAdminController',
                  'checkLogin',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('follow',
                  '/follow/',
                  '\tweeterapp\control\TweeterAdminController',
                  'follow',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('signUp',
                  '/signup/',
                  '\tweeterapp\control\TweeterAdminController',
                  'checkSignUp',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('logout',
                  '/ogout/',
                  '\tweeterapp\control\TweeterAdminController',
                  'logout',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('like',
                  '/like/',
                  '\tweeterapp\control\TweeterAdminController',
                  'like',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('dislike',
                  '/dislike/',
                  '\tweeterapp\control\TweeterAdminController',
                  'dislike',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('addfollow',
                  '/addfollow/',
                  '\tweeterapp\control\TweeterAdminController',
                  'addFollow',
                  \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);
$router->setDefaultRoute('/home/');

echo $router->run();




//echo $router->urlFor('user', [['id', 5],['name', 'toto']]);

//echo $router->executeRoute('view');

