import { CoreSection } from '../../../core/atoms/Section/CoreSection.js';
import CoreHtmlElement from '../../../core/molecules/CoreHtmlElement.js';

export class Section extends CoreHtmlElement {
  static propertyName = 'app-section';
  constructor() {
    const section = CoreSection({ class: 'section' });
    super({ element: section });
  }
}

customElements.define(Section.propertyName, Section);
