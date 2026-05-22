<?php
/*
 * Fájl: feladatok/ora.php
 * Funkció: Analóg és digitális óra feladatok generálása az idő leolvasásának gyakorlásához.
 * Módosítás: Szigorú CSS Grid elrendezés nyomtatáshoz az elcsúszás ellen, fix 10 feladat/oldal.
 * Utolsó módosítás: 2026. május 22. 09:05:00
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
// Kifejezetten 10 feladatot kérünk oldalanként, hogy tökéletesen kiférjen (5 sor x 2 oszlop)
$feladatok_per_oldal = 10;
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
        
        // Számok beljebb húzása (r-24), hogy nagyobb betűmérettel is elférjenek
        $x = round($cx + ($r - 24) * cos($angle), 1);
        $y = round($cy + ($r - 24) * sin($angle), 1);
        $html .= '<text x="' . $x . '" y="' . $y . '" text-anchor="middle" dominant-baseline="central" class="clock-number">' . $i . '</text>';

        // Rovátkák (Órák)
        $tx1 = round($cx + ($r - 2) * cos($angle), 1);
        $ty1 = round($cy + ($r - 2) * sin($angle), 1);
        $tx2 = round($cx + ($r - 12) * cos($angle), 1);
        $ty2 = round($cy + ($r - 12) * sin($angle), 1);
        $html .= '<line x1="' . $tx1 . '" y1="' . $ty1 . '" x2="' . $tx2 . '" y2="' . $ty2 . '" class="clock-tick" />';
    }

    for ($i = 0; $i < 60; $i++) {
        if ($i % 5 == 0) continue;
        $angle = deg2rad($i * 6 - 90);
        $tx1 = round($cx + ($r - 2) * cos($angle), 1);
        $ty1 = round($cy + ($r - 2) * sin($angle), 1);
        $tx2 = round($cx + ($r - 6) * cos($angle), 1);
        $ty2 = round($cy + ($r - 6) * sin($angle), 1);
        $html .= '<line x1="' . $tx1 . '" y1="' . $ty1 . '" x2="' . $tx2 . '" y2="' . $ty2 . '" class="clock-tick-min" />';
    }

    // Kismutató
    $h_rad = deg2rad($hour_angle - 90);
    $h_len = $r * 0.55; 
    $hx = round($cx + $h_len * cos($h_rad), 1);
    $hy = round($cy + $h_len * sin($h_rad), 1);
    $html .= '<line x1="' . $cx . '" y1="' . $cy . '" x2="' . $hx . '" y2="' . $hy . '" class="clock-hand-hour" />';

    // Nagymutató (Kicsit hosszabb, mint a kismutató)
    $m_rad = deg2rad($min_angle - 90);
    $m_len = $r * 0.82; 
    $mx = round($cx + $m_len * cos($m_rad), 1);
    $my = round($cy + $m_len * sin($m_rad), 1);
    $html .= '<line x1="' . $cx . '" y1="' . $cy . '" x2="' . $mx . '" y2="' . $my . '" class="clock-hand-minute" />';

    // Középpont
    $html .= '<circle cx="' . $cx . '" cy="' . $cy . '" r="6" class="clock-center" />';
    $html .= '<circle cx="' . $cx . '" cy="' . $cy . '" r="2" class="clock-center-dot" />';

    $html .= '</svg>';
    return $html;
}

for ($p = 0; $p < $oldalak_szama; $p++) {

    $html = '';

    // CSS blokk - Csak az órára vonatkozó lokális felülbírálások, méretnövelések
    if ($p === 0) {
        $html .= '<style>
        .clock-problem { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; justify-content: center; padding: 10px 0; }
        .clock-svg { width: 140px; height: 140px; flex-shrink: 0; }
        .clock-number { font-size: 24px !important; }
        
        .clock-inputs { display: flex; align-items: center; gap: 4px; font-size: 1.4rem; }
        .clock-inputs .answer-box { width: 45px; font-size: 1.2rem; }
        
        .del-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 0.85rem; font-weight: bold; padding: 4px 8px; border-radius: 6px; white-space: nowrap; margin-right: 5px; }
        .del-selector { display: inline-flex; align-items: center; gap: 4px; margin-left: 5px; }
        .del-selector .del-label { font-size: 0.85rem; padding: 4px 8px; line-height: 1.2; font-weight: bold; }
        .del-selector .del-label i { font-size: 0.85rem; }
        
        .digital-display {
            font-family: "Courier New", "Consolas", monospace; font-size: 1.4rem; font-weight: bold;
            background: #1a1a2e; color: #00ff88; padding: 6px 14px; border-radius: 8px;
            letter-spacing: 3px; display: inline-block; border: 2px solid #333; line-height: 1;
            box-shadow: inset 0 0 8px rgba(0,255,136,0.15);
        }
        .clock-arrow { font-size: 1.3rem; color: #6c757d; font-weight: bold; }
        
        @media print {
            .clock-problem { padding: 5px 0; gap: 8px; }
            
            /* --- SZIGORÚ GRID ELRENDEZÉS AZ ELCSÚSZÁS ELLEN --- */
            .row.clock-grid {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                row-gap: 20px !important;
                column-gap: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }
            .clock-grid > .col-12 {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 15px !important;
                margin: 0 !important;
                flex: none !important;
                border: none !important; /* Felülírjuk a Bootstrap kereteket */
                border-right: 1px solid #dee2e6 !important; /* Fix elválasztó vonal */
            }
            /* A jobb oldali (páros) oszlopról levesszük a vonalat */
            .clock-grid > .col-12:nth-child(even) {
                border-right: none !important;
            }

            .problem-row { padding: 10px 0 !important; margin-bottom: 0 !important; min-height: auto !important; height: 100%; }
            .digital-display { background: #1a1a2e !important; color: #00ff88 !important; border-color: #555 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            
            .clock-svg { width: 110px; height: 110px; }
            .clock-number { font-size: 20px !important; }
            .clock-inputs .answer-box { width: 35px; font-size: 1rem; }
            
            .clock-inputs .answer-box::placeholder { color: transparent !important; opacity: 0 !important; }
            .clock-inputs .answer-box::-webkit-input-placeholder { color: transparent !important; opacity: 0 !important; }
            .del-badge i { display: none !important; }
            .del-badge { font-size: 0.65rem !important; padding: 1px 4px !important; border: 1px solid #999 !important; border-radius: 3px !important; }
            .del-selector .del-label { border: 1px solid #999 !important; background: none !important; padding: 1px 5px !important; font-weight: normal !important; font-size: 0.7rem !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .del-selector .del-label i { display: none !important; }
            .del-selector .del-label::before { content: "\2610 "; font-size: 0.85rem; }
            .del-selector .del-radio:checked + .del-label::before { content: "\2611 "; }
            body.show-solutions .del-selector .del-radio:checked + .del-label { border-color: #198754 !important; background-color: #e8f5e9 !important; font-weight: bold !important; }
            
            /* Optimalizált margók, hogy garantáltan kiférjen az 5 sor portrait (álló) nézetben */
            @page { size: A4 portrait; margin: 1.5cm; }
        }
        </style>
        <script>
        document.addEventListener("DOMContentLoaded",function(){
            document.querySelectorAll(".del-radio").forEach(function(r){
                r.addEventListener("change",function(){
                    var s=this.closest(".del-selector");
                    var hiddenInput = s.querySelector(".del-input");
                    hiddenInput.value=this.value;
                    hiddenInput.classList.remove("hiba"); // Gépelés javításakor a hiba class eltávolítása
                });
            });
            var b=document.getElementById("reveal-button");
            if(b){
                b.addEventListener("click",function(){
                    setTimeout(function(){
                        var rev=document.body.classList.contains("show-solutions");
                        document.querySelectorAll(".del-selector").forEach(function(sel){
                            var hiddenInp=sel.querySelector(".del-input");
                            var cor=hiddenInp.dataset.correct;
                            if(rev) hiddenInp.classList.remove("hiba");
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

    // A feladatokat balról-jobbra, sorban generáljuk. 
    // Hozzáadjuk a "clock-grid" osztályt a szigorú CSS nyomtatási felülbíráláshoz.
    $html .= '<div class="row clock-grid gy-3 gy-md-4">'; 

    foreach ($oldal_feladatai as $index => $f) {
        // Képernyőn (böngészőben) a páros indexű elemek (bal oldal) kapnak jobb oldali szegélyt
        $borderClass = ($index % 2 === 0) ? 'border-md-end pe-md-3' : 'ps-md-3 mt-4 mt-md-0';
        
        $html .= '<div class="col-12 col-md-6 ' . $borderClass . '">';

        $del_elott = ($f['ora'] < 12);
        $del_icon = $del_elott ? 'fa-sun text-warning' : 'fa-moon text-primary-emphasis';
        $del_label = $del_elott ? 'DE' : 'DU';

        $html .= '<div class="problem-row h-100 d-flex align-items-center justify-content-center">';

        if ($f['mode'] === 'analog') {
            $ora_str = str_pad($f['ora'], 2, '0', STR_PAD_LEFT);
            $perc_str = str_pad($f['perc'], 2, '0', STR_PAD_LEFT);

            $html .= '<div class="clock-problem">';
            $html .= generate_clock_svg($f['ora'], $f['perc']);
            $html .= '<div class="clock-inputs">';
            $html .= '<span class="del-badge"><i class="fas ' . $del_icon . '"></i> ' . $del_label . '</span>';
            $html .= '<input type="number" class="answer-box" data-correct="' . $ora_str . '" min="0" max="23" placeholder="ó">';
            $html .= '<span class="fw-bold text-secondary">:</span>';
            $html .= '<input type="number" class="answer-box" data-correct="' . $perc_str . '" min="0" max="59" placeholder="pp">';
            $html .= '</div></div>';
        } else {
            // A 12 órás formátum kiszámítása
            $ora_12 = $f['ora'] % 12;
            if ($ora_12 == 0) $ora_12 = 12;
            
            $perc_str = str_pad($f['perc'], 2, '0', STR_PAD_LEFT);
            $ora_24_str = str_pad($f['ora'], 2, '0', STR_PAD_LEFT);
            $del_value = $del_elott ? 'délelőtt' : 'délután';
            $uid = 'del_' . $probSzam . '_' . $p;

            $html .= '<div class="clock-problem">';
            $html .= '<div class="digital-display">' . $ora_24_str . ':' . $perc_str . '</div>';
            $html .= '<span class="clock-arrow mx-1">→</span>';
            $html .= '<div class="clock-inputs">';
            $html .= '<input type="number" class="answer-box" data-correct="' . $ora_12 . '" min="1" max="12" placeholder="ó">';
            $html .= '<span class="fw-bold text-secondary">:</span>';
            $html .= '<input type="number" class="answer-box" data-correct="' . $perc_str . '" min="0" max="59" placeholder="pp">';
            $html .= '<div class="del-selector">';
            $html .= '<input type="hidden" class="del-input" data-correct="' . $del_value . '">';
            $html .= '<input type="radio" class="btn-check del-radio" name="' . $uid . '" id="' . $uid . '_de" value="délelőtt" autocomplete="off">';
            $html .= '<label class="btn btn-outline-warning btn-sm del-label" for="' . $uid . '_de"><i class="fas fa-sun"></i> DE</label>';
            $html .= '<input type="radio" class="btn-check del-radio" name="' . $uid . '" id="' . $uid . '_du" value="délután" autocomplete="off">';
            $html .= '<label class="btn btn-outline-primary btn-sm del-label" for="' . $uid . '_du"><i class="fas fa-moon"></i> DU</label>';
            $html .= '</div></div></div>';
        }

        $html .= '</div>'; // problem-row vége
        $html .= '</div>'; // col-12 col-md-6 vége
        $probSzam++;
    }

    $html .= '</div>'; // row clock-grid vége
    $feladat_oldalak[] = $html;
}