@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-bug me-2"></i>
                        Diagnóstico de Upload de Imagens
                    </h4>
                </div>
                <div class="card-body">
                    
                    <!-- Configurações PHP -->
                    <div class="alert alert-info">
                        <h5><i class="bi bi-gear me-2"></i>Configurações PHP</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><strong>upload_max_filesize:</strong> {{ ini_get('upload_max_filesize') }}</li>
                                    <li><strong>post_max_size:</strong> {{ ini_get('post_max_size') }}</li>
                                    <li><strong>max_file_uploads:</strong> {{ ini_get('max_file_uploads') }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><strong>memory_limit:</strong> {{ ini_get('memory_limit') }}</li>
                                    <li><strong>max_execution_time:</strong> {{ ini_get('max_execution_time') }}</li>
                                    <li><strong>file_uploads:</strong> {{ ini_get('file_uploads') ? 'Habilitado' : 'Desabilitado' }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Verificações de Diretórios -->
                    <div class="alert alert-secondary">
                        <h5><i class="bi bi-folder me-2"></i>Verificações de Diretórios</h5>
                        @php
                            $storagePublic = storage_path('app/public');
                            $storageRelatorios = storage_path('app/public/relatorios');
                            $publicStorage = public_path('storage');
                        @endphp
                        
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Storage Public:</strong><br>
                                <small>{{ $storagePublic }}</small>
                                <ul class="list-unstyled mt-2">
                                    <li>Existe: {!! is_dir($storagePublic) ? '<span class="text-success">✅</span>' : '<span class="text-danger">❌</span>' !!}</li>
                                    <li>Gravável: {!! is_writable($storagePublic) ? '<span class="text-success">✅</span>' : '<span class="text-danger">❌</span>' !!}</li>
                                    <li>Permissões: {{ file_exists($storagePublic) ? substr(sprintf('%o', fileperms($storagePublic)), -4) : 'N/A' }}</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <strong>Storage Relatórios:</strong><br>
                                <small>{{ $storageRelatorios }}</small>
                                <ul class="list-unstyled mt-2">
                                    <li>Existe: {!! is_dir($storageRelatorios) ? '<span class="text-success">✅</span>' : '<span class="text-danger">❌</span>' !!}</li>
                                    <li>Gravável: {!! is_writable($storageRelatorios) ? '<span class="text-success">✅</span>' : '<span class="text-danger">❌</span>' !!}</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <strong>Public Storage:</strong><br>
                                <small>{{ $publicStorage }}</small>
                                <ul class="list-unstyled mt-2">
                                    <li>Existe: {!! file_exists($publicStorage) ? '<span class="text-success">✅</span>' : '<span class="text-danger">❌</span>' !!}</li>
                                    <li>É Link: {!! is_link($publicStorage) ? '<span class="text-success">✅</span>' : '<span class="text-danger">❌</span>' !!}</li>
                                    @if(is_link($publicStorage))
                                        <li>Aponta para: {{ readlink($publicStorage) }}</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Espaço em Disco -->
                    <div class="alert alert-warning">
                        <h5><i class="bi bi-hdd me-2"></i>Espaço em Disco</h5>
                        @php
                            $freeBytes = disk_free_space(storage_path());
                            $totalBytes = disk_total_space(storage_path());
                        @endphp
                        
                        @if($freeBytes && $totalBytes)
                            @php
                                $freeGB = round($freeBytes / 1024 / 1024 / 1024, 2);
                                $totalGB = round($totalBytes / 1024 / 1024 / 1024, 2);
                                $usedPercent = round((($totalBytes - $freeBytes) / $totalBytes) * 100, 2);
                            @endphp
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Espaço livre:</strong> {{ $freeGB }} GB
                                </div>
                                <div class="col-md-4">
                                    <strong>Espaço total:</strong> {{ $totalGB }} GB
                                </div>
                                <div class="col-md-4">
                                    <strong>Uso:</strong> {{ $usedPercent }}%
                                </div>
                            </div>
                            
                            @if($freeGB < 1)
                                <div class="alert alert-danger mt-2">
                                    ⚠️ <strong>Aviso:</strong> Pouco espaço em disco disponível!
                                </div>
                            @endif
                        @else
                            <p>Não foi possível verificar o espaço em disco</p>
                        @endif
                    </div>

                    <!-- Teste de Escrita -->
                    <div class="alert alert-success">
                        <h5><i class="bi bi-pencil me-2"></i>Teste de Escrita</h5>
                        @php
                            $testResult = null;
                            $testFile = storage_path('app/public/relatorios/test_' . time() . '.txt');
                            $testContent = 'Teste de escrita: ' . date('Y-m-d H:i:s');
                            
                            try {
                                if (!is_dir(dirname($testFile))) {
                                    Storage::disk('public')->makeDirectory('relatorios');
                                }
                                
                                $result = file_put_contents($testFile, $testContent);
                                
                                if ($result !== false) {
                                    $testResult = "✅ Arquivo de teste criado ({$result} bytes)";
                                    
                                    // Verificar leitura
                                    $readContent = file_get_contents($testFile);
                                    if ($readContent === $testContent) {
                                        $testResult .= "<br>✅ Arquivo lido corretamente";
                                    } else {
                                        $testResult .= "<br>❌ Erro na leitura do arquivo";
                                    }
                                    
                                    // Remover arquivo
                                    if (unlink($testFile)) {
                                        $testResult .= "<br>✅ Arquivo removido com sucesso";
                                    } else {
                                        $testResult .= "<br>⚠️ Arquivo não pôde ser removido";
                                    }
                                } else {
                                    $testResult = "❌ Não foi possível criar arquivo de teste";
                                }
                            } catch (Exception $e) {
                                $testResult = "❌ Erro: " . $e->getMessage();
                            }
                        @endphp
                        
                        {!! $testResult !!}
                    </div>

                    <!-- Últimas Imagens -->
                    <div class="alert alert-info">
                        <h5><i class="bi bi-images me-2"></i>Últimas Imagens Carregadas</h5>
                        @php
                            $ultimasImagens = \App\Models\RelatorioImagem::orderBy('data_upload', 'desc')->take(5)->get();
                        @endphp
                        
                        @if($ultimasImagens->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Nome</th>
                                            <th>Tamanho</th>
                                            <th>Data Upload</th>
                                            <th>Caminho</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ultimasImagens as $imagem)
                                            @php
                                                $existe = Storage::disk('public')->exists($imagem->caminho_arquivo);
                                                $tamanho = $existe ? Storage::disk('public')->size($imagem->caminho_arquivo) : 0;
                                                $tamanhoMB = round($tamanho / 1024 / 1024, 2);
                                            @endphp
                                            <tr>
                                                <td>{!! $existe ? '<span class="text-success">✅</span>' : '<span class="text-danger">❌</span>' !!}</td>
                                                <td>{{ $imagem->nome_original }}</td>
                                                <td>{{ $tamanhoMB }} MB</td>
                                                <td>{{ $imagem->data_upload->format('d/m/Y H:i') }}</td>
                                                <td><small>{{ $imagem->caminho_arquivo }}</small></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>Nenhuma imagem encontrada no banco de dados.</p>
                        @endif
                    </div>

                    <!-- Configurações Laravel -->
                    <div class="alert alert-light">
                        <h5><i class="bi bi-gear-fill me-2"></i>Configurações Laravel</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><strong>APP_ENV:</strong> {{ config('app.env') }}</li>
                                    <li><strong>APP_DEBUG:</strong> {{ config('app.debug') ? 'true' : 'false' }}</li>
                                    <li><strong>FILESYSTEM_DISK:</strong> {{ config('filesystems.default') }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><strong>APP_URL:</strong> {{ config('app.url') }}</li>
                                    <li><strong>Storage URL:</strong> {{ config('filesystems.disks.public.url') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Possíveis Soluções -->
                    <div class="alert alert-primary">
                        <h5><i class="bi bi-lightbulb me-2"></i>Possíveis Soluções</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6>1. Configurações PHP</h6>
                                <p>Edite o arquivo <code>php.ini</code>:</p>
                                <pre class="small"><code>upload_max_filesize = 10M
post_max_size = 50M
max_file_uploads = 20</code></pre>
                            </div>
                            <div class="col-md-6">
                                <h6>2. Permissões</h6>
                                <p>Execute no servidor:</p>
                                <pre class="small"><code>sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/</code></pre>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6>3. Link Simbólico</h6>
                                <pre class="small"><code>php artisan storage:link</code></pre>
                            </div>
                            <div class="col-md-6">
                                <h6>4. Comando de Diagnóstico</h6>
                                <pre class="small"><code>php artisan relatorios:diagnosticar-upload</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 