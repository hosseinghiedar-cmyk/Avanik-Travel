export function initMobileNavigation() {
  const menu = document.querySelector('[data-av-mobile-menu]');
  const openButton = document.querySelector('[data-av-mobile-open]');
  const closeButtons = document.querySelectorAll('[data-av-mobile-close]');
  if (!menu || !openButton) return;
  const openMenu = () => {
    menu.classList.add('is-open');
    menu.setAttribute('aria-hidden', 'false');
    document.body.classList.add('av-mobile-menu-open');
    openButton.setAttribute('aria-expanded', 'true');
  };
  const closeMenu = () => {
    menu.classList.remove('is-open');
    menu.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('av-mobile-menu-open');
    openButton.setAttribute('aria-expanded', 'false');
  };
  openButton.addEventListener('click', openMenu);
  closeButtons.forEach(button => button.addEventListener('click', closeMenu));
  menu.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && menu.classList.contains('is-open')) closeMenu();
  });
}
