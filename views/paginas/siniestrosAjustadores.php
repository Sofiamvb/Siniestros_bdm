<main>

    <section class="bg-white px-6 py-4">
        <div class="mx-auto flex max-w-[1280px] flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

            <div class="flex flex-wrap items-end gap-5" aria-label="Rango de fechas para siniestros">
                <div>
                    <label for="fechaInicio" class="mb-1 block text-[14px] font-bold text-[#0b2030]">Desde</label>
                    <input type="date" id="fechaInicio"
                        class="h-[42px] w-[150px] rounded-[18px] border-0 bg-[#a8b5c4] px-4 text-[13px] text-[#263241] shadow-[0_4px_8px_rgba(0,0,0,0.25)] outline-none">
                </div>

                <div>
                    <label for="fechaFin" class="mb-1 block text-[14px] font-bold text-[#0b2030]">Hasta</label>
                    <input type="date" id="fechaFin"
                        class="h-[42px] w-[150px] rounded-[18px] border-0 bg-[#a8b5c4] px-4 text-[13px] text-[#263241] shadow-[0_4px_8px_rgba(0,0,0,0.25)] outline-none">
                </div>

                <button type="button"
                    class="h-[42px] rounded-full bg-[#0b2030] px-9 text-[14px] font-bold text-white shadow-[0_4px_8px_rgba(0,0,0,0.25)] hover:bg-[#142b3f]">
                    Filtrar
                </button>
            </div>

            <div class="flex flex-1 items-end justify-center gap-5 lg:justify-end">
                <div class="relative w-full max-w-[420px]">
                    <input type="text"
                        placeholder="Busca aquí"
                        class="h-[42px] w-full rounded-[18px] border-0 bg-[#a8b5c4] px-5 pr-12 text-[14px] text-[#263241] shadow-[0_4px_8px_rgba(0,0,0,0.25)] outline-none placeholder:text-[#5f6c7a]">

                    <span class="absolute right-4 top-1/2 flex h-5 w-5 -translate-y-1/2 items-center justify-center rounded-full text-[11px] text-white">
                        <img src="/img/lupa.png" alt="Lupa buscador">
                    </span>
                </div>

                <a href="/registrarSiniestros"
                    class="flex h-[42px] items-center justify-center rounded-full bg-[#0b2030] px-8 text-[14px] font-bold text-white no-underline shadow-[0_4px_8px_rgba(0,0,0,0.25)] hover:bg-[#142b3f]">
                    Registrar siniestro
                </a>

                <a href="/buscadorSiniestros"
                    class="flex h-[42px] items-center justify-center rounded-full border-2 border-[#0b2030] px-8 text-[14px] font-bold text-[#0b2030] no-underline shadow-[0_4px_8px_rgba(0,0,0,0.15)] hover:bg-[#0b2030] hover:text-white transition-colors">
                    Buscar siniestro
                </a>
            </div>

        </div>
    </section>

    <section class="bg-[#e3e4df] px-6 pb-20 pt-10">
        <div class="mx-auto max-w-[1280px] text-center">
            <h1 class="text-[30px] font-extrabold text-[#0d1b2a]">
                ¡Bienvenido, (Usuario)!
            </h1>

            <p class="mt-2 text-[14px] text-[#0d1b2a]">
                Estos son tus siniestros registrados.
            </p>

            <?php if (empty($siniestros)): ?>
                <div class="flex w-full flex-col items-center justify-center py-[60px] text-center text-[#888]">
                    <p class="text-[18px]">No tienes siniestros registrados aún.</p>
                    <a href="/registrarSiniestros"
                        class="mt-[16px] rounded-[20px] bg-[#16425B] px-[24px] py-[10px] text-white hover:opacity-90">
                        Registrar siniestro
                    </a>
                </div>
            <?php else: ?>

                <div class="mt-12 grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3 lg:gap-20">
                    <?php foreach ($siniestros as $siniestro): ?>
                        <?php
                        $fechaHora   = $siniestro['fecha_hora_siniestro'] ?? '';
                        $fecha       = $fechaHora ? date('d/m/Y', strtotime($fechaHora)) : '—';
                        $hora        = $fechaHora ? date('H:i:s', strtotime($fechaHora)) : '—';
                        $presupuesto = $siniestro['perdida_total'] ? 'Pérdida total' : ('$' . number_format((float)($siniestro['presupuesto_reparacion'] ?? 0), 2));
                        $dataJson    = htmlspecialchars(json_encode([
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
                            'presupuesto'      => $presupuesto,
                        ]), ENT_QUOTES);
                        ?>

                        <div class="mx-auto w-full max-w-[245px] overflow-hidden rounded-b-[18px] bg-white text-left shadow-[0_8px_14px_rgba(0,0,0,0.25)]"
                            data-siniestro="<?= $dataJson ?>">

                            <div class="h-[165px] w-full overflow-hidden">
                                <img src="<?= $siniestro['primera_evidencia'] ?>"
                                    alt="Siniestro"
                                    class="h-full w-full object-cover">
                            </div>

                            <div class="px-4 py-5 text-[11px] font-bold leading-[1.25] text-black">
                                <p>Nombre(s) del dueño: <?= htmlspecialchars($siniestro['duenio_nombre'] ?? '') ?></p>
                                <p>Marca: <?= htmlspecialchars($siniestro['marca'] ?? '') ?></p>
                                <p>Número de placas: <?= htmlspecialchars($siniestro['placas'] ?? '') ?></p>
                                <p>Nombre de la aseguradora: <?= htmlspecialchars($siniestro['compania'] ?? '') ?></p>
                                <p>Reporte: <?= htmlspecialchars($siniestro['numero_reporte'] ?? '') ?></p>

                                <br>

                                <p>Ajustador: <?= htmlspecialchars($siniestro['ajustador_nombre'] ?? '') ?></p>

                                <p>
                                    Estado:
                                    <span style="color: <?= htmlspecialchars($siniestro['estatus_color'] ?? '#000') ?>">
                                        <?= htmlspecialchars($siniestro['estatus'] ?? '') ?>
                                    </span>
                                </p>

                                <div class="mt-1 flex justify-end gap-1">
                                    <button class="p-0" onclick="openSupervisorModal()">
                                        <img src="/img/adjuntar.png" alt="Supervisor" class="h-[15px] w-[15px] object-contain">
                                    </button>

                                    <button class="p-0" onclick="openDetailsModal(this.closest('[data-siniestro]'))">
                                        <img src="/img/seeall.png" alt="Ver todo" class="h-[15px] w-[15px] object-contain">
                                    </button>

                                    <button class="p-0" onclick="openModal('/img/siniestro.jpg')">
                                        <img src="/img/comments.png" alt="Chat" class="h-[15px] w-[15px] object-contain">
                                    </button>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </div>
    </section>

    <div id="commentsModal" class="modal-overlay">
        <div class="modal-panel">
            <div class="modal-header-bar">
                <span class="modal-title-text">Comentarios</span>
                <img src="/img/comments.png" alt="Comentarios" class="modal-header-icon">
            </div>
            <div class="modal-body-scroll">
                <div class="mb-[20px] text-center">
                    <img id="modalAccidentImage" src="" alt="Siniestro" class="h-auto w-full max-w-[380px] rounded-[15px] shadow-[0_4px_12px_rgba(0,0,0,0.2)]">
                </div>

                <div class="comment-list">
                    <div class="comment-card">
                        <img src="/img/DefaultPFP.png" alt="Usuario" class="comment-avatar">
                        <div class="comment-body">
                            <div class="comment-author">Juan Ángel Oropeza</div>
                            <div class="comment-text">Esperando más información</div>
                            <div class="comment-date">2 de marzo de 2026, 10:30 AM</div>
                        </div>
                    </div>

                    <div class="comment-card">
                        <img src="/img/DefaultPFP.png" alt="Usuario" class="comment-avatar">
                        <div class="comment-body">
                            <div class="comment-author">Carlos Alberto Martínez</div>
                            <div class="comment-text">Ya envié los documentos solicitados</div>
                            <div class="comment-date">2 de marzo de 2026, 11:45 AM</div>
                        </div>
                    </div>
                </div>

                <div class="comment-entry">
                    <span class="comment-label">Comenta</span>
                    <input type="text" class="comment-input" placeholder="Realiza un comentario sobre el siniestro...">
                    <button type="button" class="comment-submit-btn">Publicar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="detailsModal" class="modal-overlay">
        <div class="details-modal-panel">
            <div class="details-header-blue">
                <button class="details-tab-btn details-tab-btn-blue details-tab-active-blue" data-tab="aseguradora" onclick="switchDetailsTab('aseguradora')">Aseguradora</button>
                <button class="details-tab-btn details-tab-btn-blue" data-tab="auto" onclick="switchDetailsTab('auto')">Auto</button>
                <button class="details-tab-btn details-tab-btn-blue" data-tab="detalles" onclick="switchDetailsTab('detalles')">Detalles</button>
            </div>

            <div class="details-body">
                <div class="details-section details-section-active" id="aseguradora-section">
                    <div class="details-field"><label class="field-label-sm">Aseguradora</label><input id="det-compania" type="text" class="input-field-soft" readonly></div>
                    <div class="details-field"><label class="field-label-sm">Número de póliza</label><input id="det-poliza" type="text" class="input-field-soft" readonly></div>
                    <div class="details-field"><label class="field-label-sm">Tiempo de seguro</label><input id="det-vigencia" type="text" class="input-field-soft" readonly></div>
                    <div class="details-field"><label class="field-label-sm">Nombre del ajustador</label><input id="det-ajustador" type="text" class="input-field-soft" readonly></div>
                </div>

                <div class="details-section details-section-hidden" id="auto-section">
                    <div class="details-field"><label class="field-label-sm">Nombre del dueño</label><input id="det-duenio" type="text" class="input-field-soft" readonly></div>
                    <div class="details-field"><label class="field-label-sm">Nombre del conductor</label><input id="det-conductor" type="text" class="input-field-soft" readonly></div>
                    <div class="details-field"><label class="field-label-sm">Marca</label><input id="det-marca" type="text" class="input-field-soft" readonly></div>
                    <div class="split-row-lg details-field">
                        <div class="split-col"><label class="field-label-sm">Modelo</label><input id="det-modelo" type="text" class="input-field-soft" readonly></div>
                        <div class="split-col"><label class="field-label-sm">Año</label><input id="det-anio" type="text" class="input-field-soft" readonly></div>
                    </div>
                    <div class="details-field"><label class="field-label-sm">Placas</label><input id="det-placas" type="text" class="input-field-soft" readonly></div>
                </div>

                <div class="details-section details-section-hidden" id="detalles-section">
                    <div class="details-field"><label class="field-label-sm">Fecha del siniestro</label><input id="det-fecha" type="text" class="input-field-soft" readonly></div>
                    <div class="details-field"><label class="field-label-sm">Hora del siniestro</label><input id="det-hora" type="text" class="input-field-soft" readonly></div>
                    <div class="details-field"><label class="field-label-sm">Ubicación</label></div>
                    <div class="split-row-lg details-field">
                        <div class="split-col"><label class="field-label-sm">Latitud</label><input id="det-latitud" type="text" class="input-field-soft" readonly></div>
                        <div class="split-col"><label class="field-label-sm">Longitud</label><input id="det-longitud" type="text" class="input-field-soft" readonly></div>
                    </div>
                    <div class="details-field"><label class="field-label-sm">Descripción del siniestro</label><textarea id="det-descripcion" class="textarea-field-soft" readonly></textarea></div>
                    <div class="details-field"><label class="field-label-sm">Dictamen</label><input id="det-dictamen" type="text" class="input-field-soft" readonly></div>
                    <div class="details-field"><label class="field-label-sm">Presupuesto</label><input id="det-presupuesto" type="text" class="input-field-soft" readonly></div>
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

    <script>
        function openDetailsModal(card) {
            const data = JSON.parse(card.dataset.siniestro);
            document.getElementById('det-compania').value = data.compania || '';
            document.getElementById('det-poliza').value = data.numero_poliza || '';
            document.getElementById('det-vigencia').value = (data.fecha_inicio && data.fecha_fin) ?
                data.fecha_inicio + ' al ' + data.fecha_fin : '';
            document.getElementById('det-ajustador').value = data.ajustador_nombre || '';
            document.getElementById('det-duenio').value = data.duenio_nombre || '';
            document.getElementById('det-conductor').value = data.conductor || '';
            document.getElementById('det-marca').value = data.marca || '';
            document.getElementById('det-modelo').value = data.modelo || '';
            document.getElementById('det-anio').value = data.anio || '';
            document.getElementById('det-placas').value = data.placas || '';
            document.getElementById('det-fecha').value = data.fecha || '';
            document.getElementById('det-hora').value = data.hora || '';
            document.getElementById('det-latitud').value = data.latitud || '';
            document.getElementById('det-longitud').value = data.longitud || '';
            document.getElementById('det-descripcion').value = data.descripcion || '';
            document.getElementById('det-dictamen').value = data.dictamen || 'Pendiente';
            document.getElementById('det-presupuesto').value = data.presupuesto || '';

            // Resetear a primera pestaña
            switchDetailsTab('aseguradora');
            document.getElementById('detailsModal').classList.add('modal-open');
        }

        document.getElementById('detailsModal').addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('modal-open');
        });
    </script>

    <?php if (!empty($siniestroNuevo)): ?>
        <script>
            Swal.fire({
                title: '¡Siniestro registrado!',
                text: 'El siniestro fue registrado correctamente y se asignó un supervisor automáticamente.',
                icon: 'success',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#16425B'
            });
        </script>
    <?php endif; ?>
</main>