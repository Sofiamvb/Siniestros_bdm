<main>

    <section class="flex items-center justify-between bg-[#E8F0F7] px-[80px] py-[50px]" id="idHome">
        <div class="w-1/2 text-center">
            <img src="../Images/LandingImg.png" alt="Auto" class="w-[120%]">
            <p class="mt-5 text-[30px] font-medium text-[#16425B]">Gestión eficiente y control documentado.</p>
        </div>

        <div class="flex w-[40%] items-center justify-start">
            <div class="w-[560px] rounded-[35px] bg-[linear-gradient(145deg,#87b4c2,#b1b4af)] px-[30px] py-[40px] text-center text-white shadow-[0_10px_25px_rgba(0,0,0,0.25)]">
                <h2 class="m-0 text-[22px] font-semibold text-white">Auto</h2>
                <div class="my-[15px] mb-[30px] h-[6px] rounded-[10px] bg-[linear-gradient(to_right,#3A7CA5_0%,#81C3D7_50%,#81C3D7_100%)]"></div>

                <form class="form-shell" action="/cotizar" method="GET" id="formCotizarLanding">
                    <select name="marca" id="landingMarca"
                        class="input-field cursor-pointer rounded-[20px] px-[18px] py-[14px] text-[#666] shadow-[0_4px_8px_rgba(0,0,0,0.15)] focus:shadow-[0_0_0_2px_#D9DCD6]">
                        <option value="" disabled selected>Marca</option>
                    </select>

                    <select name="modelo" id="landingModelo" disabled
                        class="input-field cursor-pointer rounded-[20px] px-[18px] py-[14px] text-[#666] shadow-[0_4px_8px_rgba(0,0,0,0.15)] focus:shadow-[0_0_0_2px_#D9DCD6] disabled:opacity-50">
                        <option value="" disabled selected>Modelo</option>
                    </select>

                    <select name="anio" id="landingAnio" disabled
                        class="input-field cursor-pointer rounded-[20px] px-[18px] py-[14px] text-[#666] shadow-[0_4px_8px_rgba(0,0,0,0.15)] focus:shadow-[0_0_0_2px_#D9DCD6] disabled:opacity-50">
                        <option value="" disabled selected>Año</option>
                    </select>

                    <select name="version" id="landingVersion" disabled
                        class="input-field cursor-pointer rounded-[20px] px-[18px] py-[14px] text-[#666] shadow-[0_4px_8px_rgba(0,0,0,0.15)] focus:shadow-[0_0_0_2px_#D9DCD6] disabled:opacity-50">
                        <option value="" disabled selected>Versión</option>
                    </select>

                    <button type="submit" class="mt-[10px] cursor-pointer rounded-[25px] border-none bg-[#608fa3] p-[14px] text-[16px] font-bold text-white shadow-[0_4px_8px_rgba(0,0,0,0.2)] transition duration-300 ease-in-out hover:-translate-y-[3px] hover:shadow-[0_6px_12px_rgba(0,0,0,0.25)]">
                        Cotiza ahora
                    </button>
                </form>

                <script>
                (function () {
                    const marcaEl   = document.getElementById('landingMarca');
                    const modeloEl  = document.getElementById('landingModelo');
                    const anioEl    = document.getElementById('landingAnio');
                    const versionEl = document.getElementById('landingVersion');

                    function resetFrom(el, placeholder) {
                        el.innerHTML = `<option value="" disabled selected>${placeholder}</option>`;
                        el.disabled  = true;
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
                        resetFrom(anioEl,   'Año');
                        resetFrom(versionEl,'Versión');

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
                        resetFrom(anioEl,   'Año');
                        resetFrom(versionEl,'Versión');

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

                        fetch('/api/versiones?marca='  + encodeURIComponent(marcaEl.value) +
                              '&modelo=' + encodeURIComponent(modeloEl.value) +
                              '&anio='   + encodeURIComponent(anioEl.value))
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

    <div class="flex items-center justify-evenly bg-[#E8F0F7] px-[80px] py-[30px]">
        <img src="/img/GNPSeguros.png" class="h-[40px] object-contain opacity-[0.85] transition duration-300 hover:scale-105 hover:opacity-100">
        <img src="/img/QualitasSeguros.png" class="h-[40px] object-contain opacity-[0.85] transition duration-300 hover:scale-105 hover:opacity-100">
        <img src="/img/AXASeguros.png" class="h-[40px] object-contain opacity-[0.85] transition duration-300 hover:scale-105 hover:opacity-100">
        <img src="/img/BBVASeguros.png" class="h-[40px] object-contain opacity-[0.85] transition duration-300 hover:scale-105 hover:opacity-100">
        <img src="/img/HDISeguros.png" class="h-[40px] object-contain opacity-[0.85] transition duration-300 hover:scale-105 hover:opacity-100">
        <img src="/img/MapfreSeguros.png" class="h-[40px] object-contain opacity-[0.85] transition duration-300 hover:scale-105 hover:opacity-100">
    </div>

    <section class="border-t-[2px] border-t-[#16425B] bg-[linear-gradient(135deg,#F5F7FA_0%,#E8F0F7_50%,#D4E9F5_100%)] px-[80px] py-[60px]" id="idSobreNosotros">
        <div class="flex items-center justify-between gap-[40px]">
            <div class="w-full text-right text-[17px] leading-[1.6] text-[#333]">
                <h2 class="p-[20px] text-right text-[38px] text-[#16425B]">Sobre nosotros</h2>
                <p>
                    En Sistema Integral de Siniestros Automotrices (SISA) brindamos una
                    plataforma digital para la gestión y seguimiento de siniestros de autos.
                    Facilitamos a los clientes el control de autorizaciones, pagos y entrega
                    de unidades de forma clara y organizada.
                </p>
            </div>

            <div class="w-[35%] text-center">
                <img src="/img/AbtUs.png" alt="Protección auto" class="w-[80%] max-w-[250px] drop-shadow-[0_15px_25px_rgba(0,0,0,0.2)]">
            </div>
        </div>
    </section>

    <section class="border-t-[2px] border-t-[#16425B] bg-[linear-gradient(135deg,#F5F7FA_0%,#E8F0F7_50%,#D4E9F5_100%)]" id="idContacto">
        <div class="px-5 py-[50px] text-center">
            <img src="/img/CSupport.png" alt="Soporte" class="mb-[10px] inline-block w-[150px]">
            <p class="mx-auto max-w-[700px] text-[18px] font-medium text-[#222]">
                Trabajamos bajo nuestro compromiso de
                <strong>“Gestión eficiente y control documentado”</strong>,
                ofreciendo transparencia y confianza en cada etapa del proceso.
            </p>
        </div>

        <div class="border-t-[2px] border-t-[#16425B] bg-[#E8F0F7] px-5 py-[60px]">
            <div class="flex items-center justify-center gap-[40px]">
                <img src="/img/headset.png" alt="Atención" class="w-[90px] opacity-70">
                <div>
                    <p class="mb-[10px] text-[18px]">Te ayudamos a gestionar tu relación con la aseguradora.</p>
                    <span class="inline-block rounded-[20px] bg-[#D9DCD6] px-5 py-2 font-bold text-[#16425B]">81-3155-5540</span>
                </div>
            </div>
        </div>
    </section>

</main>