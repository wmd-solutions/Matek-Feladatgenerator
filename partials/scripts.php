<?php
/*
 * Fájl: partials/scripts.php
 * Funkció: Kliens oldali JavaScript (ellenőrzés, megoldások, nyomtatás, és TÉMA KEZELÉS).
 * Utolsó módosítás: 2026. május 07. 19:25:00
 */
?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- TÉMA VÁLTÓ LOGIKA ---
        const themeBtn = document.getElementById('theme-toggle-btn');
        const rootElement = document.documentElement;

        function updateThemeIcon(theme) {
            if (!themeBtn) return;
            if (theme === 'dark') {
                themeBtn.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                themeBtn.innerHTML = '<i class="fas fa-moon"></i>';
            }
        }

        // Kezdeti ikon beállítása a localStorage alapján
        const currentTheme = rootElement.getAttribute('data-bs-theme');
        updateThemeIcon(currentTheme);

        if (themeBtn) {
            themeBtn.addEventListener('click', function() {
                const current = rootElement.getAttribute('data-bs-theme');
                const newTheme = current === 'dark' ? 'light' : 'dark';
                
                rootElement.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('app-theme', newTheme);
                updateThemeIcon(newTheme);
            });
        }

        // --- ALAPVETŐ VÁLTOZÓK ---
        const checkButton = document.getElementById('check-button');
        const revealButton = document.getElementById('reveal-button');
        const successAlert = document.getElementById('success-alert');
        const errorAlert = document.getElementById('error-alert');
        const errorCountSpan = document.getElementById('error-count');
        
        let solutionsRevealed = false;
        let savedUserAnswers = {}; 

        // --- LABIRINTUS INTERAKCIÓ ---
        document.addEventListener('click', function(e) {
            const cell = e.target.closest('.maze-cell');
            if (cell && !solutionsRevealed) {
                const entry = cell.querySelector('.maze-entry');
                if (entry && entry.textContent.trim() === 'START') {
                    cell.classList.add('user-selected');
                    return;
                }
                cell.classList.toggle('user-selected');
            }
        });

        // --- UNIVERZÁLIS ELLENŐRZŐ ---
        if (checkButton) {
            checkButton.addEventListener('click', function() {
                if (solutionsRevealed) return;

                successAlert.style.display = 'none';
                errorAlert.style.display = 'none';
                
                let hibasCount = 0;

                // 1. INPUT MEZŐK ELLENŐRZÉSE
                const inputsToCheck = document.querySelectorAll('input[data-correct]');
                inputsToCheck.forEach(input => {
                    input.classList.remove('hiba');
                    
                    const userVal = input.value.trim();
                    const correctVal = input.dataset.correct.toString().trim();

                    if (userVal === '') {
                         input.classList.add('hiba');
                         hibasCount++;
                    } else if (!isNaN(userVal) && !isNaN(correctVal)) {
                        if (Number(userVal) !== Number(correctVal)) {
                            input.classList.add('hiba');
                            hibasCount++;
                        }
                    } else {
                        if (userVal !== correctVal) {
                            input.classList.add('hiba');
                            hibasCount++;
                        }
                    }
                });

                // 2. LABIRINTUSOK ELLENŐRZÉSE
                const mazes = document.querySelectorAll('.maze-grid');
                mazes.forEach(maze => {
                    let mazeHasError = false;
                    let pathCellsTotal = 0;
                    let selectedPathCells = 0;

                    const cells = maze.querySelectorAll('.maze-cell');
                    cells.forEach(cell => {
                        cell.classList.remove('hiba');
                        const isPath = cell.dataset.isPath === "1";
                        const isSelected = cell.classList.contains('user-selected');
                        
                        if (isPath) pathCellsTotal++;

                        if (isSelected) {
                            if (!isPath) {
                                cell.classList.add('hiba');
                                mazeHasError = true;
                            } else {
                                selectedPathCells++;
                            }
                        }
                    });

                    if (mazeHasError || selectedPathCells < pathCellsTotal) {
                        hibasCount++;
                    }
                });

                if (inputsToCheck.length > 0 || mazes.length > 0) {
                    if (hibasCount === 0) {
                        successAlert.style.display = 'block';
                    } else {
                        errorCountSpan.textContent = hibasCount;
                        errorAlert.style.display = 'block';
                    }
                    window.scrollTo(0, 0);
                }
            });
        }

        // --- MEGOLDÁSOK MUTATÁSA / ELREJTÉSE ---
        if (revealButton) {
            revealButton.addEventListener('click', function() {
                const inputs = document.querySelectorAll('input[data-correct]');
                solutionsRevealed = !solutionsRevealed;

                successAlert.style.display = 'none';
                errorAlert.style.display = 'none';

                if (solutionsRevealed) {
                    this.innerHTML = '<i class="fas fa-eye-slash me-2"></i>Elrejtés';
                    this.classList.replace('btn-outline-secondary', 'btn-secondary');
                    document.body.classList.add('show-solutions');
                    
                    inputs.forEach((input, index) => {
                        savedUserAnswers[index] = input.value;
                        input.value = input.dataset.correct;
                        input.classList.remove('hiba');
                        input.classList.add('megoldas');
                        input.setAttribute('readonly', 'readonly');
                    });
                } else {
                    this.innerHTML = '<i class="fas fa-eye me-2"></i>Megoldások';
                    this.classList.replace('btn-secondary', 'btn-outline-secondary');
                    document.body.classList.remove('show-solutions');
                    
                    inputs.forEach((input, index) => {
                        input.value = savedUserAnswers[index] !== undefined ? savedUserAnswers[index] : '';
                        input.classList.remove('megoldas');
                        input.removeAttribute('readonly');
                    });
                }
            });
        }

        // --- NYOMTATÁS ---
        const printButton = document.getElementById('print-button');
        if (printButton) {
            printButton.addEventListener('click', function() {
                const printStyle = document.getElementById('print-style').value;
                document.body.classList.remove('print-kiemelt');
                if (printStyle === 'kiemelt') document.body.classList.add('print-kiemelt');
                window.print();
            });
            window.addEventListener('afterprint', function() {
                document.body.classList.remove('print-kiemelt');
            });
        }
    });
</script>