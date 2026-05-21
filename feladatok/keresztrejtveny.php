<?php
/*
 * Fájl: feladatok/keresztrejtveny.php
 * Funkció: Egymást keresztező matematikai egyenletek (keresztrejtvény) generálása.
 * Utolsó módosítás: 2026. május 07. 20:45:00
 */

// --- BEMENETEK ---
$cw_meret = 8; // Kereszteződések (egyenletek) száma
if (isset($_POST['cw_meret']) && in_array((int)$_POST['cw_meret'], [5, 8, 12, 16])) {
    $cw_meret = (int)$_POST['cw_meret'];
}

$cw_muveletek = [];
if (isset($_POST['cw_muveletek']) && is_array($_POST['cw_muveletek'])) {
    foreach ($_POST['cw_muveletek'] as $op) {
        if (in_array($op, ['+', '-', '*', '/'])) {
            $cw_muveletek[] = $op;
        }
    }
}
if (empty($cw_muveletek)) {
    $cw_muveletek = ['+', '-']; 
}

// --- HTML BEÁLLÍTÁSOK GENERÁLÁSA ---
$egyedi_beallitasok_html = '
<div class="col-md-6 col-lg-2">
    <label for="cw_meret" class="form-label fw-bold text-secondary">Rejtvény mérete</label>
    <select class="form-select" id="cw_meret" name="cw_meret">
        <option value="5" ' . ($cw_meret == 5 ? 'selected' : '') . '>Kicsi (5 sor)</option>
        <option value="8" ' . ($cw_meret == 8 ? 'selected' : '') . '>Közepes (8 sor)</option>
        <option value="12" ' . ($cw_meret == 12 ? 'selected' : '') . '>Nagy (12 sor)</option>
        <option value="16" ' . ($cw_meret == 16 ? 'selected' : '') . '>Óriás (16 sor)</option>
    </select>
</div>

<div class="col-md-12 col-lg-6">
    <label class="form-label fw-bold text-secondary d-block">Engedélyezett Műveletek</label>
    <div class="d-flex flex-wrap gap-3 align-items-center border rounded p-2 bg-white">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="cw_muveletek[]" value="+" id="op_plus" ' . (in_array('+', $cw_muveletek) ? 'checked' : '') . '>
            <label class="form-check-label fw-bold" for="op_plus">+</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="cw_muveletek[]" value="-" id="op_minus" ' . (in_array('-', $cw_muveletek) ? 'checked' : '') . '>
            <label class="form-check-label fw-bold" for="op_minus">−</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="cw_muveletek[]" value="*" id="op_mul" ' . (in_array('*', $cw_muveletek) ? 'checked' : '') . '>
            <label class="form-check-label fw-bold" for="op_mul">·</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="cw_muveletek[]" value="/" id="op_div" ' . (in_array('/', $cw_muveletek) ? 'checked' : '') . '>
            <label class="form-check-label fw-bold" for="op_div">:</label>
        </div>
    </div>
</div>
';

// --- SEGÉDFÜGGVÉNYEK A GENERÁLÁSHOZ ---

function generate_random_equation($ops, $limit) {
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
        $max_f = max(2, floor(sqrt($limit)));
        $a = random_int(2, $max_f);
        $b = random_int(2, max(2, floor($limit / $a)));
        $res = $a * $b;
    } elseif ($op === '/') {
        $max_f = max(2, floor(sqrt($limit)));
        $b = random_int(2, $max_f);
        $res = random_int(2, max(2, floor($limit / $b)));
        $a = $b * $res;
    }
    return [$a, $op, $b, '=', $res];
}

function generate_eq_for_target($target, $pos, $ops, $limit) {
    $valid_eqs = [];
    foreach ($ops as $op) {
        if ($op == '+') {
            if ($pos == 0 && $target < $limit) {
                $b = random_int(1, $limit - $target);
                $valid_eqs[] = [$target, '+', $b, '=', $target + $b];
            } elseif ($pos == 2 && $target < $limit) {
                $a = random_int(1, $limit - $target);
                $valid_eqs[] = [$a, '+', $target, '=', $a + $target];
            } elseif ($pos == 4 && $target > 1) {
                $a = random_int(1, $target - 1);
                $valid_eqs[] = [$a, '+', $target - $a, '=', $target];
            }
        } elseif ($op == '-') {
            if ($pos == 0 && $target > 1) {
                $b = random_int(1, $target - 1);
                $valid_eqs[] = [$target, '-', $b, '=', $target - $b];
            } elseif ($pos == 2 && $limit > $target) {
                $c = random_int(1, $limit - $target);
                $valid_eqs[] = [$target + $c, '-', $target, '=', $c];
            } elseif ($pos == 4 && $limit > $target) {
                $a = random_int($target + 1, $limit);
                $valid_eqs[] = [$a, '-', $a - $target, '=', $target];
            }
        } elseif ($op == '*') {
            if ($pos == 0 && $target <= $limit / 2) {
                $max_b = floor($limit / $target);
                if ($max_b >= 2) {
                    $b = random_int(2, $max_b);
                    $valid_eqs[] = [$target, '*', $b, '=', $target * $b];
                }
            } elseif ($pos == 2 && $target <= $limit / 2) {
                $max_a = floor($limit / $target);
                if ($max_a >= 2) {
                    $a = random_int(2, $max_a);
                    $valid_eqs[] = [$a, '*', $target, '=', $a * $target];
                }
            } elseif ($pos == 4) {
                $factors = [];
                for ($i = 2; $i <= sqrt($target); $i++) {
                    if ($target % $i == 0) {
                        $factors[] = $i;
                        if ($i != $target / $i) $factors[] = $target / $i;
                    }
                }
                if (!empty($factors)) {
                    $a = $factors[array_rand($factors)];
                    $valid_eqs[] = [$a, '*', $target / $a, '=', $target];
                }
            }
        } elseif ($op == '/') {
            if ($pos == 0) {
                $factors = [];
                for ($i = 2; $i <= sqrt($target); $i++) {
                    if ($target % $i == 0) {
                        $factors[] = $i;
                        if ($i != $target / $i) $factors[] = $target / $i;
                    }
                }
                if (!empty($factors)) {
                    $b = $factors[array_rand($factors)];
                    $valid_eqs[] = [$target, '/', $b, '=', $target / $b];
                }
            } elseif ($pos == 2 && $target * 2 <= $limit) {
                $max_c = floor($limit / $target);
                if ($max_c >= 1) {
                    $c = random_int(1, $max_c);
                    $valid_eqs[] = [$target * $c, '/', $target, '=', $c];
                }
            } elseif ($pos == 4 && $target * 2 <= $limit) {
                $max_b = floor($limit / $target);
                if ($max_b >= 2) {
                    $b = random_int(2, $max_b);
                    $valid_eqs[] = [$target * $b, '/', $b, '=', $target];
                }
            }
        }
    }
    if (empty($valid_eqs)) return null;
    return $valid_eqs[array_rand($valid_eqs)];
}

function can_place(&$grid, $eq, $r, $c, $dir, $H, $W) {
    for ($i = 0; $i < 5; $i++) {
        $cr = $r + ($dir == 1 ? $i : 0);
        $cc = $c + ($dir == 0 ? $i : 0);

        if ($cr < 0 || $cr >= $H || $cc < 0 || $cc >= $W) return false;

        if ($grid[$cr][$cc] !== null) {
            if ((string)$grid[$cr][$cc] !== (string)$eq[$i]) return false;
        } else {
            // Szomszédos cellák ellenőrzése, hogy ne érjenek össze párhuzamos egyenletek
            if ($dir == 0) { // Horizontális
                if ($cr > 0 && $grid[$cr-1][$cc] !== null) return false;
                if ($cr < $H-1 && $grid[$cr+1][$cc] !== null) return false;
            } else { // Vertikális
                if ($cc > 0 && $grid[$cr][$cc-1] !== null) return false;
                if ($cc < $W-1 && $grid[$cr][$cc+1] !== null) return false;
            }
        }
    }
    // Az egyenlet végeinek ellenőrzése
    if ($dir == 0) {
        if ($c > 0 && $grid[$r][$c-1] !== null) return false;
        if ($c + 5 < $W && $grid[$r][$c+5] !== null) return false;
    } else {
        if ($r > 0 && $grid[$r-1][$c] !== null) return false;
        if ($r + 5 < $H && $grid[$r+5][$c] !== null) return false;
    }

    return true;
}

function place_equation(&$grid, $eq, $r, $c, $dir) {
    for ($i = 0; $i < 5; $i++) {
        $cr = $r + ($dir == 1 ? $i : 0);
        $cc = $c + ($dir == 0 ? $i : 0);
        $grid[$cr][$cc] = $eq[$i];
    }
}

// --- FELADATOK GENERÁLÁSA ---

$feladatok_per_oldal = ($cw_meret >= 12) ? 2 : 4;
$col_class = ($cw_meret >= 12) ? 'col-12 print-col-6' : 'col-12 col-md-6 print-col-6'; 

for ($p = 0; $p < $oldalak_szama; $p++) {
    
    $html = '';

    if ($p === 0) {
        $html .= '
        <style>
            @media print {
                @page { size: A4 landscape; margin: 1cm; }
                .print-col-6 { width: 50% !important; flex: 0 0 50% !important; max-width: 50% !important; }
                .cw-container { padding: 5px !important; margin-bottom: 1rem !important; page-break-inside: avoid; break-inside: avoid; }
                .cw-cell { font-size: 1rem !important; }
                .cw-op { font-size: 1.2rem !important; }
            }
        </style>';
    }

    $html .= '<div class="row g-4 justify-content-center">';
    
    $oldal_feladatai = [];
    while (count($oldal_feladatai) < $feladatok_per_oldal) {
        
        $limit = $szamkor_hatar;
        if ($szuper_konnyu) {
            $limit = min($szamkor_hatar, 50); 
        } elseif ($nehezebb) {
            $limit = $szamkor_hatar * 2; // Nehezebb módban nagyobb számok
        }

        $W = 25; $H = 25;
        $grid = array_fill(0, $H, array_fill(0, $W, null));
        $placed_eqs = [];
        $anchors = [];

        // Első egyenlet elhelyezése
        $eq = generate_random_equation($cw_muveletek, $limit);
        $start_r = floor($H / 2);
        $start_c = floor($W / 2) - 2;
        place_equation($grid, $eq, $start_r, $start_c, 0);
        $placed_eqs[] = ['r'=>$start_r, 'c'=>$start_c, 'dir'=>0, 'eq'=>$eq];

        $anchors[] = ['r'=>$start_r, 'c'=>$start_c+0, 'val'=>$eq[0], 'dir'=>0];
        $anchors[] = ['r'=>$start_r, 'c'=>$start_c+2, 'val'=>$eq[2], 'dir'=>0];
        $anchors[] = ['r'=>$start_r, 'c'=>$start_c+4, 'val'=>$eq[4], 'dir'=>0];

        $placed = 1;
        $attempts = 0;

        while($placed < $cw_meret && $attempts < 1000) {
            $attempts++;
            if (empty($anchors)) break;

            $anchor_idx = array_rand($anchors);
            $anchor = $anchors[$anchor_idx];

            $new_dir = 1 - $anchor['dir'];
            $new_pos = array_rand([0=>0, 2=>2, 4=>4]);

            $start_r = $anchor['r'] - ($new_dir == 1 ? $new_pos : 0);
            $start_c = $anchor['c'] - ($new_dir == 0 ? $new_pos : 0);

            if ($start_r < 0 || $start_c < 0 || $start_r + ($new_dir==1?4:0) >= $H || $start_c + ($new_dir==0?4:0) >= $W) {
                continue;
            }

            $eq = generate_eq_for_target($anchor['val'], $new_pos, $cw_muveletek, $limit);
            if (!$eq) continue;

            if (can_place($grid, $eq, $start_r, $start_c, $new_dir, $H, $W)) {
                place_equation($grid, $eq, $start_r, $start_c, $new_dir);
                $placed_eqs[] = ['r'=>$start_r, 'c'=>$start_c, 'dir'=>$new_dir, 'eq'=>$eq];

                foreach ([0, 2, 4] as $pos) {
                    if ($pos != $new_pos) {
                        $anchors[] = [
                            'r' => $start_r + ($new_dir==1?$pos:0),
                            'c' => $start_c + ($new_dir==0?$pos:0),
                            'val' => $eq[$pos],
                            'dir' => $new_dir
                        ];
                    }
                }
                // Az aktuális horgonyt kivesszük, hogy ne építsünk rá túl sokat, de a biztonság kedvéért benne is maradhat.
                unset($anchors[$anchor_idx]);
                $placed++;
            }
        }

        // Csonkítás (Bounding Box)
        $min_r = $H; $max_r = 0; $min_c = $W; $max_c = 0;
        for($r=0; $r<$H; $r++) {
            for($c=0; $c<$W; $c++) {
                if ($grid[$r][$c] !== null) {
                    $min_r = min($min_r, $r); $max_r = max($max_r, $r);
                    $min_c = min($min_c, $c); $max_c = max($max_c, $c);
                }
            }
        }
        
        // Ha valamiért nem generált semmit, újra (nem fordulhat elő, de biztonságosabb)
        if ($min_r > $max_r) continue;

        // Melyik cellákat rejtsük el? (Input mezők)
        $hidden_cells = [];
        foreach ($placed_eqs as $eq_data) {
            if ($szuper_konnyu) {
                // Csak az eredményt rejtjük el
                $pos = 4;
            } else {
                // Egy véletlen számot a 3 közül
                $pos = array_rand([0=>0, 2=>2, 4=>4]);
            }
            $hr = $eq_data['r'] + ($eq_data['dir'] == 1 ? $pos : 0);
            $hc = $eq_data['c'] + ($eq_data['dir'] == 0 ? $pos : 0);
            $hidden_cells["$hr,$hc"] = true;
        }

        // Keresztrejtvény HTML összeállítása
        $cell_size = ($cw_meret >= 12) ? '35px' : '45px';
        $cw_num = count($oldal_feladatai) + 1;
        
        $cw_html = "<h5 class='text-center text-secondary mb-3 fw-bold'>Rejtvény #{$cw_num}</h5>";
        $cw_html .= '<div class="cw-container">';
        $cw_html .= '<div class="cw-grid" style="grid-template-columns: repeat('.($max_c - $min_c + 1).', '.$cell_size.'); grid-auto-rows: '.$cell_size.';">';
        
        for ($r=$min_r; $r<=$max_r; $r++) {
            for ($c=$min_c; $c<=$max_c; $c++) {
                $val = $grid[$r][$c];
                if ($val === null) {
                    $cw_html .= '<div class="cw-empty"></div>';
                } else {
                    if (is_numeric($val)) {
                        if (isset($hidden_cells["$r,$c"])) {
                            // Input mező
                            $cw_html .= '<div class="cw-cell cw-num"><input type="text" class="answer-box" data-correct="'.$val.'" autocomplete="off"></div>';
                        } else {
                            // Fix szám
                            $cw_html .= '<div class="cw-cell cw-num">'.$val.'</div>';
                        }
                    } else {
                        // Operátor
                        $display_op = str_replace(['*','/'], ['&middot;', ':'], $val);
                        $cw_html .= '<div class="cw-cell cw-op">'.$display_op.'</div>';
                    }
                }
            }
        }
        
        $cw_html .= '</div></div>';
        $oldal_feladatai[] = $cw_html;
    }

    foreach ($oldal_feladatai as $cw_html) {
        $html .= '<div class="' . $col_class . '">';
        $html .= $cw_html;
        $html .= '</div>';
    }

    $html .= '</div>';
    $feladat_oldalak[] = $html;
}