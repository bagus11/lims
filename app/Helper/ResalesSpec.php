<?php

namespace App\Helper;

/**
 * Pembacaan spesifikasi Resales Goods dari master.
 *
 * Satu baris spesifikasi bisa berbentuk:
 *  - bertoleransi : value + tolerance          -> "238 ± 1.4"
 *  - rentang      : min + max                  -> "55 s/d 58"
 *  - lama         : value saja, tanpa batas    -> "201.0" (tidak bisa dinilai)
 */
class ResalesSpec
{
    public static function normalize($spec): array
    {
        $out = ['symbol' => '', 'value' => '', 'tolerance' => '', 'min' => '', 'max' => ''];

        if (!is_array($spec)) {
            $out['symbol'] = trim((string) $spec);
            return $out;
        }

        foreach (array_keys($out) as $field) {
            $out[$field] = trim((string) ($spec[$field] ?? ''));
        }

        return $out;
    }

    /** Teks standar yang dicetak di report. */
    public static function text($spec): string
    {
        $s = self::normalize($spec);

        if ($s['value'] !== '' && $s['tolerance'] !== '') {
            return $s['value'] . ' ± ' . $s['tolerance'];
        }
        if ($s['min'] !== '' && $s['max'] !== '') {
            return $s['min'] . ' s/d ' . $s['max'];
        }

        return $s['value'];
    }

    /**
     * Batas bawah & atas untuk menilai hasil ukur, atau null bila spesifikasi
     * tidak bisa dinilai otomatis.
     */
    public static function bounds($spec): ?array
    {
        $s = self::normalize($spec);

        if (is_numeric($s['min']) && is_numeric($s['max'])) {
            return [
                'min' => min((float) $s['min'], (float) $s['max']),
                'max' => max((float) $s['min'], (float) $s['max']),
            ];
        }

        if (is_numeric($s['value']) && is_numeric($s['tolerance'])) {
            return [
                'min' => (float) $s['value'] - (float) $s['tolerance'],
                'max' => (float) $s['value'] + (float) $s['tolerance'],
            ];
        }

        return null;
    }

    /** True bila hasil ukur berada di luar batas spesifikasi. */
    public static function isOutOfSpec($spec, $actual): bool
    {
        $bounds = self::bounds($spec);
        if ($bounds === null) {
            return false;
        }

        $raw = str_replace(',', '.', trim((string) $actual));
        if ($raw === '' || !is_numeric($raw)) {
            return false;
        }

        $value = (float) $raw;

        return $value < $bounds['min'] || $value > $bounds['max'];
    }
}
