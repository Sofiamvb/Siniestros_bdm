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

    <header id="idHeaderCont" class="app-header">
        <div class="header-left">
            <img src="/img/SISA.png" alt="LOGO" id="idLogo" class="app-logo">
        </div>

        <nav class="header-group gap-[90px]">
            <a href="#idHome" class="relative text-[18px] text-black transition-all duration-300 ease-in-out hover:text-[#D9DCD6] after:absolute after:bottom-[-5px] after:left-0 after:h-[2px] after:w-0 after:bg-[#D9DCD6] after:transition-all after:duration-300 hover:after:w-full">Home</a>
            <a href="#idSobreNosotros" class="relative text-[18px] text-black transition-all duration-300 ease-in-out hover:text-[#D9DCD6] after:absolute after:bottom-[-5px] after:left-0 after:h-[2px] after:w-0 after:bg-[#D9DCD6] after:transition-all after:duration-300 hover:after:w-full">Sobre Nosotros</a>
            <a href="#idContacto" class="relative text-[18px] text-black transition-all duration-300 ease-in-out hover:text-[#D9DCD6] after:absolute after:bottom-[-5px] after:left-0 after:h-[2px] after:w-0 after:bg-[#D9DCD6] after:transition-all after:duration-300 hover:after:w-full">Contacto</a>
        </nav>

        <div class="header-user">
            <button class="ghost-action-btn" onclick="abrirModal()">Ingresar</button>
            <img src="/imag/DefaultPFP.png" class="user-avatar" alt="Usuario">
        </div>
    </header>


    <?php echo $contenido ?>

    <footer class="bg-[#87b4c2] py-[25px] text-center text-white">
        <div class="flex flex-col items-center gap-[10px]">
            <img src="/img/SISA.png" alt="Logo SISA" class="h-[45px]">
            <p class="m-0 text-[14px] opacity-90">Sistema Integral de Siniestros Automotrices © <span id="year">2026</span></p>
        </div>
    </footer>
</body>

</html>