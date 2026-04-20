<main>
    <div class="register-wrapper">
        <div class="register-container">
            <a href="/siniestrosSupervisores" class="back-link">Regresar</a>

            <div class="left-panel-box">
                <img src="/img/seguro.jpg" alt="Seguro" class="left-panel-image">
            </div>

            <div class="right-panel-box">
                <form class="form-shell w-[80%]"
                      action="/register/ajustadores"
                      method="POST"
                      enctype="multipart/form-data"
                      id="registerAjustadorForm">

                    <h2 class="text-[26px] font-bold text-[#16425B] text-center">Registrar Ajustador</h2>
                    <div class="h-[6px] w-full rounded-[5px] bg-[linear-gradient(to_right,#3A7CA5_0%,#81C3D7_50%,#81C3D7_100%)]"></div>

                    <!-- Foto de perfil -->
                    <div class="profile-row">
                        <img src="/img/DefaultPFP.png" class="profile-preview" id="profilePreview" alt="Foto de perfil">
                        <label for="foto" class="profile-upload-btn">Agregar foto</label>
                        <input type="file" id="foto" name="foto" accept="image/*" hidden>
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
                            <option value="Masculino" <?= ($_POST['genero'] ?? '') === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                            <option value="Femenino"  <?= ($_POST['genero'] ?? '') === 'Femenino'  ? 'selected' : '' ?>>Femenino</option>
                            <option value="Otro"      <?= ($_POST['genero'] ?? '') === 'Otro'      ? 'selected' : '' ?>>Otro</option>
                        </select>
                    </div>

                    <!-- Alias -->
                    <input type="text"
                           name="alias"
                           placeholder="Alias de usuario"
                           value="<?= htmlspecialchars($_POST['alias'] ?? '') ?>"
                           required
                           class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">

                    <!-- Zona de cobertura -->
                    <select name="zona_cobertura"
                            required
                            class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                        <option value="" disabled <?= empty($_POST['zona_cobertura']) ? 'selected' : '' ?>>Zona de cobertura</option>
                        <?php
                        $estados = [
                            'Aguascalientes','Baja California','Baja California Sur','Campeche',
                            'Chiapas','Chihuahua','Ciudad de México','Coahuila','Colima','Durango',
                            'Estado de México','Guanajuato','Guerrero','Hidalgo','Jalisco',
                            'Michoacán','Morelos','Nayarit','Nuevo León','Oaxaca','Puebla',
                            'Querétaro','Quintana Roo','San Luis Potosí','Sinaloa','Sonora',
                            'Tabasco','Tamaulipas','Tlaxcala','Veracruz','Yucatán','Zacatecas'
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
                           class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">

                    <!-- Contraseñas -->
                    <div class="relative w-full">
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Contraseña"
                               required
                               class="input-field w-full rounded-[20px] p-[14px] pr-[48px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
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
                               class="input-field w-full rounded-[20px] p-[14px] pr-[48px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
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

                    <button type="submit" class="primary-pill-btn flex w-[50%] self-center justify-center">
                        Registrar Ajustador
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
            document.getElementById('apellidos').value = materno
                ? `${paterno} ${materno}`
                : paterno;
        });

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.innerHTML = isHidden
                ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7
                            a9.956 9.956 0 012.293-3.95M6.938 6.938A9.956 9.956 0 0112 5
                            c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-1.357 2.574
                            M6.938 6.938L3 3m3.938 3.938L17 17" />
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>`
                : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
            }).then(() => { window.location.href = '/siniestrosSupervisores'; });
        <?php endif; ?>
    </script>
</main>
