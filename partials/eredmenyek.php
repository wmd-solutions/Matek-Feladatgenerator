<?php
/*
 * Fájl: partials/eredmenyek.php
 * Funkció: Siker és hiba üzenetek megjelenítése ellenőrzéskor.
 * Utolsó módosítás: 2026. május 07. 18:36:00
 */
?>
<div id="success-alert" class="alert alert-success mt-3 no-print shadow-sm border-0" style="display: none;">
    <h4><i class="fas fa-smile-beam me-2"></i>Gratulálok!</h4>
    Minden feladat helyes!
</div>
<div id="error-alert" class="alert alert-danger mt-3 no-print shadow-sm border-0" style="display: none;">
    <h4><i class="fas fa-exclamation-triangle me-2"></i>Hoppá!</h4>
    Összesen <strong><span id="error-count">X</span></strong> hibát találtam.
</div>