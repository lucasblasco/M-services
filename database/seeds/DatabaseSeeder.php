<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UsersTableSeeder::class);
        $this->call(StudyLevelsTableSeeder::class);
        $this->call(JobsTableSeeder::class);
        $this->call(ProfessionsTableSeeder::class);
        $this->call(AccountsTableSeeder::class);
        $this->call(DocumentTypesTableSeeder::class);        
        $this->call(CountriesTableSeeder::class);
        $this->call(ProvincesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(AgeRangesTableSeeder::class);
        $this->call(EventFormatsTableSeeder::class);
        $this->call(AssistantActivitiesTableSeeder::class);
    }
}
