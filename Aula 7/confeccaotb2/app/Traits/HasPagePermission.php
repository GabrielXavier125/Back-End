<?php

namespace App\Traits;

trait HasPagePermission
{
    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Administradores têm acesso total (via Gate::before no AppServiceProvider)
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        $pageKey = static::$pageKey ?? null;

        if (!$pageKey) {
            return true;
        }

        // Busca todas as permissões do usuário (via cargos ou diretas)
        $permissions = method_exists($user, 'getAllPermissions')
            ? $user->getAllPermissions()
            : collect();

        // Se o usuário não tem nenhuma permissão configurada, libera acesso
        if ($permissions->isEmpty()) {
            return true;
        }

        // Verifica se alguma permissão inclui esta página
        return $permissions
            ->filter(fn ($p) => in_array($pageKey, (array) ($p->pages ?? [])))
            ->isNotEmpty();
    }
}
