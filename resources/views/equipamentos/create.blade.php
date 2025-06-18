@extends('layouts.app')

@section('title', 'Novo Equipamento')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Novo Equipamento
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('equipamentos.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="nome" class="form-label">Nome do Equipamento *</label>
                                <input type="text" 
                                       class="form-control @error('nome') is-invalid @enderror" 
                                       id="nome" 
                                       name="nome" 
                                       value="{{ old('nome') }}" 
                                       required>
                                @error('nome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="codigo" class="form-label">Código</label>
                                <input type="text" 
                                       class="form-control @error('codigo') is-invalid @enderror" 
                                       id="codigo" 
                                       name="codigo" 
                                       value="{{ old('codigo') }}" 
                                       placeholder="Ex: MOI-001">
                                @error('codigo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="descricao" class="form-label">Descrição *</label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                          id="descricao" 
                                          name="descricao" 
                                          rows="3" 
                                          required>{{ old('descricao') }}</textarea>
                                @error('descricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="local_id" class="form-label">Local *</label>
                                <select class="form-select @error('local_id') is-invalid @enderror" 
                                        id="local_id" 
                                        name="local_id" 
                                        required>
                                    <option value="">Selecione um local</option>
                                    @foreach($locais as $local)
                                        <option value="{{ $local->id }}" {{ old('local_id') == $local->id ? 'selected' : '' }}>
                                            {{ $local->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('local_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="tipo" class="form-label">Tipo</label>
                                <input type="text" 
                                       class="form-control @error('tipo') is-invalid @enderror" 
                                       id="tipo" 
                                       name="tipo" 
                                       value="{{ old('tipo') }}" 
                                       placeholder="Ex: Moinho, Esteira, Bomba">
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="fabricante" class="form-label">Fabricante</label>
                                <input type="text" 
                                       class="form-control @error('fabricante') is-invalid @enderror" 
                                       id="fabricante" 
                                       name="fabricante" 
                                       value="{{ old('fabricante') }}" 
                                       placeholder="Ex: ACME Industrial">
                                @error('fabricante')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="modelo" class="form-label">Modelo</label>
                                <input type="text" 
                                       class="form-control @error('modelo') is-invalid @enderror" 
                                       id="modelo" 
                                       name="modelo" 
                                       value="{{ old('modelo') }}" 
                                       placeholder="Ex: MX-5000">
                                @error('modelo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="numero_serie" class="form-label">Número de Série</label>
                                <input type="text" 
                                       class="form-control @error('numero_serie') is-invalid @enderror" 
                                       id="numero_serie" 
                                       name="numero_serie" 
                                       value="{{ old('numero_serie') }}" 
                                       placeholder="Ex: AC001234567">
                                @error('numero_serie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="data_instalacao" class="form-label">Data de Instalação</label>
                                <input type="date" 
                                       class="form-control @error('data_instalacao') is-invalid @enderror" 
                                       id="data_instalacao" 
                                       name="data_instalacao" 
                                       value="{{ old('data_instalacao') }}">
                                @error('data_instalacao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="status_operacional" class="form-label">Status Operacional *</label>
                                <select class="form-select @error('status_operacional') is-invalid @enderror" 
                                        id="status_operacional" 
                                        name="status_operacional" 
                                        required>
                                    @foreach($statusOptions as $key => $label)
                                        <option value="{{ $key }}" {{ old('status_operacional', 'operando') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status_operacional')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="ativo" class="form-label">Status *</label>
                                <select class="form-select @error('ativo') is-invalid @enderror" 
                                        id="ativo" 
                                        name="ativo" 
                                        required>
                                    <option value="1" {{ old('ativo', '1') == '1' ? 'selected' : '' }}>Ativo</option>
                                    <option value="0" {{ old('ativo') == '0' ? 'selected' : '' }}>Inativo</option>
                                </select>
                                @error('ativo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('equipamentos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Salvar Equipamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-resize textarea
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('descricao');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });
</script>
@endpush 