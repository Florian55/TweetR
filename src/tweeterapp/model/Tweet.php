<?php

namespace tweeterapp\model;

class Tweet extends \Illuminate\Database\Eloquent\Model{
    
    protected $table = 'tweet';
    protected $primaryKey = 'id';     /* le nom de la clé primaire */
    public    $timestamps = true;  
    
    public function user(){
        return $this->belongsTo('tweeterapp\model\User', 'author');
    }
    
    public function likes(){
        return $this->belongsTo('tweeterapp\model\Like', 'tweet_id');
    }
    
}

