<?php

namespace App\Helpers;

class NumeroALetras
{
    protected static $UNIDADES = [
        '', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis',
        'siete', 'ocho', 'nueve', 'diez', 'once', 'doce',
        'trece', 'catorce', 'quince', 'dieciséis',
        'diecisiete', 'dieciocho', 'diecinueve', 'veinte'
    ];

    protected static $DECENAS = [
        'veinti', 'treinta', 'cuarenta', 'cincuenta',
        'sesenta', 'setenta', 'ochenta', 'noventa'
    ];

    protected static $CENTENAS = [
        'ciento', 'doscientos', 'trescientos', 'cuatrocientos',
        'quinientos', 'seiscientos', 'setecientos',
        'ochocientos', 'novecientos'
    ];

    public static function convertir($numero)
    {
        $numero = number_format($numero, 2, '.', '');
        [$entero, $decimal] = explode('.', $numero);

        $texto = trim(self::convertirNumero((int)$entero));

        if ($entero == 1) {
            $moneda = 'Peso';
        } else {
            $moneda = 'Pesos';
        }

        return ucfirst($texto) . " {$moneda} {$decimal}/100 M.N.";
    }

    protected static function convertirNumero($num)
    {
        if ($num == 0) {
            return 'cero';
        }

        if ($num <= 20) {
            return self::$UNIDADES[$num];
        }

        if ($num < 30) {
            return self::$DECENAS[0] . self::$UNIDADES[$num - 20];
        }

        if ($num < 100) {
            $decena = intval($num / 10);
            $unidad = $num % 10;

            if ($unidad == 0) {
                return self::$DECENAS[$decena - 2];
            }

            return self::$DECENAS[$decena - 2] . ' y ' . self::$UNIDADES[$unidad];
        }

        if ($num == 100) {
            return 'cien';
        }

        if ($num < 1000) {
            $centena = intval($num / 100);
            $resto = $num % 100;

            return self::$CENTENAS[$centena - 1] . ' ' . self::convertirNumero($resto);
        }

        if ($num < 1000000) {
            $miles = intval($num / 1000);
            $resto = $num % 1000;

            if ($miles == 1) {
                return 'mil ' . self::convertirNumero($resto);
            }

            return self::convertirNumero($miles) . ' mil ' . self::convertirNumero($resto);
        }

        if ($num < 1000000000) {
            $millones = intval($num / 1000000);
            $resto = $num % 1000000;

            if ($millones == 1) {
                return 'un millón ' . self::convertirNumero($resto);
            }

            return self::convertirNumero($millones) . ' millones ' . self::convertirNumero($resto);
        }

        return '';
    }
}
