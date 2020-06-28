<?php

use App\Models\Language;
use Illuminate\Support\Facades\Config;

function get_languages()
{
    return Language::active()->Selection()->get();
}

function get_default_lang(){
    return   Config::get('app.locale');
  }


  function uploadImage($folder, $image)
{
    $image->store('/', $folder);
    $filename = $image->hashName();
    $path = 'images/' . $folder . '/' . $filename;
    return $path;
}