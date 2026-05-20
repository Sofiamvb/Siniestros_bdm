<section class="bg-white px-6 py-5">
    <div class="mx-auto flex max-w-[1280px] flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

        <form method="GET" action="/siniestrosSupervisores"
              class="flex flex-wrap items-end gap-4" aria-label="Rango de fechas para siniestros">
            <div>
                <label for="fechaInicio" class="mb-1 block text-[15px] font-bold text-[#0d1b2a]">Desde</label>
                <input type="date" id="fechaInicio" name="desde"
                    value="<?= htmlspecialchars($_GET['desde'] ?? '') ?>"
                    class="h-[46px] w-[160px] rounded-[18px] border-0 bg-[#a9b5c3] px-4 text-[14px] text-[#263241] shadow-[0_4px_8px_rgba(0,0,0,0.22)] outline-none">
            </div>

            <div>
                <label for="fechaFin" class="mb-1 block text-[15px] font-bold text-[#0d1b2a]">Hasta</label>
                <input type="date" id="fechaFin" name="hasta"
                    value="<?= htmlspecialchars($_GET['hasta'] ?? '') ?>"
                    class="h-[46px] w-[160px] rounded-[18px] border-0 bg-[#a9b5c3] px-4 text-[14px] text-[#263241] shadow-[0_4px_8px_rgba(0,0,0,0.22)] outline-none">
            </div>

            <button type="submit"
                class="h-[46px] rounded-full bg-[#0b2030] px-9 text-[15px] font-bold text-white shadow-[0_4px_8px_rgba(0,0,0,0.25)] transition hover:bg-[#142b3f]">
                Filtrar
            </button>
            <?php if (!empty($_GET['desde']) || !empty($_GET['hasta'])): ?>
                <a href="/siniestrosSupervisores"
                    class="h-[46px] flex items-center rounded-full border border-[#0b2030] px-6 text-[14px] font-bold text-[#0b2030] no-underline transition hover:bg-gray-100">
                    Limpiar
                </a>
            <?php endif; ?>
        </form>

        <div class="flex flex-1 items-end justify-center gap-4 lg:justify-end">
            <div class="relative w-full max-w-[430px]">
                <input type="text"
                    placeholder="Busca aquí"
                    class="h-[46px] w-full rounded-[18px] border-0 bg-[#a9b5c3] px-5 pr-12 text-[15px] text-[#263241] shadow-[0_4px_8px_rgba(0,0,0,0.22)] outline-none placeholder:text-[#5f6c7a]">

                <span class="absolute right-4 top-1/2 flex h-5 w-5 -translate-y-1/2 items-center justify-center rounded-full text-[11px] text-white">
                    <img src="/img/lupa.png" alt="Lupa buscador">
                </span>
            </div>

            <a href="/register/ajustadores"
                class="flex h-[46px] items-center justify-center rounded-full bg-[#0b2030] px-8 text-[15px] font-bold text-white no-underline shadow-[0_4px_8px_rgba(0,0,0,0.25)] transition hover:bg-[#142b3f]">
                Registrar siniestro
            </a>

            <a href="/buscadorSiniestros"
                class="flex h-[46px] items-center justify-center rounded-full border-2 border-[#0b2030] px-8 text-[15px] font-bold text-[#0b2030] no-underline shadow-[0_4px_8px_rgba(0,0,0,0.15)] transition hover:bg-[#0b2030] hover:text-white">
                Buscar siniestro
            </a>
        </div>

    </div>
</section>

<section class="bg-[#e3e4df] px-6 pb-20 pt-10">
    <div class="mx-auto max-w-[1280px] text-center">
        <h1 class="text-[34px] font-extrabold text-[#0d1b2a]">
            ¡Bienvenido, <?= htmlspecialchars($_SESSION['nombre'] ?? '') ?>!
        </h1>
        <p class="mt-2 text-[16px] text-[#0d1b2a]">
            Estos son todos los siniestros registrados.
        </p>

        <?php if (empty($siniestros)): ?>
            <div class="flex w-full flex-col items-center justify-center py-[60px] text-[#888]">
                <p class="text-[18px]">No hay siniestros registrados aún.</p>
            </div>
        <?php else: ?>
        <div class="mt-12 grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3 lg:gap-20">
            <?php foreach ($siniestros as $siniestro): ?>
                <?php
                $fechaHora = $siniestro['fecha_hora_siniestro'] ?? '';
                $fecha     = $fechaHora ? date('d/m/Y', strtotime($fechaHora)) : '—';
                $hora      = $fechaHora ? date('H:i',   strtotime($fechaHora)) : '—';
                $dataJson  = htmlspecialchars(json_encode([
                    'numero_reporte'   => $siniestro['numero_reporte']    ?? '',
                    'compania'         => $siniestro['compania']          ?? '',
                    'numero_poliza'    => $siniestro['numero_poliza']     ?? '',
                    'fecha_inicio'     => $siniestro['fecha_inicio']      ?? '',
                    'fecha_fin'        => $siniestro['fecha_fin']         ?? '',
                    'ajustador_nombre' => $siniestro['ajustador_nombre']  ?? '',
                    'duenio_nombre'    => $siniestro['duenio_nombre']     ?? '',
                    'conductor'        => $siniestro['conductor_momento'] ?? '',
                    'marca'            => $siniestro['marca']             ?? '',
                    'modelo'           => $siniestro['modelo']            ?? '',
                    'anio'             => $siniestro['anio']              ?? '',
                    'placas'           => $siniestro['placas']            ?? '',
                    'fecha'            => $fecha,
                    'hora'             => $hora,
                    'latitud'          => $siniestro['latitud']           ?? '',
                    'longitud'         => $siniestro['longitud']          ?? '',
                    'descripcion'      => $siniestro['descripcion_hechos'] ?? '',
                    'dictamen'         => $siniestro['dictamen_supervisor'] ?? '',
                    'presupuesto'      => $siniestro['perdida_total'] ? 'Pérdida total' : ('$' . number_format((float)($siniestro['presupuesto_reparacion'] ?? 0), 2)),
                ]), ENT_QUOTES);
                ?>
                <div class="mx-auto w-full max-w-[260px] overflow-hidden rounded-b-[18px] bg-white text-left shadow-[0_8px_14px_rgba(0,0,0,0.25)]"
                     data-siniestro="<?= $dataJson ?>">

                    <div class="relative h-[175px] w-full overflow-hidden">
                        <img src="<?= $siniestro['primera_evidencia'] ?>" alt="Siniestro"
                            class="h-full w-full object-cover">
                        <?php if ($siniestro['es_mio']): ?>
                            <span class="absolute left-2 top-2 rounded-full bg-[#0b2030] px-2 py-0.5 text-[10px] font-bold text-white shadow">
                                Asignado a mí
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="px-4 py-5 text-[12px] font-bold leading-[1.25] text-black">
                        <p>Dueño: <?= htmlspecialchars($siniestro['duenio_nombre'] ?? '') ?></p>
                        <p>Marca: <?= htmlspecialchars($siniestro['marca'] ?? '') ?></p>
                        <p>Placas: <?= htmlspecialchars($siniestro['placas'] ?? '') ?></p>
                        <p>Aseguradora: <?= htmlspecialchars($siniestro['compania'] ?? '') ?></p>
                        <p>Reporte: <?= htmlspecialchars($siniestro['numero_reporte'] ?? '') ?></p>

                        <br>

                        <p>Ajustador: <?= htmlspecialchars($siniestro['ajustador_nombre'] ?? '') ?></p>
                        <p>Estado:
                            <span style="color: <?= htmlspecialchars($siniestro['estatus_color'] ?? '#000') ?>">
                                <?= htmlspecialchars($siniestro['estatus'] ?? '') ?>
                            </span>
                        </p>

                        <div class="mt-2 flex justify-end gap-1">
                            <a href="/siniestro?id=<?= (int)$siniestro['id'] ?>" class="p-0">
                                <img src="/img/seeall.png" alt="Ver detalle" class="h-[16px] w-[16px] object-contain">
                            </a>
                            <a href="/chat?siniestro_id=<?= (int)$siniestro['id'] ?>" class="p-0">
                                <img src="/img/comments.png" alt="Chat" class="h-[16px] w-[16px] object-contain">
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<div id="detailsModal" class="modal-overlay">
    <div class="details-modal-panel">
        <div class="details-header-blue">
            <button class="details-tab-btn details-tab-btn-blue details-tab-active-blue" data-tab="aseguradora" onclick="switchDetailsTab('aseguradora')">Aseguradora</button>
            <button class="details-tab-btn details-tab-btn-blue" data-tab="auto" onclick="switchDetailsTab('auto')">Auto</button>
            <button class="details-tab-btn details-tab-btn-blue" data-tab="detalles" onclick="switchDetailsTab('detalles')">Detalles</button>
        </div>

        <div class="details-body">
            <div class="details-section details-section-active" id="aseguradora-section">
                <div class="details-field"><label class="field-label-sm">Aseguradora</label><input type="text" class="input-field-soft" placeholder="Nombre de la aseguradora" readonly></div>
                <div class="details-field"><label class="field-label-sm">Número de póliza</label><input type="text" class="input-field-soft" placeholder="Número de poliza" readonly></div>
                <div class="details-field"><label class="field-label-sm">Tipo de seguro</label><input type="text" class="input-field-soft" placeholder="Tipo de seguro" readonly></div>
                <div class="details-field"><label class="field-label-sm">Nombre del ajustador</label><input type="text" class="input-field-soft" placeholder="Nombre del ajustador" readonly></div>
            </div>

            <div class="details-section details-section-hidden" id="auto-section">
                <div class="details-field"><label class="field-label-sm">Nombre del dueño</label><input type="text" class="input-field-soft" placeholder="Nombre del dueño del vehículo" readonly></div>
                <div class="details-field"><label class="field-label-sm">Correo electrónico</label><input type="text" class="input-field-soft" placeholder="Ejemplo@correo.com" readonly></div>
                <div class="details-field"><label class="field-label-sm">Marca</label><input type="text" class="input-field-soft" placeholder="Marca del vehículo" readonly></div>
                <div class="split-row-lg details-field">
                    <div class="split-col"><label class="field-label-sm">Modelo</label><input type="text" class="input-field-soft" placeholder="Modelo del vehículo" readonly></div>
                    <div class="split-col"><label class="field-label-sm">Año</label><input type="text" class="input-field-soft" placeholder="Año del vehículo" readonly></div>
                </div>
                <div class="details-field"><label class="field-label-sm">Número de placas</label><input type="text" class="input-field-soft" placeholder="Número de placas" readonly></div>
            </div>

            <div class="details-section details-section-hidden" id="detalles-section">
                <div class="details-field"><label class="field-label-sm">Fecha del siniestro</label><input type="text" class="input-field-soft" placeholder="Fecha del siniestro" readonly></div>
                <div class="details-field"><label class="field-label-sm">Hora del siniestro</label><input type="text" class="input-field-soft" placeholder="00:00:00" readonly></div>
                <div class="details-field"><label class="field-label-sm">Ubicación</label></div>
                <div class="split-row-lg details-field">
                    <div class="split-col"><label class="field-label-sm">Latitud</label><input type="text" class="input-field-soft" placeholder="Latitud" readonly></div>
                    <div class="split-col"><label class="field-label-sm">Longitud</label><input type="text" class="input-field-soft" placeholder="Longitud" readonly></div>
                    <div class="split-col"><label class="field-label-sm">Avenida</label><input type="text" class="input-field-soft" placeholder="Avenida" readonly></div>
                </div>
                <div class="details-field"><label class="field-label-sm">Vehículos involucrados</label><textarea class="textarea-field-soft" placeholder="Vehículos involucrados" readonly></textarea></div>
                <div class="details-field"><label class="field-label-sm">Descripción del siniestro</label><textarea class="textarea-field-soft" placeholder="Descripción del siniestro" readonly></textarea></div>
                <div class="details-field"><label class="field-label-sm">Dictamen</label><input type="text" class="input-field-soft" placeholder="Dictamen" readonly></div>
                <div class="details-field"><label class="field-label-sm">Presupuesto</label><input type="text" class="input-field-soft" placeholder="Presupuesto" readonly></div>
            </div>
        </div>
    </div>
</div>

<div class="supervisor-overlay" id="supervisorModal" aria-hidden="true">
    <div class="supervisor-panel" role="dialog" aria-modal="true" aria-labelledby="supervisorModalTitle">
        <button type="button" class="modal-close-round-lg" id="closeSupervisorModal" aria-label="Cerrar modal">×</button>
        <h2 class="supervisor-title" id="supervisorModalTitle">Ver Siniestro</h2>
        <div class="supervisor-divider"></div>

        <div class="supervisor-body">
            <div class="supervisor-section">
                <h3 class="supervisor-section-title">Fotos o Videos</h3>
                <div class="header-group gap-[10px]">
                    <input type="file" id="supervisorMediaUpload" accept="image/*,video/*" multiple style="display:none;" disabled>
                    <button type="button" class="upload-trigger-btn" disabled>Seleccionar archivos</button>
                </div>

                <div class="media-carousel mt-[10px]">
                    <button type="button" class="carousel-btn-base" id="supervisorCarouselPrev" aria-label="Anterior" disabled>‹</button>
                    <div class="carousel-view-box-dark" id="supervisorCarouselView">
                        <p class="carousel-empty-text" id="supervisorCarouselEmpty">No hay archivos cargados</p>
                    </div>
                    <button type="button" class="carousel-btn-base" id="supervisorCarouselNext" aria-label="Siguiente" disabled>›</button>
                </div>

                <div class="carousel-meta-row">
                    <p class="carousel-counter-text" id="supervisorCarouselCounter">0 / 0</p>
                    <button type="button" class="delete-media-btn-base hidden" id="supervisorDeleteMediaBtn" disabled>Eliminar actual</button>
                </div>
            </div>

            <div class="supervisor-section">
                <h3 class="supervisor-section-title">Detalles de Reparación y Pago</h3>
                <div class="supervisor-form-group">
                    <label for="supervisorDateType" class="supervisor-label">Tipo de fecha</label>
                    <select id="supervisorDateType" class="supervisor-input" disabled>
                        <option value="pago">Fecha de pago</option>
                        <option value="terminacion">Fecha de terminación</option>
                    </select>
                </div>
                <div class="supervisor-form-group">
                    <label for="supervisorDate" class="supervisor-label">Fecha</label>
                    <input type="date" id="supervisorDate" class="supervisor-input" disabled>
                </div>
                <div class="supervisor-form-group">
                    <label for="supervisorPago" class="supervisor-label">Pago pendiente</label>
                    <input type="number" id="supervisorPago" class="supervisor-input" placeholder="Ingrese el monto" min="0" step="0.01" disabled>
                </div>
                <div class="supervisor-checkbox-row">
                    <input type="checkbox" id="supervisorNoAplica" class="supervisor-checkbox" disabled>
                    <label for="supervisorNoAplica" class="checkbox-label">No Aplica</label>
                </div>
            </div>

            <div class="supervisor-footer">
                <button type="button" class="gradient-action-btn" id="supervisorAdjuntarBtn">Adjuntar</button>
            </div>
        </div>
    </div>
</div>
</main>