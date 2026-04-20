<main>
    <div class="register-wrapper">
        <div class="register-container">
            <a href="javascript:history.back()" class="back-link">Regresar</a>

            <div class="left-panel-box">
                <img src="/img/seguro.jpg" alt="Seguro" class="left-panel-image">
            </div>

            <div class="right-panel-box">

                <form class="form-shell w-[80%]"
                      action="/perfil"
                      method="POST"
                      enctype="multipart/form-data"
                      id="editarPerfilForm">

                    <!-- Foto de perfil -->
                    <div class="profile-row">
                        <img src="<?= !empty($_SESSION['foto']) ? htmlspecialchars($_SESSION['foto']) : '/img/DefaultPFP.png' ?>"
                             class="profile-preview" id="profilePreview" alt="Foto de perfil">
                        <label for="foto" class="profile-upload-btn">Cambiar foto</label>
                        <input type="file" id="foto" name="foto" accept="image/*" hidden>
                    </div>

                    <!-- Nombre -->
                    <input type="text"
                           name="nombre"
                           placeholder="Nombre(s)"
                           value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>"
                           required
                           class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">

                    <!-- Apellidos -->
                    <div class="split-row">
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
                               class="input-field flex-1 rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                        <input type="text"
                               id="apellido_materno"
                               placeholder="Apellido materno"
                               value="<?= htmlspecialchars($materno) ?>"
                               class="input-field flex-1 rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                        <input type="hidden" name="apellidos" id="apellidos">
                    </div>

                    <!-- Fecha de nacimiento y Género -->
                    <div class="split-row">
                        <input type="date"
                               name="fecha_nacimiento"
                               value="<?= htmlspecialchars($usuario['fecha_nacimiento'] ?? '') ?>"
                               required
                               class="input-field flex-1 rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">

                        <select name="genero"
                                required
                                class="input-field flex-1 rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                            <option value="" disabled <?= empty($usuario['genero']) ? 'selected' : '' ?>>Género</option>
                            <option value="Masculino" <?= ($usuario['genero'] ?? '') === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                            <option value="Femenino"  <?= ($usuario['genero'] ?? '') === 'Femenino'  ? 'selected' : '' ?>>Femenino</option>
                            <option value="Otro"      <?= ($usuario['genero'] ?? '') === 'Otro'      ? 'selected' : '' ?>>Otro</option>
                        </select>
                    </div>

                    <!-- Alias -->
                    <input type="text"
                           name="alias"
                           placeholder="Alias de usuario"
                           value="<?= htmlspecialchars($usuario['alias'] ?? '') ?>"
                           required
                           class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">

                    <!-- Email (solo lectura) -->
                    <input type="email"
                           value="<?= htmlspecialchars($usuario['email'] ?? '') ?>"
                           class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)] bg-gray-100 cursor-not-allowed"
                           readonly disabled
                           title="El correo no puede modificarse">

                    <input type="hidden" name="accion" value="perfil">

                    <button type="submit" class="primary-pill-btn flex w-[40%] self-center justify-center">
                        Guardar cambios
                    </button>

                </form>

                <!-- ── Cambiar contraseña ── -->
                <form class="form-shell w-[80%] mt-[30px] border-t border-[#ddd] pt-[24px]"
                      action="/perfil"
                      method="POST"
                      id="formPassword">

                    <p class="text-[16px] font-semibold text-[#16425B] mb-[4px]">Cambiar contraseña</p>

                    <div class="relative w-full">
                        <input type="password" name="password_actual" id="pwActual"
                               placeholder="Contraseña actual" required
                               class="input-field w-full rounded-[20px] p-[14px] pr-[48px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                        <button type="button" onclick="togglePassword('pwActual','eye1')"
                                class="absolute right-[16px] top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="relative w-full">
                        <input type="password" name="password_nueva" id="pwNueva"
                               placeholder="Nueva contraseña" required
                               class="input-field w-full rounded-[20px] p-[14px] pr-[48px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                        <button type="button" onclick="togglePassword('pwNueva','eye2')"
                                class="absolute right-[16px] top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="relative w-full">
                        <input type="password" name="password_confirmar" id="pwConfirmar"
                               placeholder="Confirmar nueva contraseña" required
                               class="input-field w-full rounded-[20px] p-[14px] pr-[48px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                        <button type="button" onclick="togglePassword('pwConfirmar','eye3')"
                                class="absolute right-[16px] top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye3" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>

                    <input type="hidden" name="accion" value="password">

                    <button type="submit" class="primary-pill-btn flex w-[40%] self-center justify-center">
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
