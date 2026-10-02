(() => {
    const routes = {
        'index.html': '/',
        'listado.html': '/propiedades',
        'propiedad.html': '/propiedades/ejemplo',
        'nosotros.html': '/nosotros',
        'tasaciones.html': '/tasacion',
        'tasaciones-enviado.html': '/tasacion/enviado',
        'contacto.html': '/contacto',
        'contacto-enviado.html': '/contacto/enviado',
    };

    document.querySelectorAll('a[href], form[action]').forEach((element) => {
        const attribute = element instanceof HTMLFormElement ? 'action' : 'href';
        const value = element.getAttribute(attribute);

        if (value && routes[value]) {
            element.setAttribute(attribute, routes[value]);
        }
    });

    const menu = document.querySelector('#navMenu');

    if (menu && ! menu.querySelector('[data-noddo-ingreso]')) {
        const ingreso = document.createElement('a');
        ingreso.href = '/administracion/ingreso';
        ingreso.className = 'nav-link';
        ingreso.dataset.noddoIngreso = 'true';
        ingreso.textContent = 'INGRESAR';
        menu.append(ingreso);
    }
})();
