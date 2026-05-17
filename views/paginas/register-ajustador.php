<main class="min-h-screen bg-[#e5e5e2]">
    <div class="mx-auto max-w-[1300px] px-6 py-8 lg:py-10">

        <div class="grid grid-cols-1 items-start gap-10 lg:grid-cols-[620px_1fr] lg:gap-14">

            <a href="/siniestrosSupervisores" class="w-fit text-[14px] font-semibold text-[#1b2b43] hover:underline lg:col-span-2">
                Regresar
            </a>

            <!-- PANEL IZQUIERDO -->
            <div class="flex justify-center lg:justify-start">
                <div class="flex min-h-[660px] w-full max-w-[560px] flex-col items-center justify-center rounded-[42px] bg-[#031a33] px-10 py-12 shadow-[0_14px_30px_rgba(0,0,0,0.18)]">
                    <h1 class="mb-10 text-center text-[82px] font-extrabold leading-none tracking-wide text-white md:text-[96px]">
                        SISA
                    </h1>

                    <img src="/img/car de lado.png" alt="Seguro" class="w-full max-w-[470px] object-contain">
                </div>
            </div>

            <!-- PANEL DERECHO -->
            <div class="flex justify-center">
                <form
                    class="w-full max-w-[520px] flex flex-col gap-5"
                    action="/register/ajustadores"
                    method="POST"
                    enctype="multipart/form-data"
                    id="registerAjustadorForm">

                    <div class="flex flex-col gap-3">
                        <h2 class="text-center text-[28px] font-bold text-[#162338] md:text-[30px]">
                            Registra un ajustador
                        </h2>
                        <div class="h-[6px] w-full rounded-full bg-[linear-gradient(to_right,#324968_0%,#6d819f_55%,#f1f1f1_100%)]"></div>
                    </div>

                    <!-- Foto de perfil -->
                    <div class="flex items-center gap-4">
                        <img
                            src="/img/DFfoto.png" class="h-[46px] w-[46px] rounded-full bg-[#0c1e35] object-cover ring-4 ring-[#0c1e35]"
                            id="profilePreview"
                            alt="Foto de perfil">

                        <label
                            for="foto"
                            class="inline-flex cursor-pointer items-center justify-center rounded-full bg-[#031a33] px-8 py-3 text-[15px] font-bold text-white shadow-[0_6px_12px_rgba(0,0,0,0.18)] hover:opacity-90">
                            Regístrate
                        </label>

                        <input type="file" id="foto" name="foto" accept="image/*" hidden>
                    </div>

                    <!-- Nombre -->
                    <input type="text"
                        name="nombre"
                        placeholder="Nombre(s)"
                        value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                        required
                         class="w-full rounded-[18px] bg-[#f8f8f7] px-4 py-[15px] text-[15px] text-slate-700 placeholder:text-slate-400 shadow-[0_4px_10px_rgba(0,0,0,0.14)] outline-none">

                    <!-- Apellidos -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <input type="text"
                            id="apellido_paterno"
                            placeholder="Apellido paterno"
                            required
                             class="w-full rounded-[18px] bg-[#f8f8f7] px-4 py-[15px] text-[15px] text-slate-700 placeholder:text-slate-400 shadow-[0_4px_10px_rgba(0,0,0,0.14)] outline-none">
                        <input type="text"
                            id="apellido_materno"
                            placeholder="Apellido materno"
                             class="w-full rounded-[18px] bg-[#f8f8f7] px-4 py-[15px] text-[15px] text-slate-700 placeholder:text-slate-400 shadow-[0_4px_10px_rgba(0,0,0,0.14)] outline-none">
                        <input type="hidden" name="apellidos" id="apellidos">
                    </div>

                    <!-- Fecha de nacimiento y Género -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <input type="date"
                            name="fecha_nacimiento"
                            value="<?= htmlspecialchars($_POST['fecha_nacimiento'] ?? '') ?>"
                            required
                            class="w-full rounded-[18px] bg-[#f8f8f7] px-4 py-[15px] text-[15px] text-slate-500 shadow-[0_4px_10px_rgba(0,0,0,0.14)] outline-none">

                        <select name="genero"
                            required
                            class="w-full rounded-[18px] bg-[#f8f8f7] px-4 py-[15px] text-[15px] text-slate-500 shadow-[0_4px_10px_rgba(0,0,0,0.14)] outline-none">
                            <option value="" disabled <?= empty($_POST['genero']) ? 'selected' : '' ?>>Género</option>
                            <option value="Masculino" <?= ($_POST['genero'] ?? '') === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                            <option value="Femenino" <?= ($_POST['genero'] ?? '') === 'Femenino'  ? 'selected' : '' ?>>Femenino</option>
                            <option value="Otro" <?= ($_POST['genero'] ?? '') === 'Otro'      ? 'selected' : '' ?>>Otro</option>
                        </select>
                    </div>

                    <!-- Alias -->
                    <input type="text"
                        name="alias"
                        placeholder="Alias de usuario"
                        value="<?= htmlspecialchars($_POST['alias'] ?? '') ?>"
                        required
                        class="w-full rounded-[18px] bg-[#f8f8f7] px-4 py-[15px] text-[15px] text-slate-700 placeholder:text-slate-400 shadow-[0_4px_10px_rgba(0,0,0,0.14)] outline-none">

                    <!-- Zona de cobertura -->
                    <select name="zona_cobertura"
                        required
                        class="w-full rounded-[18px] bg-[#f8f8f7] px-4 py-[15px] text-[15px] text-slate-700 placeholder:text-slate-400 shadow-[0_4px_10px_rgba(0,0,0,0.14)] outline-none">
                        <option value="" disabled <?= empty($_POST['zona_cobertura']) ? 'selected' : '' ?>>Zona de cobertura</option>
                        <?php
                        $estados = [
                            'Aguascalientes',
                            'Baja California',
                            'Baja California Sur',
                            'Campeche',
                            'Chiapas',
                            'Chihuahua',
                            'Ciudad de México',
                            'Coahuila',
                            'Colima',
                            'Durango',
                            'Estado de México',
                            'Guanajuato',
                            'Guerrero',
                            'Hidalgo',
                            'Jalisco',
                            'Michoacán',
                            'Morelos',
                            'Nayarit',
                            'Nuevo León',
                            'Oaxaca',
                            'Puebla',
                            'Querétaro',
                            'Quintana Roo',
                            'San Luis Potosí',
                            'Sinaloa',
                            'Sonora',
                            'Tabasco',
                            'Tamaulipas',
                            'Tlaxcala',
                            'Veracruz',
                            'Yucatán',
                            'Zacatecas'
                        ];
                        foreach ($estados as $estado): ?>
                            <option value="<?= $estado ?>" <?= ($_POST['zona_cobertura'] ?? '') === $estado ? 'selected' : '' ?>>
                                <?= $estado ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Email -->
                    <input type="email"
                        name="email"
                        placeholder="Correo electrónico"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required
                        class="w-full rounded-[18px] bg-[#f8f8f7] px-4 py-[15px] text-[15px] text-slate-700 placeholder:text-slate-400 shadow-[0_4px_10px_rgba(0,0,0,0.14)] outline-none">

                    <!-- Contraseñas -->
                    <div class="relative w-full">
                        <input type="password"
                            id="password"
                            name="password"
                            placeholder="Contraseña"
                            required
                            class="w-full rounded-[18px] bg-[#f8f8f7] px-4 py-[15px] text-[15px] text-slate-700 placeholder:text-slate-400 shadow-[0_4px_10px_rgba(0,0,0,0.14)] outline-none">
                        <button type="button"
                            onclick="togglePassword('password', 'eyeIcon1')"
                            class="absolute right-[16px] top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eyeIcon1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative w-full">
                        <input type="password"
                            id="confirmPassword"
                            name="confirmPassword"
                            placeholder="Confirmar contraseña"
                            required
                            class="w-full rounded-[18px] bg-[#f8f8f7] px-4 py-[15px] pr-[48px] text-[15px] text-slate-700 placeholder:text-slate-400 shadow-[0_4px_10px_rgba(0,0,0,0.14)] outline-none">
                        <button type="button"
                            onclick="togglePassword('confirmPassword', 'eyeIcon2')"
                            class="absolute right-[16px] top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <input type="hidden" name="rol_id" value="2">

                    <button type="submit" class="mt-2 w-full rounded-full bg-[#031a33] py-4 text-[16px] font-bold text-white shadow-[0_6px_12px_rgba(0,0,0,0.18)] hover:opacity-90">
                        Registrar
                    </button>

                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('foto').addEventListener('change', (e) => {
            const [file] = e.target.files;
            if (file) document.getElementById('profilePreview').src = URL.createObjectURL(file);
        });

        document.getElementById('registerAjustadorForm').addEventListener('submit', () => {
            const paterno = document.getElementById('apellido_paterno').value.trim();
            const materno = document.getElementById('apellido_materno').value.trim();
            document.getElementById('apellidos').value = materno ?
                `${paterno} ${materno}` :
                paterno;
        });

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.innerHTML = isHidden ?
                `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7
                            a9.956 9.956 0 012.293-3.95M6.938 6.938A9.956 9.956 0 0112 5
                            c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-1.357 2.574
                            M6.938 6.938L3 3m3.938 3.938L17 17" />
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>` :
                `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                            -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
        }

        <?php if (!empty($errores)) : ?>
            Swal.fire({
                title: 'Revisa el formulario',
                html: `<ul class="text-left text-sm list-disc pl-4">
                    <?php foreach ($errores as $error) : ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>`,
                icon: 'error',
                confirmButtonText: 'Corregir',
                confirmButtonColor: '#CE8F3A'
            });
        <?php endif; ?>

        <?php if (!empty($exito)) : ?>
            Swal.fire({
                title: '¡Ajustador registrado!',
                text: '<?= htmlspecialchars($exito) ?>',
                icon: 'success',
                confirmButtonText: 'Continuar',
                confirmButtonColor: '#CE8F3A'
            }).then(() => {
                window.location.href = '/siniestrosSupervisores';
            });
        <?php endif; ?>
    </script>
</main>