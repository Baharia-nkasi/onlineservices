@php
$paths = [
'grid'=>'<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
'users'=>'<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
'activity'=>'<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
'chart'=>'<path d="M3 3v18h18"/><path d="M7 16l4-5 3 3 5-7"/>',
'settings'=>'<path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-1.8 1.8-.06-.06a1.7 1.7 0 0 0-1.88-.34 1.7 1.7 0 0 0-1.03 1.56V22h-2.55v-.1a1.7 1.7 0 0 0-1.03-1.56 1.7 1.7 0 0 0-1.88.34l-.06.06-1.8-1.8.06-.06A1.7 1.7 0 0 0 8.1 15a1.7 1.7 0 0 0-1.56-1.03H6.4v-2.55h.14A1.7 1.7 0 0 0 8.1 10a1.7 1.7 0 0 0-.34-1.88L7.7 8.06l1.8-1.8.06.06A1.7 1.7 0 0 0 11.44 6a1.7 1.7 0 0 0 1.03-1.56V4h2.55v.44A1.7 1.7 0 0 0 16.05 6a1.7 1.7 0 0 0 1.88-.34l.06-.06 1.8 1.8-.06.06A1.7 1.7 0 0 0 19.4 9.34a1.7 1.7 0 0 0 1.56 1.03H22v2.55h-1.04A1.7 1.7 0 0 0 19.4 15Z"/>',
'server'=>'<rect x="3" y="3" width="18" height="7" rx="2"/><rect x="3" y="14" width="18" height="7" rx="2"/><path d="M7 7h.01M7 18h.01M11 7h6M11 18h6"/>',
'headset'=>'<path d="M4 13a8 8 0 0 1 16 0v5"/><path d="M4 17v-3a2 2 0 0 1 2-2h1v7H6a2 2 0 0 1-2-2Zm16 0v-3a2 2 0 0 0-2-2h-1v7h1a2 2 0 0 0 2-2Z"/><path d="M20 18c0 2-2 3-5 3"/>',
'package'=>'<path d="m21 8-9 5-9-5 9-5 9 5Z"/><path d="M3 8v8l9 5 9-5V8M12 13v8"/>',
'bolt'=>'<path d="m13 2-9 12h7l-1 8 9-12h-7l1-8Z"/>',
'user'=>'<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>'
];
@endphp
<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">{!! $paths[$name] ?? $paths['grid'] !!}</svg>