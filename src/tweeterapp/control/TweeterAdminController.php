<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace tweeterapp\control;

ini_set('display_errors', 1);

use tweeterapp\model\Tweet as Tweet;
use tweeterapp\model\Follow as Follow;
use tweeterapp\model\Like as Like;
use tweeterapp\model\User as User;

/**
 * Description of TweeterAdminController
 *
 * @author fharb
 */
class TweeterAdminController extends \mf\control\AbstractController {
    
     public function __construct(){
        parent::__construct();
    }
    
//    public function login(){
//        $v = new \tweeterapp\view\TweeterView('');
//        $v->render('login');     
//    }
    
    public function checkLogin(){        
        
        $request = new \mf\utils\HttpRequest();
        $post = $request->post;
        
        $auth = new \tweeterapp\auth\TweeterAuthentification();
        $errors = [];
        if(!empty($post['username']) && !empty($post['password'])){
            try {
              $auth->loginUser($post['username'], $post['password']);
              $router = new \mf\router\Router();
              header('Location: ' . $router->urlFor('follow'));
            } catch (\mf\auth\exception\AuthentificationException $e) {
              $errors[] = $e->getMessage();
            }
        }else{
            $errors[] = "Les champs sont mal renseignés!";
        }
        
        if(!empty($errors)){
            $tweeterView = new \tweeterapp\view\TweeterView(['errors' => $errors]);
            $tweeterView->render('login');
        }
    }
    
    public function checkSignUp(){
       $request = new \mf\utils\HttpRequest();
       $post = $request->post;
       
       $auth = new \tweeterapp\auth\TweeterAuthentification();
       $errors = [];
       
       if(!empty($post['fullname']) || !empty($post['username_sign']) || !empty($post['password_sign']) || !empty($post['retape_password'])){
           try {
              $auth->createUser($post['username_sign'], $post['password_sign'], $post['fullname']);
              $router = new \mf\router\Router();
              header('Location: ' . $router->urlFor('home'));
           } catch (\mf\auth\exception\AuthentificationException $e) {
             $errors[] = $e->getMessage();  
           }
       }else{
           $errors[] = "Les champs sont mal renseignés!"; 
       }
       
       if(!empty($errors)){
            $tweeterView = new \tweeterapp\view\TweeterView(['errors' => $errors]);
            $tweeterView->render('signUp');
        }
    }
    
    public function follow(){
        
        
        $username = $_SESSION['user_login'];
        $user = User::where('username', '=', $username)->first();
        $follows = Follow::where('followee', '=', $user->id)->get();
        
        $id_followers = array();
        foreach ($follows as $follower){
            array_push($id_followers, $follower->follower);
        }
        $users = User::whereIn("id", $id_followers)->get();
        $table = ['user' => $user, 'follow' => $users];
        $v = new \tweeterapp\view\TweeterView($table);
        $res = $v->render('follow');

        return $res;
    }
    
    public function logout(){
        $auth = new \tweeterapp\auth\TweeterAuthentification();
        $router = new \mf\router\Router();
        if ($router->urlFor('logout')){
           $auth->logout();
           header('Location: ' . $router->urlFor('home'));
        }
    }
    
    public function like(){
        $request = new \mf\utils\HttpRequest();
        $get = $request->get;
        
        $router = new \mf\router\Router();
        
        $username = $_SESSION['user_login'];
        $tweet_id = $get['id'];
        
        $user = User::where('username', '=', $username)->first();
        $likes = Like::where('user_id', '=', $user->id)->first();
        $tweet = Tweet::where('id', '=', $tweet_id)->first();

        if($likes != NULL || $user->id == $tweet->author){
            echo $tweet_id;
            header('Location: ' . $router->urlFor('view', array($tweet_id)));
        }else{
            $like = new Like();
            $like->user_id = $user->id;
            $like->tweet_id = $tweet_id;
            $like->save();
            $tweet->increment('score');
            header('Location: ' . $router->urlFor('view', array($tweet_id)));  
        }        
    }
        

    public function dislike(){
        $request = new \mf\utils\HttpRequest();
        $get = $request->get;
        
        $router = new \mf\router\Router();
        
        $username = $_SESSION['user_login'];
        $tweet_id = $get['id'];
        
        $user = User::where('username', '=', $username)->first();
        $likes = Like::where('user_id', '=', $user->id)->first();
        $tweet = Tweet::where('id', '=', $tweet_id)->first();

        if($likes != NULL || $user->id == $tweet->author){
            header('Location: ' . $router->urlFor('view', array($tweet_id)));
        }else{
            $tweet->decrement('score');
            header('Location: ' . $router->urlFor('view', array($tweet_id)));   
        }        
    }
    
    public function addFollow(){
        $request = new \mf\utils\HttpRequest();
        $get = $request->get;
        
        $router = new \mf\router\Router();
        
        $username = $_SESSION['user_login'];
        $tweet_id = $get['id'];
        
        //id du user connecté
        $user = User::where('username', '=', $username)->first();
        //id du tweet
        $tweet = Tweet::where('id', '=', $tweet_id)->first();
        echo $tweet->author;
        //user du tweet
        $follows = Follow::where('follower', '=', $user->id)->first();
        
        if($follows != NULL || $user->id == $tweet->author ){
            header('Location: ' . $router->urlFor('view', array($tweet_id)));
        }else{
           $follow = new Follow();
           $follow->follower = $user->id;
           $follow->followee = $tweet->author;
           $follow->save();
           $user->increment('followers');
           header('Location: ' . $router->urlFor('view', array($tweet_id)));
        }
    }
    
}
