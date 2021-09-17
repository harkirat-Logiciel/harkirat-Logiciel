<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('UserTableSeeder');
		DB::table('departments')->insert([
		[
			'department_name'=>'General Management'
		],
		[
			'department_name'=>'Business Strategies'
		],
		[
			'department_name'=>'Marketing Department'
		],
		[
			'department_name'=>'Operations Department'
		],
		[
			'department_name'=>'Finance Department'
		], 
		[
			'department_name'=>'Sales Department'
		], 
		[
			'department_name'=>'Human Resource Department'
		], 
		[
			'department_name'=>'Purchase Department'
		]                
		]); 
	

	}

}
