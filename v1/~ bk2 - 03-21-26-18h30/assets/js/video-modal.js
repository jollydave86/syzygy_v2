(function (window, document) {
  function initVideoModal(root) {
    const scope = root || document;
    const modal = scope.querySelector('[data-video-modal]');
    const output = scope.querySelector('[data-video-output]');
    const titleOutput = scope.querySelector('[data-video-title-output]');
    const descriptionOutput = scope.querySelector('[data-video-description-output]');
    const triggers = scope.querySelectorAll('[data-video-modal-trigger], [data-video-trigger]');
    const closeButtons = scope.querySelectorAll('[data-video-close]');

    const close = () => {
      if (!modal || !output) return;
      modal.hidden = true;
      output.src = '';
      output.title = '';
      if (titleOutput) titleOutput.textContent = '';
      if (descriptionOutput) descriptionOutput.textContent = '';
      document.body.classList.remove('video-modal-is-open');
    };

    const open = (embed, title, description) => {
      if (!modal || !output) return;
      output.src = embed;
      output.title = title || 'Embedded video';
      if (titleOutput) titleOutput.textContent = title || '';
      if (descriptionOutput) descriptionOutput.textContent = description || '';
      modal.hidden = false;
      document.body.classList.add('video-modal-is-open');
    };

    triggers.forEach((trigger) => {
      trigger.addEventListener('click', (event) => {
        const embed = trigger.getAttribute('data-video-embed');
        const title = trigger.getAttribute('data-video-title') || '';
        const description = trigger.getAttribute('data-video-description') || '';
        const fallback = trigger.getAttribute('data-video-href') || '';

        if (embed) {
          event.preventDefault();
          open(embed, title, description);
        } else if (fallback) {
          event.preventDefault();
          window.open(fallback, '_blank', 'noopener,noreferrer');
        }
      });
    });

    closeButtons.forEach((button) => {
      button.addEventListener('click', close);
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        close();
      }
    });

    return { open, close };
  }

  window.initVideoModal = initVideoModal;
})(window, document);
