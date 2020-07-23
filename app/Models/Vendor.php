<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use Notifiable;
    protected  $table = 'vendors';

    protected $fillable = [
        'name', 'email','password','address','category_id','mobile','active','logo','created_at','updated_at',
    ];

    protected $hidden = ['category_id','password'];

    public function scopeActive($q){
        return $q ->where('active',1);
    }

    public function getActive(){
        return $this -> active == 1 ? 'مفعل':'غير مفعل';
    }

    public function getLogoAttribute($val){
        return $val != null ? asset('assets/'.$val):"";
    }

    public function scopeSelection($q){
        return $q->select('id','category_id','address','password','email','active','logo','name','mobile');
    }

    public function category(){
        return $this-> belongsTo('App\Models\MainCategories','category_id','id');
    }

    public function setPasswordAttribute($pass){
        if(!empty($pass)){
            return $this->attributes['password'] = bcrypt($pass);
        }
    }



}
