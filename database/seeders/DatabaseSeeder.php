<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(9)->create();

        $admin = \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'is_active' => true,
            'password' => bcrypt('123456789'),
        ]);

        \App\Models\Section::factory(10)->create();

        \App\Models\Product::factory(10)->create();

        $this->call([PermissionRoleSeeder::class]);
        $admin->assignRole('admin');
        \App\Models\Invoice::factory(20)->create();
        \App\Models\InvoiceAttachment::factory(10)->create();
        // \App\Models\PaymentDetail::factory(10)->create();
    }
}
