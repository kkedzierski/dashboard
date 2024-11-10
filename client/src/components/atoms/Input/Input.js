import { CoreInput } from '../../../core/atoms/Input/CoreInput.js';

export class Input extends HTMLElement {
  static propertyName = 'app-input';
  constructor(placeholder = '', name = '', type = 'text') {
    super();

    this.placeholder = placeholder;
    this.name = name;
    this.type = type;

    if (this.getAttribute('placeholder')) {
      const placeholder = this.getAttribute('placeholder');
      this.placeholder = placeholder.charAt(0).toUpperCase() + placeholder.slice(1);
    }

    if (this.getAttribute('name')) {
      this.name = this.getAttribute('name');
    }

    if (this.getAttribute('type')) {
      this.type = this.getAttribute('type');
    }

    const input = CoreInput({
      placeholder: this.placeholder,
      name: this.name,
      type: this.type,
      class: 'input',
    });
    const shadowRoot = this.attachShadow({ mode: 'open' });
    const style = document.createElement('style');
    style.textContent = `
      .input {
        color: black;
        padding: 0.8rem;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 680px;
        cursor: pointer;
        background-color: rgba(248, 249, 250, 0.8);
      }

      .input:focus {
        outline: none;
        background-color: #fff;
      } 

     @media (max-width: 768px) {
        .input {
          width: 300px;
        }
      }
    `;

    shadowRoot.appendChild(style);
    shadowRoot.appendChild(input);
  }
}

customElements.define(Input.propertyName, Input);
