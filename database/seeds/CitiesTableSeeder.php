<?php

namespace Baloot\Database;

use Baloot\Models\City;
use Baloot\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::set('sluggable.method', 'str_to_slug');
        $insertedSlugs = [];
        $provinces = json_decode(file_get_contents(realpath(__DIR__.'/../../storage/cities.json')), true);
        foreach ($provinces as $province) {
            $tempModel = Province::create(['id' => $province['id'], 'name' => trim($province['name'])]);
            City::insert(array_map(function ($city) use ($tempModel, &$insertedSlugs) {
                $slug = str_to_slug(trim($city));
                while (in_array($slug, $insertedSlugs)) {
                    $slug .= '-';
                }
                $insertedSlugs[] = $slug;

                return ['province_id' => $tempModel->id, 'name' => trim($city), 'slug' => $slug];
            }, $province['cities']));
        }
    }
}
