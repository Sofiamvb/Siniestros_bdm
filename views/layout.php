<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BDM</title>
    <link href="/build/app.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/sweetalert2.min.css">
    <script src="/js/sweetalert2.min.js"></script>
    <script src="/js/registrar-siniestros.js" defer></script>
    <script src="/js/siniestros.js" defer></script>
    <script src="/js/landing.js"></script>
</head>

<body>
    <section class="relative bg-[#031a33] overflow-hidden">
        <div class="max-w-[1400px] mx-auto">
            <header class="relative z-10 flex items-center justify-between px-10 lg:px-16 py-8">
                <div class="flex items-center shrink-0">
                    <img src="/img/logo car.png" alt="SISA" class="w-[115px] lg:w-[130px] object-contain">
                </div>

                <nav class="hidden md:flex items-center gap-10 lg:gap-14 text-white text-[13px] font-medium">
                    <a href="/" class="hover:text-slate-300 transition">Home</a>
                    <a href="/#idSobreNosotros" class="hover:text-slate-300 transition">Sobre Nosotros</a>
                    <a href="/#idContacto" class="hover:text-slate-300 transition">Contacto</a>
                </nav>

                <div class="flex items-center gap-4 shrink-0">
                    <?php if (!empty($_SESSION['id'])): ?>
                        <a href="/logout" class="text-white text-[13px] font-medium hover:text-slate-300 transition">Cerrar sesión</a>
                        <a href="/perfil" class="hidden md:block text-white text-[13px] font-medium hover:underline"><?= htmlspecialchars($_SESSION['nombre']) ?></a>
                    <?php else: ?>
                        <a href="/login" class="text-white text-[13px] font-medium hover:text-slate-300 transition">Ingresar</a>
                    <?php endif; ?>
                    <img src="<?= !empty($_SESSION['foto']) ? htmlspecialchars($_SESSION['foto']) : '/img/DefaultPFP.png' ?>"
                        class="w-5 h-5 rounded-full object-cover" alt="Usuario">
                </div>
            </header>
        </div>

    </section>



    <?php echo $contenido ?>

    <footer class="bg-[#031a33] px-6 md:px-10 lg:px-16 py-8">
        <div class="max-w-[1200px] mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-10">
                <img src="/img/logo car.png" alt="SISA" class="w-[90px] md:w-[110px] object-contain">

                <nav class="flex items-center gap-6 md:gap-10 text-white text-xs md:text-sm">
                    <a href="/" class="hover:text-slate-300">Home</a>
                    <a href="/#idSobreNosotros" class="hover:text-slate-300">Nosotros</a>
                    <a href="/#idContacto" class="hover:text-slate-300">Contacto</a>
                </nav>
            </div>

            <div class="text-center md:text-right text-white">
                <img src="/img/audifonos car.png" alt="Soporte" class="w-12 h-12 mx-auto md:ml-auto md:mr-0 mb-2 object-contain">
                <p class="text-sm md:text-base font-medium">81-3155-5540</p>
                <p class="text-xs md:text-sm text-slate-300 max-w-[220px]">
                    Te ayudamos a gestionar tu relación con la aseguradora.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>