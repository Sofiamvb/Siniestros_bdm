<?php
$logoCompanias = [
    1 => '/img/GNPSeguros.png',
    2 => '/img/QualitasSeguros.png',
    3 => '/img/AXASeguros.png',
    4 => '/img/BBVASeguros.png',
    5 => '/img/HDISeguros.png',
];
$logoUrl = $logoCompanias[(int)($poliza['compania_id'] ?? 0)] ?? null;
?>
<main class="min-h-[calc(100vh-180px)] bg-[#e6e7e2] px-6 py-10 font-sans text-[#111823]">

    <!-- HEADER -->
    <div class="mx-auto mb-6 flex max-w-[900px] items-center justify-between">
        <a href="/siniestrosAsegurados"
            class="flex items-center gap-2 text-[13px] font-bold text-[#111823] no-underline hover:opacity-70">
            ← Volver
        </a>
        <span class="rounded-full px-3 py-1 text-[12px] font-bold <?= $poliza['activa'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' ?>">
            <?= $poliza['activa'] ? 'Póliza vigente' : 'Póliza caducada' ?>
        </span>
    </div>

    <div class="mx-auto max-w-[900px] flex flex-col gap-6">

        <!-- COMPAÑÍA -->
        <div class="rounded-[20px] bg-white px-8 py-6 shadow-[0_6px_20px_rgba(0,0,0,0.07)]">
            <div class="flex items-center gap-4 mb-5">
                <?php if ($logoUrl): ?>
                    <img src="<?= htmlspecialchars($logoUrl) ?>"
                         alt="<?= htmlspecialchars($poliza['compania']) ?>"
                         class="h-[52px] w-auto object-contain"
                         onerror="this.style.display='none'">
                <?php endif; ?>
                <h2 class="text-[16px] font-bold text-[#111823]">Aseguradora</h2>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Compañía</p>
                    <p class="text-[14px] font-bold text-[#111823]"><?= htmlspecialchars($poliza['compania']) ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Razón social</p>
                    <p class="text-[14px] text-[#111823]"><?= htmlspecialchars($poliza['razon_social'] ?? '—') ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">RFC</p>
                    <p class="text-[14px] text-[#111823]"><?= htmlspecialchars($poliza['compania_rfc'] ?? '—') ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Teléfono de cabina</p>
                    <p class="text-[14px] text-[#111823]"><?= htmlspecialchars($poliza['telefono_cabina'] ?? '—') ?></p>
                </div>
            </div>
        </div>

        <!-- PÓLIZA -->
        <div class="rounded-[20px] bg-white px-8 py-6 shadow-[0_6px_20px_rgba(0,0,0,0.07)]">
            <h2 class="mb-4 text-[16px] font-bold text-[#111823]">Datos de la póliza</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Número de póliza</p>
                    <p class="text-[14px] font-bold text-[#111823]"><?= htmlspecialchars($poliza['numero_poliza']) ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Placas</p>
                    <p class="text-[14px] text-[#111823]"><?= htmlspecialchars($poliza['placas']) ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Vigencia</p>
                    <p class="text-[14px] text-[#111823]"><?= htmlspecialchars($poliza['fecha_inicio']) ?> al <?= htmlspecialchars($poliza['fecha_fin']) ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Valor asegurado</p>
                    <p class="text-[14px] text-[#111823]">
                        <?= $poliza['valor_asegurado'] ? '$' . number_format((float)$poliza['valor_asegurado'], 2) : htmlspecialchars($poliza['suma_asegurada'] ?? '—') ?>
                    </p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Deducible</p>
                    <p class="text-[14px] text-[#111823]"><?= htmlspecialchars($poliza['deducible_porcentaje'] ?? '—') ?>%</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Siniestros registrados</p>
                    <p class="text-[14px] text-[#111823]"><?= (int)$poliza['total_siniestros'] ?></p>
                </div>
            </div>
        </div>

        <!-- SEGURO -->
        <div class="rounded-[20px] bg-white px-8 py-6 shadow-[0_6px_20px_rgba(0,0,0,0.07)]">
            <h2 class="mb-4 text-[16px] font-bold text-[#111823]">Plan de seguro</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Nombre del plan</p>
                    <p class="text-[14px] font-bold text-[#111823]"><?= htmlspecialchars($poliza['nombre_seguro']) ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Nivel de cobertura</p>
                    <p class="text-[14px] text-[#111823]"><?= htmlspecialchars($poliza['nivel']) ?></p>
                </div>
                <?php if (!empty($poliza['descripcion_cobertura'])): ?>
                <div class="md:col-span-2">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Descripción de cobertura</p>
                    <p class="text-[14px] text-[#111823] leading-relaxed"><?= htmlspecialchars($poliza['descripcion_cobertura']) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- VEHÍCULO -->
        <div class="rounded-[20px] bg-white px-8 py-6 shadow-[0_6px_20px_rgba(0,0,0,0.07)]">
            <h2 class="mb-4 text-[16px] font-bold text-[#111823]">Vehículo asegurado</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Marca</p>
                    <p class="text-[14px] font-bold text-[#111823]"><?= htmlspecialchars($poliza['marca']) ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Modelo</p>
                    <p class="text-[14px] text-[#111823]"><?= htmlspecialchars($poliza['modelo']) ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Año</p>
                    <p class="text-[14px] text-[#111823]"><?= htmlspecialchars($poliza['anio']) ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Versión</p>
                    <p class="text-[14px] text-[#111823]"><?= htmlspecialchars($poliza['version'] ?? '—') ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Tipo</p>
                    <p class="text-[14px] text-[#111823]"><?= htmlspecialchars($poliza['tipo_vehiculo'] ?? '—') ?></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af]">Pasajeros / Cilindros</p>
                    <p class="text-[14px] text-[#111823]"><?= htmlspecialchars($poliza['numero_pasajeros'] ?? '—') ?> / <?= htmlspecialchars($poliza['cilindros'] ?? '—') ?></p>
                </div>
            </div>
        </div>

    </div>
</main>
