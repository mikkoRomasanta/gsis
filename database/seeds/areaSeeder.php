<?php

use Illuminate\Database\Seeder;

class areaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $areas = [
            "Admin Female Cr & Clinic Cr",
            "Admin Male Cr",
            "Canteen Female Cr",
            "Canteen Male Cr",
            "Clinic",
            "Driver's Quarter",
            "Employee's Entrance Hallway/Male Locker",
            "Female Locker",
            "Hallway",
            "Kajima/Gate1/Gate2 Cr",
            "Pantry",
            "Production Female Cr",
            "Production Male Cr",
            "Scrap",
            "Utility Storage/JDI/MR",
            "Vip Female/Male/Pwd Cr",
            "Warehouse Female & Male Cr",
            "Weekly Activity"
        ];

        foreach($areas as $area){
            DB::table('areas')->insert([
                'area_name' => $area
            ]);
        }
    }
}
