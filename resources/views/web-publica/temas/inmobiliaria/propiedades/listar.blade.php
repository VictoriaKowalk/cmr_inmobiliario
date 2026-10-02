<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="{{ url('/temas/inmobiliaria') }}/">
  <title>Propiedades - Tu inmobiliaria</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link rel="stylesheet" href="assets/index-BzbH-icu.css">
  <link rel="stylesheet" href="assets/custom.css">
  <link rel="stylesheet" href="assets/footer.css">
  <link rel="stylesheet" href="assets/nav.css">
  <link rel="stylesheet" href="assets/listado.css">
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
      <a href="listado.html" class="nav-link active">PROPIEDADES</a>
      <a href="nosotros.html" class="nav-link">NOSOTROS</a>
      <a href="tasaciones.html" class="nav-link">TASACIONES</a>
      <a href="contacto.html" class="nav-link">CONTACTO</a>
    </div>
  </div>
</nav>

<main class="pt-20">

  <section class="pl-page">
    <div class="pl-container">

      <!-- Header -->
      <div class="pl-head">
        <h1 class="pl-title">Propiedades</h1>

        <div class="pl-bar">
          <p class="pl-count" id="plCount" aria-live="polite">3 propiedades encontradas</p>

          <!-- Mobile: botón filtros -->
          <button type="button" class="pl-filterBtn" id="plOpenFilters">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M4 6h16M7 12h10M10 18h4" fill="none" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" />
            </svg>
            Filtros
          </button>

          <!-- Sort (maqueta: no envía a backend) -->
          <form class="pl-sort" onsubmit="return false;">
            <label class="pl-sortLabel" for="sortBy">Ordenar</label>
            <select id="sortBy" name="sortBy" class="pl-sortSelect">
              <option value="date" selected>Más recientes</option>
              <option value="price-asc">Precio (menor a mayor)</option>
              <option value="price-desc">Precio (mayor a menor)</option>
              <option value="surface">Superficie (mayor)</option>
            </select>
          </form>
        </div>

        <div class="pl-chips" id="plActiveFilters" hidden aria-label="Filtros activos"></div>

      </div>

      <div class="pl-grid">

        <!-- Sidebar filtros (desktop) -->
        <aside class="pl-aside">

          <!-- FILTROS (convertidos desde filters.blade.php a HTML estático) -->
          <form class="pl-filters" method="GET" action="listado.html">
            <!-- para mantener el sort (maqueta) -->
            <input type="hidden" name="sortBy" value="date">

            <div class="pl-filterBox">
              <h3 class="pl-filterTitle">Filtros</h3>

              <!-- Operación -->
              <div class="pl-field">
                <label class="pl-label">OPERACIÓN</label>
                <div class="pl-selectWrap">
                  <select name="operation" class="pl-select">
                    <option value="">Todas</option>
                    <option value="venta">Comprar</option>
                    <option value="alquiler">Alquilar</option>
                    <option value="temporal">Alquiler Temporal</option>
                  </select>
                  <span class="pl-chevron">▾</span>
                </div>
              </div>

              <!-- Tipo -->
              <div class="pl-field">
                <label class="pl-label">TIPO DE PROPIEDAD</label>
                <div class="pl-selectWrap">
                  <select name="propertyType" class="pl-select">
                    <option value="">Todas</option>
                    <option value="casa">Casa</option>
                    <option value="departamento">Departamento</option>
                    <option value="lote">Lote</option>
                    <option value="quinta">Quinta</option>
                    <option value="ph">PH</option>
                  </select>
                  <span class="pl-chevron">▾</span>
                </div>
              </div>

              <!-- Ubicación -->
              <div class="pl-field">
                <label class="pl-label">UBICACIÓN</label>
                <input class="pl-input" type="text" name="location" value="" placeholder="Ej: San Isidro, Pilar.">
              </div>

              <!-- Precio -->
              <div class="pl-field">
                <label class="pl-label">PRECIO (USD)</label>
                <div class="pl-two">
                  <input class="pl-input" type="number" name="priceFrom" value="" placeholder="Desde">
                  <input class="pl-input" type="number" name="priceTo" value="" placeholder="Hasta">
                </div>
              </div>

              <!-- Superficie -->
              <div class="pl-field">
                <label class="pl-label">SUPERFICIE (m²)</label>
                <div class="pl-two">
                  <input class="pl-input" type="number" name="surfaceFrom" value="" placeholder="Desde">
                  <input class="pl-input" type="number" name="surfaceTo" value="" placeholder="Hasta">
                </div>
              </div>

              <!-- Dormitorios -->
              <div class="pl-field">
                <label class="pl-label">DORMITORIOS</label>
                <div class="pl-selectWrap">
                  <select name="bedrooms" class="pl-select">
                    <option value="">Todos</option>
                    <option value="1">1+</option>
                    <option value="2">2+</option>
                    <option value="3">3+</option>
                    <option value="4">4+</option>
                    <option value="5">5+</option>
                  </select>
                  <span class="pl-chevron">▾</span>
                </div>
              </div>

              <div class="pl-actions">
                <button class="pl-apply" type="submit">APLICAR FILTROS</button>
                <a class="pl-clear" href="listado.html">LIMPIAR FILTROS</a>
              </div>
            </div>
          </form>

        </aside>

        <!-- Listado -->
        <main class="pl-main">

          <div class="pl-cards" id="plCards">

            <!-- Card 1 -->
            <a class="pl-card" href="propiedad.html" data-operation="venta" data-property-type="casa" data-location="zona norte" data-price="850000" data-surface="320" data-bedrooms="4">
              <div class="pl-cardMedia">
                <img src="https://images.unsplash.com/photo-1638369022547-1c763b1b9b3b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1600"
                  alt="Propiedad de ejemplo" loading="lazy">
                <span class="pl-badge">DESTACADA</span>
                <span class="pl-operation">Venta</span>
                <span class="pl-photos"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="9.5" r="1.3"/><path d="m4 17 5-5 3.5 3.5 2.5-2.5 5 4"/></svg>12</span>
              </div>

              <div class="pl-cardBody">
                <p class="pl-price">USD 850.000</p>
                <h3 class="pl-cardTitle">Casa en venta</h3>

                <div class="pl-loc">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11z" fill="none"
                      stroke="currentColor" stroke-width="1.5" />
                    <circle cx="12" cy="10" r="2.3" fill="none" stroke="currentColor" stroke-width="1.5" />
                  </svg>
                  <span>Ubicación a definir</span>
                </div>

                <div class="pl-feats">
                  <div class="pl-feat">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M4 9V4h5M20 15v5h-5M20 9V4h-5M4 15v5h5" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <span>320 m²</span>
                  </div>

                  <div class="pl-feat">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M3 11h18v8M5 19v-3m14 3v-3M3 11V8a2 2 0 012-2h5a2 2 0 012 2v3"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                      <path d="M12 9h7a2 2 0 012 2v0" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <span>4 dorm</span>
                  </div>

                  <div class="pl-feat">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M4 10.5L12 4l8 6.5V20a1 1 0 01-1 1H5a1 1 0 01-1-1v-9.5z"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                    </svg>
                    <span>6 amb</span>
                  </div>
                </div>
              </div>
            </a>

            <!-- Card 2 -->
            <a class="pl-card" href="propiedad.html" data-operation="alquiler" data-property-type="casa" data-location="san isidro" data-price="1200000" data-surface="450" data-bedrooms="5">
              <div class="pl-cardMedia">
                <img src="https://images.unsplash.com/photo-1758299892056-2a2f980c993d?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1600"
                  alt="Propiedad de ejemplo" loading="lazy">
                <span class="pl-operation">Alquiler</span>
                <span class="pl-photos"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="9.5" r="1.3"/><path d="m4 17 5-5 3.5 3.5 2.5-2.5 5 4"/></svg>18</span>
              </div>

              <div class="pl-cardBody">
                <p class="pl-price">USD 1.200.000</p>
                <h3 class="pl-cardTitle">Propiedad destacada</h3>

                <div class="pl-loc">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11z" fill="none"
                      stroke="currentColor" stroke-width="1.5" />
                    <circle cx="12" cy="10" r="2.3" fill="none" stroke="currentColor" stroke-width="1.5" />
                  </svg>
                  <span>Ubicación a definir</span>
                </div>

                <div class="pl-feats">
                  <div class="pl-feat">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M4 9V4h5M20 15v5h-5M20 9V4h-5M4 15v5h5" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <span>450 m²</span>
                  </div>

                  <div class="pl-feat">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M3 11h18v8M5 19v-3m14 3v-3M3 11V8a2 2 0 012-2h5a2 2 0 012 2v3"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                      <path d="M12 9h7a2 2 0 012 2v0" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <span>5 dorm</span>
                  </div>

                  <div class="pl-feat">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M4 10.5L12 4l8 6.5V20a1 1 0 01-1 1H5a1 1 0 01-1-1v-9.5z"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                    </svg>
                    <span>7 amb</span>
                  </div>
                </div>
              </div>
            </a>

            <!-- Card 3 -->
            <a class="pl-card" href="propiedad.html" data-operation="temporal" data-property-type="departamento" data-location="tigre" data-price="450000" data-surface="180" data-bedrooms="3">
              <div class="pl-cardMedia">
                <img src="https://images.unsplash.com/photo-1581784878214-8d5596b98a01?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1600"
                  alt="Propiedad de ejemplo" loading="lazy">
                <span class="pl-operation">Alquiler temporal</span>
                <span class="pl-photos"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="9.5" r="1.3"/><path d="m4 17 5-5 3.5 3.5 2.5-2.5 5 4"/></svg>9</span>
              </div>

              <div class="pl-cardBody">
                <p class="pl-price">USD 450.000</p>
                <h3 class="pl-cardTitle">Departamento en venta</h3>

                <div class="pl-loc">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11z" fill="none"
                      stroke="currentColor" stroke-width="1.5" />
                    <circle cx="12" cy="10" r="2.3" fill="none" stroke="currentColor" stroke-width="1.5" />
                  </svg>
                  <span>Ubicación a definir</span>
                </div>

                <div class="pl-feats">
                  <div class="pl-feat">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M4 9V4h5M20 15v5h-5M20 9V4h-5M4 15v5h5" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <span>180 m²</span>
                  </div>

                  <div class="pl-feat">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M3 11h18v8M5 19v-3m14 3v-3M3 11V8a2 2 0 012-2h5a2 2 0 012 2v3"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                      <path d="M12 9h7a2 2 0 012 2v0" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <span>3 dorm</span>
                  </div>

                  <div class="pl-feat">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M4 10.5L12 4l8 6.5V20a1 1 0 01-1 1H5a1 1 0 01-1-1v-9.5z"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                    </svg>
                    <span>4 amb</span>
                  </div>
                </div>
              </div>
            </a>

          </div>

          <div class="pl-loadMore" id="plLoadMore" hidden>
            <button type="button" class="pl-loadMoreBtn">Cargar más propiedades</button>
          </div>

          <section class="pl-empty" id="plEmpty" hidden aria-live="polite">
            <div class="pl-emptyIcon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m16 16 4 4M8 11h6M11 8v6"/></svg></div>
            <h2 class="pl-emptyTitle">No encontramos propiedades con esos filtros</h2>
            <p class="pl-emptyText">Probá ampliar la búsqueda o quitá algún filtro para ver más opciones.</p>
            <button type="button" class="pl-emptyBtn" id="plEmptyClear">Limpiar filtros</button>
            <div class="pl-similar" id="plSimilar">
              <h3>También podrían interesarte</h3>
              <div class="pl-similarGrid" id="plSimilarGrid"></div>
            </div>
          </section>

        </main>
      </div>
    </div>
  </section>

  <!-- Drawer filtros (mobile) -->
  <div class="pl-drawerOverlay" id="plDrawerOverlay" hidden></div>

  <div class="pl-drawer" id="plDrawer" aria-hidden="true">
    <div class="pl-drawerTop">
      <h2 class="pl-drawerTitle">Filtros</h2>
      <button type="button" class="pl-drawerClose" id="plCloseFilters" aria-label="Cerrar">×</button>
    </div>

    <div class="pl-drawerBody">

      <!-- MISMOS FILTROS (mobile) -->
      <form class="pl-filters" method="GET" action="listado.html">
        <input type="hidden" name="sortBy" value="date">

        <div class="pl-filterBox">
          <h3 class="pl-filterTitle">Filtros</h3>

          <div class="pl-field">
            <label class="pl-label">OPERACIÓN</label>
            <div class="pl-selectWrap">
              <select name="operation" class="pl-select">
                <option value="">Todas</option>
                <option value="venta">Comprar</option>
                <option value="alquiler">Alquilar</option>
                <option value="temporal">Alquiler Temporal</option>
              </select>
              <span class="pl-chevron">▾</span>
            </div>
          </div>

          <div class="pl-field">
            <label class="pl-label">TIPO DE PROPIEDAD</label>
            <div class="pl-selectWrap">
              <select name="propertyType" class="pl-select">
                <option value="">Todas</option>
                <option value="casa">Casa</option>
                <option value="departamento">Departamento</option>
                <option value="lote">Lote</option>
                <option value="quinta">Quinta</option>
                <option value="ph">PH</option>
              </select>
              <span class="pl-chevron">▾</span>
            </div>
          </div>

          <div class="pl-field">
            <label class="pl-label">UBICACIÓN</label>
            <input class="pl-input" type="text" name="location" value="" placeholder="Ej: San Isidro, Pilar.">
          </div>

          <div class="pl-field">
            <label class="pl-label">PRECIO (USD)</label>
            <div class="pl-two">
              <input class="pl-input" type="number" name="priceFrom" value="" placeholder="Desde">
              <input class="pl-input" type="number" name="priceTo" value="" placeholder="Hasta">
            </div>
          </div>

          <div class="pl-field">
            <label class="pl-label">SUPERFICIE (m²)</label>
            <div class="pl-two">
              <input class="pl-input" type="number" name="surfaceFrom" value="" placeholder="Desde">
              <input class="pl-input" type="number" name="surfaceTo" value="" placeholder="Hasta">
            </div>
          </div>

          <div class="pl-field">
            <label class="pl-label">DORMITORIOS</label>
            <div class="pl-selectWrap">
              <select name="bedrooms" class="pl-select">
                <option value="">Todos</option>
                <option value="1">1+</option>
                <option value="2">2+</option>
                <option value="3">3+</option>
                <option value="4">4+</option>
                <option value="5">5+</option>
              </select>
              <span class="pl-chevron">▾</span>
            </div>
          </div>

          <div class="pl-actions">
            <button class="pl-apply" id="plMobileApply" type="submit">VER 3 PROPIEDADES</button>
            <a class="pl-clear" href="listado.html">LIMPIAR FILTROS</a>
          </div>
        </div>
      </form>

    </div>
  </div>

</main>

<!-- FOOTER -->
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
          <a href="#" class="rc-social__link" aria-label="Instagram"><svg viewBox="0 0 24 24" class="rc-social__icon" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="6"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.7" r="1"/></svg></a>
          <a href="#" class="rc-social__link" aria-label="YouTube"><svg viewBox="0 0 24 24" class="rc-social__icon" fill="none" stroke="currentColor"><path d="M21 12s0-3.6-.5-5.2a2.8 2.8 0 0 0-2-2C16.8 4 12 4 12 4s-4.8 0-6.5.8a2.8 2.8 0 0 0-2 2C3 8.4 3 12 3 12s0 3.6.5 5.2a2.8 2.8 0 0 0 2 2C7.2 20 12 20 12 20s4.8 0 6.5-.8a2.8 2.8 0 0 0 2-2C21 15.6 21 12 21 12z"/><path d="m10.3 9.5 5 2.5-5 2.5V9.5z" fill="currentColor" stroke="none"/></svg></a>
          <a href="#" class="rc-social__link" aria-label="WhatsApp"><svg viewBox="0 0 24 24" class="rc-social__icon" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 0 0-8.5 15.3L2 22l4.8-1.5A10 10 0 1 0 12 2Zm0 18.1a8.1 8.1 0 0 1-4.1-1.1l-.3-.2-2.8.9.9-2.7-.2-.3A8.1 8.1 0 1 1 12 20.1Zm4.5-6.1c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1-.6.8-.8 1-.3.2-.5.1a6.7 6.7 0 0 1-2-1.2 7.5 7.5 0 0 1-1.4-1.8c-.1-.2 0-.4.1-.5l.4-.4c.1-.1.1-.2.2-.4s0-.3 0-.4l-.7-1.6c-.2-.4-.4-.3-.5-.3h-.5c-.2 0-.4.1-.6.3s-.8.8-.8 2 .8 2.4.9 2.6a9.3 9.3 0 0 0 3.6 3.4c.5.2.9.4 1.2.5.5.2 1 .2 1.4.1.4-.1 1.4-.6 1.6-1.2s.2-1.1.1-1.2-.2-.2-.4-.3Z"/></svg></a>
        </div>
      </div>

      <div class="rc-footer__contact">
        <h4 class="rc-footer__kicker">CONTACTO</h4>
        <ul class="rc-contact">
          <li class="rc-contact__item"><svg class="rc-contact__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 6h16v12H4z"/><path d="m4 7 8 6 8-6"/></svg><a href="mailto:info@tuinmobiliaria.com" class="rc-contact__link">info@tuinmobiliaria.com</a></li>
          <li class="rc-contact__item"><svg class="rc-contact__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2h3l2 5-2 1c1 3 3 5 6 6l1-2 5 2v3c0 1-1 2-2 2C9 19 5 15 3 5c0-1 1-2 2-3z"/></svg><a href="tel:+540000000000" class="rc-contact__link">+54 9 00 0000-0000</a></li>
          <li class="rc-contact__item"><svg class="rc-contact__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 21s7-6 7-11a7 7 0 1 0-14 0c0 5 7 11 7 11z"/><circle cx="12" cy="10" r="2.2"/></svg><span class="rc-contact__text">Ciudad, Provincia<br>Argentina</span></li>
        </ul>
      </div>
    </div>

    <div class="rc-footer__bottom">
      <p class="rc-footer__copy">© Tu inmobiliaria. Todos los derechos reservados.</p>
      <div class="rc-footer__links"><a href="#" class="rc-footer__link">Términos y condiciones</a><a href="#" class="rc-footer__link">Privacidad</a></div>
    </div>
  </div>
</footer>

<script>
  // hamburguesa nav
  document.getElementById("navToggle")?.addEventListener("click", function () {
    document.getElementById("navMenu")?.classList.toggle("open");
  });

  // drawer filtros mobile
  (function () {
    const openBtn = document.getElementById('plOpenFilters');
    const closeBtn = document.getElementById('plCloseFilters');
    const drawer = document.getElementById('plDrawer');
    const overlay = document.getElementById('plDrawerOverlay');

    function open() {
      overlay.hidden = false;
      drawer.classList.add('is-open');
      drawer.setAttribute('aria-hidden', 'false');
      document.documentElement.classList.add('pl-lock');
    }
    function close() {
      overlay.hidden = true;
      drawer.classList.remove('is-open');
      drawer.setAttribute('aria-hidden', 'true');
      document.documentElement.classList.remove('pl-lock');
    }

    if (openBtn) openBtn.addEventListener('click', open);
    if (closeBtn) closeBtn.addEventListener('click', close);
    if (overlay) overlay.addEventListener('click', close);
  })();

  // Desplegables personalizados
  (function () {
    const chevron = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>';
    const customSelects = [];

    function closeAll(except) {
      customSelects.forEach((instance) => {
        if (instance !== except) {
          instance.wrapper.classList.remove('is-open');
          instance.trigger.setAttribute('aria-expanded', 'false');
        }
      });
    }

    document.querySelectorAll('.pl-select, .pl-sortSelect').forEach((select, index) => {
      const isFilterSelect = select.classList.contains('pl-select');
      const wrapper = isFilterSelect ? select.parentElement : document.createElement('div');

      if (!isFilterSelect) {
        wrapper.className = 'pl-sortSelectWrap';
        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);
      }

      wrapper.classList.add('pl-customSelect');
      select.tabIndex = -1;
      select.setAttribute('aria-hidden', 'true');

      const trigger = document.createElement('button');
      trigger.type = 'button';
      trigger.className = 'pl-customSelect__trigger';
      trigger.setAttribute('aria-expanded', 'false');
      trigger.setAttribute('aria-haspopup', 'listbox');
      trigger.setAttribute('aria-controls', `pl-select-menu-${index}`);

      const menu = document.createElement('div');
      menu.className = 'pl-customSelect__menu';
      menu.id = `pl-select-menu-${index}`;
      menu.setAttribute('role', 'listbox');

      function renderValue() {
        const option = select.options[select.selectedIndex];
        trigger.innerHTML = `<span>${option.text}</span>${chevron}`;
        menu.querySelectorAll('.pl-customSelect__option').forEach((button) => {
          button.classList.toggle('is-selected', button.dataset.value === select.value);
          button.setAttribute('aria-selected', String(button.dataset.value === select.value));
        });
      }

      Array.from(select.options).forEach((option) => {
        const optionButton = document.createElement('button');
        optionButton.type = 'button';
        optionButton.className = 'pl-customSelect__option';
        optionButton.dataset.value = option.value;
        optionButton.textContent = option.text;
        optionButton.setAttribute('role', 'option');
        optionButton.addEventListener('click', () => {
          select.value = option.value;
          select.dispatchEvent(new Event('change', { bubbles: true }));
          renderValue();
          closeAll();
          trigger.focus();
        });
        menu.appendChild(optionButton);
      });

      trigger.addEventListener('click', () => {
        const willOpen = !wrapper.classList.contains('is-open');
        closeAll(wrapper);
        wrapper.classList.toggle('is-open', willOpen);
        trigger.setAttribute('aria-expanded', String(willOpen));
      });

      trigger.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeAll();
      });

      select.addEventListener('change', renderValue);
      wrapper.append(trigger, menu);
      renderValue();
      customSelects.push({ wrapper, trigger });
    });

    document.addEventListener('click', (event) => {
      if (!event.target.closest('.pl-customSelect')) closeAll();
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') closeAll();
    });
  })();

  // Filtros, estado vacío y carga progresiva del listado.
  (function () {
    const forms = Array.from(document.querySelectorAll('.pl-filters'));
    const cards = Array.from(document.querySelectorAll('#plCards .pl-card'));
    const cardsContainer = document.getElementById('plCards');
    const count = document.getElementById('plCount');
    const activeFilters = document.getElementById('plActiveFilters');
    const empty = document.getElementById('plEmpty');
    const emptyClear = document.getElementById('plEmptyClear');
    const similarGrid = document.getElementById('plSimilarGrid');
    const loadMore = document.getElementById('plLoadMore');
    const loadMoreButton = loadMore?.querySelector('button');
    const mobileApply = document.getElementById('plMobileApply');
    const pageSize = 9;
    let visibleLimit = pageSize;
    let currentFilters = {};

    const labels = {
      operation: 'Operación', propertyType: 'Tipo', location: 'Ubicación',
      priceFrom: 'Precio desde', priceTo: 'Precio hasta', surfaceFrom: 'Superficie desde',
      surfaceTo: 'Superficie hasta', bedrooms: 'Dormitorios'
    };

    function setField(form, name, value) {
      const field = form.querySelector(`[name="${name}"]`);
      if (!field) return;
      field.value = value;
      field.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function filtersFrom(form) {
      return Object.fromEntries(new FormData(form).entries());
    }

    function syncForms(source) {
      const values = filtersFrom(source);
      forms.forEach((form) => {
        if (form === source) return;
        Object.entries(values).forEach(([name, value]) => setField(form, name, value));
      });
      return values;
    }

    function matches(card, filters) {
      const price = Number(card.dataset.price);
      const surface = Number(card.dataset.surface);
      const bedrooms = Number(card.dataset.bedrooms);
      const location = card.dataset.location || '';
      return (!filters.operation || card.dataset.operation === filters.operation)
        && (!filters.propertyType || card.dataset.propertyType === filters.propertyType)
        && (!filters.location || location.includes(filters.location.trim().toLowerCase()))
        && (!filters.priceFrom || price >= Number(filters.priceFrom))
        && (!filters.priceTo || price <= Number(filters.priceTo))
        && (!filters.surfaceFrom || surface >= Number(filters.surfaceFrom))
        && (!filters.surfaceTo || surface <= Number(filters.surfaceTo))
        && (!filters.bedrooms || bedrooms >= Number(filters.bedrooms));
    }

    function updateMobileButton(filters) {
      if (!mobileApply) return;
      const total = cards.filter((card) => matches(card, filters)).length;
      mobileApply.textContent = `Ver ${total} ${total === 1 ? 'propiedad' : 'propiedades'}`;
    }

    function renderActiveFilters(filters) {
      const meaningful = Object.entries(filters).filter(([name, value]) => labels[name] && value);
      activeFilters.hidden = meaningful.length === 0;
      activeFilters.innerHTML = '';
      meaningful.forEach(([name, value]) => {
        const chip = document.createElement('span');
        chip.className = 'pl-chip';
        const selected = forms[0]?.querySelector(`[name="${name}"] option:checked`);
        const displayValue = selected ? selected.textContent : value;
        chip.innerHTML = `<span>${labels[name]}: ${displayValue}</span><button type="button" aria-label="Quitar filtro ${labels[name]}">×</button>`;
        chip.querySelector('button').addEventListener('click', () => {
          forms.forEach((form) => setField(form, name, ''));
          apply(filtersFrom(forms[0]));
        });
        activeFilters.appendChild(chip);
      });
    }

    function updateUrl(filters) {
      const params = new URLSearchParams();
      Object.entries(filters).forEach(([name, value]) => {
        if (labels[name] && value) params.set(name, value);
      });
      const query = params.toString();
      window.history.replaceState({}, '', `${window.location.pathname}${query ? `?${query}` : ''}`);
    }

    function renderSimilar() {
      similarGrid.innerHTML = '';
      cards.slice(0, 3).forEach((card) => {
        const clone = card.cloneNode(true);
        clone.classList.remove('is-hidden');
        similarGrid.appendChild(clone);
      });
    }

    function apply(filters, shouldUpdateUrl = true, resetLimit = true) {
      currentFilters = filters;
      if (resetLimit) visibleLimit = pageSize;
      const matching = cards.filter((card) => matches(card, filters));
      cards.forEach((card, index) => {
        const isVisible = matching.includes(card) && matching.indexOf(card) < visibleLimit;
        card.classList.toggle('is-hidden', !isVisible);
      });
      const total = matching.length;
      count.textContent = `${total} ${total === 1 ? 'propiedad encontrada' : 'propiedades encontradas'}`;
      cardsContainer.hidden = total === 0;
      empty.hidden = total !== 0;
      loadMore.hidden = total <= visibleLimit;
      renderActiveFilters(filters);
      updateMobileButton(filters);
      if (total === 0) renderSimilar();
      if (shouldUpdateUrl) updateUrl(filters);
    }

    function clearFilters() {
      forms.forEach((form) => form.reset());
      forms.forEach((form) => form.querySelectorAll('select').forEach((select) => select.dispatchEvent(new Event('change', { bubbles: true }))));
      apply(filtersFrom(forms[0]));
    }

    forms.forEach((form) => {
      form.addEventListener('submit', (event) => {
        event.preventDefault();
        const filters = syncForms(form);
        apply(filters);
        if (form.closest('.pl-drawer')) document.getElementById('plCloseFilters')?.click();
      });
      form.addEventListener('input', () => updateMobileButton(filtersFrom(form)));
      form.addEventListener('change', () => updateMobileButton(filtersFrom(form)));
    });

    document.querySelectorAll('.pl-clear').forEach((link) => link.addEventListener('click', (event) => {
      event.preventDefault();
      clearFilters();
    }));
    emptyClear?.addEventListener('click', clearFilters);
    loadMoreButton?.addEventListener('click', () => {
      visibleLimit += pageSize;
      apply(currentFilters, false, false);
    });

    const urlFilters = Object.fromEntries(new URLSearchParams(window.location.search).entries());
    const aliases = { tipo: 'propertyType', localidad: 'location', operacion: 'operation' };
    Object.entries(urlFilters).forEach(([name, value]) => {
      const targetName = aliases[name] || name;
      forms.forEach((form) => setField(form, targetName, value));
    });
    apply(filtersFrom(forms[0]), false);
  })();
</script>

</body>
</html>
