import { CoreDiv } from '../../../core/atoms/Div/CoreDiv.js';
import { CoreElement } from '../../../core/atoms/Element/CoreElement.js';

export class Toast extends HTMLElement {
  static propertyName = 'app-toast';

  constructor(message = '', status = 'success') {
    super();

    this.toastContainer = CoreDiv({ class: 'toastContainer' });
    this.toast = CoreElement({ type: 'p', class: 'toast' });
    this.toastContainer.appendChild(this.toast);
    const shadowRoot = this.attachShadow({ mode: 'closed' });
    const style = document.createElement('style');
    style.textContent = `
      .toastContainer {
        position: fixed;
        top: 50px; 
        left: 50%;
        transform: translateX(-50%); 
        font-size: 1rem;
        padding: 1rem;
        border-radius: 5px;
        text-align: center;
        display: none;
        z-index: 1000;
        width: 80%;
        max-width: 660px;
      }
      .success {
        color: #A2A226;
        background-color: #F7FEF1;
        border: 1px solid #A2A226;
      }
      .error {
        color: #dc3545;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
      }
      @media (max-width: 768px) {
        .toastContainer {
          width: 280px;
        }
      }
    `;

    shadowRoot.appendChild(style);
    shadowRoot.appendChild(this.toastContainer);

    this.setMessage(message);
    this.setStatus(status);
  }

  setMessage(message) {
    if (message) {
      this.toast.textContent = message;
      this.toastContainer.style.display = 'block';
    } else {
      this.toastContainer.style.display = 'none';
    }
  }

  setStatus(status) {
    this.toastContainer.classList.toggle('success', status === 'success');
    this.toastContainer.classList.toggle('error', status === 'error');
  }
}

customElements.define(Toast.propertyName, Toast);
