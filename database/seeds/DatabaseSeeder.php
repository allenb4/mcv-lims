<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountriesSeeder::class,
            TimezoneSeeder::class,
            BranchSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            CurrencySeeder::class,
            SettingSeeder::class,
            CategoriesSeeder::class,
            TestsSeeder::class,
            CulturesSeeder::class,
            CultureOptionsSeeder::class,
            PatientSeeder::class,
            LanguageSeeder::class,
        ]);

        \DB::table('activity_log')->truncate();
    }
}
