<?php

namespace App\Helpers;

use Carbon\Carbon;

class BrazilHelper
{
    /**
     * Configurar timezone brasileiro
     */
    public static function setTimezone(): void
    {
        date_default_timezone_set('America/Sao_Paulo');
        config(['app.timezone' => 'America/Sao_Paulo']);
    }

    /**
     * Obter data formatada no padrão brasileiro
     */
    public static function formatDate($date, $format = 'd/m/Y'): string
    {
        if (!$date) return '';
        
        try {
            return Carbon::parse($date)->setTimezone('America/Sao_Paulo')->format($format);
        } catch (\Exception $e) {
            return $date;
        }
    }

    /**
     * Obter data e hora formatada no padrão brasileiro
     */
    public static function formatDateTime($datetime, $format = 'd/m/Y H:i:s'): string
    {
        if (!$datetime) return '';
        
        try {
            return Carbon::parse($datetime)->setTimezone('America/Sao_Paulo')->format($format);
        } catch (\Exception $e) {
            return $datetime;
        }
    }

    /**
     * Obter hora atual no Brasil
     */
    public static function now(): Carbon
    {
        return Carbon::now('America/Sao_Paulo');
    }

    /**
     * Obter hoje no Brasil
     */
    public static function today(): Carbon
    {
        return Carbon::today('America/Sao_Paulo');
    }

    /**
     * Formatar moeda brasileira
     */
    public static function formatCurrency($value): string
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    /**
     * Formatar número no padrão brasileiro
     */
    public static function formatNumber($number, $decimals = 2): string
    {
        return number_format($number, $decimals, ',', '.');
    }

    /**
     * Obter informações de localização brasileira
     */
    public static function getLocaleInfo(): array
    {
        return [
            'country' => 'Brasil',
            'country_code' => 'BR',
            'language' => 'Português (Brasil)',
            'language_code' => 'pt-BR',
            'timezone' => 'America/Sao_Paulo',
            'timezone_name' => 'Horário de Brasília',
            'currency' => 'Real',
            'currency_code' => 'BRL',
            'currency_symbol' => 'R$',
            'flag' => '🇧🇷',
            'date_format' => 'd/m/Y',
            'datetime_format' => 'd/m/Y H:i:s',
            'time_format' => 'H:i:s',
            'decimal_separator' => ',',
            'thousands_separator' => '.',
        ];
    }

    /**
     * Obter estados brasileiros
     */
    public static function getStates(): array
    {
        return [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espírito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins',
        ];
    }

    /**
     * Obter fusos horários brasileiros
     */
    public static function getTimezones(): array
    {
        return [
            'America/Rio_Branco' => 'Acre (UTC-5)',
            'America/Manaus' => 'Amazonas (UTC-4)',
            'America/Sao_Paulo' => 'Brasília (UTC-3)',
            'America/Noronha' => 'Fernando de Noronha (UTC-2)',
        ];
    }

    /**
     * Validar CPF
     */
    public static function validateCPF($cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Formatar CPF
     */
    public static function formatCPF($cpf): string
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }

    /**
     * Formatar CNPJ
     */
    public static function formatCNPJ($cnpj): string
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
    }

    /**
     * Formatar telefone brasileiro
     */
    public static function formatPhone($phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($phone) == 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $phone);
        } elseif (strlen($phone) == 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $phone);
        }
        
        return $phone;
    }
} 