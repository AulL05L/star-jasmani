<?php

namespace App\Services;

class KebugaranScoring
{
    /**
     * Label & satuan per parameter
     */
    public static array $parameters = [
        'bmi'             => ['label' => 'BMI',              'unit' => '',         'icon' => 'fa-weight-scale'],
        'komposisi_otot'  => ['label' => 'Komposisi Otot',   'unit' => '%',        'icon' => 'fa-dumbbell'],
        'komposisi_lemak' => ['label' => 'Komposisi Lemak',  'unit' => '%',        'icon' => 'fa-droplet'],
        'push_up'         => ['label' => 'Push Up',          'unit' => 'rep',      'icon' => 'fa-hand-fist'],
        'sit_up'          => ['label' => 'Sit Up',           'unit' => 'rep',      'icon' => 'fa-person'],
        'squat'           => ['label' => 'Squat',            'unit' => 'rep',      'icon' => 'fa-person-walking'],
        'sit_and_reach'   => ['label' => 'Sit & Reach',      'unit' => 'cm',       'icon' => 'fa-arrows-up-down'],
        'balke'           => ['label' => 'Balke (VO₂max)',   'unit' => 'ml/kg/m',  'icon' => 'fa-lungs'],
    ];

    /**
     * Standar umum (WHO / ACSM), dibedakan gender.
     * Format per kategori: [min, max] — null = tidak terbatas
     * Urutan prioritas: sangat_baik → baik → cukup → kurang (fallback)
     */
    private static array $standards = [
        'bmi' => [
            // BMI sama untuk semua gender (WHO)
            'pria'   => ['sangat_baik' => [18.5, 24.9], 'baik' => [17.0, 27.4], 'cukup' => [15.0, 29.9]],
            'wanita' => ['sangat_baik' => [18.5, 24.9], 'baik' => [17.0, 27.4], 'cukup' => [15.0, 29.9]],
        ],
        'komposisi_otot' => [
            'pria'   => ['sangat_baik' => [45.0, null], 'baik' => [40.0, 44.9], 'cukup' => [35.0, 39.9]],
            'wanita' => ['sangat_baik' => [40.0, null], 'baik' => [35.0, 39.9], 'cukup' => [30.0, 34.9]],
        ],
        'komposisi_lemak' => [
            'pria'   => ['sangat_baik' => [6.0, 17.9],  'baik' => [18.0, 24.9], 'cukup' => [25.0, 29.9]],
            'wanita' => ['sangat_baik' => [14.0, 24.9], 'baik' => [25.0, 31.9], 'cukup' => [32.0, 37.9]],
        ],
        'push_up' => [
            'pria'   => ['sangat_baik' => [40.0, null], 'baik' => [25.0, 39.9], 'cukup' => [15.0, 24.9]],
            'wanita' => ['sangat_baik' => [25.0, null], 'baik' => [15.0, 24.9], 'cukup' => [8.0, 14.9]],
        ],
        'sit_up' => [
            'pria'   => ['sangat_baik' => [45.0, null], 'baik' => [30.0, 44.9], 'cukup' => [20.0, 29.9]],
            'wanita' => ['sangat_baik' => [40.0, null], 'baik' => [25.0, 39.9], 'cukup' => [15.0, 24.9]],
        ],
        'squat' => [
            'pria'   => ['sangat_baik' => [50.0, null], 'baik' => [35.0, 49.9], 'cukup' => [20.0, 34.9]],
            'wanita' => ['sangat_baik' => [40.0, null], 'baik' => [25.0, 39.9], 'cukup' => [15.0, 24.9]],
        ],
        'sit_and_reach' => [
            'pria'   => ['sangat_baik' => [40.0, null], 'baik' => [30.0, 39.9], 'cukup' => [20.0, 29.9]],
            'wanita' => ['sangat_baik' => [43.0, null], 'baik' => [33.0, 42.9], 'cukup' => [23.0, 32.9]],
        ],
        // Balke VO₂max (ml/kg/min) — standar umum usia 20-29
        'balke' => [
            'pria'   => ['sangat_baik' => [51.6, null], 'baik' => [43.9, 51.5], 'cukup' => [36.2, 43.8]],
            'wanita' => ['sangat_baik' => [44.2, null], 'baik' => [36.3, 44.1], 'cukup' => [28.6, 36.2]],
        ],
    ];

    /** Rentang label yang ditampilkan di UI */
    public static array $rangeLabels = [
        'bmi'             => ['pria' => '18.5 – 24.9',      'wanita' => '18.5 – 24.9'],
        'komposisi_otot'  => ['pria' => '≥ 40%',            'wanita' => '≥ 35%'],
        'komposisi_lemak' => ['pria' => '6 – 18%',          'wanita' => '14 – 25%'],
        'push_up'         => ['pria' => '≥ 25 rep',         'wanita' => '≥ 15 rep'],
        'sit_up'          => ['pria' => '≥ 30 rep',         'wanita' => '≥ 25 rep'],
        'squat'           => ['pria' => '≥ 35 rep',         'wanita' => '≥ 25 rep'],
        'sit_and_reach'   => ['pria' => '≥ 30 cm',          'wanita' => '≥ 33 cm'],
        'balke'           => ['pria' => '≥ 43.9 ml/kg/m',   'wanita' => '≥ 36.3 ml/kg/m'],
    ];

    public static function category(string $parameter, float $value, string $gender): string
    {
        $s = self::$standards[$parameter][$gender] ?? self::$standards[$parameter]['pria'];

        foreach (['sangat_baik', 'baik', 'cukup'] as $cat) {
            [$min, $max] = $s[$cat];
            $inRange = $value >= $min && ($max === null || $value <= $max);
            if ($inRange) return $cat;
        }

        return 'kurang';
    }

    /** Persentase menuju target "baik" (0–100+) untuk progress bar */
    public static function percentage(string $parameter, float $value, string $gender): float
    {
        $s    = self::$standards[$parameter][$gender] ?? self::$standards[$parameter]['pria'];
        $target = $s['baik'][0]; // nilai minimum kategori "baik" sebagai 100%

        if ($target <= 0) return 100.0;

        // Untuk lemak: semakin rendah semakin baik — invert
        if ($parameter === 'komposisi_lemak') {
            $ideal = ($s['sangat_baik'][0] + $s['sangat_baik'][1]) / 2;
            $worst = $gender === 'pria' ? 35.0 : 42.0;
            $pct   = max(0, ($worst - $value) / ($worst - $ideal) * 100);
            return round(min($pct, 100), 1);
        }

        // Balke: linear dari worst (20) ke sangat baik
        if ($parameter === 'balke') {
            $best  = $gender === 'pria' ? 51.6 : 44.2;
            $worst = $gender === 'pria' ? 20.0 : 18.0;
            return round(min(max(($value - $worst) / ($best - $worst) * 100, 0), 100), 1);
        }

        // Untuk BMI: target tengah range ideal
        if ($parameter === 'bmi') {
            $ideal = 21.7;
            $worst = 35.0;
            $pct   = max(0, ($worst - abs($value - $ideal)) / ($worst - 0) * 100);
            return round(min($pct * 1.4, 100), 1);
        }

        return round(min($value / $target * 100, 100), 1);
    }

    public static function categoryLabel(string $cat): string
    {
        return match ($cat) {
            'sangat_baik' => 'Sangat Baik',
            'baik'        => 'Baik',
            'cukup'       => 'Cukup',
            default       => 'Kurang',
        };
    }

    public static function categoryColor(string $cat): string
    {
        return match ($cat) {
            'sangat_baik' => 'emerald',
            'baik'        => 'green',
            'cukup'       => 'amber',
            default       => 'red',
        };
    }

    /** Hitung skor total kebugaran 0–100 dari semua parameter */
    public static function totalScore(array $scores, string $gender): array
    {
        if (empty($scores)) return ['score' => 0, 'category' => 'kurang'];

        $total = 0;
        $count = 0;
        foreach ($scores as $param => $value) {
            $total += self::percentage($param, (float) $value, $gender);
            $count++;
        }

        $avg = $count > 0 ? round($total / $count) : 0;

        $cat = match (true) {
            $avg >= 85 => 'sangat_baik',
            $avg >= 65 => 'baik',
            $avg >= 45 => 'cukup',
            default    => 'kurang',
        };

        return ['score' => $avg, 'category' => $cat];
    }
}
