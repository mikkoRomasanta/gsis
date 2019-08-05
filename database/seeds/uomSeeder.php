<?php

use Illuminate\Database\Seeder;

class uomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $uoms = ['btl','can','gal','ltr','pair','pc','roll'];

        foreach($uoms as $uom){
            DB::table('uom')->insert([
                'uom' => $uom
            ]);
        }
    }
}
