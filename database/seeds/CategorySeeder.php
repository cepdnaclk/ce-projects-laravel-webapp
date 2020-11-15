<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

    protected $data = [
        array('id' => '1','category_code' => '3yp','title' => 'Unified Project','description' => '3rd year unified project','cover_image' => NULL,'thumb_image' => NULL,'filters' => '["^e\\\\d{2}-3yp-", "^e\\\\d{2}-unified-"]','contact' => 'isurun@eng.pdn.ac.lk','created_at' => '2020-11-15 11:53:53','updated_at' => '2020-11-15 11:53:53')
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        foreach ($this->data as $index => $dataRow) {
            $result = DB::table('categories')->insert($dataRow);

            if (!$result) {
                $this->command->info("Insert failed at record $index.");
                return;
            }
        }
        $this->command->info('Inserted ' . count($this->data) . ' records.');
    }
}
