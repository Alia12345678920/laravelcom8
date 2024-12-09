$(document).ready(function(){

    //call datatable class
    //new DataTable('#sections');
    $('#sections').DataTable();
    $('#categories').DataTable();
    $('#brands').DataTable();
    $('#products').DataTable();


    $(".nav-item").removeClass("active");
    $(".nav-link").removeClass("active");
// التحقق اذا كلمة سر الادمن صحيحة او لا
$("#current_password").keyup(function(){
    var current_password = $("#current_password").val();
    // alert(current_password);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:'post',
        url:'/admin/check-admin-password',
        data:{current_password:current_password},
        success:function(resp){
            if(resp=="false"){
                $("#check_password").html("<font color='red'>Current Password is Incorrect!</font>");
            }else if(resp=="true"){
                $("#check_password").html("<font color='green'>Current Password is Correct!</font>");
            }
        },error:function(){
            alert('Error');
        }
    });
})

   // تحديث حالة الادمن
   $(document).on("click",".updateAdminStatus",function(){
    var status = $(this).children("i").attr("status");
    var admin_id = $(this).attr("admin_id");
    alert("Status updated successfully");
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:'post',
        url:'/admin/update-admin-status',
        data:{status:status,admin_id:admin_id},
        // success: function(resp) {
        //     console.log(resp); // طباعة الاستجابة للتحقق منها
        //     alert("Status updated successfully: " + resp.status);
        // },
        // error: function(xhr, status, error) {
        //     console.error("Error details:", xhr, status, error);
        //     alert("Error: " + error);
        // }
        success:function(resp){
            // alert(resp);
            if(resp['status']==0){
                $("#admin-"+admin_id).html("<i style='font-size:30px' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
            }else  if(resp['status']==1){
                $("#admin-"+admin_id).html("<i style='font-size:30px' class='mdi mdi-bookmark-check' status='Active'></i>");
            }
        }
        // , error:function(){
        //     alert("Error");
        // }
    })

});

   // تحديث حالة القسم 
   $(document).on("click",".updateSectionStatus",function(){
    var status = $(this).children("i").attr("status");
    var section_id = $(this).attr("section_id");
    alert("Status updated successfully");
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:'post',
        url:'/admin/update-section-status',
        data:{status:status,section_id:section_id},
        // success: function(resp) {
        //     console.log(resp); // طباعة الاستجابة للتحقق منها
        //     alert("Status updated successfully: " + resp.status);
        // },
        // error: function(xhr, status, error) {
        //     console.error("Error details:", xhr, status, error);
        //     alert("Error: " + error);
        // }
        success:function(resp){
            // alert(resp);
            if(resp['status']==0){
                $("#section-"+section_id).html("<i style='font-size:30px' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
            }else  if(resp['status']==1){
                $("#section-"+section_id).html("<i style='font-size:30px' class='mdi mdi-bookmark-check' status='Active'></i>");
            }
        }
        // , error:function(){
        //     alert("Error");
        // }
    })

});

  // تحديث حالة الفئة 
  $(document).on("click",".updateCategoryStatus",function(){
    var status = $(this).children("i").attr("status");
    var category_id = $(this).attr("category_id");
    alert("Status updated successfully");
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:'post',
        url:'/admin/update-category-status',
        data:{status:status,category_id:category_id},
        success:function(resp){
            // alert(resp);
            if(resp['status']==0){
                $("#category-"+category_id).html("<i style='font-size:30px' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
            }else  if(resp['status']==1){
                $("#category-"+category_id).html("<i style='font-size:30px' class='mdi mdi-bookmark-check' status='Active'></i>");
            }
        }
    })

});


// تحديث حالة العلامة التجارية 
$(document).on("click",".updateBrandStatus",function(){
    var status = $(this).children("i").attr("status");
    var brand_id = $(this).attr("brand_id");
    alert("Status updated successfully");
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:'post',
        url:'/admin/update-brand-status',
        data:{status:status,brand_id:brand_id},
        success:function(resp){
            // alert(resp);
            if(resp['status']==0){
                $("#brand-"+brand_id).html("<i style='font-size:30px' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
            }else  if(resp['status']==1){
                $("#brand-"+brand_id).html("<i style='font-size:30px' class='mdi mdi-bookmark-check' status='Active'></i>");
            }
        }
    })

});

// تحديث حالة المنتج 
$(document).on("click",".updateProductStatus",function(){
    var status = $(this).children("i").attr("status");
    var product_id = $(this).attr("product_id");
    alert("Status updated successfully");
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:'post',
        url:'/admin/update-product-status',
        data:{status:status,product_id:product_id},
        success:function(resp){
            // alert(resp);
            if(resp['status']==0){
                $("#product-"+product_id).html("<i style='font-size:30px' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
            }else  if(resp['status']==1){
                $("#product-"+product_id).html("<i style='font-size:30px' class='mdi mdi-bookmark-check' status='Active'></i>");
            }
        }
    })

});


// // Confirm Delete جافا سكريبت بسيطة 
// $(".confirmDelete").click(function(){
//     var title = $(this).attr("title");
//     if(confirm("Are you sure to delete this "+title+"?")){
//        return true; 
//     }else{
//         return false;
//     }    
// })

// Confirm Delete مكتبة سويت الرت 
$(".confirmDelete").click(function(){
    var module = $(this).attr("module");
    var moduleid = $(this).attr("moduleid");
    console.log("/admin/delete-" + module + "/" + moduleid);
    // var deleteUrl = $(this).attr("delete-url"); // الحصول على الرابط الصحيح من السمة
 Swal.fire({
  title: "Are you sure?",
  text: "You won't be able to revert this!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Yes, delete it!"
}).then((result) => {
  if (result.isConfirmed) {
    Swal.fire(
        'Deleted!',
        'Your file has been deleted.',
        'success'
    //   title: "Deleted!",
    //   text: "Your file has been deleted.",
    //   icon: "success"
    )
    window.location = "/admin/delete-"+module+"/"+moduleid;
       // إعادة توجيه المتصفح للرابط الصحيح
    //    window.location = deleteUrl;
  }
})
})



// الحاق مستوى الفئات
$("#section_id").change(function(){
  var section_id = $(this).val();
  $.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type:'get',
    url:'/admin/append-categories-level',
    data:{section_id:section_id},
    success:function(resp){
        $("#appendCategoriesLevel").html(resp);
    },error:function(){
       alert("Error"); 
    }
  })
});

// اضافة\حذف سمات المنتجات  سكريبت
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div><div style="height:10px;"></div><input type="text" name="size[]" placeholder="Size" style="width: 120px;"/>&nbsp;<input type="text" name="sku[]" placeholder="SKU" style="width: 120px;"/>&nbsp;<input type="text" name="price[]" placeholder="Price" style="width: 120px;"/>&nbsp;<input type="text" name="stock[]" placeholder="Stock" style="width: 120px;"/>&nbsp; <a href="javascript:void(0);" class="remove_button">Remove</div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    // Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increase field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
        // else{
        //     alert('A maximum of '+maxField+' fields are allowed to be added. ');
        // }
    });
    
    // Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrease field counter
    });


// $(document).on("click", ".updateAdminStatus", function() {
//     console.log("Button clicked"); // للتأكد أن النقر يعمل
//     var status = $(this).children("i").attr("status");
//     var admin_id = $(this).attr("admin_id");
//     console.log("Status:", status, "Admin ID:", admin_id); // عرض القيم قبل الإرسال

//     $.ajax({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         type: 'post',
//         url: '/admin/update-admin-status',
//         data: { status: status, admin_id: admin_id },
//         success: function(resp) {
//             console.log("Response:", resp); // عرض الرد في وحدة التحكم
//             alert("Status updated successfully");
//         },
//         error: function(err) {
//             console.error("Error:", err); // عرض الخطأ في وحدة التحكم
//             alert("Error occurred while updating status");
//         }
//     });
// });

});