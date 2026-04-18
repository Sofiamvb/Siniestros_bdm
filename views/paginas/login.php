<main>
    <div class="register-wrapper">
        <div class="register-container">
            <a href="/" class="back-link">Regresar</a>

            <div class="left-panel-box">
                <img src="/img/seguro.jpg" alt="Seguro" class="left-panel-image">
            </div>

            <div class="right-panel-box">
                <form class="form-shell w-[80%]"
                      action="/login"
                      method="POST"
                      id="loginForm">

                    <h2 class="text-[26px] font-bold text-[#16425B] text-center">Inicia sesión</h2>
                    <div class="h-[6px] w-full rounded-[5px] bg-[linear-gradient(to_right,#3A7CA5_0%,#81C3D7_50%,#81C3D7_100%)]"></div>

                    <input type="email"
                           name="email"
                           placeholder="Correo electrónico"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           required
                           class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">

                    <div class="relative w-full">
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Contraseña"
                               required
                               class="input-field w-full rounded-[20px] p-[14px] pr-[48px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                        <button type="button"
                                onclick="togglePassword('password', 'eyeIcon')"
                                class="absolute right-[16px] top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <a href="#" class="text-[13px] text-[#16425B] no-underline hover:underline self-end">
                        Recuperar contraseña
                    </a>

                    <button type="submit" class="primary-pill-btn flex w-[40%] self-center justify-center">
                        Ingresar
                    </button>

                    <div class="h-[2px] bg-[linear-gradient(to_right,#87b4c2,#71adbe)]"></div>

                    <p class="text-center text-[12px] text-[#16425B]">¿Aún no tienes cuenta?</p>

                    <a href="/register" class="secondary-pill-btn self-center text-center w-[40%]">
                        Regístrate
                    </a>

                </form>
            </div>
        </div>
    </div>

    <script>
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
                title: 'Error al ingresar',
                html: `<ul class="text-left text-sm list-disc pl-4">
                    <?php foreach ($errores as $error) : ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>`,
                icon: 'error',
                confirmButtonText: 'Intentar de nuevo',
                confirmButtonColor: '#CE8F3A'
            });
        <?php endif; ?>

        <?php if (!empty($redirectUrl)) : ?>
            window.location.href = '<?= $redirectUrl ?>';
        <?php endif; ?>
    </script>
</main>
