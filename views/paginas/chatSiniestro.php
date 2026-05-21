<?php
$roles = [1 => 'Asegurado', 2 => 'Ajustador', 3 => 'Supervisor'];

// Convertir imágenes BLOB a data URI para los mensajes iniciales
$mensajesIniciales = json_encode(array_map(function ($m) {
    if (!empty($m['imagen'])) {
        $m['imagen_src'] = \Model\ActiveRecord::blobToImg($m['imagen'], $m['imagen_mime'] ?? 'image/jpeg');
    }
    unset($m['imagen']);
    return [
        'id'         => $m['id'],
        'usuario_id' => $m['usuario_id'],
        'mensaje'    => $m['mensaje'],
        'imagen_src' => $m['imagen_src'] ?? null,
        'video_ruta' => $m['video_ruta'] ?? null,
        'created_at' => $m['created_at'],
        'nombre'     => $m['nombre'],
        'apellidos'  => $m['apellidos'],
        'rol_id'     => $m['rol_id'],
    ];
}, $mensajes));
?>
<main class="min-h-[calc(100vh-180px)] bg-[#e6e7e2] flex flex-col">

    <!-- HEADER -->
    <div class="bg-white px-6 py-4 shadow-sm flex items-center justify-between">
        <a href="<?= htmlspecialchars($volverUrl) ?>"
           class="flex items-center gap-2 text-[13px] font-bold text-[#111823] no-underline hover:opacity-70">
            ← Volver
        </a>
        <div class="text-center">
            <p class="text-[13px] font-bold text-[#111823]">Chat del siniestro</p>
            <p class="text-[12px] text-[#6b7280]"><?= htmlspecialchars($siniestro['numero_reporte'] ?? '') ?></p>
        </div>
        <div class="w-16"></div>
    </div>

    <!-- PARTICIPANTES -->
    <div class="bg-white border-b border-gray-100 px-6 py-3">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-[#9ca3af] mb-2">Participantes</p>
        <div class="flex flex-wrap gap-3">
            <?php if (!empty($siniestro['ajustador_nombre'])): ?>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#dbeafe] px-3 py-1 text-[12px] font-semibold text-[#1e40af]">
                    <span class="h-2 w-2 rounded-full bg-[#1e40af]"></span>
                    Ajustador: <?= htmlspecialchars($siniestro['ajustador_nombre']) ?>
                </span>
            <?php endif; ?>
            <?php if (!empty($siniestro['supervisor_nombre'])): ?>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#fef3c7] px-3 py-1 text-[12px] font-semibold text-[#92400e]">
                    <span class="h-2 w-2 rounded-full bg-[#92400e]"></span>
                    Supervisor: <?= htmlspecialchars($siniestro['supervisor_nombre']) ?>
                </span>
            <?php endif; ?>
            <?php if (!empty($siniestro['duenio_nombre'])): ?>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#d1fae5] px-3 py-1 text-[12px] font-semibold text-[#065f46]">
                    <span class="h-2 w-2 rounded-full bg-[#065f46]"></span>
                    Asegurado: <?= htmlspecialchars($siniestro['duenio_nombre']) ?>
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- MENSAJES -->
    <div id="mensajesLista" class="flex-1 overflow-y-auto px-6 py-6 flex flex-col gap-3">
        <p id="mensajesVacio" class="text-center text-[13px] text-[#9ca3af] mt-10 hidden">
            Aún no hay mensajes. ¡Sé el primero en escribir!
        </p>
    </div>

    <!-- LIGHTBOX para imágenes del chat -->
    <div id="chatLightbox" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/85"
         onclick="document.getElementById('chatLightbox').classList.add('hidden')">
        <img id="chatLightboxImg" src="" class="max-h-[90vh] max-w-[90vw] object-contain rounded-[12px]">
    </div>

    <!-- INPUT -->
    <div class="bg-white border-t border-gray-200 px-6 py-4">
        <div class="mx-auto flex max-w-[860px] flex-col gap-2">
            <!-- Preview del adjunto seleccionado -->
            <div id="adjuntoPreview" class="hidden flex items-center gap-2 text-[12px] text-[#6b7280] px-2">
                <span id="adjuntoNombre" class="truncate max-w-[300px]"></span>
                <button onclick="limpiarAdjunto()" class="text-[#ef4444] font-bold hover:opacity-70">✕</button>
            </div>
            <div class="flex items-center gap-3">
                <!-- Input archivo oculto -->
                <input type="file" id="adjuntoInput" accept="image/*,video/*" class="hidden">
                <!-- Botón clip -->
                <button type="button" id="adjuntoBtn"
                    onclick="document.getElementById('adjuntoInput').click()"
                    class="h-[46px] w-[46px] shrink-0 rounded-full bg-[#f3f4f6] flex items-center justify-center text-[20px] transition hover:bg-gray-200"
                    title="Adjuntar imagen o video">
                    📎
                </button>
                <input id="chatInput" type="text"
                    placeholder="Escribe un mensaje..."
                    class="flex-1 h-[46px] rounded-full bg-[#f3f4f6] px-5 text-[14px] text-[#111823] outline-none focus:ring-2 focus:ring-[#0b2030]">
                <button id="chatEnviar"
                    class="h-[46px] rounded-full bg-[#0b2030] px-8 text-[14px] font-bold text-white transition hover:bg-[#142b3f]">
                    Enviar
                </button>
            </div>
        </div>
    </div>

</main>

<script>
(function () {
    const MI_ID        = <?= (int) $_SESSION['id'] ?>;
    const SINIESTRO_ID = <?= (int) $siniestro['id'] ?>;
    const ROLES        = { 1: 'Asegurado', 2: 'Ajustador', 3: 'Supervisor' };

    const lista      = document.getElementById('mensajesLista');
    const vacio      = document.getElementById('mensajesVacio');
    const input      = document.getElementById('chatInput');
    const btnEnviar  = document.getElementById('chatEnviar');
    const adjuntoInput   = document.getElementById('adjuntoInput');
    const adjuntoPreview = document.getElementById('adjuntoPreview');
    const adjuntoNombre  = document.getElementById('adjuntoNombre');

    let ultimoId = 0;

    // Mensajes iniciales desde PHP
    const iniciales = <?= $mensajesIniciales ?>;
    if (iniciales.length > 0) {
        renderMensajes(iniciales);
        ultimoId = iniciales[iniciales.length - 1].id;
    } else {
        vacio.classList.remove('hidden');
    }

    // Preview del adjunto seleccionado
    adjuntoInput.addEventListener('change', function () {
        const f = this.files[0];
        if (f) {
            adjuntoNombre.textContent = f.name;
            adjuntoPreview.classList.remove('hidden');
        } else {
            limpiarAdjunto();
        }
    });

    window.limpiarAdjunto = function () {
        adjuntoInput.value = '';
        adjuntoPreview.classList.add('hidden');
        adjuntoNombre.textContent = '';
    };

    // Polling cada 5 segundos
    setInterval(async () => {
        try {
            const r = await fetch(`/api/chat?siniestro_id=${SINIESTRO_ID}&desde_id=${ultimoId}`);
            const d = await r.json();
            if (d.mensajes && d.mensajes.length > 0) {
                vacio.classList.add('hidden');
                renderMensajes(d.mensajes);
                ultimoId = d.mensajes[d.mensajes.length - 1].id;
            }
        } catch { /* silencioso */ }
    }, 5000);

    // Enviar mensaje
    async function enviar() {
        const texto   = input.value.trim();
        const archivo = adjuntoInput.files[0];
        if (!texto && !archivo) return;

        const body = new FormData();
        body.append('siniestro_id', SINIESTRO_ID);
        body.append('mensaje', texto);
        if (archivo) body.append('adjunto', archivo);

        input.value = '';
        limpiarAdjunto();
        btnEnviar.disabled = true;

        try {
            const r = await fetch('/api/chat/enviar', { method: 'POST', body });
            const d = await r.json();
            if (d.ok && d.mensaje) {
                vacio.classList.add('hidden');
                renderMensajes([d.mensaje]);
                ultimoId = d.mensaje.id;
            }
        } catch { /* silencioso */ }

        btnEnviar.disabled = false;
        input.focus();
    }

    btnEnviar.addEventListener('click', enviar);
    input.addEventListener('keydown', e => { if (e.key === 'Enter') enviar(); });

    function renderMensajes(mensajes) {
        mensajes.forEach(m => {
            const esMio  = Number(m.usuario_id) === MI_ID;
            const rol    = ROLES[m.rol_id] || '';
            const nombre = `${m.nombre} ${m.apellidos}`;
            const fecha  = new Date(m.created_at).toLocaleString('es-MX', {
                day: '2-digit', month: 'short',
                hour: '2-digit', minute: '2-digit'
            });

            const multimedia = m.imagen_src
                ? `<img src="${m.imagen_src}" class="mt-2 max-w-[220px] rounded-[12px] cursor-pointer block" onclick="abrirImgChat('${m.imagen_src}')" alt="imagen">`
                : m.video_ruta
                    ? `<video src="${m.video_ruta}" class="mt-2 max-w-[220px] rounded-[12px] block" controls></video>`
                    : '';

            const fila = document.createElement('div');
            fila.className = `flex ${esMio ? 'justify-end' : 'justify-start'}`;
            fila.setAttribute('data-msg-id', m.id);

            fila.innerHTML = `
                <div class="flex flex-col max-w-[70%] ${esMio ? 'items-end' : 'items-start'}">
                    <p class="text-[11px] font-semibold text-[#6b7280] mb-1 px-1">
                        ${esMio ? 'Tú' : esc(nombre)} · ${esc(rol)}
                    </p>
                    <div class="rounded-[18px] px-4 py-3 shadow-sm ${esMio ? 'bg-[#0b2030] text-white' : 'bg-white text-[#111823]'}">
                        ${m.mensaje ? `<p class="text-[14px] leading-relaxed">${esc(m.mensaje)}</p>` : ''}
                        ${multimedia}
                        <p class="text-[11px] mt-1 text-right ${esMio ? 'text-[#93c5fd]' : 'text-[#9ca3af]'}">${fecha}</p>
                    </div>
                </div>`;

            lista.appendChild(fila);
        });

        lista.scrollTop = lista.scrollHeight;
    }

    window.abrirImgChat = function (src) {
        document.getElementById('chatLightboxImg').src = src;
        document.getElementById('chatLightbox').classList.remove('hidden');
    };

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') document.getElementById('chatLightbox').classList.add('hidden');
    });

    function esc(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }
})();
</script>
