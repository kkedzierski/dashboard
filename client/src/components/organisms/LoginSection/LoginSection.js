import { CoreSection } from "../../../Core/atoms/Section/CoreSection.js";
import CoreHtmlElement from "../../../Core/molecules/CoreHtmlElement.js";

class LoginSection extends CoreHtmlElement {
  static propertyName = "login-section";
  constructor() {
    const section = CoreSection({ class: "loginSection" });
    const style = `
      .loginSection {
        display: grid;
      }
      `;
    const content = `
      <app-form>
        <slot></slot>
      </app-form>
    `;
    super({ element: section, content, style });
  }
}

customElements.define(LoginSection.propertyName, LoginSection);

export default LoginSection;
