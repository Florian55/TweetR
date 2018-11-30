<?php

namespace tweeterapp\model;

class Follow extends \Illuminate\Database\Eloquent\Model{
    
    protected $table = 'follow';
    protected $primaryKey = 'id';     /* le nom de la clé primaire */
    public    $timestamps = false;
    
    public function users(){
        return $this->belongsTo('tweeterapp\model\User', 'follower');
    }
  
    
    
}

