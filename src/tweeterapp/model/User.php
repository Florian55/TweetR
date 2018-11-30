<?php

namespace tweeterapp\model;

class User extends \Illuminate\Database\Eloquent\Model{
    
    protected $table = 'user';
    protected $primaryKey = 'id';     /* le nom de la clé primaire */
    public    $timestamps = false;
    
    public function tweets(){
        return $this->hasMany('tweeterapp\model\Tweet', 'author');
    }
    
    public function follows(){
        return $this->belongsTo('tweeterapp\model\Follow', 'follower');
    }
    
   public function likes(){
       return $this->hasMany('tweeterapp\model\Like', 'user_id');
   } 
   
}

