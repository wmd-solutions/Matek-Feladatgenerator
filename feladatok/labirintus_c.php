<?php
/*
 * Fájl: feladatok/labirintus_c.php
 * Funkció: Számolós matematikai labirintus generálása. (Opció C: Az akkumulátor)
 * Módosítás: Olvashatatlan text-dark szövegek text-primary-ra cserélése.
 * Utolsó módosítás: 2026. május 07. 19:57:00
 */

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

function get_next_op($acc, $ops, $limit, $is_hard) {
    $valid = false;
    $attempts = 0;
    while (!$valid && $attempts < 50) {
        $attempts++;
        $op = $ops[array_rand($ops)];
        $val = 0;
        $new_acc = $acc;
        
        if ($op === '+') {
            $val = random_int(1, max(1, $limit - $acc));
            $new_acc = $acc + $val;
        } elseif ($op === '-') {
            if ($acc <= 1) continue;
            $val = random_int(1, $acc - 1);
            $new_acc = $acc - $val;
        } elseif ($op === '*') {
            if ($acc <= 1 || $acc > $limit/2) continue;
            $max_mul = floor($limit / $acc);
            if ($max_mul < 2) continue;
            $val = random_int(2, $max_mul);
            $new_acc = $acc * $val;
        } elseif ($op === '/') {
            $divisors = [];
            for ($i = 2; $i <= $acc; $i++) {
                if ($acc % $i === 0) $divisors[] = $i;
            }
            if (empty($divisors)) continue;
            if ($is_hard && count($divisors) > 1) {
                if (($key = array_search(1, $divisors)) !== false) unset($divisors[$key]);
            }
            $val = $divisors[array_rand($divisors)];
            $new_acc = $acc / $val;
        }
        
        if ($new_acc > 0 && $new_acc <= $limit) {
            $str_op = str_replace(['*','/'], ['&middot;', ':'], $op);
            return ['str' => $str_op . ' ' . $val, 'new_acc' => $new_acc];
        }
    }
    return ['str' => '+ 1', 'new_acc' => $acc + 1];
}

$instrukciok = [
    "Indulj a <strong class='text-primary'>START</strong> mezőn lévő számmal! Minden lépésnél végezd el az új mezőn látható műveletet. Találd meg azt az utat, amelynek a végén pontosan a <strong class='text-success'>CÉL</strong>-ban lévő számot kapod!",
    "Akkumulátor: A <strong class='text-primary'>START</strong> számmal indulsz. Útközben a mezők folyamatosan növelik, csökkentik, szorozzák vagy osztják az értéked. Navigálj úgy, hogy a <strong class='text-success'>CÉL</strong>-ba érve a végeredményed megegyezzen az ott lévő számmal!",
    "Göngyölítsd a számokat! Kezdd a <strong class='text-primary'>START</strong> mezőről, végezd el sorban a matematikai műveleteket az elért mezőkön. Jó irányba mész, ha az utolsó lépés után a <strong class='text-success'>CÉL</strong> száma jön ki.",
    "Számolj folyamatosan! A <strong class='text-primary'>START</strong> számtól haladj lépésről lépésre. Az utad végén a számításaidnak pontosan a <strong class='text-success'>CÉL</strong> értékét kell kiadniuk!"
];

$feladatok_per_oldal = ($lab_meret >= 6) ? 4 : 6;
$col_class = ($lab_meret >= 6) ? 'col-12 col-md-6 print-col-6' : 'col-12 col-md-6 col-lg-4 print-col-4'; 

$print_mh = '60px'; $print_fs = '0.9rem';
if ($lab_meret == 5) { $print_mh = '50px'; $print_fs = '0.85rem'; }
if ($lab_meret == 6) { $print_mh = '45px'; $print_fs = '0.8rem'; }
if ($lab_meret == 7) { $print_mh = '40px'; $print_fs = '0.75rem'; }

global $oldal_cim; 
$aktualis_cim = isset($oldal_cim) ? $oldal_cim : 'Labirintus (Akkumulátor)';

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
                cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
                user-select: none;
            }
            .maze-cell:hover { transform: scale(1.02); }
            
            .maze-entry { font-size: 1rem; font-weight: bold; padding: 4px 2px; transition: background-color 0.2s ease, color 0.2s ease; }
            .maze-math { font-size: 1.5rem; padding: 10px 2px; flex-grow: 1; display: flex; align-items: center; justify-content: center; }

            .maze-cell.user-selected {
                background-color: #cfe2ff !important; border-color: #0d6efd !important;
                box-shadow: 0 0 8px rgba(13, 110, 253, 0.4);
            }
            .maze-cell.user-selected .maze-entry.bg-dark { background-color: #052c65 !important; }
            .maze-cell.user-selected .maze-math { color: #052c65 !important; }

            .maze-cell.hiba {
                background-color: #f8d7da !important; border-color: #dc3545 !important;
                animation: shake 0.4s ease-in-out;
            }
            .maze-cell.hiba .maze-math { color: #842029 !important; }

            @keyframes shake {
                0% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                50% { transform: translateX(5px); }
                75% { transform: translateX(-5px); }
                100% { transform: translateX(0); }
            }

            body.show-solutions .maze-cell[data-is-path="1"] {
                background-color: #d1e7dd !important; border-color: #0f5132 !important;
                box-shadow: 0 0 10px rgba(25, 135, 84, 0.5) !important;
            }
            body.show-solutions .maze-cell[data-is-path="1"] .maze-entry.bg-dark { background-color: #0f5132 !important; }
            body.show-solutions .maze-cell[data-is-path="1"] .maze-math { color: #0f5132 !important; }

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
                .maze-entry { font-size: 0.7rem !important; padding: 1px !important; }
                .maze-math { font-size: ' . $print_fs . ' !important; padding: 1px !important; }

                body.show-solutions .maze-cell[data-is-path="1"] { background-color: #e8f5e9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                body.show-solutions .maze-cell[data-is-path="1"] .maze-entry.bg-dark { background-color: #198754 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
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
            $limit = min($szamkor_hatar, 50);
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
        
        $start_val = random_int(5, min($limit, 50));
        $acc = $start_val;

        for($i=0; $i<count($path); $i++) {
            list($r, $c) = explode(",", $path[$i]);
            $grid[$r][$c]['is_path'] = 1;
            
            if ($i == 0) {
                $grid[$r][$c]['entry'] = 'START';
                $grid[$r][$c]['math'] = $start_val;
            } elseif ($i == count($path) - 1) {
                $grid[$r][$c]['entry'] = 'CÉL';
                $grid[$r][$c]['math'] = $acc;
            } else {
                $op_data = get_next_op($acc, $lab_muveletek, $limit, $nehezebb);
                $grid[$r][$c]['math'] = $op_data['str'];
                $acc = $op_data['new_acc'];
            }
        }
        
        for($r=0; $r<$rows; $r++) {
            for($c=0; $c<$cols; $c++) {
                if (!isset($grid[$r][$c]['is_path'])) {
                    $grid[$r][$c]['is_path'] = 0;
                    
                    $rand_op = $lab_muveletek[array_rand($lab_muveletek)];
                    $rand_val = random_int(1, 20);
                    if ($rand_op == '*' || $rand_op == '/') $rand_val = random_int(2, 10);
                    
                    $str_op = str_replace(['*','/'], ['&middot;', ':'], $rand_op);
                    $grid[$r][$c]['math'] = "$str_op $rand_val";
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
                $math = $cell['math'];
                
                $selected_class = (isset($cell['entry']) && $cell['entry'] === 'START') ? ' user-selected' : '';
                
                $grid_html .= "<div class='maze-cell{$selected_class}' data-is-path='$is_path'>";
                
                if (isset($cell['entry'])) {
                    $bg_class = ($cell['entry'] === 'START') ? 'bg-dark' : 'bg-success';
                    $grid_html .= "<div class='maze-entry $bg_class text-white rounded-top'>{$cell['entry']}</div>";
                    $grid_html .= "<div class='maze-math fw-bold text-body'>$math</div>";
                } else {
                    $grid_html .= "<div class='maze-math fw-bold text-body' style='height:100%;'>$math</div>";
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