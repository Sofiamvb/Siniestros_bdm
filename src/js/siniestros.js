function lockBodyScroll() {
    document.body.classList.add('overflow-hidden');
}

function unlockBodyScroll() {
    document.body.classList.remove('overflow-hidden');
}

function openFlexModal(modal) {
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    lockBodyScroll();
}

function closeFlexModal(modal) {
    if (!modal) return;
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    unlockBodyScroll();
}

/* ============================= */
/*       COMMENTS MODAL + CHAT   */
/* ============================= */

let _chatSiniestroId = 0;
let _chatUltimoId    = 0;
let _chatPollTimer   = null;

function openModal(imageSrc, siniestroId) {
    const modal = document.getElementById('commentsModal');
    const modalImage = document.getElementById('modalAccidentImage');

    if (modalImage && imageSrc) {
        modalImage.src = imageSrc;
    }

    _chatSiniestroId = siniestroId || 0;
    _chatUltimoId    = 0;

    openFlexModal(modal);

    if (_chatSiniestroId) {
        _cargarMensajes(true);
        _chatPollTimer = setInterval(() => _cargarMensajes(false), 5000);
        _iniciarEnvio();
    }
}

function closeModal() {
    const modal = document.getElementById('commentsModal');
    closeFlexModal(modal);

    clearInterval(_chatPollTimer);
    _chatPollTimer   = null;
    _chatSiniestroId = 0;
    _chatUltimoId    = 0;

    const lista = document.querySelector('#commentsModal .comment-list');
    if (lista) lista.innerHTML = '';
}

async function _cargarMensajes(inicial) {
    if (!_chatSiniestroId) return;
    try {
        const url = `/api/chat?siniestro_id=${_chatSiniestroId}&desde_id=${_chatUltimoId}`;
        const resp = await fetch(url);
        const data = await resp.json();
        if (data.mensajes && data.mensajes.length > 0) {
            _renderMensajes(data.mensajes, inicial);
            _chatUltimoId = data.mensajes[data.mensajes.length - 1].id;
        }
    } catch { /* silencioso */ }
}

function _renderMensajes(mensajes, limpiar) {
    const lista = document.querySelector('#commentsModal .comment-list');
    if (!lista) return;
    if (limpiar) lista.innerHTML = '';

    const roles = { '1': 'Asegurado', '2': 'Ajustador', '3': 'Supervisor' };

    mensajes.forEach(m => {
        const fecha = new Date(m.created_at).toLocaleString('es-MX', {
            day: '2-digit', month: 'long', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });

        const card = document.createElement('div');
        card.className = 'comment-card';
        card.innerHTML = `
            <img src="/img/DefaultPFP.png" alt="${m.nombre}" class="comment-avatar">
            <div class="comment-body">
                <div class="comment-author">${m.nombre} ${m.apellidos}
                    <span style="font-weight:normal;font-size:11px;color:#6b7280">
                        · ${roles[String(m.rol_id)] || ''}
                    </span>
                </div>
                <div class="comment-text">${_escapeHtml(m.mensaje)}</div>
                <div class="comment-date">${fecha}</div>
            </div>`;
        lista.appendChild(card);
    });

    lista.scrollTop = lista.scrollHeight;
}

function _iniciarEnvio() {
    const input  = document.querySelector('#commentsModal .comment-input');
    const btn    = document.querySelector('#commentsModal .comment-submit-btn');
    if (!btn || btn._chatWired) return;
    btn._chatWired = true;

    async function enviar() {
        const texto = input ? input.value.trim() : '';
        if (!texto || !_chatSiniestroId) return;
        input.value = '';

        try {
            const body = new URLSearchParams({
                siniestro_id: _chatSiniestroId,
                mensaje:      texto,
            });
            const resp = await fetch('/api/chat/enviar', { method: 'POST', body });
            const data = await resp.json();
            if (data.ok && data.mensaje) {
                _renderMensajes([data.mensaje], false);
                _chatUltimoId = data.mensaje.id;
            }
        } catch { /* silencioso */ }
    }

    btn.addEventListener('click', enviar);
    if (input) {
        input.addEventListener('keydown', e => { if (e.key === 'Enter') enviar(); });
    }
}

function _escapeHtml(str) {
    return String(str ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

/* ============================= */
/*       DETAILS MODAL           */
/* ============================= */

function openDetailsModal() {
    const detailsModal = document.getElementById('detailsModal');
    openFlexModal(detailsModal);
}

function closeDetailsModal() {
    const detailsModal = document.getElementById('detailsModal');
    closeFlexModal(detailsModal);
}

function switchDetailsTab(tabName) {
    const sections = document.querySelectorAll('.details-section');
    const tabs = document.querySelectorAll('.details-tab');

    sections.forEach((section) => {
        section.classList.add('hidden');
        section.classList.remove('block');
        section.classList.remove('active');
    });

    tabs.forEach((tab) => {
        tab.classList.remove('active');
        tab.classList.remove('bg-[#74b8cc]');
        tab.classList.remove('border-b-[#16425B]');
        tab.classList.add('bg-transparent');
        tab.classList.add('border-b-transparent');
    });

    const selectedSection = document.getElementById(`${tabName}-section`);
    if (selectedSection) {
        selectedSection.classList.remove('hidden');
        selectedSection.classList.add('block');
        selectedSection.classList.add('active');
    }

    const selectedTab = document.querySelector(`.details-tab[data-tab="${tabName}"]`);
    if (selectedTab) {
        selectedTab.classList.add('active');
        selectedTab.classList.remove('bg-transparent');
        selectedTab.classList.remove('border-b-transparent');
        selectedTab.classList.add('bg-[#74b8cc]');
        selectedTab.classList.add('border-b-[#16425B]');
    }
}

/* ============================= */
/*        STATUS HELPER          */
/* ============================= */

function updateStatus(selectElement) {
    const statusText = selectElement.options[selectElement.selectedIndex].text;
    console.log('Status changed to:', statusText);
}

/* ============================= */
/*   SUPERVISOR MODAL FUNCTIONS  */
/* ============================= */

let supervisorMediaFiles = [];
let supervisorMediaIndex = 0;

function openSupervisorModal() {
    const modal = document.getElementById('supervisorModal');
    if (!modal) return;

    modal.setAttribute('aria-hidden', 'false');
    openFlexModal(modal);
}

function closeSupervisorModal() {
    const modal = document.getElementById('supervisorModal');
    if (!modal) return;

    modal.setAttribute('aria-hidden', 'true');
    closeFlexModal(modal);
}

function handleSupervisorMediaUpload(event) {
    const files = Array.from(event.target.files || []);
    if (files.length === 0) return;

    const newMediaItems = files.map((file) => ({
        file,
        url: URL.createObjectURL(file),
        isVideo: file.type.startsWith('video/')
    }));

    supervisorMediaFiles = [...supervisorMediaFiles, ...newMediaItems];
    supervisorMediaIndex = supervisorMediaFiles.length - newMediaItems.length;

    event.target.value = '';
    renderSupervisorCarousel();
}

function renderSupervisorCarousel() {
    const view = document.getElementById('supervisorCarouselView');
    const counter = document.getElementById('supervisorCarouselCounter');
    const prevButton = document.getElementById('supervisorCarouselPrev');
    const nextButton = document.getElementById('supervisorCarouselNext');
    const deleteButton = document.getElementById('supervisorDeleteMediaBtn');

    if (!view || !counter || !prevButton || !nextButton || !deleteButton) return;

    view.innerHTML = '';

    if (supervisorMediaFiles.length === 0) {
        const empty = document.createElement('p');
        empty.className = 'text-[14px] text-[#666]';
        empty.textContent = 'No hay archivos cargados';
        empty.id = 'supervisorCarouselEmpty';
        view.appendChild(empty);

        counter.textContent = '0 / 0';
        prevButton.disabled = true;
        nextButton.disabled = true;
        deleteButton.disabled = true;
        return;
    }

    const currentMedia = supervisorMediaFiles[supervisorMediaIndex];
    let mediaElement;

    if (currentMedia.isVideo) {
        mediaElement = document.createElement('video');
        mediaElement.src = currentMedia.url;
        mediaElement.controls = true;
    } else {
        mediaElement = document.createElement('img');
        mediaElement.src = currentMedia.url;
        mediaElement.alt = 'Evidencia del siniestro';
    }

    mediaElement.className = 'h-[200px] w-full rounded-[12px] object-contain';
    view.appendChild(mediaElement);

    counter.textContent = `${supervisorMediaIndex + 1} / ${supervisorMediaFiles.length}`;
    prevButton.disabled = supervisorMediaIndex === 0;
    nextButton.disabled = supervisorMediaIndex === supervisorMediaFiles.length - 1;
    deleteButton.disabled = false;
}

function showSupervisorPrevMedia() {
    if (supervisorMediaIndex > 0) {
        supervisorMediaIndex -= 1;
        renderSupervisorCarousel();
    }
}

function showSupervisorNextMedia() {
    if (supervisorMediaIndex < supervisorMediaFiles.length - 1) {
        supervisorMediaIndex += 1;
        renderSupervisorCarousel();
    }
}

function deleteSupervisorCurrentMedia() {
    if (supervisorMediaFiles.length === 0) return;

    const removedItem = supervisorMediaFiles[supervisorMediaIndex];
    if (removedItem?.url) {
        URL.revokeObjectURL(removedItem.url);
    }

    supervisorMediaFiles.splice(supervisorMediaIndex, 1);

    if (supervisorMediaIndex >= supervisorMediaFiles.length && supervisorMediaIndex > 0) {
        supervisorMediaIndex -= 1;
    }

    renderSupervisorCarousel();
}

/* ============================= */
/*        GLOBAL EVENTS          */
/* ============================= */

window.addEventListener('click', function (event) {
    const commentsModal = document.getElementById('commentsModal');
    const detailsModal = document.getElementById('detailsModal');

    if (event.target === commentsModal) {
        closeModal();
    }

    if (event.target === detailsModal) {
        closeDetailsModal();
    }
});

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeModal();
        closeDetailsModal();
        closeSupervisorModal();
    }
});

/* ============================= */
/*         INIT EVENTS           */
/* ============================= */

window.addEventListener('DOMContentLoaded', () => {
    const closeSupervisorBtn = document.getElementById('closeSupervisorModal');
    const supervisorModal = document.getElementById('supervisorModal');
    const supervisorMediaInput = document.getElementById('supervisorMediaUpload');
    const supervisorPrevBtn = document.getElementById('supervisorCarouselPrev');
    const supervisorNextBtn = document.getElementById('supervisorCarouselNext');
    const supervisorDeleteBtn = document.getElementById('supervisorDeleteMediaBtn');

    const commentsModal = document.getElementById('commentsModal');
    const detailsModal = document.getElementById('detailsModal');

    if (commentsModal) {
        commentsModal.classList.add('hidden');
        commentsModal.classList.remove('flex');
    }

    if (detailsModal) {
        detailsModal.classList.add('hidden');
        detailsModal.classList.remove('flex');
    }

    if (supervisorModal) {
        supervisorModal.classList.add('hidden');
        supervisorModal.classList.remove('flex');
    }

    if (closeSupervisorBtn) {
        closeSupervisorBtn.addEventListener('click', closeSupervisorModal);
    }

    if (supervisorModal) {
        supervisorModal.addEventListener('click', (e) => {
            if (e.target === supervisorModal) {
                closeSupervisorModal();
            }
        });
    }

    if (supervisorMediaInput) {
        supervisorMediaInput.addEventListener('change', handleSupervisorMediaUpload);
    }

    if (supervisorPrevBtn) {
        supervisorPrevBtn.addEventListener('click', showSupervisorPrevMedia);
    }

    if (supervisorNextBtn) {
        supervisorNextBtn.addEventListener('click', showSupervisorNextMedia);
    }

    if (supervisorDeleteBtn) {
        supervisorDeleteBtn.addEventListener('click', deleteSupervisorCurrentMedia);
    }

    switchDetailsTab('aseguradora');
    renderSupervisorCarousel();
});