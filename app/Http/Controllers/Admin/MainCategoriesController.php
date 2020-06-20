<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Main_Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class MainCategoriesController extends Controller
{
    public function index(){

        $default_lang = get_default_lang();
     $categories = Main_Category::Where('translation_lang',$default_lang)->Selection()->get();

        return view('admin.maincategories.index',Compact('categories'));
    }
}
