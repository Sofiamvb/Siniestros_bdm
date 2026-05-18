<main class="min-h-[calc(100vh-180px)] bg-[#e6e7e2] flex flex-col items-center pt-32 pb-24 px-6">

    <h1 class="text-[40px] md:text-[52px] font-bold text-[#111823] mb-8">
        SINIESTROS
    </h1>

    <!-- BARRA DE BÚSQUEDA -->
    <div class="relative w-full max-w-[540px]" id="searchWrapper">

        <div class="relative">
            <input
                type="text"
                id="searchInput"
                placeholder="Busca por No. reporte, placa o póliza"
                autocomplete="off"
                class="w-full h-[56px] rounded-full bg-[#9ca3af] px-6 pr-14 text-[16px] text-[#111823] placeholder-[#4a5568] shadow-sm outline-none focus:ring-2 focus:ring-[#111823] transition-all"
            >
            <span id="searchSpinner" class="hidden absolute right-[52px] top-1/2 -translate-y-1/2">
                <svg class="animate-spin h-4 w-4 text-[#111823]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
            </span>
            <button
                id="searchBtn"
                type="button"
                class="absolute right-2.5 top-1/2 -translate-y-1/2 flex h-9 w-9 items-center justify-center rounded-full bg-[#111823] text-white transition hover:bg-gray-800"
                aria-label="Buscar"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>

        <!-- DROPDOWN DE RESULTADOS -->
        <div
            id="searchDropdown"
            class="hidden absolute left-0 right-0 top-[60px] bg-white rounded-[18px] shadow-[0_8px_24px_rgba(0,0,0,0.18)] overflow-hidden z-50"
        >
            <div id="searchResults" class="overflow-y-auto"></div>
        </div>

    </div>

    <!-- MENSAJE DE ESTADO -->
    <p id="searchHint" class="mt-6 text-[14px] text-[#4a5568]">
        Escribe al menos 2 caracteres para buscar.
    </p>

</main>

<script>
(function () {
    const input    = document.getElementById('searchInput');
    const dropdown = document.getElementById('searchDropdown');
    const results  = document.getElementById('searchResults');
    const hint     = document.getElementById('searchHint');
    const spinner  = document.getElementById('searchSpinner');
    const btn      = document.getElementById('searchBtn');

    let debounceTimer = null;

    const ETIQUETAS = {
        siniestro: { texto: 'Siniestro', color: 'bg-[#1e40af] text-white' },
        placa:     { texto: 'Placa',     color: 'bg-[#065f46] text-white' },
        poliza:    { texto: 'Póliza',    color: 'bg-[#7c3aed] text-white' },
    };

    input.addEventListener('input', function () {
        const q = this.value.trim();
        clearTimeout(debounceTimer);

        if (q.length < 2) {
            cerrarDropdown();
            hint.textContent = 'Escribe al menos 2 caracteres para buscar.';
            return;
        }

        hint.textContent = '';
        debounceTimer = setTimeout(() => buscar(q), 300);
    });

    // El botón lupa dispara la búsqueda directamente (sin esperar el debounce)
    btn.addEventListener('click', function () {
        const q = input.value.trim();
        if (q.length >= 2) {
            clearTimeout(debounceTimer);
            buscar(q);
        } else {
            input.focus();
        }
    });

    document.addEventListener('click', function (e) {
        if (!document.getElementById('searchWrapper').contains(e.target)) {
            cerrarDropdown();
        }
    });

    async function buscar(termino) {
        spinner.classList.remove('hidden');

        try {
            const resp = await fetch(`/api/buscar-siniestros?q=${encodeURIComponent(termino)}`);
            const data = await resp.json();
            renderResultados(data, termino);
        } catch {
            hint.textContent = 'Error al conectar con el servidor.';
            cerrarDropdown();
        } finally {
            spinner.classList.add('hidden');
        }
    }

    function renderResultados(filas, termino) {
        if (!Array.isArray(filas) || filas.length === 0) {
            results.innerHTML = `
                <div class="px-5 py-4 text-[14px] text-[#6b7280] text-center">
                    Sin resultados para <strong>${escape(termino)}</strong>
                </div>`;
            abrirDropdown();
            return;
        }

        const grupos = { siniestro: [], placa: [], poliza: [] };
        filas.forEach(f => {
            const tipo = f.tipo_match || 'siniestro';
            if (grupos[tipo]) grupos[tipo].push(f);
        });

        let html = '';

        for (const [tipo, items] of Object.entries(grupos)) {
            if (items.length === 0) continue;

            const etiqueta = ETIQUETAS[tipo];
            html += `<div class="px-4 pt-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">${etiqueta.texto}s encontrados</div>`;

            items.forEach(item => {
                const subtitulo  = `${escape(item.marca)} ${escape(item.modelo)} ${escape(item.anio)}`;
                const textoColor = item.estatus_color || '#6b7280';
                const href       = construirHref(tipo, item);

                let tituloPrincipal = '';
                if (tipo === 'siniestro') {
                    tituloPrincipal = `Reporte: ${escape(item.numero_reporte)}`;
                } else if (tipo === 'placa') {
                    tituloPrincipal = `Placa: ${escape(item.placas)} &nbsp;·&nbsp; ${escape(item.numero_reporte)}`;
                } else {
                    tituloPrincipal = `Póliza: ${escape(item.numero_poliza)} &nbsp;·&nbsp; ${escape(item.numero_reporte)}`;
                }

                html += `
                <a href="${href}"
                   class="flex items-center gap-3 px-4 py-3 hover:bg-[#f3f4f6] transition-colors cursor-pointer no-underline">
                    <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-bold ${etiqueta.color}">
                        ${etiqueta.texto}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-[13px] font-semibold text-[#111823] truncate">${tituloPrincipal}</p>
                        <p class="text-[12px] text-[#6b7280] truncate">${subtitulo} · ${escape(item.duenio_nombre)}</p>
                    </div>
                    <span class="shrink-0 text-[11px] font-bold" style="color:${textoColor}">
                        ${escape(item.estatus)}
                    </span>
                </a>`;
            });
        }

        results.innerHTML = html;
        abrirDropdown();
    }

    function construirHref(tipo, item) {
        return `/siniestro?id=${item.siniestro_id}`;
    }

    function abrirDropdown() {
        dropdown.classList.remove('hidden');
        // Calcula el espacio real entre el dropdown y el borde inferior del viewport
        const rect    = dropdown.getBoundingClientRect();
        const margen  = 24; // px de respiro antes del footer
        const maxH    = Math.max(120, window.innerHeight - rect.top - margen);
        results.style.maxHeight = maxH + 'px';
    }

    function cerrarDropdown() {
        dropdown.classList.add('hidden');
        results.innerHTML = '';
        results.style.maxHeight = '';
    }

    function escape(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
})();
</script>
