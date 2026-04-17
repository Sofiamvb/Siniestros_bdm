<main>

    <div class="register-wrapper">
        <div class="register-container">
            <a href="landing.html" class="back-link">Regresar</a>

            <div class="left-panel-box">
                <img src="/img/seguro.jpg" alt="Seguro" class="left-panel-image">
            </div>

            <div class="right-panel-box">
                <div class="profile-row">
                    <img src="/img/DefaultPFP.png" class="profile-preview" id="profilePreview" alt="Foto de perfil">
                    <label for="profileUpload" class="profile-upload-btn">Agregar foto</label>
                    <input type="file" id="profileUpload" accept="image/*" hidden>
                </div>

                <form class="form-shell w-[80%]">
                    <input type="text" placeholder="Nombre(s)" required class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">

                    <div class="split-row">
                        <input type="text" placeholder="Apellido paterno" required class="input-field flex-1 rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                        <input type="text" placeholder="Apellido materno" class="input-field flex-1 rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                    </div>

                    <div class="split-row">
                        <input type="date" required class="input-field flex-1 rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                        <input type="text" placeholder="Género" class="input-field flex-1 rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                    </div>

                    <input type="text" placeholder="Alias de usuario" required class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                    <input type="email" placeholder="Correo electrónico" required class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                    <input type="password" placeholder="Contraseña" required class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">
                    <input type="password" placeholder="Confirmar contraseña" required class="input-field rounded-[20px] p-[14px] shadow-[0_5px_10px_rgba(0,0,0,0.15)]">

                    <button type="submit" class="primary-pill-btn flex w-[30%] self-center justify-center">Regístrate</button>

                    <p class="text-center text-[12px]">
                        ¿Ya tienes cuenta?
                        <a href="landing.html" class="font-bold text-[#16425B] no-underline hover:underline">Inicia Sesión</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        const profileUpload = document.getElementById('profileUpload');
        const profilePreview = document.getElementById('profilePreview');

        profileUpload.addEventListener('change', (event) => {
            const [file] = event.target.files;
            if (!file) return;
            profilePreview.src = URL.createObjectURL(file);
        });

        const registerForm = document.querySelector('form');
        registerForm.addEventListener('submit', (event) => {
            event.preventDefault();

            Swal.fire({
                title: "Registro exitoso!",
                text: "Bienvenido!",
                icon: "success",
                confirmButtonText: "Continuar",
                confirmButtonColor: "#CE8F3A",
                timer: 2000,
                timerProgressBar: true
            });
        });
    </script>
</main>