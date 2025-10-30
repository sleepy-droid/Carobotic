document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('blog-carousel');
    const items = carousel.querySelectorAll('.carousel-item');
    
    // Solo necesitamos hacer algo si hay 2 entradas
    if (items.length === 2) {
        let currentSlide = 0;

        function updateCarousel() {
            // Mueve el carrusel: 0% para el primer item, -100% para el segundo.
            carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
        }

        // Función para cambiar de slide
        function nextSlide() {
            currentSlide = (currentSlide + 1) % items.length; // Cambia entre 0 y 1
            updateCarousel();
        }

        // Cambiar automáticamente cada 5 segundos
        setInterval(nextSlide, 5000);
    }
});