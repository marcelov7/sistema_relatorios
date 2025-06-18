<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UpdateUsersWithUsernameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereNull('username')->get();

        foreach ($users as $user) {
            // Gera username baseado no nome
            $username = $this->generateUsername($user->name);
            
            // Verifica se já existe e adiciona número se necessário
            $originalUsername = $username;
            $counter = 1;
            
            while (User::where('username', $username)->exists()) {
                $username = $originalUsername . $counter;
                $counter++;
            }
            
            $user->update(['username' => $username]);
            
            $this->command->info("Username '{$username}' adicionado para {$user->name}");
        }
        
        $this->command->info('Usernames atualizados com sucesso!');
    }
    
    /**
     * Gera username baseado no nome
     */
    private function generateUsername(string $name): string
    {
        // Remove acentos e caracteres especiais
        $username = Str::slug($name, '.');
        
        // Remove pontos extras e converte para minúsculas
        $username = strtolower(str_replace(['-', ' '], '.', $username));
        
        // Remove pontos consecutivos
        $username = preg_replace('/\.+/', '.', $username);
        
        // Remove pontos do início e fim
        $username = trim($username, '.');
        
        // Limita a 20 caracteres
        if (strlen($username) > 20) {
            $parts = explode('.', $username);
            if (count($parts) >= 2) {
                // Pega primeiro nome e primeira letra do sobrenome
                $username = $parts[0] . '.' . substr($parts[1], 0, 1);
            } else {
                $username = substr($username, 0, 20);
            }
        }
        
        return $username;
    }
}
