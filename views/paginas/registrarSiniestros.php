<main>
    <section class="tabs-bar" aria-label="Secciones de registro de siniestro">
        <button type="button" class="tab-btn tab-btn-active" data-section="aseguradora" onclick="showSection('aseguradora')">Aseguradora</button>
        <button type="button" class="tab-btn" data-section="auto" onclick="showSection('auto')">Auto</button>
        <button type="button" class="tab-btn" data-section="detalles" onclick="showSection('detalles')">Detalles</button>
    </section>

    <main class="flex justify-center px-[16px] pb-[28px] pt-[18px]">
        <section class="register-card flex min-h-[430px] w-[min(860px,94vw)] flex-col rounded-[18px] bg-[#F5F7FA] px-[30px] pb-[20px] pt-[22px] shadow-[0_6px_12px_rgba(0,0,0,0.15)]">
            <a href="./siniestros.html" class="w-fit text-[22px] font-bold text-[#16425B] no-underline">← Regresar</a>

            <div class="section-content flex-1" id="aseguradora-section">
                <div class="mx-auto mt-[44px] grid max-w-[700px] grid-cols-2 gap-x-[22px] gap-y-[16px]">
                    <div class="field-group">
                        <label for="aseguradora-nombre" class="field-label">Aseguradora</label>
                        <input id="aseguradora-nombre" type="text" placeholder="Nombre de la aseguradora" class="input-field">
                    </div>
                    <div class="field-group">
                        <label for="aseguradora-poliza" class="field-label">Número de poliza</label>
                        <input id="aseguradora-poliza" type="text" placeholder="Número de la poliza" class="input-field">
                    </div>
                    <div class="field-group">
                        <label for="aseguradora-tipo" class="field-label">Tiempo de seguro</label>
                        <input id="aseguradora-tipo" type="text" placeholder="Tiempo de seguro" class="input-field">
                    </div>
                    <div class="field-group">
                        <label for="aseguradora-ajustador" class="field-label">Nombre del ajustador</label>
                        <input id="aseguradora-ajustador" type="text" placeholder="Nombre del ajustador" class="input-field">
                    </div>
                </div>
            </div>

            <div class="section-content hidden flex-1" id="auto-section">
                <div class="mx-auto mt-[44px] grid max-w-[700px] grid-cols-2 gap-x-[22px] gap-y-[16px]">
                    <div class="field-group">
                        <label for="auto-duenio" class="field-label">Nombre del dueño</label>
                        <input id="auto-duenio" type="text" placeholder="Nombre del dueño" class="input-field">
                    </div>
                    <div class="field-group">
                        <label for="auto-correo" class="field-label">Conductor</label>
                        <input id="auto-correo" type="text" placeholder="Nombre del conductor" class="input-field">
                    </div>
                    <div class="field-group">
                        <label for="auto-marca" class="field-label">Marca</label>
                        <input id="auto-marca" type="text" placeholder="Marca del auto" class="input-field">
                    </div>
                    <div class="field-group">
                        <label for="auto-modelo" class="field-label">Modelo</label>
                        <input id="auto-modelo" type="text" placeholder="Modelo del auto" class="input-field">
                    </div>
                    <div class="field-group">
                        <label for="auto-anio" class="field-label">Año</label>
                        <input id="auto-anio" type="text" placeholder="Año del auto" class="input-field">
                    </div>
                    <div class="field-group">
                        <label for="auto-placa" class="field-label">Placa</label>
                        <input id="auto-placa" type="text" placeholder="Placa" class="input-field">
                    </div>
                </div>
            </div>

            <div class="section-content hidden flex-1" id="detalles-section">
                <div class="mx-auto mt-[44px] grid max-w-[700px] grid-cols-2 gap-x-[22px] gap-y-[16px]">
                    <div class="field-group">
                        <label for="detalles-fecha" class="field-label">Fecha del siniestro</label>
                        <input id="detalles-fecha" type="text" placeholder="Fecha del siniestro" class="input-field">
                    </div>
                    <div class="field-group">
                        <label for="detalles-hora" class="field-label">Hora del siniestro</label>
                        <input id="detalles-hora" type="text" placeholder="00:00:00" class="input-field">
                    </div>

                    <div class="field-group col-span-full">
                        <label class="field-label">Ubicación</label>
                        <div class="location-grid">
                            <input id="detalles-calle" type="text" placeholder="Latitud" class="input-field">
                            <input id="detalles-municipio" type="text" placeholder="Longitud" class="input-field">
                            <input id="detalles-estado" type="text" placeholder="Avenida" class="input-field">
                        </div>
                    </div>

                    <div class="field-group col-span-full">
                        <label for="detalles-vehiculos" class="field-label">Vehiculos involucrados</label>
                        <div class="relative">
                            <textarea id="detalles-vehiculos" class="textarea-field min-h-[110px] pr-[52px]" placeholder="Vehiculos involucrados"></textarea>
                            <button type="button" class="floating-add-btn" id="openVehiculoModal" aria-label="Agregar vehiculo involucrado">+</button>
                        </div>
                    </div>

                    <div class="field-group col-span-full">
                        <label for="detalles-descripcion" class="field-label">Descripción del siniestro</label>
                        <textarea id="detalles-descripcion" class="textarea-field min-h-[110px]" placeholder="Descripción del siniestro"></textarea>
                    </div>

                    <div class="field-group">
                        <label for="detalles-dictamen" class="field-label">Dictamen</label>
                        <input id="detalles-dictamen" type="text" placeholder="Dictamen" class="input-field">
                    </div>

                    <div class="field-group">
                        <label for="detalles-presupuesto" class="field-label">Presupuesto</label>
                        <input id="detalles-presupuesto" type="text" placeholder="Presupuesto" class="input-field">
                    </div>

                    <div class="field-group col-span-full">
                        <label for="detalles-media" class="field-label">Subir imágenes o videos</label>
                        <input id="detalles-media" type="file" accept="image/*,video/*" multiple class="file-input-field">
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
                <button type="button" class="gradient-action-btn bg-[#16425B] px-[22px] py-[8px] text-[18px] shadow-[0_4px_4px_rgba(0,0,0,0.15)] hover:bg-[#16425B]" id="continueBtn">Continuar →</button>
            </div>
        </section>
    </main>

    <div class="supervisor-overlay" id="vehiculoModal" aria-hidden="true">
        <div class="supervisor-panel max-h-[90vh] max-w-[660px] px-[24px] pb-[24px] pt-[20px]" role="dialog" aria-modal="true" aria-labelledby="vehiculoModalTitle">
            <button type="button" class="modal-close-round-lg" id="closeVehiculoModal" aria-label="Cerrar modal">x</button>
            <h2 class="supervisor-title text-[34px]" id="vehiculoModalTitle">Vehiculo involucrado</h2>
            <div class="supervisor-divider"></div>

            <div class="mx-auto max-w-[500px]">
                <div class="supervisor-form-group">
                    <label for="vehiculo-conductor" class="field-label">Nombre del conductor</label>
                    <input id="vehiculo-conductor" type="text" placeholder="Nombre del conductor" class="supervisor-input">
                </div>

                <div class="supervisor-form-group">
                    <label for="vehiculo-correo" class="field-label">Correo electronico</label>
                    <input id="vehiculo-correo" type="email" placeholder="Ejemplo@correo.com" class="supervisor-input">
                </div>

                <div class="supervisor-form-group">
                    <label for="vehiculo-marca" class="field-label">Marca</label>
                    <input id="vehiculo-marca" type="text" placeholder="Marca del vehiculo" class="supervisor-input">
                </div>

                <div class="split-row">
                    <div class="supervisor-form-group flex-1">
                        <label for="vehiculo-modelo" class="field-label">Modelo</label>
                        <input id="vehiculo-modelo" type="text" placeholder="Nombre del modelo" class="supervisor-input">
                    </div>
                    <div class="supervisor-form-group flex-1">
                        <label for="vehiculo-anio" class="field-label">Año</label>
                        <input id="vehiculo-anio" type="text" placeholder="Ej. 2015" class="supervisor-input">
                    </div>
                </div>

                <div class="supervisor-form-group">
                    <label for="vehiculo-placas" class="field-label">Placas</label>
                    <input id="vehiculo-placas" type="text" placeholder="Numero de placas" class="supervisor-input">
                </div>

                <div class="supervisor-form-group">
                    <label for="vehiculo-aseguradora" class="field-label">Aseguradora</label>
                    <input id="vehiculo-aseguradora" type="text" placeholder="Nombre de la aseguradora" class="supervisor-input">
                </div>

                <div class="supervisor-form-group">
                    <label for="vehiculo-descripcion" class="field-label">Descripcion</label>
                    <textarea id="vehiculo-descripcion" placeholder="Descripcion" class="supervisor-input min-h-[90px] resize-y"></textarea>
                </div>

                <div class="mt-[8px] flex justify-end">
                    <button type="button" class="gradient-action-btn bg-[#16425B] px-[20px] py-[9px] text-[15px] shadow-[0_4px_8px_rgba(0,0,0,0.18)] hover:bg-[#16425B]">Done</button>
                </div>
            </div>
        </div>
    </div>
</main>