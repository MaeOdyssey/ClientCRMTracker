<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class TestClientsSeeder extends Seeder
{
    public function run()
    {
        $clients = [
            [
                'first_name' => 'Juno',
                'last_name' => 'Hex',
                'email' => 'juno.hex@nowhere.net',
                'phone' => '555-4040',
                'notes' => 'May or may not be a time traveler',
            ],
            [
                'first_name' => 'Faye',
                'last_name' => 'Velvet',
                'email' => 'faye@lushmail.com',
                'phone' => '555-7777',
                'notes' => 'Allergic to spreadsheets',
            ],
            [
                'first_name' => 'Tester',
                'last_name' => 'McTestFace',
                'email' => 'test@example.com',
                'phone' => '555-2020',
                'notes' => 'May or may not exist',
            ],
            [
                'first_name' => 'Nyx',
                'last_name' => 'Lucent',
                'email' => 'nyx@whispers.ai',
                'phone' => '555-1010',
                'notes' => 'Prefers quiet servers and candlelight CSS',
            ],
            [
                'first_name' => 'Nova',
                'last_name' => 'Vox',
                'email' => 'nova@orbit.com',
                'phone' => '555-3030',
                'notes' => 'Always humming synth tunes',
            ],
            [
                'first_name' => 'Bart',
                'last_name' => 'Boat',
                'email' => 'bboat@gmail.com',
                'phone' => '867-5309',
                'notes' => 'Has dreams of one day dreaming.',
            ],
        ];

        foreach ($clients as $data) {
            Client::create($data);
        }
    }
}
