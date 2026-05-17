<main class="bg-[#d9d9d6] min-h-screen">
    <!-- Tabs bar -->
    <section class="mx-auto max-w-[980px] px-6 pt-10" aria-label="Secciones de registro de siniestro">
        <div class="tabs-bar">
            <button type="button" class="tab-btn tab-btn-active" data-section="aseguradora" id="tab-aseguradora">Aseguradora</button>
            <button type="button" class="tab-btn" data-section="auto" id="tab-auto">Auto</button>
            <button type="button" class="tab-btn" data-section="detalles" id="tab-detalles">Detalles</button>
        </div>
    </section>

    <section class="flex justify-center px-6 pb-10 pt-10">
        <form method="POST" action="/registrarSiniestros" enctype="multipart/form-data"
            class="flex min-h-[440px] w-full max-w-[740px] flex-col rounded-[18px] bg-[#f7f7f7] px-12 pb-10 pt-8 shadow-[0_6px_14px_rgba(0,0,0,0.16)]"
            id="formSiniestro">

            <a href="/siniestrosAjustadores" class="w-fit text-[18px] font-bold text-[#1a2538] no-underline hover:underline">Regresar</a>

            <?php if (!empty($errores)): ?>
                <div class="mt-[16px] rounded-[12px] bg-red-50 p-[14px] text-red-700 text-[14px]">
                    <ul class="list-disc pl-[18px]">
                        <?php foreach ($errores as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Hidden fields populated by JS after validating poliza -->
            <input type="hidden" name="poliza_id" id="hPolizaId"
                value="<?= (int) ($post['poliza_id'] ?? 0) ?>">
            <input type="hidden" name="suma_asegurada" id="hSumaAsegurada"
                value="<?= htmlspecialchars($post['suma_asegurada'] ?? '') ?>">
            <input type="hidden" name="terceros_json" id="hTercerosJson"
                value="<?= htmlspecialchars($post['terceros_json'] ?? '[]') ?>">

            <!-- ══ SECTION 1: Aseguradora ══ -->
            <div class="section-content flex-1" id="aseguradora-section">
                <div class="mx-auto mt-16 flex max-w-[650px] flex-col gap-6">

                    <div class="w-[310px]">
                        <label class="mb-3 block text-[16px] font-semibold text-[#1f2937]">
                            Número de póliza
                        </label>
                        <input id="inputNumPoliza" type="text" placeholder="Ej. POL-2024-000123"
                            class="h-[52px] w-full rounded-[16px] border-0 bg-[#b9c2d0] px-4 text-[15px] text-[#4b5563] placeholder:text-[#6b7280] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none"
                            autocomplete="off">
                    </div>

                    <div class="w-full">
                        <label class="mb-3 block text-[16px] font-semibold text-[#1f2937]">
                            Nombre del ajustador
                        </label>

                        <input type="text"
                            value="<?= htmlspecialchars($_SESSION['nombre'] ?? '') ?>"
                            class="h-[52px] w-full rounded-[16px] border-0 bg-[#b9c2d0] px-4 text-[15px] text-[#4b5563] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none cursor-not-allowed"
                            readonly disabled>
                    </div>

                </div>
            </div>

            <!-- ══ SECTION 2: Auto ══ -->
            <div class="section-content invisible absolute pointer-events-none flex-1" id="auto-section">
                <div class="mx-auto mt-12 grid max-w-[620px] grid-cols-2 gap-x-7 gap-y-4">

                    <div>
                        <label class="mb-2 block text-[14px] font-semibold text-[#1f2937]">Nombre del dueño</label>
                        <input id="autoDuenio" type="text"
                            class="h-[44px] w-full rounded-[14px] border-0 bg-[#b9c2d0] px-4 text-[14px] text-[#5b6470] placeholder:text-[#6b7280] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none cursor-not-allowed"
                            readonly placeholder="Nombre completo">
                    </div>

                    <div>
                        <label class="mb-2 block text-[14px] font-semibold text-[#1f2937]">Aseguradora</label>
                        <input id="inputCompania" type="text"
                            class="h-[44px] w-full rounded-[14px] border-0 bg-[#b9c2d0] px-4 text-[14px] text-[#5b6470] placeholder:text-[#6b7280] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none cursor-not-allowed"
                            readonly placeholder="Nombre de aseguradora">
                    </div>

                    <div>
                        <label class="mb-2 block text-[14px] font-semibold text-[#1f2937]">Conductor al momento</label>
                        <input name="conductor" id="autoConductor" type="text"
                            placeholder="Nombre completo del conductor"
                            class="h-[44px] w-full rounded-[14px] border-0 bg-[#b9c2d0] px-4 text-[14px] text-[#5b6470] placeholder:text-[#6b7280] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-[14px] font-semibold text-[#1f2937]">Marca</label>
                        <input id="autoMarca" type="text"
                            class="h-[44px] w-full rounded-[14px] border-0 bg-[#b9c2d0] px-4 text-[14px] text-[#5b6470] placeholder:text-[#6b7280] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none cursor-not-allowed"
                            readonly placeholder="Nombre de la marca">
                    </div>

                    <div>
                        <label class="mb-2 block text-[14px] font-semibold text-[#1f2937]">Modelo</label>
                        <input id="autoModelo" type="text"
                            class="h-[44px] w-full rounded-[14px] border-0 bg-[#b9c2d0] px-4 text-[14px] text-[#5b6470] placeholder:text-[#6b7280] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none cursor-not-allowed"
                            readonly placeholder="Nombre del modelo">
                    </div>

                    <div>
                        <label class="mb-2 block text-[14px] font-semibold text-[#1f2937]">Año</label>
                        <input id="autoAnio" type="text"
                            class="h-[44px] w-full rounded-[14px] border-0 bg-[#b9c2d0] px-4 text-[14px] text-[#5b6470] placeholder:text-[#6b7280] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none cursor-not-allowed"
                            readonly placeholder="Ej. 2000">
                    </div>

                    <div class="col-span-1">
                        <label class="mb-2 block text-[14px] font-semibold text-[#1f2937]">Placa</label>
                        <input id="autoPlaca" type="text"
                            class="h-[44px] w-full rounded-[14px] border-0 bg-[#b9c2d0] px-4 text-[14px] text-[#5b6470] placeholder:text-[#6b7280] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none cursor-not-allowed"
                            readonly placeholder="Ej. ABC-12-04">
                    </div>

                </div>
            </div>

            <!-- ══ SECTION 3: Detalles ══ -->
            <div class="section-content invisible absolute pointer-events-none flex-1" id="detalles-section">
                <div class="mx-auto mt-8 grid max-w-[620px] grid-cols-2 gap-x-7 gap-y-3">

                    <div>
                        <label class="mb-2 block text-[13px] font-semibold text-[#1f2937]">Fecha del siniestro</label>
                        <input name="fecha_hora" id="detFechaHora" type="datetime-local"
                            max="<?= date('Y-m-d\TH:i') ?>"
                            class="h-[42px] w-full rounded-[14px] border-0 bg-[#b9c2d0] px-4 text-[13px] text-[#5b6470] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-[13px] font-semibold text-[#1f2937]">Hora del siniestro</label>
                        <input type="text"
                            class="h-[42px] w-full rounded-[14px] border-0 bg-[#b9c2d0] px-4 text-[13px] text-[#5b6470] placeholder:text-[#6b7280] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none cursor-not-allowed"
                            placeholder="Nombre de la aseguradora" readonly disabled>
                    </div>

                    <div>
                        <label class="mb-2 block text-[13px] font-semibold text-[#1f2937]">Latitud</label>
                        <input name="latitud" id="detLatitud" type="text"
                            placeholder="Latitud"
                            class="h-[42px] w-full rounded-[14px] border-0 bg-[#b9c2d0] px-4 text-[13px] text-[#5b6470] placeholder:text-[#6b7280] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-[13px] font-semibold text-[#1f2937]">Longitud</label>
                        <input name="longitud" id="detLongitud" type="text"
                            placeholder="Longitud"
                            class="h-[42px] w-full rounded-[14px] border-0 bg-[#b9c2d0] px-4 text-[13px] text-[#5b6470] placeholder:text-[#6b7280] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none">
                    </div>

                    <div class="col-span-full">
                        <label class="mb-2 block text-[13px] font-semibold text-[#1f2937]">Vehículos involucrados</label>
                        <div class="relative">
                            <div id="tercerosLista" class="min-h-[42px] rounded-[14px] border-0 bg-[#b9c2d0] px-4 py-3 text-[13px] text-[#5b6470] shadow-[0_4px_8px_rgba(0,0,0,0.12)]">
                                <p class="italic text-[#6b7280]" id="tercerosVacio">Sin terceros registrados</p>
                            </div>
                            <button type="button"
                                class="absolute right-4 top-1/2 flex h-[24px] w-[24px] -translate-y-1/2 items-center justify-center rounded-full bg-[#031a33] text-[16px] font-bold text-white"
                                id="openVehiculoModal"
                                aria-label="Agregar vehículo involucrado">+</button>
                        </div>
                    </div>

                    <div class="col-span-full">
                        <label class="mb-2 block text-[13px] font-semibold text-[#1f2937]">Descripción de los hechos</label>
                        <textarea name="descripcion" id="detDescripcion"
                            class="min-h-[76px] w-full rounded-[14px] border-0 bg-[#b9c2d0] px-4 py-3 text-[13px] text-[#5b6470] placeholder:text-[#6b7280] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none resize-none"
                            placeholder="Describe lo sucedido en el siniestro"></textarea>
                    </div>

                    <div class="col-span-full">
                        <label class="flex cursor-pointer select-none items-center gap-2">
                            <input type="checkbox" name="perdida_total" id="chkPerdidaTotal" value="1"
                                class="h-[14px] w-[14px] accent-[#16425B]">
                            <span class="text-[12px] font-semibold text-[#1f2937]">Pérdida total (sin presupuesto de reparación)</span>
                        </label>
                    </div>

                    <div class="col-span-full" id="presupuestoGroup">
                        <label class="mb-2 block text-[13px] font-semibold text-[#1f2937]">Presupuesto de reparación</label>
                        <input name="presupuesto" id="detPresupuesto" type="number"
                            min="0" step="0.01" placeholder="0.0"
                            class="h-[42px] w-full rounded-[14px] border-0 bg-[#b9c2d0] px-4 text-[13px] text-[#5b6470] placeholder:text-[#6b7280] shadow-[0_4px_8px_rgba(0,0,0,0.12)] outline-none">
                        <p id="presupuestoHint" class="mt-2 text-[11px] text-[#8a8a8a]"></p>
                    </div>

                    <div class="col-span-full">
                        <label class="mb-2 block text-[13px] font-semibold text-[#1f2937]">Evidencia multimedia</label>
                        <input name="evidencias[]" id="detalles-media" type="file"
                            accept="image/*,video/*" multiple
                            class="block w-full rounded-[14px] border-0 bg-[#b9c2d0] px-3 py-2 text-[12px] text-[#5b6470] shadow-[0_4px_8px_rgba(0,0,0,0.12)] file:mr-3 file:rounded file:border-0 file:bg-white file:px-2 file:py-1 file:text-[11px] file:text-[#4b5563]">
                    </div>

                    <div class="col-span-full">
                        <label class="mb-2 block text-center text-[13px] font-semibold text-[#1f2937]">Vista previa</label>

                        <div class="flex items-center justify-center gap-4">
                            <button type="button" class="flex h-[24px] w-[24px] items-center justify-center rounded-full bg-[#031a33] text-white" id="carouselPrev" aria-label="Anterior">‹</button>

                            <div class="flex h-[150px] w-[240px] items-center justify-center rounded-[14px] bg-[#b9c2d0] text-center text-[12px] text-[#6b7280] shadow-[0_4px_8px_rgba(0,0,0,0.12)]" id="carouselView">
                                <p id="carouselEmpty">No hay archivos cargados</p>
                            </div>

                            <button type="button" class="flex h-[24px] w-[24px] items-center justify-center rounded-full bg-[#031a33] text-white" id="carouselNext" aria-label="Siguiente">›</button>
                        </div>

                        <div class="mt-2 flex flex-col items-center gap-2">
                            <p class="text-[11px] text-[#7b7b7b]" id="carouselCounter">0 / 0</p>
                            <button type="button"
                                class="rounded-full bg-[#031a33] px-6 py-2 text-[13px] font-semibold text-white shadow-[0_4px_8px_rgba(0,0,0,0.18)]"
                                id="deleteMediaBtn">
                                Eliminar actual
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="mt-auto flex justify-end pt-12">
                <button type="button"
                    class="flex items-center gap-3 rounded-full bg-[#031a33] px-8 py-4 text-[17px] font-bold text-white shadow-[0_6px_12px_rgba(0,0,0,0.18)] hover:opacity-90"
                    id="continueBtn">
                    Continuar
                    <span class="text-[22px] leading-none">→</span>
                </button>
            </div>

        </form>
</main>

<!-- ══ Modal: Vehículo involucrado ══ -->
<div class="supervisor-overlay" id="vehiculoModal" aria-hidden="true">
    <div class="supervisor-panel max-h-[90vh] max-w-[660px] px-[24px] pb-[24px] pt-[20px]"
        role="dialog" aria-modal="true" aria-labelledby="vehiculoModalTitle">
        <button type="button" class="modal-close-round-lg" id="closeVehiculoModal" aria-label="Cerrar modal">x</button>
        <h2 class="supervisor-title text-[34px]" id="vehiculoModalTitle">Vehículo involucrado</h2>
        <div class="supervisor-divider"></div>

        <div class="mx-auto max-w-[500px]">
            <div class="supervisor-form-group">
                <label class="field-label">Nombre del conductor</label>
                <input id="mv-conductor" type="text" placeholder="Nombre del conductor" class="supervisor-input">
            </div>
            <div class="supervisor-form-group">
                <label class="field-label">Correo electrónico</label>
                <input id="mv-correo" type="email" placeholder="correo@ejemplo.com" class="supervisor-input">
            </div>
            <div class="supervisor-form-group">
                <label class="field-label">Marca</label>
                <input id="mv-marca" type="text" placeholder="Marca del vehículo" class="supervisor-input">
            </div>
            <div class="split-row">
                <div class="supervisor-form-group flex-1">
                    <label class="field-label">Modelo</label>
                    <input id="mv-modelo" type="text" placeholder="Nombre del modelo" class="supervisor-input">
                </div>
                <div class="supervisor-form-group flex-1">
                    <label class="field-label">Año</label>
                    <input id="mv-anio" type="text" placeholder="Ej. 2018" class="supervisor-input">
                </div>
            </div>
            <div class="supervisor-form-group">
                <label class="field-label">Placas</label>
                <input id="mv-placas" type="text" placeholder="Número de placas" class="supervisor-input">
            </div>
            <div class="supervisor-form-group">
                <label class="field-label">Aseguradora</label>
                <input id="mv-aseguradora" type="text" placeholder="Nombre de la aseguradora" class="supervisor-input">
            </div>
            <div class="supervisor-form-group">
                <label class="field-label">Descripción de daños</label>
                <textarea id="mv-descripcion" placeholder="Descripción" class="supervisor-input min-h-[90px] resize-y"></textarea>
            </div>
            <div class="mt-[8px] flex justify-end">
                <button type="button" id="guardarTerceroBtn"
                    class="gradient-action-btn bg-[#16425B] px-[20px] py-[9px] text-[15px] shadow-[0_4px_8px_rgba(0,0,0,0.18)] hover:bg-[#16425B]">
                    Agregar
                </button>
            </div>
        </div>
    </div>
</div>
</main>

<script>
    /* ── Re-poblar al volver con errores de POST ── */
    (function() {
        const hasErrors = <?= !empty($errores) ? 'true' : 'false' ?>;
        const sumaAsegurada = parseFloat(document.getElementById('hSumaAsegurada').value) || 0;
        const perdidaTotal = <?= !empty($post['perdida_total']) ? 'true' : 'false' ?>;

        if (!hasErrors) return;

        // Re-poblar campos de detalles con valores del POST
        const set = (id, val) => {
            const el = document.getElementById(id);
            if (el && val) el.value = val;
        };
        set('detFechaHora', <?= json_encode($post['fecha_hora']   ?? '') ?>);
        set('detLatitud', <?= json_encode($post['latitud']      ?? '') ?>);
        set('detLongitud', <?= json_encode($post['longitud']     ?? '') ?>);
        set('autoConductor', <?= json_encode($post['conductor']    ?? '') ?>);
        set('detDescripcion', <?= json_encode($post['descripcion']  ?? '') ?>);
        set('detPresupuesto', <?= json_encode($post['presupuesto']  ?? '') ?>);

        // Restaurar checkbox pérdida total
        if (perdidaTotal) {
            const chk = document.getElementById('chkPerdidaTotal');
            if (chk) chk.checked = true;
            const group = document.getElementById('presupuestoGroup');
            if (group) group.classList.add('hidden');
        }

        // Mostrar hint de suma asegurada
        if (sumaAsegurada > 0) {
            const hint = document.getElementById('presupuestoHint');
            const presupuesto = document.getElementById('detPresupuesto');
            if (hint) hint.textContent = 'Suma asegurada máx.: $' + sumaAsegurada.toLocaleString('es-MX', {
                minimumFractionDigits: 2
            });
            if (presupuesto) presupuesto.max = sumaAsegurada;
        }

        // Saltar directamente a la sección de detalles
        if (typeof showSection === 'function') {
            showSection('detalles');
        } else {
            window.addEventListener('DOMContentLoaded', () => showSection('detalles'));
        }
    })();

    /* ── Toggle pérdida total ── */
    document.getElementById('chkPerdidaTotal').addEventListener('change', function() {
        const group = document.getElementById('presupuestoGroup');
        const input = document.getElementById('detPresupuesto');
        if (this.checked) {
            group.classList.add('hidden');
            input.value = '';
        } else {
            group.classList.remove('hidden');
        }
    });

    /* ── Terceros involucrados ── */
    (function() {
        const terceros = [];

        function cerrarModal() {
            const modal = document.getElementById('vehiculoModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
            ['conductor', 'correo', 'marca', 'modelo', 'anio', 'placas', 'aseguradora', 'descripcion']
            .forEach(f => {
                document.getElementById('mv-' + f).value = '';
            });
        }

        document.getElementById('guardarTerceroBtn').addEventListener('click', () => {
            const t = {
                conductor: document.getElementById('mv-conductor').value.trim(),
                correo: document.getElementById('mv-correo').value.trim(),
                marca: document.getElementById('mv-marca').value.trim(),
                modelo: document.getElementById('mv-modelo').value.trim(),
                anio: document.getElementById('mv-anio').value.trim(),
                placas: document.getElementById('mv-placas').value.trim(),
                aseguradora: document.getElementById('mv-aseguradora').value.trim(),
                descripcion: document.getElementById('mv-descripcion').value.trim(),
            };

            if (!t.marca || !t.placas) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Datos incompletos',
                    text: 'Marca y placas son obligatorias.'
                });
                return;
            }

            terceros.push(t);
            document.getElementById('hTercerosJson').value = JSON.stringify(terceros);
            renderTerceros();
            cerrarModal();
        });

        function renderTerceros() {
            const lista = document.getElementById('tercerosLista');
            if (terceros.length === 0) {
                lista.innerHTML = '<p class="text-[#aaa] italic">Sin terceros registrados</p>';
                return;
            }
            lista.innerHTML = terceros.map((t, i) => `
            <div class="flex items-center justify-between rounded-[10px] bg-white border border-[#ddd] px-[12px] py-[8px] mb-[8px]">
                <span class="text-[14px] font-medium text-[#333]">${t.marca} · ${t.placas}</span>
                <button type="button" onclick="removeTercero(${i})"
                        class="text-[12px] text-red-500 hover:underline">Eliminar</button>
            </div>
        `).join('');
        }

        window.removeTercero = function(i) {
            terceros.splice(i, 1);
            document.getElementById('hTercerosJson').value = JSON.stringify(terceros);
            renderTerceros();
        };
    })();
</script>