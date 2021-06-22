<?php

namespace App\Library;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
class Format
{

    public static function paginate($items, $perPage = 50, $page = null, $options = [])
    {
        $page = $page ? $page : 1;
        $perPage = $perPage ? $perPage : 50;
       
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }

    public static function removeAcentos($str)
    {
        $from = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
        $to = "aaaaeeiooouucAAAAEEIOOOUUC";

        $keys = array();
        $values = array();
        preg_match_all('/./u', $from, $keys);
        preg_match_all('/./u', $to, $values);
        $mapping = array_combine($keys[0], $values[0]);
        return strtr($str, $mapping);
    }

    public static function phoneNumber($number_raw)
    {
        $number = '';
        if (substr($number_raw, 0, 2) == "55") {
            // Brasil
            if (strlen($number_raw) == 13) {
                // Ninth digit
                $number = substr($number_raw, 2, 2);
                $number .= " ";
                $number .= substr($number_raw, 4, 5);
                $number .= " ";
                $number .= substr($number_raw, 9);
            } elseif (strlen($number_raw) == 12) {
                // No ninth digit
                $number = substr($number_raw, 2, 2);
                $number .= " ";
                $number .= substr($number_raw, 4, 4);
                $number .= " ";
                $number .= substr($number_raw, 8);
            } else {
                $number = substr($number_raw, 2, 2);
                $number .= " ";
                $number .= substr($number_raw, 4);
            }
        } else {
            // International
            $number = "+" + $number_raw;
        }

        return $number;
    }

    public static function formatPhone($phone)
    {
        $formatedPhone = preg_replace('/[^0-9]/', '', $phone);
        $matches = [];
        preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $formatedPhone, $matches);
        if ($matches) {
            return '(' . $matches[1] . ') ' . $matches[2] . '-' . $matches[3];
        }

        return $phone; // return number without format
    }

    public static function normalizeDate($date, $time, Carbon $fallback = null)
    {
        switch ($time) {
            case 'start':
                $time = '00:00:00';
                break;

            case 'end':
                $time = '23:59:59';
                break;

            default:
                break;
        }

        $cbDate = null;
        if (!empty($date)) {
            try {
                $cbDate = Carbon::createFromFormat('d/m/Y H:i:s', $date . ' ' . $time, 'America/Sao_Paulo')->timezone('UTC');
            } catch (Exception $e) {
                if ($fallback) {
                    $cbDate = static::normalizeDate($fallback->format('d/m/Y'), $time, null);
                    var_dump($cbDate);
                    exit;
                }
            }
        }
        return $cbDate;
    }

    public static function cpfExpression($value)
    {
        $value = preg_replace('[\D]', '', $value);
        $substr1 = substr($value, 0, 3);
        $substr2 = substr($value, 3, 3);
        $substr3 = substr($value, 6, 3);
        $substr4 = substr($value, -2);
        $expression = ($substr1 . '.' . $substr2 . '.' . $substr3 . '-' . $substr4);
        return $expression;
    }

    public static function money($value, $symbol = 'R$', $decimal = ',', $thousands = '.', $round = 2)
    {
        return $symbol . number_format($value, $round, $decimal, $thousands);
    }

    public static function moneyWithoutSymbol($value, $decimal = ',', $thousands = '.', $round = 2)
    {
        return number_format($value, $round, $decimal, $thousands);
    }

    public static function isAssoc($arr)
    {
        if (!$arr) return false;
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function extractNumbers($value)
    {
        return preg_replace('[\D]', '', $value);
    }

    public static function remove_emoji($text)
    {
        return preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
    }

    public static function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            } else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
}
