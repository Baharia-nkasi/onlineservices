<?php

namespace App\Support;

use App\Models\Service;

class ServiceImage
{
    /**
     * Return a deterministic, service-specific embedded SVG fallback.
     * This means every service still has an image even when an external
     * image URL is unavailable, blocked, or removed.
     */
    public static function fallbackDataUri(Service|string $service): string
    {
        $slug = $service instanceof Service ? (string) $service->slug : (string) $service;
        $name = $service instanceof Service ? (string) $service->name : $slug;

        $palette = self::palette($slug);
        $icon = self::icon($slug);

        $safeName = htmlspecialchars(
            mb_strimwidth($name, 0, 42, '…', 'UTF-8'),
            ENT_QUOTES | ENT_XML1,
            'UTF-8'
        );

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 700" role="img" aria-label="{$safeName}">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="{$palette[0]}"/>
      <stop offset="1" stop-color="{$palette[1]}"/>
    </linearGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="18" stdDeviation="18" flood-color="#020617" flood-opacity=".22"/>
    </filter>
  </defs>
  <rect width="1200" height="700" rx="42" fill="url(#bg)"/>
  <circle cx="1040" cy="100" r="190" fill="#fff" opacity=".10"/>
  <circle cx="120" cy="620" r="240" fill="#fff" opacity=".08"/>
  <g filter="url(#shadow)" transform="translate(110 110)">
    <rect width="480" height="480" rx="72" fill="#fff" opacity=".96"/>
    <g transform="translate(92 92) scale(12)" fill="none" stroke="{$palette[1]}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
      {$icon}
    </g>
  </g>
  <text x="650" y="315" fill="#fff" font-family="Arial,Helvetica,sans-serif" font-size="42" font-weight="800">Online Services</text>
  <text x="650" y="375" fill="#e2e8f0" font-family="Arial,Helvetica,sans-serif" font-size="28" font-weight="700">{$safeName}</text>
  <text x="650" y="430" fill="#cbd5e1" font-family="Arial,Helvetica,sans-serif" font-size="20">Service image</text>
</svg>
SVG;

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    private static function palette(string $slug): array
    {
        if (str_contains($slug, 'chuo') || str_contains($slug, 'elimu') || str_contains($slug, 'nactvet') || str_contains($slug, 'necta') || str_contains($slug, 'ufadhili')) {
            return ['#1d4ed8', '#0f766e'];
        }

        if (str_contains($slug, 'pasipoti') || str_contains($slug, 'visa') || str_contains($slug, 'makazi') || str_contains($slug, 'udereva')) {
            return ['#0369a1', '#4338ca'];
        }

        if (str_contains($slug, 'brela') || str_contains($slug, 'tin') || str_contains($slug, 'kampuni') || str_contains($slug, 'biashara')) {
            return ['#047857', '#0f766e'];
        }

        if (str_contains($slug, 'erita') || str_contains($slug, 'cheti') || str_contains($slug, 'nyaraka') || str_contains($slug, 'nida')) {
            return ['#7c3aed', '#be185d'];
        }

        if (str_contains($slug, 'kazi')) {
            return ['#ea580c', '#b45309'];
        }

        if (str_contains($slug, 'tabia')) {
            return ['#334155', '#1e3a8a'];
        }

        return ['#2563eb', '#312e81'];
    }

    private static function icon(string $slug): string
    {
        if (str_contains($slug, 'pasipoti') || str_contains($slug, 'visa') || str_contains($slug, 'makazi')) {
            return '<path d="M6 3h12v18H6z"/><path d="M9 7h6M9 11h6M9 15h4"/>';
        }

        if (str_contains($slug, 'brela') || str_contains($slug, 'tin') || str_contains($slug, 'kampuni') || str_contains($slug, 'biashara')) {
            return '<rect x="3" y="6" width="18" height="13" rx="2"/><path d="M8 6V4h8v2M3 11h18M10 11v2h4v-2"/>';
        }

        if (str_contains($slug, 'erita') || str_contains($slug, 'cheti') || str_contains($slug, 'nyaraka')) {
            return '<path d="M6 3h9l3 3v15H6z"/><path d="M15 3v4h4M9 12h6M9 16h6"/><path d="M12 8v.01"/>';
        }

        if (str_contains($slug, 'nida') || str_contains($slug, 'tabia')) {
            return '<rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="11" r="2"/><path d="M13 10h5M13 14h4M6 16h12"/>';
        }

        if (str_contains($slug, 'kazi')) {
            return '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5h8v2M3 12h18M10 12v3h4v-3"/>';
        }

        if (str_contains($slug, 'udereva')) {
            return '<path d="M5 16l1.5-6h11l1.5 6"/><path d="M7 10l1.5-3h7L17 10M3 16h18M7 19h.01M17 19h.01"/>';
        }

        return '<path d="M4 4h16v16H4z"/><path d="M8 8h8M8 12h8M8 16h5"/>';
    }
}
