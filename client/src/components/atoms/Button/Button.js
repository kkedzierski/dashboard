import { CoreButton } from '../../../core/atoms/Button/CoreButton.js';

export class Button extends HTMLElement {
  static propertyName = 'app-button';
  constructor() {
    super();

    if (this.getAttribute('type')) {
      this.type = this.getAttribute('type');
    }

    if (this.getAttribute('name')) {
      this.name = this.getAttribute('name');
    }

    const button = CoreButton({ class: 'button', type: this.type, name: this.name });
    const shadowRoot = this.attachShadow({ mode: 'closed' });
    const style = document.createElement('style');
    style.textContent = `
      .button {
        font-size: 1rem;
        color: white;
        padding: 1.5rem;
        background-color: #15396b;
        border: none;
        width: 710px;
        border-radius: 5px;
        cursor: pointer;
      }
      .button:hover {
        background-color: #1f4b8e;
      }

      @media (max-width: 768px) {
        .button {
          width: 330px;
        }
      }
    `;

    shadowRoot.appendChild(style);
    shadowRoot.appendChild(button);
  }
}

customElements.define(Button.propertyName, Button);
