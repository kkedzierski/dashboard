import { Button } from '../../atoms/Button/Button.js';

export class LogoutButton extends Button {
  static propertyName = 'app-logout-button';
  constructor() {
    super();

    if (this.getAttribute('name') === 'logout') {
      document.querySelector('app-logout-button').addEventListener('click', () => {
        localStorage.removeItem('jwtToken');
        window.location.href = '/';
      });
    }
  }
}

customElements.define(LogoutButton.propertyName, LogoutButton);
