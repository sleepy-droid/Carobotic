document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.getElementById('blog-carousel');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const indicatorsContainer = document.getElementById('carousel-indicators');
    
    // Obtener todos los items (diapositivas)
    const items = carousel.querySelectorAll('.carousel-item');
    const totalItems = items.length;
    let currentIndex = 0;
    
    // Si no hay posts, ocultar flechas e indicadores
    if (totalItems <= 1) {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
        if (indicatorsContainer) indicatorsContainer.style.display = 'none';
        return; // Detener la ejecución si no hay suficiente contenido para un carrusel
    }

    // Función para actualizar la posición del carrusel
    const updateCarousel = () => {
        const offset = -currentIndex * 100;
        carousel.style.transform = `translateX(${offset}%)`;
        updateIndicators();
    };

    // Función para crear y actualizar los indicadores (puntos)
    const updateIndicators = () => {
        indicatorsContainer.innerHTML = '';
        items.forEach((_, index) => {
            const indicator = document.createElement('div');
            indicator.classList.add('indicator');
            if (index === currentIndex) {
                indicator.classList.add('active');
            }
            // Navegación al hacer clic en el punto
            indicator.addEventListener('click', () => {
                currentIndex = index;
                updateCarousel();
            });
            indicatorsContainer.appendChild(indicator);
        });
    };

    // Navegación al siguiente item
    const nextSlide = () => {
        currentIndex = (currentIndex + 1) % totalItems;
        updateCarousel();
    };

    // Navegación al item anterior
    const prevSlide = () => {
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
        updateCarousel();
    };

    // Asignar eventos a las flechas
    prevBtn.addEventListener('click', prevSlide);
    nextBtn.addEventListener('click', nextSlide);

    // Inicialización
    updateCarousel();

    // Auto-avance (Opcional: Descomentar si deseas que avance automáticamente)
    // setInterval(nextSlide, 5000); 
});