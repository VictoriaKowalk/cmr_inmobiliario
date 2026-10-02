<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="{{ url('/temas/inmobiliaria') }}/">
  <title>Nosotros - Tu inmobiliaria</title>
  <link rel="stylesheet" href="assets/index-BzbH-icu.css">
  <link rel="stylesheet" href="assets/custom.css">
  <link rel="stylesheet" href="assets/footer.css">
  <link rel="stylesheet" href="assets/nav.css">
  <link rel="stylesheet" href="assets/nosotros.css">
  <link rel="stylesheet" href="assets/noddo-preview.css">
  <script defer src="assets/noddo-preview.js"></script>
</head>
<body>
<nav class="nav"><div class="nav-container">
  <a href="index.html" class="nav-logo"><img src="assets/marca.svg" alt="Tu inmobiliaria"></a>
  <button class="nav-toggle" id="navToggle" type="button" aria-label="Abrir menú"><span></span><span></span><span></span></button>
  <div class="nav-menu" id="navMenu"><a href="index.html" class="nav-link">HOME</a><a href="listado.html" class="nav-link">PROPIEDADES</a><a href="nosotros.html" class="nav-link active">NOSOTROS</a><a href="tasaciones.html" class="nav-link">TASACIONES</a><a href="contacto.html" class="nav-link">CONTACTO</a></div>
</div></nav>

<main class="about">
  <section class="about-hero"><div class="site-container about-hero__grid">
    <div class="about-hero__copy">
      <p class="kicker">NOSOTROS</p><h1 class="display-serif">Una inmobiliaria cerca tuyo.</h1>
      <p class="about-hero__lead">Acompañamos decisiones inmobiliarias con conocimiento local, información clara y atención personalizada.</p>
      <p class="about-hero__text">Comprar, vender o alquilar una propiedad es una decisión importante. Por eso trabajamos cerca: entendiendo lo que necesitás y haciendo que cada etapa sea más simple.</p>
      <a href="listado.html" class="about-link">Explorá nuestras propiedades <span aria-hidden="true">→</span></a>
    </div>
    <figure class="about-hero__media"><img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=85" alt="Interior de una propiedad luminosa" loading="eager"></figure>
  </div></section>

  <section class="about-intro"><div class="site-container about-intro__grid">
    <p class="kicker">NUESTRA FORMA DE TRABAJAR</p>
    <div><h2 class="title-serif">Escuchamos antes de proponer.</h2><p>Creemos que un buen asesoramiento comienza por conocer a cada persona, su momento y sus prioridades. Combinamos una mirada cercana con herramientas claras para que puedas decidir con tranquilidad.</p></div>
  </div></section>

  <section class="about-values"><div class="site-container">
    <div class="section-head"><p class="kicker">LO QUE NOS DEFINE</p><h2 class="title-serif">Una experiencia más clara y personal.</h2></div>
    <div class="values-grid">
      <article class="value-card"><div class="value-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></div><h3>Conocimiento local</h3><p>Conocemos las zonas en las que trabajamos y te ayudamos a encontrar opciones que tengan sentido para vos.</p></article>
      <article class="value-card"><div class="value-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="8" r="3.25"/><path stroke-linecap="round" stroke-linejoin="round" d="M5 20c.7-3.4 3.1-5.5 7-5.5s6.3 2.1 7 5.5"/></svg></div><h3>Atención personalizada</h3><p>Cada búsqueda y cada propiedad tienen su contexto. Te acompañamos con una atención directa y cercana.</p></article>
      <article class="value-card"><div class="value-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5h14v14H5z"/><path stroke-linecap="round" d="m8.5 12 2.2 2.2L15.8 9"/></svg></div><h3>Información clara</h3><p>Te acercamos los datos necesarios en cada paso para que puedas evaluar y avanzar con confianza.</p></article>
    </div>
  </div></section>

  <section class="about-process"><div class="site-container">
    <div class="section-head section-head--split"><div><p class="kicker">TE ACOMPAÑAMOS</p><h2 class="title-serif">En cada etapa.</h2></div><p>Desde la primera consulta hasta el cierre, estamos para ordenar el proceso y resolver tus dudas.</p></div>
    <ol class="process-list"><li><span>01</span><div><h3>Escuchamos</h3><p>Entendemos qué necesitás, qué buscás y qué es importante para vos.</p></div></li><li><span>02</span><div><h3>Orientamos</h3><p>Te presentamos alternativas y la información para comparar con claridad.</p></div></li><li><span>03</span><div><h3>Concretamos</h3><p>Te acompañamos para que la decisión y la gestión sean simples.</p></div></li></ol>
  </div></section>

  <section class="about-cta"><div class="site-container"><p class="kicker">HABLEMOS</p><h2 class="title-serif">¿Querés hablar con nosotros?</h2><p>Contanos qué necesitás. Estamos para ayudarte a dar el próximo paso.</p><div class="cta-actions"><a href="contacto.html" class="btn btn--primary">CONTACTANOS</a><a href="listado.html" class="btn btn--outline">VER PROPIEDADES</a></div></div></section>
</main>
@include('web-publica.temas.inmobiliaria.partials.footer')
<script>document.getElementById('navToggle')?.addEventListener('click', () => document.getElementById('navMenu')?.classList.toggle('open'));</script>
</body>
</html>
