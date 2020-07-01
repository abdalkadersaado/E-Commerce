<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Main_Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\MainCategoryRequest;
use DB;

class MainCategoriesController extends Controller
{
    public function index(){

        $default_lang = get_default_lang();
     $categories = Main_Category::Where('translation_lang',$default_lang)->Selection()->get();

        return view('admin.maincategories.index',Compact('categories'));
    }

    public function create() 
    {
        return view('admin.maincategories.create');
    }

    public function store(MainCategoryRequest $request)
    {

        try {
            //return $request;

            $main_categories = collect($request->category);

            $filter = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] == get_default_lang();
            });

            $default_category = array_values($filter->all()) [0];


            $filePath = "";
            if ($request->has('photo')) {

                $filePath = uploadImage('maincategories', $request->photo);
                //maincategories is file in filesystem.
            }

            DB::beginTransaction();

            $default_category_id = Main_Category::insertGetId([
                'translation_lang' => $default_category['abbr'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'photo' => $filePath
            ]);

            $categories = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] != get_default_lang();
            });


            if (isset($categories) && $categories->count()) {

                $categories_arr = [];
                foreach ($categories as $category) {
                    $categories_arr[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $default_category_id,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'photo' => $filePath
                    ];
                }

                Main_Category::insert($categories_arr);
            }

            DB::commit();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }


    public function edit($mainCat_id)
    {

        //get specific categories and its translations
        $mainCategory = Main_Category::with('categories')
        ->selection()
        ->find($mainCat_id);

  if (!$mainCategory)
      return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

  return view('admin.maincategories.edit', compact('mainCategory'));   
    }

    public function update($mainCat_id, MainCategoryRequest $request)
    {

        try {
            $main_category = Main_Category::find($mainCat_id);

           if (!$main_category)
               return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

           // update date

           $category = array_values($request->category) [0];

           if (!$request->has('category.0.active'))
               $request->request->add(['active' => 0]);
           else
               $request->request->add(['active' => 1]);


           Main_Category::where('id', $mainCat_id)
               ->update([
                   'name' => $category['name'],
                   'active' => $request->active,
               ]);

           // save image

           if ($request->has('photo')) 
           {
               $filePath = uploadImage('maincategories', $request->photo);
               Main_Category::where('id', $mainCat_id)
                   ->update([
                        'photo' => $filePath,
                   ]);
           }
           return redirect()->route('admin.maincategories')->with(['success' => 'تم ألتحديث بنجاح']);
       }
        catch (\Exception $ex) 
       {

           return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
       }
    }
}
