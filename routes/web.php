<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Relatorio;
use App\Models\Notificacao;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\RelatorioV2Controller;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\AnalisadorController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\InspecaoGeradorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\AccessibilityController;
use App\Http\Controllers\GeradorController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('relatorios.dashboard');
    }
    return view('welcome');
});

// Rota de teste para verificar o banco
Route::get('/teste-banco', function () {
    try {
        // Teste de conexão
        DB::connection()->getPdo();
        
        // Contagem de registros
        $usuarios = User::count();
        $relatorios = Relatorio::count();
        $notificacoes = Notificacao::count();
        
        return response()->json([
            'status' => 'Conexão OK!',
            'banco' => env('DB_DATABASE'),
            'contagens' => [
                'usuarios' => $usuarios,
                'relatorios' => $relatorios,
                'notificacoes' => $notificacoes,
            ]
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'Erro na conexão',
            'erro' => $e->getMessage()
        ], 500);
    }
});

// Rota para listar usuários (teste)
Route::get('/teste-usuarios', function () {
    try {
        $usuarios = User::select('id', 'name', 'email', 'cargo', 'departamento', 'ativo')
                       ->take(5)
                       ->get();
        
        return response()->json([
            'status' => 'OK',
            'usuarios' => $usuarios
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'Erro',
            'erro' => $e->getMessage()
        ], 500);
    }
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rotas de Relatórios (usuários autenticados)
Route::middleware('auth')->group(function () {
    // Dashboard de relatórios
    Route::get('/relatorios/dashboard', [RelatorioController::class, 'dashboard'])->name('relatorios.dashboard');
    
    // CRUD de relatórios
    Route::resource('relatorios', RelatorioController::class);
    
    // Rotas V2 (versão de teste com múltiplos equipamentos)
    Route::get('/relatorios-v2/create', [RelatorioV2Controller::class, 'create'])->name('relatorios-v2.create');
    Route::post('/relatorios-v2', [RelatorioV2Controller::class, 'store'])->name('relatorios-v2.store');
    Route::get('/api/equipamentos-por-local', [RelatorioV2Controller::class, 'equipamentosPorLocal'])->name('api.equipamentos-por-local');
    
    // Ações específicas
    Route::patch('/relatorios/{relatorio}/progresso', [RelatorioController::class, 'updateProgresso'])->name('relatorios.update-progresso');
    Route::post('/relatorios/{relatorio}/progresso', [RelatorioController::class, 'updateProgresso'])->name('relatorios.updateProgresso');
    Route::post('/relatorios/{relatorio}/duplicate', [RelatorioController::class, 'duplicate'])->name('relatorios.duplicate');
    
    // Imagens dos relatórios
    Route::delete('/relatorio-imagens/{imagem}', [RelatorioController::class, 'removerImagem'])->name('relatorio-imagens.destroy');
    
    // Apenas admin pode alterar editabilidade
    Route::middleware('role:admin')->group(function () {
        Route::patch('/relatorios/{relatorio}/toggle-editavel', [RelatorioController::class, 'toggleEditavel'])->name('relatorios.toggle-editavel');
    });
    
    // Rotas de Locais
    Route::resource('locais', LocalController::class)->parameters(['locais' => 'local']);
    Route::patch('/locais/{local}/toggle-status', [LocalController::class, 'toggleStatus'])->name('locais.toggle-status');
    
    // Rotas de Equipamentos
    Route::resource('equipamentos', EquipamentoController::class);
    Route::patch('/equipamentos/{equipamento}/toggle-status', [EquipamentoController::class, 'toggleStatus'])->name('equipamentos.toggle-status');
    
    // API para listagem de locais e equipamentos ativos
    Route::get('/api/locais/ativos', [LocalController::class, 'apiLocaisAtivos'])->name('api.locais.ativos');
    Route::get('/api/equipamentos/ativos', [EquipamentoController::class, 'apiEquipamentosAtivos'])->name('api.equipamentos.ativos');

    // --- Atribuição de usuários a relatórios ---
    Route::get('/relatorios/{relatorio}/atribuicoes', [RelatorioController::class, 'atribuicoes'])->name('relatorios.atribuicoes');
    Route::post('/relatorios/{relatorio}/atribuir', [RelatorioController::class, 'atribuirUsuario'])->name('relatorios.atribuir');
    Route::delete('/relatorios/{relatorio}/remover-atribuicao/{userId}', [RelatorioController::class, 'removerAtribuicao'])->name('relatorios.remover-atribuicao');

    // --- Analisadores ---
    Route::resource('analisadores', AnalisadorController::class)->parameters(['analisadores' => 'analisador']);
    Route::patch('/analisadores/{analisador}/toggle-status', [AnalisadorController::class, 'toggleStatus'])->name('analisadores.toggle-status');
    Route::post('/analisadores/{analisador}/duplicate', [AnalisadorController::class, 'duplicate'])->name('analisadores.duplicate');

    // --- Motores ---
    Route::resource('motores', MotorController::class)->parameters(['motores' => 'motor']);
    Route::post('/motores/{motor}/duplicate', [MotorController::class, 'duplicate'])->name('motores.duplicate');
    Route::delete('/motores/{motor}/photo', [MotorController::class, 'deletePhoto'])->name('motores.delete-photo');

    // --- Inspeções de Gerador ---
    Route::resource('inspecoes-gerador', InspecaoGeradorController::class)->parameters(['inspecoes-gerador' => 'inspecaoGerador']);
    Route::post('/inspecoes-gerador/{inspecaoGerador}/duplicate', [InspecaoGeradorController::class, 'duplicate'])->name('inspecoes-gerador.duplicate');
    Route::patch('/inspecoes-gerador/{inspecaoGerador}/toggle-status', [InspecaoGeradorController::class, 'toggleStatus'])->name('inspecoes-gerador.toggle-status');
    
    // --- Analytics ---
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.dashboard');
    Route::get('/analytics/dados-graficos', [AnalyticsController::class, 'getDadosGraficos'])->name('analytics.dados-graficos');

    // --- PDFs ---
    Route::prefix('pdf')->name('pdf.')->group(function () {
        Route::get('/', [App\Http\Controllers\PDFController::class, 'index'])->name('index');
        Route::get('/relatorio/{relatorio}', [App\Http\Controllers\PDFController::class, 'relatorio'])->name('relatorio');
        Route::get('/inspecao/{inspecaoGerador}', [App\Http\Controllers\PDFController::class, 'inspecao'])->name('inspecao');
        Route::get('/analisador/{analisador}', [App\Http\Controllers\PDFController::class, 'analisador'])->name('analisador');
        Route::get('/analytics', [App\Http\Controllers\PDFController::class, 'analytics'])->name('analytics');
        Route::post('/relatorios-lote', [App\Http\Controllers\PDFController::class, 'relatoriosLote'])->name('relatorios-lote');
        Route::post('/inspecoes-lote', [App\Http\Controllers\PDFController::class, 'inspecoesLote'])->name('inspecoes-lote');
    });

    // --- Perfil do Usuário ---
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // --- Sistema e Versionamento ---
    Route::get('/system/info', [SystemController::class, 'info'])->name('system.info');
    Route::get('/system/changelog', [SystemController::class, 'changelog'])->name('system.changelog');
    Route::get('/system/upload-debug', function () {
        return view('system.upload-debug');
    })->name('system.upload-debug');
    
    // --- Acessibilidade ---
    Route::get('/accessibility', [AccessibilityController::class, 'index'])->name('accessibility.index');
    Route::post('/accessibility', [AccessibilityController::class, 'store'])->name('accessibility.store');
    Route::get('/api/accessibility/settings', [AccessibilityController::class, 'getSettings'])->name('api.accessibility.settings');
    Route::post('/api/accessibility/toggle-dark-mode', [AccessibilityController::class, 'toggleDarkMode'])->name('api.accessibility.toggle-dark-mode');
    
    // APIs do Sistema
    Route::get('/api/system/version', [SystemController::class, 'apiVersion'])->name('api.system.version');
    Route::get('/api/system/info', [SystemController::class, 'apiSystemInfo'])->name('api.system.info');
});

// Rotas Administrativas
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,supervisor'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Gestão de Usuários (apenas admin)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        Route::resource('roles', RoleController::class);
    });
});

// Rotas de PDF
Route::get('/relatorio/{id}/pdf', [PDFController::class, 'gerarRelatorio'])->name('relatorio.pdf');
Route::get('/relatorio/{id}/pdf-browsershot', [PDFController::class, 'gerarRelatorioBrowsershot'])->name('relatorio.pdf.browsershot');
Route::get('/inspecao/{id}/pdf-browsershot', [PDFController::class, 'gerarInspecaoBrowsershot'])->name('inspecao.pdf.browsershot');
Route::get('/analisador/{id}/pdf-browsershot', [PDFController::class, 'gerarAnalisadorBrowsershot'])->name('analisador.pdf.browsershot');
Route::get('/analytics/pdf', [PDFController::class, 'gerarAnalytics'])->name('analytics.pdf');
Route::get('/analytics/pdf-browsershot', [PDFController::class, 'gerarAnalyticsBrowsershot'])->name('analytics.pdf.browsershot');

// Rota de teste para PDF do gerador
Route::get('/teste-pdf-gerador/{id}', function($id) {
    $inspecao = App\Models\InspecaoGerador::findOrFail($id);
    $controller = new App\Http\Controllers\PDFController();
    return $controller->inspecao($inspecao);
})->name('teste.pdf.gerador');



// Rota de teste para acessibilidade
Route::get('/test-accessibility', [AccessibilityController::class, 'test'])->name('test.accessibility');
