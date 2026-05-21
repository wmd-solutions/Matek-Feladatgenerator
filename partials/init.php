<?php
/*
 * Fájl: partials/init.php
 * Funkció: Közös változók inicializálása, kérések feldolgozása, és a megfelelő feladat modul betöltése.
 * Utolsó módosítás: 2026. május 07. 20:45:00
 */

// PHP 8.3 környezet
header('Content-Type: text/html; charset=utf-8');

$mappa = 'feladatok/';
$aktualis_feladat = isset($_GET['tipus']) ? $_GET['tipus'] : 'dashboard';

// Engedélyezett feladatok listája (Küldetések a Dashboardhoz)
// MÓDOSÍTÁS: Matematikai Keresztrejtvény hozzáadása
$engedelyezett_feladatok = [
    'osszeadas'       => 'Több tényezős összeadás',
    'osztas'          => 'Maradékos osztás',
    'szorzas'         => 'Szorzás gyakorlása',
    'kerekites'       => 'Kerekítés gyakorlása',
    'szamszomszed'    => 'Számszomszédok',
    'mertekegyseg'    => 'Mértékegységek',
    'alapmuveletek'   => 'Alapvető műveletek',
    'labirintus'      => 'Labirintus (Követő)',
    'labirintus_b'    => 'Labirintus (Igaz/Hamis)',
    'labirintus_c'    => 'Labirintus (Akkumulátor)',
    'keresztrejtveny' => 'Matek Keresztrejtvény',
    'ora'             => 'Óra gyakorlás'
];

if ($aktualis_feladat !== 'dashboard' && !array_key_exists($aktualis_feladat, $engedelyezett_feladatok)) {
    $aktualis_feladat = 'dashboard';
}

$oldal_cim = ($aktualis_feladat === 'dashboard') ? 'Küldetések' : $engedelyezett_feladatok[$aktualis_feladat];

// --- FUNKCIÓK TÁMOGATÁSA (Konfiguráció) ---
$funkcio_tamogatas = [
    'osszeadas'       => ['nehezebb' => true,  'szuper_konnyu' => true],
    'osztas'          => ['nehezebb' => true,  'szuper_konnyu' => false],
    'szorzas'         => ['nehezebb' => true,  'szuper_konnyu' => false],
    'kerekites'       => ['nehezebb' => false, 'szuper_konnyu' => false],
    'szamszomszed'    => ['nehezebb' => false, 'szuper_konnyu' => false],
    'mertekegyseg'    => ['nehezebb' => true,  'szuper_konnyu' => true],
    'alapmuveletek'   => ['nehezebb' => true,  'szuper_konnyu' => true],
    'labirintus'      => ['nehezebb' => true,  'szuper_konnyu' => true],
    'labirintus_b'    => ['nehezebb' => true,  'szuper_konnyu' => true],
    'labirintus_c'    => ['nehezebb' => true,  'szuper_konnyu' => true],
    'keresztrejtveny' => ['nehezebb' => true,  'szuper_konnyu' => true],
    'ora'             => ['nehezebb' => true,  'szuper_konnyu' => true],
];

$tamogatja_nehezebb = false;
$tamogatja_szuper_konnyu = false;
$szamkor_hatar = 100;
$oldalak_szama = 1;
$nehezebb = false;
$szuper_konnyu = false;
$feladat_oldalak = []; 

// Ha nem dashboardon vagyunk, töltsük be a modult
if ($aktualis_feladat !== 'dashboard') {
    $tamogatja_nehezebb = $funkcio_tamogatas[$aktualis_feladat]['nehezebb'];
    $tamogatja_szuper_konnyu = $funkcio_tamogatas[$aktualis_feladat]['szuper_konnyu'];

    if (isset($_POST['szamkor']) && (int)$_POST['szamkor'] > 0) $szamkor_hatar = (int)$_POST['szamkor'];
    if (isset($_POST['oldalak']) && (int)$_POST['oldalak'] > 0) $oldalak_szama = (int)$_POST['oldalak'];

    $nehezebb = $tamogatja_nehezebb && isset($_POST['nehezebb']);
    $szuper_konnyu = $tamogatja_szuper_konnyu && isset($_POST['szuper_konnyu']);

    $fajl_utvonal = $mappa . $aktualis_feladat . '.php';

    if (file_exists($fajl_utvonal)) {
        include $fajl_utvonal;
    } else {
        echo "Hiba: A feladatfájl nem található: $fajl_utvonal";
        exit;
    }
}