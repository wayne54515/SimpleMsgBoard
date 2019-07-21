<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = App\Models\User::create([
            'name' => 'SuperUser',
            'type' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make("password"),
            'color' => '#600000',
            'sex' => 'male'
        ]);

        DB::transaction(function () use ($admin) {
            $admin->save();
        });
    }
}
