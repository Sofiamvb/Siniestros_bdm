<main class="min-h-screen bg-[#E8F0F7] px-[80px] py-[60px]">

    <div class="mx-auto max-w-[900px]">

        <h1 class="mb-[10px] text-[32px] font-bold text-[#16425B]">Cotizador de Seguro</h1>
        <div class="mb-[40px] h-[4px] w-[80px] rounded-full bg-[#3A7CA5]"></div>

        <?php if (!$vehiculo): ?>
        <!-- ── Formulario de búsqueda ── -->
        <div class="rounded-[30px] bg-white p-[40px] shadow-[0_8px_24px_rgba(0,0,0,0.1)]">
            <h2 class="mb-[24px] text-[20px] font-semibold text-[#333]">Selecciona tu vehículo</h2>

            <?php if (!empty($errores)): ?>
            <div class="mb-[20px] rounded-[12px] bg-red-50 p-[16px] text-red-700">
                <ul class="list-disc pl-[20px]">
                    <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <form method="POST" action="/cotizar" class="flex flex-col gap-[18px]" id="formCotizar">

                <div class="flex flex-col gap-[6px]">
                    <label class="text-[14px] font-medium text-[#555]">Marca</label>
                    <select name="marca" id="cotizarMarca" required
                        class="rounded-[14px] border border-[#ccc] px-[16px] py-[12px] text-[#333] focus:border-[#3A7CA5] focus:outline-none">
                        <option value="" disabled selected>Selecciona una marca</option>
                        <?php foreach ($marcas as $m): ?>
                        <option value="<?= htmlspecialchars($m) ?>"
                            <?= ($post['marca'] ?? '') === $m ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex flex-col gap-[6px]">
                    <label class="text-[14px] font-medium text-[#555]">Modelo</label>
                    <select name="modelo" id="cotizarModelo" required
                        class="rounded-[14px] border border-[#ccc] px-[16px] py-[12px] text-[#333] focus:border-[#3A7CA5] focus:outline-none disabled:opacity-50">
                        <option value="" disabled selected>Selecciona un modelo</option>
                    </select>
                </div>

                <div class="flex flex-col gap-[6px]">
                    <label class="text-[14px] font-medium text-[#555]">Año</label>
                    <select name="anio" id="cotizarAnio" required
                        class="rounded-[14px] border border-[#ccc] px-[16px] py-[12px] text-[#333] focus:border-[#3A7CA5] focus:outline-none disabled:opacity-50">
                        <option value="" disabled selected>Selecciona un año</option>
                    </select>
                </div>

                <div class="flex flex-col gap-[6px]">
                    <label class="text-[14px] font-medium text-[#555]">Versión</label>
                    <select name="version" id="cotizarVersion" required
                        class="rounded-[14px] border border-[#ccc] px-[16px] py-[12px] text-[#333] focus:border-[#3A7CA5] focus:outline-none disabled:opacity-50">
                        <option value="" disabled selected>Selecciona una versión</option>
                    </select>
                </div>

                <button type="submit"
                    class="mt-[8px] rounded-[25px] bg-[#3A7CA5] px-[30px] py-[14px] text-[16px] font-bold text-white shadow-[0_4px_12px_rgba(58,124,165,0.4)] transition duration-300 hover:-translate-y-[2px] hover:shadow-[0_6px_16px_rgba(58,124,165,0.5)]">
                    Ver precio
                </button>
            </form>
        </div>

        <?php else: ?>
        <!-- ── Resultado de cotización ── -->
        <div class="grid grid-cols-1 gap-[24px] md:grid-cols-2">

            <!-- Tarjeta vehículo -->
            <div class="rounded-[30px] bg-white p-[36px] shadow-[0_8px_24px_rgba(0,0,0,0.1)]">
                <p class="mb-[6px] text-[13px] font-semibold uppercase tracking-widest text-[#3A7CA5]">Vehículo cotizado</p>
                <h2 class="mb-[20px] text-[26px] font-bold text-[#16425B]">
                    <?= htmlspecialchars($vehiculo->marca) ?> <?= htmlspecialchars($vehiculo->modelo) ?>
                </h2>

                <div class="flex flex-col gap-[12px] text-[15px]">
                    <div class="flex justify-between border-b border-[#eee] pb-[10px]">
                        <span class="text-[#777]">Año</span>
                        <span class="font-semibold text-[#333]"><?= $vehiculo->anio ?></span>
                    </div>
                    <div class="flex justify-between border-b border-[#eee] pb-[10px]">
                        <span class="text-[#777]">Versión</span>
                        <span class="font-semibold text-[#333]"><?= htmlspecialchars($vehiculo->version) ?></span>
                    </div>
                    <div class="flex justify-between border-b border-[#eee] pb-[10px]">
                        <span class="text-[#777]">Tipo</span>
                        <span class="font-semibold text-[#333]"><?= htmlspecialchars($vehiculo->tipo_vehiculo) ?></span>
                    </div>
                    <div class="flex justify-between border-b border-[#eee] pb-[10px]">
                        <span class="text-[#777]">Pasajeros</span>
                        <span class="font-semibold text-[#333]"><?= $vehiculo->numero_pasajeros ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#777]">Cilindros</span>
                        <span class="font-semibold text-[#333]"><?= $vehiculo->cilindros ?></span>
                    </div>
                </div>
            </div>

            <!-- Tarjeta precio -->
            <div class="flex flex-col justify-between rounded-[30px] bg-[linear-gradient(145deg,#3A7CA5,#81C3D7)] p-[36px] text-white shadow-[0_8px_24px_rgba(58,124,165,0.35)]">
                <div>
                    <p class="mb-[6px] text-[13px] font-semibold uppercase tracking-widest text-white/80">Precio del seguro</p>
                    <p class="mb-[8px] text-[48px] font-bold leading-none">
                        $<?= number_format($vehiculo->precio_seguro, 2) ?>
                    </p>
                    <p class="text-[14px] text-white/70">MXN · Prima anual estimada</p>
                </div>

                <div class="mt-[30px] flex flex-col gap-[12px]">
                    <a href="/contratar?vehiculo_id=<?= $vehiculo->id ?>"
                        class="block rounded-[25px] bg-white py-[14px] text-center text-[16px] font-bold text-[#3A7CA5] shadow-[0_4px_10px_rgba(0,0,0,0.15)] transition duration-300 hover:-translate-y-[2px] hover:shadow-[0_6px_14px_rgba(0,0,0,0.2)]">
                        Contratar seguro
                    </a>
                    <a href="/cotizar"
                        class="block rounded-[25px] border border-white/50 py-[12px] text-center text-[14px] font-medium text-white transition hover:bg-white/10">
                        Cotizar otro vehículo
                    </a>
                </div>
            </div>

        </div>
        <?php endif; ?>

    </div>

</main>

<script>
(function () {
    const marcaEl   = document.getElementById('cotizarMarca');
    const modeloEl  = document.getElementById('cotizarModelo');
    const anioEl    = document.getElementById('cotizarAnio');
    const versionEl = document.getElementById('cotizarVersion');

    if (!marcaEl) return;

    // Valores preseleccionados al volver con errores
    const presel = {
        modelo:  <?= json_encode($post['modelo']  ?? '') ?>,
        anio:    <?= json_encode($post['anio']    ?? '') ?>,
        version: <?= json_encode($post['version'] ?? '') ?>,
    };

    function reset(el, label) {
        el.innerHTML = `<option value="" disabled selected>${label}</option>`;
        el.disabled  = true;
    }

    function populate(el, items, preselVal) {
        items.forEach(v => {
            const o = document.createElement('option');
            o.value = o.textContent = v;
            if (String(v) === String(preselVal)) o.selected = true;
            el.appendChild(o);
        });
        el.disabled = false;
    }

    function loadModelos(marca, chain) {
        reset(modeloEl, 'Selecciona un modelo');
        reset(anioEl,   'Selecciona un año');
        reset(versionEl,'Selecciona una versión');
        if (!marca) return;

        fetch('/api/modelos?marca=' + encodeURIComponent(marca))
            .then(r => r.json()).then(items => {
                populate(modeloEl, items, chain ? presel.modelo : null);
                if (chain && modeloEl.value) loadAnios(marca, modeloEl.value, true);
            });
    }

    function loadAnios(marca, modelo, chain) {
        reset(anioEl,    'Selecciona un año');
        reset(versionEl, 'Selecciona una versión');

        fetch('/api/anios?marca=' + encodeURIComponent(marca) + '&modelo=' + encodeURIComponent(modelo))
            .then(r => r.json()).then(items => {
                populate(anioEl, items, chain ? presel.anio : null);
                if (chain && anioEl.value) loadVersiones(marca, modelo, anioEl.value, true);
            });
    }

    function loadVersiones(marca, modelo, anio, chain) {
        reset(versionEl, 'Selecciona una versión');

        fetch('/api/versiones?marca=' + encodeURIComponent(marca) +
              '&modelo=' + encodeURIComponent(modelo) +
              '&anio='   + encodeURIComponent(anio))
            .then(r => r.json()).then(items => {
                populate(versionEl, items, chain ? presel.version : null);
            });
    }

    // Repoblar en cascada si venimos de POST con errores
    if (marcaEl.value) loadModelos(marcaEl.value, true);

    marcaEl.addEventListener('change',  () => loadModelos(marcaEl.value, false));
    modeloEl.addEventListener('change', () => loadAnios(marcaEl.value, modeloEl.value, false));
    anioEl.addEventListener('change',   () => loadVersiones(marcaEl.value, modeloEl.value, anioEl.value, false));
})();
</script>
