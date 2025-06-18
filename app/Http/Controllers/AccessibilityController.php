<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccessibilityService;

class AccessibilityController extends Controller
{
    /**
     * Exibir configurações de acessibilidade
     */
    public function index()
    {
        $settings = AccessibilityService::getUserSettings();
        $colorThemes = AccessibilityService::getColorThemes();
        $fontSizes = AccessibilityService::getFontSizes();
        
        return view('accessibility.index', compact('settings', 'colorThemes', 'fontSizes'));
    }

    /**
     * Salvar configurações de acessibilidade
     */
    public function store(Request $request)
    {
        $request->validate([
            'color_theme' => 'required|in:default,protanopia,deuteranopia,tritanopia,high_contrast',
            'dark_mode' => 'boolean',
            'font_size' => 'required|in:small,normal,large,extra_large',
            'high_contrast' => 'boolean',
            'reduce_motion' => 'boolean'
        ]);

        $settings = [
            'color_theme' => $request->color_theme,
            'dark_mode' => $request->boolean('dark_mode'),
            'font_size' => $request->font_size,
            'high_contrast' => $request->boolean('high_contrast'),
            'reduce_motion' => $request->boolean('reduce_motion')
        ];

        AccessibilityService::saveUserSettings($settings);

        return response()->json([
            'success' => true,
            'message' => 'Configurações de acessibilidade salvas com sucesso!'
        ]);
    }

    /**
     * API para obter configurações atuais
     */
    public function getSettings()
    {
        $settings = AccessibilityService::getUserSettings();
        $colorThemes = AccessibilityService::getColorThemes();
        $darkModeConfig = AccessibilityService::getDarkModeConfig();
        
        return response()->json([
            'settings' => $settings,
            'colorThemes' => $colorThemes,
            'darkModeConfig' => $darkModeConfig
        ]);
    }

    /**
     * Alternar modo escuro rapidamente
     */
    public function toggleDarkMode(Request $request)
    {
        $settings = AccessibilityService::getUserSettings();
        $settings['dark_mode'] = !$settings['dark_mode'];
        
        AccessibilityService::saveUserSettings($settings);
        
        return response()->json([
            'success' => true,
            'dark_mode' => $settings['dark_mode']
        ]);
    }
} 