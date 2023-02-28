<?php

namespace Database\Seeders;

use App\Models\Threads\Type;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class BaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            [
                'id'=> Str::uuid()->toString(),
                'code'=> 'planning-recruitment',
                'name'=> 'Planning & Recruitment',
                'color'=> '#14ACB7'
            ],
            [
                'id'=> Str::uuid()->toString(),
                'code'=> 'hr-administration',
                'name'=> 'HR Administration',
                'color'=> '#9B7DFC'
            ],
            [
                'id'=> Str::uuid()->toString(),
                'code'=> 'perfomance-development',
                'name'=> 'Perfomance & Development',
                'color'=> '#F96342'
            ],
            [
                'id'=> Str::uuid()->toString(),
                'code'=> 'comben',
                'name'=> 'Comben',
                'color'=> '#0B8EE5'
            ],
            [
                'id'=> Str::uuid()->toString(),
                'code'=> 'tax-legal',
                'name'=> 'Tax & Legal',
                'color'=> '#FEAC37'
            ],
            [
                'id'=> Str::uuid()->toString(),
                'code'=> 'organization-development',
                'name'=> 'Organization Development',
                'color'=> '#9CCA4A'
            ],
            [
                'id'=> Str::uuid()->toString(),
                'code'=> 'hr-digital',
                'name'=> 'HR Digital',
                'color'=> '#2344CB'
            ],
            [
                'id'=> Str::uuid()->toString(),
                'code'=> 'career-succession',
                'name'=> 'Career & Succession',
                'color'=> '#FDCD25'
            ],
        ];

        Type::insert($datas);

        User::create([
            'id'      => '27ac0aa4-1ffc-475b-ae83-e55a0d17e9a4',
            'name'    => 'Dwi Sulistyo',
            'email'   => 'dwislstyn@gmail.com',
            'role_id' => '389963c2-8abf-461a-b2fc-cdf989679cc1',
            'password'=> Hash::make('password'),
            'login_by'=> 'konven',
            'is_verify'=> '1',
            'is_active'=> '1'
        ]);
    }
}
