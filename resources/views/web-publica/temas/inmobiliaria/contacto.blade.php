<!doctype html>
<html lang="es"><head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><base href="{{ url('/temas/inmobiliaria') }}/">
  <title>Contacto - Tu inmobiliaria</title>
  <link rel="stylesheet" href="assets/index-BzbH-icu.css"><link rel="stylesheet" href="assets/custom.css"><link rel="stylesheet" href="assets/footer.css"><link rel="stylesheet" href="assets/nav.css"><link rel="stylesheet" href="assets/contacto.css"><link rel="stylesheet" href="assets/noddo-preview.css"><script defer src="assets/noddo-preview.js"></script>
</head><body>
<nav class="nav"><div class="nav-container"><a href="index.html" class="nav-logo"><img src="assets/marca.svg" alt="Tu inmobiliaria"></a><button class="nav-toggle" id="navToggle" type="button" aria-label="Abrir menú"><span></span><span></span><span></span></button><div class="nav-menu" id="navMenu"><a href="index.html" class="nav-link">HOME</a><a href="listado.html" class="nav-link">PROPIEDADES</a><a href="nosotros.html" class="nav-link">NOSOTROS</a><a href="tasaciones.html" class="nav-link">TASACIONES</a><a href="contacto.html" class="nav-link active">CONTACTO</a></div></div></nav>

<main class="contact">
  <section class="contact-hero"><div class="contact-container"><p class="contact-kicker">CONTACTO</p><h1>Hablemos de lo que estás buscando.</h1><p>Ya sea que quieras consultar por una propiedad, vender o simplemente resolver una duda, estamos para escucharte.</p></div></section>
  <section class="contact-main"><div class="contact-container contact-grid">
    <div class="contact-copy"><p class="contact-kicker">ENVIANOS TU CONSULTA</p><h2>Una conversación puede ser el primer paso.</h2><p>Dejanos tus datos y contanos cómo podemos ayudarte. Nuestro equipo recibirá tu consulta y se pondrá en contacto con vos.</p><div class="contact-points"><div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg><span><strong>Conocemos la zona</strong>Una mirada local para orientarte mejor.</span></div><div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v12H8l-4 3V5Z"/><path stroke-linecap="round" d="M8 10h8"/></svg><span><strong>Atención personalizada</strong>Tu consulta se trata de forma directa.</span></div></div><a class="contact-property-link" href="listado.html">¿Querés ver propiedades? <span aria-hidden="true">→</span></a></div>
    <div class="contact-card">
      @if (session('estado'))<div class="contact-alert" role="status">{{ session('estado') }}</div>@endif
      <form method="POST" action="{{ route('publico.consultas.guardar') }}" novalidate>@csrf
        <div class="contact-trap" aria-hidden="true"><label for="sitio_web">Sitio web</label><input id="sitio_web" name="sitio_web" tabindex="-1" autocomplete="off"></div>
        <div class="contact-fields"><label>Nombre completo<input name="nombre" value="{{ old('nombre') }}" autocomplete="name" required></label><label>Email<input type="email" name="email" value="{{ old('email') }}" autocomplete="email"></label><label>Teléfono<input type="tel" name="telefono" value="{{ old('telefono') }}" autocomplete="tel"></label><label class="contact-field--wide">¿Cómo podemos ayudarte?<textarea name="mensaje" rows="6" placeholder="Contanos tu consulta" required>{{ old('mensaje') }}</textarea></label></div>
        @if ($errors->any())<div class="contact-errors" role="alert"><p>Revisá los datos ingresados:</p><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <button type="submit">ENVIAR CONSULTA <span aria-hidden="true">→</span></button><p class="contact-privacy">Al enviar, aceptás que nos comuniquemos con vos para responder tu consulta.</p>
      </form>
    </div>
  </div></section>
</main>
@include('web-publica.temas.inmobiliaria.partials.footer')
<script>document.getElementById('navToggle')?.addEventListener('click',()=>document.getElementById('navMenu')?.classList.toggle('open'));</script>
</body></html>
