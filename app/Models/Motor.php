<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    use HasFactory;

    protected $table = 'motores';

    protected $fillable = [
        'tag',
        'equipment',
        'frame_manufacturer',
        'power_kw',
        'power_cv',
        'rotation',
        'rated_current',
        'configured_current',
        'equipment_type',
        'manufacturer',
        'stock_reserve',
        'location',
        'photo',
        'storage'
    ];

    protected $casts = [
        'power_kw' => 'decimal:2',
        'power_cv' => 'decimal:2',
        'rotation' => 'integer',
        'rated_current' => 'decimal:2',
        'configured_current' => 'decimal:2'
    ];

    // Override timestamps para usar nomes do banco existente
    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = 'data_atualizacao';

    /**
     * Scopes
     */
    public function scopeByTag($query, $tag)
    {
        return $query->where('tag', 'like', "%{$tag}%");
    }

    public function scopeByEquipment($query, $equipment)
    {
        return $query->where('equipment', 'like', "%{$equipment}%");
    }

    public function scopeByManufacturer($query, $manufacturer)
    {
        return $query->where('manufacturer', 'like', "%{$manufacturer}%");
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    /**
     * Accessors
     */
    public function getPowerDisplayAttribute()
    {
        $parts = [];
        if ($this->power_kw) {
            $parts[] = $this->power_kw . ' kW';
        }
        if ($this->power_cv) {
            $parts[] = $this->power_cv . ' CV';
        }
        return implode(' / ', $parts) ?: 'N/A';
    }

    public function getCurrentDisplayAttribute()
    {
        $parts = [];
        if ($this->rated_current) {
            $parts[] = 'Nominal: ' . $this->rated_current . 'A';
        }
        if ($this->configured_current) {
            $parts[] = 'Config: ' . $this->configured_current . 'A';
        }
        return implode(' | ', $parts) ?: 'N/A';
    }

    public function getRotationDisplayAttribute()
    {
        return $this->rotation ? $this->rotation . ' RPM' : 'N/A';
    }

    /**
     * Tipos de equipamentos mais comuns
     */
    public static function getEquipmentTypes()
    {
        return [
            'Bomba' => 'Bomba',
            'Ventilador' => 'Ventilador',
            'Compressor' => 'Compressor',
            'Esteira' => 'Esteira Transportadora',
            'Moinho' => 'Moinho',
            'Britador' => 'Britador',
            'Misturador' => 'Misturador',
            'Exaustor' => 'Exaustor',
            'Agitador' => 'Agitador',
            'Outro' => 'Outro'
        ];
    }

    /**
     * Fabricantes mais comuns
     */
    public static function getManufacturers()
    {
        return [
            'WEG' => 'WEG',
            'Siemens' => 'Siemens',
            'ABB' => 'ABB',
            'Schneider' => 'Schneider Electric',
            'Eaton' => 'Eaton',
            'GE' => 'General Electric',
            'Toshiba' => 'Toshiba',
            'Outro' => 'Outro'
        ];
    }

    /**
     * Opções de estoque reserva
     */
    public static function getStockReserveOptions()
    {
        return [
            'Sim' => 'Sim',
            'Não' => 'Não',
            'Parcial' => 'Parcial'
        ];
    }
}
