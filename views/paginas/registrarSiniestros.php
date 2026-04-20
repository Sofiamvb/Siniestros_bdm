<main>
    <!-- Tabs bar -->
    <section class="tabs-bar" aria-label="Secciones de registro de siniestro">
        <button type="button" class="tab-btn tab-btn-active" data-section="aseguradora" id="tab-aseguradora">Aseguradora</button>
        <button type="button" class="tab-btn" data-section="auto" id="tab-auto">Auto</button>
        <button type="button" class="tab-btn" data-section="detalles" id="tab-detalles">Detalles</button>
    </section>

    <main class="flex justify-center px-[16px] pb-[28px] pt-[18px]">
        <form method="POST" action="/registrarSiniestros" enctype="multipart/form-data"
              class="register-card flex min-h-[430px] w-[min(860px,94vw)] flex-col rounded-[18px] bg-[#F5F7FA] px-[30px] pb-[20px] pt-[22px] shadow-[0_6px_12px_rgba(0,0,0,0.15)]"
              id="formSiniestro">

            <a href="/siniestrosAjustadores" class="w-fit text-[22px] font-bold text-[#16425B] no-underline">← Regresar</a>

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
            <input type="hidden" name="poliza_id"      id="hPolizaId"
                   value="<?= (int) ($post['poliza_id'] ?? 0) ?>">
            <input type="hidden" name="suma_asegurada" id="hSumaAsegurada"
                   value="<?= htmlspecialchars($post['suma_asegurada'] ?? '') ?>">
            <input type="hidden" name="terceros_json"  id="hTercerosJson"
                   value="<?= htmlspecialchars($post['terceros_json'] ?? '[]') ?>">

            <!-- ══ SECTION 1: Aseguradora ══ -->
            <div class="section-content flex-1" id="aseguradora-section">
                <div class="mx-auto mt-[44px] grid max-w-[700px] grid-cols-2 gap-x-[22px] gap-y-[16px]">

                    <div class="field-group">
                        <label class="field-label">Número de póliza</label>
                        <input id="inputNumPoliza" type="text" placeholder="Ej. POL-2024-000123"
                               class="input-field" autocomplete="off">
                    </div>

                    <div class="field-group col-span-full">
                        <label class="field-label">Nombre del ajustador</label>
                        <input type="text"
                               value="<?= htmlspecialchars($_SESSION['nombre'] ?? '') ?>"
                               class="input-field bg-gray-100 cursor-not-allowed"
                               readonly disabled>
                    </div>

                </div>
            </div>

            <!-- ══ SECTION 2: Auto ══ -->
            <div class="section-content hidden flex-1" id="auto-section">
                <div class="mx-auto mt-[44px] grid max-w-[700px] grid-cols-2 gap-x-[22px] gap-y-[16px]">

                    <div class="field-group">
                        <label class="field-label">Nombre del dueño</label>
                        <input id="autoDuenio" type="text" class="input-field bg-gray-100 cursor-not-allowed"
                               readonly placeholder="Se carga al validar la póliza">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Aseguradora</label>
                        <input id="inputCompania" type="text" class="input-field bg-gray-100 cursor-not-allowed"
                               readonly placeholder="Se carga al validar la póliza">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Conductor al momento del siniestro</label>
                        <input name="conductor" id="autoConductor" type="text"
                               placeholder="Nombre del conductor" class="input-field">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Marca</label>
                        <input id="autoMarca" type="text" class="input-field bg-gray-100 cursor-not-allowed"
                               readonly placeholder="Se carga automáticamente">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Modelo</label>
                        <input id="autoModelo" type="text" class="input-field bg-gray-100 cursor-not-allowed"
                               readonly placeholder="Se carga automáticamente">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Año</label>
                        <input id="autoAnio" type="text" class="input-field bg-gray-100 cursor-not-allowed"
                               readonly placeholder="Se carga automáticamente">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Placa</label>
                        <input id="autoPlaca" type="text" class="input-field bg-gray-100 cursor-not-allowed"
                               readonly placeholder="Se carga automáticamente">
                    </div>

                </div>
            </div>

            <!-- ══ SECTION 3: Detalles ══ -->
            <div class="section-content hidden flex-1" id="detalles-section">
                <div class="mx-auto mt-[44px] grid max-w-[700px] grid-cols-2 gap-x-[22px] gap-y-[16px]">

                    <div class="field-group col-span-full">
                        <label class="field-label">Fecha y hora del siniestro</label>
                        <input name="fecha_hora" id="detFechaHora" type="datetime-local"
                               max="<?= date('Y-m-d\TH:i') ?>"
                               class="input-field">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Latitud</label>
                        <input name="latitud" id="detLatitud" type="text"
                               placeholder="Ej. 25.6866" class="input-field">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Longitud</label>
                        <input name="longitud" id="detLongitud" type="text"
                               placeholder="Ej. -100.3161" class="input-field">
                    </div>

                    <div class="field-group col-span-full">
                        <label class="field-label">Vehículos involucrados</label>
                        <div class="relative">
                            <div id="tercerosLista" class="min-h-[80px] rounded-[14px] border border-[#ccc] bg-white p-[12px] text-[14px] text-[#555]">
                                <p class="text-[#aaa] italic" id="tercerosVacio">Sin terceros registrados</p>
                            </div>
                            <button type="button"
                                    class="floating-add-btn" id="openVehiculoModal"
                                    aria-label="Agregar vehículo involucrado">+</button>
                        </div>
                    </div>

                    <div class="field-group col-span-full">
                        <label class="field-label">Descripción de los hechos</label>
                        <textarea name="descripcion" id="detDescripcion"
                                  class="textarea-field min-h-[110px]"
                                  placeholder="Describe lo ocurrido en el siniestro"></textarea>
                    </div>

                    <div class="field-group col-span-full">
                        <label class="flex items-center gap-[10px] cursor-pointer select-none">
                            <input type="checkbox" name="perdida_total" id="chkPerdidaTotal" value="1"
                                   class="w-[18px] h-[18px] accent-[#16425B]">
                            <span class="field-label mb-0">Pérdida total (sin presupuesto de reparación)</span>
                        </label>
                    </div>

                    <div class="field-group col-span-full" id="presupuestoGroup">
                        <label class="field-label">Presupuesto de reparación (MXN)</label>
                        <input name="presupuesto" id="detPresupuesto" type="number"
                               min="0" step="0.01" placeholder="0.00" class="input-field">
                        <p id="presupuestoHint" class="mt-[4px] text-[12px] text-[#888]"></p>
                    </div>

                    <div class="field-group col-span-full">
                        <label class="field-label">Imágenes / Videos de evidencia</label>
                        <input name="evidencias[]" id="detalles-media" type="file"
                               accept="image/*,video/*" multiple class="file-input-field">
                    </div>

                    <div class="field-group col-span-full">
                        <label class="field-label">Vista previa</label>
                        <div class="media-carousel" id="mediaCarousel">
                            <button type="button" class="carousel-btn-base" id="carouselPrev" aria-label="Anterior">‹</button>
                            <div class="carousel-view-box" id="carouselView">
                                <p class="carousel-empty-text" id="carouselEmpty">No hay archivos cargados</p>
                            </div>
                            <button type="button" class="carousel-btn-base" id="carouselNext" aria-label="Siguiente">›</button>
                        </div>
                        <div class="carousel-meta-row">
                            <p class="carousel-counter-text" id="carouselCounter">0 / 0</p>
                            <button type="button" class="delete-media-btn-base" id="deleteMediaBtn">Eliminar actual</button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="mt-auto flex justify-end border-t border-t-[rgba(0,0,0,0.1)] pt-[24px]">
                <button type="button"
                        class="gradient-action-btn bg-[#16425B] px-[22px] py-[8px] text-[18px] shadow-[0_4px_4px_rgba(0,0,0,0.15)] hover:bg-[#16425B]"
                        id="continueBtn">Continuar →</button>
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
(function () {
    const hasErrors     = <?= !empty($errores) ? 'true' : 'false' ?>;
    const sumaAsegurada = parseFloat(document.getElementById('hSumaAsegurada').value) || 0;
    const perdidaTotal  = <?= !empty($post['perdida_total']) ? 'true' : 'false' ?>;

    if (!hasErrors) return;

    // Re-poblar campos de detalles con valores del POST
    const set = (id, val) => { const el = document.getElementById(id); if (el && val) el.value = val; };
    set('detFechaHora',   <?= json_encode($post['fecha_hora']   ?? '') ?>);
    set('detLatitud',     <?= json_encode($post['latitud']      ?? '') ?>);
    set('detLongitud',    <?= json_encode($post['longitud']     ?? '') ?>);
    set('autoConductor',  <?= json_encode($post['conductor']    ?? '') ?>);
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
        const hint        = document.getElementById('presupuestoHint');
        const presupuesto = document.getElementById('detPresupuesto');
        if (hint)        hint.textContent = 'Suma asegurada máx.: $' + sumaAsegurada.toLocaleString('es-MX', { minimumFractionDigits: 2 });
        if (presupuesto) presupuesto.max  = sumaAsegurada;
    }

    // Saltar directamente a la sección de detalles
    if (typeof showSection === 'function') {
        showSection('detalles');
    } else {
        window.addEventListener('DOMContentLoaded', () => showSection('detalles'));
    }
})();

/* ── Toggle pérdida total ── */
document.getElementById('chkPerdidaTotal').addEventListener('change', function () {
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
(function () {
    const terceros = [];

    function cerrarModal() {
        const modal = document.getElementById('vehiculoModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
        ['conductor','correo','marca','modelo','anio','placas','aseguradora','descripcion']
            .forEach(f => { document.getElementById('mv-' + f).value = ''; });
    }

    document.getElementById('guardarTerceroBtn').addEventListener('click', () => {
        const t = {
            conductor:   document.getElementById('mv-conductor').value.trim(),
            correo:      document.getElementById('mv-correo').value.trim(),
            marca:       document.getElementById('mv-marca').value.trim(),
            modelo:      document.getElementById('mv-modelo').value.trim(),
            anio:        document.getElementById('mv-anio').value.trim(),
            placas:      document.getElementById('mv-placas').value.trim(),
            aseguradora: document.getElementById('mv-aseguradora').value.trim(),
            descripcion: document.getElementById('mv-descripcion').value.trim(),
        };

        if (!t.marca || !t.placas) {
            Swal.fire({ icon: 'warning', title: 'Datos incompletos', text: 'Marca y placas son obligatorias.' });
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
