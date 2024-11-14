const options = document.querySelectorAll('.option'); // Selecciona todos los elementos con la clase 'option' y los almacena en un NodeList.

options.forEach(option => {
    option.addEventListener('mouseover', () => { // Agrega un evento 'mouseover' (cuando el mouse se pasa sobre el elemento).
        options.forEach(opt => { // Itera sobre todos los elementos con la clase 'option'.
            if (opt !== option) { // Si el elemento no es el que está actualmente siendo "hovered".
                opt.style.transform = 'scale(0.9)'; // Reduce el tamaño de los demás elementos al 90%.
            }
        });
        option.style.transform = 'scale(1)'; // Mantiene el tamaño original del elemento actual.
        option.style.transition = 'transform 0.3s'; // Aplica una transición suave de 0.3 segundos para el cambio de escala.
    });

    option.addEventListener('mouseout', () => { // Agrega un evento 'mouseout' (cuando el mouse sale del elemento).
        options.forEach(opt => { // Itera sobre todos los elementos con la clase 'option'.
            opt.style.transform = 'scale(1)'; // Restaura el tamaño original de todos los elementos al 100%.
        });
    });
});
