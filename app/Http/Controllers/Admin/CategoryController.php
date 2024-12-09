<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Category;
use Session;
use Image;

class CategoryController extends Controller
{
    public function categories(){
        Session::put('page','categories');
        $categories = Category::with(['section','parentcategory'])->get()->toArray();
        // dd($categories);
        return view('admin.categories.categories')->with(compact('categories'));
    }

    public function updateCategoryStatus(Request $request){
        if($request->ajax()){
           $data = $request->all();
           // echo "<pre>"; print_r($data); die;
           if($data['status']=="Active"){
               $status = 0;
           }else{
               $status = 1;
           }
           Category::where('id',$data['category_id'])->update(['status'=>$status]);
        //    return response()->json(['status' => $status, 'category_id' => $data['category_id']]);
           $response = ['status' => $status, 'category_id' => $data['category_id']];
           return response()->json($response, 200);
        }
       }

       public function addEditCategory(Request $request, $id=null){
        Session::put('page','categories');
          if($id==""){
            //اضافة فئة
            $title = "Add Category";
            $category = new Category;
            $getCategories = array();
            $message = "Category added successfully!";
          }else{
           //تعديل فئة
           $title = "Edit Category";
           $category = Category::find($id);
        //    echo "<pre>"; print_r($category['category_name']); die;
           $getCategories = Category::with('subcategories')->where(['parent_id'=>0,
        'section_id'=>$category['section_id']])->get();
           $message = "Category updated successfully!";
          }

         if($request->isMethod('post')){
            $data = $request->all();
        //    echo "<pre>"; print_r($data); die; 
        $rules = [
            'category_name' => 'required|regex:/^[\pL\s\-]+$/u',
            'section_id' => 'required',
            'url' => 'required',

      ];
      $this->validate($request,$rules);
//اعطاء قيمة للعمود في حال ترك فارغ لو رقم 
// او عمل "" اذا كان كلام او تغيره null  من فاعدة البيانات 
        if($data['category_discount']==""){
            $data['category_discount'] = 0;}
      
            //رفع صورة الفئة 
             if($request->hasFile('category_image')){
                 $image_tmp = $request->file('category_image');
                  if($image_tmp->isValid()){
                    // الحصول على ملحق الصورة
                     $extension = $image_tmp->getClientOriginalExtension();  
                    //انشاء اسم صورة جديد 
                     $imageName = rand(111,99999).'.'.$extension; 
                     $imagePath = 'front/images/category_images/'.$imageName;
                    //رفع الصورة 
                    Image::make($image_tmp)->save($imagePath);
                    $category->category_image = $imageName;
                  }
                }else{
                    $category->category_image = "";
             }

             $category->section_id = $data['section_id'];
             $category->parent_id = $data['parent_id'];
             $category->category_name = $data['category_name'];
             $category->category_discount = $data['category_discount'];
             $category->description = $data['description'];
             $category->url = $data['url'];       
             $category->meta_title = $data['meta_title'];
             $category->meta_description = $data['meta_description'];
             $category->meta_keywords = $data['meta_keywords'];
             $category->status = 1;
             $category->save();

             return redirect('admin/categories')->with('success_message',$message);

         }
              
          //الحصول على كل الاقسام 
          $getSections = Section::get()->toArray();
          return view('admin.categories.add_edit_category')->with(compact('title',
          'category','getSections','getCategories'));
       }

       public function appendCategoryLevel(Request $request){
            if($request->ajax()){
                $data = $request->all();
                $getCategories = Category::with('subcategories')->where(['parent_id'=>0,'section_id'
                =>$data['section_id']])->get()->toArray();
                return view('admin.categories.append_categories_level')->with(compact('getCategories'));
            }
       }

       public function deleteCategory($id){
             //حذف فئة
             Category::where('id',$id)->delete();
             $message = "Category has been deleted successfully!"; 
             return redirect()->back()->with('success_message',$message);
       }

       public function deleteCategoryImage($id){
        // الحصول على صورة الفئة
        $categoryImage = Category::select('category_image')->where('id'.$id)->first();

        // الحصول على مسار صورة الفئة
        $category_image_path = 'front/images/category_images/';

        // حذف صورة الفئة من الملف
        if(file_exists($category_image_path.$categoryImage->category_image)){
            unlink($category_image_path.$categoryImage->category_image);  }

        // حذف صورة الفئة من قاعدة البيانات 
        Category::where('id',$id)->update(['category_image'=>'']);
        $message = "Category Image has been deleted successfully!"; 
        return redirect()->back()->with('success_message',$message);
  }
}
