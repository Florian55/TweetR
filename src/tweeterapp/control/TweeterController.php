<?php

namespace tweeterapp\control;



/* Classe TweeterController :
 *  
 * Réalise les algorithmes des fonctionnalités suivantes: 
 *
 *  - afficher la liste des Tweets 
 *  - afficher un Tweet
 *  - afficher les tweet d'un utilisateur 
 *  - afficher la le formulaire pour poster un Tweet
 *  - afficher la liste des utilisateurs suivis 
 *  - évaluer un Tweet
 *  - suivre un utilisateur
 *   
 */

class TweeterController extends \mf\control\AbstractController {


    /* Constructeur :
     * 
     * Appelle le constructeur parent
     *
     * c.f. la classe \mf\control\AbstractController
     * 
     */
    
    public function __construct(){
        parent::__construct();
    }


    /* Méthode viewHome : 
     * 
     * Réalise la fonctionnalité : afficher la liste de Tweet
     * 
     */
    
    public function viewHome(){

        /* Algorithme :
         *  
         *  1 Récupérer tous les tweet en utilisant le modèle Tweet
         *  2 Parcourir le résultat 
         *      afficher le text du tweet, l'auteur et la date de création
         *  3 Retourner un block HTML qui met en forme la liste
         * 
         */
               
       $tweets = \tweeterapp\model\Tweet::orderBy("id", "desc")->get();
       $v = new \tweeterapp\view\TweeterView($tweets);
       return $v->render('home');
    }


    /* Méthode viewTweet : 
     *  
     * Réalise la fonctionnalité afficher un Tweet
     *
     */
    
    public function viewTweet(){

        /* Algorithme : 
         *  
         *  1 L'identifiant du Tweet en question est passé en paramètre (id) 
         *      d'une requête GET 
         *  2 Récupérer le Tweet depuis le modèle Tweet
         *  3 Afficher toutes les informations du tweet 
         *      (text, auteur, date, score)
         *  4 Retourner un block HTML qui met en forme le Tweet
         * 
         *  Erreurs possibles : (*** à implanter ultérieurement ***)
         *    - pas de paramètre dans la requête
         *    - le paramètre passé ne correspond pas a un identifiant existant
         *    - le paramètre passé n'est pas un entier 
         * 
         */
       $res = ""; 
       $req = new \mf\utils\HttpRequest();
       if(isset($req->get['id'])){
            $id = $req->get['id'];
            $tweet = \tweeterapp\model\Tweet::select()->where('id', '=', $id);
            $ligne = $tweet->first();
            if($ligne){
                $v = new \tweeterapp\view\TweeterView($ligne);
                $res = $v->render('viewTweet');
            } 
       }
       return $res;
    }

    /* Méthode viewUserTweets :
     *
     * Réalise la fonctionnalité afficher les tweet d'un utilisateur
     *
     */
    
    public function viewUserTweets(){

        /*
         *
         *  1 L'identifiant de l'utilisateur en question est passé en 
         *      paramètre (id) d'une requête GET 
         *  2 Récupérer l'utilisateur et ses Tweets depuis le modèle 
         *      Tweet et User
         *  3 Afficher les informations de l'utilisateur 
         *      (nom, login, nombre de suiveurs) 
         *  4 Afficher ses Tweets (text, auteur, date)
         *  5 Retourner un block HTML qui met en forme la liste
         *
         *  Erreurs possibles : (*** à implanter ultérieurement ***)
         *    - pas de paramètre dans la requête
         *    - le paramètre passé ne correspond pas a un identifiant existant
         *    - le paramètre passé n'est pas un entier 
         * 
         */
       $req = new \mf\utils\HttpRequest();
       
       if(isset($req->get['id'])){
            $id = $req->get['id'];
            $user = \tweeterapp\model\User::select()->where('id', '=', $id)->first();
            $tweets = $user->tweets()->get();
            $table = ['user' => $user, 'tweet' => $tweets];
            $v = new \tweeterapp\view\TweeterView($table);
            $res = $v->render('userTweets');
            
            return $res;
       } 
    }
    
    public function viewPostTweet(){
        $v = new \tweeterapp\view\TweeterView('');
        return $v->render('postTweet');
    }
    
    public function viewSendTweet(){
        if(($_POST['message']) == NULL){
            $v = new \tweeterapp\view\TweeterView('');
            return $v->render('postTweet');
        }else{
            $username = $_SESSION['user_login'];
            $user = \tweeterapp\model\User::where('username', '=', $username)->first();
            $mess = array($_POST['message']);
            foreach ($mess as $message){
                $tweet = \tweeterapp\model\Tweet::insert(['text'=>$message, 'author'=> $user->id,'score'=>0,'created_at'=> date('Y/m/d H:i:s'), 'updated_at'=> date('Y/m/d H:i:s')]);
                filter_var($tweet, FILTER_SANITIZE_SPECIAL_CHARS);
            }
            $v = new \tweeterapp\view\TweeterView($tweet);
            return $v->render('sendTweet');
        }
    }
    
    
    
}