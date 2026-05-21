<?php
/*
 * Fájl: feladatok/labirintus.php
 * Funkció: Számolós matematikai labirintus generálása. (Opció A: Keresd a megoldást)
 * Módosítás: Olvashatatlan text-dark szövegek text-primary-ra cserélése.
 * Utolsó módosítás: 2026. május 07. 19:57:00
 */

// --- BEMENETEK ---
$lab_meret = 5; 
if (isset($_POST['lab_meret']) && in_array((int)$_POST['lab_meret'], [4, 5, 6, 7])) {
    $lab_meret = (int)$_POST['lab_meret'];
}

$lab_muveletek = [];
if (isset($_POST['lab_muveletek']) && is_array($_POST['lab_muveletek'])) {
    foreach ($_POST['lab_muveletek'] as $op) {
        if (in_array($op, ['+', '-', '*', '/'])) {
            $lab_muveletek[] = $op;
        }
    }
}
if (empty($lab_muveletek)) {
    $lab_muveletek = ['+', '-']; 
}

// --- HTML BEÁLLÍTÁSOK GENERÁLÁSA ---
$egyedi_beallitasok_html = '
<div class="col-md-6 col-lg-2">
    <label for="lab_meret" class="form-label fw-bold text-secondary">Rács mérete</label>
    <select class="form-select" id="lab_meret" name="lab_meret">
        <option value="4" ' . ($lab_meret == 4 ? 'selected' : '') . '>4 x 4 (Kicsi)</option>
        <option value="5" ' . ($lab_meret == 5 ? 'selected' : '') . '>5 x 5 (Közepes)</option>
        <option value="6" ' . ($lab_meret == 6 ? 'selected' : '') . '>6 x 6 (Nagy)</option>
        <option value="7" ' . ($lab_meret == 7 ? 'selected' : '') . '>7 x 7 (Óriás)</option>
    </select>
</div>

<div class="col-md-12 col-lg-6">
    <label class="form-label fw-bold text-secondary d-block">Engedélyezett Műveletek</label>
    <div class="d-flex flex-wrap gap-3 align-items-center border rounded p-2 bg-white">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="lab_muveletek[]" value="+" id="op_plus" ' . (in_array('+', $lab_muveletek) ? 'checked' : '') . '>
            <label class="form-check-label fw-bold" for="op_plus">+</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="lab_muveletek[]" value="-" id="op_minus" ' . (in_array('-', $lab_muveletek) ? 'checked' : '') . '>
            <label class="form-check-label fw-bold" for="op_minus">−</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="lab_muveletek[]" value="*" id="op_mul" ' . (in_array('*', $lab_muveletek) ? 'checked' : '') . '>
            <label class="form-check-label fw-bold" for="op_mul">·</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="lab_muveletek[]" value="/" id="op_div" ' . (in_array('/', $lab_muveletek) ? 'checked' : '') . '>
            <label class="form-check-label fw-bold" for="op_div">:</label>
        </div>
    </div>
</div>
';

// --- SEGÉDFÜGGVÉNYEK ---
function generate_simple_math($ops, $limit) {
    $op = $ops[array_rand($ops)];
    $a = 0; $b = 0; $res = 0;
    
    if ($op === '+') {
        $res = random_int(2, $limit);
        $a = random_int(1, $res - 1);
        $b = $res - $a;
    } elseif ($op === '-') {
        $a = random_int(2, $limit);
        $b = random_int(1, $a - 1);
        $res = $a - $b;
    } elseif ($op === '*') {
        $max_f = floor(sqrt($limit));
        $max_f = max(2, $max_f); 
        $a = random_int(2, $max_f);
        $b = random_int(2, max(2, floor($limit / $a)));
        $res = $a * $b;
    } elseif ($op === '/') {
        $max_f = floor(sqrt($limit));
        $max_f = max(2, $max_f);
        $b = random_int(2, $max_f);
        $res = random_int(2, max(2, floor($limit / $b)));
        $a = $b * $res;
    }
    
    $str_op = str_replace(['*','/'], ['&middot;', ':'], $op);
    return ['str' => "$a $str_op $b", 'res' => $res];
}

// Bővített, játékos instrukciók tárháza (Javított színekkel: text-primary)
$instrukciok = [
    "Indulj a <strong class='text-primary'>START</strong> mezőről! Számold ki az ott lévő műveletet, majd lépj a helyes eredményt tartalmazó szomszédos (fel, le, balra, jobbra) mezőre. Folytasd az utat a <strong class='text-success'>CÉL</strong> feliratig!",
    "Készen állsz? A <strong class='text-primary'>START</strong> celláról indulva minden mezőn egy feladatot találsz. A helyes válasz megmutatja, merre kell tovább lépned. Találd meg az utat a <strong class='text-success'>CÉL</strong>-ig!",
    "Kövesd a helyes számokat! A <strong class='text-primary'>START</strong>-tól kezdve végezd el a műveleteket. Csak arra a szomszédos cellára léphetsz, amelyik a feladatod helyes eredményét mutatja.",
    "Matematikai küldetés: Indulj a <strong class='text-primary'>START</strong> felirattól. Számolj pontosan, és kövesd az eredményeket a mezőkön keresztül. A jó válaszok a <strong class='text-success'>CÉL</strong>-ba vezetnek!",
    "Keresd meg a kiutat! Számítsd ki a <strong class='text-primary'>START</strong> mezőn lévő feladatot, a kapott eredmény lesz a belépőd a következő mezőre. Lépésről lépésre jutsz el a <strong class='text-success'>CÉL</strong>-ba.",
    "Labirintus kaland: A <strong class='text-success'>CÉL</strong>-ba csak a helyes eredményeken át vezet út. Indulj a <strong class='text-primary'>START</strong>-ról, számolj, és válaszd mindig a jó irányt a szomszédos mezők közül!",
    "Vigyázz a zsákutcákkal! Oldd meg a műveletet a <strong class='text-primary'>START</strong> cellán, majd lépj arra a szomszédos mezőre, ahol az eredményt látod. A hibás számok tévútra visznek!",
    "A matematika az iránytűd! A <strong class='text-primary'>START</strong> mező művelete megadja, merre léphetsz. Keresd meg a szomszédok között a helyes választ, amíg el nem éred a <strong class='text-success'>CÉL</strong>-t.",
    "Törd a fejed és találd meg az utat! A <strong class='text-primary'>START</strong> mezőről lépj a helyes válaszra, majd az ott lévő új feladat eredményét keresd meg a következő lépéshez.",
    "Számolj és navigálj! A <strong class='text-primary'>START</strong>-tól indulva a jó eredmények mutatják a <strong class='text-success'>CÉL</strong> felé vezető egyetlen helyes utat. Ne hagyd magad becsapni a hamis számokkal!"
];

// --- FELADATOK GENERÁLÁSA ---
$feladatok_per_oldal = ($lab_meret >= 6) ? 4 : 6;
$col_class = ($lab_meret >= 6) ? 'col-12 col-md-6 print-col-6' : 'col-12 col-md-6 col-lg-4 print-col-4'; 

$print_mh = '60px'; $print_fs = '0.9rem';
if ($lab_meret == 5) { $print_mh = '50px'; $print_fs = '0.85rem'; }
if ($lab_meret == 6) { $print_mh = '45px'; $print_fs = '0.8rem'; }
if ($lab_meret == 7) { $print_mh = '40px'; $print_fs = '0.75rem'; }

global $oldal_cim; 
$aktualis_cim = isset($oldal_cim) ? $oldal_cim : 'Számolós Labirintus';

for ($p = 0; $p < $oldalak_szama; $p++) {
    
    $html = '';

    if ($p === 0) {
        $html .= '
        <style>
            .worksheet > h3.text-center { display: none !important; }

            .lab-header {
                display: flex; align-items: center; justify-content: space-between;
                margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #eee; gap: 1rem;
            }
            .lab-title { flex: 0 0 auto; margin: 0; }
            .instruction-box {
                background-color: transparent !important; border-left: 5px solid #0dcaf0;
                padding: 5px 15px; flex: 1 1 auto;
            }
            .instruction-box p { margin: 0; line-height: 1.3; }

            .maze-grid { margin: 0 auto 1.5rem auto; max-width: 100%; }
            .maze-cell {
                border: 2px solid #555; border-radius: 8px; text-align: center;
                background-color: #fff; display: flex; flex-direction: column;
                justify-content: flex-start; min-height: 80px;
                box-shadow: 2px 2px 5px rgba(0,0,0,0.05); overflow: hidden;
                cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
                user-select: none;
            }
            .maze-cell:hover { transform: scale(1.02); }
            .maze-entry {
                font-size: 1.3rem; font-weight: bold; border-bottom: 1px dashed #ccc;
                padding: 4px 2px; background-color: #f8f9fa;
                transition: background-color 0.2s ease, color 0.2s ease;
            }
            .maze-math {
                font-size: 1.2rem; padding: 10px 2px; flex-grow: 1;
                display: flex; align-items: center; justify-content: center;
            }

            /* FELHASZNÁLÓI KIJELÖLÉS */
            .maze-cell.user-selected {
                background-color: #cfe2ff; border-color: #0d6efd; box-shadow: 0 0 8px rgba(13, 110, 253, 0.4);
            }
            .maze-cell.user-selected .maze-entry {
                background-color: #9ec5fe; color: #052c65; border-bottom-color: #0d6efd;
            }

            /* HIBÁS KIJELÖLÉS */
            .maze-cell.hiba {
                background-color: #f8d7da !important; border-color: #dc3545 !important;
                animation: shake 0.4s ease-in-out;
            }
            .maze-cell.hiba .maze-entry { background-color: #f5c2c7 !important; color: #842029 !important; }

            @keyframes shake {
                0% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                50% { transform: translateX(5px); }
                75% { transform: translateX(-5px); }
                100% { transform: translateX(0); }
            }

            /* MEGOLDÁS MUTATÁSA */
            body.show-solutions .maze-cell[data-is-path="1"] {
                background-color: #d1e7dd !important; border-color: #0f5132 !important;
            }
            body.show-solutions .maze-cell[data-is-path="1"] .maze-entry {
                background-color: #badbcc !important; color: #0f5132 !important; border-bottom-color: #0f5132 !important;
            }

            /* --- KÖTELEZŐ NYOMTATÁSI NÉZET --- */
            @media print {
                @page { size: A4 landscape; margin: 1cm; }
                .row.g-4 { --bs-gutter-x: 0.5rem; --bs-gutter-y: 0.5rem; }
                .maze-grid { gap: 1px !important; margin-bottom: 0.5rem !important; }
                
                .lab-header { margin-bottom: 0.5rem !important; padding-bottom: 0.25rem !important; }
                .instruction-box { padding: 2px 10px !important; border-left-width: 4px !important; }
                .instruction-box p { font-size: 0.85rem !important; line-height: 1.2 !important; }
                
                .print-col-4 { width: 33.333333% !important; flex: 0 0 33.333333% !important; max-width: 33.333333% !important; }
                .print-col-6 { width: 50% !important; flex: 0 0 50% !important; max-width: 50% !important; }
                .maze-wrapper { page-break-inside: avoid; break-inside: avoid; }

                .maze-cell { border-width: 1px !important; box-shadow: none !important; min-height: ' . $print_mh . ' !important; transform: none !important; }
                .maze-entry { font-size: ' . $print_fs . ' !important; padding: 1px !important; background-color: transparent !important; border-bottom: 1px solid #aaa !important; }
                .maze-math { font-size: ' . $print_fs . ' !important; padding: 1px !important; }

                body.show-solutions .maze-cell[data-is-path="1"] { background-color: #e8f5e9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                body.show-solutions .maze-cell[data-is-path="1"] .maze-entry { background-color: #c8e6c9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            }
        </style>';
    }

    $aktualis_instrukcio = $instrukciok[array_rand($instrukciok)];
    $oldal_szoveg = ($oldalak_szama > 1) ? '<small class="text-muted d-block fs-6 mt-1">(' . ($p + 1) . '. oldal)</small>' : '';
    
    $html .= '
    <div class="lab-header border-secondary-subtle">
        <h3 class="lab-title fw-bold text-body-secondary">
            ' . htmlspecialchars($aktualis_cim) . '
            ' . $oldal_szoveg . '
        </h3>
        <div class="instruction-box">
            <p class="mb-0 text-body-secondary">
                <strong><i class="fas fa-gamepad me-1 no-print text-info"></i> Szabály:</strong> 
                ' . $aktualis_instrukcio . '
            </p>
        </div>
    </div>';

    $html .= '<div class="row g-4">';
    
    $oldal_feladatai = [];
    while (count($oldal_feladatai) < $feladatok_per_oldal) {
        
        $limit = $szamkor_hatar;
        if ($szuper_konnyu) {
            $limit = min($szamkor_hatar, 30);
        }

        $rows = $lab_meret;
        $cols = $lab_meret;
        
        $path = ["0,0"];
        $r = 0; $c = 0;
        while ($r < $rows - 1 || $c < $cols - 1) {
            $options = [];
            if ($r < $rows - 1) $options[] = [$r+1, $c];
            if ($c < $cols - 1) $options[] = [$r, $c+1];
            
            $next = $options[array_rand($options)];
            $r = $next[0]; $c = $next[1];
            $path[] = "$r,$c";
        }

        $grid = [];
        for($i=0; $i<$rows; $i++) $grid[$i] = array_fill(0, $cols, []);
        
        for($i=0; $i<count($path); $i++) {
            list($r, $c) = explode(",", $path[$i]);
            $grid[$r][$c]['is_path'] = 1;
            
            if ($i == 0) {
                $grid[$r][$c]['entry_num'] = 'START';
            } else {
                list($pr, $pc) = explode(",", $path[$i-1]);
                $grid[$r][$c]['entry_num'] = $grid[$pr][$pc]['correct_answer'];
            }
            
            if ($i == count($path) - 1) {
                $grid[$r][$c]['math'] = 'CÉL';
                $grid[$r][$c]['correct_answer'] = 0; 
            } else {
                $math = generate_simple_math($lab_muveletek, $limit);
                $grid[$r][$c]['math'] = $math['str'];
                $grid[$r][$c]['correct_answer'] = $math['res'];
            }
        }
        
        for($r=0; $r<$rows; $r++) {
            for($c=0; $c<$cols; $c++) {
                if (!isset($grid[$r][$c]['is_path'])) {
                    $grid[$r][$c]['is_path'] = 0;
                    $math = generate_simple_math($lab_muveletek, $limit);
                    $grid[$r][$c]['math'] = $math['str'];
                    $grid[$r][$c]['correct_answer'] = $math['res'];
                    
                    $forbidden_entries = [];
                    $neighbors = [];
                    $dirs = [[1,0], [-1,0], [0,1], [0,-1]];
                    
                    foreach($dirs as $d) {
                        $nr = $r+$d[0]; $nc = $c+$d[1];
                        if($nr>=0 && $nr<$rows && $nc>=0 && $nc<$cols) {
                            $neighbors[] = [$nr, $nc];
                            if (isset($grid[$nr][$nc]['correct_answer'])) {
                                $forbidden_entries[] = $grid[$nr][$nc]['correct_answer'];
                            }
                        }
                    }
                    
                    $pn = $neighbors[array_rand($neighbors)];
                    $target_res = isset($grid[$pn[0]][$pn[1]]['correct_answer']) ? $grid[$pn[0]][$pn[1]]['correct_answer'] : random_int(5, $limit);
                    if ($target_res == 0) $target_res = random_int(5, $limit);
                    
                    $max_attempts = 50;
                    do {
                        if ($nehezebb) {
                            $diffs = [-10, -2, -1, 1, 2, 10];
                            $fake_ans = $target_res + $diffs[array_rand($diffs)];
                        } else {
                            $fake_diff = random_int(3, 10);
                            $fake_ans = (random_int(0,1)==0) ? $target_res + $fake_diff : $target_res - $fake_diff;
                        }
                        if ($fake_ans <= 0) $fake_ans = $target_res + random_int(1, 5);
                        $max_attempts--;
                    } while (in_array($fake_ans, $forbidden_entries) && $max_attempts > 0);
                    
                    if ($max_attempts <= 0) {
                        $fake_ans = $limit + random_int(100, 500) + $r + $c;
                    }
                    $grid[$r][$c]['entry_num'] = $fake_ans;
                }
            }
        }

        $lab_num = count($oldal_feladatai) + 1;
        $grid_html = "<h6 class='text-center text-body-secondary mb-1 fw-bold'>Labirintus #{$lab_num}</h6>";
        $grid_html .= '<div class="maze-grid" style="display:grid; grid-template-columns: repeat('.$cols.', 1fr); gap: 6px;">';
        
        for($r=0; $r<$rows; $r++) {
            for($c=0; $c<$cols; $c++) {
                $cell = $grid[$r][$c];
                $is_path = $cell['is_path'];
                $entry = $cell['entry_num'];
                $math = $cell['math'];
                
                $selected_class = ($entry === 'START') ? ' user-selected' : '';
                
                $grid_html .= "<div class='maze-cell{$selected_class}' data-is-path='$is_path'>";
                
                if ($entry === 'START') {
                    $grid_html .= "<div class='maze-entry bg-dark text-white rounded-top py-1' style='font-size: 1rem; border-bottom: none;'>$entry</div>";
                } else {
                    $grid_html .= "<div class='maze-entry text-primary'>$entry</div>";
                }
                
                if ($math === 'CÉL') {
                    $grid_html .= "<div class='maze-math bg-success text-white rounded-bottom py-1 fw-bold' style='font-size: 1.2rem; margin-top: auto;'>$math</div>";
                } else {
                    $grid_html .= "<div class='maze-math fw-bold text-body' style='margin-top: auto; padding-bottom: 5px;'>$math</div>";
                }
                
                $grid_html .= "</div>";
            }
        }
        $grid_html .= '</div>';
        
        $oldal_feladatai[] = $grid_html;
    }

    foreach ($oldal_feladatai as $grid_html) {
        $html .= '<div class="' . $col_class . ' maze-wrapper">';
        $html .= $grid_html;
        $html .= '</div>';
    }

    $html .= '</div>';
    $feladat_oldalak[] = $html;
}