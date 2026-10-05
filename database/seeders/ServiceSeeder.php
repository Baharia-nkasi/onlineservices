<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name'=>'Maombi ya Chuo Kikuu','slug'=>'maombi-ya-chuo-kikuu','description'=>'Usaidizi wa maombi ya vyuo vikuu na maandalizi ya nyaraka.'],
            ['name'=>'Maombi ya Mkopo wa Elimu ya Juu','slug'=>'maombi-ya-mkopo-wa-elimu-ya-juu','description'=>'Usaidizi wa maombi ya mkopo wa elimu ya juu.'],
            ['name'=>'Huduma za NACTVET','slug'=>'huduma-za-nactvet','description'=>'Usaidizi wa huduma za NACTVET na maombi ya elimu ya ufundi.'],
            ['name'=>'Pasipoti ya Kielektroniki','slug'=>'pasipoti-ya-kielektroniki','description'=>'Usaidizi wa maandalizi ya maombi ya pasipoti.'],
            ['name'=>'TIN Number - TRA','slug'=>'tin-number-tra','description'=>'Usaidizi wa maombi na maandalizi ya taarifa za TIN.'],
            ['name'=>'Usajili wa Jina la Biashara - BRELA','slug'=>'usajili-wa-jina-la-biashara-brela','description'=>'Usaidizi wa usajili wa jina la biashara.'],
            ['name'=>'Usajili wa Kampuni - BRELA','slug'=>'usajili-wa-kampuni-brela','description'=>'Usaidizi wa maandalizi ya usajili wa kampuni.'],
            ['name'=>'Umiliki Halisi wa Kampuni','slug'=>'umiliki-halisi-wa-kampuni','description'=>'Usaidizi wa taarifa za beneficial ownership.'],
            ['name'=>'Leseni ya Udereva','slug'=>'leseni-ya-udereva','description'=>'Usaidizi wa maombi na nyaraka za leseni ya udereva.'],
            ['name'=>'Hati ya Tabia Njema','slug'=>'hati-ya-tabia-njema','description'=>'Usaidizi wa maombi ya hati ya tabia njema.'],
            ['name'=>'Huduma za NECTA','slug'=>'huduma-za-necta','description'=>'Usaidizi wa huduma za NECTA na vyeti.'],
            ['name'=>'Vyeti na Nakala za Matokeo ya Chuo','slug'=>'vyeti-na-nakala-za-matokeo-ya-chuo','description'=>'Usaidizi wa vyeti, transcripts na nakala za matokeo.'],
            ['name'=>'Maombi ya Ufadhili / Masomo','slug'=>'maombi-ya-ufadhili-masomo','description'=>'Usaidizi wa maombi ya scholarships na sponsorships.'],
            ['name'=>'eRITA - Cheti cha Kuzaliwa','slug'=>'erita-cheti-cha-kuzaliwa','description'=>'Usaidizi wa maombi ya cheti cha kuzaliwa.'],
            ['name'=>'eRITA - Cheti cha Kifo','slug'=>'erita-cheti-cha-kifo','description'=>'Usaidizi wa maombi ya cheti cha kifo.'],
            ['name'=>'eRITA - Uthibitisho wa Cheti','slug'=>'erita-uthibitisho-wa-cheti','description'=>'Usaidizi wa uthibitisho wa vyeti kupitia eRITA.'],
            ['name'=>'eRITA - Nakala / Cheti Kilichopotea','slug'=>'erita-nakala-cheti-kilichopotea','description'=>'Usaidizi wa nakala ya cheti kilichopotea au kuharibika.'],
            ['name'=>'eRITA - Marekebisho ya Taarifa','slug'=>'erita-marekebisho-ya-taarifa','description'=>'Usaidizi wa marekebisho ya taarifa za cheti.'],
            ['name'=>'Maombi ya Kazi','slug'=>'maombi-ya-kazi','description'=>'Usaidizi wa CV, cover letter na maombi ya kazi.'],
            ['name'=>'Visa','slug'=>'visa','description'=>'Usaidizi wa maandalizi ya maombi ya visa na nyaraka.'],
            ['name'=>'Kibali cha Makazi / Kazi','slug'=>'kibali-cha-makazi-kazi','description'=>'Usaidizi wa maandalizi ya maombi ya residence/work permit.'],
            ['name'=>'Kitambulisho cha Taifa - NIDA','slug'=>'kitambulisho-cha-taifa-nida','description'=>'Usaidizi wa maandalizi ya huduma za NIDA.'],
            ['name'=>'Uthibitisho wa Nyaraka','slug'=>'uthibitisho-wa-nyaraka','description'=>'Usaidizi wa maandalizi ya nyaraka kwa ajili ya uthibitisho.'],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => $service['slug']],
                [
                    'name' => $service['name'],
                    'description' => $service['description'],
                ]
            );
        }
    }
}
