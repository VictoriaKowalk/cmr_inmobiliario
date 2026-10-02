<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="{{ url('/temas/inmobiliaria') }}/">
  <title>Propiedad - Tu inmobiliaria</title>

  <link rel="stylesheet" href="assets/index-BzbH-icu.css">
  <link rel="stylesheet" href="assets/custom.css">
  <link rel="stylesheet" href="assets/footer.css">
  <link rel="stylesheet" href="assets/nav.css">
  <link rel="stylesheet" href="assets/listado.css">
  <!-- Si tenés CSS específico de detalle, descomentá y ajustá el nombre -->
  <!-- <link rel="stylesheet" href="assets/propiedad.css"> -->
  <link rel="stylesheet" href="assets/noddo-preview.css">
  <script defer src="assets/noddo-preview.js"></script>
</head>

<body>

<!-- NAV -->
<nav class="nav">
  <div class="nav-container">
    <a href="index.html" class="nav-logo">
      <img src="assets/marca.svg" alt="Tu inmobiliaria">
    </a>

    <button class="nav-toggle" id="navToggle" type="button" aria-label="Abrir menú">
      <span></span><span></span><span></span>
    </button>

    <div class="nav-menu" id="navMenu">
      <a href="index.html" class="nav-link">HOME</a>
      <a href="listado.html" class="nav-link">PROPIEDADES</a>
      <a href="nosotros.html" class="nav-link">NOSOTROS</a>
      <a href="tasaciones.html" class="nav-link">TASACIONES</a>
      <a href="contacto.html" class="nav-link">CONTACTO</a>
    </div>
  </div>
</nav>

<main class="pt-20">

  <div class="pd-wrap">

    <!-- VOLVER -->
    <div class="pd-backbar">
      <div class="pd-backbar__inner">
        <a href="listado.html" class="pd-backlink">
          <span aria-hidden="true">←</span>
          VOLVER AL LISTADO
        </a>
      </div>

    </div>

    <div class="pd-container">

      <!-- GALERÍA -->
      <div class="pd-gallery">
        <div class="pd-gallery__carousel">
          <div class="pd-gallery__main">
          <img
            id="pdMainImage"
            src="https://images.unsplash.com/photo-1650211803854-e7b2e0ce86f9?auto=format&fit=crop&w=1400&q=80"
            alt="Propiedad de ejemplo">
            <div class="pd-gallery__controls"><button type="button" id="pdPreviousImage" aria-label="Foto anterior"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m14 6-6 6 6 6"/></svg></button><span id="pdGalleryCount">1 / 3</span><button type="button" id="pdNextImage" aria-label="Foto siguiente"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m10 6 6 6-6 6"/></svg></button></div>
            <button class="pd-gallery__open" type="button" id="pdOpenGallery"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="9.5" r="1.3"/><path d="m4 17 5-5 3.5 3.5 2.5-2.5 5 4"/></svg>Ver fotos</button>
          </div>
          <div class="pd-gallery__thumbs" aria-label="Miniaturas de fotos">
            <button class="pd-gallery__thumb is-active" type="button" data-image="https://images.unsplash.com/photo-1650211803854-e7b2e0ce86f9?auto=format&fit=crop&w=1400&q=80" data-alt="Vista exterior de la propiedad">
            <img src="https://images.unsplash.com/photo-1650211803854-e7b2e0ce86f9?auto=format&fit=crop&w=600&q=80" alt="Vista exterior de la propiedad">
          </button>
          <button class="pd-gallery__thumb" type="button" data-image="https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1400&q=80" data-alt="Vista interior de la propiedad">
            <img
              src="https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=600&q=80"
              alt="Vista interior de la propiedad">
          </button>
          <button class="pd-gallery__thumb" type="button" data-image="https://images.unsplash.com/photo-1769528527740-23887d50645a?auto=format&fit=crop&w=1400&q=80" data-alt="Detalle de la propiedad">
            <img
              src="https://images.unsplash.com/photo-1769528527740-23887d50645a?auto=format&fit=crop&w=600&q=80"
              alt="Detalle de la propiedad">
          </button>
          </div>
        </div>
      </div>

      <div class="pd-lightbox" id="pdLightbox" hidden role="dialog" aria-modal="true" aria-label="Galería de fotos">
        <button class="pd-lightbox__close" type="button" id="pdCloseGallery" aria-label="Cerrar galería"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg></button>
        <img id="pdLightboxImage" src="" alt="">
      </div>

      <div class="pd-main">

        <!-- COLUMNA IZQUIERDA -->
        <div class="pd-left">

          <header class="pd-heading">
            <div class="pd-heading__meta"><span class="pd-badge">Venta</span><span class="pd-code">Cód. NOR-850</span></div>
            <h1 class="pd-title">Propiedad de ejemplo</h1>
            <p class="pd-price">USD 850.000</p>
            <p class="pd-location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.4"/></svg>Ubicación a definir</p>
          </header>

          <div>
            <p class="pd-kicker">DESCRIPCIÓN</p>
            <p class="pd-text">
              Espectacular casa de diseño contemporáneo con amplios ventanales y vista al lago.<br><br>
              Terminaciones premium, pileta, quincho y solarium.
            </p>
          </div>

          <!-- ASPECTOS GENERALES -->
          <div>
            <p class="pd-kicker">ASPECTOS GENERALES</p>

            <div class="pd-aspects">

              <div class="pd-aspect">
                <svg class="pd-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M9 3 7 21M17 3l-2 18M4 9h17M3 15h17"/></svg>
                <div>
                  <p class="pd-aspect__label">Código</p>
                  <p class="pd-aspect__value">NOR-850</p>
                </div>
              </div>

              <div class="pd-aspect">
                <svg class="pd-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 17v-6h18v6M3 17v3m18-3v3M5 11V8h5a3 3 0 0 1 3 3v1M3 14h18"/></svg>
                <div>
                  <p class="pd-aspect__label">Dormitorios</p>
                  <p class="pd-aspect__value">4</p>
                </div>
              </div>

              <div class="pd-aspect">
                <svg class="pd-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 18v-6h16v6M4 18v2m16-2v2M7 12V9a3 3 0 0 1 6 0v3m2-3h2a3 3 0 0 1 3 3"/></svg>
                <div>
                  <p class="pd-aspect__label">Ambientes</p>
                  <p class="pd-aspect__value">5</p>
                </div>
              </div>

              <div class="pd-aspect">
                <svg class="pd-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 13h16v2a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4zM7 13V9a2 2 0 0 1 4 0v4M3 20h2m14 0h2"/></svg>
                <div>
                  <p class="pd-aspect__label">Baños</p>
                  <p class="pd-aspect__value">3</p>
                </div>
              </div>

              <div class="pd-aspect">
                <svg class="pd-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M5 16h14v3H5zM7 16l1.5-6h7L17 16M7 19v2m10-2v2M8 16h.01M16 16h.01"/></svg>
                <div>
                  <p class="pd-aspect__label">Cocheras</p>
                  <p class="pd-aspect__value">2</p>
                </div>
              </div>

              <div class="pd-aspect">
                <svg class="pd-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="m4 20 16-16M7 17l-3-3m6 0-3-3m6 0-3-3m6 0-3-3m3 0 3 3"/></svg>
                <div>
                  <p class="pd-aspect__label">Superficie</p>
                  <p class="pd-aspect__value">320 m²</p>
                </div>
              </div>

              <div class="pd-aspect">
                <svg class="pd-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="12" cy="12" r="8"/><path d="M12 8v4l3 2"/></svg>
                <div>
                  <p class="pd-aspect__label">Antigüedad</p>
                  <p class="pd-aspect__value">A estrenar</p>
                </div>
              </div>

            </div>
          </div>

          <section class="pd-amenities">
            <p class="pd-kicker">AMENITIES Y SERVICIOS</p>
            <ul class="pd-amenities__list">
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m5 12 4 4 10-10"/></svg>Pileta</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m5 12 4 4 10-10"/></svg>Quincho</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m5 12 4 4 10-10"/></svg>Jardín</li>
              <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m5 12 4 4 10-10"/></svg>Cochera cubierta</li>
            </ul>
          </section>

          <!-- VIDEO -->
          <div class="pd-video">
            <p class="pd-kicker">VIDEO</p>
            <div class="pd-video__frame">
              <iframe
                src="https://www.youtube.com/embed/auX0URAlGV4?autoplay=0&rel=0&modestbranding=1"
                title="Video de la propiedad"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe>
            </div>
          </div>

          <section class="pd-map-section">
            <p class="pd-kicker">UBICACIÓN</p>
            <div class="pd-map-section__content">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.4"/></svg>
              <div><strong>Ubicación a definir</strong><p>El mapa se mostrará cuando se cargue la dirección de la propiedad.</p></div>
            </div>
          </section>

          <p class="pd-legal">Las medidas, superficies y gastos son aproximados y deberán verificarse con la documentación correspondiente. Las fotografías tienen carácter ilustrativo y no contractual.</p>

        </div>

        <!-- COLUMNA DERECHA (FORM) -->
        <aside class="pd-side">
          <div class="pd-card">
            <h3 class="pd-form-title">Consultá por esta propiedad</h3>

            <!-- Maqueta: redirige a contacto-enviado -->
            <form id="pdForm" action="contacto-enviado.html" method="GET">

              <div class="pd-field">
                <label class="pd-label">NOMBRE</label>
                <input class="pd-input" type="text" name="nombre" required>
              </div>

              <div class="pd-field">
                <label class="pd-label">EMAIL</label>
                <input class="pd-input" type="email" name="email" required>
              </div>

              <div class="pd-field">
                <label class="pd-label">TELÉFONO</label>
                <input class="pd-input" type="text" name="telefono">
              </div>

              <div class="pd-field">
                <label class="pd-label">MENSAJE</label>
                <textarea class="pd-textarea" name="mensaje">Hola, estoy interesado en la propiedad NOR-850</textarea>
              </div>

              <input type="hidden" name="codigo" value="NOR-850">

              <button class="pd-submit" type="submit">ENVIAR CONSULTA</button>
            </form>

            <a class="pd-whatsapp" href="https://wa.me/5490000000000?text=Hola%2C%20quiero%20consultar%20por%20la%20propiedad%20NOR-850" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 0 0-8.5 15.3L2 22l4.8-1.5A10 10 0 1 0 12 2Zm0 18.1a8.1 8.1 0 0 1-4.1-1.1l-.3-.2-2.8.9.9-2.7-.2-.3A8.1 8.1 0 1 1 12 20.1Z"/></svg>Consultar por WhatsApp</a>
            <button class="pd-print" type="button" id="pdPrint"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M6 9V3h12v6M6 17H4a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2M6 14h12v7H6z"/></svg>Imprimir ficha</button>

          </div>
        </aside>

      </div>

      <section class="pd-related" aria-labelledby="pd-related-title">
        <div class="pd-related__head"><h2 id="pd-related-title">Propiedades similares</h2><a href="listado.html">Ver todas</a></div>
        <div class="pd-related__grid">
          <a href="propiedad.html" class="pd-related__card"><img src="https://images.unsplash.com/photo-1638369022547-1c763b1b9b3b?auto=format&fit=crop&w=900&q=80" alt="Propiedad similar" loading="lazy"><span>Venta</span><h3>Casa en venta</h3><p>USD 320.000</p></a>
          <a href="propiedad.html" class="pd-related__card"><img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=900&q=80" alt="Propiedad similar" loading="lazy"><span>Venta</span><h3>Propiedad destacada</h3><p>Consultar precio</p></a>
          <a href="propiedad.html" class="pd-related__card"><img src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=900&q=80" alt="Propiedad similar" loading="lazy"><span>Alquiler</span><h3>Departamento en alquiler</h3><p>Consultar precio</p></a>
        </div>
      </section>
    </div>
  </div>

</main>

@include('web-publica.temas.inmobiliaria.partials.footer')

<script>
  // hamburguesa nav
  document.getElementById("navToggle")?.addEventListener("click", function () {
    document.getElementById("navMenu")?.classList.toggle("open");
  });

  // Galería de la propiedad
  (function () {
    const mainImage = document.getElementById('pdMainImage');
    const thumbnails = Array.from(document.querySelectorAll('.pd-gallery__thumb'));
    const lightbox = document.getElementById('pdLightbox');
    const lightboxImage = document.getElementById('pdLightboxImage');
    const galleryCount = document.getElementById('pdGalleryCount');
    let currentIndex = 0;

    function setImage(button) {
      mainImage.src = button.dataset.image;
      mainImage.alt = button.dataset.alt;
      thumbnails.forEach((thumbnail) => thumbnail.classList.toggle('is-active', thumbnail === button));
      currentIndex = thumbnails.indexOf(button);
      galleryCount.textContent = `${currentIndex + 1} / ${thumbnails.length}`;
    }

    thumbnails.forEach((thumbnail) => thumbnail.addEventListener('click', () => setImage(thumbnail)));
    document.getElementById('pdPreviousImage')?.addEventListener('click', () => setImage(thumbnails[(currentIndex - 1 + thumbnails.length) % thumbnails.length]));
    document.getElementById('pdNextImage')?.addEventListener('click', () => setImage(thumbnails[(currentIndex + 1) % thumbnails.length]));

    function openGallery() {
      lightboxImage.src = mainImage.src;
      lightboxImage.alt = mainImage.alt;
      lightbox.hidden = false;
      document.documentElement.classList.add('pd-lock');
    }

    function closeGallery() {
      lightbox.hidden = true;
      document.documentElement.classList.remove('pd-lock');
    }

    document.getElementById('pdOpenGallery')?.addEventListener('click', openGallery);
    document.getElementById('pdCloseGallery')?.addEventListener('click', closeGallery);
    lightbox?.addEventListener('click', (event) => { if (event.target === lightbox) closeGallery(); });
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') closeGallery(); });
    document.getElementById('pdPrint')?.addEventListener('click', () => window.print());
  })();
</script>

</body>
</html>
