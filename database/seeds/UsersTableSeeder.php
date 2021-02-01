<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Jonas Pereira',
            'email' => 'jonasplc@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        User::create([
            'name' => 'Áurea Nicacio',
            'email' => 'andradeaurea78@gmail.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
