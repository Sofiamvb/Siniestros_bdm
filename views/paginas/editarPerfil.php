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

                    <button type="submit" class="primary-pill-btn flex w-[40%] self-center justify-center">
                        Guardar cambios
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
    </script>
</main>
