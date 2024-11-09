import { CoreDiv } from '../../../core/atoms/Div/CoreDiv.js';
import { CoreElement } from '../../../core/atoms/Element/CoreElement.js';

export class Toast extends HTMLElement {
  static propertyName = 'app-toast';
  constructor() {
    super();

    this.toastContainer = CoreDiv({ class: 'toastContainer' });
    this.toast = CoreElement({ type: 'p', class: 'toast' });
    this.toastContainer.appendChild(this.toast);
    const shadowRoot = this.attachShadow({ mode: 'closed' });
    const style = document.createElement('style');
    style.textContent = `
      .toastContainer {
        font-size: 1rem;
        color: #E0987D;
        padding: 1.5rem;
        background-color: #FFF2F1;
        border: 1px solid #E0987D;
        width: 660px;
        border-radius: 5px;
        cursor: pointer;
        margin: 1rem;
        text-align: center;
        display: none;
      }
      @media (max-width: 768px) {
        .toastContainer {
          width: 280px;
        }
      }
    `;

    shadowRoot.appendChild(style);
    shadowRoot.appendChild(this.toastContainer);
  }

  static get observedAttributes() {
    return ['message'];
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (name === 'message') {
      this.updateMessage(newValue);
    }
  }

  updateMessage(message) {
    if (message) {
      this.toast.textContent = message;
      this.toastContainer.style.display = 'block';
    } else {
      this.toastContainer.style.display = 'none';
    }
  }
}

customElements.define(Toast.propertyName, Toast);
