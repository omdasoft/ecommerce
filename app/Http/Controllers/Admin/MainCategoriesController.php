<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCatRequest;
use App\Models\MainCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use DB;
use Illuminate\Support\Str;

class MainCategoriesController extends Controller
{
    public function index(){
        $default_lang = get_default_lang();
        $cats = MainCategories::where('translation_lang',$default_lang)->Selection()->paginate(PAGINATION_COUNT);
        return view('admin.maincats.index',compact('cats'));
    }

    public function create(){
        return view('admin.maincats.create');
    }

    public function store(MainCatRequest $request)
    {
        try{
        $main_cats = collect($request->category);
        $filter = $main_cats->filter(function ($value, $key) {
            return $value['abbr'] == get_default_lang();
        });

        $file_path = "";
        if ($request->has('photo')) {
            $file_path = uploadImage('maincats', $request->photo);

        }
        $default_cat = array_values($filter->all())[0];

       // DB::beginTransaction();

        $default_cat_id = MainCategories::insertGetId([
            'translation_lang' => $default_cat['abbr'],
            'translation_of' => 0,
            'name' => $default_cat['name'],
            'slug' => $default_cat['name'],
            'photo' => $file_path
        ]);

        $cats = $main_cats->filter(function ($value, $key) {
            return $value['abbr'] != get_default_lang();
        });

        if (isset($cats) && $cats->count()) {
            $cats_arr = [];
            foreach ($cats as $cat) {
                $cats_arr[] = [
                    'translation_lang' => $cat['abbr'],
                    'translation_of' => $default_cat_id,
                    'name' => $cat['name'],
                    'slug' => $cat['name'],
                    'photo' => $file_path
                ];
            }

            MainCategories::insert($cats_arr);
            return redirect()->route('admin.maincats')->with(['success' => 'تم حفظ القسم بنجاح']);

        }
        //DB::commit();
        }catch (\Exception $ex){
           // DB::rolleback();
            return redirect()->route('admin.maincats')->with(['error'=>'حدث خطا ما الرجاء المحاولة لاحقا']);
        }
    }

    //edit category function
    public function edit($mainCat_id){
        //get specific category with its translations
        $mainCategory = MainCategories::with('categories')->Selection()->find($mainCat_id);
        if(!$mainCategory){
            return redirect()->route('admin.maincats')->with(['error'=>'عفوا الفسم غير موجود']);
        }

        return view('admin.maincats.edit',compact('mainCategory'));
    }

    //update  category

    public function update(MainCatRequest $request,$mainCat_id){
        try {
            //find category id
            $mainCategory = MainCategories::Selection()->find($mainCat_id);

            //check if category exists
            if (!$mainCategory) {
                return redirect()->route('admin.maincats')->with(['error' => 'عفوا الفسم غير موجود']);
            }

            //get the first category
            $category = array_values($request->category)[0];

            //check the active value


            if (!$request->has('category.0.active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            MainCategories::where('id', $mainCat_id)->update([
                'name' => $category['name'],
                'active' => $request->active,
            ]);

            //save image
            if ($request->has('photo')) {
                $file_path = uploadImage('maincats', $request->photo);
                MainCategories::where('id', $mainCat_id)->update([
                    'photo' => $file_path,
                ]);
            }

            return redirect()->route('admin.maincats')->with(['success' => 'تم التحديث بنجاح']);
        }catch (\Exception $e){
            return redirect()->route('admin.maincats')->with(['error' => 'عفوا حدث خطأ ما الرجاء المحاوله لاحقا']);

        }

    }

    //change status

    public function changeStatus($id){
        try {
            $category = MainCategories::find($id);
            if(!$category)
                return redirect()->route('admin.maincats')->with(['error'=>'عفوا الفسم غير موجود']);

                $status = $category->active == 0 ? 1 : 0;
                $category->update(['active' => $status]);


            return redirect()->back()->with(['success' => 'تم تغيير الحالة بنجاح']);


        }catch (\Exception $ex){
            return redirect()->route('admin.maincats')->with(['error' => 'عفوا حدث خطأ ما الرجاء المحاوله لاحقا']);
        }
    }

    //delete
    public function destroy($id){
        try {
            $mainCategory = MainCategories::find($id);
            if(!$mainCategory)
                return redirect()->route('admin.maincats')->with(['error' => 'عفوا هذا التصنيف غير موجود']);
            //get the vendors
            $vendors = $mainCategory -> vendors();

            if(isset($vendors) && $vendors->count()){
                return redirect()->route('admin.maincats')->with(['error' => 'عفوا لا يمكن مسح هذا القسم']);
            }


            $image = Str::after($mainCategory-> photo ,'assets/');
            $image = base_path('assets/'.$image);
            if(file_exists($image))
                unlink($image);
            //delete category translations
            $mainCategory->categories()->delete();
            //delete main category
            $mainCategory -> delete();
            return redirect()->route('admin.maincats')->with(['success' => 'تم الحذف بنجاح']);


        }catch (\Exception $ex){
            return redirect()->route('admin.maincats')->with(['error' => 'عفوا حدث خطأ ما الرجاء المحاوله لاحقا']);

        }
    }
}
