<?php

use Illuminate\Support\Facades\Config;

function get_languages(){
   return \App\Models\Language::active()->Selection()->get();
}

//get the default language
function get_default_lang(){
    return Config::get('app.locale');
}

//uplaod image
function uploadImage($folder, $image){
    $image->store('/',$folder);
    $filename = $image->hashName();
    $path = 'images/'.$folder.'/'.$filename;
    return $path;
}
