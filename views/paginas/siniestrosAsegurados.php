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
        <!-- Card 1 -->
        <div class="siniestro-card-base">
            <div class="card-row-with-actions">
                <div class="card-left-col">
                    <div class="mini-carousel">
                        <button type="button" class="mini-carousel-btn" disabled>‹</button>
                        <div class="mini-carousel-view"><img src="../Images/siniestro.jpg" alt="Siniestro" class="mini-carousel-image"></div>
                        <button type="button" class="mini-carousel-btn" disabled>›</button>
                    </div>
                    <p class="adjuster-text">Ajustador: Juan Ángel Oropeza</p>
                </div>
                <div class="card-right-col">
                    <div>
                        <p class="info-text"><strong class="info-label-strong">Nombre(s) del dueño:</strong> Carlos Alberto Martínez López</p>
                        <p class="info-text"><strong class="info-label-strong">Marca:</strong> Nissan</p>
                        <p class="info-text"><strong class="info-label-strong">Número de placas:</strong> ABC-347-D</p>
                        <p class="info-text"><strong class="info-label-strong">Nombre de la aseguradora:</strong> GNP Seguros</p>
                    </div>
                    <div class="status-row">
                        <h2 class="status-title-flex">
                            Estado:
                            <select class="status-dropdown-base" onchange="updateStatus(this)">
                                <option value="en-revision" selected>En revisión</option>
                                <option value="rechazado">Rechazado</option>
                                <option value="aceptado">Aceptado</option>
                                <option value="aceptado-con-deducible">Aceptado con pago de deducible</option>
                                <option value="aceptado-sin-deducible">Aceptado sin pago de deducible</option>
                                <option value="pago-reparacion">Aplica pago para reparación de la unidad</option>
                                <option value="perdida-total">Pérdida total, aplica pago completo de la unidad</option>
                            </select>
                        </h2>
                    </div>
                </div>
            </div>
            <button class="icon-btn-supervisor-top" onclick="openSupervisorModal()"><img src="/img/adjuntar.png" alt="Supervisor" class="icon-btn-image-dim"></button>
            <button class="icon-btn-see-top" onclick="openDetailsModal()"><img src="/img/seeall.png" alt="Ver todo" class="icon-btn-image"></button>
            <button class="icon-btn-chat-top" onclick="openModal('/img/siniestro.jpg')"><img src="/img/comments.png" alt="Chat" class="icon-btn-image"></button>
        </div>

        <!-- Card 2 -->
        <div class="siniestro-card-base">
            <div class="card-row-with-actions">
                <div class="card-left-col">
                    <div class="mini-carousel">
                        <button type="button" class="mini-carousel-btn" disabled>‹</button>
                        <div class="mini-carousel-view"><img src="/img/siniestro1.jpg" alt="Siniestro" class="mini-carousel-image"></div>
                        <button type="button" class="mini-carousel-btn" disabled>›</button>
                    </div>
                    <p class="adjuster-text">Ajustador: Juan Ángel Oropeza</p>
                </div>
                <div class="card-right-col">
                    <div>
                        <p class="info-text"><strong class="info-label-strong">Nombre(s) del dueño:</strong> Mariana Fernanda Ruiz Torres</p>
                        <p class="info-text"><strong class="info-label-strong">Marca:</strong> Toyota</p>
                        <p class="info-text"><strong class="info-label-strong">Número de placas:</strong> LMX-582-A</p>
                        <p class="info-text"><strong class="info-label-strong">Nombre de la aseguradora:</strong> Quálitas Seguros</p>
                    </div>
                    <div class="status-row">
                        <h2 class="status-title-flex">
                            Estado:
                            <select class="status-dropdown-base" onchange="updateStatus(this)">
                                <option value="en-revision">En revisión</option>
                                <option value="rechazado">Rechazado</option>
                                <option value="aceptado" selected>Aceptado</option>
                                <option value="aceptado-con-deducible">Aceptado con pago de deducible</option>
                                <option value="aceptado-sin-deducible">Aceptado sin pago de deducible</option>
                                <option value="pago-reparacion">Aplica pago para reparación de la unidad</option>
                                <option value="perdida-total">Pérdida total, aplica pago completo de la unidad</option>
                            </select>
                        </h2>
                    </div>
                </div>
            </div>
            <button class="icon-btn-supervisor-top" onclick="openSupervisorModal()"><img src="/img/adjuntar.png" alt="Supervisor" class="icon-btn-image-dim"></button>
            <button class="icon-btn-see-top" onclick="openDetailsModal()"><img src="/img/seeall.png" alt="Ver todo" class="icon-btn-image"></button>
            <button class="icon-btn-chat-top" onclick="openModal('/img/siniestro1.jpg')"><img src="/img/comments.png" alt="Chat" class="icon-btn-image"></button>
        </div>

        <!-- Card 3 -->
        <div class="siniestro-card-base">
            <div class="card-row-with-actions">
                <div class="card-left-col">
                    <div class="mini-carousel">
                        <button type="button" class="mini-carousel-btn" disabled>‹</button>
                        <div class="mini-carousel-view"><img src="/img/siniestro2.jpg" alt="Siniestro" class="mini-carousel-image"></div>
                        <button type="button" class="mini-carousel-btn" disabled>›</button>
                    </div>
                    <p class="adjuster-text">Ajustador: Sofia Villegas Blanco</p>
                </div>
                <div class="card-right-col">
                    <div>
                        <p class="info-text"><strong class="info-label-strong">Nombre(s) del dueño:</strong> Santiago Carrizales Becerra</p>
                        <p class="info-text"><strong class="info-label-strong">Marca:</strong> Toyota</p>
                        <p class="info-text"><strong class="info-label-strong">Número de placas:</strong> OML-679-D</p>
                        <p class="info-text"><strong class="info-label-strong">Nombre de la aseguradora:</strong> AXA Seguros</p>
                    </div>
                    <div class="status-row">
                        <h2 class="status-title-flex">
                            Estado:
                            <select class="status-dropdown-base" onchange="updateStatus(this)">
                                <option value="en-revision">En revisión</option>
                                <option value="rechazado" selected>Rechazado</option>
                                <option value="aceptado">Aceptado</option>
                                <option value="aceptado-con-deducible">Aceptado con pago de deducible</option>
                                <option value="aceptado-sin-deducible">Aceptado sin pago de deducible</option>
                                <option value="pago-reparacion">Aplica pago para reparación de la unidad</option>
                                <option value="perdida-total">Pérdida total, aplica pago completo de la unidad</option>
                            </select>
                        </h2>
                    </div>
                </div>
            </div>
            <button class="icon-btn-supervisor-top" onclick="openSupervisorModal()"><img src="/img/adjuntar.png" alt="Supervisor" class="icon-btn-image-dim"></button>
            <button class="icon-btn-see-top" onclick="openDetailsModal()"><img src="/img/seeall.png" alt="Ver todo" class="icon-btn-image"></button>
            <button class="icon-btn-chat-top" onclick="openModal('/img/siniestro2.jpg')"><img src="/img/comments.png" alt="Chat" class="icon-btn-image"></button>
        </div>

        <!-- Card 4 -->
        <div class="siniestro-card-base">
            <div class="card-row-with-actions">
                <div class="card-left-col">
                    <div class="mini-carousel">
                        <button type="button" class="mini-carousel-btn" disabled>‹</button>
                        <div class="mini-carousel-view"><img src="/img/siniestro3.jpg" alt="Siniestro" class="mini-carousel-image"></div>
                        <button type="button" class="mini-carousel-btn" disabled>›</button>
                    </div>
                    <p class="adjuster-text">Ajustador: Alejandro Acosta Beltrán</p>
                </div>
                <div class="card-right-col">
                    <div>
                        <p class="info-text"><strong class="info-label-strong">Nombre(s) del dueño:</strong> Andrea García Núñez</p>
                        <p class="info-text"><strong class="info-label-strong">Marca:</strong> Honda</p>
                        <p class="info-text"><strong class="info-label-strong">Número de placas:</strong> IOS-821-O</p>
                        <p class="info-text"><strong class="info-label-strong">Nombre de la aseguradora:</strong> BBVA Seguros</p>
                    </div>
                    <div class="status-row">
                        <h2 class="status-title-flex">
                            Estado:
                            <select class="status-dropdown-base" onchange="updateStatus(this)">
                                <option value="en-revision">En revisión</option>
                                <option value="rechazado" selected>Rechazado</option>
                                <option value="aceptado">Aceptado</option>
                                <option value="aceptado-con-deducible">Aceptado con pago de deducible</option>
                                <option value="aceptado-sin-deducible">Aceptado sin pago de deducible</option>
                                <option value="pago-reparacion">Aplica pago para reparación de la unidad</option>
                                <option value="perdida-total">Pérdida total, aplica pago completo de la unidad</option>
                            </select>
                        </h2>
                    </div>
                </div>
            </div>
            <button class="icon-btn-supervisor-top" onclick="openSupervisorModal()"><img src="/img/adjuntar.png" alt="Supervisor" class="icon-btn-image-dim"></button>
            <button class="icon-btn-see-top" onclick="openDetailsModal()"><img src="/img/seeall.png" alt="Ver todo" class="icon-btn-image"></button>
            <button class="icon-btn-chat-top" onclick="openModal('/img/siniestro3.jpg')"><img src="/img/comments.png" alt="Chat" class="icon-btn-image"></button>
        </div>
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
                            <div class="comment-author">Ajustador</div>
                            <div class="comment-text">Esperando más información</div>
                            <div class="comment-date">2 de marzo de 2026, 10:30 AM</div>
                        </div>
                    </div>
                    <div class="comment-card">
                        <img src="/img/DefaultPFP.png" alt="Usuario" class="comment-avatar">
                        <div class="comment-body">
                            <div class="comment-author">Asegurado</div>
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
            <div class="details-header-green">
                <button class="details-tab-btn details-tab-btn-cyan details-tab-active-cyan" data-tab="aseguradora" onclick="switchDetailsTab('aseguradora')">Aseguradora</button>
                <button class="details-tab-btn details-tab-btn-cyan" data-tab="auto" onclick="switchDetailsTab('auto')">Auto</button>
                <button class="details-tab-btn details-tab-btn-cyan" data-tab="detalles" onclick="switchDetailsTab('detalles')">Detalles</button>
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