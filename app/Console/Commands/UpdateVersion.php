<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'version:update 
                            {version : Nova versão (ex: 1.1.0)}
                            {--name= : Nome da versão (ex: "Aurora")}
                            {--type=minor : Tipo de release (major|minor|patch)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza a versão do sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $version = $this->argument('version');
        $versionName = $this->option('name') ?? $this->generateVersionName();
        $type = $this->option('type');
        
        // Validar formato da versão
        if (!preg_match('/^\d+\.\d+\.\d+$/', $version)) {
            $this->error('Formato de versão inválido. Use o formato: X.Y.Z (ex: 1.1.0)');
            return 1;
        }

        // Validar tipo de release
        if (!in_array($type, ['major', 'minor', 'patch'])) {
            $this->error('Tipo de release inválido. Use: major, minor ou patch');
            return 1;
        }

        $this->info("Atualizando sistema para versão {$version} \"{$versionName}\"...");

        try {
            // Atualizar config/app.php
            $this->updateAppConfig($version, $versionName);
            
            // Atualizar VersionHelper com novo changelog
            $this->updateVersionHelper($version, $versionName, $type);
            
            $this->info('✅ Versão atualizada com sucesso!');
            $this->line('');
            $this->line("📋 Resumo da atualização:");
            $this->line("   Versão: {$version}");
            $this->line("   Nome: \"{$versionName}\"");
            $this->line("   Tipo: {$type}");
            $this->line("   Build: " . $this->generateBuild());
            $this->line('');
            $this->warn('⚠️  Lembre-se de atualizar o changelog no VersionHelper com as mudanças desta versão!');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Erro ao atualizar versão: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Atualizar arquivo config/app.php
     */
    private function updateAppConfig($version, $versionName)
    {
        $configPath = config_path('app.php');
        $content = File::get($configPath);
        
        $build = $this->generateBuild();
        $releaseDate = now()->format('Y-m-d');
        
        // Substituir valores de versão
        $content = preg_replace(
            "/'version' => '[^']*'/",
            "'version' => '{$version}'",
            $content
        );
        
        $content = preg_replace(
            "/'version_name' => '[^']*'/",
            "'version_name' => '{$versionName}'",
            $content
        );
        
        $content = preg_replace(
            "/'release_date' => '[^']*'/",
            "'release_date' => '{$releaseDate}'",
            $content
        );
        
        $content = preg_replace(
            "/'build' => '[^']*'/",
            "'build' => '{$build}'",
            $content
        );
        
        File::put($configPath, $content);
        $this->line("✅ Arquivo config/app.php atualizado");
    }

    /**
     * Atualizar VersionHelper (placeholder para changelog)
     */
    private function updateVersionHelper($version, $versionName, $type)
    {
        $this->line("✅ VersionHelper preparado para nova versão");
        $this->line("   📝 Adicione manualmente o changelog para a versão {$version} no método getChangelog()");
    }

    /**
     * Gerar número de build baseado na data/hora
     */
    private function generateBuild()
    {
        return now()->format('YmdHis');
    }

    /**
     * Gerar nome de versão aleatório se não fornecido
     */
    private function generateVersionName()
    {
        $names = [
            'Aurora', 'Nebula', 'Cosmos', 'Stellar', 'Phoenix', 'Quantum',
            'Infinity', 'Horizon', 'Eclipse', 'Zenith', 'Apex', 'Vortex',
            'Prism', 'Nexus', 'Fusion', 'Matrix', 'Catalyst', 'Velocity',
            'Spectrum', 'Pinnacle', 'Summit', 'Vertex', 'Radiance', 'Luminous'
        ];
        
        return $names[array_rand($names)];
    }
}
