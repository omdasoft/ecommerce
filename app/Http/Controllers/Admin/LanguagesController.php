<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
    public function index(){
        $languages = Language::selection()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index',compact('languages'));
    }

    public function create(){
        return view('admin.languages.create');
    }

    public function store(LanguageRequest $request){
        try{
            if(!$request->has('active'))
                $request->request->add(['active'=>0]); 
            Language::create($request->except(['_token']));
            return redirect()->route('admin.languages')->with(['success'=> 'تم اضافة اللغة بنجاح']);
       }catch (\Exception $ex){
            return redirect()->back()->with(['error'=>'عفوا هنالك خطأ ما يرجي المحاوله لاحقا']);
        }
    }

    public function edit($id){
        $lang = Language::select()->find($id);
        if(!$lang){
            return redirect()->route('admin.languages')->with(['error'=>'عفوا هذة اللغة غير موجوده']);
        }else{
            return view('admin.languages.edit',compact('lang'));
        }
    }

    public function update(LanguageRequest $request,$id){
        $lang = Language::find($id);
        try{
            if(!$request->has('active'))
                $request->request->add(['active'=>0]);
            $lang->update($request->all());
            return redirect()->route('admin.languages')->with(['success'=>'تم تحديث البيانات بنجاح']);
        }catch (\Exception $ex){
          return redirect()->back()->with(['error'=>'عفوا هنالك خطأ ما يرجي المحاوله لاحقا']);
       }

    }

    public function destroy($id){
        $lang = Language::find($id);
        try{
            if(!$lang){
                return redirect()->route('admin.languages')->with(['error'=>'عفوا هذة اللغة غير موجوده']);
            }else{
                $lang->delete();
                return redirect()->route('admin.languages')->with(['success'=>'تم حذف اللغة بنجاح']);
            }
        }catch (\Exception $ex){

        }
    }
}
