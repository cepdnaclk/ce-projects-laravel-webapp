<?php

use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    protected $data = [];

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        foreach ($this->data as $index => $dataRow) {
            $result = DB::table('projects')->insert($dataRow);

            if (!$result) {
                $this->command->info("Insert failed at record $index.");
                return;
            }
        }
        $this->command->info('Inserted ' . count($this->data) . ' records.');
    }
}
