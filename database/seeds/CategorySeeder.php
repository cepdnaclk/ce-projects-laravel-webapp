<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

    protected $data = [
        array('id' => '1','category_code' => '4yp','title' => '4th Year Research Project','description' => '3rd year unified project','cover_image' => 'img_cover.jpg','thumb_image' => 'img_thumb.jpg','filters' => '["^e\\\\d{2}-4yp-", "^e\\\\d{2}-fyp-"]','contact' => 'lecturer.name@eng.pdn.ac.lk','created_at' => '2020-11-15 14:19:58','updated_at' => '2020-11-15 14:19:58'),
        array('id' => '2','category_code' => '3yp','title' => 'Unified Project','description' => '3rd year unified project','cover_image' => 'img_cover.jpg','thumb_image' => 'img_thumb.jpg','filters' => '["^e\\\\d{2}-3yp-", "^e\\\\d{2}-unified-"]','contact' => 'lecturer.name@eng.pdn.ac.lk','created_at' => '2020-11-15 14:19:58','updated_at' => '2020-11-15 14:19:58') ];

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
