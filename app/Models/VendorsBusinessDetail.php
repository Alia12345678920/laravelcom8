<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorsBusinessDetail extends Model
{
    use HasFactory;

    protected $table = 'vendors_business_details'; // تأكد من أن الاسم مطابق تمامًا لاسم الجدول في قاعدة البيانات
}