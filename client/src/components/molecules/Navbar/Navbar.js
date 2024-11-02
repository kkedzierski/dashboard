import { CoreNav } from '../../../core/atoms/Nav/CoreNav.js';
import CoreHtmlElement from '../../../core/molecules/CoreHtmlElement.js';

export class Navbar extends CoreHtmlElement {
  static propertyName = 'app-navbar';
  constructor() {
    const div = CoreNav({ class: 'navbar' });
    const style = `
        .navbar {
            display: flex;
            margin-top: 3rem;
            padding: 20px;
            justify-content: space-evenly;
          }
        `;

    super({ element: div, style });
  }
}

customElements.define(Navbar.propertyName, Navbar);
