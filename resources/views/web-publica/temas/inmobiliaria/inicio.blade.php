<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="{{ url('/temas/inmobiliaria') }}/">

  <title>Tu inmobiliaria</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- CSS (igual que en Laravel, pero con rutas estáticas) -->
  <link rel="stylesheet" href="assets/index-BzbH-icu.css">
  <link rel="stylesheet" href="assets/home.css">
  <link rel="stylesheet" href="assets/custom.css">
  <link rel="stylesheet" href="assets/footer.css">
  <link rel="stylesheet" href="assets/nosotros.css">
  <link rel="stylesheet" href="assets/listado.css">
  <link rel="stylesheet" href="assets/contacto.css">
  <link rel="stylesheet" href="assets/nav.css">
  <link rel="stylesheet" href="assets/noddo-preview.css">
  <script defer src="assets/noddo-preview.js"></script>
</head>

<body class="min-h-screen bg-white">

  <!-- NAV (estático, sin Laravel) -->
  <nav class="nav">
    <div class="nav-container">
      <a href="index.html" class="nav-logo">
        <img src="assets/marca.svg" alt="Tu inmobiliaria">
      </a>

      <button class="nav-toggle" id="navToggle" type="button" aria-label="Abrir menú">
        <span></span><span></span><span></span>
      </button>

      <div class="nav-menu" id="navMenu">
        <a href="index.html" class="nav-link active">HOME</a>
        <a href="listado.html" class="nav-link">PROPIEDADES</a>
        <a href="nosotros.html" class="nav-link">NOSOTROS</a>
        <a href="tasaciones.html" class="nav-link">TASACIONES</a>
        <a href="contacto.html" class="nav-link">CONTACTO</a>
      </div>
    </div>
  </nav>

  <main class="pt-20">

    <div class="min-h-screen">

      <!-- HERO -->
      <section class="hero">
        <div class="hero__media">
          <img
            src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=2400&q=90"
            alt="Casa moderna rodeada de naturaleza" class="hero__img" loading="eager" />
          <div class="hero__wash"></div>
          <div class="hero__grad"></div>
        </div>

        <div class="hero__content">
          <h1 class="hero__title">
            <span>Vivir en el lugar</span>
            <span>correcto</span>
          </h1>
        </div>

        <!-- Buscador (maqueta: manda a listado.html) -->
        <div class="hero-search-wrap">
          <div class="hero-search">

            <div class="hero-search-tabs">
              <button type="button" class="hero-tab is-active">VENTA</button>
              <button type="button" class="hero-tab">ALQUILER</button>
              <button type="button" class="hero-tab">ALQUILER TEMPORAL</button>
            </div>

            <form action="listado.html" method="GET" class="hero-search-form">
              <div class="hero-field">
                <label class="hero-label">TIPO DE PROPIEDAD</label>
                <div class="hero-control hero-property-select">
                  <button type="button" class="hero-input hero-select-trigger" aria-expanded="false">
                    <span class="hero-select-value">Todos los tipos</span>
                  </button>
                  <span class="hero-select-chevron">▾</span>
                  <div class="hero-select-menu" role="listbox" aria-label="Tipo de propiedad">
                    <button type="button" class="hero-select-option" data-value="casa">Casa</button>
                    <button type="button" class="hero-select-option" data-value="departamento">Departamento</button>
                    <button type="button" class="hero-select-option" data-value="lote">Lote</button>
                  </div>
                  <input type="hidden" name="tipo" value="">
                </div>
              </div>

              <div class="hero-field">
                <label class="hero-label">UBICACIÓN</label>
                <div class="hero-control">
                  <input type="text" name="localidad" placeholder="Localidad o barrio" class="hero-input" />
                </div>
              </div>

              <div class="hero-action">
                <button type="submit" class="hero-btn">Buscar <span aria-hidden="true">→</span></button>
              </div>

              <input type="hidden" name="operacion" value="venta">
            </form>
          </div>
        </div>

      </section>

      <!-- PROPIEDADES DESTACADAS -->
      <section class="featured">
        <div class="featured__container">
          <header class="featured__header">
            <h2 class="featured__title">Propiedades destacadas</h2>
          </header>

          <div class="featured__grid">

            <article class="pcard">
              <a href="propiedad.html" class="pcard__link">
                <div class="pcard__media">
                  <span class="pcard__photos" aria-label="12 fotos"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="9.5" r="1.3"/><path d="m4 17 5-5 3.5 3.5 2.5-2.5 5 4"/></svg>12</span>
                  <span class="pcard__operation">Venta</span>
                  <img
                    src="https://images.unsplash.com/photo-1638369022547-1c763b1b9b3b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1600"
                    alt="Propiedad destacada" loading="lazy" />
                </div>

                <div class="pcard__body">
                  <h3 class="pcard__title">Casa en venta</h3>
                  <p class="pcard__location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.4"/></svg>Ubicación a definir</p>
                  <p class="pcard__price">USD 320.000</p>
                  <p class="pcard__desc">Propiedad de ejemplo. Los datos reales se mostrarán desde el CRM.
                  </p>
                  <div class="pcard__divider"></div>

                  <div class="pcard__meta">
                    <span class="pcard__metaItem" title="Dormitorios" aria-label="4 dormitorios"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 17v-6h18v6"/><path d="M3 17v3m18-3v3M5 11V8h5a3 3 0 0 1 3 3v1M3 14h18"/></svg>4</span>
                    <span class="pcard__metaItem" title="Baños" aria-label="3 baños"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 13h16v2a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4zM7 13V9a2 2 0 0 1 4 0v4M3 20h2m14 0h2"/></svg>3</span>
                    <span class="pcard__metaItem" title="Superficie" aria-label="320 metros cuadrados"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M9 5H5v4m10-4h4v4M5 15v4h4m10-4v4h-4"/></svg>320 m²</span>
                  </div>
                  <span class="pcard__cta">Ver propiedad <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span>
                </div>
              </a>
            </article>

            <article class="pcard">
              <a href="propiedad.html" class="pcard__link">
                <div class="pcard__media">
                  <span class="pcard__photos" aria-label="18 fotos"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="9.5" r="1.3"/><path d="m4 17 5-5 3.5 3.5 2.5-2.5 5 4"/></svg>18</span>
                  <span class="pcard__operation">Alquiler</span>
                  <img
                    src="https://images.unsplash.com/photo-1758299892056-2a2f980c993d?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1600"
                    alt="Propiedad destacada" loading="lazy" />
                </div>

                <div class="pcard__body">
                  <h3 class="pcard__title">Propiedad destacada</h3>
                  <p class="pcard__location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.4"/></svg>Ubicación a definir</p>
                  <p class="pcard__price">Consultar precio</p>
                  <p class="pcard__desc">Propiedad de ejemplo. Los datos reales se mostrarán desde el CRM.
                  </p>
                  <div class="pcard__divider"></div>

                  <div class="pcard__meta">
                    <span class="pcard__metaItem" title="Dormitorios" aria-label="5 dormitorios"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 17v-6h18v6"/><path d="M3 17v3m18-3v3M5 11V8h5a3 3 0 0 1 3 3v1M3 14h18"/></svg>5</span>
                    <span class="pcard__metaItem" title="Baños" aria-label="4 baños"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 13h16v2a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4zM7 13V9a2 2 0 0 1 4 0v4M3 20h2m14 0h2"/></svg>4</span>
                    <span class="pcard__metaItem" title="Superficie" aria-label="450 metros cuadrados"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M9 5H5v4m10-4h4v4M5 15v4h4m10-4v4h-4"/></svg>450 m²</span>
                  </div>
                  <span class="pcard__cta">Ver propiedad <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span>
                </div>
              </a>
            </article>

            <article class="pcard">
              <a href="propiedad.html" class="pcard__link">
                <div class="pcard__media">
                  <span class="pcard__photos" aria-label="9 fotos"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="9.5" r="1.3"/><path d="m4 17 5-5 3.5 3.5 2.5-2.5 5 4"/></svg>9</span>
                  <span class="pcard__operation">Alquiler temporal</span>
                  <img
                    src="https://images.unsplash.com/photo-1581784878214-8d5596b98a01?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1600"
                    alt="Propiedad destacada" loading="lazy" />
                </div>

                <div class="pcard__body">
                  <h3 class="pcard__title">Departamento temporal</h3>
                  <p class="pcard__location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.4"/></svg>Ubicación a definir</p>
                  <p class="pcard__price">Consultar precio</p>
                  <p class="pcard__desc">Propiedad de ejemplo. Los datos reales se mostrarán desde el CRM.
                  </p>
                  <div class="pcard__divider"></div>

                  <div class="pcard__meta">
                    <span class="pcard__metaItem" title="Dormitorios" aria-label="3 dormitorios"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 17v-6h18v6"/><path d="M3 17v3m18-3v3M5 11V8h5a3 3 0 0 1 3 3v1M3 14h18"/></svg>3</span>
                    <span class="pcard__metaItem" title="Baños" aria-label="2 baños"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 13h16v2a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4zM7 13V9a2 2 0 0 1 4 0v4M3 20h2m14 0h2"/></svg>2</span>
                    <span class="pcard__metaItem" title="Superficie" aria-label="180 metros cuadrados"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M9 5H5v4m10-4h4v4M5 15v4h4m10-4v4h-4"/></svg>180 m²</span>
                  </div>
                  <span class="pcard__cta">Ver propiedad <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span>
                </div>
              </a>
            </article>

          </div>

          <div class="featured__cta">
            <a class="featured__btn" href="listado.html">
              VER TODAS LAS PROPIEDADES
            </a>
          </div>
        </div>
      </section>

      <!-- Espacio reservado para futuras secciones del sitio. -->
      <template>
        <div class="max-w-[1800px] mx-auto px-8 lg:px-16">
          <div class="text-center mb-20">
            <p class="tracking-widest mb-4 text-black/40" style="font-size: 0.75rem; letter-spacing: 0.2em;">
              NUESTRAS ESPECIALIDADES
            </p>
            <h2 class="font-serif"
              style="font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 300; letter-spacing: -0.02em;">
              Dos destinos, una visión
            </h2>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <a href="propiedad.html" class="group relative aspect-[4/5] overflow-hidden text-left block">
              <img
                src="https://images.unsplash.com/photo-1689574666546-75e1036e55fb?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixlib=rb-4.1.0&q=80&w=1080"
                alt="Propiedades destacadas"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                loading="lazy" />
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

              <div class="absolute bottom-0 left-0 right-0 p-12">
                <h3 class="font-serif text-white mb-3"
                  style="font-size: 2.5rem; font-weight: 300; letter-spacing: -0.02em;">
                  Próximamente
                </h3>
                <p class="text-white/80 mb-6 max-w-md" style="font-size: 1rem; line-height: 1.6;">
                  Urbanización privada con infraestructura completa, colegios internacionales y vida comunitaria.
                </p>
                <span class="inline-flex items-center gap-2 text-white/90 group-hover:gap-3 transition-all"
                  style="font-size: 0.875rem; letter-spacing: 0.05em;">
                  EXPLORAR <span class="w-4 h-4">→</span>
                </span>
              </div>
            </a>

            <a href="propiedad.html" class="group relative aspect-[4/5] overflow-hidden text-left block">
              <img
                src="https://images.unsplash.com/photo-1758299892056-2a2f980c993d?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixlib=rb-4.1.0&q=80&w=1080"
                alt="Propiedades destacadas"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                loading="lazy" />
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

              <div class="absolute bottom-0 left-0 right-0 p-12">
                <h3 class="font-serif text-white mb-3"
                  style="font-size: 2.5rem; font-weight: 300; letter-spacing: -0.02em;">
                  Próximamente
                </h3>
                <p class="text-white/80 mb-6 max-w-md" style="font-size: 1rem; line-height: 1.6;">
                  Barrios náuticos exclusivos con amarras propias, canales navegables y vistas al agua.
                </p>
                <span class="inline-flex items-center gap-2 text-white/90 group-hover:gap-3 transition-all"
                  style="font-size: 0.875rem; letter-spacing: 0.05em;">
                  EXPLORAR <span class="w-4 h-4">→</span>
                </span>
              </div>
            </a>

          </div>
        </div>
      </template>

      <section class="work-areas" aria-labelledby="work-areas-title">
        <div class="work-areas__container">
          <div class="work-areas__intro">
            <h2 id="work-areas-title">Conocemos tu zona</h2>
            <p>Explorá las propiedades disponibles en las zonas donde trabajamos.</p>
          </div>
          <div class="work-areas__links">
            <a href="listado.html?localidad=Zona%20Norte" class="work-area-card">
              <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=85" alt="Casa moderna entre árboles" loading="lazy">
              <span class="work-area-card__shade"></span><span class="work-area-card__name">Zona Norte</span><span class="work-area-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span>
            </a>
            <a href="listado.html?localidad=San%20Isidro" class="work-area-card">
              <img src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1200&q=85" alt="Interior residencial luminoso" loading="lazy">
              <span class="work-area-card__shade"></span><span class="work-area-card__name">San Isidro</span><span class="work-area-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span>
            </a>
            <a href="listado.html?localidad=Tigre" class="work-area-card">
              <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=85" alt="Casa contemporánea con jardín" loading="lazy">
              <span class="work-area-card__shade"></span><span class="work-area-card__name">Tigre</span><span class="work-area-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span>
            </a>
            <a href="listado.html?localidad=Pilar" class="work-area-card">
              <img src="https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=1200&q=85" alt="Casa residencial de estilo moderno" loading="lazy">
              <span class="work-area-card__shade"></span><span class="work-area-card__name">Pilar</span><span class="work-area-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span>
            </a>
          </div>
        </div>
      </section>

      <section class="value-proposition" aria-labelledby="value-proposition-title">
        <div class="value-proposition__container">
          <div class="value-proposition__image">
            <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=1400&q=85" alt="Interior cálido de una vivienda" loading="lazy">
          </div>
          <div class="value-proposition__content">
            <p class="value-proposition__eyebrow">Nuestro acompañamiento</p>
            <h2 id="value-proposition-title">Estamos en cada decisión</h2>
            <p class="value-proposition__lead">Comprar, vender o alquilar es mucho más simple cuando contás con alguien que conoce el camino.</p>
            <ol class="value-steps">
              <li class="value-step" tabindex="0">
                <span class="value-step__number">01</span>
                <div><h3>Escuchamos lo que necesitás</h3><p>Entendemos tus prioridades antes de proponerte opciones.</p></div>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
              </li>
              <li class="value-step" tabindex="0">
                <span class="value-step__number">02</span>
                <div><h3>Te orientamos con claridad</h3><p>Información concreta para que puedas decidir con confianza.</p></div>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
              </li>
              <li class="value-step" tabindex="0">
                <span class="value-step__number">03</span>
                <div><h3>Te acompañamos hasta el final</h3><p>Seguimos presentes en cada instancia de la operación.</p></div>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
              </li>
            </ol>
          </div>
        </div>
      </section>

      <section class="seller-cta">
        <div class="seller-cta__content">
          <h2 class="seller-cta__title">¿Querés vender tu propiedad?</h2>
          <p class="seller-cta__description">Conocé su valor de mercado y recibí asesoramiento para definir la mejor estrategia de venta.</p>
          <a href="tasaciones.html" class="seller-cta__button">Solicitar tasación</a>
          <p class="seller-cta__note">Sin costo y sin compromiso.</p>
        </div>
      </section>

    </div>
  </main>

  <!-- FOOTER (igual al tuyo, solo sin asset()) -->
  <footer class="rc-footer">
    <div class="rc-footer__inner">
      <div class="rc-footer__grid">

        <div class="rc-footer__brand">
          <img src="assets/marca.svg" alt="Tu inmobiliaria" class="rc-footer__logo" />
        </div>

        <div class="rc-footer__company">
          <h3 class="rc-footer__name">Tu inmobiliaria</h3>
          <p class="rc-footer__role">Negocios Inmobiliarios</p>

          <h4 class="rc-footer__kicker">SEGUINOS</h4>

          <div class="rc-social">
            <a href="#" class="rc-social__link" aria-label="Instagram">
              <svg viewBox="0 0 24 24" class="rc-social__icon" fill="none" stroke="currentColor">
                <rect x="3" y="3" width="18" height="18" rx="6"></rect>
                <circle cx="12" cy="12" r="4"></circle>
                <circle cx="17.3" cy="6.7" r="1"></circle>
              </svg>
            </a>

            <a href="#" class="rc-social__link" aria-label="YouTube">
              <svg viewBox="0 0 24 24" class="rc-social__icon" fill="none" stroke="currentColor">
                <path
                  d="M21 12s0-3.6-.5-5.2a2.8 2.8 0 0 0-2-2C16.8 4 12 4 12 4s-4.8 0-6.5.8a2.8 2.8 0 0 0-2 2C3 8.4 3 12 3 12s0 3.6.5 5.2a2.8 2.8 0 0 0 2 2C7.2 20 12 20 12 20s4.8 0 6.5-.8a2.8 2.8 0 0 0 2-2C21 15.6 21 12 21 12z">
                </path>
                <path d="M10.3 9.5l5 2.5-5 2.5V9.5z" fill="currentColor" stroke="none"></path>
              </svg>
            </a>

            <a href="#" class="rc-social__link rc-social__link--wa" aria-label="WhatsApp">
              <svg viewBox="0 0 24 24" class="rc-social__icon" aria-hidden="true">
                <path fill="currentColor"
                  d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.93.54 3.82 1.58 5.46L2 22l4.79-1.56a9.83 9.83 0 0 0 5.25 1.5h.01c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2zm5.73 14.14c-.25.7-1.25 1.31-1.99 1.47-.51.11-1.17.2-3.8-.86-3.37-1.4-5.54-4.82-5.71-5.06-.17-.23-1.36-1.82-1.36-3.47 0-1.65.86-2.47 1.17-2.8.31-.33.68-.41.91-.41h.66c.21 0 .49-.08.77.59.25.61.85 2.1.93 2.25.08.15.13.33.03.52-.1.2-.15.33-.3.5-.15.17-.32.39-.45.52-.15.15-.3.31-.13.6.17.29.76 1.25 1.63 2.02 1.12 1 2.07 1.31 2.37 1.46.3.15.47.13.65-.08.18-.21.75-.87.95-1.17.2-.3.4-.25.66-.15.26.1 1.67.79 1.96.93.29.15.48.22.55.34.07.12.07.7-.18 1.4z" />
              </svg>
            </a>
          </div>
        </div>

        <div class="rc-footer__contact">
          <h4 class="rc-footer__kicker">CONTACTO</h4>

          <ul class="rc-contact">
            <li class="rc-contact__item">
              <svg class="rc-contact__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 6h16v12H4z"></path>
                <path d="M4 7l8 6 8-6"></path>
              </svg>
              <a href="mailto:info@tuinmobiliaria.com" class="rc-contact__link">info@tuinmobiliaria.com</a>
            </li>

            <li class="rc-contact__item">
              <svg class="rc-contact__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M6 2h3l2 5-2 1c1 3 3 5 6 6l1-2 5 2v3c0 1-1 2-2 2C9 19 5 15 3 5c0-1 1-2 2-3z">
                </path>
              </svg>
              <a href="tel:+540000000000" class="rc-contact__link">+54 9 00 0000-0000</a>
            </li>

            <li class="rc-contact__item">
              <svg class="rc-contact__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11z"></path>
                <circle cx="12" cy="10" r="2.2"></circle>
              </svg>
              <span class="rc-contact__text">Ciudad, Provincia<br>Argentina</span>
            </li>
          </ul>
        </div>

      </div>

      <div class="rc-footer__bottom">
        <p class="rc-footer__copy">© Tu inmobiliaria. Todos los derechos reservados.</p>

        <div class="rc-footer__links">
          <a href="#" class="rc-footer__link">Términos y Condiciones</a>
          <a href="#" class="rc-footer__link">Privacidad</a>
        </div>
      </div>

    </div>
  </footer>

  <script>
    // Hamburguesa
    document.getElementById("navToggle")?.addEventListener("click", function () {
      document.getElementById("navMenu")?.classList.toggle("open");
    });

    // Tabs del buscador (maqueta)
    (function () {
      const tabs = document.querySelectorAll('.hero-tab');
      const hidden = document.querySelector('input[name="operacion"]');
      if (!tabs.length || !hidden) return;

      tabs.forEach((btn, idx) => {
        btn.addEventListener('click', () => {
          tabs.forEach(t => t.classList.remove('is-active'));
          btn.classList.add('is-active');

          const map = ['venta', 'alquiler', 'alquiler-temporal'];
          hidden.value = map[idx] || 'venta';
        });
      });
    })();

    // Parallax sutil del fondo del hero durante el scroll.
    (function () {
      const image = document.querySelector('.hero__img');
      const hero = document.querySelector('.hero');
      const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

      if (!image || !hero || reducedMotion.matches || window.innerWidth < 768) return;

      let ticking = false;

      const updateParallax = () => {
        const position = hero.getBoundingClientRect();
        const scrollOffset = Math.max(-160, Math.min(28, position.top * 0.20));
        image.style.transform = `translate3d(0, ${scrollOffset}px, 0) scale(1.05)`;
        ticking = false;
      };

      const requestUpdate = () => {
        if (!ticking) {
          window.requestAnimationFrame(updateParallax);
          ticking = true;
        }
      };

      updateParallax();
      window.addEventListener('scroll', requestUpdate, { passive: true });
    })();

    // Selector de tipo de propiedad: se abre siempre debajo del campo.
    (function () {
      const selector = document.querySelector('.hero-property-select');
      if (!selector) return;

      const trigger = selector.querySelector('.hero-select-trigger');
      const value = selector.querySelector('.hero-select-value');
      const hidden = selector.querySelector('input[name="tipo"]');
      const options = selector.querySelectorAll('.hero-select-option');

      const close = () => {
        selector.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
      };

      trigger.addEventListener('click', () => {
        const isOpen = selector.classList.toggle('is-open');
        trigger.setAttribute('aria-expanded', String(isOpen));
      });

      options.forEach((option) => {
        option.addEventListener('click', () => {
          value.textContent = option.textContent;
          hidden.value = option.dataset.value;
          close();
        });
      });

      document.addEventListener('click', (event) => {
        if (!selector.contains(event.target)) close();
      });
    })();
  </script>

</body>

</html>
