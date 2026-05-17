<main class="bg-[#ececec]">

    <section id="idHome" class="relative overflow-hidden bg-[#031a33] min-h-screen px-6 md:px-10 lg:px-16 py-10">

        
        <div class="absolute top-[110px] left-[40%] w-[50px] h-[50px] rounded-full bg-[#25385d] opacity-80"></div>
        <div class="absolute -bottom-28 -right-24 w-[430px] h-[430px] rounded-full bg-[#25385d] opacity-80"></div>

        <div class="relative z-10 mt-10 lg:mt-16 flex flex-col lg:flex-row items-center justify-between gap-10">
            
            <div class="w-full lg:w-1/2 flex justify-center">
                <img src="/img/car de lado.png" alt="Auto" class="w-full max-w-[650px] object-contain">
            </div>

            <div class="w-full lg:w-1/2 flex justify-center lg:justify-center">
                <div class="w-full max-w-[360px] bg-[#5d7697] rounded-[28px] shadow-xl px-7 py-6">
                    <h2 class="text-center text-white text-3xl font-bold uppercase">Auto</h2>
                    <div class="w-full h-[4px] bg-white rounded-full mt-2 mb-6"></div>

                    <form action="/cotizar" method="GET" id="formCotizarLanding" class="flex flex-col gap-4">
                        <select name="marca" id="landingMarca"
                            class="w-full h-11 rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-600 focus:outline-none">
                            <option value="" disabled selected>Marca</option>
                        </select>

                        <select name="modelo" id="landingModelo" disabled
                            class="w-full h-11 rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-600 focus:outline-none disabled:opacity-50">
                            <option value="" disabled selected>Modelo</option>
                        </select>

                        <select name="anio" id="landingAnio" disabled
                            class="w-full h-11 rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-600 focus:outline-none disabled:opacity-50">
                            <option value="" disabled selected>Año</option>
                        </select>

                        <select name="version" id="landingVersion" disabled
                            class="w-full h-11 rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-600 focus:outline-none disabled:opacity-50">
                            <option value="" disabled selected>Versión</option>
                        </select>

                        <button type="submit"
                            class="mx-auto mt-3 bg-[#1f2f4a] hover:bg-[#17243a] text-white font-semibold rounded-full px-8 py-3 shadow-md transition">
                            Cotiza ahora
                        </button>
                    </form>
                    <script>
                        (function() {
                            const marcaEl = document.getElementById('landingMarca');
                            const modeloEl = document.getElementById('landingModelo');
                            const anioEl = document.getElementById('landingAnio');
                            const versionEl = document.getElementById('landingVersion');

                            function resetFrom(el, placeholder) {
                                el.innerHTML = `<option value="" disabled selected>${placeholder}</option>`;
                                el.disabled = true;
                            }

                            fetch('/api/marcas').then(r => r.json()).then(marcas => {
                                marcas.forEach(m => {
                                    const o = document.createElement('option');
                                    o.value = o.textContent = m;
                                    marcaEl.appendChild(o);
                                });
                            });

                            marcaEl.addEventListener('change', () => {
                                resetFrom(modeloEl, 'Modelo');
                                resetFrom(anioEl, 'Año');
                                resetFrom(versionEl, 'Versión');

                                fetch('/api/modelos?marca=' + encodeURIComponent(marcaEl.value))
                                    .then(r => r.json()).then(items => {
                                        items.forEach(v => {
                                            const o = document.createElement('option');
                                            o.value = o.textContent = v;
                                            modeloEl.appendChild(o);
                                        });
                                        modeloEl.disabled = false;
                                    });
                            });

                            modeloEl.addEventListener('change', () => {
                                resetFrom(anioEl, 'Año');
                                resetFrom(versionEl, 'Versión');

                                fetch('/api/anios?marca=' + encodeURIComponent(marcaEl.value) +
                                        '&modelo=' + encodeURIComponent(modeloEl.value))
                                    .then(r => r.json()).then(items => {
                                        items.forEach(v => {
                                            const o = document.createElement('option');
                                            o.value = o.textContent = v;
                                            anioEl.appendChild(o);
                                        });
                                        anioEl.disabled = false;
                                    });
                            });

                            anioEl.addEventListener('change', () => {
                                resetFrom(versionEl, 'Versión');

                                fetch('/api/versiones?marca=' + encodeURIComponent(marcaEl.value) +
                                        '&modelo=' + encodeURIComponent(modeloEl.value) +
                                        '&anio=' + encodeURIComponent(anioEl.value))
                                    .then(r => r.json()).then(items => {
                                        items.forEach(v => {
                                            const o = document.createElement('option');
                                            o.value = o.textContent = v;
                                            versionEl.appendChild(o);
                                        });
                                        versionEl.disabled = false;
                                    });
                            });
                        })();
                    </script>
                </div>
            </div>
    </section>

    <div class="bg-[#FFFFFF] px-6 md:px-10 lg:px-16 py-16">
        <div class="flex flex-wrap justify-center items-center gap-5 md:gap-8">
            <div class="w-[100px] md:w-[120px] h-[56px] md:h-[68px] bg-white border border-slate-400 rounded-lg shadow-sm flex items-center justify-center p-3">
                <img src="/img/GNPSeguros.png" class="max-w-full max-h-full object-contain" alt="GNP">
            </div>
            <div class="w-[100px] md:w-[120px] h-[56px] md:h-[68px] bg-white border border-slate-400 rounded-lg shadow-sm flex items-center justify-center p-3">
                <img src="/img/QualitasSeguros.png" class="max-w-full max-h-full object-contain" alt="Qualitas">
            </div>
            <div class="w-[100px] md:w-[120px] h-[56px] md:h-[68px] bg-white border border-slate-400 rounded-lg shadow-sm flex items-center justify-center p-3">
                <img src="/img/BBVASeguros.png" class="max-w-full max-h-full object-contain" alt="BBVA">
            </div>
            <div class="w-[100px] md:w-[120px] h-[56px] md:h-[68px] bg-white border border-slate-400 rounded-lg shadow-sm flex items-center justify-center p-3">
                <img src="/img/AXASeguros.png" class="max-w-full max-h-full object-contain" alt="AXA">
            </div>
            <div class="w-[100px] md:w-[120px] h-[56px] md:h-[68px] bg-white border border-slate-400 rounded-lg shadow-sm flex items-center justify-center p-3">
                <img src="/img/HDISeguros.png" class="max-w-full max-h-full object-contain" alt="HDI">
            </div>
        </div>
    </div>

    <section id="idSobreNosotros" class="bg-[#FFFFFF] px-6 md:px-10 lg:px-16 py-6">
        <div class="max-w-[1200px] mx-auto grid grid-cols-1 md:grid-cols-[1fr_1.6fr] gap-5">
            <div class="bg-[#031a33] rounded-2xl shadow-lg p-8 md:p-10 min-h-[300px] flex flex-col justify-center">
                <h2 class="text-white text-2xl md:text-4xl font-bold uppercase mb-6">Sobre nosotros</h2>
                <p class="text-white text-base md:text-lg leading-relaxed">
                    En Sistema Integral de Siniestros Automotrices (SISA) brindamos una
                    plataforma digital para la gestión y seguimiento de siniestros de autos.
                    Facilitamos a los clientes el control de autorizaciones, pagos y entrega
                    de unidades de forma clara y organizada.
                </p>
            </div>

            <div class="bg-[#031a33] rounded-2xl shadow-lg p-6 min-h-[300px] flex items-center justify-center">
                <img src="/img/CAR.png" alt="Protección auto" class="w-full max-w-[520px] object-contain">
            </div>
        </div>
    </section>

    <section id="idContacto" class="bg-[#FFFFFF] px-6 md:px-10 lg:px-16 pt-4 pb-16">
        <div class="max-w-[1200px] mx-auto bg-[#031a33] rounded-2xl shadow-lg min-h-[250px] flex flex-col items-center justify-center text-center px-8 py-12">
            <img src="/img/CSupport.png" alt="Soporte" class="w-24 h-24 object-contain mb-8">
            <p class="text-white text-base md:text-2xl leading-relaxed max-w-[760px]">
                Trabajamos bajo nuestro compromiso de
                <strong>“Gestión eficiente y control documentado”</strong>,
                ofreciendo transparencia y confianza en cada etapa del proceso.
            </p>
        </div>
    </section>

</main>