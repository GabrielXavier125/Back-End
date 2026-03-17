<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'email', 'telefone', 'cpf', 'endereco',];

    protected function telefone(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => self::formatTelefone($value),
            set: fn ($value) => self::onlyDigits($value),
        );
    }

    protected function cpf(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => self::formatCpf($value),
            set: fn ($value) => self::onlyDigits($value),
        );
    }

    private static function onlyDigits($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', (string) $value);

        return $digits !== '' ? $digits : null;
    }

    private static function formatTelefone($value): ?string
    {
        $digits = self::onlyDigits($value);

        if ($digits === null) {
            return null;
        }

        if (strlen($digits) === 11) {
            return sprintf('(%s) %s-%s', substr($digits, 0, 2), substr($digits, 2, 5), substr($digits, 7, 4));
        }

        if (strlen($digits) === 10) {
            return sprintf('(%s) %s-%s', substr($digits, 0, 2), substr($digits, 2, 4), substr($digits, 6, 4));
        }

        return $value;
    }

    private static function formatCpf($value): ?string
    {
        $digits = self::onlyDigits($value);

        if ($digits === null) {
            return null;
        }

        if (strlen($digits) === 11) {
            return sprintf('%s.%s.%s-%s', substr($digits, 0, 3), substr($digits, 3, 3), substr($digits, 6, 3), substr($digits, 9, 2));
        }

        return $value;
    }
}
