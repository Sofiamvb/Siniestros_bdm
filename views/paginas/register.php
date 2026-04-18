<main>
    <div class="register-wrapper">
        <div class="register-container">
            <a href="/" class="back-link">Regresar</a>

            <div class="left-panel-box">
                <img src="/img/seguro.jpg" alt="Seguro" class="left-panel-image">
            </div>

            <div class="right-panel-box">

                <form class="form-shell w-[80%]"
                      action="/register"
                      method="POST"
                      enctype="multipart/form-data"
                      id="registerForm">

                    <!-- Foto de perfil -->
                    <div class="profile-row">
                        <img src="/img/DefaultPFP.png" class="profile-preview" id="profilePreview" alt="Foto de perfil">
                        <label for="foto" class="profile-upload-btn">Agregar foto</label>
                        <input type="file" id="foto" name="foto" accept="image/*" hidden required>
                    </div>

                    <!-- Nombre -->
                    <input type="text"
                           name="nombre"
                           placeholder="Nombre(s)"
                           value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                           required
                           class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">

                    <!-- Apellidos -->
                    <div class="split-row">
                        <input type="text"
                               id="apellido_paterno"
                               placeholder="Apellido paterno"
                               required
                               class="input-field flex-1 rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                        <input type="text"
                               id="apellido_materno"
                               placeholder="Apellido materno"
                               class="input-field flex-1 rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                        <!-- Campo oculto que recibe PHP -->
                        <input type="hidden" name="apellidos" id="apellidos">
                    </div>

                    <!-- Fecha de nacimiento y Género -->
                    <div class="split-row">
                        <input type="date"
                               name="fecha_nacimiento"
                               value="<?= htmlspecialchars($_POST['fecha_nacimiento'] ?? '') ?>"
                               required
                               class="input-field flex-1 rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">

                        <select name="genero"
                                required
                                class="input-field flex-1 rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                            <option value="" disabled <?= empty($_POST['genero']) ? 'selected' : '' ?>>Género</option>
                            <option value="Masculino"  <?= ($_POST['genero'] ?? '') === 'Masculino'  ? 'selected' : '' ?>>Masculino</option>
                            <option value="Femenino"   <?= ($_POST['genero'] ?? '') === 'Femenino'   ? 'selected' : '' ?>>Femenino</option>
                            <option value="Otro"       <?= ($_POST['genero'] ?? '') === 'Otro'       ? 'selected' : '' ?>>Otro</option>
                        </select>
                    </div>

                    <!-- Alias -->
                    <input type="text"
                           name="alias"
                           placeholder="Alias de usuario"
                           value="<?= htmlspecialchars($_POST['alias'] ?? '') ?>"
                           required
                           class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">

                    <!-- Email -->
                    <input type="email"
                           name="email"
                           placeholder="Correo electrónico"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           required
                           class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">

                    <!-- Contraseñas -->
                    <input type="password"
                           name="password"
                           placeholder="Contraseña"
                           required
                           class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">

                    <input type="password"
                           name="confirmPassword"
                           placeholder="Confirmar contraseña"
                           required
                           class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">

                    <input type="hidden" name="rol_id" value="1">

                    <button type="submit" class="primary-pill-btn flex w-[30%] self-center justify-center">
                        Regístrate
                    </button>

                    <p class="text-center text-[12px]">
                        ¿Ya tienes cuenta?
                        <a href="/login" class="font-bold text-[#16425B] no-underline hover:underline">Inicia Sesión</a>
                    </p>

                </form>
            </div>
        </div>
    </div>

    <script>
        // Preview de la foto antes de subir
        document.getElementById('foto').addEventListener('change', (e) => {
            const [file] = e.target.files;
            if (file) document.getElementById('profilePreview').src = URL.createObjectURL(file);
        });

        // Unir apellido paterno + materno en el campo oculto antes de enviar
        document.getElementById('registerForm').addEventListener('submit', () => {
            const paterno = document.getElementById('apellido_paterno').value.trim();
            const materno = document.getElementById('apellido_materno').value.trim();
            document.getElementById('apellidos').value = materno
                ? `${paterno} ${materno}`
                : paterno;
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
            }).then(() => { window.location.href = '/login'; });
        <?php endif; ?>
    </script>
</main>
