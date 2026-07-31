export function initDropdowns() {
  document.querySelectorAll('[data-av-dropdown]').forEach((dropdown) => {
    const trigger = dropdown.querySelector('[data-av-dropdown-trigger]');
    if (!trigger) return;
    trigger.addEventListener('click', (event) => {
      event.preventDefault();
      dropdown.classList.toggle('is-open');
    });
  });

  document.addEventListener('click', (event) => {
    document.querySelectorAll('.av-dropdown.is-open').forEach((dropdown) => {
      if (!dropdown.contains(event.target)) dropdown.classList.remove('is-open');
    });
  });
}

export function initTabs() {
  document.querySelectorAll('[data-av-tabs]').forEach((tabs) => {
    const buttons = tabs.querySelectorAll('[data-av-tab]');
    buttons.forEach((button) => {
      button.addEventListener('click', () => {
        buttons.forEach((item) => {
          item.classList.remove('is-active');
          item.setAttribute('aria-selected', 'false');
        });
        button.classList.add('is-active');
        button.setAttribute('aria-selected', 'true');
      });
    });
  });
}

export function initModals() {
  document.querySelectorAll('[data-av-modal-open]').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const target = document.querySelector(trigger.dataset.avModalOpen);
      if (target) {
        target.classList.add('is-open');
        document.body.classList.add('av-modal-open');
      }
    });
  });

  document.querySelectorAll('[data-av-modal-close]').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const modal = trigger.closest('.av-modal');
      if (modal) {
        modal.classList.remove('is-open');
        document.body.classList.remove('av-modal-open');
      }
    });
  });
}
