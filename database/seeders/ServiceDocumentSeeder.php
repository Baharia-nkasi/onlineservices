<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceDocument;
use Illuminate\Database\Seeder;

class ServiceDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $requirements = [

            /*
            |--------------------------------------------------------------------------
            | 1. MAOMBI YA CHUO KIKUU
            |--------------------------------------------------------------------------
            */

            'maombi-ya-chuo-kikuu' => [
                ['name' => 'Kitambulisho cha Taifa', 'required' => true],
                ['name' => 'Cheti cha kuzaliwa', 'required' => true],
                ['name' => 'Picha ya pasipoti', 'required' => true],
                ['name' => 'Cheti au matokeo ya Kidato cha Nne', 'required' => true],
                ['name' => 'Cheti au matokeo ya Kidato cha Sita', 'required' => false],
                ['name' => 'Cheti cha Stashahada', 'required' => false],
                ['name' => 'Nakala ya matokeo ya Stashahada', 'required' => false],
                ['name' => 'Vyeti vingine vya kitaaluma', 'required' => false],
                ['name' => 'Namba ya mtihani ya Kidato cha Nne', 'required' => true],
                ['name' => 'Namba ya mtihani ya Kidato cha Sita', 'required' => false],
                ['name' => 'Taarifa za mzazi au mlezi', 'required' => true],
                ['name' => 'Nyaraka za uhamisho', 'required' => false],
            ],

            /*
            |--------------------------------------------------------------------------
            | 2. MAOMBI YA MKOPO WA ELIMU YA JUU
            |--------------------------------------------------------------------------
            */

            'maombi-ya-mkopo-wa-elimu-ya-juu' => [
                ['name' => 'Kitambulisho cha Taifa', 'required' => true],
                ['name' => 'Cheti cha kuzaliwa', 'required' => true],
                ['name' => 'Namba ya uthibitisho wa cheti cha kuzaliwa', 'required' => true],
                ['name' => 'Barua ya udahili', 'required' => true],
                ['name' => 'Namba ya mtihani ya Kidato cha Nne', 'required' => true],
                ['name' => 'Namba ya mtihani ya Kidato cha Sita', 'required' => false],
                ['name' => 'Taarifa za chuo na programu anayosomea', 'required' => true],
                ['name' => 'Taarifa za benki', 'required' => false],
                ['name' => 'Cheti cha kifo cha mzazi', 'required' => false],
                ['name' => 'Namba ya uthibitisho wa cheti cha kifo', 'required' => false],
                ['name' => 'Kitambulisho cha mzazi aliyefariki', 'required' => false],
                ['name' => 'Hati au cheti cha ulemavu', 'required' => false],
                ['name' => 'Nyaraka nyingine za kuthibitisha ulemavu', 'required' => false],
                ['name' => 'Nyaraka za ufadhili', 'required' => false],
                ['name' => 'Nyaraka za TASAF', 'required' => false],
                ['name' => 'Namba ya simu na barua pepe', 'required' => true],
            ],

            /*
            |--------------------------------------------------------------------------
            | 3. HUDUMA ZA NACTVET
            |--------------------------------------------------------------------------
            */

            'huduma-za-nactvet' => [
                ['name' => 'Kitambulisho cha Taifa', 'required' => true],
                ['name' => 'Cheti cha kuzaliwa', 'required' => true],
                ['name' => 'Picha ya pasipoti', 'required' => true],
                ['name' => 'Cheti cha Kidato cha Nne', 'required' => true],
                ['name' => 'Cheti cha Kidato cha Sita', 'required' => false],
                ['name' => 'Cheti cha VETA', 'required' => false],
                ['name' => 'Cheti cha Stashahada', 'required' => false],
                ['name' => 'Nakala ya matokeo ya masomo', 'required' => true],
                ['name' => 'Namba ya mtihani', 'required' => true],
                ['name' => 'Taarifa za chuo na programu', 'required' => true],
                ['name' => 'Namba ya simu', 'required' => true],
                ['name' => 'Barua pepe', 'required' => true],
            ],

            /*
            |--------------------------------------------------------------------------
            | 4. PASIPOTI YA KIELEKTRONIKI
            |--------------------------------------------------------------------------
            */

            'pasipoti-ya-kielektroniki' => [
                ['name' => 'Cheti cha kuzaliwa', 'required' => true],
                ['name' => 'Kitambulisho cha Taifa', 'required' => true],
                ['name' => 'Picha ya pasipoti', 'required' => true],
                ['name' => 'Cheti cha kuzaliwa cha mzazi', 'required' => false],
                ['name' => 'Kitambulisho cha mzazi', 'required' => false],
                ['name' => 'Pasipoti ya zamani', 'required' => false],
                ['name' => 'Ripoti ya polisi kuhusu kupotea kwa pasipoti', 'required' => false],
                ['name' => 'Pasipoti iliyoharibika', 'required' => false],
                ['name' => 'Taarifa za safari', 'required' => false],
                ['name' => 'Anwani', 'required' => true],
                ['name' => 'Namba ya simu', 'required' => true],
                ['name' => 'Barua pepe', 'required' => true],
                ['name' => 'Taarifa za mdhamini au mtu wa dharura', 'required' => false],
            ],

            /*
            |--------------------------------------------------------------------------
            | 5. TIN NUMBER - TRA
            |--------------------------------------------------------------------------
            */

            'tin-number-tra' => [
                ['name' => 'Kitambulisho cha Taifa', 'required' => true],
                ['name' => 'Taarifa za makazi', 'required' => true],
                ['name' => 'Namba ya simu', 'required' => true],
                ['name' => 'Barua pepe', 'required' => true],
                ['name' => 'Taarifa za kazi au shughuli anayofanya', 'required' => true],
                ['name' => 'Cheti cha usajili wa biashara', 'required' => false],
                ['name' => 'Leseni ya biashara', 'required' => false],
                ['name' => 'Nyaraka za kampuni', 'required' => false],
                ['name' => 'Taarifa za mahali biashara ilipo', 'required' => false],
                ['name' => 'Taarifa za shughuli za biashara', 'required' => false],
            ],

            /*
            |--------------------------------------------------------------------------
            | 6. USAJILI WA JINA LA BIASHARA - BRELA
            |--------------------------------------------------------------------------
            */

            'usajili-wa-jina-la-biashara-brela' => [
                ['name' => 'Kitambulisho cha Taifa', 'required' => true],
                ['name' => 'Jina analotaka kusajili', 'required' => true],
                ['name' => 'Majina mengine ya ziada', 'required' => true],
                ['name' => 'Maelezo ya shughuli za biashara', 'required' => true],
                ['name' => 'Anwani ya biashara', 'required' => true],
                ['name' => 'Anwani ya makazi', 'required' => true],
                ['name' => 'Namba ya simu', 'required' => true],
                ['name' => 'Barua pepe', 'required' => true],
                ['name' => 'Vitambulisho vya washirika', 'required' => false],
                ['name' => 'Taarifa za washirika', 'required' => false],
            ],

            /*
            |--------------------------------------------------------------------------
            | 7. USAJILI WA KAMPUNI - BRELA
            |--------------------------------------------------------------------------
            */

            'usajili-wa-kampuni-brela' => [
                ['name' => 'Vitambulisho vya Taifa vya wakurugenzi', 'required' => true],
                ['name' => 'Vitambulisho vya Taifa vya wanahisa', 'required' => true],
                ['name' => 'Majina yanayopendekezwa ya kampuni', 'required' => true],
                ['name' => 'Maelezo ya shughuli za kampuni', 'required' => true],
                ['name' => 'Anwani ya ofisi ya kampuni', 'required' => true],
                ['name' => 'Taarifa za wakurugenzi', 'required' => true],
                ['name' => 'Taarifa za wanahisa', 'required' => true],
                ['name' => 'Taarifa za mtaji na hisa', 'required' => true],
                ['name' => 'Namba za simu', 'required' => true],
                ['name' => 'Barua pepe', 'required' => true],
                ['name' => 'Nyaraka za kampuni zinazohitajika kwa usajili', 'required' => true],
            ],

            /*
            |--------------------------------------------------------------------------
            | 8. UMILIKI HALISI WA KAMPUNI
            |--------------------------------------------------------------------------
            */

            'umiliki-halisi-wa-kampuni' => [
                ['name' => 'Kitambulisho cha Taifa au pasipoti ya mmiliki halisi', 'required' => true],
                ['name' => 'Cheti cha usajili wa kampuni', 'required' => true],
                ['name' => 'Nyaraka zinazoonyesha umiliki', 'required' => true],
                ['name' => 'Taarifa za wanahisa', 'required' => true],
                ['name' => 'Asilimia ya umiliki wa kila mwanahisa', 'required' => true],
                ['name' => 'Taarifa za wakurugenzi', 'required' => true],
            ],

            /*
            |--------------------------------------------------------------------------
            | 9. LESENI YA UDEREVA
            |--------------------------------------------------------------------------
            */

            'leseni-ya-udereva' => [
                ['name' => 'Kitambulisho cha Taifa', 'required' => true],
                ['name' => 'Picha ya pasipoti', 'required' => false],
                ['name' => 'Leseni ya zamani', 'required' => false],
                ['name' => 'Namba ya leseni ya zamani', 'required' => false],
                ['name' => 'Ripoti ya polisi', 'required' => false],
                ['name' => 'Taarifa za shule ya udereva', 'required' => false],
                ['name' => 'Taarifa za mafunzo', 'required' => false],
                ['name' => 'Taarifa za mtihani', 'required' => false],
                ['name' => 'Hati ya afya', 'required' => false],
            ],

            /*
            |--------------------------------------------------------------------------
            | 10. HATI YA TABIA NJEMA
            |--------------------------------------------------------------------------
            */

            'hati-ya-tabia-njema' => [
                ['name' => 'Kitambulisho cha Taifa', 'required' => true],
                ['name' => 'Pasipoti', 'required' => false],
                ['name' => 'Picha ya pasipoti', 'required' => false],
                ['name' => 'Cheti cha kuzaliwa', 'required' => false],
                ['name' => 'Barua ya mwajiri au taasisi', 'required' => false],
                ['name' => 'Taarifa binafsi za utambulisho', 'required' => true],
                ['name' => 'Namba ya simu', 'required' => true],
                ['name' => 'Barua pepe', 'required' => true],
            ],

            /*
            |--------------------------------------------------------------------------
            | 11. HUDUMA ZA NECTA
            |--------------------------------------------------------------------------
            */

            'huduma-za-necta' => [
                ['name' => 'Cheti halisi', 'required' => true],
                ['name' => 'Kitambulisho cha Taifa', 'required' => true],
                ['name' => 'Namba ya mtihani', 'required' => true],
                ['name' => 'Mwaka wa mtihani', 'required' => true],
                ['name' => 'Jina la shule', 'required' => true],
                ['name' => 'Ripoti ya polisi', 'required' => false],
                ['name' => 'Cheti kilichoharibika', 'required' => false],
                ['name' => 'Nyaraka za kuthibitisha uharibifu au upotevu', 'required' => false],
                ['name' => 'Taarifa nyingine za mtihani', 'required' => false],
            ],

            /*
            |--------------------------------------------------------------------------
            | 12. VYETI NA NAKALA ZA MATOKEO YA CHUO
            |--------------------------------------------------------------------------
            */

            'vyeti-na-nakala-za-matokeo-ya-chuo' => [
                ['name' => 'Kitambulisho cha Taifa', 'required' => true],
                ['name' => 'Kitambulisho cha mwanafunzi', 'required' => false],
                ['name' => 'Namba ya usajili wa mwanafunzi', 'required' => true],
                ['name' => 'Namba ya udahili', 'required' => true],
                ['name' => 'Cheti cha chuo', 'required' => false],
                ['name' => 'Nakala ya matokeo', 'required' => true],
                ['name' => 'Taarifa ya matokeo', 'required' => false],
                ['name' => 'Jina la programu aliyosomea', 'required' => true],
                ['name' => 'Mwaka wa kuhitimu', 'required' => true],
                ['name' => 'Taarifa za malipo', 'required' => false],
                ['name' => 'Ripoti ya polisi kwa cheti kilichopotea', 'required' => false],
                ['name' => 'Taarifa za chuo', 'required' => true],
            ],

            /*
            |--------------------------------------------------------------------------
            | 13. MAOMBI YA UFADHILI / MASOMO
            |--------------------------------------------------------------------------
            */

            'maombi-ya-ufadhili-masomo' => [
                ['name' => 'Kitambulisho cha Taifa au pasipoti', 'required' => true],
                ['name' => 'Cheti cha kuzaliwa', 'required' => true],
                ['name' => 'Picha ya pasipoti', 'required' => true],
                ['name' => 'Vyeti vya kitaaluma', 'required' => true],
                ['name' => 'Nakala za matokeo', 'required' => true],
                ['name' => 'Barua ya udahili', 'required' => true],
                ['name' => 'Wasifu binafsi', 'required' => true],
                ['name' => 'Barua ya maombi', 'required' => true],
                ['name' => 'Barua ya mapendekezo', 'required' => false],
                ['name' => 'Maelezo ya sababu za kuomba ufadhili', 'required' => true],
                ['name' => 'Hati ya kipato cha mzazi au mlezi', 'required' => false],
                ['name' => 'Nyaraka za ulemavu', 'required' => false],
                ['name' => 'Pendekezo la utafiti', 'required' => false],
                ['name' => 'Vyeti vya mafunzo', 'required' => false],
                ['name' => 'Nyaraka za udhamini', 'required' => false],
            ],

            /*
            |--------------------------------------------------------------------------
            | 14. eRITA - CHETI CHA KUZALIWA
            |--------------------------------------------------------------------------
            */

            'erita-cheti-cha-kuzaliwa' => [
                ['name' => 'Kadi ya kliniki ya mama', 'required' => true],
                ['name' => 'Kadi ya kliniki ya mtoto', 'required' => true],
                ['name' => 'Tangazo la kuzaliwa', 'required' => true],
                ['name' => 'Cheti cha ubatizo cha mtoto', 'required' => false],
                ['name' => 'Cheti cha kumaliza elimu ya msingi au sekondari', 'required' => false],
                ['name' => 'Pasipoti', 'required' => false],
                ['name' => 'Kadi ya mpiga kura', 'required' => false],
                ['name' => 'Kitambulisho cha Taifa cha mzazi au mlezi', 'required' => true],
                ['name' => 'Hati ya kusafiria ya mzazi', 'required' => false],
                ['name' => 'Utambulisho kutoka kwa Mtendaji wa Kata', 'required' => false],
                ['name' => 'Taarifa sahihi za mtoto', 'required' => true],
                ['name' => 'Taarifa za wazazi', 'required' => true],
                ['name' => 'Tarehe ya kuzaliwa', 'required' => true],
                ['name' => 'Mahali alipozaliwa', 'required' => true],
                ['name' => 'Namba ya simu', 'required' => true],
                ['name' => 'Barua pepe', 'required' => false],
            ],

            /*
            |--------------------------------------------------------------------------
            | 15. eRITA - CHETI CHA KIFO
            |--------------------------------------------------------------------------
            */

            'erita-cheti-cha-kifo' => [
                ['name' => 'Kibali cha mazishi kutoka kituo cha tiba', 'required' => true],
                ['name' => 'Muhtasari wa kikao cha wanandugu', 'required' => true],
                ['name' => 'Barua ya utambulisho wa msimamizi wa mirathi', 'required' => true],
                ['name' => 'Kadi ya mpiga kura au Kitambulisho cha Taifa cha marehemu', 'required' => true],
                ['name' => 'Kitambulisho cha msimamizi wa mirathi', 'required' => true],
                ['name' => 'Cheti cha ndoa', 'required' => false],
                ['name' => 'Vyeti vya kuzaliwa vya watoto', 'required' => false],
                ['name' => 'Jina la marehemu', 'required' => true],
                ['name' => 'Tarehe ya kuzaliwa', 'required' => true],
                ['name' => 'Tarehe ya kifo', 'required' => true],
                ['name' => 'Mahali kifo kilipotokea', 'required' => true],
                ['name' => 'Taarifa za msimamizi wa mirathi', 'required' => true],
                ['name' => 'Namba ya simu', 'required' => true],
            ],

            /*
            |--------------------------------------------------------------------------
            | 16. eRITA - UTHIBITISHO WA CHETI
            |--------------------------------------------------------------------------
            */

            'erita-uthibitisho-wa-cheti' => [
                ['name' => 'Cheti cha kuzaliwa au cheti cha kifo', 'required' => true],
                ['name' => 'Namba ya cheti', 'required' => true],
                ['name' => 'Namba ya kumbukumbu ya maombi', 'required' => false],
                ['name' => 'Kitambulisho cha Taifa', 'required' => false],
                ['name' => 'Taarifa sahihi za mwenye cheti', 'required' => true],
            ],

            /*
            |--------------------------------------------------------------------------
            | 17. eRITA - NAKALA / CHETI KILICHOPOTEA
            |--------------------------------------------------------------------------
            */

            'erita-nakala-cheti-kilichopotea' => [
                ['name' => 'Kitambulisho cha Taifa', 'required' => true],
                ['name' => 'Namba ya cheti cha zamani', 'required' => false],
                ['name' => 'Nakala ya cheti', 'required' => false],
                ['name' => 'Ripoti ya polisi', 'required' => false],
                ['name' => 'Cheti kilichoharibika', 'required' => false],
                ['name' => 'Nyaraka zinazothibitisha taarifa za cheti', 'required' => true],
                ['name' => 'Taarifa za kuzaliwa au kifo', 'required' => true],
                ['name' => 'Namba ya simu', 'required' => true],
            ],

            /*
            |--------------------------------------------------------------------------
            | 18. eRITA - MAREKEBISHO YA TAARIFA
            |--------------------------------------------------------------------------
            */

            'erita-marekebisho-ya-taarifa' => [
                ['name' => 'Cheti chenye taarifa isiyo sahihi', 'required' => true],
                ['name' => 'Kitambulisho cha Taifa', 'required' => true],
                ['name' => 'Nyaraka zinazoonyesha taarifa sahihi', 'required' => true],
                ['name' => 'Cheti cha kuzaliwa', 'required' => false],
                ['name' => 'Cheti cha ndoa', 'required' => false],
                ['name' => 'Hati ya kiapo', 'required' => false],
                ['name' => 'Barua ya hospitali au taasisi husika', 'required' => false],
                ['name' => 'Nyaraka nyingine za kisheria', 'required' => false],
            ],

            /*
            |--------------------------------------------------------------------------
            | 19. MAOMBI YA KAZI
            |--------------------------------------------------------------------------
            */

            'maombi-ya-kazi' => [
                ['name' => 'Kitambulisho cha Taifa', 'required' => true],
                ['name' => 'Cheti cha kuzaliwa', 'required' => true],
                ['name' => 'Picha ya pasipoti', 'required' => true],
                ['name' => 'Wasifu wa kazi', 'required' => true],
                ['name' => 'Barua ya maombi ya kazi', 'required' => true],
                ['name' => 'Cheti cha Kidato cha Nne', 'required' => true],
                ['name' => 'Cheti cha Kidato cha Sita', 'required' => false],
                ['name' => 'Cheti cha Stashahada', 'required' => false],
                ['name' => 'Nakala ya matokeo ya Stashahada', 'required' => false],
                ['name' => 'Cheti cha Shahada', 'required' => false],
                ['name' => 'Nakala ya matokeo ya Shahada', 'required' => false],
                ['name' => 'Cheti cha Uzamili', 'required' => false],
                ['name' => 'Vyeti vya taaluma', 'required' => false],
                ['name' => 'Vyeti vya mafunzo', 'required' => false],
                ['name' => 'Vyeti vya uzoefu wa kazi', 'required' => false],
                ['name' => 'Barua za utambulisho', 'required' => false],
                ['name' => 'Barua za mapendekezo', 'required' => false],
                ['name' => 'Leseni ya kitaaluma', 'required' => false],
                ['name' => 'Uthibitisho wa vyeti', 'required' => false],
            ],

            /*
            |--------------------------------------------------------------------------
            | 20. VISA
            |--------------------------------------------------------------------------
            */

            'visa' => [
                ['name' => 'Pasipoti halali', 'required' => true],
                ['name' => 'Picha ya pasipoti', 'required' => true],
                ['name' => 'Taarifa za safari', 'required' => true],
                ['name' => 'Anwani ya mahali atakapofikia', 'required' => true],
                ['name' => 'Barua ya mwaliko', 'required' => false],
                ['name' => 'Uthibitisho wa sehemu ya kulala', 'required' => false],
                ['name' => 'Tiketi ya kwenda au kurudi', 'required' => false],
                ['name' => 'Taarifa za mwenyeji', 'required' => false],
                ['name' => 'Barua ya udahili wa chuo', 'required' => false],
                ['name' => 'Barua ya mwajiri', 'required' => false],
                ['name' => 'Nyaraka za biashara', 'required' => false],
                ['name' => 'Uthibitisho wa sababu ya safari', 'required' => false],
            ],

            /*
            |--------------------------------------------------------------------------
            | 21. KIBALI CHA MAKAZI / KAZI
            |--------------------------------------------------------------------------
            */

            'kibali-cha-makazi-kazi' => [
                ['name' => 'Pasipoti', 'required' => true],
                ['name' => 'Picha ya pasipoti', 'required' => true],
                ['name' => 'Mkataba wa kazi au barua ya ajira', 'required' => true],
                ['name' => 'Wasifu wa kazi', 'required' => true],
                ['name' => 'Vyeti vya elimu', 'required' => true],
                ['name' => 'Vyeti vya taaluma', 'required' => true],
                ['name' => 'Cheti cha tabia njema', 'required' => false],
                ['name' => 'Cheti cha afya', 'required' => false],
                ['name' => 'Nyaraka za mwajiri', 'required' => false],
                ['name' => 'Cheti cha usajili wa kampuni', 'required' => false],
                ['name' => 'Namba ya mlipa kodi', 'required' => false],
                ['name' => 'Nyaraka za kampuni', 'required' => false],
            ],

            /*
            |--------------------------------------------------------------------------
            | 22. KITAMBULISHO CHA TAIFA - NIDA
            |--------------------------------------------------------------------------
            */

            'kitambulisho-cha-taifa-nida' => [
                ['name' => 'Cheti cha kuzaliwa', 'required' => true],
                ['name' => 'Nyaraka za utambulisho zinazohitajika', 'required' => true],
                ['name' => 'Kitambulisho cha mzazi au mlezi', 'required' => false],
                ['name' => 'Pasipoti', 'required' => false],
                ['name' => 'Kibali cha makazi au kazi', 'required' => false],
                ['name' => 'Taarifa za makazi', 'required' => true],
                ['name' => 'Namba ya simu', 'required' => true],
                ['name' => 'Barua pepe', 'required' => false],
            ],

            /*
            |--------------------------------------------------------------------------
            | 23. UTHIBITISHO WA NYARAKA
            |--------------------------------------------------------------------------
            */

            'uthibitisho-wa-nyaraka' => [
                ['name' => 'Cheti cha masomo', 'required' => false],
                ['name' => 'Nakala ya matokeo', 'required' => false],
                ['name' => 'Cheti cha kuzaliwa', 'required' => false],
                ['name' => 'Cheti cha kifo', 'required' => false],
                ['name' => 'NIDA', 'required' => false],
                ['name' => 'Cheti cha TIN', 'required' => false],
                ['name' => 'Leseni ya udereva', 'required' => false],
                ['name' => 'Cheti cha usajili wa biashara au kampuni', 'required' => false],
            ],
        ];

        foreach ($requirements as $slug => $documents) {

            $service = Service::where('slug', $slug)->first();

            if (!$service) {
                continue;
            }

            foreach ($documents as $index => $document) {
                ServiceDocument::updateOrCreate(
                    [
                        'service_id' => $service->id,
                        'name' => $document['name'],
                    ],
                    [
                        'description' => null,
                        'is_required' => $document['required'],
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}