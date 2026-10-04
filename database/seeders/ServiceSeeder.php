<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Vyeti vya Kuzaliwa na Kifo',
                'slug' => 'vyeti-vya-kuzaliwa-na-kifo',
                'description' => 'Huduma za maombi ya vyeti vya kuzaliwa na kifo.',
            ],
            [
                'name' => 'Sekretarieti ya Ajira katika Utumishi wa Umma',
                'slug' => 'sekretarieti-ya-ajira',
                'description' => 'Huduma zinazohusiana na maombi ya ajira katika Utumishi wa Umma.',
            ],
            [
                'name' => 'Leseni za Udereva na Vyombo vya Moto (TRA)',
                'slug' => 'leseni-za-udereva-na-vyombo-vya-moto',
                'description' => 'Huduma zinazohusiana na leseni za udereva na vyombo vya moto.',
            ],
            [
                'name' => 'Usajili wa Biashara na Leseni (BRELA)',
                'slug' => 'usajili-wa-biashara-na-leseni',
                'description' => 'Huduma za usajili wa biashara na leseni.',
            ],
            [
                'name' => 'Bodi ya Mikopo ya Wanafunzi wa Elimu ya Juu (HESLB)',
                'slug' => 'heslb',
                'description' => 'Huduma za maombi na usaidizi wa huduma za HESLB.',
            ],
            [
                'name' => 'Pasipoti ya Kielektroniki',
                'slug' => 'pasipoti-ya-kielektroniki',
                'description' => 'Huduma za maombi ya pasipoti ya kielektroniki.',
            ],
            [
                'name' => 'Namba ya Mlipa Kodi (TIN)',
                'slug' => 'tin-number',
                'description' => 'Huduma za maombi ya TIN Number.',
            ],
            [
                'name' => 'Huduma za Ardhi na Makazi (ILMIS)',
                'slug' => 'huduma-za-ardhi-na-makazi',
                'description' => 'Huduma zinazohusiana na ardhi na makazi.',
            ],
            [
                'name' => 'Maombi ya Bima ya Afya (NHIF)',
                'slug' => 'bima-ya-afya-nhif',
                'description' => 'Huduma za maombi na usaidizi wa bima ya afya.',
            ],
            [
                'name' => 'Huduma za PSSSF / NSSF Portals',
                'slug' => 'psssf-nssf-portals',
                'description' => 'Huduma zinazohusiana na PSSSF na NSSF portals.',
            ],
            [
                'name' => 'Maombi ya Vyuo',
                'slug' => 'maombi-ya-vyuo',
                'description' => 'Huduma za maombi ya vyuo na usaidizi wa application.',
            ],
        ];

        foreach ($services as $service) {
            Service::create([
                ...$service,
                'government_fee' => 0,
                'service_fee' => 0,
                'is_active' => true,
            ]);
        }
    }
}