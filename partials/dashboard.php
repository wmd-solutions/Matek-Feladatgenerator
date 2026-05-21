<?php
/*
 * Fájl: partials/dashboard.php
 * Funkció: Videojáték-stílusú küldetésválasztó kezdőképernyő előnézeti kártyákkal.
 * Utolsó módosítás: 2026. május 07. 20:45:00
 */

// Miniatűr HTML előnézet generátor a kártyákhoz
function render_mini_preview($type) {
    $preview = '';
    switch($type) {
        case 'osszeadas': 
            $preview = '<div class="mini-preview"><span class="text-info">12</span> + <span class="text-info">34</span> + <div class="mini-box"></div> = <span class="text-success">68</span></div>'; 
            break;
        case 'osztas': 
            $preview = '<div class="mini-preview"><span class="text-info">45</span> : <span class="text-info">7</span> = <div class="mini-box"></div> m. <div class="mini-box"></div></div>'; 
            break;
        case 'szorzas': 
            $preview = '<div class="mini-preview"><span class="text-info">8</span> &middot; <div class="mini-box"></div> = <span class="text-success">56</span></div>'; 
            break;
        case 'kerekites': 
            $preview = '<div class="mini-preview"><div class="mini-box" style="width:45px"></div> &approx; <span class="text-warning">73</span> &approx; <div class="mini-box" style="width:45px"></div></div>'; 
            break;
        case 'szamszomszed': 
            $preview = '<div class="mini-preview"><div class="mini-box" style="width:40px"></div> < <span class="text-warning">156</span> < <div class="mini-box" style="width:40px"></div></div>'; 
            break;
        case 'mertekegyseg': 
            $preview = '<div class="mini-preview"><span class="text-info">4m</span> + <span class="text-info">30cm</span> = <div class="mini-box" style="width:45px"></div> <span class="text-success">cm</span></div>'; 
            break;
        case 'alapmuveletek': 
            $preview = '<div class="mini-preview"><span class="text-info">5</span> &middot; ( <div class="mini-box"></div> - <span class="text-info">2</span> ) = <span class="text-success">15</span></div>'; 
            break;
        case 'keresztrejtveny':
            $preview = '
            <div class="mini-preview cw-preview" style="font-size:1.2rem;">
                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                    <div class="mini-box text-info d-flex align-items-center justify-content-center" style="width:25px;height:25px">8</div>
                    <div>&middot;</div>
                    <div class="mini-box text-info d-flex align-items-center justify-content-center" style="width:25px;height:25px">4</div>
                    <div>=</div>
                    <div class="mini-box text-success d-flex align-items-center justify-content-center" style="width:25px;height:25px">32</div>
                </div>
                <div class="d-flex align-items-center justify-content-center gap-1 mb-1" style="margin-right: 60px;">
                    <div style="width:25px;height:25px;visibility:hidden"></div>
                    <div style="width:10px"></div>
                    <div>+</div>
                </div>
                <div class="d-flex align-items-center justify-content-center gap-1" style="margin-right: 60px;">
                    <div style="width:25px;height:25px;visibility:hidden"></div>
                    <div style="width:10px"></div>
                    <div class="mini-box text-warning d-flex align-items-center justify-content-center" style="width:25px;height:25px">6</div>
                </div>
            </div>';
            break;
        case 'ora':
            $preview = '
            <div class="mini-preview" style="font-size:1rem;">
                <svg viewBox="0 0 80 80" width="70" height="70" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="40" cy="40" r="36" fill="var(--card-grad-1, #fff)" stroke="var(--card-border, #333)" stroke-width="1.5"/>
                    <text x="40" y="12" text-anchor="middle" font-size="7" fill="var(--card-text, #333)" font-weight="bold">12</text>
                    <text x="68" y="43" text-anchor="middle" font-size="7" fill="var(--card-text, #333)" font-weight="bold">3</text>
                    <text x="40" y="70" text-anchor="middle" font-size="7" fill="var(--card-text, #333)" font-weight="bold">6</text>
                    <text x="12" y="43" text-anchor="middle" font-size="7" fill="var(--card-text, #333)" font-weight="bold">9</text>
                    <line x1="40" y1="40" x2="54" y2="25" stroke="#333" stroke-width="3" stroke-linecap="round"/>
                    <line x1="40" y1="40" x2="52" y2="52" stroke="#333" stroke-width="1.5" stroke-linecap="round"/>
                    <circle cx="40" cy="40" r="2.5" fill="#333"/>
                </svg>
            </div>';
            break;
        case 'labirintus':
        case 'labirintus_b':
        case 'labirintus_c':
            $preview = '
            <div class="mini-maze mx-auto">
                <div class="start blink"></div><div class="path"></div><div class="wall"></div>
                <div class="wall"></div><div class="path"></div><div class="wall"></div>
                <div class="wall"></div><div class="path"></div><div class="target blink"></div>
            </div>';
            break;
    }
    return $preview;
}
?>

<div class="dashboard-container">
    <h2 class="text-center text-white mb-5 fw-bold text-uppercase" style="letter-spacing: 2px; text-shadow: 0 0 15px rgba(13,202,240,0.8);">
        <i class="fas fa-rocket text-info me-2"></i> Válassz Küldetést!
    </h2>
    <div class="row g-4 dashboard-grid">
        <?php foreach ($engedelyezett_feladatok as $kulcs => $nev): ?>
            <div class="col-md-6 col-lg-4">
                <a href="?tipus=<?php echo urlencode($kulcs); ?>" class="text-decoration-none">
                    <div class="game-card h-100">
                        <div class="game-card-header">
                            <h5 class="fw-bold mb-0"><i class="fas fa-gamepad me-2 text-info"></i><?php echo htmlspecialchars($nev); ?></h5>
                        </div>
                        <div class="game-card-body">
                            <?php echo render_mini_preview($kulcs); ?>
                        </div>
                        <div class="game-card-footer d-flex justify-content-between align-items-center">
                            <span class="badge bg-dark border border-secondary text-secondary">
                                <?php echo (strpos($kulcs, 'labirintus') !== false || $kulcs === 'keresztrejtveny') ? 'Kaland' : 'Matek'; ?>
                            </span>
                            <span class="play-btn">START <i class="fas fa-play ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>