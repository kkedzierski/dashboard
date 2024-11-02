import { CoreMain } from '../../../core/atoms/Main/CoreMain.js';
import CoreHtmlElement from '../../../core/molecules/CoreHtmlElement.js';

export class Main extends CoreHtmlElement {
  static propertyName = 'app-main';
  constructor() {
    const section = CoreMain({ class: 'main' });
    const style = `
      .main {
        margin-top: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        background-position: -10%;
      }
    `;

    super({ element: section, style });
  }
}

customElements.define(Main.propertyName, Main);
