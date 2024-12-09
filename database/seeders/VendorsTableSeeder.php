<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendorRecords = [
             ['id'=>1, 'name'=>'John', 'address'=>'CP-112', 'city'=>'New Delhi',
              'state'=>'Delhi', 'country'=>'India', 'pincode'=>'110001','mobile'=>'97000000000',
              'email'=>'John@admin.com', 'status'=>0],
        ];
        Vendor::insert($vendorRecords);

    }
}
