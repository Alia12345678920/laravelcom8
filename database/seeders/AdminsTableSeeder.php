<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRecords = [
            //كلمة السرر من 1 لا 6
            ['id'=>1,'name'=>'Super Admin', 'type'=>'superadmin','vendor_id'=>0,'mobile'=>'980000000',
            'email'=>'admin@admin.com','password'=>Hash::make('123456'),'image'=>'','status'=>1],
            ['id'=>2,'name'=>'John', 'type'=>'vendor','vendor_id'=>1,'mobile'=>'97000000000',
            'email'=>'John@admin.com','password'=>Hash::make('123456'),'image'=>'','status'=>0],

            // ['id'=>2,'name'=>'Admin', 'type'=>'admin','vendor_id'=>0,'mobile'=>'980000000',
            // 'email'=>'admin@admin.com','password'=>'','image'=>'','status'=>1],
        ];
        Admin::insert($adminRecords);
    }
}
