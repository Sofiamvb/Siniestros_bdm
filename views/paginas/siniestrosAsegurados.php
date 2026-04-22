<main class="min-h-screen bg-[#e5e5e2]">

    <section class="mx-auto max-w-[900px] px-6 pt-8 text-center">
        <h1 class="text-[32px] md:text-[36px] font-bold leading-none text-[#162338]">
            ¡Bienvenido, (Usuario)!
        </h1>
        <p class="mt-2 text-[14px] md:text-[16px] text-[#243247]">
            Estas son tus pólizas vigentes.
        </p>
    </section>

    <section class="mx-auto max-w-[980px] min-h-[560px] px-6 pb-24 pt-10">
        <?php if (empty($polizas)): ?>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 md:gap-10">

                <!-- TARJETA IZQUIERDA -->
                <div class="min-h-[300px] rounded-[18px] bg-[#f7f7f7] px-7 py-7 shadow-[0_6px_14px_rgba(0,0,0,0.16)]">
                    <div class="flex h-full flex-col justify-between">
                        <div class="flex flex-col items-center gap-4">
                            <h2 class="text-[18px] md:text-[19px] font-bold text-[#162338]">
                                ¿Tienes problemas con tu póliza?
                            </h2>

                            <p class="mt-4 text-[14px] text-[#243247]">
                                Llama a nuestra línea de telefono.
                            </p>

                            <div class="mt-8 flex justify-start">
                                <div class="flex h-[82px] items-center overflow-hidden">
                                    <img src="/img/logo car azul.png" alt="SISA"
                                        class="h-[52px] w-auto object-contain">
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-start">
                            <a href="tel:8131555540"
                                class="inline-flex h-[52px] min-w-[185px] items-center justify-center rounded-full bg-[#4f6d98] px-8 text-[13px] font-bold text-white shadow-[0_5px_10px_rgba(0,0,0,0.18)] transition hover:bg-[#466288]">
                                81-3155-5540
                            </a>
                        </div>
                    </div>
                </div>

                <!-- TARJETA DERECHA -->
                <div class="min-h-[300px] rounded-[18px] bg-[#f7f7f7] px-7 py-7 text-center shadow-[0_6px_14px_rgba(0,0,0,0.16)]">
                    <div class="flex h-full flex-col items-center justify-between">
                        <div class="w-full">
                            <div class="flex justify-center">
                                <div class="flex h-[82px] items-center justify-center overflow-hidden">
                                    <img src="/img/logo car azul.png" alt="SISA"
                                        class="h-[52px] w-auto object-contain">
                                </div>
                            </div>

                            <h2 class="mt-4 text-[18px] md:text-[19px] font-bold text-[#162338]">
                                Aún no tienes pólizas
                            </h2>

                            <p class="mx-auto mt-4 max-w-[310px] text-[14px] leading-relaxed text-[#243247]">
                                Protege tu vehículo ahora y cotiza con nuestras aseguradoras.
                            </p>
                        </div>

                        <div class="mt-8 flex justify-center">
                            <a href="/cotizar"
                                class="inline-flex h-[52px] min-w-[185px] items-center justify-center rounded-full bg-[#4f6d98] px-8 text-[13px] font-bold text-white shadow-[0_5px_10px_rgba(0,0,0,0.18)] transition hover:bg-[#466288]">
                                Cotiza ahora
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        <?php else: ?>
            <?php foreach ($polizas as $poliza): ?>
                <div class="siniestro-card-base">
                    <div class="card-row-with-actions">
                        <div class="card-left-col">
                            <div class="mini-carousel">
                                <button type="button" class="mini-carousel-btn" disabled>‹</button>
                                <div class="mini-carousel-view"><img src="/img/siniestro.jpg" alt="Póliza" class="mini-carousel-image"></div>
                                <button type="button" class="mini-carousel-btn" disabled>›</button>
                            </div>
                            <p class="adjuster-text">Póliza: <?= htmlspecialchars($poliza->numero_poliza) ?></p>
                        </div>
                        <div class="card-right-col">
                            <div>
                                <p class="info-text"><strong class="info-label-strong">Vehículo:</strong> <?= htmlspecialchars($poliza->marca) ?> <?= htmlspecialchars($poliza->modelo) ?> <?= $poliza->anio ?></p>
                                <p class="info-text"><strong class="info-label-strong">Versión:</strong> <?= htmlspecialchars($poliza->version) ?></p>
                                <p class="info-text"><strong class="info-label-strong">Número de placas:</strong> <?= htmlspecialchars($poliza->placas) ?></p>
                                <p class="info-text"><strong class="info-label-strong">Aseguradora:</strong> <?= htmlspecialchars($poliza->compania) ?></p>
                                <p class="info-text"><strong class="info-label-strong">Cobertura:</strong> <?= htmlspecialchars($poliza->nivel) ?> — <?= htmlspecialchars($poliza->nombre_seguro) ?></p>
                                <p class="info-text"><strong class="info-label-strong">Vigencia:</strong> <?= htmlspecialchars($poliza->fecha_inicio) ?> al <?= htmlspecialchars($poliza->fecha_fin) ?></p>
                            </div>
                            <div class="status-row">
                                <h2 class="status-title-flex">
                                    Estado: <span class="font-semibold <?= $poliza->estatus_poliza ? 'text-green-600' : 'text-red-500' ?>">
                                        <?= $poliza->estatus_poliza ? 'Activa' : 'Caducada' ?>
                                    </span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <button class="icon-btn-see-top" onclick="openDetailsModal()"><img src="/img/seeall.png" alt="Ver todo" class="icon-btn-image"></button>
                    <button class="icon-btn-chat-top" onclick="openModal('/img/siniestro.jpg')"><img src="/img/comments.png" alt="Chat" class="icon-btn-image"></button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
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

    <?php if (!empty($_GET['poliza_nueva'])): ?>
        <script>
            Swal.fire({
                title: '¡Póliza contratada!',
                text: 'Tu seguro ha sido activado exitosamente. Ya puedes verlo en tus pólizas.',
                icon: 'success',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3A7CA5'
            });
        </script>
    <?php endif; ?>
</main>