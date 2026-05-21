<?php
/*
 * Fájl: feladatok/ora.php
 * Funkció: Analóg és digitális óra feladatok generálása az idő leolvasásának gyakorlásához.
 * Utolsó módosítás: 2026. május 21. 11:00:00
 */

// --- BEMENETEK ---
$ora_tipus = isset($_POST['ora_tipus']) ? $_POST['ora_tipus'] : 'analog';
if (!in_array($ora_tipus, ['analog', 'digital', 'vegyes'])) $ora_tipus = 'analog';

// --- HTML BEÁLLÍTÁSOK GENERÁLÁSA ---
$egyedi_beallitasok_html = '
<div class="col-md-6 col-lg-3">
    <label for="ora_tipus" class="form-label fw-bold text-secondary">Óra típusa</label>
    <select class="form-select" id="ora_tipus" name="ora_tipus">
        <option value="analog"' . ($ora_tipus == 'analog' ? ' selected' : '') . '>Analóg óra (mutatók → számok)</option>
        <option value="digital"' . ($ora_tipus == 'digital' ? ' selected' : '') . '>Digitális óra (24h → 12h)</option>
        <option value="vegyes"' . ($ora_tipus == 'vegyes' ? ' selected' : '') . '>Vegyes</option>
    </select>
    <small class="d-block text-muted mt-1">Nehezített: 1 perces pontosság</small>
</div>
';

// --- FELADATOK GENERÁLÁSA ---
$feladatok_per_oldal = 12;
$probSzam = 0;

function generate_clock_svg($hour, $minute) {
    $cx = 110; $cy = 110; $r = 100;
    $hour_angle = ($hour % 12) * 30 + $minute * 0.5;
    $min_angle = $minute * 6;

    $html = '<svg class="clock-svg" viewBox="0 0 220 220" xmlns="http://www.w3.org/2000/svg">';

    $html .= '<circle cx="' . $cx . '" cy="' . $cy . '" r="' . $r . '" class="clock-face" />';
    $html .= '<circle cx="' . $cx . '" cy="' . $cy . '" r="' . ($r - 2) . '" class="clock-face-inner" />';

    for ($i = 1; $i <= 12; $i++) {
        $angle = deg2rad($i * 30 - 90);
        $x = round($cx + ($r - 18) * cos($angle), 1);
        $y = round($cy + ($r - 18) * sin($angle), 1);
        $html .= '<text x="' . $x . '" y="' . $y . '" text-anchor="middle" dominant-baseline="central" class="clock-number">' . $i . '</text>';

        $tx1 = round($cx + ($r - 5) * cos($angle), 1);
        $ty1 = round($cy + ($r - 5) * sin($angle), 1);
        $tx2 = round($cx + ($r - 14) * cos($angle), 1);
        $ty2 = round($cy + ($r - 14) * sin($angle), 1);
        $html .= '<line x1="' . $tx1 . '" y1="' . $ty1 . '" x2="' . $tx2 . '" y2="' . $ty2 . '" class="clock-tick" />';
    }

    for ($i = 0; $i < 60; $i++) {
        if ($i % 5 == 0) continue;
        $angle = deg2rad($i * 6 - 90);
        $tx1 = round($cx + ($r - 3) * cos($angle), 1);
        $ty1 = round($cy + ($r - 3) * sin($angle), 1);
        $tx2 = round($cx + ($r - 7) * cos($angle), 1);
        $ty2 = round($cy + ($r - 7) * sin($angle), 1);
        $html .= '<line x1="' . $tx1 . '" y1="' . $ty1 . '" x2="' . $tx2 . '" y2="' . $ty2 . '" class="clock-tick-min" />';
    }

    $h_rad = deg2rad($hour_angle - 90);
    $h_len = $r * 0.5;
    $hx = round($cx + $h_len * cos($h_rad), 1);
    $hy = round($cy + $h_len * sin($h_rad), 1);
    $html .= '<line x1="' . $cx . '" y1="' . $cy . '" x2="' . $hx . '" y2="' . $hy . '" class="clock-hand-hour" />';

    $m_rad = deg2rad($min_angle - 90);
    $m_len = $r * 0.68;
    $mx = round($cx + $m_len * cos($m_rad), 1);
    $my = round($cy + $m_len * sin($m_rad), 1);
    $html .= '<line x1="' . $cx . '" y1="' . $cy . '" x2="' . $mx . '" y2="' . $my . '" class="clock-hand-minute" />';

    $html .= '<circle cx="' . $cx . '" cy="' . $cy . '" r="5" class="clock-center" />';
    $html .= '<circle cx="' . $cx . '" cy="' . $cy . '" r="2" class="clock-center-dot" />';

    $html .= '</svg>';
    return $html;
}

for ($p = 0; $p < $oldalak_szama; $p++) {

    $html = '';

    if ($p === 0) {
        $html .= '<style>
        .clock-problem { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; justify-content: center; }
        .clock-svg { width: 85px; height: 85px; flex-shrink: 0; }
        .clock-inputs { display: flex; align-items: center; gap: 2px; font-size: 1.2rem; }
        .clock-inputs .answer-box { width: 38px; font-size: 1rem; }
        .del-badge { display: inline-flex; align-items: center; gap: 3px; font-size: 0.75rem; font-weight: bold; padding: 2px 6px; border-radius: 4px; white-space: nowrap; }
        .del-selector { display: inline-flex; align-items: center; gap: 2px; }
        .del-selector .del-label { font-size: 0.75rem; padding: 2px 6px; line-height: 1.2; }
        .del-selector .del-label i { font-size: 0.75rem; }
        .digital-display {
            font-family: "Courier New", "Consolas", monospace; font-size: 1.3rem; font-weight: bold;
            background: #1a1a2e; color: #00ff88; padding: 5px 12px; border-radius: 8px;
            letter-spacing: 3px; display: inline-block; border: 2px solid #333; line-height: 1;
            box-shadow: inset 0 0 8px rgba(0,255,136,0.15);
        }
        .clock-arrow { font-size: 1.1rem; color: #6c757d; }
        @media print {
            .digital-display { background: #1a1a2e !important; color: #00ff88 !important; border-color: #555 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .clock-svg { width: 75px; height: 75px; }
            .clock-problem { gap: 3px; }
            .clock-inputs .answer-box { width: 32px; font-size: 0.9rem; }
            .clock-inputs .answer-box::placeholder { color: transparent !important; opacity: 0 !important; }
            .clock-inputs .answer-box::-webkit-input-placeholder { color: transparent !important; opacity: 0 !important; }
            .del-badge i { display: none !important; }
            .del-badge { font-size: 0.65rem !important; padding: 1px 4px !important; border: 1px solid #999 !important; border-radius: 3px !important; }
            .del-selector .del-label { border: 1px solid #999 !important; background: none !important; padding: 1px 5px !important; font-weight: normal !important; font-size: 0.7rem !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .del-selector .del-label i { display: none !important; }
            .del-selector .del-label::before { content: "\\2610 "; font-size: 0.85rem; }
            .del-selector .del-radio:checked + .del-label::before { content: "\\2611 "; }
            body.show-solutions .del-selector .del-radio:checked + .del-label { border-color: #198754 !important; background-color: #e8f5e9 !important; font-weight: bold !important; }
        }
        </style>
        <script>
        document.addEventListener("DOMContentLoaded",function(){
            document.querySelectorAll(".del-radio").forEach(function(r){
                r.addEventListener("change",function(){
                    var s=this.closest(".del-selector");
                    s.querySelector(".del-input").value=this.value;
                });
            });
            var b=document.getElementById("reveal-button");
            if(b){
                b.addEventListener("click",function(){
                    setTimeout(function(){
                        var rev=document.body.classList.contains("show-solutions");
                        document.querySelectorAll(".del-selector").forEach(function(sel){
                            var cor=sel.querySelector(".del-input").dataset.correct;
                            sel.querySelectorAll(".del-radio").forEach(function(r){
                                r.checked=rev&&(r.value===cor);
                            });
                        });
                    },10);
                });
            }
        });
        </script>';
    }

    $html .= '<div class="row">';
    $oldal_feladatai = [];

    while (count($oldal_feladatai) < $feladatok_per_oldal) {
        if ($ora_tipus === 'vegyes') {
            $mode = (random_int(0, 1) === 0) ? 'analog' : 'digital';
        } else {
            $mode = $ora_tipus;
        }

        if ($szuper_konnyu) {
            $perc = 0;
        } elseif ($nehezebb) {
            $perc = random_int(0, 59);
        } else {
            $perc = random_int(0, 11) * 5;
        }

        $ora = random_int(0, 23);

        $oldal_feladatai[] = [
            'mode' => $mode,
            'ora' => $ora,
            'perc' => $perc
        ];
    }

    $half = ceil(count($oldal_feladatai) / 2);
    $columns = array_chunk($oldal_feladatai, $half);

    foreach ($columns as $colIndex => $columnTasks) {
        $borderClass = ($colIndex === 0) ? 'border-end pe-3' : 'ps-3';
        $html .= '<div class="col-6 ' . $borderClass . '">';

        foreach ($columnTasks as $f) {
            $del_elott = ($f['ora'] < 12);
            $del_icon = $del_elott ? 'fa-sun text-warning' : 'fa-moon text-primary-emphasis';
            $del_label = $del_elott ? 'DE' : 'DU';

            $html .= '<div class="problem-row d-flex align-items-center justify-content-center">';

            if ($f['mode'] === 'analog') {
                $ora_str = str_pad($f['ora'], 2, '0', STR_PAD_LEFT);
                $perc_str = str_pad($f['perc'], 2, '0', STR_PAD_LEFT);

                $html .= '<div class="clock-problem">';
                $html .= generate_clock_svg($f['ora'], $f['perc']);
                $html .= '<div class="clock-inputs">';
                $html .= '<span class="del-badge"><i class="fas ' . $del_icon . '"></i> ' . $del_label . '</span>';
                $html .= '<input type="number" class="answer-box" data-correct="' . $ora_str . '" min="0" max="23" placeholder="ó">';
                $html .= '<span class="fw-bold">:</span>';
                $html .= '<input type="number" class="answer-box" data-correct="' . $perc_str . '" min="0" max="59" placeholder="pp">';
                $html .= '</div></div>';
            } else {
                $ora_12 = $f['ora'] % 12;
                if ($ora_12 == 0) $ora_12 = 12;
                $perc_str = str_pad($f['perc'], 2, '0', STR_PAD_LEFT);
                $ora_24_str = str_pad($f['ora'], 2, '0', STR_PAD_LEFT);
                $del_value = $del_elott ? 'délelőtt' : 'délután';
                $uid = 'del_' . $probSzam;

                $html .= '<div class="clock-problem">';
                $html .= '<div class="digital-display">' . $ora_24_str . ':' . $perc_str . '</div>';
                $html .= '<span class="clock-arrow mx-1">→</span>';
                $html .= '<div class="clock-inputs">';
                $html .= '<input type="number" class="answer-box" data-correct="' . $ora_12 . '" min="1" max="12" placeholder="ó">';
                $html .= '<span class="fw-bold">:</span>';
                $html .= '<input type="number" class="answer-box" data-correct="' . $perc_str . '" min="0" max="59" placeholder="pp">';
                $html .= '<div class="del-selector">';
                $html .= '<input type="hidden" class="del-input" data-correct="' . $del_value . '">';
                $html .= '<input type="radio" class="btn-check del-radio" name="' . $uid . '" id="' . $uid . '_de" value="délelőtt" autocomplete="off">';
                $html .= '<label class="btn btn-outline-warning btn-sm del-label" for="' . $uid . '_de"><i class="fas fa-sun"></i> DE</label>';
                $html .= '<input type="radio" class="btn-check del-radio" name="' . $uid . '" id="' . $uid . '_du" value="délután" autocomplete="off">';
                $html .= '<label class="btn btn-outline-primary btn-sm del-label" for="' . $uid . '_du"><i class="fas fa-moon"></i> DU</label>';
                $html .= '</div></div></div>';
            }

            $html .= '</div>';
            $probSzam++;
        }

        $html .= '</div>';
    }

    $html .= '</div>';
    $feladat_oldalak[] = $html;
}
