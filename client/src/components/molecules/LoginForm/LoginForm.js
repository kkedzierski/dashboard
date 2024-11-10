import { config } from '../../../config.js';
import { CoreForm } from '../../../core/atoms/Form/Form.js';
import { CoreStyle } from '../../../core/atoms/Style/CoreStyle.js';
import { loginUser } from '../../../services/ResourceApiManager.js';
import { showToast } from '../../atoms/Toast/ToastManager.js';

export class LoginForm extends HTMLElement {
  static propertyName = 'app-login-form';

  constructor() {
    super();

    const shadowRoot = this.attachShadow({ mode: 'open' });
    this.action = `${config.API_URL}/login`;
    this.method = 'POST';
    this.createComponent(shadowRoot);

    this.form.addEventListener('click', (event) => {
      if (event.target.name === 'login') {
        event.preventDefault();

        const userData = this.prepareUserData(shadowRoot);
        this.loginUser(userData);
      }
    });
  }

  createComponent(shadowRoot) {
    this.form = CoreForm({
      class: 'form',
      action: this.action,
      method: this.method,
    });

    while (this.children.length > 0) {
      this.form.appendChild(this.children[0]);
    }

    const style = CoreStyle({
      textContent: `
        .form {
          display: flex;
          flex-direction: column;
          align-items: center;
          width: 100%;
          gap: 1rem;
        }
      `,
    });

    shadowRoot.appendChild(style);
    shadowRoot.appendChild(this.form);
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
      showToast('Please enter username and password!', 'error');
      return;
    }

    loginUser(username, password)
      .then((response) => {
        localStorage.setItem('jwtToken', response.token);
        window.location.href = '/dashboard';
      })
      .catch((error) => {
        console.error('Login failed:', error);
        showToast('Wrong Login Data!', 'error');
      });
  };
}

customElements.define(LoginForm.propertyName, LoginForm);
