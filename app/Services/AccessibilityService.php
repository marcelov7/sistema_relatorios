<?php

namespace App\Services;

class AccessibilityService
{
    /**
     * Temas de cores disponíveis para daltonismo
     */
    public static function getColorThemes()
    {
        return [
            'default' => [
                'name' => 'Padrão',
                'description' => 'Cores padrão do sistema',
                'colors' => [
                    'primary' => '#6f42c1',
                    'success' => '#198754',
                    'danger' => '#dc3545',
                    'warning' => '#ffc107',
                    'info' => '#0dcaf0',
                    'secondary' => '#6c757d'
                ]
            ],
            'protanopia' => [
                'name' => 'Protanopia',
                'description' => 'Adaptado para deficiência de vermelho',
                'colors' => [
                    'primary' => '#0066cc',
                    'success' => '#0099cc',
                    'danger' => '#ff6600',
                    'warning' => '#ffcc00',
                    'info' => '#0066cc',
                    'secondary' => '#666666'
                ]
            ],
            'deuteranopia' => [
                'name' => 'Deuteranopia',
                'description' => 'Adaptado para deficiência de verde',
                'colors' => [
                    'primary' => '#0066cc',
                    'success' => '#0099cc',
                    'danger' => '#cc6600',
                    'warning' => '#ffcc00',
                    'info' => '#0066cc',
                    'secondary' => '#666666'
                ]
            ],
            'tritanopia' => [
                'name' => 'Tritanopia',
                'description' => 'Adaptado para deficiência de azul',
                'colors' => [
                    'primary' => '#cc0066',
                    'success' => '#009900',
                    'danger' => '#cc0000',
                    'warning' => '#ff9900',
                    'info' => '#cc0066',
                    'secondary' => '#666666'
                ]
            ],
            'high_contrast' => [
                'name' => 'Alto Contraste',
                'description' => 'Cores com alto contraste',
                'colors' => [
                    'primary' => '#000000',
                    'success' => '#008000',
                    'danger' => '#ff0000',
                    'warning' => '#ff8000',
                    'info' => '#0000ff',
                    'secondary' => '#808080'
                ]
            ]
        ];
    }

    /**
     * Configurações de modo escuro
     */
    public static function getDarkModeConfig()
    {
        return [
            'background' => '#1a1a1a',
            'surface' => '#2d2d2d',
            'card' => '#3a3a3a',
            'text' => '#ffffff',
            'text_secondary' => '#cccccc',
            'border' => '#4a4a4a',
            'navbar' => '#2d2d2d',
            'sidebar' => '#1f1f1f'
        ];
    }

    /**
     * Configurações de tamanho de fonte
     */
    public static function getFontSizes()
    {
        return [
            'small' => [
                'name' => 'Pequeno',
                'scale' => 0.875
            ],
            'normal' => [
                'name' => 'Normal',
                'scale' => 1.0
            ],
            'large' => [
                'name' => 'Grande',
                'scale' => 1.125
            ],
            'extra_large' => [
                'name' => 'Extra Grande',
                'scale' => 1.25
            ]
        ];
    }

    /**
     * Obter configurações do usuário
     */
    public static function getUserSettings($userId = null)
    {
        $userId = $userId ?? auth()->id();
        
        if (!$userId) {
            return self::getDefaultSettings();
        }

        // Buscar nas configurações do usuário ou usar localStorage
        return session('accessibility_settings', self::getDefaultSettings());
    }

    /**
     * Configurações padrão
     */
    public static function getDefaultSettings()
    {
        return [
            'color_theme' => 'default',
            'dark_mode' => false,
            'font_size' => 'normal',
            'high_contrast' => false,
            'reduce_motion' => false
        ];
    }

    /**
     * Salvar configurações do usuário
     */
    public static function saveUserSettings($settings, $userId = null)
    {
        $userId = $userId ?? auth()->id();
        
        // Por enquanto, salvar na sessão
        // Futuramente pode ser salvo no banco de dados
        session(['accessibility_settings' => $settings]);
        
        return true;
    }
} 