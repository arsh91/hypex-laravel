<?php

use Illuminate\Database\Seeder;

class UserSizeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seed_array = array();

        for($i = 0; $i<=14; $i++){
        	$value = $i + 0.5;
        	$seed_array[] = array(
    		 	'size' => $value,
		 	 	'status' => '1',
            	'created_at' => Date('Y-m-d H:i:s'),
            	'updated_at' => Date('Y-m-d H:i:s'),
        	);

        	$value = $i + 1;
        	$seed_array[] = array(
    		 	'size' => $value,
		 	 	'status' => '1',
            	'created_at' => Date('Y-m-d H:i:s'),
            	'updated_at' => Date('Y-m-d H:i:s'),
        	);
        }

        DB::table('size_list')->insert($seed_array);
    }
}
