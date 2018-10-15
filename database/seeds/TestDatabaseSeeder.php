<?php

use Illuminate\Database\Seeder;

/**
 * Class TestFigureSeeder
 */
class TestDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(\App\Models\User::class)->create();
        $figures = factory(\App\Models\Figure::class, 20)->create(['user_id' => $user->id]);
        foreach ($figures as $figure) {
            $count = rand(3, 6);
            for ($i = 0; $i < $count; $i++) {
                factory(\App\Models\Point::class)->create(['figure_id' => $figure->id, 'order' => $i]);
            }
        }
    }
}
