<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

    protected $data = [
        array('id' => '1', 'category_code' => '4yp', 'title' => '4th Year Research Project', 'type' => 'COURSE', 'description' => '3rd year unified project', 'cover_image' => 'https://nuwanj.github.io/ce-projects-data-repository/data/categories/fyp/cover_page.jpg', 'thumb_image' => 'https://nuwanj.github.io/ce-projects-data-repository/data/categories/fyp/thumbnail.jpg', 'filters' => '[{"filter": "^e\\\\d{2}-4yp-", "organization": "cepdnaclk"}, {"filter": "^e\\\\d{2}-fyp-", "organization": "cepdnaclk"}]', 'contact' => 'lecturer.name@eng.pdn.ac.lk', 'created_at' => '2020-11-19 19:12:52', 'updated_at' => '2020-11-19 19:12:52'),
        array('id' => '2', 'category_code' => '3yp', 'title' => 'Embedded Systems Projects', 'type' => 'COURSE', 'description' => '3rd year embedded systems project which is a combination of CO321, CO324 and CO325 courses', 'cover_image' => 'https://nuwanj.github.io/ce-projects-data-repository/data/categories/unified/cover_page.jpg', 'thumb_image' => 'https://nuwanj.github.io/ce-projects-data-repository/data/categories/unified/thumbnail.jpg', 'filters' => '[{"filter": "^e\\\\d{2}-3yp-", "organization": "cepdnaclk"}, {"filter": "^e\\\\d{2}-unified-", "organization": "cepdnaclk"}]', 'contact' => 'lecturer.name@eng.pdn.ac.lk', 'created_at' => '2020-11-19 19:12:54', 'updated_at' => '2020-11-19 19:12:54'),
        array('id' => '3', 'category_code' => 'swarm', 'title' => 'Swarm Robotics Project', 'type' => 'DEPARTMENT', 'description' => 'Swarm Robotics project of the department of Computer Engineering', 'cover_image' => 'https://nuwanj.github.io/ce-projects-data-repository/data/categories/swarm/cover_page.jpg', 'thumb_image' => 'https://nuwanj.github.io/ce-projects-data-repository/data/categories/swarm/thumbnail.jpg', 'filters' => '[{"filter": "^swarm-", "organization": "cepdnaclk"}]', 'contact' => 'lecturer.name@eng.pdn.ac.lk', 'created_at' => '2020-11-19 19:12:56', 'updated_at' => '2020-11-19 19:12:56')
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
