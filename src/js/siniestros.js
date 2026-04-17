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
/*       COMMENTS MODAL          */
/* ============================= */

function openModal(imageSrc) {
    const modal = document.getElementById('commentsModal');
    const modalImage = document.getElementById('modalAccidentImage');

    if (modalImage) {
        modalImage.src = imageSrc;
    }

    openFlexModal(modal);
}

function closeModal() {
    const modal = document.getElementById('commentsModal');
    closeFlexModal(modal);
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