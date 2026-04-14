<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $guarded = [];

    public function setTelefoneAttribute(?string $value): void
    {
        $this->attributes['telefone'] = self::onlyDigits($value);
    }

    public function setDocumentoAttribute(?string $value): void
    {
        $this->attributes['documento'] = self::onlyDigits($value);
    }

    public static function formatTelefone(?string $value): ?string
    {
        $digits = self::onlyDigits($value);

        if (blank($digits)) {
            return null;
        }

        if (strlen($digits) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $digits);
        }

        if (strlen($digits) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $digits);
        }

        return $value;
    }

    public static function formatDocumento(?string $value): ?string
    {
        $digits = self::onlyDigits($value);

        if (blank($digits)) {
            return null;
        }

        if (strlen($digits) === 14) {
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $digits);
        }

        if (strlen($digits) === 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $digits);
        }

        return $value;
    }

    private static function onlyDigits(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return preg_replace('/\D+/', '', $value);
    }
}
