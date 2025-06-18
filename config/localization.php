<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações de Localização para Brasil
    |--------------------------------------------------------------------------
    |
    | Configurações específicas para localização brasileira incluindo
    | timezone, formato de datas, moeda e outras configurações regionais.
    |
    */

    'timezone' => 'America/Sao_Paulo',
    'locale' => 'pt_BR',
    'currency' => 'BRL',
    'currency_symbol' => 'R$',
    
    /*
    |--------------------------------------------------------------------------
    | Formatos de Data e Hora
    |--------------------------------------------------------------------------
    */
    
    'date_format' => 'd/m/Y',
    'datetime_format' => 'd/m/Y H:i:s',
    'time_format' => 'H:i:s',
    
    /*
    |--------------------------------------------------------------------------
    | Configurações Regionais
    |--------------------------------------------------------------------------
    */
    
    'decimal_separator' => ',',
    'thousands_separator' => '.',
    'phone_mask' => '(99) 99999-9999',
    'cpf_mask' => '999.999.999-99',
    'cnpj_mask' => '99.999.999/9999-99',
    
    /*
    |--------------------------------------------------------------------------
    | Fuso Horário por Região
    |--------------------------------------------------------------------------
    */
    
    'timezones' => [
        'acre' => 'America/Rio_Branco',           // UTC-5
        'amazonas_oeste' => 'America/Rio_Branco', // UTC-5
        'amazonas_leste' => 'America/Manaus',     // UTC-4
        'brasilia' => 'America/Sao_Paulo',        // UTC-3
        'fernando_noronha' => 'America/Noronha',  // UTC-2
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Estados e Regiões
    |--------------------------------------------------------------------------
    */
    
    'states' => [
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
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Configurações de Idioma
    |--------------------------------------------------------------------------
    */
    
    'language_name' => 'Português (Brasil)',
    'language_code' => 'pt-BR',
    'country_code' => 'BR',
    'flag_emoji' => '🇧🇷',
]; 