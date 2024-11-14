document.addEventListener('DOMContentLoaded', () => {
    // Espera hasta que todo el contenido HTML haya sido cargado y parseado antes de ejecutar el código.

    const hamburgerIcon = document.getElementById('hamburger-icon'); // Selecciona el icono de hamburguesa mediante su ID.
    const mobileNav = document.getElementById('mobile-nav'); // Selecciona la barra de navegación móvil mediante su ID.

    hamburgerIcon.addEventListener('click', () => { 
        // Añade un evento 'click' al icono de hamburguesa.
        mobileNav.classList.toggle('active'); 
        // Cambia la clase 'active' en el menú de navegación móvil cada vez que se hace clic en el icono.
    });
});
