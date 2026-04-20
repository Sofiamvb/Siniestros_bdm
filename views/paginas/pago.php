<main class="min-h-screen bg-[#E8F0F7] px-[80px] py-[60px]">
    <div class="mx-auto max-w-[700px]">

        <a href="/contratar?vehiculo_id=<?= $vehiculo->id ?>" class="mb-[10px] inline-block text-[14px] text-[#3A7CA5] hover:underline">← Cambiar seguro</a>
        <h1 class="mb-[4px] text-[30px] font-bold text-[#16425B]">Orden de compra</h1>
        <div class="mb-[32px] h-[4px] w-[80px] rounded-full bg-[#3A7CA5]"></div>

        <!-- Resumen -->
        <div class="mb-[24px] rounded-[20px] bg-white p-[28px] shadow-[0_4px_16px_rgba(0,0,0,0.08)]">
            <h2 class="mb-[16px] text-[16px] font-semibold text-[#333]">Resumen</h2>

            <div class="flex flex-col gap-[10px] text-[14px]">
                <div class="flex justify-between border-b border-[#eee] pb-[8px]">
                    <span class="text-[#777]">Vehículo</span>
                    <span class="font-semibold text-[#333]">
                        <?= htmlspecialchars($vehiculo->marca) ?> <?= htmlspecialchars($vehiculo->modelo) ?> <?= $vehiculo->anio ?>
                    </span>
                </div>
                <div class="flex justify-between border-b border-[#eee] pb-[8px]">
                    <span class="text-[#777]">Versión</span>
                    <span class="font-semibold text-[#333]"><?= htmlspecialchars($vehiculo->version) ?></span>
                </div>
                <div class="flex justify-between border-b border-[#eee] pb-[8px]">
                    <span class="text-[#777]">Aseguradora</span>
                    <span class="font-semibold text-[#333]"><?= htmlspecialchars($seguro->compania) ?></span>
                </div>
                <div class="flex justify-between border-b border-[#eee] pb-[8px]">
                    <span class="text-[#777]">Tipo de cobertura</span>
                    <span class="font-semibold text-[#333]"><?= htmlspecialchars($seguro->nivel) ?> — <?= htmlspecialchars($seguro->nombre_seguro) ?></span>
                </div>
                <div class="flex justify-between border-b border-[#eee] pb-[8px]">
                    <span class="text-[#777]">Deducible</span>
                    <span class="font-semibold text-[#333]"><?= htmlspecialchars($seguro->deducible_porcentaje) ?>%</span>
                </div>
                <div class="flex justify-between border-b border-[#eee] pb-[8px]">
                    <span class="text-[#777]">Vigencia</span>
                    <span class="font-semibold text-[#333]">
                        <?= date('d/m/Y') ?> al <?= date('d/m/Y', strtotime('+1 year')) ?>
                    </span>
                </div>
                <div class="flex justify-between pt-[4px]">
                    <span class="text-[16px] font-bold text-[#16425B]">Prima anual</span>
                    <span class="text-[20px] font-bold text-[#3A7CA5]">$<?= number_format($prima, 2) ?> MXN</span>
                </div>
            </div>
        </div>

        <!-- Formulario: placas + mock pago -->
        <div class="rounded-[20px] bg-white p-[28px] shadow-[0_4px_16px_rgba(0,0,0,0.08)]">
            <h2 class="mb-[16px] text-[16px] font-semibold text-[#333]">Datos del vehículo</h2>

            <?php if (!empty($errores)): ?>
            <div class="mb-[16px] rounded-[12px] bg-red-50 p-[14px] text-red-700">
                <ul class="list-disc pl-[18px] text-[13px]">
                    <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <form method="POST" action="/pago" id="formPago">
                <input type="hidden" name="vehiculo_id" value="<?= $vehiculo->id ?>">
                <input type="hidden" name="seguro_id"   value="<?= $seguro->id ?>">

                <div class="mb-[20px] flex flex-col gap-[6px]">
                    <label class="text-[13px] font-medium text-[#555]">Número de placas</label>
                    <input type="text"
                           name="placas"
                           placeholder="Ej. ABC-123-D"
                           maxlength="10"
                           value="<?= htmlspecialchars($_POST['placas'] ?? '') ?>"
                           required
                           class="rounded-[14px] border border-[#ccc] px-[16px] py-[12px] text-[#333] uppercase focus:border-[#3A7CA5] focus:outline-none">
                </div>

                <!-- Mock pago -->
                <div class="mb-[20px] rounded-[14px] border border-dashed border-[#3A7CA5] bg-[#f0f7fc] p-[16px]">
                    <p class="mb-[4px] text-[13px] font-semibold text-[#3A7CA5]">Simulación de pago</p>
                    <p class="text-[12px] text-[#777]">En un entorno real aquí aparecería la pasarela de pago. Para fines de demostración, confirma el pago con el botón de abajo.</p>
                    <p class="mt-[8px] text-[18px] font-bold text-[#16425B]">Total a pagar: $<?= number_format($prima, 2) ?> MXN</p>
                </div>

                <button type="submit"
                    class="w-full rounded-[25px] bg-[#3A7CA5] py-[14px] text-[16px] font-bold text-white shadow-[0_4px_12px_rgba(58,124,165,0.4)] transition hover:-translate-y-[2px]">
                    Confirmar pago y contratar
                </button>
            </form>
        </div>

    </div>
</main>

<script>
    document.getElementById('formPago').addEventListener('submit', function (e) {
        const placas = this.querySelector('[name="placas"]').value.trim();
        if (!placas) {
            e.preventDefault();
            Swal.fire({ title: 'Placas requeridas', text: 'Ingresa el número de placas para continuar.', icon: 'warning', confirmButtonColor: '#3A7CA5' });
        }
    });
</script>
