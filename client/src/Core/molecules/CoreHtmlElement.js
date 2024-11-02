class CoreHtmlElement extends HTMLElement {
  constructor({ element, ...props }) {
    super();
    const shadowRoot = this.attachShadow({ mode: "closed" });
    this.setElement(element);
    this.fetchProps(props, shadowRoot);

    shadowRoot.appendChild(this.element);
  }

  validateElement(element) {
    if (!element instanceof HTMLElement) {
      throw new TypeError(
        "The `element` parameter must be an instance of HTMLElement."
      );
    }
  }

  setElement(element) {
    this.validateElement(element);

    this.element = element;
  }

  validateProps(props) {
    if (typeof props !== "object") {
      throw new TypeError("The `props` parameter must be an object.");
    }

    if (props.className && typeof props.className !== "string") {
      throw new TypeError("The `className` in `props` must be a string.");
    }

    if (props.style && typeof props.style !== "string") {
      throw new TypeError("The `style` in `props` must be a string.");
    }
  }

  fetchProps(props, shadowRoot) {
    this.validateProps(props);

    if (props.style) {
      const style = document.createElement("style");
      style.textContent = props.style;
      shadowRoot.appendChild(style);
    }

    if (props.content) {
      this.element.innerHTML = props.content;
    }

    this.fetchAdditionalProps(props);
  }

  additionalProps(props) {
    Object.entries(props).filter(
      (prop) => !["content", "style"].includes(prop[0])
    );
  }

  fetchAdditionalProps(props) {
    if (this.additionalProps(props)) {
      this.additionalProps(props).forEach((prop) => {
        this.element.setAttribute(prop[0], prop[1]);
      });
    }
  }
}

export default CoreHtmlElement;
