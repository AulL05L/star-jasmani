<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SamaptaScore extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    protected $fillable = [
        'athlete_id', 'parameter_id', 'assessed_by', 'institution',
        'institution_id', 'session_label', 'assessment_date',
        'parameter_ke', 'tahun_sesi',
        'raw_lari_meter', 'raw_pushup_reps', 'raw_situp_reps',
        'raw_pullup_reps', 'raw_shuttle_seconds', 'raw_renang_seconds',
        'score_lari', 'score_pushup', 'score_situp', 'score_pullup',
        'score_shuttle', 'score_jasmani_b', 'score_renang',
        'score_ukg_avg', 'score_final', 'grade', 'notes',
        'snapshot_ukg_weight', 'snapshot_renang_weight',
        'nilai_jasmani_a', 'nilai_jasmani_b', 'nilai_total_jasmani',
    ];

    protected function casts(): array
    {
        return [
            'assessment_date'        => 'date',
            'raw_shuttle_seconds'    => 'float',
            'raw_renang_seconds'     => 'float',
            'score_lari'             => 'float',
            'score_pushup'           => 'float',
            'score_situp'            => 'float',
            'score_pullup'           => 'float',
            'score_shuttle'          => 'float',
            'score_jasmani_b'        => 'float',
            'score_renang'           => 'float',
            'score_ukg_avg'          => 'float',
            'score_final'            => 'float',
            'snapshot_ukg_weight'    => 'float',
            'snapshot_renang_weight' => 'float',
            'nilai_jasmani_a'        => 'float',
            'nilai_jasmani_b'        => 'float',
            'nilai_total_jasmani'    => 'float',
            'parameter_ke'           => 'integer',
            'tahun_sesi'             => 'integer',
        ];
    }

    // ══════════════════════════════════════════════
    // RELATIONSHIPS
    // ══════════════════════════════════════════════

    public function athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class, 'athlete_id');
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(BatchParameter::class, 'parameter_id');
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    // ══════════════════════════════════════════════
    // CORE CALCULATION
    // ══════════════════════════════════════════════

    public function calculateAndFill(string $gender, ?Institution $institution = null): void
    {
        $this->score_lari    = $this->calcScoreLari($this->raw_lari_meter, $gender);
        $this->score_pushup  = $this->calcScorePushup($this->raw_pushup_reps, $gender);
        $this->score_situp   = $this->calcScoreSitup($this->raw_situp_reps, $gender);
        $this->score_pullup  = $this->calcScorePullup($this->raw_pullup_reps, $gender);
        $this->score_shuttle = $this->calcScoreShuttle($this->raw_shuttle_seconds, $gender);
        $this->score_renang  = $this->raw_renang_seconds !== null
            ? $this->calcScoreRenang($this->raw_renang_seconds, $gender)
            : null;

        $ukgWeight    = $institution ? ($institution->ukg_weight / 100) : 0.80;
        $renangWeight = $institution ? ($institution->renang_weight / 100) : 0.20;

        $this->snapshot_ukg_weight    = $ukgWeight;
        $this->snapshot_renang_weight = $renangWeight;

        $komponenB = array_filter([
            $this->score_pushup,
            $this->score_situp,
            $this->score_pullup,
            $this->score_shuttle,
        ], fn($v) => $v !== null);

        $this->score_jasmani_b = count($komponenB) > 0
            ? round(array_sum($komponenB) / count($komponenB), 2)
            : null;

        if ($this->score_lari !== null && $this->score_jasmani_b !== null) {
            $this->score_ukg_avg = round(($this->score_lari + $this->score_jasmani_b) / 2, 2);
        } elseif ($this->score_lari !== null) {
            $this->score_ukg_avg = round($this->score_lari, 2);
        } elseif ($this->score_jasmani_b !== null) {
            $this->score_ukg_avg = round($this->score_jasmani_b, 2);
        }

        if ($this->score_ukg_avg !== null && $this->score_renang !== null) {
            $this->score_final = round(
                ($this->score_ukg_avg * $ukgWeight) + ($this->score_renang * $renangWeight),
                2
            );
        } elseif ($this->score_ukg_avg !== null) {
            $this->score_final = round($this->score_ukg_avg, 2);
        }

        $this->nilai_jasmani_a     = $this->score_lari;
        $this->nilai_jasmani_b     = $this->score_jasmani_b;
        $this->nilai_total_jasmani = $this->score_ukg_avg;
        $this->grade               = $this->determineGrade($this->score_final);
    }

    // ══════════════════════════════════════════════
    // GRADE & ACCESSORS
    // ══════════════════════════════════════════════

    private function determineGrade(?float $score): ?string
    {
        if ($score === null) return null;
        return match (true) {
            $score >= 80 => 'A',
            $score >= 70 => 'B',
            $score >= 60 => 'C',
            $score >= 50 => 'D',
            default      => 'E',
        };
    }

    public function getGradeLabelAttribute(): string
    {
        return match ($this->grade) {
            'A' => 'Sangat Baik', 'B' => 'Baik', 'C' => 'Cukup',
            'D' => 'Kurang', 'E' => 'Tidak Lulus',
            default => 'Belum Dinilai',
        };
    }

    public function getLabelParameterAttribute(): string
    {
        return "Parameter {$this->parameter_ke}";
    }

    public function getIsLulusAttribute(): bool
    {
        return ($this->score_final ?? 0) >= 70;
    }

    // ══════════════════════════════════════════════
    // TABEL KONVERSI SKOR
    // ══════════════════════════════════════════════

    private function calcScoreLari(?int $meter, string $gender): ?float
    {
        if ($meter === null) return null;
        $tablePutra = [
            [3444,100],[3422,99],[3401,98],[3380,97],[3369,96],[3338,95],[3317,94],
            [3253,91],[3232,90],[3211,89],[3190,88],[3169,87],[3148,86],[3126,85],
            [3105,84],[3084,83],[3062,82],[3041,81],[3021,80],[2999,79],[2978,78],
            [2957,77],[2936,76],[2914,75],[2893,74],[2872,73],[2851,72],[2830,71],
            [2809,70],[2788,69],[2767,68],[2746,67],[2725,66],[2703,65],[2682,64],
            [2661,63],[2639,62],[2618,61],[2597,60],[2576,59],[2555,58],[2534,57],
            [2513,56],[2491,55],[2470,54],[2449,53],[2428,52],[2407,51],[2385,50],
            [2364,49],[2343,48],[2322,47],[2301,46],[2280,45],[2259,44],[2237,43],
            [2216,42],[2195,41],[2174,40],[2153,39],[2132,38],[2111,37],[2090,36],
            [2069,35],[2048,34],[2026,33],[2005,32],[1984,31],[1962,30],[1941,29],
            [1920,28],[1899,27],[1878,26],[1857,25],[1836,24],[1814,23],[1793,22],
            [1772,21],[1750,20],[1729,19],[1708,18],[1687,17],[1666,16],[1645,15],
            [1624,14],[1603,13],[1582,12],[1561,11],[1539,10],[1518,9],[1497,8],
            [1476,7],[1455,6],[1434,5],[1413,4],[1392,3],[1371,2],[1349,1],[0,1],
        ];
        $tablePutri = [
            [3095,100],[3084,99],[3062,98],[3041,97],[3020,96],[2999,95],[2978,94],
            [2957,93],[2936,92],[2914,91],[2893,90],[2872,89],[2851,88],[2830,87],
            [2809,86],[2788,85],[2767,84],[2746,83],[2725,82],[2703,81],[2682,80],
            [2661,79],[2639,78],[2618,77],[2597,76],[2576,75],[2555,74],[2534,73],
            [2513,72],[2491,71],[2470,70],[2449,69],[2428,68],[2407,67],[2385,66],
            [2364,65],[2343,64],[2322,63],[2301,62],[2280,61],[2259,60],[2237,59],
            [2216,58],[2195,57],[2174,56],[2153,55],[2132,54],[2111,53],[2090,52],
            [2069,51],[2048,50],[2026,49],[2005,48],[1984,47],[1962,46],[1941,45],
            [1920,44],[1899,43],[1878,42],[1857,41],[1836,40],[1814,39],[1793,38],
            [1772,37],[1750,36],[1729,35],[1708,34],[1687,33],[1666,32],[1645,31],
            [1624,30],[1603,29],[1582,28],[1561,27],[1539,26],[1518,25],[1497,24],
            [1476,23],[1455,22],[1434,21],[1412,20],[1391,19],[1370,18],[1349,17],
            [1328,16],[1307,15],[1286,14],[1265,13],[1244,12],[1223,11],[1202,10],
            [1181,9],[1160,8],[1139,7],[1118,6],[1097,5],[1076,4],[1055,3],[1034,2],[1013,1],[0,1],
        ];
        return $this->lookupTable($meter, $gender === 'pria' ? $tablePutra : $tablePutri);
    }

    private function calcScorePushup(?int $reps, string $gender): ?float
    {
        if ($reps === null) return null;
        $tablePutra = [
            [42,100],[41,97],[40,94],[39,91],[38,88],[37,85],[36,82],[35,79],
            [34,76],[33,73],[32,70],[31,67],[30,64],[29,61],[28,58],[27,55],
            [26,52],[25,50],[24,48],[23,46],[22,44],[21,42],[20,40],[19,38],
            [18,36],[17,34],[16,32],[15,29],[14,26],[13,23],[12,21],[11,19],
            [10,17],[9,15],[8,13],[7,11],[6,9],[5,7],[4,6],[3,5],[2,4],[1,3],[0,3],
        ];
        $tablePutri = [
            [50,100],[49,96],[48,93],[47,91],[46,87],[45,84],[44,82],[43,78],
            [42,75],[41,73],[40,69],[39,66],[38,64],[37,60],[36,57],[35,55],
            [34,51],[33,48],[32,46],[31,42],[30,39],[29,37],[28,33],[27,30],
            [26,26],[25,24],[24,20],[23,19],[22,15],[21,12],[20,10],[19,9],
            [18,8],[17,7],[16,6],[15,6],[14,5],[13,5],[12,4],[11,4],[10,3],
            [9,3],[8,3],[7,2],[6,2],[5,2],[4,1],[3,1],[2,1],[1,1],[0,1],
        ];
        return $this->lookupTable($reps, $gender === 'pria' ? $tablePutra : $tablePutri);
    }

    private function calcScoreSitup(?int $reps, string $gender): ?float
    {
        if ($reps === null) return null;
        $tablePutra = [
            [40,100],[39,96],[38,92],[37,88],[36,84],[35,80],[34,76],[33,72],
            [32,68],[31,64],[30,60],[29,56],[28,52],[27,48],[26,44],[25,41],
            [24,38],[23,35],[22,32],[21,30],[20,28],[19,26],[18,24],[17,22],
            [16,20],[15,18],[14,16],[13,14],[12,12],[11,10],[10,8],[9,6],[8,4],[0,4],
        ];
        $tablePutri = [
            [50,100],[49,97],[48,93],[47,91],[46,87],[45,84],[44,82],[43,78],
            [42,75],[41,73],[40,69],[39,66],[38,64],[37,60],[36,57],[35,55],
            [34,51],[33,48],[32,46],[31,42],[30,39],[29,37],[28,33],[27,30],
            [26,26],[25,24],[24,20],[23,19],[22,15],[21,12],[20,10],[19,9],
            [18,8],[17,7],[16,6],[15,6],[14,5],[13,5],[12,4],[11,4],[10,3],
            [9,3],[8,3],[7,2],[6,2],[5,2],[4,1],[3,1],[2,1],[1,1],[0,1],
        ];
        return $this->lookupTable($reps, $gender === 'pria' ? $tablePutra : $tablePutri);
    }

    private function calcScorePullup(?int $reps, string $gender): ?float
    {
        if ($reps === null) return null;
        $tablePutra = [
            [17,100],[16,94],[15,88],[14,82],[13,76],[12,70],[11,64],[10,58],
            [9,52],[8,46],[7,39],[6,32],[5,26],[4,20],[3,14],[2,8],[1,4],[0,4],
        ];
        $tablePutri = [
            [72,100],[71,97],[70,95],[69,92],[68,90],[67,87],[66,85],[65,82],
            [64,80],[63,77],[62,75],[61,72],[60,70],[59,67],[58,65],[57,62],
            [56,60],[55,57],[54,55],[53,52],[52,50],[51,47],[50,45],[49,42],
            [48,40],[47,37],[46,35],[45,32],[44,30],[43,27],[42,25],[41,22],
            [40,20],[39,17],[38,15],[37,12],[36,10],[35,7],[34,5],[33,2],[0,2],
        ];
        return $this->lookupTable($reps, $gender === 'pria' ? $tablePutra : $tablePutri);
    }

    private function calcScoreShuttle(?float $seconds, string $gender): ?float
    {
        if ($seconds === null) return null;
        $tablePutra = [
            [16.2,100],[16.3,99],[16.4,98],[16.5,97],[16.6,96],[16.7,95],
            [16.8,94],[16.9,92],[17.0,90],[17.1,88],[17.2,86],[17.3,84],
            [17.4,82],[17.5,80],[17.6,78],[17.7,76],[17.8,74],[17.9,72],
            [18.0,70],[18.1,68],[18.2,66],[18.3,64],[18.4,62],[18.5,60],
            [18.6,58],[18.7,56],[18.8,54],[18.9,52],[19.0,51],[19.1,49],
            [19.2,47],[19.3,45],[19.4,43],[19.5,41],[19.6,40],[19.7,38],
            [19.8,36],[19.9,34],[20.0,32],[20.1,30],[20.2,28],[20.3,26],
            [20.4,24],[20.5,22],[20.6,21],[20.7,19],[20.8,17],[20.9,15],
            [21.0,13],[21.1,11],[21.2,10],[21.3,8],[21.4,6],[21.5,4],
            [21.6,2],[21.7,0],[999,0],
        ];
        $tablePutri = [
            [17.6,100],[17.7,99],[17.8,98],[17.9,97],[18.0,96],[18.1,95],
            [18.2,94],[18.3,93],[18.4,92],[18.5,91],[18.6,90],[18.7,89],
            [18.8,88],[18.9,87],[19.0,86],[19.1,85],[19.2,84],[19.3,83],
            [19.4,82],[19.5,81],[19.6,80],[19.7,79],[19.8,78],[19.9,77],
            [20.0,76],[20.1,75],[20.2,74],[20.3,73],[20.4,72],[20.5,71],
            [20.6,70],[20.7,69],[20.8,68],[20.9,67],[21.0,66],[21.1,65],
            [21.2,64],[21.3,63],[21.4,62],[21.5,61],[21.6,60],[21.7,59],
            [21.8,58],[21.9,57],[22.0,56],[22.1,55],[22.2,54],[22.3,53],
            [22.4,52],[22.5,51],[22.6,50],[22.7,49],[22.8,48],[22.9,47],
            [23.0,46],[24.4,32],[24.5,31],[24.6,30],[24.7,29],[24.8,28],
            [24.9,27],[25.0,26],[25.1,25],[25.2,24],[25.3,23],[25.4,22],
            [25.5,21],[25.6,20],[25.7,19],[25.8,18],[25.9,17],[26.0,16],
            [26.1,15],[26.2,14],[26.3,13],[26.4,12],[26.5,11],[26.6,10],
            [26.7,9],[26.8,8],[26.9,7],[27.0,6],[27.1,5],[27.2,4],[27.3,3],
            [27.4,2],[27.5,1],[999,0],
        ];
        $table = $gender === 'pria' ? $tablePutra : $tablePutri;
        foreach ($table as [$threshold, $poin]) {
            if ($seconds <= $threshold) return (float) $poin;
        }
        return 0.0;
    }

    private function calcScoreRenang(?float $seconds, string $gender): ?float
    {
        if ($seconds === null) return null;
        $tablePutra = [
            [14.0,100],[14.7,99],[15.4,98],[16.1,97],[16.8,96],[17.5,95],
            [18.2,94],[18.9,93],[19.6,92],[20.3,91],[21.0,90],[21.7,89],
            [22.4,88],[23.1,87],[23.8,86],[24.5,85],[25.2,84],[25.9,83],
            [26.6,82],[27.3,81],[28.0,80],[28.7,79],[29.4,78],[30.1,77],
            [30.8,76],[31.5,75],[32.2,74],[32.9,73],[33.6,72],[34.3,71],
            [35.0,70],[35.7,69],[36.4,68],[37.1,67],[37.8,66],[38.5,65],
            [39.2,64],[39.9,63],[40.6,62],[41.3,61],[42.0,60],[42.7,59],
            [43.4,58],[44.1,57],[44.8,56],[45.5,55],[46.2,54],[46.9,53],
            [47.6,52],[48.3,51],[49.0,50],[49.7,49],[50.4,48],[51.1,47],
            [51.8,46],[52.5,45],[53.2,44],[53.9,43],[54.6,42],[55.0,41],
            [999,41],
        ];
        $tablePutri = [
            [20.0,100],[20.7,99],[21.3,98],[22.0,97],[22.7,96],[23.4,95],
            [24.0,94],[24.7,93],[25.4,92],[26.0,91],[26.7,90],[27.4,89],
            [28.0,88],[28.7,87],[29.4,86],[30.1,85],[30.7,84],[31.4,83],
            [32.1,82],[32.7,81],[33.4,80],[34.1,79],[34.7,78],[35.4,77],
            [36.1,76],[36.8,75],[37.4,74],[38.1,73],[38.8,72],[39.4,71],
            [40.1,70],[40.8,69],[41.4,68],[42.1,67],[42.8,66],[43.5,65],
            [44.1,64],[44.8,63],[45.5,62],[46.1,61],[46.8,60],[47.5,59],
            [48.1,58],[48.8,57],[49.5,56],[50.2,54],[50.5,53],[51.2,52],
            [52.8,51],[53.5,50],[54.2,49],[54.8,48],[55.5,47],[56.2,46],
            [56.9,45],[57.5,44],[58.2,43],[58.9,42],[60.0,41],[999,41],
        ];
        $table = $gender === 'pria' ? $tablePutra : $tablePutri;
        foreach ($table as [$threshold, $poin]) {
            if ($seconds <= $threshold) return (float) $poin;
        }
        return 41.0;
    }

    private function lookupTable(float $value, array $table): float
    {
        foreach ($table as [$threshold, $poin]) {
            if ($value >= $threshold) return (float) $poin;
        }
        return 1.0;
    }

    // ══════════════════════════════════════════════
    // SCOPES
    // ══════════════════════════════════════════════

    public function scopeByParameter($query, int $parameterKe)
    {
        return $query->where('parameter_ke', $parameterKe);
    }

    public function scopeByTahun($query, int $tahun)
    {
        return $query->whereYear('assessment_date', $tahun);
    }
}