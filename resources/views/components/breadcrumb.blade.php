@if(!empty($breadcrumbs) && count($breadcrumbs) > 1)
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb modern-breadcrumb mb-0">
        @foreach($breadcrumbs as $breadcrumb)
            <li class="breadcrumb-item {{ $breadcrumb['active'] ? 'active' : '' }}" 
                {{ $breadcrumb['active'] ? 'aria-current=page' : '' }}>
                @if(!$breadcrumb['active'])
                    <a href="{{ $breadcrumb['url'] }}" class="breadcrumb-link">
                        <i class="bi {{ $breadcrumb['icon'] }} me-1"></i>
                        {{ $breadcrumb['title'] }}
                    </a>
                @else
                    <span class="breadcrumb-current">
                        <i class="bi {{ $breadcrumb['icon'] }} me-1"></i>
                        {{ $breadcrumb['title'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

<style>
.modern-breadcrumb {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 0.75rem 1.25rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.modern-breadcrumb:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.breadcrumb-item {
    font-size: 0.875rem;
    font-weight: 500;
}

.breadcrumb-link {
    color: #6c757d;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: 8px;
    border: 1px solid transparent;
}

.breadcrumb-link:hover {
    color: var(--primary-color, #6f42c1);
    background: rgba(111, 66, 193, 0.05);
    border-color: rgba(111, 66, 193, 0.1);
    text-decoration: none;
    transform: translateY(-1px);
}

.breadcrumb-current {
    color: var(--primary-color, #6f42c1);
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    background: linear-gradient(135deg, rgba(111, 66, 193, 0.1), rgba(139, 92, 246, 0.1));
    border-radius: 8px;
    border: 1px solid rgba(111, 66, 193, 0.2);
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #adb5bd;
    font-weight: bold;
    margin: 0 0.5rem;
    opacity: 0.7;
}

.breadcrumb-item.active .breadcrumb-current {
    animation: fadeInScale 0.3s ease;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@media (max-width: 768px) {
    .modern-breadcrumb {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }
    
    .breadcrumb-link,
    .breadcrumb-current {
        padding: 0.125rem 0.25rem;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        margin: 0 0.25rem;
    }
}

/* Animação de entrada */
.modern-breadcrumb {
    animation: slideInDown 0.4s ease;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endif 