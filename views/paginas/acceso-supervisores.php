<main class="min-h-screen bg-[#e5e5e2]">

    <div class="mx-auto max-w-[1300px] px-6 py-8 lg:py-10">

        <div class="grid grid-cols-1 items-start gap-10 lg:grid-cols-[620px_1fr] lg:gap-8">
            
            <a href="/" class="w-fit text-[14px] font-semibold text-[#1b2b43] hover:underline lg:col-span-2">Regresar</a>

             <div class="flex justify-center lg:justify-start">
                <div class="flex min-h-[640px] w-full max-w-[560px] flex-col items-center justify-center rounded-[42px] bg-[#1c2d4a] px-10 py-12 shadow-[0_14px_30px_rgba(0,0,0,0.18)]">
                    <h1 class="mb-10 text-center text-[82px] font-extrabold leading-none tracking-wide text-white md:text-[96px]">
                        SISA
                    </h1>

                    <img src="/img/CAR.png" alt="Seguro" class="w-full max-w-[470px] object-contain">
                </div>
            </div>

           <div class="flex justify-center">
                <form
                    class="w-full max-w-[520px] flex flex-col gap-6 pt-16"
                    action="/acceso-supervisores"
                    method="POST"
                    id="tokenForm">

                    <h2 class="text-center text-[34px] md:text-[42px] font-bold text-[#162338]">
                        Acceso restringido
                    </h2>

                    <p class="text-center text-[16px] leading-snug text-[#7b7b7b]">
                        Esta sección es exclusiva para el registro de supervisores.<br>
                        Ingresa el token de autorización para continuar.
                    </p>

                    <div class="relative w-full pt-4">
                        <input type="password"
                               id="token"
                               name="token"
                               placeholder="Token de autorización"
                               required
                               class="w-full rounded-[18px] bg-[#f8f8f7] px-4 py-[15px] pr-[48px] text-[15px] text-slate-700 placeholder:text-slate-400 shadow-[0_4px_10px_rgba(0,0,0,0.14)] outline-none">
                        <button type="button"
                                onclick="togglePassword('token', 'eyeIcon')"
                                class="absolute right-[16px] top-[calc(50%+8px)] -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <button type="submit"  class="mt-2 w-full rounded-full bg-[#031a33] py-4 text-[16px] font-bold text-white shadow-[0_6px_12px_rgba(0,0,0,0.18)] hover:opacity-90">
                        Verificar token
                    </button>

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
                title: 'Acceso denegado',
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
    </script>
</main>
