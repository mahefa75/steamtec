// Gestion de l'affichage responsive
function handleResponsiveTable() {
    const desktopTable = document.querySelector('.table:not(.table-mobile)');
    const mobileTable = document.querySelector('.table-mobile');
    
    if (!desktopTable || !mobileTable) return;

    if (window.innerWidth <= 768) {
        desktopTable.classList.add('d-none');
        mobileTable.classList.remove('d-none');
    } else {
        desktopTable.classList.remove('d-none');
        mobileTable.classList.add('d-none');
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    handleResponsiveTable();
    window.addEventListener('resize', handleResponsiveTable);
});

// Export de la fonction pour une utilisation globale si nécessaire
window.initializeUsersTable = function() {
    handleResponsiveTable();
}; 