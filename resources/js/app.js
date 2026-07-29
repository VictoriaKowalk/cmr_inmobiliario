import './bootstrap';

document.querySelectorAll('[data-autocomplete-ubicacion]').forEach((contenedor) => {
    const entrada = contenedor.querySelector('[data-autocomplete-entrada]');
    const identificador = contenedor.querySelector('[data-autocomplete-id]');
    const resultados = contenedor.querySelector('[data-autocomplete-resultados]');
    const url = contenedor.dataset.url;
    let temporizador;

    const cerrarResultados = () => {
        resultados.innerHTML = '';
        resultados.classList.add('hidden');
    };

    entrada.addEventListener('input', () => {
        identificador.value = '';
        clearTimeout(temporizador);
        const texto = entrada.value.trim();

        if (texto.length < 2) {
            cerrarResultados();
            return;
        }

        temporizador = setTimeout(async () => {
            const respuesta = await fetch(`${url}?buscar=${encodeURIComponent(texto)}`, {
                headers: { Accept: 'application/json' },
            });
            const ubicaciones = await respuesta.json();

            resultados.innerHTML = '';
            resultados.classList.remove('hidden');

            if (ubicaciones.length === 0) {
                resultados.innerHTML = '<p class="px-3 py-3 text-sm text-neutral-500">No se encontraron ubicaciones.</p>';
                return;
            }

            ubicaciones.forEach((ubicacion) => {
                const boton = document.createElement('button');
                boton.type = 'button';
                boton.className = 'block w-full border-b border-neutral-100 px-3 py-3 text-left text-sm hover:bg-neutral-50 last:border-b-0';
                boton.textContent = ubicacion.nombre_completo;
                boton.addEventListener('click', () => {
                    entrada.value = ubicacion.nombre_completo;
                    identificador.value = ubicacion.id;
                    cerrarResultados();
                });
                resultados.appendChild(boton);
            });
        }, 250);
    });

    document.addEventListener('click', (evento) => {
        if (!contenedor.contains(evento.target)) {
            cerrarResultados();
        }
    });
});

document.querySelectorAll('[data-operacion]').forEach((contenedor) => {
    const casilla = contenedor.querySelector('[data-operacion-activa]');
    const campos = contenedor.querySelector('[data-operacion-campos]');

    const actualizar = () => {
        campos.classList.toggle('hidden', !casilla.checked);
        contenedor.classList.toggle('border-emerald-500', casilla.checked);
        contenedor.classList.toggle('bg-emerald-50/30', casilla.checked);
    };

    casilla.addEventListener('change', actualizar);
    actualizar();
});

document.querySelectorAll('[data-selector-imagenes]').forEach((entrada) => {
    const vistaPrevia = document.querySelector(
        `[data-vista-previa="${entrada.id}"]`
    );
    const mensajeError = document.querySelector(
        `[data-error-imagenes="${entrada.id}"]`
    );
    const formulario = entrada.closest('form');
    const archivosSeleccionados = new Map();
    const maximoArchivos = Number(entrada.dataset.maximoArchivos || 20);
    const maximoPorArchivo = Number(
        entrada.dataset.maximoBytes || 10 * 1024 * 1024
    );
    const maximoTotal = Number(
        entrada.dataset.maximoTotalBytes || 200 * 1024 * 1024
    );

    const claveArchivo = (archivo) => [
        archivo.name,
        archivo.size,
        archivo.lastModified,
    ].join('-');

    const sincronizarEntrada = () => {
        const transferencia = new DataTransfer();
        archivosSeleccionados.forEach((archivo) => transferencia.items.add(archivo));
        entrada.files = transferencia.files;
    };

    const mostrarError = (mensaje = '') => {
        mensajeError.textContent = mensaje;
        mensajeError.classList.toggle('hidden', mensaje === '');
    };

    const calcularTotal = () => Array.from(archivosSeleccionados.values())
        .reduce((total, archivo) => total + archivo.size, 0);

    const mostrarVistaPrevia = () => {
        vistaPrevia.innerHTML = '';

        archivosSeleccionados.forEach((archivo, clave) => {
            const elemento = document.createElement('div');
            elemento.className = 'relative aspect-[4/3] overflow-hidden border border-neutral-200 bg-neutral-100';

            const imagen = document.createElement('img');
            imagen.className = 'h-full w-full object-cover';
            imagen.alt = archivo.name;
            imagen.src = URL.createObjectURL(archivo);
            imagen.addEventListener('load', () => URL.revokeObjectURL(imagen.src));

            const quitar = document.createElement('button');
            quitar.type = 'button';
            quitar.className = 'absolute right-2 top-2 flex size-8 items-center justify-center bg-neutral-950 text-lg leading-none text-white';
            quitar.setAttribute('aria-label', `Quitar ${archivo.name}`);
            quitar.title = 'Quitar imagen';
            quitar.textContent = '×';
            quitar.addEventListener('click', () => {
                archivosSeleccionados.delete(clave);
                sincronizarEntrada();
                mostrarVistaPrevia();
            });

            elemento.appendChild(imagen);
            elemento.appendChild(quitar);
            vistaPrevia.appendChild(elemento);
        });
    };

    entrada.addEventListener('change', () => {
        mostrarError();

        Array.from(entrada.files).forEach((archivo) => {
            if (archivo.size > maximoPorArchivo) {
                mostrarError(`La imagen "${archivo.name}" supera los 10 MB.`);
                return;
            }

            if (archivosSeleccionados.size >= maximoArchivos
                && ! archivosSeleccionados.has(claveArchivo(archivo))) {
                mostrarError(`Podés seleccionar hasta ${maximoArchivos} imágenes.`);
                return;
            }

            if (calcularTotal() + archivo.size > maximoTotal
                && ! archivosSeleccionados.has(claveArchivo(archivo))) {
                mostrarError('El conjunto de imágenes supera los 200 MB permitidos.');
                return;
            }

            archivosSeleccionados.set(claveArchivo(archivo), archivo);
        });

        sincronizarEntrada();
        mostrarVistaPrevia();
    });

    formulario.addEventListener('submit', (evento) => {
        if (archivosSeleccionados.size === 0) {
            evento.preventDefault();
            mostrarError('Seleccioná al menos una imagen para subir.');
        }
    });
});

document.querySelectorAll('[data-lista-ordenable]').forEach((lista) => {
    let elementoArrastrado = null;

    const actualizarPosiciones = () => {
        lista.querySelectorAll('[data-imagen-ordenable]').forEach((elemento, indice) => {
            const posicion = indice + 1;
            elemento.querySelector('[data-orden-imagen]').value = posicion;
            elemento.querySelector('[data-posicion-imagen]').textContent = `Posición ${posicion}`;
        });
    };

    lista.querySelectorAll('[data-asa-arrastre]').forEach((asa) => {
        asa.addEventListener('dragstart', (evento) => {
            elementoArrastrado = asa.closest('[data-imagen-ordenable]');
            elementoArrastrado.classList.add('opacity-50', 'ring-2', 'ring-emerald-500');
            evento.dataTransfer.effectAllowed = 'move';
            evento.dataTransfer.setData('text/plain', elementoArrastrado.dataset.imagenId);
        });

        asa.addEventListener('dragend', () => {
            elementoArrastrado?.classList.remove('opacity-50', 'ring-2', 'ring-emerald-500');
            elementoArrastrado = null;
            actualizarPosiciones();
        });
    });

    lista.addEventListener('dragover', (evento) => {
        evento.preventDefault();

        if (!elementoArrastrado) {
            return;
        }

        const destino = evento.target.closest('[data-imagen-ordenable]');
        if (!destino || destino === elementoArrastrado) {
            return;
        }

        const rectangulo = destino.getBoundingClientRect();
        const diferenciaVertical = evento.clientY - (rectangulo.top + rectangulo.height / 2);
        const diferenciaHorizontal = evento.clientX - (rectangulo.left + rectangulo.width / 2);
        const despues = Math.abs(diferenciaVertical) > rectangulo.height / 4
            ? diferenciaVertical > 0
            : diferenciaHorizontal > 0;
        lista.insertBefore(
            elementoArrastrado,
            despues ? destino.nextSibling : destino
        );
        actualizarPosiciones();
    });

    lista.querySelectorAll('[data-mover-imagen]').forEach((boton) => {
        boton.addEventListener('click', () => {
            const elemento = boton.closest('[data-imagen-ordenable]');

            if (boton.dataset.moverImagen === 'antes') {
                const anterior = elemento.previousElementSibling;
                if (anterior) {
                    lista.insertBefore(elemento, anterior);
                }
            } else {
                const siguiente = elemento.nextElementSibling;
                if (siguiente) {
                    lista.insertBefore(siguiente, elemento);
                }
            }

            actualizarPosiciones();
        });
    });

    actualizarPosiciones();
});

let promesaLeaflet;
let promesaGoogleMaps;

const cargarScript = (src) => new Promise((resolve, reject) => {
    const existente = document.querySelector(`script[src="${src}"]`);
    if (existente) {
        if (existente.dataset.cargado === '1') {
            resolve();
            return;
        }

        existente.addEventListener('load', resolve, { once: true });
        existente.addEventListener('error', reject, { once: true });
        return;
    }

    const script = document.createElement('script');
    script.src = src;
    script.async = true;
    script.defer = true;
    script.addEventListener('load', () => {
        script.dataset.cargado = '1';
        resolve();
    }, { once: true });
    script.addEventListener('error', reject, { once: true });
    document.head.appendChild(script);
});

const cargarEstilo = (href) => {
    if (document.querySelector(`link[href="${href}"]`)) {
        return;
    }

    const estilo = document.createElement('link');
    estilo.rel = 'stylesheet';
    estilo.href = href;
    document.head.appendChild(estilo);
};

const cargarLeaflet = async () => {
    if (window.L) {
        return window.L;
    }

    if (!promesaLeaflet) {
        cargarEstilo('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
        promesaLeaflet = cargarScript('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js')
            .then(() => window.L);
    }

    return promesaLeaflet;
};

const cargarGoogleMaps = (clave) => {
    if (window.google?.maps?.places) {
        return Promise.resolve(window.google);
    }

    if (promesaGoogleMaps) {
        return promesaGoogleMaps;
    }

    promesaGoogleMaps = new Promise((resolve, reject) => {
        const nombreCallback = `iniciarGoogleMaps${Date.now()}`;
        window[nombreCallback] = () => {
            resolve(window.google);
            delete window[nombreCallback];
        };

        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${encodeURIComponent(clave)}&libraries=places&callback=${nombreCallback}`;
        script.async = true;
        script.defer = true;
        script.onerror = () => reject(new Error('No se pudo cargar Google Maps.'));
        document.head.appendChild(script);
    });

    return promesaGoogleMaps;
};

document.querySelectorAll('[data-geocodificacion]').forEach(async (contenedor) => {
    const proveedorMapas = contenedor.dataset.mapasProveedor || 'openstreetmap';
    const clave = contenedor.dataset.googleMapsKey;
    const entradaDireccion = contenedor.querySelector('[data-direccion-geocodificacion]');
    const entradaLatitud = contenedor.querySelector('[data-latitud-geocodificacion]');
    const entradaLongitud = contenedor.querySelector('[data-longitud-geocodificacion]');
    const entradaDireccionNormalizada = contenedor.querySelector('[data-direccion-normalizada]');
    const entradaProveedor = contenedor.querySelector('[data-proveedor-geocodificacion]');
    const entradaPlaceId = contenedor.querySelector('[data-place-id]');
    const entradaConfirmada = contenedor.querySelector('[data-ubicacion-confirmada]');
    const estado = contenedor.querySelector('[data-estado-geocodificacion]');
    const mapaElemento = contenedor.querySelector('[data-mapa-geocodificacion]');
    const botonConfirmar = contenedor.querySelector('[data-confirmar-geocodificacion]');
    const entradaUbicacion = document.querySelector('[data-autocomplete-entrada]');

    const actualizarEstado = (mensaje) => {
        estado.textContent = mensaje;
    };

    const fijarCoordenadas = (latitud, longitud, confirmar = true) => {
        entradaLatitud.value = Number(latitud).toFixed(7);
        entradaLongitud.value = Number(longitud).toFixed(7);
        entradaConfirmada.value = confirmar ? '1' : '0';
    };

    const coordenadasIniciales = () => {
        const latitudTexto = entradaLatitud.value || contenedor.dataset.latitudInicial || '';
        const longitudTexto = entradaLongitud.value || contenedor.dataset.longitudInicial || '';

        if (latitudTexto === '' || longitudTexto === '') {
            return { lat: -34.397, lng: -58.668 };
        }

        const latitud = Number(latitudTexto);
        const longitud = Number(longitudTexto);

        if (Number.isFinite(latitud) && Number.isFinite(longitud)) {
            return { lat: latitud, lng: longitud };
        }

        return { lat: -34.397, lng: -58.668 };
    };

    entradaDireccion.addEventListener('input', () => {
        entradaConfirmada.value = '0';
        entradaDireccionNormalizada.value = '';
        entradaPlaceId.value = '';
    });

    if (proveedorMapas !== 'google' || !clave) {
        try {
            const L = await cargarLeaflet();
            const centroInicial = coordenadasIniciales();
            const tieneCoordenadas = entradaLatitud.value !== '' && entradaLongitud.value !== '';

            mapaElemento.classList.remove('flex', 'items-center', 'justify-center', 'text-center', 'text-sm', 'text-neutral-500');
            mapaElemento.classList.add('min-h-[320px]');
            mapaElemento.textContent = '';

            const mapa = L.map(mapaElemento, {
                center: [centroInicial.lat, centroInicial.lng],
                zoom: tieneCoordenadas ? 16 : 11,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            }).addTo(mapa);

            const marcador = L.marker(
                [centroInicial.lat, centroInicial.lng],
                { draggable: true }
            );

            if (tieneCoordenadas) {
                marcador.addTo(mapa);
            }

            const ubicarPin = (latitud, longitud, direccionNormalizada = '') => {
                const coordenadas = [Number(latitud), Number(longitud)];
                marcador.setLatLng(coordenadas);

                if (!mapa.hasLayer(marcador)) {
                    marcador.addTo(mapa);
                }

                mapa.setView(coordenadas, 17);
                entradaDireccionNormalizada.value = direccionNormalizada || entradaDireccion.value;
                entradaProveedor.value = 'openstreetmap';
                entradaPlaceId.value = '';
                fijarCoordenadas(latitud, longitud);
            };

            const esperar = (milisegundos) => new Promise(
                (resolve) => setTimeout(resolve, milisegundos)
            );

            const normalizarUbicacionParaBusqueda = () => {
                const textoUbicacion = entradaUbicacion?.value?.trim() || '';

                return textoUbicacion
                    .split('|')
                    .map((parte) => parte.trim())
                    .filter(Boolean)
                    .filter((parte) => !/^argentina$/i.test(parte))
                    .filter((parte) => !/g\.?\s*b\.?\s*a/i.test(parte))
                    .filter((parte) => !/zona/i.test(parte))
                    .filter((parte) => !/countries|b\.?\s*cerrado/i.test(parte));
            };

            const buscarCoordenadasOpenStreetMap = async (consulta) => {
                const parametros = new URLSearchParams({
                    q: consulta,
                    format: 'jsonv2',
                    addressdetails: '1',
                    limit: '1',
                    countrycodes: 'ar',
                });
                const respuesta = await fetch(`https://nominatim.openstreetmap.org/search?${parametros.toString()}`, {
                    headers: { Accept: 'application/json' },
                });
                const resultados = await respuesta.json();

                if (!respuesta.ok || !resultados?.[0]) {
                    return null;
                }

                return resultados[0];
            };

            botonConfirmar.addEventListener('click', async () => {
                const direccion = entradaDireccion.value.trim();

                if (!direccion) {
                    actualizarEstado('Escribí una dirección para buscar coordenadas.');
                    return;
                }

                const partesUbicacion = normalizarUbicacionParaBusqueda();
                const consultas = [
                    [direccion, ...partesUbicacion, 'Buenos Aires', 'Argentina'],
                    [direccion, partesUbicacion[0], 'Buenos Aires', 'Argentina'],
                    [direccion, 'Buenos Aires', 'Argentina'],
                ]
                    .map((partes) => partes.filter(Boolean).join(', '))
                    .filter((consulta, indice, lista) => lista.indexOf(consulta) === indice);

                botonConfirmar.disabled = true;
                botonConfirmar.classList.add('cursor-wait', 'opacity-75');
                actualizarEstado('Buscando coordenadas en OpenStreetMap...');

                try {
                    let resultado = null;

                    for (const [indice, consulta] of consultas.entries()) {
                        if (indice > 0) {
                            await esperar(1100);
                        }

                        resultado = await buscarCoordenadasOpenStreetMap(consulta);

                        if (resultado) {
                            break;
                        }
                    }

                    if (!resultado) {
                        actualizarEstado('No pudimos ubicar esa dirección. Probá con más datos o ubicá el pin manualmente.');
                        return;
                    }

                    ubicarPin(resultado.lat, resultado.lon, resultado.display_name);
                    actualizarEstado('Coordenadas cargadas con OpenStreetMap. Podés mover el pin para ajustar.');
                } catch (error) {
                    actualizarEstado('No se pudo consultar OpenStreetMap. Podés ubicar el pin manualmente.');
                } finally {
                    botonConfirmar.disabled = false;
                    botonConfirmar.classList.remove('cursor-wait', 'opacity-75');
                }
            });

            marcador.on('dragend', () => {
                const posicion = marcador.getLatLng();
                fijarCoordenadas(posicion.lat, posicion.lng);
                entradaProveedor.value = 'openstreetmap';
                actualizarEstado('Pin ajustado manualmente. Las coordenadas quedaron confirmadas.');
            });

            mapa.on('click', (evento) => {
                ubicarPin(evento.latlng.lat, evento.latlng.lng);
                actualizarEstado('Pin ubicado manualmente. Las coordenadas quedaron confirmadas.');
            });

            window.setTimeout(() => mapa.invalidateSize(), 250);
        } catch (error) {
            actualizarEstado('No se pudo cargar OpenStreetMap. Revisá la conexión a internet.');
            botonConfirmar.disabled = true;
            botonConfirmar.classList.add('cursor-not-allowed', 'opacity-50');
        }

        return;
    }

    try {
        const google = await cargarGoogleMaps(clave);
        const centroInicial = coordenadasIniciales();
        const tieneCoordenadas = entradaLatitud.value !== '' && entradaLongitud.value !== '';

        mapaElemento.classList.remove('flex', 'items-center', 'justify-center', 'text-center', 'text-sm', 'text-neutral-500');
        mapaElemento.classList.add('min-h-[320px]');
        mapaElemento.textContent = '';

        const mapa = new google.maps.Map(mapaElemento, {
            center: centroInicial,
            zoom: tieneCoordenadas ? 16 : 11,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
        });

        const marcador = new google.maps.Marker({
            map: mapa,
            position: centroInicial,
            draggable: true,
            visible: tieneCoordenadas,
        });

        const geocoder = new google.maps.Geocoder();
        const autocomplete = new google.maps.places.Autocomplete(entradaDireccion, {
            componentRestrictions: { country: 'ar' },
            fields: ['formatted_address', 'geometry', 'place_id', 'name'],
            types: ['geocode', 'establishment'],
        });

        const aplicarLugar = (lugar) => {
            if (!lugar?.geometry?.location) {
                actualizarEstado('No se encontraron coordenadas para esa dirección.');
                return;
            }

            const ubicacion = lugar.geometry.location;
            marcador.setPosition(ubicacion);
            marcador.setVisible(true);
            mapa.setCenter(ubicacion);
            mapa.setZoom(17);

            entradaDireccion.value = lugar.formatted_address || entradaDireccion.value;
            entradaDireccionNormalizada.value = lugar.formatted_address || entradaDireccion.value;
            entradaProveedor.value = 'google_maps';
            entradaPlaceId.value = lugar.place_id || '';
            fijarCoordenadas(ubicacion.lat(), ubicacion.lng());
            actualizarEstado('Coordenadas cargadas automáticamente. Podés mover el pin para ajustar.');
        };

        autocomplete.addListener('place_changed', () => {
            aplicarLugar(autocomplete.getPlace());
        });

        botonConfirmar.addEventListener('click', () => {
            const direccion = entradaDireccion.value.trim();

            if (!direccion) {
                actualizarEstado('Escribí una dirección para buscar coordenadas.');
                return;
            }

            actualizarEstado('Buscando coordenadas...');
            geocoder.geocode(
                {
                    address: direccion,
                    componentRestrictions: { country: 'AR' },
                },
                (resultados, estadoGeocoder) => {
                    if (estadoGeocoder !== 'OK' || !resultados?.[0]) {
                        actualizarEstado('No pudimos ubicar esa dirección. Probá con más datos.');
                        return;
                    }

                    aplicarLugar(resultados[0]);
                }
            );
        });

        marcador.addListener('dragend', () => {
            const posicion = marcador.getPosition();
            fijarCoordenadas(posicion.lat(), posicion.lng());
            entradaProveedor.value = entradaProveedor.value || 'google_maps';
            actualizarEstado('Pin ajustado manualmente. Las coordenadas quedaron confirmadas.');
        });

        mapa.addListener('click', (evento) => {
            marcador.setPosition(evento.latLng);
            marcador.setVisible(true);
            fijarCoordenadas(evento.latLng.lat(), evento.latLng.lng());
            entradaProveedor.value = entradaProveedor.value || 'google_maps';
            actualizarEstado('Pin ubicado manualmente. Las coordenadas quedaron confirmadas.');
        });
    } catch (error) {
        actualizarEstado('No se pudo cargar Google Maps. Revisá la clave configurada.');
        botonConfirmar.disabled = true;
        botonConfirmar.classList.add('cursor-not-allowed', 'opacity-50');
    }
});
