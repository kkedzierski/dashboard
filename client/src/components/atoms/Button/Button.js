import { CoreButton } from '../../../core/atoms/Button/CoreButton.js';
import { CoreStyle } from '../../../core/atoms/Style/CoreStyle.js';

export class Button extends HTMLElement {
  static propertyName = 'app-button';
  constructor(type = 'button', name = 'button') {
    super();
    this.type = type;
    this.name = name;

    if (this.getAttribute('type')) {
      this.type = this.getAttribute('type');
    }

    if (this.getAttribute('name')) {
      this.name = this.getAttribute('name');
    }

    const shadowRoot = this.attachShadow({ mode: 'closed' });
    this.createComponent(shadowRoot);
  }

  createComponent(shadowRoot) {
    const button = CoreButton({ class: 'button', type: this.type, name: this.name });
    const style = CoreStyle({
      textContent: `
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
      `,
    });

    shadowRoot.appendChild(style);
    shadowRoot.appendChild(button);
  }
}

customElements.define(Button.propertyName, Button);
