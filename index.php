<?php
/*
 * Fájl: index.php
 * Funkció: Főoldal (átjáró), keretrendszer, amely betölti a logikát, a CDN-eket és a részfájlokat.
 * Utolsó módosítás: 2026. május 07. 19:25:00
 */
require_once 'partials/init.php';
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($oldal_cim); ?> - Generátor</title>
    
    <!-- Téma betöltése villanás (FOUC) nélkül -->
    <script>
        const savedTheme = localStorage.getItem('app-theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>

    <!-- CDN Betöltések -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Stílusok (Moduláris) -->
    <?php include 'partials/styles.php'; ?>
</head>
<body class="<?php echo ($aktualis_feladat === 'dashboard') ? 'dashboard-mode' : ''; ?>">

<div class="container mt-4">
    
    <!-- ÁLTALÁNOS FEJLÉC -->
    <div class="card shadow mb-4 no-print border-0 <?php echo ($aktualis_feladat === 'dashboard') ? 'bg-transparent shadow-none' : ''; ?>">
        <?php include 'partials/navbar.php'; ?>
        <?php if ($aktualis_feladat !== 'dashboard'): ?>
            <?php include 'partials/beallitasok_form.php'; ?>
        <?php endif; ?>
    </div>

    <!-- TARTALOM (Dashboard vagy Feladatlapok) -->
    <?php if ($aktualis_feladat === 'dashboard'): ?>
        <?php include 'partials/dashboard.php'; ?>
    <?php else: ?>
        <!-- EREDMÉNYJELZŐK -->
        <?php include 'partials/eredmenyek.php'; ?>

        <!-- FELADATLAPOK MEGJELENÍTÉSE -->
        <?php include 'partials/feladatlapok.php'; ?>
    <?php endif; ?>

</div>

<!-- JS CDN Betöltések -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Kliens oldali szkriptek (Ellenőrzés, Nyomtatás, Téma váltó) -->
<?php include 'partials/scripts.php'; ?>
</body>
</html>