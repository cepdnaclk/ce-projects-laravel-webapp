<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $data = [
        array('id' => '1','name' => 'Nuwan Jaliyagoda','email' => 'nuwanjaliyagoda@gmail.com','email_verified_at' => NULL,'password' => '$2y$10$t2Gg5d8zISFyP6zYrgDUCe05BxyqA6k6h9qdYEea06aEM9HnFujxi','remember_token' => NULL,'created_at' => '2020-11-07 13:53:59','updated_at' => '2020-11-07 13:53:59')
    ];


    public function run()
    {
        foreach ($this->data as $index => $dataRow) {
            $result = DB::table('users')->insert($dataRow);

            if (!$result) {
                $this->command->info("Insert failed at record $index.");
                return;
            }
        }
        $this->command->info('Inserted ' . count($this->data) . ' records.');
    }
}
