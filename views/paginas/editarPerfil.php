<main class="min-h-screen bg-[#e3e4df] px-6 py-8 md:px-10 md:py-10">
    <div class="mx-auto max-w-[1320px]">
        <div class="relative grid min-h-[calc(100vh-5rem)] grid-cols-1 items-center gap-10 lg:grid-cols-[620px_minmax(0,520px)] lg:gap-12">
            <a href="javascript:history.back()"
                class="absolute left-[50px] top-[80px] text-[14px] font-bold text-[#172238] no-underline hover:underline">
                Regresar
            </a>

            <div class="flex justify-center lg:justify-start pt-10 lg:pt-0">
                <div class="relative flex h-[620px] w-full max-w-[560px] items-center justify-center overflow-hidden rounded-[58px] bg-[#031a33] shadow-sm">

                    <!-- Texto SISA -->
                    <h1 class="absolute top-[70px] text-[72px] font-extrabold text-white tracking-wide">
                        SISA
                    </h1>

                    <!-- Imagen -->
                    <img src="/img/car de lado.png"
                        alt="Seguro"
                        class="h-auto w-[88%] object-contain mt-[80px]">
                </div>
            </div>

            <div class="right-panel-box flex w-full flex-col items-center justify-center pt-10 lg:pt-0">

                <form class="form-shell flex w-full max-w-[470px] flex-col gap-[14px]"
                    action="/perfil"
                    method="POST"
                    enctype="multipart/form-data"
                    id="editarPerfilForm">

                    <!-- Foto de perfil -->
                    <div class="profile-row mb-[18px] flex items-center gap-[24px]">
                        <img src="<?= !empty($_SESSION['foto']) ? htmlspecialchars($_SESSION['foto']) : '/img/DefaultPFP.png' ?>"
                            class="profile-preview h-[82px] w-[82px] rounded-full object-cover" id="profilePreview" alt="Foto de perfil">
                        <label for="foto" class="profile-upload-btn cursor-pointer rounded-full bg-[#0c1f2f] px-[24px] py-[11px] text-[14px] font-bold text-white shadow-[0_5px_8px_rgba(0,0,0,0.18)] transition hover:bg-[#142b3f]">Cambiar foto</label>
                        <input type="file" id="foto" name="foto" accept="image/*" hidden>
                    </div>

                    <!-- Nombre -->
                    <input type="text"
                        name="nombre"
                        placeholder="Nombre(s)"
                        value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>"
                        required
                        class="input-field h-[43px] w-full rounded-[18px] border-0 bg-white px-[16px] text-[13px] text-[#1f2937] shadow-[0_4px_7px_rgba(0,0,0,0.18)] outline-none placeholder:text-[#9ca3af]">

                    <!-- Apellidos -->
                    <div class="split-row grid grid-cols-1 gap-[14px] sm:grid-cols-2">
                        <?php
                        $partes   = explode(' ', $usuario['apellidos'] ?? '', 2);
                        $paterno  = $partes[0] ?? '';
                        $materno  = $partes[1] ?? '';
                        ?>
                        <input type="text"
                            id="apellido_paterno"
                            placeholder="Apellido paterno"
                            value="<?= htmlspecialchars($paterno) ?>"
                            required
                            class="input-field h-[43px] w-full rounded-[18px] border-0 bg-white px-[16px] text-[13px] text-[#1f2937] shadow-[0_4px_7px_rgba(0,0,0,0.18)] outline-none placeholder:text-[#9ca3af]">
                        <input type="text"
                            id="apellido_materno"
                            placeholder="Apellido materno"
                            value="<?= htmlspecialchars($materno) ?>"
                            class="input-field h-[43px] w-full rounded-[18px] border-0 bg-white px-[16px] text-[13px] text-[#1f2937] shadow-[0_4px_7px_rgba(0,0,0,0.18)] outline-none placeholder:text-[#9ca3af]">
                        <input type="hidden" name="apellidos" id="apellidos">
                    </div>

                    <!-- Fecha de nacimiento y Género -->
                    <div class="split-row grid grid-cols-1 gap-[14px] sm:grid-cols-2">
                        <input type="date"
                            name="fecha_nacimiento"
                            value="<?= htmlspecialchars($usuario['fecha_nacimiento'] ?? '') ?>"
                            required
                            class="input-field h-[43px] w-full rounded-[18px] border-0 bg-white px-[16px] text-[13px] text-[#9ca3af] shadow-[0_4px_7px_rgba(0,0,0,0.18)] outline-none">

                        <select name="genero"
                            required
                            class="input-field h-[43px] w-full rounded-[18px] border-0 bg-white px-[16px] text-[13px] text-[#9ca3af] shadow-[0_4px_7px_rgba(0,0,0,0.18)] outline-none">
                            <option value="" disabled <?= empty($usuario['genero']) ? 'selected' : '' ?>>Género</option>
                            <option value="Masculino" <?= ($usuario['genero'] ?? '') === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                            <option value="Femenino" <?= ($usuario['genero'] ?? '') === 'Femenino'  ? 'selected' : '' ?>>Femenino</option>
                            <option value="Otro" <?= ($usuario['genero'] ?? '') === 'Otro'      ? 'selected' : '' ?>>Otro</option>
                        </select>
                    </div>

                    <!-- Alias -->
                    <input type="text"
                        name="alias"
                        placeholder="Alias de usuario"
                        value="<?= htmlspecialchars($usuario['alias'] ?? '') ?>"
                        required
                        class="input-field h-[43px] w-full rounded-[18px] border-0 bg-white px-[16px] text-[13px] text-[#1f2937] shadow-[0_4px_7px_rgba(0,0,0,0.18)] outline-none placeholder:text-[#9ca3af]">

                    <!-- Email (solo lectura) -->
                    <input type="email"
                        value="<?= htmlspecialchars($usuario['email'] ?? '') ?>"
                        class="input-field h-[43px] w-full cursor-not-allowed rounded-[18px] border-0 bg-white px-[16px] text-[13px] text-[#9ca3af] shadow-[0_4px_7px_rgba(0,0,0,0.18)] outline-none"
                        readonly disabled
                        title="El correo no puede modificarse">

                    <input type="hidden" name="accion" value="perfil">

                    <button type="submit"
                        class="primary-pill-btn mt-[18px] flex h-[43px] w-full items-center justify-center rounded-full bg-[#0c1f2f] text-[14px] font-bold text-white shadow-[0_5px_8px_rgba(0,0,0,0.18)] transition hover:bg-[#142b3f]">
                        Guardar
                    </button>

                </form>

                <!-- ── Cambiar contraseña ── -->
                <form class="form-shell mt-[48px] flex w-full max-w-[470px] flex-col gap-[14px]"
                    action="/perfil"
                    method="POST"
                    id="formPassword">

                    <p class="mb-[4px] text-[15px] font-bold text-[#172238]">
                        Cambiar contraseña
                    </p>

                    <div class="relative w-full">
                        <input type="password" name="password_actual" id="pwActual"
                            placeholder="Contraseña actual" required
                            class="input-field h-[43px] w-full rounded-[18px] border-0 bg-white px-[16px] pr-[48px] text-[13px] text-[#1f2937] shadow-[0_4px_7px_rgba(0,0,0,0.18)] outline-none placeholder:text-[#9ca3af]">
                        <button type="button" onclick="togglePassword('pwActual','eye1')"
                            class="absolute right-[16px] top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative w-full">
                        <input type="password" name="password_nueva" id="pwNueva"
                            placeholder="Nueva contraseña" required
                            class="input-field h-[43px] w-full rounded-[18px] border-0 bg-white px-[16px] pr-[48px] text-[13px] text-[#1f2937] shadow-[0_4px_7px_rgba(0,0,0,0.18)] outline-none placeholder:text-[#9ca3af]">
                        <button type="button" onclick="togglePassword('pwNueva','eye2')"
                            class="absolute right-[16px] top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative w-full">
                        <input type="password" name="password_confirmar" id="pwConfirmar"
                            placeholder="Confirmar nueva contraseña" required
                            class="input-field h-[43px] w-full rounded-[18px] border-0 bg-white px-[16px] pr-[48px] text-[13px] text-[#1f2937] shadow-[0_4px_7px_rgba(0,0,0,0.18)] outline-none placeholder:text-[#9ca3af]">
                        <button type="button" onclick="togglePassword('pwConfirmar','eye3')"
                            class="absolute right-[16px] top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye3" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <input type="hidden" name="accion" value="password">

                    <button type="submit"
                        class="primary-pill-btn mt-[18px] flex h-[43px] w-full items-center justify-center rounded-full bg-[#0c1f2f] text-[14px] font-bold text-white shadow-[0_5px_8px_rgba(0,0,0,0.18)] transition hover:bg-[#142b3f]">
                        Cambiar contraseña
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

        document.getElementById('editarPerfilForm').addEventListener('submit', () => {
            const paterno = document.getElementById('apellido_paterno').value.trim();
            const materno = document.getElementById('apellido_materno').value.trim();
            document.getElementById('apellidos').value = materno ? `${paterno} ${materno}` : paterno;
        });

        <?php if (!empty($errores)): ?>
            Swal.fire({
                title: 'Revisa el formulario',
                html: `<ul class="text-left text-sm list-disc pl-4">
                <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>`,
                icon: 'error',
                confirmButtonText: 'Corregir',
                confirmButtonColor: '#16425B'
            });
        <?php endif; ?>

        <?php if (!empty($exito)): ?>
            Swal.fire({
                title: '¡Perfil actualizado!',
                text: '<?= htmlspecialchars($exito) ?>',
                icon: 'success',
                confirmButtonColor: '#16425B'
            });
        <?php endif; ?>

        <?php if (!empty($exitoPassword)): ?>
            Swal.fire({
                title: '¡Contraseña actualizada!',
                text: '<?= htmlspecialchars($exitoPassword) ?>',
                icon: 'success',
                confirmButtonColor: '#16425B'
            });
        <?php endif; ?>
    </script>
</main>