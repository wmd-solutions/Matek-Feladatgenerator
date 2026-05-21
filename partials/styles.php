<?php
/*
 * Fájl: partials/styles.php
 * Funkció: Közös, Feladatlap és Dashboard CSS stílusok.
 * Módosítás: Keresztrejtvény stílusok visszaállítása, túlzó sötét mód overrides eltávolítása a feladatlapokról.
 * Utolsó módosítás: 2026. május 07. 21:35:00
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

    /* --- KERESZTREJTVÉNY STÍLUSOK (VISSZAÁLLÍTVA) --- */
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

    @media print {
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
    }

    /* --- LABIRINTUS STÍLUSOK (Tompított/Javított sötét mód) --- */
    /* A cellák maradnak tiszták, hogy olvashatóak legyenek, csak az aktív kijelölés alkalmazkodik */
    [data-bs-theme="dark"] .maze-cell.user-selected {
        background-color: rgba(13, 110, 253, 0.15) !important;
        border-color: #0d6efd !important;
        box-shadow: 0 0 5px rgba(13, 110, 253, 0.3) !important;
    }
    [data-bs-theme="dark"] .maze-cell.user-selected .maze-entry {
        background-color: rgba(13, 110, 253, 0.25) !important;
        color: #9ec5fe !important;
        border-bottom-color: #0d6efd !important;
    }
    [data-bs-theme="dark"] .maze-cell.user-selected .maze-entry.bg-dark {
        background-color: #0d6efd !important;
        color: #fff !important;
    }
    [data-bs-theme="dark"] .maze-cell.user-selected .maze-math {
        color: #9ec5fe !important;
    }

    /* --- DASHBOARD (VIDEOJÁTÉK) STÍLUSOK --- */
    body.dashboard-mode { background-color: var(--dash-bg); color: var(--card-text); transition: background-color 0.3s ease; }
    .dashboard-title { color: var(--dash-title) !important; text-shadow: 0 0 15px var(--dash-title-shadow) !important; transition: color 0.3s ease; }
    .dashboard-container { padding: 1rem 0 3rem 0; }
    
    .game-card {
        background: linear-gradient(145deg, var(--card-grad-1), var(--card-grad-2));
        border: 2px solid var(--card-border); border-radius: 16px; overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex; flex-direction: column; position: relative;
        box-shadow: 0 8px 15px rgba(0,0,0,0.1); text-decoration: none;
    }
    [data-bs-theme="dark"] .game-card { box-shadow: 0 8px 15px rgba(0,0,0,0.5); }
    
    .game-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, #0dcaf0, #0d6efd); opacity: 0; transition: opacity 0.3s ease;
    }
    .game-card:hover {
        transform: translateY(-10px) scale(1.03); border-color: #0dcaf0;
        box-shadow: 0 20px 40px rgba(13, 202, 240, 0.2);
    }
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

    /* --- NYOMTATÁSI NÉZET --- */
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
        
        @page { size: A4; margin: 2cm; }
    }
</style>