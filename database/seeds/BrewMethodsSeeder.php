<?php

use Illuminate\Database\Seeder;

class BrewMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $methods = ['Hario V60 Dripper', 'Chemex', 'Siphon', 'Kyoto Cold Brew', 'Clover', 'Espresso', 'Aeropress', 'French Press', 'Kalita Wave Dripper', 'Nitrous'];
        $now = now();
        $insert_data = [];

        foreach ($methods as $method) {
            $insert_data[] = ['method' => $method, 'created_at' => $now, 'updated_at' => $now];
        }

        \Illuminate\Support\Facades\DB::table('brew_methods')->insert($insert_data);
    }
}
