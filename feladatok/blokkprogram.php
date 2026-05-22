<?php
/*
 * Fájl: feladatok/blokkprogram.php
 * Funkció: Blokkprogramozás labirintusban - kódolás tanítása Code.org stílusú parancsblokkokkal.
 * Módosítás: Hatástalanítás blokk és beágyazható Lépj előre (forward) konténer.
 * Utolsó módosítás: 2026. május 21. 20:50:00
 */

$meret = isset($_POST['meret']) ? max(4, min(8, (int)$_POST['meret'])) : 5;

$egyedi_beallitasok_html = '
<div class="col-md-6 col-lg-2">
    <label for="meret" class="form-label fw-bold text-secondary">Labirintus mérete</label>
    <select class="form-select border-secondary-subtle" id="meret" name="meret">
        <option value="4"' . ($meret == 4 ? ' selected' : '') . '>4×4</option>
        <option value="5"' . ($meret == 5 ? ' selected' : '') . '>5×5</option>
        <option value="6"' . ($meret == 6 ? ' selected' : '') . '>6×6</option>
        <option value="7"' . ($meret == 7 ? ' selected' : '') . '>7×7</option>
        <option value="8"' . ($meret == 8 ? ' selected' : '') . '>8×8</option>
    </select>
</div>
';

$feladatok_per_oldal = 1;

// --- LABIRINTUS GENERÁLÁS ---
$w = $meret;
$h = $meret;
$grid = array_fill(0, $h, array_fill(0, $w, 1));

function generate_path($w, $h) {
    $path = [[0, 0]];
    $x = 0; $y = 0;
    while ($x < $w - 1 || $y < $h - 1) {
        if ($x >= $w - 1) { $y++; }
        elseif ($y >= $h - 1) { $x++; }
        else { if (random_int(0, 1) === 0) $x++; else $y++; }
        $path[] = [$x, $y];
    }
    return $path;
}

$path = generate_path($w, $h);
$path_keys = [];
foreach ($path as $p) {
    $grid[$p[1]][$p[0]] = 0;
    $path_keys[$p[1] * $w + $p[0]] = true;
}

// Terelők / Zsákutcák
for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        if ($grid[$y][$x] === 1 && random_int(0, 3) === 0) {
            $adjacent = 0;
            foreach ([[0,1],[0,-1],[1,0],[-1,0]] as $d) {
                $nx = $x + $d[0]; $ny = $y + $d[1];
                if ($nx >= 0 && $nx < $w && $ny >= 0 && $ny < $h && $grid[$ny][$nx] === 0) $adjacent++;
            }
            if ($adjacent === 1) {
                $grid[$y][$x] = 0;
            }
        }
    }
}

// Csapdák (Nehezített mód)
$traps = [];
if ($nehezebb) {
    $available = max(0, count($path) - 2);
    $trap_count = max(0, min(4, (int)floor(count($path) / 3), $available));
    if ($trap_count > 0) {
        $path_slice = array_slice($path, 1, $available);
        $raw_idx = (array) array_rand($path_slice, min($trap_count, count($path_slice)));
        foreach ($raw_idx as $ti) {
            $a = random_int(10, 50);
            $b = random_int(5, 40);
            $op = random_int(0, 1) === 0 ? '+' : '-';
            if ($op === '-' && $a < $b) { $tmp = $a; $a = $b; $b = $tmp; }
            $result = $op === '+' ? $a + $b : $a - $b;
            
            // 3 feleletválasztós opció generálása
            $options = [$result];
            while (count($options) < 3) {
                $fake = $result + random_int(-15, 15);
                if ($fake > 0 && !in_array($fake, $options)) {
                    $options[] = $fake;
                }
            }
            shuffle($options);

            // Bővített időkeret a számoláshoz
            $trap_time = $szuper_konnyu ? 20 : ($nehezebb ? 15 : 10);

            $traps[] = [
                'x' => $path[$ti + 1][0],
                'y' => $path[$ti + 1][1],
                'question' => $a . ' ' . $op . ' ' . $b . ' = ?',
                'answer' => $result,
                'options' => $options,
                'time' => $trap_time
            ];
        }
    }
}
$traps_json = json_encode($traps, JSON_UNESCAPED_UNICODE);

$maze_json = json_encode([
    'grid' => $grid,
    'w' => $w, 'h' => $h,
    'start' => [0, 0],
    'goal' => [$w - 1, $h - 1]
], JSON_UNESCAPED_UNICODE);

// --- HTML KIADÁS ---
for ($p = 0; $p < $oldalak_szama; $p++) {

$html = '';

// Traps grid
$trap_grid = [];
foreach ($traps as $t) { $trap_grid[$t['y'] * $w + $t['x']] = true; }

$html .= '<div class="bp-wrap no-print">';

// --- BAL: Labirintus ---
$html .= '<div class="bp-maze-container">';
$html .= '<div class="bp-maze-grid" id="bpMazeGrid" style="grid-template-columns:repeat(' . $w . ', 55px)">';
foreach ($grid as $y => $row) {
    foreach ($row as $x => $cell) {
        $cls = $cell === 1 ? 'wall' : 'path';
        $key = $y * $w + $x;
        if ($x === 0 && $y === 0) $cls .= ' start';
        if ($x === $w - 1 && $y === $h - 1) $cls .= ' goal';
        if (isset($trap_grid[$key])) $cls .= ' trap';
        
        $html .= '<div class="bp-cell ' . $cls . '" data-x="' . $x . '" data-y="' . $y . '">';
        if ($x === 0 && $y === 0) $html .= 'START';
        elseif ($x === $w - 1 && $y === $h - 1) $html .= '🏁';
        elseif (isset($trap_grid[$key])) $html .= '⚡';
        $html .= '</div>';
    }
}
// Rakéta irányjelző: kezdéskor jobbra néz
$html .= '<div class="bp-robot facing-right" id="bpRobot" style="left:7px;top:7px;">🚀</div>';
$html .= '</div></div>';

// --- JOBB: Editor ---
$html .= '<div class="bp-editor">';

// Eszköztár (Toolbox)
$html .= '<div class="bp-toolbox" id="bpPalette">';
$html .= '<strong class="text-secondary mb-2 text-uppercase fs-6">Eszközök</strong>';

// A Code.org stílusú fix blokkpaletta játékos ikonokkal
$blocks = [];

// A Lépj előre mostantól egy konténer blokk!
$blocks[] = ['type' => 'forward', 'label' => 'Lépj előre 👣', 'cls' => 'forward-action'];
$blocks[] = ['type' => 'turn_left', 'label' => 'Fordulj balra ↩️', 'cls' => 'action'];
$blocks[] = ['type' => 'turn_right', 'label' => 'Fordulj jobbra ↪️', 'cls' => 'action'];

if ($nehezebb) {
    $blocks[] = ['type' => 'solve_trap', 'label' => 'Hatástalanítás ⚡', 'cls' => 'action trap-action'];
}

$blocks[] = ['type' => 'repeat_n', 'label' => 'Ismételd X-szer 🔁', 'cls' => 'loop'];
$blocks[] = ['type' => 'repeat_until', 'label' => 'Ismételd amíg 🏁', 'cls' => 'loop'];

foreach ($blocks as $b) {
    if ($b['type'] === 'repeat_n') {
        $html .= '
        <div class="bp-block-item loop" draggable="true" data-type="repeat_n">
            <div class="loop-header">' . $b['label'] . ' <input type="number" class="repeat-num" value="3" min="1" max="10"></div>
        </div>';
    } elseif ($b['type'] === 'repeat_until') {
        $html .= '
        <div class="bp-block-item loop" draggable="true" data-type="repeat_until">
            <div class="loop-header">' . $b['label'] . '</div>
        </div>';
    } elseif ($b['type'] === 'forward') {
        $html .= '
        <div class="bp-block-item forward-action" draggable="true" data-type="forward">
            <div class="loop-header">' . $b['label'] . '</div>
        </div>';
    } else {
        // További vizuális osztályok
        $html .= '
        <div class="bp-block-item ' . $b['cls'] . '" draggable="true" data-type="' . $b['type'] . '">
            ' . $b['label'] . '
        </div>';
    }
}

$html .= '</div>';

// Munkaterület
$html .= '<div class="d-flex flex-column flex-grow-1">';
$html .= '<strong class="text-secondary mb-2 text-uppercase fs-6">Munkaterület <small class="text-muted text-lowercase">(ide húzd a blokkokat)</small></strong>';
$html .= '<div class="bp-workspace" id="bpWorkspace"></div>';
$html .= '</div>';

$html .= '</div>'; // bp-editor

// Vezérlők
$html .= '<div class="bp-controls mt-3 d-flex align-items-center bg-white p-3 rounded-3 shadow-sm border">';
if ($nehezebb) {
    $html .= '<div class="bp-hp me-auto" id="bpHp">';
    for ($i = 0; $i < 3; $i++) $html .= '<span class="hp-heart fs-3">❤️</span>';
    $html .= '</div>';
} else {
    $html .= '<div class="bp-hp me-auto"></div>'; // Spacer
}
$html .= '<span class="bp-status me-3 fs-5" id="bpStatus">Készen áll a kódolásra!</span>';
$html .= '<button class="btn btn-secondary fw-bold px-4 me-2 py-2" id="bpReset"><i class="fas fa-undo me-2"></i>Vissza</button>';
$html .= '<button class="btn btn-warning text-dark fw-bold px-4 py-2 fs-5 shadow-sm" id="bpRun" style="border: 2px solid #e0a800;"><i class="fas fa-play me-2"></i>Futtatás</button>';
$html .= '</div>';

$html .= '</div>'; // bp-wrap

// Adatok JS számára
$html .= '<script>
var BP_MAZE = ' . $maze_json . ';
var BP_TRAPS = ' . $traps_json . ';
var BP_HARD = ' . ($nehezebb ? 'true' : 'false') . ';
</script>';

// A HTML string és JS kód elválasztása Nowdoc formátummal a biztonságért és a tiszta szintaktikáért
$html .= <<<'JS'
<script>
document.addEventListener("DOMContentLoaded",function(){
    var grid = BP_MAZE.grid, w = BP_MAZE.w, h = BP_MAZE.h;
    var startX = 0, startY = 0, goalX = w-1, goalY = h-1;
    var curX = 0, curY = 0, facing = 1; // 0=Fel, 1=Jobb, 2=Le, 3=Bal
    var dirs = [[0,-1], [1,0], [0,1], [-1,0]]; // X, Y vektorok (képkoordináta)
    var dirClasses = ["facing-up", "facing-right", "facing-down", "facing-left"];
    var running = false, hp = 3, traps = JSON.parse(JSON.stringify(BP_TRAPS)); // Clone for reset

    var robot = document.getElementById("bpRobot");
    var ws = document.getElementById("bpWorkspace");
    var palette = document.getElementById("bpPalette");
    var runBtn = document.getElementById("bpRun");
    var resetBtn = document.getElementById("bpReset");
    var statusEl = document.getElementById("bpStatus");

    function updateRobot(anim) {
        var cellSize = 55, gap = 2, padding = 4, robotSize = 45;
        var offset = (cellSize - robotSize) / 2;
        var x = padding + curX * (cellSize + gap) + offset;
        var y = padding + curY * (cellSize + gap) + offset;
        robot.style.left = x + "px";
        robot.style.top = y + "px";
        
        // Előző irány osztályok törlése
        robot.classList.remove("facing-up", "facing-right", "facing-down", "facing-left", "shake-anim");
        robot.classList.add(dirClasses[facing]);
        
        if (anim) {
            robot.classList.add("running");
            setTimeout(() => robot.classList.remove("running"), 400);
        }
    }

    function resetRobot() {
        curX = startX; curY = startY; facing = 1; // Induláskor jobbra néz
        updateRobot(false);
        running = false;
        if(BP_HARD) { hp = 3; updateHP(); }
        statusEl.textContent = "Készen áll a kódolásra!";
        statusEl.className = "bp-status";
        robot.style.background = ""; // Clear any red/green backgrounds
        document.querySelectorAll(".bp-ws-block").forEach(b => b.classList.remove("active-block"));
        traps = JSON.parse(JSON.stringify(BP_TRAPS)); // Restore traps
        
        // Csapda ikonok visszaállítása a pályán
        document.querySelectorAll('.bp-cell.trap').forEach(cell => {
            cell.innerHTML = "⚡";
        });
    }

    function updateHP() {
        var c = document.getElementById("bpHp");
        if(!c) return;
        c.innerHTML = "";
        for(var i=0; i<3; i++) c.innerHTML += "<span" + (i>=hp ? " class=\"hp-empty\"" : "") + ">❤️</span>";
        if(hp <= 0) {
            statusEl.innerHTML = "💀 <strong>KÜLDETÉS SIKERTELEN!</strong> Elfogyott az életerőd!";
            statusEl.className = "bp-status text-danger border-danger bg-danger-subtle";
        }
    }

    function sleep(ms) { return new Promise(resolve => setTimeout(resolve, ms)); }

    // Feleletválasztós Csapda
    function showTrap(trap) {
        return new Promise(function(resolve) {
            var overlay = document.createElement("div");
            overlay.className = "bp-trap-overlay";
            
            var optionsHtml = "";
            trap.options.forEach(function(opt) {
                optionsHtml += `<button class="btn trap-opt-btn m-2 shadow-sm rounded-pill">${opt}</button>`;
            });

            overlay.innerHTML = `
            <div class="bp-trap-modal">
                <h5>Hekkelés folyamatban... ⚡</h5>
                <div class="timer" id="trapTimer">${trap.time}</div>
                <div class="question">${trap.question}</div>
                <div class="d-flex flex-wrap justify-content-center">
                    ${optionsHtml}
                </div>
            </div>`;
            document.body.appendChild(overlay);

            var timerEl = overlay.querySelector("#trapTimer");
            var timeLeft = trap.time;
            
            var timer = setInterval(function() {
                timeLeft--;
                timerEl.textContent = timeLeft;
                if(timeLeft <= 0) {
                    clearInterval(timer);
                    overlay.remove();
                    resolve(false);
                }
            }, 1000);

            overlay.querySelectorAll(".trap-opt-btn").forEach(function(btn) {
                btn.addEventListener("click", function() {
                    clearInterval(timer);
                    var val = parseInt(this.textContent);
                    overlay.remove();
                    resolve(val === trap.answer);
                });
            });
        });
    }

    updateRobot(false);

    // --- DRAG & DROP LOGIKA (Clone & Nesting) ---
    var draggedEl = null;
    var sourceType = "";

    function createWorkspaceBlock(type, originalEl) {
        var wb = document.createElement("div");
        wb.className = "bp-ws-block";
        wb.draggable = true;
        wb.dataset.type = type;
        
        var cloneInner = originalEl.cloneNode(true);
        // A class frissítése (action vagy loop vagy forward konténer)
        var isContainer = type.startsWith("repeat") || type === "forward";
        var baseClass = "";
        
        if (type.startsWith("repeat")) baseClass = "loop";
        else if (type === "forward") baseClass = "forward-action";
        else if (type === "solve_trap") baseClass = "action trap-action";
        else baseClass = "action";
        
        cloneInner.className = "bp-block-item " + baseClass;
        
        var input = cloneInner.querySelector("input");
        if(input) input.addEventListener("mousedown", e => e.stopPropagation());

        var delBtn = document.createElement("button");
        delBtn.className = "bp-del";
        delBtn.innerHTML = '<i class="fas fa-times"></i>';
        delBtn.title = "Törlés";
        
        if(isContainer) {
            cloneInner.querySelector(".loop-header").appendChild(delBtn);
            var body = document.createElement("div");
            body.className = "loop-body";
            cloneInner.appendChild(body);
        } else {
            cloneInner.appendChild(delBtn);
        }

        wb.appendChild(cloneInner);
        setupWSBlock(wb);
        return wb;
    }

    document.addEventListener("dragstart", function(e) {
        var block = e.target.closest(".bp-block-item");
        var wsBlock = e.target.closest(".bp-ws-block");
        
        if (wsBlock) {
            draggedEl = wsBlock;
            sourceType = "workspace";
            setTimeout(() => wsBlock.style.opacity = "0.5", 0);
        } else if (block) {
            draggedEl = block;
            sourceType = "toolbox";
        } else {
            return;
        }
        
        e.dataTransfer.effectAllowed = "copyMove";
        e.dataTransfer.setData("text/plain", "block");
    });

    document.addEventListener("dragend", function(e) {
        if(draggedEl) {
            draggedEl.style.opacity = "1";
        }
        document.querySelectorAll(".drag-over").forEach(el => el.classList.remove("drag-over"));
        draggedEl = null;
    });

    ws.addEventListener("dragover", function(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = sourceType === "toolbox" ? "copy" : "move";
        document.querySelectorAll(".drag-over").forEach(el => el.classList.remove("drag-over"));
        var targetContainer = e.target.closest(".loop-body") || ws;
        targetContainer.classList.add("drag-over");
    });

    ws.addEventListener("drop", function(e) {
        e.preventDefault();
        document.querySelectorAll(".drag-over").forEach(el => el.classList.remove("drag-over"));
        
        if (!draggedEl) return;

        var targetContainer = e.target.closest(".loop-body") || ws;
        if (sourceType === "workspace" && draggedEl.contains(targetContainer)) return;

        var targetSibling = e.target.closest(".bp-ws-block");
        var newBlock = (sourceType === "toolbox") ? createWorkspaceBlock(draggedEl.dataset.type, draggedEl) : draggedEl;
        newBlock.style.opacity = "1"; // Biztosítjuk, hogy a dropped elem látható legyen

        if (targetSibling && targetSibling.parentNode === targetContainer && targetSibling !== newBlock) {
            var rect = targetSibling.getBoundingClientRect();
            if (e.clientY < rect.top + rect.height / 2) {
                targetContainer.insertBefore(newBlock, targetSibling);
            } else {
                targetContainer.insertBefore(newBlock, targetSibling.nextSibling);
            }
        } else {
            targetContainer.appendChild(newBlock);
        }
    });

    function setupWSBlock(el) {
        el.querySelector(".bp-del").addEventListener("click", function(e) {
            e.stopPropagation();
            el.remove();
        });
    }

    // --- VÉGREHAJTÓ MOTOR (AST alapú) ---
    async function executeStep() {
        let nx = curX + dirs[facing][0];
        let ny = curY + dirs[facing][1];
        
        // Fal ellenőrzés
        if (nx < 0 || nx >= w || ny < 0 || ny >= h || grid[ny][nx] === 1) {
            statusEl.innerHTML = "💥 <strong>BUMM!</strong> Falba ütköztél!";
            statusEl.className = "bp-status text-danger border-danger bg-danger-subtle";
            robot.style.background = "#dc3545";
            robot.classList.add("shake-anim");
            await sleep(800);
            return false;
        }
        
        // Csapda ellenőrzés a LÉPÉSNÉL - HA MÉG AKTÍV, ROBBAN
        if (BP_HARD) {
            var tIdx = traps.findIndex(t => t.x === nx && t.y === ny);
            if (tIdx >= 0) {
                hp--;
                updateHP();
                statusEl.innerHTML = "💥 <strong>RÁLÉPTÉL EGY CSAPDÁRA!</strong> -1 ❤️";
                statusEl.className = "bp-status text-danger border-danger bg-danger-subtle";
                robot.style.background = "#dc3545";
                robot.classList.add("shake-anim");
                await sleep(1500);
                if (hp > 0) {
                    resetRobot(); // Vissza a startra ha van még élete
                }
                return false; 
            }
        }

        // Lépés sikeres
        curX = nx; curY = ny;
        updateRobot(true);
        return true;
    }

    async function solveTrap() {
        // A robot megvizsgálja a csapdát az ELŐTTE lévő mezőn!
        let tx = curX + dirs[facing][0];
        let ty = curY + dirs[facing][1];
        
        var tIdx = traps.findIndex(t => t.x === tx && t.y === ty);
        if (tIdx >= 0) {
            statusEl.innerHTML = "⚡ <strong>CSAPDA ÉSZLELVE!</strong> Hatástalanítás...";
            statusEl.className = "bp-status text-warning border-warning bg-warning-subtle";
            var correct = await showTrap(traps[tIdx]);
            if (correct) {
                statusEl.innerHTML = "✅ <strong>SIKER!</strong> Csapda hatástalanítva!";
                statusEl.className = "bp-status text-success border-success bg-success-subtle";
                traps.splice(tIdx, 1);
                var cell = document.querySelector(`.bp-cell[data-x="${tx}"][data-y="${ty}"]`);
                if(cell) {
                    cell.classList.remove('trap');
                    if (cell.innerHTML === "⚡") cell.innerHTML = ""; 
                }
            } else {
                hp--;
                updateHP();
                statusEl.innerHTML = "💔 <strong>HIBÁS KÓD!</strong> -1 ❤️";
                statusEl.className = "bp-status text-danger border-danger bg-danger-subtle";
                robot.style.background = "#dc3545";
                robot.classList.add("shake-anim");
                await sleep(1000);
                if (hp <= 0) {
                    return false;
                } else {
                    resetRobot();
                    return false;
                }
            }
        } else {
            statusEl.innerHTML = "❓ Nincs csapda előtted!";
            statusEl.className = "bp-status text-secondary border-secondary bg-light";
            await sleep(800);
        }
        return true;
    }

    async function runCommands(container) {
        let blocks = Array.from(container.children).filter(el => el.classList.contains("bp-ws-block") && !el.classList.contains("dragging"));
        
        for (let i = 0; i < blocks.length; i++) {
            if (!running) return false;
            
            let block = blocks[i];
            let type = block.dataset.type;
            
            // Highlight aktív blokk
            document.querySelectorAll(".bp-ws-block").forEach(b => b.classList.remove("active-block"));
            block.classList.add("active-block");
            
            await sleep(400);

            if (type === "forward") {
                // Először végrehajtjuk a bele ágyazott parancsokat (pl. hatástalanítás)
                let loopBody = block.querySelector(".loop-body");
                if (loopBody && loopBody.children.length > 0) {
                    let ok = await runCommands(loopBody);
                    if (!ok) return false;
                }
                
                // Utána lépünk előre
                let ok2 = await executeStep();
                if (!ok2) return false;
                
            } else if (type === "solve_trap") {
                let ok = await solveTrap();
                if (!ok) return false;

            } else if (type === "turn_left") {
                facing = (facing + 3) % 4; 
                updateRobot(true);
                await sleep(300);
                
            } else if (type === "turn_right") {
                facing = (facing + 1) % 4; 
                updateRobot(true);
                await sleep(300);
                
            } else if (type === "repeat_n") {
                let input = block.querySelector(".repeat-num");
                let times = input ? parseInt(input.value) : 3;
                if(isNaN(times) || times < 1) times = 1;
                
                let loopBody = block.querySelector(".loop-body");
                for (let k = 0; k < times; k++) {
                    if (!running) return false;
                    let ok = await runCommands(loopBody);
                    if (!ok) return false;
                }
                // Highlight loop block again after inner iterations
                document.querySelectorAll(".bp-ws-block").forEach(b => b.classList.remove("active-block"));
                block.classList.add("active-block");
                await sleep(200);

            } else if (type === "repeat_until") {
                let loopBody = block.querySelector(".loop-body");
                let maxSafety = 100; // Végtelen ciklus védelem
                while (!(curX === goalX && curY === goalY) && maxSafety-- > 0) {
                    if (!running) return false;
                    let ok = await runCommands(loopBody);
                    if (!ok) return false;
                }
            }
        }
        return true;
    }

    runBtn.addEventListener("click", async function() {
        if(running) return;
        if(hp <= 0 && BP_HARD) { 
            statusEl.innerHTML = "💀 <strong>Nincs életerő!</strong> Kattints a Visszaállításra!"; 
            return; 
        }
        
        resetRobot();
        if(ws.querySelectorAll(".bp-ws-block").length === 0) {
            statusEl.innerHTML = "⚠ <strong>Üres a munkaterület!</strong> Húzz be blokkokat!";
            statusEl.className = "bp-status text-warning border-warning bg-warning-subtle";
            return;
        }
        
        running = true;
        statusEl.innerHTML = "▶ <strong>Kód futtatása...</strong>";
        statusEl.className = "bp-status text-primary border-primary bg-primary-subtle";
        runBtn.disabled = true;

        let ok = await runCommands(ws);
        
        // Kiemelés törlése
        document.querySelectorAll(".bp-ws-block").forEach(b => b.classList.remove("active-block"));

        if (ok && curX === goalX && curY === goalY) {
            statusEl.innerHTML = "🎉 <strong>GRATULÁLOK!</strong> Elérted a CÉL-t!";
            statusEl.className = "bp-status text-success border-success bg-success-subtle";
        } else if (ok) {
            statusEl.innerHTML = "⏹ <strong>A kód lefutott</strong>, de nem értél célba.";
            statusEl.className = "bp-status text-warning border-warning bg-warning-subtle";
        }
        
        running = false;
        runBtn.disabled = false;
    });

    resetBtn.addEventListener("click", function() {
        running = false; // Megállítja a háttérben futó ciklusokat
        setTimeout(() => {
            resetRobot();
            runBtn.disabled = false;
        }, 100);
    });

});
</script>
JS;

$feladat_oldalak[] = $html;
}