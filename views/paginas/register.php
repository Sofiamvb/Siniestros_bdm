<main class="min-h-screen bg-[#e5e5e2]">

    <div class="mx-auto max-w-[1300px] px-6 py-8 lg:py-10">

        <div class="grid grid-cols-1 items-start gap-10 lg:grid-cols-[620px_1fr] lg:gap-8">

            <a href="/" class="w-fit text-[14px] font-semibold text-[#1b2b43] hover:underline lg:col-span-2">Regresar</a>

           <!-- PANEL IZQUIERDO -->
            <div class="flex justify-center lg:justify-start">
                <div class="flex min-h-[620px] w-full max-w-[560px] flex-col items-center justify-center rounded-[42px] bg-[#1c2d4a] px-10 py-12 shadow-[0_14px_30px_rgba(0,0,0,0.18)]">
                    <h1 class="mb-10 text-center text-[82px] font-extrabold leading-none tracking-wide text-white md:text-[96px]">
                        SISA
                    </h1>

                    <img src="/img/CAR.png" alt="Seguro" class="w-full max-w-[470px] object-contain">
                </div>
            </div>

            <div class="flex justify-center md:justify-start md:pt-2">

                <form class="flex w-full max-w-[520px] flex-col gap-4"
                    action="/register"
                    method="POST"
                    enctype="multipart/form-data"
                    id="registerForm">

                    <!-- Foto de perfil -->
                    <div class="profile-row">
                        <img src="/img/DFfoto.png" class="h-[46px] w-[46px] rounded-full bg-[#0c1e35] object-cover ring-4 ring-[#0c1e35]" id="profilePreview" alt="Foto de perfil">
                        <label for="foto" class="inline-flex min-w-[150px] items-center justify-center rounded-full bg-[#071b33] px-6 py-3 text-[15px] font-semibold text-white shadow-[0_6px_12px_rgba(0,0,0,0.18)] transition hover:bg-[#0b2647]">Agregar foto</label>
                        <input type="file" id="foto" name="foto" accept="image/*" hidden>
                    </div>

                    <!-- Nombre -->
                    <input type="text"
                        name="nombre"
                        placeholder="Nombre(s)"
                        value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                        required
                        class="w-full rounded-[14px] border-0 bg-[#f7f7f5] px-4 py-[14px] text-[14px] text-slate-700 shadow-[0_3px_8px_rgba(0,0,0,0.14)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1c2d4a]">

                    <!-- Apellidos -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <input type="text"
                            id="apellido_paterno"
                            placeholder="Apellido paterno"
                            required
                            class="w-full rounded-[14px] border-0 bg-[#f7f7f5] px-4 py-[14px] text-[14px] text-slate-700 shadow-[0_3px_8px_rgba(0,0,0,0.14)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1c2d4a]">
                        <input type="text"
                            id="apellido_materno"
                            placeholder="Apellido materno"
                            class="w-full rounded-[14px] border-0 bg-[#f7f7f5] px-4 py-[14px] text-[14px] text-slate-700 shadow-[0_3px_8px_rgba(0,0,0,0.14)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1c2d4a]">
                        <!-- Campo oculto que recibe PHP -->
                        <input type="hidden" name="apellidos" id="apellidos">
                    </div>

                    <!-- Fecha de nacimiento y Género -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <input type="date"
                            name="fecha_nacimiento"
                            value="<?= htmlspecialchars($_POST['fecha_nacimiento'] ?? '') ?>"
                            required
                            class="w-full rounded-[14px] border-0 bg-[#f7f7f5] px-4 py-[14px] text-[14px] text-slate-500 shadow-[0_3px_8px_rgba(0,0,0,0.14)] focus:outline-none focus:ring-2 focus:ring-[#1c2d4a]">

                        <select name="genero"
                            required
                            class="w-full rounded-[14px] border-0 bg-[#f7f7f5] px-4 py-[14px] text-[14px] text-slate-500 shadow-[0_3px_8px_rgba(0,0,0,0.14)] focus:outline-none focus:ring-2 focus:ring-[#1c2d4a]">
                            <option value="" disabled <?= empty($_POST['genero']) ? 'selected' : '' ?>>Género</option>
                            <option value="Masculino" <?= ($_POST['genero'] ?? '') === 'Masculino'  ? 'selected' : '' ?>>Masculino</option>
                            <option value="Femenino" <?= ($_POST['genero'] ?? '') === 'Femenino'   ? 'selected' : '' ?>>Femenino</option>
                            <option value="Otro" <?= ($_POST['genero'] ?? '') === 'Otro'       ? 'selected' : '' ?>>Otro</option>
                        </select>
                    </div>

                    <!-- Alias -->
                    <input type="text"
                        name="alias"
                        placeholder="Alias de usuario"
                        value="<?= htmlspecialchars($_POST['alias'] ?? '') ?>"
                        required
                        class="w-full rounded-[14px] border-0 bg-[#f7f7f5] px-4 py-[14px] text-[14px] text-slate-700 shadow-[0_3px_8px_rgba(0,0,0,0.14)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1c2d4a]">

                    <!-- Email -->
                    <input type="email"
                        name="email"
                        placeholder="Correo electrónico"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required
                        class="w-full rounded-[14px] border-0 bg-[#f7f7f5] px-4 py-[14px] text-[14px] text-slate-700 shadow-[0_3px_8px_rgba(0,0,0,0.14)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1c2d4a]">

                    <!-- Contraseñas -->
                    <div class="relative w-full">
                        <input type="password"
                            id="password"
                            name="password"
                            placeholder="Contraseña"
                            required
                            class="w-full rounded-[14px] border-0 bg-[#f7f7f5] px-4 py-[14px] pr-[48px] text-[14px] text-slate-700 shadow-[0_3px_8px_rgba(0,0,0,0.14)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1c2d4a]">
                        <button type="button"
                            onclick="togglePassword('password', 'eyeIcon1')"
                            class="absolute right-[16px] top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eyeIcon1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative w-full">
                        <input type="password"
                            id="confirmPassword"
                            name="confirmPassword"
                            placeholder="Confirmar contraseña"
                            required
                            class="w-full rounded-[14px] border-0 bg-[#f7f7f5] px-4 py-[14px] pr-[48px] text-[14px] text-slate-700 shadow-[0_3px_8px_rgba(0,0,0,0.14)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1c2d4a]">
                        <button type="button"
                            onclick="togglePassword('confirmPassword', 'eyeIcon2')"
                            class="absolute right-[16px] top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <!-- RFC -->
                    <input type="text"
                        name="rfc"
                        placeholder="RFC (ej. ABCD123456XY0)"
                        maxlength="13"
                        value="<?= htmlspecialchars($_POST['rfc'] ?? '') ?>"
                        required
                        class="w-full rounded-[14px] border-0 bg-[#f7f7f5] px-4 py-[14px] text-[14px] text-slate-700 shadow-[0_3px_8px_rgba(0,0,0,0.14)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1c2d4a]">

                    <!-- Licencia de conducir -->
                    <input type="text"
                        name="licencia_conducir"
                        placeholder="Número de licencia de conducir"
                        value="<?= htmlspecialchars($_POST['licencia_conducir'] ?? '') ?>"
                        required
                        class="w-full rounded-[14px] border-0 bg-[#f7f7f5] px-4 py-[14px] text-[14px] text-slate-700 shadow-[0_3px_8px_rgba(0,0,0,0.14)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1c2d4a]">

                    <!-- Dirección de facturación -->
                    <input type="text"
                        name="direccion_facturacion"
                        placeholder="Dirección de facturación"
                        value="<?= htmlspecialchars($_POST['direccion_facturacion'] ?? '') ?>"
                        required
                        class="w-full rounded-[14px] border-0 bg-[#f7f7f5] px-4 py-[14px] text-[14px] text-slate-700 shadow-[0_3px_8px_rgba(0,0,0,0.14)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1c2d4a]">

                    <input type="hidden" name="rol_id" value="1">

                    <button type="submit" class="mt-2 w-full rounded-full bg-[#071b33] px-6 py-4 text-[16px] font-bold text-white shadow-[0_6px_12px_rgba(0,0,0,0.18)] transition hover:bg-[#0b2647]">
                        Regístrate
                    </button>

                    <p class="pt-1 text-center text-[12px] text-[#1d2b44]">
                        ¿Ya tienes cuenta?
                        <a href="/login" class="font-bold text-[#1d2b44] no-underline hover:underline">Inicia Sesión</a>
                    </p>

                </form>
            </div>
        </div>
    </div>

    <script>
        // Mostrar / ocultar contraseña
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';

            // Ojo abierto → visible | Ojo tachado → oculto
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

        // Preview de la foto antes de subir
        document.getElementById('foto').addEventListener('change', (e) => {
            const [file] = e.target.files;
            if (file) document.getElementById('profilePreview').src = URL.createObjectURL(file);
        });

        // Unir apellido paterno + materno en el campo oculto antes de enviar
        document.getElementById('registerForm').addEventListener('submit', () => {
            const paterno = document.getElementById('apellido_paterno').value.trim();
            const materno = document.getElementById('apellido_materno').value.trim();
            document.getElementById('apellidos').value = materno ?
                `${paterno} ${materno}` :
                paterno;
        });

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
                title: '¡Registro exitoso!',
                text: '<?= htmlspecialchars($exito) ?>',
                icon: 'success',
                confirmButtonText: 'Iniciar sesión',
                confirmButtonColor: '#CE8F3A'
            }).then(() => {
                window.location.href = '/login';
            });
        <?php endif; ?>
    </script>
</main>