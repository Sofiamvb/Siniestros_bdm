<?php
$fechaHora    = $siniestro['fecha_hora_siniestro'] ?? '';
$fecha        = $fechaHora ? date('d/m/Y', strtotime($fechaHora)) : '—';
$hora         = $fechaHora ? date('H:i:s',  strtotime($fechaHora)) : '—';
$presupuesto  = !empty($siniestro['perdida_total'])
    ? 'Pérdida total'
    : number_format((float)($siniestro['presupuesto_reparacion'] ?? 0), 2);
$estatusId    = (int) ($siniestro['estatus_id']       ?? 0);
$esTerminal   = !empty($siniestro['estatus_terminal']);
$esPendiente  = $estatusId === 7; // estado inicial antes de dictamen

$terceroTexto = empty($terceros)
    ? 'Sin terceros registrados'
    : implode(' | ', array_map(fn($t) => "{$t['marca_tercero']} {$t['modelo_tercero']} ({$t['placas_tercero']})", $terceros));

$evidenciasJson = json_encode(array_values(array_map(fn($e) => [
    'src'    => $e['src'],
    'tipo'   => $e['tipo_evidencia'],
    'nombre' => $e['nombre_archivo'],
], $evidencias)));
?>
<main class="min-h-[calc(100vh-180px)] bg-[#e6e7e2] px-6 py-12 font-sans text-[#111823]">
    <div class="mx-auto mb-6 flex max-w-[1200px] items-center justify-between">
        <a href="/siniestrosSupervisores" class="flex items-center gap-2 text-[13px] font-bold text-[#111823] no-underline hover:opacity-70">
            ← Volver
        </a>
        <span class="rounded-full px-3 py-1 text-[12px] font-bold text-white" style="background:<?= htmlspecialchars($siniestro['estatus_color'] ?? '#888') ?>">
            <?= htmlspecialchars($siniestro['estatus'] ?? '') ?>
        </span>
    </div>

    <div class="mx-auto flex max-w-[1200px] flex-col gap-10 lg:flex-row lg:gap-16">

        <!-- ── COLUMNA 1: TIMELINE ─────────────────────────────────── -->
        <div class="relative flex w-full flex-col items-center space-y-6 lg:w-24">
            <?php foreach ($seguimiento as $mov): ?>
                <?php $esActual = ($mov['estatus'] === ($siniestro['estatus'] ?? '')); ?>
                <div class="flex flex-col items-center text-center">
                    <span class="mb-1 text-[11px] font-medium text-gray-700">
                        <?= date('d/m/Y', strtotime($mov['fecha_movimiento'])) ?>
                    </span>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full shadow-sm <?= $esActual ? 'bg-[#111823]' : 'bg-[#b8bec8]' ?>">
                        <svg class="h-5 w-5 <?= $esActual ? 'text-white' : 'text-[#111823]' ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="mt-1 max-w-[80px] text-[11px] font-medium text-gray-700 leading-tight">
                        <?= htmlspecialchars($mov['estatus']) ?>
                    </span>
                </div>
            <?php endforeach; ?>

            <?php if (empty($seguimiento)): ?>
                <p class="text-[12px] text-gray-500 text-center">Sin historial</p>
            <?php endif; ?>

            <div class="mt-8 pt-4">
                <button class="flex h-14 w-14 items-center justify-center rounded-full bg-white shadow-lg transition hover:scale-105">
                    <svg class="h-7 w-7 text-[#111823]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 10H6v-2h12v2zm0-3H6V7h12v2z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- ── COLUMNA 2: DATOS DEL SINIESTRO ─────────────────────── -->
        <div class="flex-1">
            <form class="flex flex-col gap-5">

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1 ml-1 block text-[14px] font-bold text-[#111823]">Fecha del siniestro</label>
                        <div class="relative">
                            <input type="text" value="<?= htmlspecialchars($fecha) ?>"
                                class="h-[46px] w-full rounded-full bg-[#b8bec8] px-5 text-[#111823] outline-none" readonly>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 ml-1 block text-[14px] font-bold text-[#111823]">Hora del siniestro</label>
                        <input type="text" value="<?= htmlspecialchars($hora) ?>"
                            class="h-[46px] w-full rounded-full bg-[#b8bec8] px-5 text-[#111823] outline-none" readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1 ml-1 block text-[14px] font-bold text-[#111823]">Latitud</label>
                        <input type="text" value="<?= htmlspecialchars($siniestro['latitud'] ?? '') ?>"
                            class="h-[46px] w-full rounded-full bg-[#b8bec8] px-5 text-[#111823] outline-none" readonly>
                    </div>
                    <div>
                        <label class="mb-1 ml-1 block text-[14px] font-bold text-[#111823]">Longitud</label>
                        <input type="text" value="<?= htmlspecialchars($siniestro['longitud'] ?? '') ?>"
                            class="h-[46px] w-full rounded-full bg-[#b8bec8] px-5 text-[#111823] outline-none" readonly>
                    </div>
                </div>

                <div>
                    <label class="mb-1 ml-1 block text-[14px] font-bold text-[#111823]">Conductor al momento</label>
                    <input type="text" value="<?= htmlspecialchars($siniestro['conductor_momento'] ?? '') ?>"
                        class="h-[46px] w-full rounded-full bg-[#b8bec8] px-5 text-[#111823] outline-none" readonly>
                </div>

                <div>
                    <label class="mb-1 ml-1 block text-[14px] font-bold text-[#111823]">Vehículos involucrados</label>
                    <input type="text" value="<?= htmlspecialchars($terceroTexto) ?>"
                        class="h-[46px] w-full rounded-full bg-[#b8bec8] px-5 text-[#111823] outline-none" readonly>
                </div>

                <div>
                    <label class="mb-1 ml-1 block text-[14px] font-bold text-[#111823]">Descripción de los hechos</label>
                    <textarea rows="3" class="w-full resize-none rounded-[20px] bg-[#b8bec8] p-5 text-[#111823] outline-none" readonly><?= htmlspecialchars($siniestro['descripcion_hechos'] ?? '') ?></textarea>
                </div>

                <div class="flex items-center gap-2 pl-2">
                    <input type="checkbox" class="h-4 w-4" <?= !empty($siniestro['perdida_total']) ? 'checked' : '' ?> disabled>
                    <label class="rounded bg-white px-2 py-0.5 text-[12px] font-bold text-[#3A6B9B] shadow-sm">
                        Pérdida total (sin presupuesto de reparación)
                    </label>
                </div>

                <div>
                    <label class="mb-1 ml-1 block text-[14px] font-bold text-[#111823]">Presupuesto de reparación</label>
                    <input type="text" value="<?= htmlspecialchars($presupuesto) ?>"
                        class="h-[46px] w-full rounded-full bg-[#b8bec8] px-5 text-[#111823] outline-none" readonly>
                    <?php if (!empty($siniestro['suma_asegurada'])): ?>
                        <div class="mt-1 inline-block rounded bg-white px-2 py-0.5 text-[12px] font-medium text-gray-600 shadow-sm">
                            Suma asegurada máx.: $<?= number_format((float)$siniestro['suma_asegurada'], 2) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Carrusel de evidencias -->
                <div class="mt-4 flex flex-col items-center">
                    <h3 class="mb-2 text-[15px] font-bold text-[#111823]">Evidencias</h3>
                    <div class="flex w-full items-center justify-center gap-4">
                        <button type="button" id="prevBtn" onclick="cambiarEvidencia(-1)"
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-[#111823] text-white transition hover:bg-gray-800">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <div id="carouselBox" class="flex h-[200px] w-full max-w-[400px] items-center justify-center rounded-[20px] bg-[#b8bec8] overflow-hidden">
                            <span id="carouselEmpty" class="text-[14px] text-gray-600">No hay archivos cargados</span>
                            <img id="carouselImg" src="" alt="" class="hidden h-full w-full object-cover">
                            <video id="carouselVideo" src="" class="hidden h-full w-full object-cover" controls></video>
                        </div>
                        <button type="button" id="nextBtn" onclick="cambiarEvidencia(1)"
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-[#111823] text-white transition hover:bg-gray-800">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                    <span id="carouselCounter" class="mt-2 text-[12px] text-gray-600">0 / 0</span>
                </div>

            </form>
        </div>

        <!-- ── COLUMNA 3: TARJETA DE PÓLIZA ───────────────────────── -->
        <div class="w-full lg:w-[280px]">
            <div class="flex flex-col items-center rounded-[24px] bg-white px-6 py-10 text-center shadow-[0_8px_30px_rgba(0,0,0,0.06)]">
                <h2 class="text-[18px] font-bold text-[#111823]"><?= htmlspecialchars($siniestro['compania'] ?? '') ?></h2>
                <p class="mt-2 text-[13px] text-[#4a5568]">No. <?= htmlspecialchars($siniestro['numero_poliza'] ?? '') ?></p>
                <p class="mt-1 text-[13px] text-[#4a5568]">Placas: <?= htmlspecialchars($siniestro['placas'] ?? '') ?></p>
                <p class="mt-1 text-[13px] text-[#4a5568]"><?= htmlspecialchars($siniestro['marca'] ?? '') ?> <?= htmlspecialchars($siniestro['modelo'] ?? '') ?> <?= htmlspecialchars($siniestro['anio'] ?? '') ?></p>
                <hr class="my-4 w-full border-gray-200">
                <p class="text-[13px] font-bold text-[#111823]">Ajustador</p>
                <p class="mt-1 text-[13px] text-[#4a5568]"><?= htmlspecialchars($siniestro['ajustador_nombre'] ?? '') ?></p>
                <?php if (!empty($siniestro['supervisor_nombre'])): ?>
                    <p class="mt-3 text-[13px] font-bold text-[#111823]">Supervisor</p>
                    <p class="mt-1 text-[13px] text-[#4a5568]"><?= htmlspecialchars($siniestro['supervisor_nombre']) ?></p>
                <?php endif; ?>
                <hr class="my-4 w-full border-gray-200">
                <p class="text-[12px] text-gray-500">Vigencia</p>
                <p class="text-[12px] text-gray-600"><?= htmlspecialchars($siniestro['fecha_inicio'] ?? '') ?> al <?= htmlspecialchars($siniestro['fecha_fin'] ?? '') ?></p>
            </div>
        </div>

    </div>
</main>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- PANEL DE DICTAMEN DEL SUPERVISOR                           -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="mx-auto mt-10 max-w-[1200px] px-0">

    <?php if ($esTerminal && $estatusId === 1): ?>
        <!-- Rechazado → no se puede hacer nada -->
        <div class="rounded-[18px] border border-red-200 bg-red-50 px-8 py-6 text-center">
            <p class="text-[15px] font-bold text-red-600">Siniestro rechazado — no se pueden realizar más acciones.</p>
        </div>

    <?php elseif ($esTerminal): ?>
        <!-- Otro estado terminal (aceptado con dictamen final) -->
        <div class="rounded-[18px] border border-green-200 bg-green-50 px-8 py-6 text-center">
            <p class="text-[15px] font-bold text-green-700">Dictamen registrado: <?= htmlspecialchars($siniestro['estatus']) ?></p>
        </div>

    <?php else: ?>
        <!-- Siniestro pendiente de dictamen o en estado no terminal → formulario -->
        <div class="rounded-[18px] bg-white px-8 py-8 shadow-[0_8px_24px_rgba(0,0,0,0.08)]">
            <h2 class="mb-6 text-[18px] font-bold text-[#111823]">Dictamen del supervisor</h2>

            <!-- PASO 1: Aceptar / Rechazar -->
            <div id="paso1" class="<?= $esPendiente ? '' : 'hidden' ?>">
                <p class="mb-5 text-[14px] text-[#4a5568]">
                    Revisa la documentación del ajustador y determina si el siniestro es válido.
                </p>
                <div class="flex flex-wrap gap-4">
                    <button type="button" onclick="rechazarSiniestro()"
                        class="h-[46px] rounded-full bg-red-600 px-10 text-[14px] font-bold text-white transition hover:bg-red-700">
                        Rechazar
                    </button>
                    <button type="button" onclick="mostrarPaso2()"
                        class="h-[46px] rounded-full bg-[#0b2030] px-10 text-[14px] font-bold text-white transition hover:bg-[#142b3f]">
                        Aceptar →
                    </button>
                </div>
            </div>

            <!-- PASO 2: Tipo de dictamen -->
            <div id="paso2" class="<?= $esPendiente ? 'hidden' : '' ?>">
                <?php if (!$esPendiente): ?>
                    <p class="mb-4 text-[13px] text-[#6b7280]">
                        Estado actual: <strong><?= htmlspecialchars($siniestro['estatus']) ?></strong>. Puedes actualizar el dictamen.
                    </p>
                <?php endif; ?>

                <p class="mb-4 text-[14px] font-semibold text-[#111823]">Selecciona el tipo de dictamen:</p>

                <div class="flex flex-col gap-3 mb-6">
                    <?php
                    $opciones = [
                        2 => 'Aceptado',
                        3 => 'Aceptado con pago de deducible',
                        4 => 'Aceptado sin pago de deducible',
                        5 => 'Aplica pago para reparación de la unidad',
                        6 => 'Pérdida total, aplica pago completo de la unidad',
                    ];
                    foreach ($opciones as $id => $label):
                    ?>
                        <label class="flex cursor-pointer items-center gap-3 rounded-[12px] border border-gray-200 px-4 py-3 transition hover:bg-gray-50">
                            <input type="radio" name="estatus_radio" value="<?= $id ?>"
                                class="h-4 w-4 accent-[#0b2030]"
                                onchange="onEstatusChange(this.value)"
                                <?= $estatusId === $id ? 'checked' : '' ?>>
                            <span class="text-[14px] font-medium text-[#111823]"><?= $label ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <!-- Fecha (solo visible para estados 5 y 6) -->
                <div id="fechaPanel" class="mb-6 hidden">
                    <label class="mb-2 block text-[14px] font-semibold text-[#111823]" id="fechaLabel">Fecha</label>
                    <input type="date" id="fecha_evento"
                        class="h-[46px] w-full max-w-[280px] rounded-full bg-[#b8bec8] px-5 text-[#111823] outline-none focus:ring-2 focus:ring-[#0b2030]">
                </div>

                <div class="flex flex-wrap gap-4 items-center">
                    <?php if ($esPendiente): ?>
                        <button type="button" onclick="volverPaso1()"
                            class="h-[46px] rounded-full border-2 border-[#0b2030] px-8 text-[14px] font-bold text-[#0b2030] transition hover:bg-gray-100">
                            ← Volver
                        </button>
                    <?php endif; ?>
                    <button type="button" onclick="confirmarDictamen()"
                        class="h-[46px] rounded-full bg-[#0b2030] px-10 text-[14px] font-bold text-white transition hover:bg-[#142b3f]">
                        Confirmar dictamen
                    </button>
                </div>
            </div>
        </div>

        <!-- Formulario oculto que hace el POST -->
        <form id="dictamenForm" method="POST" action="/siniestro/estado" class="hidden">
            <input type="hidden" name="siniestro_id"  value="<?= (int)$siniestro['id'] ?>">
            <input type="hidden" name="estatus_id"    id="input_estatus_id">
            <input type="hidden" name="comentario"    id="input_comentario">
            <input type="hidden" name="fecha_evento"  id="input_fecha_evento">
        </form>
    <?php endif; ?>

</div>

<script>
function mostrarPaso2() {
    document.getElementById('paso1').classList.add('hidden');
    document.getElementById('paso2').classList.remove('hidden');
}

function volverPaso1() {
    document.getElementById('paso2').classList.add('hidden');
    document.getElementById('paso1').classList.remove('hidden');
}

function onEstatusChange(val) {
    const panel = document.getElementById('fechaPanel');
    const label = document.getElementById('fechaLabel');
    if (val === '5') {
        panel.classList.remove('hidden');
        label.textContent = 'Fecha estimada de terminación de reparación';
    } else if (val === '6') {
        panel.classList.remove('hidden');
        label.textContent = 'Fecha de pago';
    } else {
        panel.classList.add('hidden');
    }
}

function rechazarSiniestro() {
    Swal.fire({
        title: '¿Rechazar siniestro?',
        text: 'Esta acción es definitiva y no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, rechazar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc2626',
        cancelButtonColor:  '#6b7280',
    }).then(result => {
        if (!result.isConfirmed) return;
        document.getElementById('input_estatus_id').value  = 1;
        document.getElementById('input_comentario').value  = 'Siniestro rechazado por el supervisor';
        document.getElementById('input_fecha_evento').value = '';
        document.getElementById('dictamenForm').submit();
    });
}

function confirmarDictamen() {
    const radio = document.querySelector('input[name="estatus_radio"]:checked');
    if (!radio) {
        Swal.fire('Selección requerida', 'Debes elegir un tipo de dictamen.', 'warning');
        return;
    }
    const estatusId = radio.value;
    const requiereFecha = estatusId === '5' || estatusId === '6';
    const fecha = document.getElementById('fecha_evento').value;

    if (requiereFecha && !fecha) {
        Swal.fire('Fecha requerida', 'Debes indicar la fecha para este tipo de dictamen.', 'warning');
        return;
    }

    const etiquetas = {
        '2': 'Aceptado',
        '3': 'Aceptado con pago de deducible',
        '4': 'Aceptado sin pago de deducible',
        '5': 'Aplica pago para reparación',
        '6': 'Pérdida total',
    };

    Swal.fire({
        title: '¿Confirmar dictamen?',
        text: `Se registrará como: ${etiquetas[estatusId]}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#0b2030',
        cancelButtonColor:  '#6b7280',
    }).then(result => {
        if (!result.isConfirmed) return;
        document.getElementById('input_estatus_id').value   = estatusId;
        document.getElementById('input_comentario').value   = `Dictamen: ${etiquetas[estatusId]}`;
        document.getElementById('input_fecha_evento').value = fecha || '';
        document.getElementById('dictamenForm').submit();
    });
}
</script>

<script>
(function () {
    const evidencias = <?= $evidenciasJson ?>;
    let idx = 0;

    const empty   = document.getElementById('carouselEmpty');
    const img     = document.getElementById('carouselImg');
    const video   = document.getElementById('carouselVideo');
    const counter = document.getElementById('carouselCounter');

    function mostrar(i) {
        if (evidencias.length === 0) return;
        idx = (i + evidencias.length) % evidencias.length;
        const ev = evidencias[idx];

        img.classList.add('hidden');
        video.classList.add('hidden');
        empty.classList.add('hidden');

        if (ev.tipo === 'video') {
            video.src = ev.src;
            video.classList.remove('hidden');
        } else {
            img.src = ev.src;
            img.alt = ev.nombre;
            img.classList.remove('hidden');
        }
        counter.textContent = `${idx + 1} / ${evidencias.length}`;
    }

    window.cambiarEvidencia = function (dir) { mostrar(idx + dir); };

    if (evidencias.length > 0) {
        mostrar(0);
    } else {
        counter.textContent = '0 / 0';
    }
})();
</script>
