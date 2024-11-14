let currentIndex = 0; // Índice actual del slider (inicia en la primera opción)

const slider = document.querySelector('.slider'); // Selecciona el contenedor del slider
const options = document.querySelectorAll('.option'); // Selecciona todas las opciones dentro del slider
const totalOptions = options.length; // Número total de opciones disponibles en el slider
const itemsPerMove = 3; // Número de elementos que se moverán por cada clic en las flechas

const nextArrow = document.getElementById('nextArrow'); // Selecciona el botón de la flecha derecha
const prevArrow = document.getElementById('prevArrow'); // Selecciona el botón de la flecha izquierda

nextArrow.addEventListener('click', () => {
    // Evento de clic para la flecha derecha
    if (currentIndex + itemsPerMove < totalOptions) { 
        // Verifica si el índice actual más los elementos por mover no exceden el total de opciones
        currentIndex += itemsPerMove; // Incrementa el índice por los elementos a mover
        slider.style.transform = `translateX(-${(100 / 3) * currentIndex}%)`; 
        // Aplica una transformación CSS para mover el slider horizontalmente
        // Divide el 100% en 3 partes iguales para mostrar 3 elementos por vez
    }
});

prevArrow.addEventListener('click', () => {
    // Evento de clic para la flecha izquierda
    if (currentIndex - itemsPerMove >= 0) { 
        // Verifica si el índice actual menos los elementos por mover no es negativo
        currentIndex -= itemsPerMove; // Decrementa el índice por los elementos a mover
        slider.style.transform = `translateX(-${(100 / 3) * currentIndex}%)`; 
        // Aplica la transformación CSS para mover el slider hacia atrás
    }
});
