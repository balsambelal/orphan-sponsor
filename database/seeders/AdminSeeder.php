<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'مدير المشروع',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'), // ضع كلمة مرور قوية
        ]);
    }
}
