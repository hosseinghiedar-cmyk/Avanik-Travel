import { initDropdowns, initTabs, initModals } from './ui.js';

document.addEventListener('DOMContentLoaded', () => {
  document.documentElement.classList.add('av-js-ready');
  initDropdowns();
  initTabs();
  initModals();
});
