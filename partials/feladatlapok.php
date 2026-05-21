<?php
/*
 * Fájl: partials/feladatlapok.php
 * Funkció: Generált feladatoldalak megjelenítése a felhasználói felületen.
 * Utolsó módosítás: 2026. május 07. 18:36:00
 */
?>
<?php if (empty($feladat_oldalak)): ?>
    <div class="alert alert-info text-center no-print mt-3 shadow-sm border-0">Kattints a <strong>Generálás</strong> gombra a küldetés megkezdéséhez!</div>
<?php else: ?>
    <?php foreach ($feladat_oldalak as $oldal_index => $oldal_html): ?>
        <div class="worksheet page-break <?php echo ($oldal_index > 0) ? 'mt-5 mt-print-0' : ''; ?>">
            <h3 class="text-center mb-4 pt-2 fw-bold text-secondary">
                <?php echo htmlspecialchars($oldal_cim); ?>
                <?php if ($oldalak_szama > 1): ?>
                    <small class="text-muted d-block fs-6 mt-1">(<?php echo $oldal_index + 1; ?>. oldal)</small>
                <?php endif; ?>
            </h3>
            
            <!-- A konkrét feladat HTML kódjának kiírása -->
            <?php echo $oldal_html; ?>
            
        </div>
    <?php endforeach; ?>
<?php endif; ?>