<?php

namespace tweeterapp\model;

class Like extends \Illuminate\Database\Eloquent\Model{
    
    protected $table = 'like';
    protected $primaryKey = 'id';     /* le nom de la clé primaire */
    public    $timestamps = false;
   
    public function tweets(){
        return $this->hasMany('tweeterapp\model\Tweet', 'id');
    }
    
    public function user(){
        return $this->belongsTo('tweeterapp\model\User', 'user_id');
    }
    
    
    
    
}

