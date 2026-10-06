<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name'=>'Maombi ya Chuo Kikuu','slug'=>'maombi-ya-chuo-kikuu','description'=>'Usaidizi wa maombi ya vyuo vikuu na maandalizi ya nyaraka.','image_url'=>'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Maombi ya Mkopo wa Elimu ya Juu','slug'=>'maombi-ya-mkopo-wa-elimu-ya-juu','description'=>'Usaidizi wa maombi ya mkopo wa elimu ya juu.','image_url'=>'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Huduma za NACTVET','slug'=>'huduma-za-nactvet','description'=>'Usaidizi wa huduma za NACTVET na maombi ya elimu ya ufundi.','image_url'=>'https://images.unsplash.com/photo-1516321497487-e288fb19713f?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Pasipoti ya Kielektroniki','slug'=>'pasipoti-ya-kielektroniki','description'=>'Usaidizi wa maandalizi ya maombi ya pasipoti.','image_url'=>'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'TIN Number - TRA','slug'=>'tin-number-tra','description'=>'Usaidizi wa maombi na maandalizi ya taarifa za TIN.','image_url'=>'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Usajili wa Jina la Biashara - BRELA','slug'=>'usajili-wa-jina-la-biashara-brela','description'=>'Usaidizi wa usajili wa jina la biashara.','image_url'=>'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Usajili wa Kampuni - BRELA','slug'=>'usajili-wa-kampuni-brela','description'=>'Usaidizi wa maandalizi ya usajili wa kampuni.','image_url'=>'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Umiliki Halisi wa Kampuni','slug'=>'umiliki-halisi-wa-kampuni','description'=>'Usaidizi wa taarifa za beneficial ownership.','image_url'=>'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Leseni ya Udereva','slug'=>'leseni-ya-udereva','description'=>'Usaidizi wa maombi na nyaraka za leseni ya udereva.','image_url'=>'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Hati ya Tabia Njema','slug'=>'hati-ya-tabia-njema','description'=>'Usaidizi wa maombi ya hati ya tabia njema.','image_url'=>'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Huduma za NECTA','slug'=>'huduma-za-necta','description'=>'Usaidizi wa huduma za NECTA na vyeti.','image_url'=>'https://images.unsplash.com/photo-1529070538774-1843cb3265df?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Vyeti na Nakala za Matokeo ya Chuo','slug'=>'vyeti-na-nakala-za-matokeo-ya-chuo','description'=>'Usaidizi wa vyeti, transcripts na nakala za matokeo.','image_url'=>'https://images.unsplash.com/photo-1606761568499-6d2451b23c66?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Maombi ya Ufadhili / Masomo','slug'=>'maombi-ya-ufadhili-masomo','description'=>'Usaidizi wa maombi ya scholarships na sponsorships.','image_url'=>'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'eRITA - Cheti cha Kuzaliwa','slug'=>'erita-cheti-cha-kuzaliwa','description'=>'Usaidizi wa maombi ya cheti cha kuzaliwa.','image_url'=>'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'eRITA - Cheti cha Kifo','slug'=>'erita-cheti-cha-kifo','description'=>'Usaidizi wa maombi ya cheti cha kifo.','image_url'=>'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'eRITA - Nakala / Cheti Kilichopotea','slug'=>'erita-nakala-cheti-kilichopotea','description'=>'Usaidizi wa nakala ya cheti kilichopotea au kuharibika.','image_url'=>'https://images.unsplash.com/photo-1554224154-26032ffc0d07?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'eRITA - Marekebisho ya Taarifa','slug'=>'erita-marekebisho-ya-taarifa','description'=>'Usaidizi wa marekebisho ya taarifa za cheti.','image_url'=>'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Maombi ya Kazi','slug'=>'maombi-ya-kazi','description'=>'Usaidizi wa CV, cover letter na maombi ya kazi.','image_url'=>'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Visa','slug'=>'visa','description'=>'Usaidizi wa maandalizi ya maombi ya visa na nyaraka.','image_url'=>'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Kibali cha Makazi / Kazi','slug'=>'kibali-cha-makazi-kazi','description'=>'Usaidizi wa maandalizi ya maombi ya residence/work permit.','image_url'=>'https://images.unsplash.com/photo-1494412651409-8963ce7935a7?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Kitambulisho cha Taifa - NIDA','slug'=>'kitambulisho-cha-taifa-nida','description'=>'Usaidizi wa maandalizi ya huduma za NIDA.','image_url'=>'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1400&q=85'],
            ['name'=>'Uthibitisho wa Nyaraka','slug'=>'uthibitisho-wa-nyaraka','description'=>'Usaidizi wa maandalizi ya nyaraka kwa ajili ya uthibitisho.','image_url'=>'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1400&q=85'],
        ];

        foreach ($services as $service) {
            // Seed defaults only when the service is new. Existing admin edits
            // (name, description, fees, active state, etc.) must survive deploys.
            $record = Service::firstOrCreate(
                ['slug' => $service['slug']],
                [
                    'name' => $service['name'],
                    'description' => $service['description'],
                    'image_url' => $service['image_url'],
                    'government_fee' => 0,
                    'service_fee' => 0,
                    'is_active' => true,
                ]
            );

            if (blank($record->image_url) && filled($service['image_url'])) {
                $record->update(['image_url' => $service['image_url']]);
            }
        }
    }
}
