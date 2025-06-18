@extends('pdf.layout')

@section('title', 'Relatório #' . $relatorio->id)

@section('header-title', 'Relatório de Problema #' . $relatorio->id)
@section('header-subtitle', 'Detalhes completos do relatório')

@section('content')
<div class="section no-break">
    <div class="section-title">Informações Gerais</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">ID do Relatório:</div>
            <div class="info-value">#{{ $relatorio->id }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Data da Ocorrencia:</div>
            <div class="info-value">{{ $relatorio->data_ocorrencia ? $relatorio->data_ocorrencia->format('d/m/Y H:i') : 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Criado por:</div>
            <div class="info-value">{{ $relatorio->usuario->name ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">
                <span class="status-badge status-{{ $relatorio->status }}">
                    {{ ucfirst(str_replace('_', ' ', $relatorio->status)) }}
                </span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Prioridade:</div>
            <div class="info-value">
                <span class="priority-badge priority-{{ $relatorio->prioridade }}">
                    {{ ucfirst($relatorio->prioridade) }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="section no-break">
    <div class="section-title">Local e Equipamento</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Local:</div>
            <div class="info-value">{{ $relatorio->local->nome ?? 'N/A' }}</div>
        </div>
        @if($relatorio->local && $relatorio->local->endereco)
        <div class="info-row">
            <div class="info-label">Endereço:</div>
            <div class="info-value">{{ $relatorio->local->endereco }}</div>
        </div>
        @endif
        <div class="info-row">
            <div class="info-label">Equipamento:</div>
            <div class="info-value">{{ $relatorio->equipamento->nome ?? 'N/A' }}</div>
        </div>
        @if($relatorio->equipamento && $relatorio->equipamento->codigo)
        <div class="info-row">
            <div class="info-label">Código do Equipamento:</div>
            <div class="info-value">{{ $relatorio->equipamento->codigo }}</div>
        </div>
        @endif
        @if($relatorio->equipamento && $relatorio->equipamento->modelo)
        <div class="info-row">
            <div class="info-label">Modelo:</div>
            <div class="info-value">{{ $relatorio->equipamento->modelo }}</div>
        </div>
        @endif
    </div>
</div>

<div class="section">
    <div class="section-title">Descrição do Problema</div>
    <div class="description-box">
        <h4>Título:</h4>
        <p>{{ $relatorio->titulo }}</p>
    </div>
    <div class="description-box">
        <h4>Descrição Detalhada:</h4>
        <p>{{ $relatorio->descricao }}</p>
    </div>
</div>

@if($relatorio->solucao)
<div class="section">
    <div class="section-title">Solução Aplicada</div>
    <div class="description-box">
        <p>{{ $relatorio->solucao }}</p>
    </div>
</div>
@endif

@if($relatorio->observacoes)
<div class="section">
    <div class="section-title">Observações</div>
    <div class="description-box">
        <p>{{ $relatorio->observacoes }}</p>
    </div>
</div>
@endif

@if($relatorio->historicos && $relatorio->historicos->count() > 0)
<div class="section page-break">
    <div class="section-title">Histórico de Alterações</div>
    <table class="table">
        <thead>
            <tr>
                <th>Data</th>
                <th>Usuário</th>
                <th>Ação</th>
                <th>Detalhes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($relatorio->historicos as $historico)
            <tr>
                <td>{{ $historico->data_atualizacao ? $historico->data_atualizacao->format('d/m/Y H:i') : 'N/A' }}</td>
                <td>{{ $historico->usuario->name ?? 'Sistema' }}</td>
                <td>
                    @if($historico->status_anterior && $historico->status_novo)
                        Status: {{ ucfirst(str_replace('_', ' ', $historico->status_anterior)) }} → {{ ucfirst(str_replace('_', ' ', $historico->status_novo)) }}
                    @else
                        Atualização
                    @endif
                </td>
                <td>{{ $historico->descricao ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if($relatorio->imagens && $relatorio->imagens->count() > 0)
<div class="section page-break">
    <div class="section-title">Imagens Anexadas</div>
    <p class="mb-20">Total de {{ $relatorio->imagens->count() }} imagem(ns) anexada(s) ao relatório.</p>
    
    @foreach($relatorio->imagens as $index => $imagem)
    <div class="no-break mb-20">
        <div style="border: 1px solid #ddd; padding: 15px; margin: 10px 0; background: #f9f9f9;">
            <h4 style="margin: 0 0 10px 0; color: #333;">
                📷 Imagem {{ $index + 1 }}: {{ $imagem->nome_original ?: $imagem->nome_arquivo }}
            </h4>
            
            @php
                $caminhoCompleto = storage_path('app/public/' . $imagem->caminho_arquivo);
                $imagemExiste = file_exists($caminhoCompleto);
                $tamanhoArquivo = $imagemExiste ? filesize($caminhoCompleto) : 0;
            @endphp
            
            <div style="display: table; width: 100%; margin-bottom: 10px;">
                <div style="display: table-row;">
                    <div style="display: table-cell; width: 120px; font-weight: bold; color: #666;">Adicionada em:</div>
                    <div style="display: table-cell;">{{ $imagem->data_upload ? $imagem->data_upload->format('d/m/Y H:i') : 'N/A' }}</div>
                </div>
                @if($imagem->descricao)
                <div style="display: table-row;">
                    <div style="display: table-cell; width: 120px; font-weight: bold; color: #666;">Descrição:</div>
                    <div style="display: table-cell;">{{ $imagem->descricao }}</div>
                </div>
                @endif
            </div>
            
            @if(!$imagemExiste)
                <div style="background: #f8d7da; border: 1px solid #dc3545; padding: 10px; border-radius: 4px;">
                    <p style="margin: 0; color: #721c24; font-weight: bold;">❌ Arquivo não encontrado</p>
                </div>
                
            @elseif(!$imagem->isImagem())
                <div style="background: #f8f9fa; border: 1px solid #6c757d; padding: 10px; border-radius: 4px;">
                    <p style="margin: 0; color: #495057; font-weight: bold;">📎 Arquivo anexado</p>
                </div>
                
            @elseif($tamanhoArquivo > 1024 * 1024)
                <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 10px; border-radius: 4px;">
                    <p style="margin: 0; color: #856404; font-weight: bold;">⚠️ Imagem muito grande</p>
                </div>
                
            @else
                {{-- Imagem válida e de tamanho adequado --}}
                <div style="background: #d4edda; border: 1px solid #28a745; padding: 10px; border-radius: 4px;">
                    <p style="margin: 0; color: #155724; font-weight: bold;">✅ Imagem incluída no relatório</p>
                </div>
                
                {{-- Tentar incluir a imagem usando base64 otimizado --}}
                @php
                    $imagemIncluida = false;
                    try {
                        if ($imagem->tipo_mime === 'image/png') {
                            // Converter PNG para JPEG
                            $img = imagecreatefrompng($caminhoCompleto);
                            if ($img) {
                                $info = getimagesize($caminhoCompleto);
                                $novaImg = imagecreatetruecolor($info[0], $info[1]);
                                $branco = imagecolorallocate($novaImg, 255, 255, 255);
                                imagefill($novaImg, 0, 0, $branco);
                                imagecopy($novaImg, $img, 0, 0, 0, 0, $info[0], $info[1]);
                                
                                ob_start();
                                imagejpeg($novaImg, null, 75);
                                $dadosImagem = ob_get_contents();
                                ob_end_clean();
                                
                                imagedestroy($img);
                                imagedestroy($novaImg);
                                
                                $base64 = base64_encode($dadosImagem);
                                $imagemIncluida = true;
                            }
                        } elseif ($imagem->tipo_mime === 'image/jpeg') {
                            // JPEG direto, mas otimizado
                            $img = imagecreatefromjpeg($caminhoCompleto);
                            if ($img) {
                                ob_start();
                                imagejpeg($img, null, 75);
                                $dadosImagem = ob_get_contents();
                                ob_end_clean();
                                
                                imagedestroy($img);
                                
                                $base64 = base64_encode($dadosImagem);
                                $imagemIncluida = true;
                            }
                        }
                    } catch (Exception $e) {
                        $imagemIncluida = false;
                    }
                @endphp
                
                @if($imagemIncluida && isset($base64))
                    <div style="text-align: center; margin: 15px 0; page-break-inside: avoid;">
                        <div style="border: 2px solid #28a745; padding: 8px; display: inline-block; background: white;">
                            <img src="data:image/jpeg;base64,{{ $base64 }}" 
                                 style="max-width: 250px; max-height: 180px; width: auto; height: auto;" 
                                 alt="Imagem {{ $index + 1 }}">
                        </div>
                    </div>
                    @php unset($dadosImagem, $base64); @endphp
                @endif
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

<div class="section">
    <div class="section-title">Informações do Sistema</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Criado em:</div>
            <div class="info-value">{{ $relatorio->data_criacao ? $relatorio->data_criacao->format('d/m/Y H:i:s') : 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Ultima atualizacao:</div>
            <div class="info-value">{{ $relatorio->data_atualizacao ? $relatorio->data_atualizacao->format('d/m/Y H:i:s') : 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tempo total:</div>
            <div class="info-value">
                @if($relatorio->status === 'resolvido' && $relatorio->data_criacao && $relatorio->data_atualizacao)
                    @php
                        $tempoTotal = $relatorio->data_criacao->diffInDays($relatorio->data_atualizacao);
                        $tempoHoras = $relatorio->data_criacao->diffInHours($relatorio->data_atualizacao) % 24;
                        $tempoMinutos = $relatorio->data_criacao->diffInMinutes($relatorio->data_atualizacao) % 60;
                    @endphp
                    @if($tempoTotal > 0)
                        {{ $tempoTotal }} dia(s)
                        @if($tempoHoras > 0)
                            {{ $tempoHoras }} hora(s)
                        @endif
                    @elseif($tempoHoras > 0)
                        {{ $tempoHoras }} hora(s)
                        @if($tempoMinutos > 0)
                            {{ $tempoMinutos }} minuto(s)
                        @endif
                    @else
                        {{ $tempoMinutos }} minuto(s)
                    @endif
                @elseif($relatorio->status !== 'resolvido')
                    Em aberto há {{ $relatorio->data_criacao ? $relatorio->data_criacao->diffForHumans() : 'N/A' }}
                @else
                    N/A
                @endif
            </div>
        </div>
        @if($relatorio->progresso)
        <div class="info-row">
            <div class="info-label">Progresso:</div>
            <div class="info-value">{{ $relatorio->progresso }}%</div>
        </div>
        @endif
    </div>
</div>
@endsection 