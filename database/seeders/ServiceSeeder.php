<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name'=>'Maombi ya Chuo Kikuu','slug'=>'maombi-ya-chuo-kikuu','description'=>'Usaidizi wa maombi ya vyuo vikuu na maandalizi ya nyaraka.','image_url'=>'https://static.africa-press.net/tanzania/sites/17/2023/08/sm_1691681820.20191.jpg'],
            ['name'=>'Maombi ya Mkopo wa Elimu ya Juu','slug'=>'maombi-ya-mkopo-wa-elimu-ya-juu','description'=>'Usaidizi wa maombi ya mkopo wa elimu ya juu.','image_url'=>'https://tanzania.un.org/sites/default/files/styles/hero_header_2xl_1x/public/2023-11/DIT40.jpg?itok=w3zAwUd1'],
            ['name'=>'Huduma za NACTVET','slug'=>'huduma-za-nactvet','description'=>'Usaidizi wa huduma za NACTVET na maombi ya elimu ya ufundi.','image_url'=>'https://www.moe.go.tz/sites/default/files/inline/images/Mageuzi%20ya%20elimu%20kupitia%20Sera%20na%20Mitaala%20mipya%20yamewawezesha%20wanafunzi%20kujiunga%20na%20Sekondari%20za%20%20%282%29.jpg'],
            ['name'=>'Pasipoti ya Kielektroniki','slug'=>'pasipoti-ya-kielektroniki','description'=>'Usaidizi wa maandalizi ya maombi ya pasipoti.','image_url'=>'https://static.wixstatic.com/media/182abf_c859c0e2128d4c3db724f076429b5152~mv2.png/v1/fill/w_1913%2Ch_972%2Cal_c/182abf_c859c0e2128d4c3db724f076429b5152~mv2.png'],
            ['name'=>'TIN Number - TRA','slug'=>'tin-number-tra','description'=>'Usaidizi wa maombi na maandalizi ya taarifa za TIN.','image_url'=>'https://media.zoomtz.com/2023/12/TRA-Taxpayer-Portal.png'],
            ['name'=>'Usajili wa Jina la Biashara - BRELA','slug'=>'usajili-wa-jina-la-biashara-brela','description'=>'Usaidizi wa usajili wa jina la biashara.','image_url'=>'https://cdn.brandfetch.io/idBWfiLFbX/w/1500/h/500/id2mFx7mbD.jpeg?c=1bxid64Mup7aczewSAYMX&t=1773079001784'],
            ['name'=>'Usajili wa Kampuni - BRELA','slug'=>'usajili-wa-kampuni-brela','description'=>'Usaidizi wa maandalizi ya usajili wa kampuni.','image_url'=>'https://cdn.brandfetch.io/idBWfiLFbX/w/1500/h/500/id2mFx7mbD.jpeg?c=1bxid64Mup7aczewSAYMX&t=1773079001784'],
            ['name'=>'Umiliki Halisi wa Kampuni','slug'=>'umiliki-halisi-wa-kampuni','description'=>'Usaidizi wa taarifa za beneficial ownership.','image_url'=>'https://media.licdn.com/dms/image/v2/D4D12AQGaQzWwtslTUg/article-cover_image-shrink_720_1280/B4DZXM5RoVHAAI-/0/1742899340444?e=2147483647&t=-NtCtwZ50IgxGaT9-GTxwlyac_RAROiIblfwpilW1yA&v=beta'],
            ['name'=>'Leseni ya Udereva','slug'=>'leseni-ya-udereva','description'=>'Usaidizi wa maombi na nyaraka za leseni ya udereva.','image_url'=>'https://media.zoomtz.com/2023/12/TRA-Taxpayer-Portal.png'],
            ['name'=>'Hati ya Tabia Njema','slug'=>'hati-ya-tabia-njema','description'=>'Usaidizi wa maombi ya hati ya tabia njema.','image_url'=>'https://commons.wikimedia.org/wiki/Special:FilePath/Tanzania%20Police%20Force.png'],
            ['name'=>'Huduma za NECTA','slug'=>'huduma-za-necta','description'=>'Usaidizi wa huduma za NECTA na vyeti.','image_url'=>'https://archangelsschools.ac.tz/assets/uploads/partner-26.jpg'],
            ['name'=>'Vyeti na Nakala za Matokeo ya Chuo','slug'=>'vyeti-na-nakala-za-matokeo-ya-chuo','description'=>'Usaidizi wa vyeti, transcripts na nakala za matokeo.','image_url'=>'https://image.slidesharecdn.com/universityacademiccertificateandtranscript-150205093904-conversion-gate01/85/university-academic-certificate-and-transcript-1-638.jpg'],
            ['name'=>'Maombi ya Ufadhili / Masomo','slug'=>'maombi-ya-ufadhili-masomo','description'=>'Usaidizi wa maombi ya scholarships na sponsorships.','image_url'=>'https://tanzania.un.org/sites/default/files/styles/hero_header_2xl_1x/public/2023-11/DIT40.jpg?itok=w3zAwUd1'],
            ['name'=>'eRITA - Cheti cha Kuzaliwa','slug'=>'erita-cheti-cha-kuzaliwa','description'=>'Usaidizi wa maombi ya cheti cha kuzaliwa.','image_url'=>'https://image.zoomtz.com/mabumbe/tz/2025/03/erita_rita_go_tz.png'],
            ['name'=>'eRITA - Cheti cha Kifo','slug'=>'erita-cheti-cha-kifo','description'=>'Usaidizi wa maombi ya cheti cha kifo.','image_url'=>'https://image.zoomtz.com/mabumbe/tz/2025/03/erita_rita_go_tz.png'],
            ['name'=>'eRITA - Uthibitisho wa Cheti','slug'=>'erita-uthibitisho-wa-cheti','description'=>'Usaidizi wa uthibitisho wa vyeti kupitia eRITA.','image_url'=>'https://image.zoomtz.com/mabumbe/tz/2025/03/erita_rita_go_tz.png'],
            ['name'=>'eRITA - Nakala / Cheti Kilichopotea','slug'=>'erita-nakala-cheti-kilichopotea','description'=>'Usaidizi wa nakala ya cheti kilichopotea au kuharibika.','image_url'=>'https://image.zoomtz.com/mabumbe/tz/2025/03/erita_rita_go_tz.png'],
            ['name'=>'eRITA - Marekebisho ya Taarifa','slug'=>'erita-marekebisho-ya-taarifa','description'=>'Usaidizi wa marekebisho ya taarifa za cheti.','image_url'=>'https://image.zoomtz.com/mabumbe/tz/2025/03/erita_rita_go_tz.png'],
            ['name'=>'Maombi ya Kazi','slug'=>'maombi-ya-kazi','description'=>'Usaidizi wa CV, cover letter na maombi ya kazi.','image_url'=>'https://wananchiforum.com/attachments/vitu-vya-kuzingaia-unapokwenda-kwenye-usaili-ajira-portal-webp.1073/'],
            ['name'=>'Visa','slug'=>'visa','description'=>'Usaidizi wa maandalizi ya maombi ya visa na nyaraka.','image_url'=>'https://static.wixstatic.com/media/182abf_c859c0e2128d4c3db724f076429b5152~mv2.png/v1/fill/w_1913%2Ch_972%2Cal_c/182abf_c859c0e2128d4c3db724f076429b5152~mv2.png'],
            ['name'=>'Kibali cha Makazi / Kazi','slug'=>'kibali-cha-makazi-kazi','description'=>'Usaidizi wa maandalizi ya maombi ya residence/work permit.','image_url'=>'https://unitedrepublicoftanzania.com/wp-content/uploads/2023/08/Work-Permit.jpg'],
            ['name'=>'Kitambulisho cha Taifa - NIDA','slug'=>'kitambulisho-cha-taifa-nida','description'=>'Usaidizi wa maandalizi ya huduma za NIDA.','image_url'=>'https://www.mbuludc.go.tz/storage/app/media/uploaded-files/nida%20online.jpg'],
            ['name'=>'Uthibitisho wa Nyaraka','slug'=>'uthibitisho-wa-nyaraka','description'=>'Usaidizi wa maandalizi ya nyaraka kwa ajili ya uthibitisho.','image_url'=>'https://www.mbuludc.go.tz/storage/app/media/uploaded-files/nida%20online.jpg'],
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
