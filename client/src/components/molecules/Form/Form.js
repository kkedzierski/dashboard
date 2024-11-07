import { CoreForm } from '../../../core/atoms/Form/Form.js';
import { loginUser } from '../../../services/ResourceApiManager.js';

export class Form extends HTMLElement {
  static propertyName = 'app-form';
  constructor() {
    super();

    const shadowRoot = this.attachShadow({ mode: 'open' });

    this.action = this.getAttribute('action') || '';
    this.method = this.getAttribute('method') || 'GET';

    if (this.getAttribute('action')) {
      this.action = this.getAttribute('action');
    }

    if (this.getAttribute('method')) {
      this.method = this.getAttribute('method');
    }

    const form = CoreForm({
      class: 'form',
      action: this.action,
      method: this.method,
    });

    form.addEventListener('click', (event) => {
      if (event.target.name === 'login') {
        event.preventDefault();

        const userData = this.prepareUserData(shadowRoot);

        this.loginUser(userData);
      }
    });

    while (this.children.length > 0) {
      form.appendChild(this.children[0]);
    }

    const style = document.createElement('style');
    style.textContent = `
      .form {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        gap: 1rem;
      }
      `;

    shadowRoot.appendChild(style);
    shadowRoot.appendChild(form);
  }

  prepareUserData = (shadowRoot) => {
    const appInputs = shadowRoot.querySelectorAll('app-input');

    const loginData = {};
    appInputs.forEach((input) => {
      const name = input.getAttribute('name');
      const value = input.shadowRoot.querySelector('input').value;
      if (name) {
        loginData[name] = value;
      }
    });

    return loginData;
  };

  loginUser = (userData) => {
    const { username, password } = userData;

    if (!username || !password) {
      return;
    }

    loginUser(username, password)
      .then((response) => {
        console.log('Login successful:', response);
      })
      .catch((error) => {
        console.error('Login failed:', error);
      });
  };
}

customElements.define(Form.propertyName, Form);
