<?php
/*
 * Fájl: partials/beallitasok_form.php
 * Funkció: A beállításokat kezelő űrlap, gombok és nyomtatás vezérlő (Csak feladatoknál jelenik meg).
 * Utolsó módosítás: 2026. május 07. 19:55:00
 */
?>
<div class="card-body bg-body-tertiary p-4">
    <form action="?tipus=<?php echo htmlspecialchars($aktualis_feladat); ?>" method="POST">
        <div class="row g-4 align-items-end">
            
            <!-- 1. Közös: Számkör -->
            <div class="col-md-6 col-lg-2">
                <label for="szamkor" class="form-label fw-bold text-body-secondary">Számkör</label>
                <div class="input-group">
                    <span class="input-group-text bg-body-secondary border-secondary-subtle"><i class="fas fa-hashtag text-body-secondary"></i></span>
                    <input type="number" class="form-control border-secondary-subtle" id="szamkor" name="szamkor" 
                           value="<?php echo htmlspecialchars($szamkor_hatar); ?>" min="10">
                </div>
            </div>

            <!-- 2. Közös: Oldalak száma -->
            <div class="col-md-6 col-lg-2">
                <label for="oldalak" class="form-label fw-bold text-body-secondary">Oldalak</label>
                <input type="number" class="form-control border-secondary-subtle" id="oldalak" name="oldalak" 
                       value="<?php echo htmlspecialchars($oldalak_szama); ?>" min="1" max="20">
            </div>

            <!-- 3. EGYEDI BEÁLLÍTÁSOK (A feladat fájlból jön) -->
            <?php 
                // Ha van egyedi beállítás HTML, akkor a benne lévő bg-white/bg-light osztályokat is érdemes lecserélni, 
                // de ezt a feladatlap modulok (pl. labirintus.php) generálják.
                if (isset($egyedi_beallitasok_html)) {
                    // Kicseréljük a beégetett fehér/világos háttereket a modulok által küldött HTML-ben is
                    $javitott_html = str_replace(
                        ['bg-white', 'bg-light', 'text-secondary'], 
                        ['bg-body', 'bg-body-tertiary', 'text-body-secondary'], 
                        $egyedi_beallitasok_html
                    );
                    echo $javitott_html;
                }
            ?>

            <!-- 4. Közös: Mód választók (Kondicionális megjelenítés) -->
            <div class="col-md-12 col-lg-4">
                <div class="row g-2">
                    <!-- Nehézség kapcsoló -->
                    <div class="col-6">
                        <label class="form-label fw-bold text-body-secondary">Nehézségi szint</label>
                        <input type="checkbox" class="btn-check" id="nehezebb" name="nehezebb" autocomplete="off" 
                               <?php echo $nehezebb ? 'checked' : ''; ?>
                               <?php echo $tamogatja_nehezebb ? '' : 'disabled'; ?>>
                        <label class="btn btn-outline-warning w-100 fw-bold <?php echo $tamogatja_nehezebb ? '' : 'disabled border-secondary text-body-secondary opacity-25'; ?>" for="nehezebb">
                            <i class="fas fa-question-circle me-1"></i> Nehezített
                        </label>
                    </div>
                    <!-- Szuper könnyű kapcsoló -->
                    <div class="col-6">
                        <label class="form-label fw-bold text-body-secondary">Könnyítés</label>
                        <input type="checkbox" class="btn-check" id="szuper_konnyu" name="szuper_konnyu" autocomplete="off" 
                               <?php echo $szuper_konnyu ? 'checked' : ''; ?>
                               <?php echo $tamogatja_szuper_konnyu ? '' : 'disabled'; ?>>
                        <label class="btn btn-outline-success w-100 fw-bold <?php echo $tamogatja_szuper_konnyu ? '' : 'disabled border-secondary text-body-secondary opacity-25'; ?>" for="szuper_konnyu">
                            <i class="fas fa-feather me-1"></i> Szuper könnyű
                        </label>
                    </div>
                </div>
            </div>

            <!-- 5. Generálás gomb -->
            <div class="col-md-12 col-lg-2 text-end ms-auto">
                <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">
                    <i class="fas fa-sync-alt me-2"></i>Generálás
                </button>
            </div>
        </div>

        <hr class="my-4 border-secondary-subtle">
        
        <!-- Alsó sáv: Ellenőrzés és Nyomtatás -->
        <div class="row justify-content-between align-items-center">
            <div class="col-auto d-flex flex-wrap gap-2">
                <button type="button" id="check-button" class="btn btn-outline-info fw-bold">
                    <i class="fas fa-check-double me-2"></i>Ellenőrzés
                </button>
                <button type="button" id="reveal-button" class="btn btn-outline-secondary fw-bold">
                    <i class="fas fa-eye me-2"></i>Megoldások
                </button>
            </div>
            <div class="col-auto mt-2 mt-md-0">
                <div class="d-flex align-items-center bg-body p-2 rounded border border-secondary-subtle shadow-sm">
                    <select class="form-select form-select-sm me-2 border-0 bg-body-tertiary text-body" id="print-style" style="width: auto;">
                        <option value="tiszta" selected>Tiszta</option>
                        <option value="kiemelt">Kiemelt</option>
                    </select>
                    <button type="button" id="print-button" class="btn btn-success btn-sm px-3">
                        <i class="fas fa-print me-1"></i> Nyomtatás
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>