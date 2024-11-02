import { CoreButton } from '../../../core/atoms/Button/CoreButton.js';
import CoreHtmlElement from '../../../core/molecules/CoreHtmlElement.js';

export class Button extends CoreHtmlElement {
  static propertyName = 'app-button';
  constructor() {
    const button = CoreButton({ class: 'button' });
    const style = `
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

    super({ element: button, style });
  }
}

customElements.define(Button.propertyName, Button);
