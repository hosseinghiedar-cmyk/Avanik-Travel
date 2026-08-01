import { initDropdowns, initTabs, initModals } from './ui.js';
import { initMobileNavigation } from './navigation.js';

document.addEventListener('DOMContentLoaded', () => {
  document.documentElement.classList.add('av-js-ready');
  initDropdowns();
  initTabs();
  initModals();
  initMobileNavigation();
});
