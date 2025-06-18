<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Alternativa ao Str::limit quando mbstring não está disponível
     */
    public static function limit($value, $limit = 100, $end = '...')
    {
        if (function_exists('mb_strimwidth')) {
            return mb_strimwidth($value, 0, $limit, $end);
        }
        
        // Fallback quando mbstring não estiver disponível
        if (strlen($value) <= $limit) {
            return $value;
        }
        
        return substr($value, 0, $limit - strlen($end)) . $end;
    }
    
    /**
     * Verifica se uma string contém determinado texto (case insensitive)
     */
    public static function contains($haystack, $needle)
    {
        return stripos($haystack, $needle) !== false;
    }
    
    /**
     * Converte para slug
     */
    public static function slug($text)
    {
        $text = trim($text);
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        return $text;
    }
} 