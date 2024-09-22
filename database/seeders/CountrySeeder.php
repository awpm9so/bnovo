<?php

namespace Database\Seeders;

use App\Imports\CountryImport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Excel::import(new CountryImport(),  'storage/app/countries.xlsx');
    }
}
