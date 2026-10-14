@props(['name'=>'arrow','size'=>20])
@php
$paths = [
'arrow'=>'M7 17 17 7M7 7h10v10',
'chevron'=>'m9 5 7 7-7 7',
'check'=>'m5 12 4 4L19 6',
'compass'=>'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Zm4 5-3 5-5 3 3-5 5-3Z',
'shield'=>'m12 3 8 4v5c0 5-8 9-8 9s-8-4-8-9V7l8-4Zm-4 9 3 3 5-6',
'heart'=>'M20 5a5 5 0 0 0-8 2 5 5 0 0 0-8-2c-4 4 1 9 8 15 7-6 12-11 8-15Z',
'calendar'=>'M5 5h14v16H5V5Zm3-3v6m8-6v6M5 10h14',
'pin'=>'M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Zm-5 0a3 3 0 1 0-6 0 3 3 0 0 0 6 0Z',
'plane'=>'m3 12 7 2 2 7 2-1 1-5 5-3c3-2 1-4-1-3l-5 2-5-7-2 1 2 8-6-1v2Z',
'phone'=>'M7 3H4v4c0 7 6 13 13 13h4v-3l-5-2-2 2-7-7 2-2-2-5Z',
'menu'=>'M4 6h16M4 12h16M4 18h16',
'star'=>'m12 3 3 6 6 1-4 5 1 6-6-3-6 3 1-6-4-5 6-1 3-6Z',
'building'=>'M4 21V7l8-4 8 4v14M2 21h20M8 9h2m4 0h2M8 13h2m4 0h2m-6 8v-4h4v4',
'mail'=>'M3 5h18v14H3V5Zm0 0 9 8 9-8',
'close'=>'m6 6 12 12M6 18 18 6',
'pause'=>'M8 5v14M16 5v14',
'play'=>'m8 4 12 8-12 8V4Z',
'clock'=>'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Zm0 4v5l3 2',
'instagram'=>'M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4Zm9 9a4 4 0 1 0-8 0 4 4 0 0 0 8 0Zm1-5h.01',
'whatsapp'=>'M12 3a9 9 0 0 0-7.8 13.5L3 21l4.5-1.2A9 9 0 1 0 12 3Zm-3.5 4 2 3-1.5 1.5a8 8 0 0 0 3.5 3.5L14 13.5l3 2c-1 3.5-7 1.5-9.5-3C6 10 6.5 8 8.5 7Z',
];
@endphp
<svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $paths[$name] ?? $paths['arrow'] }}"/></svg>
