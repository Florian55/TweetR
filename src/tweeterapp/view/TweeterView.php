<?php

namespace tweeterapp\view;

class TweeterView extends \mf\view\AbstractView {
  
    /* Constructeur 
    *
    * Appelle le constructeur de la classe parent
    */
    public function __construct($data){
        parent::__construct($data);
    }

    /* Méthode renderHeader
     *
     *  Retourne le fragment HTML de l'entête (unique pour toutes les vues)
     */ 
    private function renderHeader(){ 
        $html = '<h1>MiniTweeTR</h1>';
           $html .= '<nav>' .
                    $this->renderTopMenu();
        return $html;
    }
    
    /* Méthode renderFooter
     *
     * Retourne le fragment HTML du bas de la page (unique pour toutes les vues)
     */
    private function renderFooter(){
        return 'La super app créée en Licence Pro &copy;2018';
    }

    /* Méthode renderHome
     *
     * Vue de la fonctionalité afficher tous les Tweets. 
     *  
     */
    
    private function renderHome(){

        /*
         * Retourne le fragment HTML qui affiche tous les Tweets. 
         *  
         * L'attribut $this->data contient un tableau d'objets tweet.
         * 
         */
        $router = new \mf\router\Router();
        
        $html = "<article class='theme-backcolor2'>";
        foreach($this->data as $tweet){
            $tweet_id = array($tweet->id);
            $user_id = array($tweet->user->id);
            $html .=    '<div class="tweet">' .
                            '<a href="' . $router->urlFor('view', $tweet_id) . '"><div class="tweet-text">' . $tweet->text . '</div></a>' .
                            '<div class="tweet-footer">' .
                                '<a href="' . $router->urlFor('user', $user_id) . '"><div class="tweet-author">' . $tweet->user->fullname . '</div></a>' .
                                '<div>' . $tweet->created_at . '</div>' .
                            '</div>' .
                        '</div>';  
        }
        $html .= '</article>';
        return $html; 
        
    }
  
    /* Méthode renderUeserTweets
     *
     * Vue de la fonctionalité afficher tout les Tweets d'un utilisateur donné. 
     * 
     */
     
    private function renderUserTweets(){

        /* 
         * Retourne le fragment HTML pour afficher
         * tous les Tweets d'un utilisateur donné. 
         *  
         * L'attribut $this->data contient un objet User.
         *
         */
        $router = new \mf\router\Router();
        $html = '<article class="theme-backcolor2">' .
                    '<h2>Tweets de ' . $this->data['user']->fullname . '</h2>' .
                    '<h3>' . $this->data['user']->followers . ' followers</h3>';
            foreach($this->data['tweet'] as $tweet){
                $tweet_id = array($tweet->id);
                $user_id = array($tweet->user->id);
           $html .= '<div class="tweet">' . 
                        '<a href="' . $router->urlFor('view', $tweet_id) . '"><div class="tweet-text">' . $tweet->text . '</div></a>' .
                        '<div class="tweet-footer">' .
                            '<a href="' . $router->urlFor('user', $user_id) . '"><div class="tweet-author">' . $tweet->user->fullname . '</div></a>' .
                            '<div>' . $tweet->created_at . '</div>' .
                        '</div>' . 
                    '</div>';   
            }
       $html .= '</article>';
        return $html;
        
    }
  
    /* Méthode renderViewTweet 
     * 
     * Réalise la vue de la fonctionnalité affichage d'un tweet
     *
     */
    
    private function renderViewTweet(){
        /* 
         * Retourne le fragment HTML qui réalise l'affichage d'un tweet 
         * en particulié 
         * 
         * L'attribut $this->data contient un objet Tweet
         *
         */
        
        $connecté = $_SESSION['access_level'];
        $router = new \mf\router\Router();
        $tweet_id = array($this->data->id);
        $user_id = array($this->data->user->id);
        
        $html = '<article class="theme-backcolor2">' .
                    '<div class="tweet">' .
                        '<a href="' . $router->urlFor('view', $tweet_id) . '"><div class="tweet-text">' . $this->data->text . '</div></a>' .
                        '<div class="tweet-footer"> ' . 
                            '<a href="' . $router->urlFor('user', $user_id) . '"><div class="tweet-author">' . $this->data->user->fullname .  '</div></a>' .
                            '<div>' . $this->data->created_at . '</div>' .
                        '</div>' .
                            '<hr/>' .
                        '<div clas="tweet-footer">' .
                            '<div class="tweet-score tweet-control">' . $this->data->score . '</div>';
                            if ($connecté > 0) {
                   $html .= '<div class="tweet-control"><a href="' . $router->urlFor('like') . "?id=" . $_GET["id"] . '"><img src="/Tweeter/html/img/like.png" alt="Home"></a></div>' .
                            '<div class="tweet-control"><a href="' . $router->urlFor('dislike') . "?id=" . $_GET["id"] . '"><img src="/Tweeter/html/img/dislike.png" alt="Home"></a></div>' . 
                            '<div class="tweet-control"><a href="' . $router->urlFor('addfollow') . "?id=" . $_GET["id"] . '"><img src="/Tweeter/html/img/follow.png" alt="Add"></a></div>';
                            }       
                  $html .=  '</div>' . 
                    '</div>' . 
                '</article>';
        return $html;  
    }



    /* Méthode renderPostTweet
     *
     * Realise la vue de régider un Tweet
     *
     */
    private function renderPostTweet(){
        
        /* Méthode renderPostTweet
         *
         * Retourne la fragment HTML qui dessine un formulaire pour la rédaction 
         * d'un tweet, l'action du formulaire est la route "send"
         *
         */
        $router = new \mf\router\Router();
        
        $html = '<article class ="theme-backcolor2">' .
                    '<form method="post" action="/Tweeter/main.php/send/">' .
                        '<div>' .
                            '<textarea id="tweet-form" type="text" name="message" placeholder="Tweet here.."></textarea>' .
                        '</div>' .
                        '<div id="send-button">' .
                            '<button type="submit"> SEND </button>' .
                        '</div>' .
                    '</form>' .
                '</article>'; 
                
        return $html;    
    }
    
    private function renderSendTweet(){
        $html = '<article class="theme-backcolor2">' .
                     '<form method="post" action="/Tweeter/main.php/post/">' .
                         '<div>' .
                             '<p> Nouveau tweet envoyé. </p>' .
                         '</div>' .
                         '<div>' .
                             '<button type="submit"> Retour </button>' .
                         '</div>' .
                     '</form>' .
                 '</article>'; 
        
        return $html;
                
    }
    
    private function renderLogin(){
        $html = '<article class="theme-backcolor2">' .
                    '<form method="post" class="forms">' .
                        '<input class="forms-text" type="text" name="username" placeholder="username"> </input>' .
                        '<input class="forms-text" type="password" name="password" placeholder="password"> </input>' .
                        '<button class="forms-button" name="login_button" type="submit"> Login </button></a>' .
                    '</form>' .
                '</article>';
        return $html;
    }
    
    private function renderFollowers(){
        $router = new \mf\router\Router();
        
        $html = '<article class="theme-backcolor2">' .
                    '<h2>Follower(s) de ' . $this->data['user']->fullname . ' :</h2>' ;
               if(count($this->data['follow']) < 1){
                   $html .= '<div> Pas de Follower </div>'; 
                }else{
                        foreach ($this->data['follow'] as $follow){
                            $user_id = array($follow->id);
                   $html .= '<a href="' . $router->urlFor('user', $user_id) . '"<div>' . $follow->fullname . '</div></a></br>';    
                        }      
                }
       $html .= '</article>';       
        return $html;
    }
    
    private function renderSignUp(){
        $html = '<article class="theme-backcolor2">' .
                    '<form method="post" class="forms">' .
                        '<input class="forms-text" type="text" name="fullname" placeholder="fullname"> </input>' .
                        '<input class="forms-text" type="text" name="username_sign" placeholder="username"> </input>' .
                        '<input class="forms-text" type="password" name="password_sign" placeholder="password"> </input>' .
                        '<input class="forms-text" type="password" name="retape_password" placeholder="retype password"> </input>' .
                        '<button class="forms-button" name="create" type="submit"> Create </button>' .
                    '</forms>' .
                '</article>'; 
        return $html;
    }
    
    private function renderBottomMenu(){
        $router = new \mf\router\Router();
        
        $html = '<div class="theme-backcolor1">' .
                   '<a href="' . $router->urlFor('post') . '"><button class="button-new">NEW </button></a>' .
                '</div>';
        return $html;
    }
    
    private function renderTopMenu(){
        $connecté = $_SESSION['access_level'];

        if($connecté > 0){
            $router = new \mf\router\Router(); 
            $html =     '<a href="' . $router->urlFor('home') . '"><img src="/Tweeter/html/img/home.png" alt="Home"></a>' .
                        '<a href="' . $router->urlFor('follow') . '"><img src="/Tweeter/html/img/followees.png" alt="Follow"></a>' .
                        '<a href="' . $router->urlFor('logout') . '"><img src="/Tweeter/html/img/logout.png" alt="Logout"></a>' .
                    '</nav>';
        }else{
            $router = new \mf\router\Router(); 
            $html =     '<a href="' . $router->urlFor('home') . '"><img src="/Tweeter/html/img/home.png" alt="Home"></a>' .
                        '<a href="' . $router->urlFor('login') . '"><img src="/Tweeter/html/img/enter.png" alt="Login"></a>' .
                        '<a href="' . $router->urlFor('signUp') . '"><img src="/Tweeter/html/img/add.png" alt="Add"></a>' .
                    '</nav>';
        }
        return $html;
    }
    


    /* Méthode renderBody
     *
     * Retourne la framgment HTML de la balise <body> elle est appelée
     * par la méthode héritée render.
     *
     */
    
    public function renderBody($selector = null){
        $connecté = $_SESSION['access_level'];
        
        //vue du header
        $header = $this->renderHeader();
        $html = '<header class="theme-backcolor1"> ' . $header . '</header>';
        
        //vue de la section
                
        if($selector == 'home'){
            $html .= '<section>' . $this->renderHome();
        }else if ($selector == 'userTweets') {
            $html .= '<section>' .  $this->renderUserTweets();
        }else if ($selector == 'viewTweet') {
            $html .= '<section>' .  $this->renderViewTweet();
        }else if ($selector == 'postTweet') {
            $html .= '<section>' . $this->renderPostTweet();
        }else if ($selector == 'sendTweet') {
            $html .= '<section>' . $this->renderSendTweet();
        }elseif ($selector == 'login') {
            $html .= '<section>' . $this->renderLogin();
        }elseif ($selector == 'follow') {
            $html .= '<section>' . $this->renderFollowers();
        }elseif ($selector == 'signUp') {
            $html .= '<section>' . $this->renderSignUp(); 
        }
        
        if ($connecté > 0) {
            $html .= $this->renderBottomMenu() . '</section>';
        }else{
            $html .= '</section>' ;
        }
        
        
        //vue du footer
        $footer = $this->renderFooter();
        
        $html .= '<footer class="theme-backcolor1"> ' . $footer . '</footer>';
     
        return $html;
    }


    
}
