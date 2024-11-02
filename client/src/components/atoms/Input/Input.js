import { CoreInput } from "../../../Core/atoms/Input/CoreInput.js";

export class Input extends HTMLElement {
  static propertyName = "app-input";
  constructor() {
    super();

    if (this.getAttribute("placeholder")) {
      const placeholder = this.getAttribute("placeholder");
      this.placeholder =
        placeholder.charAt(0).toUpperCase() + placeholder.slice(1);
    }

    const input = CoreInput({
      placeholder: this.placeholder,
      class: "input",
    });
    const shadowRoot = this.attachShadow({ mode: "closed" });
    const style = document.createElement("style");
    style.textContent = `
      .input {
        color: black;
        padding: 0.8rem;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 680px;
        cursor: pointer;
        background-color: #f8f9fa;
      }

      .input:focus {
        outline: none;
        background-color: #fff;
      } 

     @media (max-width: 768px) {
        .input {
          width: 300px;
        }
      }
    `;

    shadowRoot.appendChild(style);
    shadowRoot.appendChild(input);
  }
}

customElements.define(Input.propertyName, Input);
