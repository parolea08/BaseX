<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create entitlements so they can be assigned to users
        DB::table('entitlements')->insert([
          [
            'name' => 'Admin',
            'description' => 'This entitlement grants the user access to all
                              Admin functionalities like e.g. assigning the
                              Moderator entitlement to other users'
          ],
          [
            'name' => 'Moderator',
            'description' => 'This entitlement grants the user access to all
                              Moderator functionalities like e.g. creating a new
                              project or creating an editing session'
          ],
        ]);
    }
}
