<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $images = [
            'maombi-ya-chuo-kikuu' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1400&q=85',
            'maombi-ya-mkopo-wa-elimu-ya-juu' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1400&q=85',
            'huduma-za-nactvet' => 'https://images.unsplash.com/photo-1516321497487-e288fb19713f?auto=format&fit=crop&w=1400&q=85',
            'pasipoti-ya-kielektroniki' => 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=1400&q=85',
            'tin-number-tra' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=1400&q=85',
            'usajili-wa-jina-la-biashara-brela' => 'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1400&q=85',
            'usajili-wa-kampuni-brela' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1400&q=85',
            'umiliki-halisi-wa-kampuni' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1400&q=85',
            'leseni-ya-udereva' => 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=1400&q=85',
            'hati-ya-tabia-njema' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1400&q=85',
            'huduma-za-necta' => 'https://images.unsplash.com/photo-1529070538774-1843cb3265df?auto=format&fit=crop&w=1400&q=85',
            'vyeti-na-nakala-za-matokeo-ya-chuo' => 'https://images.unsplash.com/photo-1606761568499-6d2451b23c66?auto=format&fit=crop&w=1400&q=85',
            'maombi-ya-ufadhili-masomo' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1400&q=85',
            'erita-cheti-cha-kuzaliwa' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=1400&q=85',
            'erita-cheti-cha-kifo' => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=1400&q=85',
            'erita-nakala-cheti-kilichopotea' => 'https://images.unsplash.com/photo-1554224154-26032ffc0d07?auto=format&fit=crop&w=1400&q=85',
            'erita-marekebisho-ya-taarifa' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1400&q=85',
            'maombi-ya-kazi' => 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1400&q=85',
            'visa' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1400&q=85',
            'kibali-cha-makazi-kazi' => 'https://images.unsplash.com/photo-1494412651409-8963ce7935a7?auto=format&fit=crop&w=1400&q=85',
            'kitambulisho-cha-taifa-nida' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1400&q=85',
            'uthibitisho-wa-nyaraka' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1400&q=85',
        ];

        foreach ($images as $slug => $url) {
            DB::table('services')->where('slug', $slug)->update(['image_url' => $url]);
        }
    }

    public function down(): void
    {
        // Image URLs are presentation data; rolling back to the previous
        // external URLs is intentionally avoided because some were invalid
        // or no longer reachable.
    }
};
