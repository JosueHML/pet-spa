<?php

return [
    'enable_pwa' => true,
    
    'name' => 'Pet Spa',
    'short_name' => 'PetSpa',
    'description' => 'El mejor cuidado para tu mascota',
    'theme_color' => '#28a745',
    'background_color' => '#ffffff',
    
    // No usar imagen externa
    'icon' => null,
    
    'install-toast-show' => true,

    'manifest' => [
        'name' => 'Pet Spa',
        'short_name' => 'PetSpa',
        'start_url' => '/',
        'scope' => '/',
        'description' => 'El mejor cuidado para tu mascota',
        'orientation' => 'portrait',
        'display' => 'standalone',
        'theme_color' => '#28a745',
        'background_color' => '#ffffff',
        'icons' => [
            [
                'src' => 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"%3E%3Crect width="100" height="100" rx="20" fill="%2328a745"/%3E%3Ctext x="50" y="67" text-anchor="middle" font-size="50" fill="white"%3E🐾%3C/text%3E%3C/svg%3E',
                'sizes' => '512x512',
                'type' => 'image/svg+xml',
            ],
        ],
    ],

    'debug' => false,
    'title' => '¡Bienvenido a Pet Spa!',
    'description' => 'Instala esta app para una mejor experiencia',
    'small_device_position' => 'bottom',
    'install_now_button_text' => 'Instalar App',
    'livewire-app' => false,
];