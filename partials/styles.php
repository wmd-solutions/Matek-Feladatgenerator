<?php
/*
 * Fájl: partials/styles.php
 * Funkció: Közös, Feladatlap és Dashboard CSS stílusok.
 * Módosítás: Rakéta irányának korrigálása (merőleges igazítás a 45 fokos emoji kompenzálásával).
 * Utolsó módosítás: 2026. május 22. 08:35:00
 */
?>
<style>
    /* --- GYÖKÉR VÁLTOZÓK (Témák a Dashboardhoz) --- */
    :root {
        --dash-bg: #f8f9fa;
        --dash-title: #212529;
        --dash-title-shadow: rgba(13, 202, 240, 0.4);
        --card-grad-1: #ffffff;
        --card-grad-2: #f1f3f5;
        --card-border: #dee2e6;
        --card-text: #212529;
        --maze-wall: #e9ecef;
    }

    [data-bs-theme="dark"] {
        --dash-bg: #0b0b13;
        --dash-title: #ffffff;
        --dash-title-shadow: rgba(13, 202, 240, 0.8);
        --card-grad-1: #161625;
        --card-grad-2: #1d1d30;
        --card-border: #2d2d4a;
        --card-text: #ffffff;
        --maze-wall: #2d2d4a;
    }

    /* --- FELADATLAP (WORKSHEET) ÁLTALÁNOS STÍLUSOK --- */
    .problem { font-size: 1.3rem; font-family: 'Arial', sans-serif; white-space: nowrap; }
    .problem-row { padding: 12px 0; margin-bottom: 10px; border-radius: 8px; page-break-inside: avoid; transition: background-color 0.3s ease; }
    
    .problem-row:nth-child(even) { background-color: #f8f9fa; }
    /* Finomított zebra csíkozás sötét módban */
    [data-bs-theme="dark"] .problem-row:nth-child(even) { background-color: rgba(255, 255, 255, 0.03); }
    
    .answer-box {
        width: 60px; border: none; border-bottom: 2px solid #212529;
        text-align: center; font-weight: bold; font-size: 1.3rem;
        padding: 2px; background-color: #fff; color: #212529; -moz-appearance: textfield;
        transition: all 0.3s ease;
    }
    
    [data-bs-theme="dark"] .answer-box { border-bottom-color: #adb5bd; }

    .answer-box::-webkit-outer-spin-button, .answer-box::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .answer-box.hiba { border-color: #dc3545 !important; background-color: #f8d7da !important; color: #842029 !important;}
    .answer-box.megoldas { color: #198754 !important; background-color: #e8f5e9 !important; border-bottom-color: #198754 !important; }

    /* --- KERESZTREJTVÉNY STÍLUSOK --- */
    .cw-container { display: flex; justify-content: center; margin-bottom: 2rem; overflow-x: auto; padding: 10px; }
    .cw-grid { display: grid; gap: 4px; }
    .cw-cell { 
        display: flex; justify-content: center; align-items: center; 
        font-size: 1.2rem; font-weight: bold; 
    }
    .cw-num { 
        border: 2px solid #555; background: #fff; border-radius: 6px; 
        box-shadow: 2px 2px 4px rgba(0,0,0,0.05); color: #212529;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    .cw-op { color: #555; font-size: 1.4rem; }
    [data-bs-theme="dark"] .cw-op { color: #adb5bd; }
    .cw-empty { background: transparent; }
    
    .cw-num .answer-box {
        width: 100%; height: 100%; border: none !important; border-radius: 4px;
        background: transparent; padding: 0; margin: 0; box-shadow: none; font-size: 1.2rem;
        color: inherit;
    }
    .cw-num .answer-box:focus { outline: none; background: #e9ecef; }
    .cw-num .answer-box.megoldas { background-color: #d1e7dd; color: #0f5132 !important; }
    .cw-num .answer-box.hiba { background-color: #f8d7da; color: #842029 !important; }

    /* --- ÓRA (CLOCK) STÍLUSOK --- */
    .clock-face { fill: #ffffff; stroke: #dee2e6; stroke-width: 2; }
    .clock-face-inner { fill: none; stroke: #dee2e6; stroke-width: 1; }
    .clock-number { font-size: 14px; font-weight: bold; fill: #212529; font-family: Arial, sans-serif; }
    .clock-tick { stroke: #212529; stroke-width: 2; stroke-linecap: round; }
    .clock-tick-min { stroke: #adb5bd; stroke-width: 1; stroke-linecap: round; }
    .clock-hand-hour { stroke: #212529; stroke-width: 4; stroke-linecap: round; }
    .clock-hand-minute { stroke: #212529; stroke-width: 2.5; stroke-linecap: round; }
    .clock-center { fill: #212529; }
    .clock-center-dot { fill: #ffffff; }

    [data-bs-theme="dark"] .clock-face { fill: #2d2d3d; stroke: #495057; }
    [data-bs-theme="dark"] .clock-face-inner { stroke: #495057; }
    [data-bs-theme="dark"] .clock-number { fill: #e9ecef; }
    [data-bs-theme="dark"] .clock-tick { stroke: #e9ecef; }
    [data-bs-theme="dark"] .clock-tick-min { stroke: #6c757d; }
    [data-bs-theme="dark"] .clock-hand-hour { stroke: #e9ecef; }
    [data-bs-theme="dark"] .clock-hand-minute { stroke: #e9ecef; }
    [data-bs-theme="dark"] .clock-center { fill: #e9ecef; }
    [data-bs-theme="dark"] .clock-center-dot { fill: #2d2d3d; }

    [data-bs-theme="dark"] .digital-display {
        background: #0a0a1a !important; color: #00ff88 !important; border-color: #444 !important;
    }

    /* --- LABIRINTUS STÍLUSOK --- */
    [data-bs-theme="dark"] .maze-cell.user-selected {
        background-color: rgba(13, 110, 253, 0.15) !important; border-color: #0d6efd !important; box-shadow: 0 0 5px rgba(13, 110, 253, 0.3) !important;
    }
    [data-bs-theme="dark"] .maze-cell.user-selected .maze-entry {
        background-color: rgba(13, 110, 253, 0.25) !important; color: #9ec5fe !important; border-bottom-color: #0d6efd !important;
    }
    [data-bs-theme="dark"] .maze-cell.user-selected .maze-entry.bg-dark { background-color: #0d6efd !important; color: #fff !important; }
    [data-bs-theme="dark"] .maze-cell.user-selected .maze-math { color: #9ec5fe !important; }

    /* --- DASHBOARD (VIDEOJÁTÉK) STÍLUSOK --- */
    body.dashboard-mode { background-color: var(--dash-bg); color: var(--card-text); transition: background-color 0.3s ease; }
    .dashboard-title { color: var(--dash-title) !important; text-shadow: 0 0 15px var(--dash-title-shadow) !important; transition: color 0.3s ease; }
    .dashboard-container { padding: 1rem 0 3rem 0; }
    
    .game-card {
        background: linear-gradient(145deg, var(--card-grad-1), var(--card-grad-2));
        border: 2px solid var(--card-border); border-radius: 16px; overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); display: flex; flex-direction: column; position: relative;
        box-shadow: 0 8px 15px rgba(0,0,0,0.1); text-decoration: none;
    }
    [data-bs-theme="dark"] .game-card { box-shadow: 0 8px 15px rgba(0,0,0,0.5); }
    
    .game-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, #0dcaf0, #0d6efd); opacity: 0; transition: opacity 0.3s ease;
    }
    .game-card:hover { transform: translateY(-10px) scale(1.03); border-color: #0dcaf0; box-shadow: 0 20px 40px rgba(13, 202, 240, 0.2); }
    .game-card:hover::before { opacity: 1; }
    
    .game-card-header { padding: 1rem 1.2rem; background: rgba(128,128,128,0.1); border-bottom: 1px solid var(--card-border); color: var(--card-text); }
    .game-card-body { padding: 2rem 1rem; flex-grow: 1; display: flex; align-items: center; justify-content: center; background: transparent; }
    .game-card-footer { padding: 0.8rem 1.2rem; background: rgba(128,128,128,0.15); border-top: 1px solid var(--card-border); }
    
    .play-btn { color: #0dcaf0; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s ease; }
    .game-card:hover .play-btn { color: var(--card-text); text-shadow: 0 0 8px #0dcaf0; }
    
    .mini-preview { font-family: 'Courier New', Courier, monospace; font-size: 1.5rem; font-weight: bold; color: var(--card-text); text-align: center; }
    [data-bs-theme="dark"] .mini-preview { text-shadow: 0 2px 4px rgba(0,0,0,0.8); }

    .mini-box {
        display: inline-block; width: 35px; height: 35px; border: 2px dashed #0dcaf0;
        border-radius: 6px; vertical-align: middle; background: rgba(13, 202, 240, 0.05);
        margin: 0 4px; box-shadow: inset 0 0 10px rgba(13, 202, 240, 0.1); transition: all 0.3s ease;
    }
    .game-card:hover .mini-box { border-style: solid; background: rgba(13, 202, 240, 0.2); box-shadow: 0 0 10px rgba(13, 202, 240, 0.4); }
    
    .mini-maze { display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px; width: 70px; height: 70px; margin: 0 auto; transition: transform 0.5s ease; }
    .mini-maze div { background: var(--maze-wall); border-radius: 4px; transition: all 0.5s ease; }
    .game-card:hover .mini-maze .path { background: #198754; box-shadow: 0 0 8px #198754; }
    .game-card:hover .mini-maze .start { background: #0dcaf0; box-shadow: 0 0 8px #0dcaf0; }
    .game-card:hover .mini-maze .target { background: #ffc107; box-shadow: 0 0 8px #ffc107; }
    
    @keyframes pulse-glow { 0% { opacity: 0.85; } 50% { opacity: 1; box-shadow: 0 0 6px #0dcaf0; } 100% { opacity: 0.85; } }
    .game-card:hover .blink { animation: pulse-glow 1.5s infinite; }

    /* --- BLOKKPROGRAM STÍLUSOK (CODE.ORG STÍLUS) --- */
    .bp-wrap { display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap; margin-bottom: 2rem; }
    .bp-maze-container { flex: 0 0 auto; background: #fff; padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border: 2px solid var(--card-border); }
    .bp-maze-grid { display: grid; gap: 2px; background: #dee2e6; padding: 4px; border-radius: 8px; position: relative; }
    .bp-cell { width: 55px; height: 55px; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: bold; position: relative; transition: background 0.3s; }
    
    /* Vizuálisan füves/bokros falak */
    .bp-cell.wall { background: #8bc34a; background-image: radial-gradient(#4caf50 15%, transparent 16%), radial-gradient(#4caf50 15%, transparent 16%); background-size: 10px 10px; background-position: 0 0, 5px 5px; border: 1px solid #689f38; }
    .bp-cell.path { background: #e0e0e0; border: 1px solid #bdbdbd; }
    .bp-cell.start { background: #ffeb3b; color: #333; border: 2px solid #fbc02d; font-size: 0.7rem; }
    .bp-cell.goal { background: #f44336; color: #fff; border: 2px solid #d32f2f; font-size: 1.5rem; }
    .bp-cell.trap { background: #9c27b0; color: #fff; animation: pulse-trap 1.5s infinite; border: 2px solid #7b1fa2; font-size: 1.2rem; }
    
    @keyframes pulse-trap { 50% { opacity: 0.7; transform: scale(0.95); } }
    
    .bp-robot {
        position: absolute; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 2.2rem; z-index: 10; transition: transform 0.3s ease, left 0.4s cubic-bezier(.4,0,.2,1), top 0.4s cubic-bezier(.4,0,.2,1); text-shadow: 2px 2px 4px rgba(0,0,0,0.4);
    }
    
    /* RAKÉTA TENGELY IRÁNYOK JAVÍTÁSA */
    /* A 🚀 emoji alapból 45 fokos szögben dől (felfelé és jobbra). Ezt a dőlést vonjuk ki a kívánt forgatásból. */
    .bp-robot.facing-up { transform: rotate(-45deg) !important; }
    .bp-robot.facing-right { transform: rotate(45deg) !important; }
    .bp-robot.facing-down { transform: rotate(135deg) !important; }
    .bp-robot.facing-left { transform: rotate(225deg) !important; }
    
    .shake-anim { animation: roboshake 0.4s ease-in-out; }
    @keyframes roboshake {
        0% { translate: 1px 1px; }
        10% { translate: -1px -2px; }
        20% { translate: -3px 0px; }
        30% { translate: 3px 2px; }
        40% { translate: 1px -1px; }
        50% { translate: -1px 2px; }
        60% { translate: -3px 1px; }
        70% { translate: 3px 1px; }
        80% { translate: -1px -1px; }
        90% { translate: 1px 2px; }
        100% { translate: 1px -2px; }
    }
    
    .bp-editor { flex: 1; min-width: 320px; display: flex; gap: 15px; }
    .bp-toolbox { flex: 0 0 170px; background: #f8f9fa; border: 2px dashed #adb5bd; border-radius: 12px; padding: 10px; display: flex; flex-direction: column; gap: 8px; min-height: 300px; }
    
    .bp-workspace {
        flex: 1; background: #fff; border: 2px solid #dee2e6; border-radius: 12px; padding: 15px;
        min-height: 400px; position: relative; display: flex; flex-direction: column; gap: 2px; box-shadow: inset 0 3px 10px rgba(0,0,0,0.05);
    }
    .bp-workspace.drag-over { background: #f0f6ff; border-color: #0d6efd; }
    
    .bp-block-item {
        display: flex; align-items: center; gap: 6px; padding: 10px 14px; border-radius: 8px; font-size: 0.95rem; font-weight: bold; color: #fff;
        cursor: grab; user-select: none; box-shadow: 0 4px 0 rgba(0,0,0,0.2); border: 2px solid; width: fit-content; margin-bottom: 6px; position: relative;
        opacity: 1 !important; text-shadow: 1px 1px 2px rgba(0,0,0,0.4);
    }
    .bp-block-item:active { cursor: grabbing; box-shadow: 0 1px 0 rgba(0,0,0,0.2) !important; transform: translateY(3px); }
    
    .bp-block-item.action { background-color: #29b6f6 !important; border-color: #0288d1 !important; z-index: 2; }
    .bp-block-item.loop { background-color: #ec407a !important; border-color: #c2185b !important; flex-direction: column; align-items: stretch; z-index: 1; padding-bottom: 6px;}
    .bp-block-item.trap-action { background-color: #ff9800 !important; border-color: #e65100 !important; }
    
    /* Lépj előre konténer stílus */
    .bp-block-item.forward-action { background-color: #29b6f6 !important; border-color: #0288d1 !important; flex-direction: column; align-items: stretch; z-index: 1; padding-bottom: 6px; }
    .bp-block-item.forward-action .loop-body:empty::after { content: "Beágyazott (pl. hatástalanítás)"; font-size: 0.75rem; color: rgba(255,255,255,0.8); font-style: italic; font-weight: normal; }

    .loop-header { display: flex; align-items: center; gap: 6px; }
    .loop-body {
        min-height: 30px; margin-top: 5px; margin-left: 15px; border-left: 4px solid rgba(255,255,255,0.4); padding-left: 10px; padding-top: 5px; padding-bottom: 5px;
        background: rgba(0,0,0,0.1); border-radius: 0 0 0 4px; display: flex; flex-direction: column; gap: 2px;
    }
    .loop-body.drag-over { background: rgba(255,255,255,0.2); border-left-color: #fff; }
    .loop-body:empty::after { content: "Húzz ide blokkokat"; font-size: 0.75rem; color: rgba(255,255,255,0.8); font-style: italic; font-weight: normal; }

    .bp-ws-block { position: relative; animation: slideIn 0.2s ease-out; opacity: 1 !important; }
    .bp-ws-block .bp-block-item { width: 100%; margin-bottom: 0; box-shadow: inset 0 -3px rgba(0,0,0,0.2) !important; border-width: 1px !important; }
    
    @keyframes slideIn { from { opacity: 0; transform: translateX(-10px); } to { opacity: 1; transform: translateX(0); } }
    
    .bp-del { 
        margin-left: auto; background: rgba(0,0,0,0.2); border: none; color: white; border-radius: 50%; width: 22px; height: 22px; display: flex; justify-content: center; align-items: center; 
        cursor: pointer; font-size: 0.8rem; transition: background 0.2s;
    }
    .bp-del:hover { background: rgba(255,0,0,0.8); }

    .repeat-num { width: 40px; padding: 2px 4px; border-radius: 4px; border: 1px solid #ccc; text-align: center; font-weight: bold; color: #333; margin: 0 4px; outline: none; }
    .bp-ws-block.active-block > .bp-block-item { outline: 3px solid #ffeb3b; outline-offset: 1px; z-index: 10; box-shadow: 0 0 10px #ffeb3b !important; }

    .bp-trap-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 1050; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(3px); }
    .bp-trap-modal { background: #fffdf2 !important; border-radius: 16px; padding: 30px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.4); max-width: 450px; width: 90%; border: 5px solid #ff9800 !important; }
    .bp-trap-modal h5 { color: #dc3545; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
    .bp-trap-modal .timer { font-size: 3.5rem; font-weight: 900; color: #0d6efd; margin: 10px 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .bp-trap-modal .question { font-size: 2.5rem; font-weight: bold; margin-bottom: 25px; color: #212529; }
    .trap-opt-btn { background-color: #ffca28 !important; border-color: #ff8f00 !important; color: #333 !important; font-size: 1.5rem !important; font-weight: bold !important; border-width: 2px !important; transition: transform 0.1s; padding: 10px 20px; }
    .trap-opt-btn:hover { background-color: #ffb300 !important; color: #fff !important; }
    .trap-opt-btn:active { transform: scale(0.95); }

    .bp-controls { width: 100%; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; background: #fff; padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border: 2px solid var(--card-border); margin-top: 15px; }
    .bp-hp { display: flex; gap: 5px; font-size: 1.5rem; filter: drop-shadow(0 2px 2px rgba(0,0,0,0.2)); margin-right: auto; }
    .bp-hp .hp-empty { filter: grayscale(100%) opacity(0.3); }
    .bp-status { font-size: 1rem; font-weight: bold; padding: 6px 15px; border-radius: 8px; background: #f8f9fa; border: 1px solid #dee2e6; }

    [data-bs-theme="dark"] .bp-maze-container, [data-bs-theme="dark"] .bp-workspace, [data-bs-theme="dark"] .bp-controls { background: #161625; border-color: #2d2d4a; }
    [data-bs-theme="dark"] .bp-toolbox { background: #1d1d30; border-color: #495057; }
    [data-bs-theme="dark"] .bp-maze-grid { background: #495057; border-color: #6c757d; }
    [data-bs-theme="dark"] .bp-cell.wall { background: #2e7d32; border-color: #1b5e20; }
    [data-bs-theme="dark"] .bp-cell.path { background: #424242; border-color: #212121; }
    [data-bs-theme="dark"] .bp-trap-modal { background: #2d2d3d !important; border-color: #dc3545 !important; }
    [data-bs-theme="dark"] .bp-trap-modal .question { color: #e9ecef; }

    /* --- NYOMTATÁSI NÉZET (KÖZÖS) --- */
    @media print {
        .no-print { display: none !important; }
        .container, .container-fluid { width: 100% !important; max-width: none !important; padding: 0; margin: 0; }
        .page-break { page-break-after: always; }
        .page-break:last-child { page-break-after: auto; }
        .worksheet { min-height: 95vh; }
        body.print-kiemelt .problem-row:nth-child(even) { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        body.print-kiemelt .answer-box.hiba { border-color: #dc3545 !important; background-color: #f8d7da !important; color: #842029 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        body.print-kiemelt .answer-box.megoldas { color: #198754 !important; background-color: #e8f5e9 !important; border-bottom-color: #198754 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        
        /* Keresztrejtvény nyomtatás */
        .cw-num { border: 1px solid #000 !important; box-shadow: none !important; }
        .cw-num .answer-box::-webkit-input-placeholder { color: transparent !important; opacity: 0 !important; }
        body.show-solutions .cw-num .answer-box.megoldas { background-color: #e8f5e9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        
        /* Óra nyomtatás */
        .clock-face { fill: #ffffff !important; stroke: #333 !important; }
        .clock-number { fill: #000 !important; }
        .clock-tick { stroke: #000 !important; }
        .clock-tick-min { stroke: #666 !important; }
        .clock-hand-hour { stroke: #000 !important; }
        .clock-hand-minute { stroke: #000 !important; }
        .clock-center { fill: #000 !important; }
        .clock-center-dot { fill: #fff !important; }
        body.print-kiemelt .clock-face { fill: #ffffff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        body.print-kiemelt .digital-display { background: #1a1a2e !important; color: #00ff88 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        
        @page { size: A4; margin: 2cm; }
    }
</style>