<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorsRequest;
use App\Models\MainCategories;
use App\Models\Vendor;
use App\Notifications\VendorCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use DB;
use Illuminate\Support\Str;

class VendorsController extends Controller
{
    public function index(){
        $vendors = Vendor::selection() -> paginate(PAGINATION_COUNT);
        return view('admin.vendors.index', compact('vendors'));
    }

    //create
    public function create(){
        $categories = MainCategories::where('translation_of',0)->active()->get();
        return view('admin.vendors.create',compact('categories'));
    }

    //store
    public function store(VendorsRequest $request){
        try {
            if(!$request->has('active'))
                $request->request->add(['active'=>0]);
            //save image
            if ($request->has('logo')) {
                $file_path = uploadImage('vendors', $request->logo);

            }
           $vendor = Vendor::create([
                'name' => $request->name,
                'address' => $request->address,
                'logo'    => $file_path,
                'mobile'  => $request->mobile,
                'email'   => $request->email,
                'password'   => $request->password,
                'active'  => $request->active,
                'category_id' => $request->category_id,

            ]);

            //send email notification to vendor created
            //Notification::send($vendor, new VendorCreatedNotification($vendor));

            return redirect()->route('admin.vendors')->with(['success'=>'تم انشاء المتجر بنجاح']);
        }catch (\Exception $ex){
            return $ex->getMessage();
            return redirect()->route('admin.vendors')->with(['error'=>'عفوا حدث خطأ ما الرجاء المحاولة لاحقا']);
        }

    }
    //edit
    public function edit($id){
        try{
            $categories = MainCategories::where('translation_of',0)->active()->get();
            $vendor = Vendor::find($id);
            if(isset($vendor)) {
                return view('admin.vendors.edit', compact(['vendor','categories']));
            }else{
                return redirect()->route('admin.vendors')->with(['error'=>'عفوا المتجر غير موجود']);
            }
        }catch (\Exception $ex){
            return redirect()->route('admin.vendors')->with(['error'=>'عفوا حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }
    //update
    public function update(VendorsRequest $request, $id){

        try{
            $vendor = Vendor::selection()->find($id);

            if(isset($vendor)) {

                DB::beginTransaction();

                //active
                if (!$request->has('active'))
                    $request->merge(['active' => 0]);
                else
                    $request->merge(['active' => 1]);

                //password
                if(!empty($request->password)){
                    $request->merge(['password' => bcrypt($request->password)]);
                }else{
                    $request->merge(['password' => $vendor->password]);
                }
                //address
                if(!empty($request->address)){
                    $request->merge(['address' => $request -> address]);
                }else{
                    $request->merge(['address' => $vendor->address]);
                }


                Vendor::where('id',$id)->update([
                    'name' => $request->name,
                    'address' => $request->address,
                    'mobile'  => $request->mobile,
                    'password' => $request->password,
                    'email'   => $request->email,
                    'active'  => $request->active,
                    'category_id' => $request->category_id,
                ]);

                //save image
                if ($request->has('logo')) {
                    $file_path = uploadImage('vendors', $request->logo);
                    Vendor::where('id', $id)->update([
                        'logo' => $file_path,
                    ]);
                }



                DB::commit();
                return redirect()->route('admin.vendors')->with(['success'=>'تم تحديث البيانات بنجاح']);


            }else{
                return redirect()->route('admin.vendors')->with(['error'=>'عفوا المتجر غير موجود']);
            }


        }catch (\Exception $ex){
            DB::rollback();
            //return $ex->getMessage();
            return redirect()->route('admin.vendors')->with(['error'=>'عفوا حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }
    //change active status
    public function changeStatus($id){
        try {
            $vendor = Vendor::find($id);
            if(!$vendor)
                return redirect()->route('admin.vendors')->with(['error'=>'عفوا المتجر غير موجود']);
            $status = $vendor->active == 0 ? 1: 0;
            $vendor->update(['active' => $status]);

            return redirect()->route('admin.vendors')->with(['success'=>'تم تغير الحالة بنجاح']);


        }catch (\Exception $ex){
            return redirect()->route('admin.vendors')->with(['error'=>'عفوا حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }
    //destroy
    public function destroy($id)
    {
        try {
            $vendor = Vendor::find($id);
            if(!$vendor)
                return redirect()->route('admin.vendors')->with(['error'=>'عفوا المتجر غير موجود']);
            $image = Str::after($vendor-> logo ,'assets/');
            $image = base_path('assets/'.$image);
            if(file_exists($image))
                unlink($image);
            //delete vendor
            $vendor->delete();
            return redirect()->route('admin.vendors')->with(['success'=>'تم حذف المتجر بنجاح']);

        }catch (\Exception $ex){
            return redirect()->route('admin.vendors')->with(['error'=>'عفوا حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }

}
