<main class="min-h-screen bg-[#E8F0F7] px-[80px] py-[60px]">
    <div class="mx-auto max-w-[1000px]">

        <a href="/cotizar" class="mb-[10px] inline-block text-[14px] text-[#111823] hover:underline">← Volver a cotizar</a>
        <h1 class="mb-[4px] text-[30px] font-bold text-[#16425B]">Elige tu seguro</h1>
        <p class="mb-[8px] text-[15px] text-[#555]">
            <strong><?= htmlspecialchars($vehiculo->marca) ?> <?= htmlspecialchars($vehiculo->modelo) ?></strong>
            &nbsp;·&nbsp;<?= $vehiculo->anio ?>&nbsp;·&nbsp;<?= htmlspecialchars($vehiculo->version) ?>
        </p>
        <div class="mb-[36px] h-[4px] w-[80px] rounded-full bg-[#3A7CA5]"></div>

        <?php
        // Agrupar seguros por compañía
        $porCompania = [];
        foreach ($seguros as $s) {
            $porCompania[$s->compania][] = $s;
        }
        ?>

        <?php foreach ($porCompania as $nombreCompania => $productos): ?>
        <div class="mb-[32px]">
            <h2 class="mb-[16px] text-[18px] font-semibold text-[#16425B]"><?= htmlspecialchars($nombreCompania) ?></h2>
            <div class="grid grid-cols-1 gap-[16px] md:grid-cols-3">
                <?php foreach ($productos as $seguro): ?>
                <?php
                $colorNivel = match($seguro->nivel) {
                    'Básico'   => 'border-[#87b4c2] bg-white',
                    'Estándar' => 'border-[#3A7CA5] bg-white',
                    'Premium'  => 'border-[#16425B] bg-[linear-gradient(145deg,#16425B,#415A77)] text-white',
                    default    => 'border-[#ccc] bg-white',
                };
                $esPremium = $seguro->nivel === 'Premium';
                ?>
                <div class="flex flex-col justify-between rounded-[20px] border-2 <?= $colorNivel ?> p-[24px] shadow-[0_4px_16px_rgba(0,0,0,0.08)]">
                    <div>
                        <span class="mb-[8px] inline-block rounded-full <?= $esPremium ? 'bg-white/20 text-white' : 'bg-[#E8F0F7] text-[#3A7CA5]' ?> px-[12px] py-[4px] text-[12px] font-bold uppercase tracking-widest">
                            <?= htmlspecialchars($seguro->nivel) ?>
                        </span>
                        <p class="mt-[6px] text-[20px] font-bold <?= $esPremium ? 'text-white' : 'text-[#16425B]' ?>">
                            $<?= number_format($seguro->prima, 2) ?> <span class="text-[13px] font-normal <?= $esPremium ? 'text-white/70' : 'text-[#888]' ?>">MXN/año</span>
                        </p>
                        <p class="mt-[4px] text-[12px] <?= $esPremium ? 'text-white/70' : 'text-[#888]' ?>">
                            Deducible: <?= htmlspecialchars($seguro->deducible_porcentaje) ?>%
                        </p>
                        <p class="mt-[12px] text-[13px] leading-[1.5] <?= $esPremium ? 'text-white/90' : 'text-[#555]' ?>">
                            <?= htmlspecialchars($seguro->descripcion_cobertura) ?>
                        </p>
                    </div>

                    <a href="/pago?vehiculo_id=<?= $vehiculo->id ?>&seguro_id=<?= $seguro->id ?>"
                        class="mt-[20px] block rounded-[25px] <?= $esPremium ? 'bg-white text-[#16425B]' : 'bg-[#415A77] text-white' ?> py-[12px] text-center text-[14px] font-bold shadow-[0_4px_10px_rgba(0,0,0,0.15)] transition hover:-translate-y-[2px]">
                        Seleccionar
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
</main>
