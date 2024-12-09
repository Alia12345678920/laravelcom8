<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Section;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductsAttribute;
use Auth;
use Image;
// use Intervention\Image\Facades\Image;

class ProductsController extends Controller
{
    public function products(){
       // Session::put('page','sections');
        $products = Product::with([
        'section'=>function($query){$query->select('id','name');},
        'category'=>function($query){$query->select('id','category_name');}
        ])->get()->toArray();
        //  dd($products);
        return view('admin.products.products')->with(compact('products'));
    }
    public function updateProductStatus(Request $request){
        if($request->ajax()){
           $data = $request->all();
           // echo "<pre>"; print_r($data); die;
           if($data['status']=="Active"){
               $status = 0;
           }else{
               $status = 1;
           }
           Product::where('id',$data['product_id'])->update(['status'=>$status]);
        //    return response()->json(['status' => $status, 'product_id' => $data['product_id']]);
           $response = ['status' => $status, 'product_id' => $data['product_id']];
           return response()->json($response, 200);
        }
       }
       public function deleteProduct($id){
        //حذف منتج
        Product::where('id',$id)->delete();
        $message = "Product has been deleted successfully!"; 
        return redirect()->back()->with('success_message',$message);
  }

  public function addEditProduct(Request $request, $id=null){
    if($id==""){
      //اضافة منتج
      $title = "Add Product";
      $product = new Product;
      $message = "Product added successfully!";
         } else{
        //تعديل منتج
        $title = "Edit Product";
        $product = Product::find($id);
        // dd($product);
        // echo "<pre>"; print_r($product); die;
        $message = "Product updated successfully!";
        }
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die; 

            $rules = [
                'category_id' => 'required',
                'product_name' =>'required|regex:/^[\pL\s\-]+$/u',
                'product_code' =>'required',
                'product_price' =>'required|numeric',
                'product_color' =>'required|regex:/^[\pL\s\-]+$/u',    
          ];
          $this->validate($request,$rules);

        //   // Upload Product Image after Resize small: 250x250 medium: 500x500 large: 1000x1000

        // if($request->hasFile('product_image')){
        //     $image_tmp = $request->file('product_image');            
        //     if($image_tmp->isValid()){          
        //     // Get Image Extension          
        //     $extension = $image_tmp->getClientOriginalExtension();          
        //     // Generate New Image Name           
        //     $imageName = rand(111,99999).'.'.$extension;           
        //     $largeImagePath = 'front/images/product_images/large/'.$imageName;
        //     $mediumImagePath = 'front/images/product_images/medium/'.$imageName;
        //     $smallImagePath = 'front/images/product_images/small/'.$imageName;          
        //     // Upload the Large, Medium and Small Images after Resize           
        //     Image::make($image_tmp)->resize(1000,1000)->save($largeImagePath);           
        //     Image::make($image_tmp)->resize(500,500)->save($mediumImagePath);           
        //     Image::make($image_tmp)->resize(250,250)->save($smallImagePath);         
        //     // Insert Image Name in products table      
        //     $product->product_image = $imageName;
            
        //     }
        //     }

        // Upload Product Image without Resize and Save in Single Folder
if($request->hasFile('product_image')){
  $image_tmp = $request->file('product_image');            
  if($image_tmp->isValid()){          
      // Get Image Extension          
      $extension = $image_tmp->getClientOriginalExtension();          
      // Generate New Image Name           
      $imageName = rand(111,99999).'.'.$extension;           
      $destinationPath = 'front/images/product_images/';  

      // Create Directory if not exists
      if (!file_exists($destinationPath)) {
          mkdir($destinationPath, 0755, true);
      }

      // Move Uploaded Image to Destination
      $image_tmp->move($destinationPath, $imageName);

      // Insert Image Name in products table      
      $product->product_image = $imageName;
  }
}

        //رفع فديو المنتج 
        if($request->hasFile('product_video')){
          $video_tmp = $request->file('product_video');
          if($video_tmp->isValid()){
            //رفع الفديو في مجلد الفديوهات 
            $extension = $video_tmp->getClientOriginalExtension();
            $videoName = rand(111,99999).'.'.$extension; 
            $videoPath = 'front/videos/product_videos/';
            $video_tmp->move($videoPath,$videoName);
            // ادخال اسم الفديو الى جدول المنتجات
            $product->product_video = $videoName;

          }
        }
        
          // احفظ تفاصيل المنتج في جدول المنتجات
          $categoryDetails = Category::find($data['category_id']);
          $product->section_id = $categoryDetails['section_id'];
          $product->category_id = $data['category_id'];
          $product->brand_id = $data['brand_id'];

          $adminType = Auth::guard('admin')->user()->type;
          $vendor_id = Auth::guard('admin')->user()->vendor_id;
          $admin_id = Auth::guard('admin')->user()->id;

          $product->admin_type = $adminType;
          $product->admin_id = $admin_id;
          if($adminType=="vendor"){
            $product->vendor_id = $vendor_id;

          }else{
            $product->vendor_id = 0;
          }

          $product->product_name = $data['product_name'];
          $product->product_code = $data['product_code'];
          $product->product_color = $data['product_color'];
          $product->product_price = $data['product_price'];
          $product->product_discount = $data['product_discount'];
          $product->product_weight = $data['product_weight'];
          $product->description = $data['description'];
          $product->meta_title = $data['meta_title'];
          $product->meta_description = $data['meta_description'];
          $product->meta_keywords = $data['meta_keywords'];
          if(!empty($data['is_featured'])) {
            $product->is_featured = $data['is_featured'];
            }else{    
            $product->is_featured = "No";
            }
            $product->status = 1;
            $product->save();
            return redirect('admin/products')->with('success_message',$message);


        }
        // الحصول على الاقسام مع الفئات و الفئات الفرعية 
        $categories = Section::with('categories')->get()->toArray();
        // dd($categories);
        // الحصول على جميع العلامات التجارية
        $brands = Brand::where('status',1)->get()->toArray();

        return view('admin.products.add_edit_product')->with(compact('title','categories','brands','product'));

  }
  
  public function deleteProductImage($id) {
    // التحقق من وجود المنتج
    $productImage = Product::select('product_image')->where('id', $id)->first();
    if (!$productImage) {
        // dd("Product not found for ID: " . $id);
    }
    // التحقق من المسار
    $image_path = 'front/images/product_images/';
    // dd($image_path . $productImage->product_image);
    // حذف الصورة
    if (!empty($productImage->product_image) && file_exists($image_path . $productImage->product_image)) { 
        unlink($image_path . $productImage->product_image);
    }
    // تحديث قاعدة البيانات
    Product::where('id', $id)->update(['product_image' => '']);    
    $message = "Product Image has been deleted successfully!";    
    return redirect()->back()->with('success_message', $message);
}

public function deleteProductVideo($id) {
  // التحقق من وجود المنتج
  $productVideo = Product::select('product_video')->where('id', $id)->first();

  // التحقق من المسار
  $video_path = 'front/videos/product_videos/';

  // حذف الفديو
  if (!empty($productVideo->product_video) && file_exists($video_path . $productVideo->product_video)) { 
      unlink($video_path . $productVideo->product_video);
  }
  // تحديث قاعدة البيانات
  Product::where('id', $id)->update(['product_video' => '']);    
  $message = "Product Video has been deleted successfully!";    
  return redirect()->back()->with('success_message', $message);
}

  public function addAttributes(Request $request, $id) {
    $product = Product::select('id','product_name','product_code', 'product_color','product_price'
    ,'product_image')->with('attributes')->find($id);
    
    // dd($product);
   if($request->isMethod('post')){
    $data = $request->all();
    // echo "<pre>"; print_r($data); die;

    foreach ($data['sku'] as $key => $value) {
      if(!empty($value)) {  

        // SKU duplicate check
        // التحقق من تكرار SKU
        $skuCount = ProductsAttribute::where('sku', $value)->count();
        if ($skuCount>0) {
        return redirect()->back()->with('error_message', 'SKU already exists! Please add another SKU!');
        }
        // Size duplicate check
        // التحقق من الحجم المكرر
        $sizeCount = ProductsAttribute::where('product_id', $id)->where('size', $data['size'][$key])->count();
        if ($sizeCount > 0) {
            return redirect()->back()->with('error_message', 'Size already exists! Please add another Size!');
}
      $attribute = new ProductsAttribute;
      $attribute->product_id = $id;
      $attribute->sku= $value;   
      $attribute->size = $data['size'][$key];   
      $attribute->price = $data['price'][$key];
       $attribute->stock = $data['stock'][$key];
      $attribute->status = 1;
      $attribute->save();
      }  
      }
      
      return redirect()->back()->with('success_message', 'Product Attributes has been added successfully!');
      
      
   }


   return view('admin.attributes.add_edit_attributes')->with(compact('product'));
  }

}