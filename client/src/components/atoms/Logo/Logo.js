import { CoreImg } from "../../../Core/atoms/Img/CoreImg.js";
import CoreHtmlElement from "../../../Core/molecules/CoreHtmlElement.js";

export class Logo extends CoreHtmlElement {
  static propertyName = "app-logo";
  constructor() {
    const logo = CoreImg({
      class: "logo",
      src: "/client/assets/images/logo.svg",
      alt: "Logo CGRD",
    });

    super({ element: logo });
  }
}

customElements.define(Logo.propertyName, Logo);
