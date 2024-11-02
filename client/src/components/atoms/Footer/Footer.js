import { CoreMain } from "../../../Core/atoms/Main/CoreMain.js";
import CoreHtmlElement from "../../../Core/molecules/CoreHtmlElement.js";

export class Footer extends CoreHtmlElement {
  static propertyName = "app-footer";
  constructor() {
    const section = CoreMain({ class: "footer" });
    super({ element: section });
  }
}

customElements.define(Footer.propertyName, Footer);
