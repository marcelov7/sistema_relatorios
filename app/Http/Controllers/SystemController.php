<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\VersionHelper;

class SystemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibir informações do sistema
     */
    public function info()
    {
        $versionInfo = VersionHelper::getFullVersionInfo();
        $systemInfo = VersionHelper::getSystemInfo();
        $changelog = VersionHelper::getCurrentChangelog();
        $fullChangelog = VersionHelper::getChangelog();

        return view('system.info', compact('versionInfo', 'systemInfo', 'changelog', 'fullChangelog'));
    }

    /**
     * Exibir changelog completo
     */
    public function changelog()
    {
        $changelog = VersionHelper::getChangelog();
        $versionInfo = VersionHelper::getFullVersionInfo();

        return view('system.changelog', compact('changelog', 'versionInfo'));
    }

    /**
     * API para obter informações da versão
     */
    public function apiVersion()
    {
        return response()->json([
            'success' => true,
            'data' => VersionHelper::getFullVersionInfo()
        ]);
    }

    /**
     * API para obter informações do sistema
     */
    public function apiSystemInfo()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'version' => VersionHelper::getFullVersionInfo(),
                'system' => VersionHelper::getSystemInfo(),
                'changelog' => VersionHelper::getCurrentChangelog()
            ]
        ]);
    }
} 