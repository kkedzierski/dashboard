import { CoreForm } from "../../../Core/atoms/Form/Form.js";
import CoreHtmlElement from "../../../Core/molecules/CoreHtmlElement.js";

export class Form extends CoreHtmlElement {
  static propertyName = "app-form";
  constructor() {
    const form = CoreForm({ class: "form" });
    const style = `
      .form {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        gap: 1rem;
      }
      `;

    super({ element: form, style });
  }
}

customElements.define(Form.propertyName, Form);
