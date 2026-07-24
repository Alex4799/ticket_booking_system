<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\User;
use App\Models\WebsiteInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Alex',
            'email'=>'mr.alex4799@gmail.com',
            'password'=>Hash::make('alex@0912'),
            'position'=>'developer',
            'role'=>'admin',
        ]);
        User::create([
            'name'=>'Admin',
            'email'=>'admin@alexlucifer.com',
            'password'=>Hash::make('admin0912'),
            'position'=>'CEO',
            'role'=>'admin',
        ]);

        WebsiteInfo::create([
            'name'=>'MCL Cinema',
            'contact'=>'<p>Phone1 - 09980730638</p>',
            'cover_letter'=>'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam quo aperiam praesentium quis ipsum eum at, vel similique obcaecati mollitia deserunt sapiente ab eveniet porro reprehenderit fugit doloremque ipsa illo.',
            'aboutus_letter'=>'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam quo aperiam praesentium quis ipsum eum at, vel similique obcaecati mollitia deserunt sapiente ab eveniet porro reprehenderit fugit doloremque ipsa illo.',
            'footer_letter'=>'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam quo aperiam praesentium quis ipsum eum at, vel similique obcaecati mollitia deserunt sapiente ab eveniet porro reprehenderit fugit doloremque ipsa illo.',
            'cover_image'=>[],
            'about_image'=>[]
        ]);

        Payment::create([
            'name'=>'Cash',
            'username'=>'Cinema',
            'number'=>'09980730638',
            'active'=>1,
        ]);
        Payment::create([
            'name'=>'Booking',
            'username'=>'Cinema',
            'number'=>'09980730638',
            'active'=>1,
        ]);
        Payment::create([
            'name'=>'UAB',
            'username'=>'Nanobots',
            'number'=>'09980730638',
            'active'=>1,
        ]);
    }
}
