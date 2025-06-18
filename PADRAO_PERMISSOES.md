# 🔐 Padrão de Permissões do Sistema

## 📋 **Convenção de Nomenclatura**

### Formato: `[modulo]_[acao]`

**Ações Padrão:**
- `visualizar` - Permite listar e visualizar detalhes
- `criar` - Permite criar novos registros
- `editar` - Permite editar registros existentes  
- `excluir` - Permite deletar registros
- `gerenciar` - Permite todas as ações do módulo
- `atribuir` - Permite atribuir/designar a outros usuários

## 🏗️ **Processo para Novas Funcionalidades**

### 1. **Criação Automática de Permissões**
```bash
php artisan permissions:create-module equipamentos
# Cria: equipamentos_visualizar, equipamentos_criar, etc.
```

### 2. **Controller - Middleware Padrão**
```php
public function __construct()
{
    $this->middleware(['auth', 'permission:equipamentos_visualizar'])->only(['index', 'show']);
    $this->middleware(['auth', 'permission:equipamentos_criar'])->only(['create', 'store']);
    $this->middleware(['auth', 'permission:equipamentos_editar'])->only(['edit', 'update']);
    $this->middleware(['auth', 'permission:equipamentos_excluir'])->only(['destroy']);
    $this->middleware(['auth', 'permission:equipamentos_gerenciar'])->except(['show']);
}
```

### 3. **Rotas - Proteção por Grupo**
```php
Route::middleware(['permission:equipamentos_gerenciar'])->group(function() {
    Route::resource('equipamentos', EquipamentoController::class);
});
```

### 4. **Views - Blade Directives**
```blade
{{-- Botão Novo --}}
@can('equipamentos_criar')
    <a href="{{ route('equipamentos.create') }}" class="btn btn-primary">Novo</a>
@endcan

{{-- Botões Ação --}}
@can('equipamentos_editar')
    <a href="{{ route('equipamentos.edit', $equipamento) }}" class="btn btn-warning">Editar</a>
@endcan

@can('equipamentos_excluir')
    <form method="POST" action="{{ route('equipamentos.destroy', $equipamento) }}">
        @csrf @method('DELETE')
        <button class="btn btn-danger">Excluir</button>
    </form>
@endcan
```

### 5. **Menu de Navegação**
```blade
@can('equipamentos_visualizar')
    <a href="{{ route('equipamentos.index') }}" class="nav-link">
        <i class="bi bi-gear"></i> Equipamentos
    </a>
@endcan
```

### 6. **Helper de Permissões**
```php
use App\Helpers\PermissionHelper;

// Verificações simples
PermissionHelper::canView('equipamentos')
PermissionHelper::canCreate('equipamentos')  
PermissionHelper::canEdit('equipamentos')
PermissionHelper::canDelete('equipamentos')
PermissionHelper::canManage('equipamentos')

// Verificações múltiplas
PermissionHelper::canMultiple(['equipamentos_criar', 'equipamentos_editar'], 'or')

// Obter permissões do módulo
$permissoes = PermissionHelper::getModulePermissions('equipamentos');
```

## 📊 **Atribuição de Permissões por Role**

### **Admin**
- Todas as permissões de todos os módulos

### **Supervisor**  
- `[modulo]_visualizar`
- `[modulo]_criar`
- `[modulo]_editar` 
- `[modulo]_gerenciar`

### **Usuário**
- `[modulo]_visualizar` (apenas próprios registros)
- `[modulo]_criar`

## 🔄 **Comando de Criação Automática**

```bash
# Criar permissões para novo módulo
php artisan permissions:create-module [nome_modulo]

# Exemplos:
php artisan permissions:create-module equipamentos
php artisan permissions:create-module manutencoes
php artisan permissions:create-module inspeccoes

# Com ações específicas
php artisan permissions:create-module configuracoes --actions=visualizar,editar

# Atribuir automaticamente a roles
php artisan permissions:create-module equipamentos --roles=admin,supervisor
```

## 📝 **Checklist para Nova Funcionalidade**

- [ ] 1. Executar comando `permissions:create-module`
- [ ] 2. Adicionar middleware no Controller
- [ ] 3. Proteger rotas com permissions
- [ ] 4. Implementar `@can` nas views
- [ ] 5. Adicionar item no menu com `@can`
- [ ] 6. Testar com diferentes roles
- [ ] 7. Atualizar este documento se necessário

## 🎯 **Exemplos de Módulos Futuros**

### **Relatórios (Fase 3)**
```
relatorios_visualizar
relatorios_criar
relatorios_editar
relatorios_excluir
relatorios_gerenciar
relatorios_atribuir
```

### **Templates PDFs (Fase 4)**
```
templates_visualizar
templates_criar
templates_editar
templates_excluir
pdfs_gerar
```

### **Notificações (Fase 5)**
```
notificacoes_visualizar
notificacoes_criar
notificacoes_gerenciar
```

## ⚠️ **Boas Práticas**

1. **Sempre** usar o comando para criar permissões
2. **Sempre** proteger Controllers com middleware
3. **Sempre** usar `@can` nas views para botões/links
4. **Sempre** testar com diferentes roles após implementar
5. **Nunca** usar role direto, sempre usar permissions
6. **Documentar** novas permissões específicas se necessário

---

**Última atualização:** $(date)  
**Próxima revisão:** Após implementação da Fase 3 