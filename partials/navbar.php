<?php
/*
 * Fájl: partials/navbar.php
 * Funkció: Felső navigációs sáv, visszalépés és Téma váltó gomb.
 * Utolsó módosítás: 2026. május 07. 19:25:00
 */
?>
<div class="card-header bg-primary text-white py-3 <?php echo ($aktualis_feladat === 'dashboard') ? 'rounded shadow' : ''; ?>">
    <div class="row align-items-center">
        <div class="col-md-6 d-flex align-items-center">
            <h4 class="mb-0 fw-bold"><i class="fas fa-calculator me-2"></i>Matek Generátor</h4>
        </div>
        <div class="col-md-6 text-md-end mt-2 mt-md-0 d-flex justify-content-md-end align-items-center gap-3">
            
            <?php if ($aktualis_feladat !== 'dashboard'): ?>
                <span class="fs-5 fw-bold d-none d-lg-inline"><?php echo htmlspecialchars($oldal_cim); ?></span>
                <a href="?tipus=dashboard" class="btn btn-light fw-bold text-primary shadow-sm">
                    <i class="fas fa-gamepad me-2"></i>Küldetések
                </a>
            <?php else: ?>
                <span class="fs-5 fw-bold text-light ms-auto"><i class="fas fa-rocket me-2"></i>Küldetésválasztó</span>
            <?php endif; ?>

            <!-- Sötét/Világos téma váltó -->
            <button id="theme-toggle-btn" class="btn btn-outline-light rounded-circle shadow-sm" style="width: 40px; height: 40px;" title="Téma váltása">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    </div>
</div>