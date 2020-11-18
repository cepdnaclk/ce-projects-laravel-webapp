<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

    protected $data = [
        array('id' => '1', 'category_code' => '4yp', 'type' => 'COURSE', 'title' => '4th Year Research Project', 'description' => '3rd year unified project', 'cover_image' => 'https://nuwanj.github.io/ce-projects-data-repository/data/categories/fyp/cover_page.jpg', 'thumb_image' => 'https://nuwanj.github.io/ce-projects-data-repository/data/categories/fyp/thumbnail.jpg', 'filters' => '[{"filter": "^e\\\\d{2}-4yp-", "organization": "cepdnaclk"}, {"filter": "^e\\\\d{2}-fyp-", "organization": "cepdnaclk"}]', 'contact' => 'lecturer.name@eng.pdn.ac.lk', 'created_at' => '2020-11-18 13:04:05', 'updated_at' => '2020-11-18 13:04:05'),
        array('id' => '2', 'category_code' => '3yp', 'type' => 'COURSE', 'title' => 'Embedded Systems Projects', 'description' => '3rd year embedded systems project which is a combination of CO321, CO324 and CO325 courses', 'cover_image' => 'https://nuwanj.github.io/ce-projects-data-repository/data/categories/unified/cover_page.jpg', 'thumb_image' => 'https://nuwanj.github.io/ce-projects-data-repository/data/categories/unified/thumbnail.jpg', 'filters' => '[{"filter": "^e\\\\d{2}-3yp-", "organization": "cepdnaclk"}, {"filter": "^e\\\\d{2}-unified-", "organization": "cepdnaclk"}]', 'contact' => 'lecturer.name@eng.pdn.ac.lk', 'created_at' => '2020-11-18 13:04:05', 'updated_at' => '2020-11-18 13:04:05'),
        array('id' => '3', 'category_code' => 'swarm', 'type' => 'DEPARTMENT', 'title' => 'Swarm Robotics Projects', 'description' => '', 'cover_image' => 'https://nuwanj.github.io/ce-projects-data-repository/data/categories/swarm-robotics/cover_page.jpg', 'thumb_image' => 'https://nuwanj.github.io/ce-projects-data-repository/data/categories/swarm-robotics/thumbnail.jpg', 'filters' => '[{"filter": "^swarm-", "organization": "cepdnaclk"}]', 'contact' => 'lecturer.name@eng.pdn.ac.lk', 'created_at' => '2020-11-18 13:04:05', 'updated_at' => '2020-11-18 13:04:05')
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
