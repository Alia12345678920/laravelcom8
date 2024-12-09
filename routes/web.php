`<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function(){
     // Admin Login Route without admin group
     Route::match(['get','post'],'login','AdminController@login');
     Route::group(['middleware'=>['admin']],function(){
         // Admin dashboard Route without admin group
         //دخلناه داخل الميدل وير عشان مايقدر يوصل للداش بورد بدون تسجيل دخول
     Route::get('dashboard','AdminController@dashboard');

     // تحديث كلمة سر الادمن
     Route::match(['get','post'],'update-admin-password','AdminController@updateAdminPassword');

      // التحقق كلمة سر الادمن
      Route::post('check-admin-password','AdminController@checkAdminPassword');

      // تحديث تفاصيل الادمن
      Route::match(['get','post'],'update-admin-details','AdminController@updateAdminDetails');

      // تحديث تفاصيل البائع
      Route::match(['get','post'],'update-vendor-details/{slug}','AdminController@updateVendorDetails');

      //عرض الادمن \ الادمن الفرعي \ البائعين
      Route::get('admins/{type?}','AdminController@admins');

      // عرض تفاصيل البائع
      Route::get('view-vendor-details/{id}','AdminController@viewVendorDetails');

      // تحديث حالة الادمن
      Route::post('update-admin-status','AdminController@updateAdminStatus');


     //Admin Logout
     Route::get('logout','AdminController@logout');

    //sections اقسام
    Route::get('sections','SectionController@sections');
    Route::post('update-section-status','SectionController@updateSectionStatus');
    Route::get('delete-section/{id}','SectionController@deleteSection');
    //  Route::get('admin/delete-section/{id}', 'SectionController@deleteSection')->name('delete.section');
    //  Route::match(['get','post'],'admin/add-edit-section/{id?}','SectionController@addEditSection');
   // Route::match(['get', 'post'], 'admin/add-edit-section/{id?}', 'SectionController@addEditSection')->name('admin.add-edit-section');
   Route::match(['get','post'],'add-edit-section/{id?}','SectionController@addEditSection');


    // علامات تجارية
    Route::get('brands','BrandController@brands');
    Route::post('update-brand-status','BrandController@updateBrandStatus');
    Route::get('delete-brand/{id}','BrandController@deleteBrand');
    Route::match(['get','post'],'add-edit-brand/{id?}','BrandController@addEditBrand');

   // فئات
   Route::get('categories','CategoryController@categories');
   Route::post('update-category-status','CategoryController@updateCategoryStatus');
   Route::match(['get','post'],'add-edit-category/{id?}','CategoryController@addEditCategory');
   Route::get('append-categories-level','CategoryController@appendCategoryLevel');
   Route::get('delete-category/{id}','CategoryController@deleteCategory');
   Route::get('delete-category-image/{id}','CategoryController@deleteCategoryImage');

   // المنتجات
   Route::get('products','ProductsController@products');
   Route::post('update-product-status','ProductsController@updateProductStatus');
   Route::get('delete-product/{id}','ProductsController@deleteProduct');
   Route::match(['get','post'],'add-edit-product/{id?}','ProductsController@addEditProduct');
   Route::get('delete-product-image/{id}','ProductsController@deleteProductImage');
   Route::get('delete-product-video/{id}','ProductsController@deleteProductVideo');

   //السمات 
   Route::match(['get','post'],'add-edit-attributes/{id}','ProductsController@addAttributes');

     });

});

