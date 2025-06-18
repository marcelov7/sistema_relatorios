<!-- Barra de Ferramentas de Acessibilidade Simples -->
<div style="position: fixed; top: 50%; right: 0; z-index: 1050;">
    <button id="accessBtn" style="background: #6f42c1; color: white; border: none; padding: 12px 8px; border-radius: 8px 0 0 8px; cursor: pointer;">
        <i class="bi bi-universal-access"></i>
    </button>
    
    <div id="accessPanel" style="position: absolute; right: 100%; top: 0; width: 280px; background: #2d2d2d; color: white; border-radius: 8px 0 0 8px; box-shadow: -5px 0 20px rgba(0,0,0,0.3); transform: translateX(100%); opacity: 0; visibility: hidden; transition: all 0.3s ease;">
        <div style="padding: 15px; border-bottom: 1px solid #4a4a4a;">
            <h6 style="margin: 0; display: inline-block;">Acessibilidade</h6>
            <button id="closeBtn" style="float: right; background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer;">&times;</button>
        </div>
        
        <div style="padding: 15px;">
            <button id="darkBtn" style="background: #3a3a3a; border: 1px solid #4a4a4a; color: white; padding: 10px; border-radius: 6px; cursor: pointer; width: 100%; margin-bottom: 10px;">
                <i class="bi bi-moon-fill"></i> Modo Escuro
            </button>
            
            <button id="contrastBtn" style="background: #3a3a3a; border: 1px solid #4a4a4a; color: white; padding: 10px; border-radius: 6px; cursor: pointer; width: 100%; margin-bottom: 10px;">
                <i class="bi bi-contrast"></i> Alto Contraste
            </button>
        </div>
    </div>
</div>

<style>
.show-panel {
    transform: translateX(0) !important;
    opacity: 1 !important;
    visibility: visible !important;
}

body.dark-mode {
    background-color: #1a1a1a !important;
    color: #ffffff !important;
}

body.dark-mode .card {
    background-color: #2d2d2d !important;
    color: #ffffff !important;
}

body.high-contrast {
    filter: contrast(150%) brightness(110%);
}
</style>

<script>
console.log('Carregando script de acessibilidade simples...');

function initSimpleAccessibility() {
    console.log('Inicializando acessibilidade simples...');
    
    const btn = document.getElementById('accessBtn');
    const panel = document.getElementById('accessPanel');
    const closeBtn = document.getElementById('closeBtn');
    const darkBtn = document.getElementById('darkBtn');
    const contrastBtn = document.getElementById('contrastBtn');
    
    console.log('Botão encontrado:', !!btn);
    console.log('Panel encontrado:', !!panel);
    
    if (!btn || !panel) {
        console.error('Elementos não encontrados!');
        return;
    }
    
    // Toggle panel
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Botão clicado!');
        panel.classList.toggle('show-panel');
    });
    
    // Close panel
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            panel.classList.remove('show-panel');
        });
    }
    
    // Dark mode
    if (darkBtn) {
        darkBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Modo escuro clicado');
            document.body.classList.toggle('dark-mode');
        });
    }
    
    // High contrast
    if (contrastBtn) {
        contrastBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Alto contraste clicado');
            document.body.classList.toggle('high-contrast');
        });
    }
    
    console.log('Acessibilidade simples inicializada!');
}

// Inicializar quando o DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSimpleAccessibility);
} else {
    initSimpleAccessibility();
}
</script> 