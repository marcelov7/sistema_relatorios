@extends('layouts.app')

@section('title', 'Novo Local - Sistema de Relatórios')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">
                    <i class="bi bi-house me-1"></i>
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('locais.index') }}">Locais</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Novo Local</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="bi bi-plus-circle me-2"></i>
                        Criar Novo Local
                    </h1>
                    <p class="text-muted mb-0">Preencha as informações do novo local</p>
                </div>
                <div>
                    <a href="{{ route('locais.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Voltar para Locais
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Informações do Local
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('locais.store') }}">
                        @csrf
                        
                        <!-- Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-bold">
                                Nome do Local <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nome') is-invalid @enderror" 
                                   id="nome" 
                                   name="nome" 
                                   value="{{ old('nome') }}" 
                                   placeholder="Ex: Escritório Principal, Depósito Central..."
                                   required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descrição -->
                        <div class="mb-3">
                            <label for="descricao" class="form-label fw-bold">
                                Descrição <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                      id="descricao" 
                                      name="descricao" 
                                      rows="3" 
                                      placeholder="Descreva o local e sua finalidade..."
                                      required>{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Endereço -->
                        <div class="mb-3">
                            <label for="endereco" class="form-label fw-bold">
                                Endereço
                            </label>
                            <input type="text" 
                                   class="form-control @error('endereco') is-invalid @enderror" 
                                   id="endereco" 
                                   name="endereco" 
                                   value="{{ old('endereco') }}" 
                                   placeholder="Ex: Rua das Flores, 123 - Centro">
                            <div class="form-text">Campo opcional. Informe o endereço físico do local.</div>
                            @error('endereco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Status do Local</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input @error('ativo') is-invalid @enderror" 
                                       type="checkbox" 
                                       role="switch" 
                                       id="ativo" 
                                       name="ativo" 
                                       value="1" 
                                       {{ old('ativo', '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="ativo">
                                    <span class="fw-bold text-success">Ativar local</span>
                                    <br>
                                    <small class="text-muted">Locais ativos podem receber relatórios e equipamentos</small>
                                </label>
                            </div>
                            @error('ativo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botões -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('locais.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>
                                Criar Local
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-check-input:checked {
        background-color: var(--bs-success);
        border-color: var(--bs-success);
    }
    
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
    }
    
    .form-control:focus {
        border-color: #6f42c1;
        box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
    }
</style>
@endpush 