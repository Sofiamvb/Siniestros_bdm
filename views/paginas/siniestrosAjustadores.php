<main>

    <section class="page-section-title">
        <div class="page-title-row">
            <div class="date-filter-wrap" aria-label="Rango de fechas para siniestros">
                <div class="date-field-wrap">
                    <label for="fechaInicio" class="date-label">Desde</label>
                    <input type="date" id="fechaInicio" class="date-input">
                </div>
                <div class="date-field-wrap">
                    <label for="fechaFin" class="date-label">Hasta</label>
                    <input type="date" id="fechaFin" class="date-input">
                </div>
                <button type="button" class="filter-btn-base">Filtrar</button>
            </div>
            <h1 class="page-main-title">SINIESTROS</h1>
            <div class="w-[1px] max-[980px]:hidden" aria-hidden="true"></div>
        </div>
        <div class="page-title-divider"></div>
    </section>

    <main class="page-container">
        <?php if (empty($siniestros)): ?>
        <div class="flex w-full flex-col items-center justify-center py-[60px] text-center text-[#888]">
            <p class="text-[18px]">No tienes siniestros registrados aún.</p>
            <a href="/registrarSiniestros" class="mt-[16px] rounded-[20px] bg-[#16425B] px-[24px] py-[10px] text-white hover:opacity-90">Registrar siniestro</a>
        </div>
        <?php else: ?>
        <?php foreach ($siniestros as $s): ?>
        <?php
            $fechaHora   = $s['fecha_hora_siniestro'] ?? '';
            $fecha       = $fechaHora ? date('d/m/Y',   strtotime($fechaHora)) : '—';
            $hora        = $fechaHora ? date('H:i:s',   strtotime($fechaHora)) : '—';
            $presupuesto = $s['perdida_total'] ? 'Pérdida total' : ('$' . number_format((float)($s['presupuesto_reparacion'] ?? 0), 2));
            $dataJson    = htmlspecialchars(json_encode([
                'numero_reporte'   => $s['numero_reporte']    ?? '',
                'compania'         => $s['compania']          ?? '',
                'numero_poliza'    => $s['numero_poliza']     ?? '',
                'fecha_inicio'     => $s['fecha_inicio']      ?? '',
                'fecha_fin'        => $s['fecha_fin']         ?? '',
                'ajustador_nombre' => $s['ajustador_nombre']  ?? '',
                'duenio_nombre'    => $s['duenio_nombre']     ?? '',
                'conductor'        => $s['conductor_momento'] ?? '',
                'marca'            => $s['marca']             ?? '',
                'modelo'           => $s['modelo']            ?? '',
                'anio'             => $s['anio']              ?? '',
                'placas'           => $s['placas']            ?? '',
                'fecha'            => $fecha,
                'hora'             => $hora,
                'latitud'          => $s['latitud']           ?? '',
                'longitud'         => $s['longitud']          ?? '',
                'descripcion'      => $s['descripcion_hechos'] ?? '',
                'dictamen'         => $s['dictamen_supervisor'] ?? '',
                'presupuesto'      => $presupuesto,
            ]), ENT_QUOTES);
        ?>
        <div class="siniestro-card-base" data-siniestro="<?= $dataJson ?>">
            <div class="card-row-with-actions">
                <div class="card-left-col">
                    <div class="mini-carousel">
                        <button type="button" class="mini-carousel-btn" disabled aria-label="Anterior">‹</button>
                        <div class="mini-carousel-view">
                            <img src="/img/siniestro.jpg" alt="Siniestro" class="mini-carousel-image">
                        </div>
                        <button type="button" class="mini-carousel-btn" disabled aria-label="Siguiente">›</button>
                    </div>
                    <p class="adjuster-text">Ajustador: <?= htmlspecialchars($s['ajustador_nombre'] ?? '') ?></p>
                </div>
                <div class="card-right-col">
                    <div>
                        <p class="info-text"><strong class="info-label-strong">Nombre(s) del dueño:</strong> <?= htmlspecialchars($s['duenio_nombre'] ?? '') ?></p>
                        <p class="info-text"><strong class="info-label-strong">Marca:</strong> <?= htmlspecialchars($s['marca'] ?? '') ?></p>
                        <p class="info-text"><strong class="info-label-strong">Número de placas:</strong> <?= htmlspecialchars($s['placas'] ?? '') ?></p>
                        <p class="info-text"><strong class="info-label-strong">Nombre de la aseguradora:</strong> <?= htmlspecialchars($s['compania'] ?? '') ?></p>
                        <p class="info-text"><strong class="info-label-strong">Reporte:</strong> <?= htmlspecialchars($s['numero_reporte'] ?? '') ?></p>
                    </div>
                    <div class="status-row">
                        <h2 class="status-title">Estado: <span style="color: <?= htmlspecialchars($s['estatus_color'] ?? '#000') ?>"><?= htmlspecialchars($s['estatus'] ?? '') ?></span></h2>
                    </div>
                </div>
            </div>
            <button class="icon-btn-supervisor-top" onclick="openSupervisorModal()"><img src="/img/adjuntar.png" alt="Supervisor" class="icon-btn-image-dim"></button>
            <button class="icon-btn-see-top" onclick="openDetailsModal(this.closest('.siniestro-card-base'))"><img src="/img/seeall.png" alt="Ver todo" class="icon-btn-image"></button>
            <button class="icon-btn-chat-top" onclick="openModal('/img/siniestro.jpg')"><img src="/img/comments.png" alt="Chat" class="icon-btn-image"></button>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </main>

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
            document.getElementById('det-compania').value   = data.compania        || '';
            document.getElementById('det-poliza').value     = data.numero_poliza   || '';
            document.getElementById('det-vigencia').value   = (data.fecha_inicio && data.fecha_fin)
                                                                ? data.fecha_inicio + ' al ' + data.fecha_fin : '';
            document.getElementById('det-ajustador').value  = data.ajustador_nombre || '';
            document.getElementById('det-duenio').value     = data.duenio_nombre   || '';
            document.getElementById('det-conductor').value  = data.conductor       || '';
            document.getElementById('det-marca').value      = data.marca           || '';
            document.getElementById('det-modelo').value     = data.modelo          || '';
            document.getElementById('det-anio').value       = data.anio            || '';
            document.getElementById('det-placas').value     = data.placas          || '';
            document.getElementById('det-fecha').value      = data.fecha           || '';
            document.getElementById('det-hora').value       = data.hora            || '';
            document.getElementById('det-latitud').value    = data.latitud         || '';
            document.getElementById('det-longitud').value   = data.longitud        || '';
            document.getElementById('det-descripcion').value = data.descripcion    || '';
            document.getElementById('det-dictamen').value   = data.dictamen        || 'Pendiente';
            document.getElementById('det-presupuesto').value = data.presupuesto    || '';

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
