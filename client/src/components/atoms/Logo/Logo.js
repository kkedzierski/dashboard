import { CoreImg } from '../../../core/atoms/Img/CoreImg.js';
import CoreHtmlElement from '../../../core/molecules/CoreHtmlElement.js';

export class Logo extends CoreHtmlElement {
  static propertyName = 'app-logo';
  constructor() {
    const logo = CoreImg({
      class: 'logo',
      src: 'assets/images/logo.svg',
      alt: 'Logo CGRD',
    });

    super({ element: logo });
  }
}

customElements.define(Logo.propertyName, Logo);
