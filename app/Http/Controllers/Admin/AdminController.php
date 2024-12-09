<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
use App\Models\Admin;
use App\Models\Vendor;
use App\Models\VendorsBusinessDetail;
use App\Models\VendorsBankDetail;
use Image;
// use Intervention\Image\Facades\Image;
use Session;

class AdminController extends Controller
{
    public function dashboard(){
        Session::put('page','dashboard');
        return view('admin.dashboard'); 
    }

    public function updateAdminPassword(Request $request){
        Session::put('page','update_admin_password');
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            //التحقق اذا كلمة المرور الحالية ادخلت بواسطة الادمن بشكل صحيح 
            if(Hash::check($data['current_password'],Auth::guard('admin')->user()->password)){
                // التحقق اذا كلمة السر الجديدة نفس المكررة منها للتاكيد
                if($data['confirm_password']==$data['new_password']){
                    Admin::where('id',Auth::guard('admin')->user()->id)->update(['password'=>
                    bcrypt($data['new_password'])]);
                    return redirect()->back()->with('success_message','Password has been updated successfully!');
                }else{
                    return redirect()->back()->with('error_message','New password and Confirm password does not match!');

                }
            }else{
                return redirect()->back()->with('error_message','Your current password is Incorrect!');
            }

        }
        $adminDetails = Admin::where('email',Auth::guard('admin')->user()->email)->first()->toArray();
        return view('admin.settings.update_admin_password')->with(compact('adminDetails')); 
    }

    public function checkAdminPassword(Request $request){
    $data = $request->all();
    // echo "<pre>"; print_r($data); die;
    if(Hash::check($data['current_password'],Auth::guard('admin')->user()->password)){
        return "true";
    }else{
        return "false";
    }
    }

    public function updateAdminDetails(Request $request){
        Session::put('page','update_admin_details');
        if($request->isMethod('post')){
            $data = $request->all();
            //  echo "<pre>"; print_r($data); die;

            $rules = [
                  'admin_name' => 'required|regex:/^[\pL\s\-]+$/u',
                  'admin_mobile' => 'required|numeric',
            ];

            $this->validate($request,$rules);

            // //رفع صورة الادمن 
            //  if($request->hasFile('admin_image')){
            //      $image_tmp = $request->file('admin_image');
            //       if($image_tmp->isValid()){
            //         // الحصول على ملحق الصورة
            //          $extension = $image_tmp->getClientOriginalExtension();  
            //         //انشاء اسم صورة جديد 
            //          $imageName = rand(111,99999).'.'.$extension; 
            //          $imagePath = 'admin/images/photos/'.$imageName;
            //         //رفع الصورة 
            //         Image::make($image_tmp)->save($imagePath);
            //       }
            //  }else if(!empty($data['current_admin_image'])){
            //     $imageName = $data['current_admin_image'];
            //  }else{
            //     $imageName = "";
            //  }
    

             //تحديث تفاصيل الادمن
             Admin::where('id',Auth::guard('admin')->user()->id)->update
             (['name'=>$data['admin_name'], 'mobile'=>$data['admin_mobile']
           //  ,'image'=>$imageName
            ]);
             return redirect()->back()->with('success_message','Admin details
              has been updated successfully!');
        }
      return view('admin\settings\update_admin_details');
    }
    public function updateVendorDetails($slug, Request $request){
        if($slug=="personal"){
            Session::put('page','update_personal_details');
            if($request->isMethod('post')){
                $data = $request->all();
                 // echo "<pre>"; print_r($data); die;

                  $rules = [
                    'vendor_name' => 'required|regex:/^[\pL\s\-]+$/u',
                    'vendor_city' => 'required|regex:/^[\pL\s\-]+$/u',
                    'vendor_mobile' => 'required|numeric',
              ];
  
              $this->validate($request,$rules);
  
              // //رفع صورة الادمن 
              //  if($request->hasFile('vendor_image')){
              //      $image_tmp = $request->file('vendor_image');
              //       if($image_tmp->isValid()){
              //         // الحصول على ملحق الصورة
              //          $extension = $image_tmp->getClientOriginalExtension();  
              //         //انشاء اسم صورة جديد 
              //          $imageName = rand(111,99999).'.'.$extension; 
              //          $imagePath = 'admin/images/photos/'.$imageName;
              //         //رفع الصورة 
              //         Image::make($image_tmp)->save($imagePath);
              //       }
              //  }else if(!empty($data['current_vendor_image'])){
              //     $imageName = $data['current_vendor_image'];
              //  }else{
              //     $imageName = "";
              //  }
      
  
               //تحديث في جدول الادمن
               Admin::where('id',Auth::guard('admin')->user()->id)->update
               (['name'=>$data['vendor_name'], 'mobile'=>$data['vendor_mobile']
             //  ,'image'=>$imageName
              ]);
              //تحديث في جدول البائع
              Vendor::where('id',Auth::guard('admin')->user()->vendor_id)->update
              (['name'=>$data['vendor_name'], 'mobile'=>$data['vendor_mobile'], 'address'=>$data['vendor_address']
              , 'city'=>$data['vendor_city'], 'state'=>$data['vendor_state'], 'country'=>$data['vendor_country']
              , 'pincode'=>$data['vendor_pincode']
             ]);
               return redirect()->back()->with('success_message','Vendor details
                has been updated successfully!');
  
            }
    
            $vendorDetails = Vendor::where('id',Auth::guard('admin')->user()->vendor_id)->first()->toArray();

        }else if($slug=="business"){
            Session::put('page','update_business_details');
            if($request->isMethod('post')){
                $data = $request->all();
                 // echo "<pre>"; print_r($data); die;

                  $rules = [
                    'shop_name' => 'required|regex:/^[\pL\s\-]+$/u',
                    'shop_city' => 'required|regex:/^[\pL\s\-]+$/u',
                    'shop_mobile' => 'required|numeric',
                    'address_proof' => 'required',
              ];
  
              $this->validate($request,$rules);
  
              // //رفع صورة الادمن 
              //  if($request->hasFile('address_proof_image')){
              //      $image_tmp = $request->file('address_proof_image');
              //       if($image_tmp->isValid()){
              //         // الحصول على ملحق الصورة
              //          $extension = $image_tmp->getClientOriginalExtension();  
              //         //انشاء اسم صورة جديد 
              //          $imageName = rand(111,99999).'.'.$extension; 
              //          $imagePath = 'admin/images/proofs/'.$imageName;
              //         //رفع الصورة 
              //         Image::make($image_tmp)->save($imagePath);
              //       }
              //  }else if(!empty($data['current_address_proof'])){
              //     $imageName = $data['current_address_proof'];
              //  }else{
              //     $imageName = "";
              //  }
      
  
             
              //تحديث في جدول vendors_business_details
              VendorsBusinessDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->update
              (['shop_name'=>$data['shop_name'], 'shop_mobile'=>$data['shop_mobile'], 'shop_address'=>$data['shop_address']
              , 'shop_city'=>$data['shop_city'], 'shop_state'=>$data['shop_state'], 'shop_country'=>$data['shop_country']
              , 'shop_pincode'=>$data['shop_pincode'] , 'business_license_number'=>$data['business_license_number']
               , 'gst_number'=>$data['gst_number'] , 'pan_number'=>$data['pan_number']
               , 'address_proof'=>$data['address_proof'] , //'address_proof_image'=>$imageName
             ]);
               return redirect()->back()->with('success_message','Vendor details
                has been updated successfully!');
  
            }
            $vendorDetails = VendorsBusinessDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->first()->toArray();
           // dd($vendorDetails);
        }else if($slug=="bank"){
            Session::put('page','update_bank_details');

            if($request->isMethod('post')){
                $data = $request->all();
                 // echo "<pre>"; print_r($data); die;

                  $rules = [
                    'account_holder_name' => 'required|regex:/^[\pL\s\-]+$/u',
                    'bank_name' => 'required',
                    'account_number' => 'required|numeric',
                    'bank_ifsc_code' => 'required',
              ];
  
              $this->validate($request,$rules);
  
              //تحديث في جدول vendors_bank_details
              VendorsBankDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->update
              (['account_holder_name'=>$data['account_holder_name'], 'bank_name'=>$data['bank_name'], 'account_number'=>$data['account_number']
              , 'bank_ifsc_code'=>$data['bank_ifsc_code']
             ]);
               return redirect()->back()->with('success_message','Vendor details
                has been updated successfully!');
  
            }
            $vendorDetails = VendorsBankDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->first()->toArray();   
        }
        return view('admin.settings.update_vendor_details')->with(compact('slug','vendorDetails'));

    }

    public function login(Request $request){
        // echo $password = Hash::make('123456');die;
        

      if($request->isMethod('post')){
          $data = $request->all();
        //   echo "<pre>";print_r($data);die;

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);
        
          if(Auth::guard('admin')->attempt(['email'=>$data['email'],'password'=>$data['password'],
          'status'=>1])){
               return redirect('admin/dashboard');
        } else{
            return redirect()->back()->with('error_message','Invalid Email or Password');
        }
      }
        return view('admin.login'); 
    }

    public function admins($type=null){
    $admins = Admin::query();
    if(!empty($type)){
        $admins = $admins->where('type',$type);
        $title = ucfirst($type)."s";
        Session::put('page','view_'.strtolower($title));
    }else{
        $title = "All Admins/Subadmins/Vendors";
        Session::put('page','view_all');

    }
    $admins =$admins->get()->toArray();
    // dd($admins);
    return view('admin.admins.admins')->with(compact('admins','title'));
    }

    public function viewVendorDetails($id){
       $vendorDetails = Admin::with('vendorPersonal','vendorBusiness','vendorBank')
       ->where('id',$id)->first();
       $vendorDetails = json_decode(json_encode($vendorDetails),true);
    //    dd($vendorDetails);
       return view('admin.admins.view_vendor_details')->with(compact('vendorDetails'));
    }
    public function updateAdminStatus(Request $request){
     if($request->ajax()){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        if($data['status']=="Active"){
            $status = 0;
        }else{
            $status = 1;
        }
        Admin::where('id',$data['admin_id'])->update(['status'=>$status]);
        // return response()->json(['status'=>$status,'admin_id'=>$data['admin_id']]);
        $response = ['status' => $status, 'admin_id' => $data['admin_id']];
        return response()->json($response, 200);
     }
    }


    public function logout(){
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }
}
