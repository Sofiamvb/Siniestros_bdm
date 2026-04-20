const sectionsOrder = ['aseguradora', 'auto', 'detalles'];
let currentSection = 'aseguradora';
let mediaFiles = [];
let mediaIndex = 0;

function showSection(sectionName) {
    const allSections = document.querySelectorAll('.section-content');
    const allTabs = document.querySelectorAll('.tab-btn');

    allSections.forEach((section) => {
        section.classList.add('hidden');
    });

    allTabs.forEach((tab) => {
        tab.classList.remove('tab-btn-active');
    });

    const selectedSection = document.getElementById(`${sectionName}-section`);
    const selectedTab = document.querySelector(`.tab-btn[data-section="${sectionName}"]`);

    if (selectedSection) {
        selectedSection.classList.remove('hidden');
    }

    if (selectedTab) {
        selectedTab.classList.add('tab-btn-active');
    }

    currentSection = sectionName;
    updateContinueButton();
}

async function goToNextSection() {
    const currentIndex = sectionsOrder.indexOf(currentSection);

    if (currentSection === 'aseguradora') {
        await validarPolizaYAvanzar();
        return;
    }

    if (currentSection === 'auto') {
        const conductor = document.getElementById('autoConductor');
        if (conductor && !conductor.value.trim()) {
            Swal.fire({ icon: 'warning', title: 'Campo requerido', text: 'Ingresa el nombre del conductor.' });
            return;
        }

        // Default fecha/hora a ahora al entrar a detalles
        const dtInput = document.getElementById('detFechaHora');
        if (dtInput && !dtInput.value) {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            dtInput.value = now.toISOString().slice(0, 16);
        }
    }

    const nextIndex = currentIndex + 1;
    if (nextIndex < sectionsOrder.length) {
        showSection(sectionsOrder[nextIndex]);
    }
}

async function validarPolizaYAvanzar() {
    const numero = document.getElementById('inputNumPoliza');
    if (!numero || !numero.value.trim()) {
        Swal.fire({ icon: 'warning', title: 'Campo requerido', text: 'Ingresa el número de póliza.' });
        return;
    }

    try {
        const res  = await fetch('/api/validar-poliza?numero=' + encodeURIComponent(numero.value.trim()));
        const data = await res.json();

        if (data.error) {
            Swal.fire({ icon: 'warning', title: 'Póliza no encontrada', text: data.error });
            return;
        }

        // Llenar hidden inputs
        const hPolizaId      = document.getElementById('hPolizaId');
        const hSumaAsegurada = document.getElementById('hSumaAsegurada');
        if (hPolizaId)      hPolizaId.value      = data.poliza_id;
        if (hSumaAsegurada) hSumaAsegurada.value = data.suma_asegurada;

        // Llenar campos readonly del auto
        const set = (id, val) => { const el = document.getElementById(id); if (el) el.value = val; };
        set('autoDuenio', data.nombre_duenio);
        set('autoMarca',  data.marca);
        set('autoModelo', data.modelo);
        set('autoAnio',   data.anio);
        set('autoPlaca',  data.placas);

        // Hint de presupuesto máximo
        const hint        = document.getElementById('presupuestoHint');
        const presupuesto = document.getElementById('detPresupuesto');
        if (hint)        hint.textContent = 'Suma asegurada máx.: $' + parseFloat(data.suma_asegurada).toLocaleString('es-MX', { minimumFractionDigits: 2 });
        if (presupuesto) presupuesto.max  = data.suma_asegurada;

        showSection('auto');

    } catch (e) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo conectar con el servidor.' });
    }
}

function updateContinueButton() {
    const continueBtn = document.getElementById('continueBtn');
    if (!continueBtn) return;

    continueBtn.style.visibility = 'visible';

    if (currentSection === 'detalles') {
        continueBtn.textContent = 'Registrar siniestro';
        continueBtn.onclick = () => document.getElementById('formSiniestro').submit();
    } else {
        continueBtn.textContent = 'Continuar →';
        continueBtn.onclick = goToNextSection;
    }
}

function showSuccessMessage(message) {
    const overlay = document.createElement('div');
    overlay.className = 'success-overlay';

    const messageBox = document.createElement('div');
    messageBox.className = 'success-message';
    messageBox.textContent = message;

    overlay.appendChild(messageBox);
    document.body.appendChild(overlay);

    setTimeout(() => {
        overlay.remove();
    }, 2000);
}

function handleMediaUpload(event) {
    const files = Array.from(event.target.files || []);
    if (files.length === 0) return;

    const newMediaItems = files.map((file) => ({
        file,
        url: URL.createObjectURL(file),
        isVideo: file.type.startsWith('video/')
    }));

    mediaFiles = [...mediaFiles, ...newMediaItems];
    mediaIndex = mediaFiles.length - newMediaItems.length;

    event.target.value = '';
    renderCarousel();
}

function renderCarousel() {
    const view = document.getElementById('carouselView');
    const counter = document.getElementById('carouselCounter');
    const prevButton = document.getElementById('carouselPrev');
    const nextButton = document.getElementById('carouselNext');
    const deleteButton = document.getElementById('deleteMediaBtn');

    if (!view || !counter || !prevButton || !nextButton || !deleteButton) {
        return;
    }

    view.innerHTML = '';

    if (mediaFiles.length === 0) {
        const emptyText = document.createElement('p');
        emptyText.className = 'carousel-empty';
        emptyText.textContent = 'No hay archivos cargados';
        view.appendChild(emptyText);

        counter.textContent = '0 / 0';
        prevButton.disabled = true;
        nextButton.disabled = true;
        deleteButton.disabled = true;
        return;
    }

    const currentMedia = mediaFiles[mediaIndex];
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

    mediaElement.className = 'w-full h-[200px] object-contain rounded-[12px]';
    view.appendChild(mediaElement);

    counter.textContent = `${mediaIndex + 1} / ${mediaFiles.length}`;
    prevButton.disabled = mediaIndex === 0;
    nextButton.disabled = mediaIndex === mediaFiles.length - 1;
    deleteButton.disabled = false;
}

function showPrevMedia() {
    if (mediaIndex > 0) {
        mediaIndex -= 1;
        renderCarousel();
    }
}

function showNextMedia() {
    if (mediaIndex < mediaFiles.length - 1) {
        mediaIndex += 1;
        renderCarousel();
    }
}

function deleteCurrentMedia() {
    if (mediaFiles.length === 0) return;

    const removedItem = mediaFiles[mediaIndex];
    if (removedItem?.url) {
        URL.revokeObjectURL(removedItem.url);
    }

    mediaFiles.splice(mediaIndex, 1);

    if (mediaIndex >= mediaFiles.length && mediaIndex > 0) {
        mediaIndex -= 1;
    }

    renderCarousel();
}

function openVehiculoModal() {
    const modal = document.getElementById('vehiculoModal');
    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modal.setAttribute('aria-hidden', 'false');
}

function closeVehiculoModal() {
    const modal = document.getElementById('vehiculoModal');
    if (!modal) return;

    modal.classList.remove('flex');
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
}

function handleVehiculoModalBackdropClick(event) {
    if (event.target && event.target.id === 'vehiculoModal') {
        closeVehiculoModal();
    }
}

function handleVehiculoModalEscape(event) {
    if (event.key === 'Escape') {
        closeVehiculoModal();
    }
}

window.addEventListener('DOMContentLoaded', () => {
    showSection('aseguradora');

    const mediaInput = document.getElementById('detalles-media');
    const prevButton = document.getElementById('carouselPrev');
    const nextButton = document.getElementById('carouselNext');
    const deleteButton = document.getElementById('deleteMediaBtn');
    const openVehiculoModalBtn = document.getElementById('openVehiculoModal');
    const closeVehiculoModalBtn = document.getElementById('closeVehiculoModal');
    const vehiculoModal = document.getElementById('vehiculoModal');

    if (mediaInput) {
        mediaInput.addEventListener('change', handleMediaUpload);
    }

    if (prevButton) {
        prevButton.addEventListener('click', showPrevMedia);
    }

    if (nextButton) {
        nextButton.addEventListener('click', showNextMedia);
    }

    if (deleteButton) {
        deleteButton.addEventListener('click', deleteCurrentMedia);
    }

    if (openVehiculoModalBtn) {
        openVehiculoModalBtn.addEventListener('click', openVehiculoModal);
    }

    if (closeVehiculoModalBtn) {
        closeVehiculoModalBtn.addEventListener('click', closeVehiculoModal);
    }

    if (vehiculoModal) {
        vehiculoModal.addEventListener('click', handleVehiculoModalBackdropClick);
    }

    document.addEventListener('keydown', handleVehiculoModalEscape);

    renderCarousel();
});