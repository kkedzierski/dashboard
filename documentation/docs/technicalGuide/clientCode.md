## Code documentation

A main.js file has been created in the codebase.

This file checks if the user has a token. If so, it redirects them to the dashboard; if not, it redirects them to the login page.

Two folders have been created for components:

- **components** - contains all components used in the application
- **core** - contains components that are used in multiple places within the application

Core components were created to simplify and improve the readability of the code when creating new components.

> How to Add a New Component?
>

To create, for example, an atom:

**Approach for Simple Components**

1.	Create a new folder in src/components/atoms.
2.	In this folder, create a file xxx.js, where the component will be defined.
3.	Add the import for the new component in main.js.
4.	In the xxx.js file, create a class that inherits from CoreHtmlElement.
5.	Define the component name using the propertyName parameter.
6.	Import the core element you want to inherit, such as CoreImg.
7.	Assign it a class or any other attributes as needed.
8.	Pass the element to super.
9.	Define customElement.
10.	Done.

Example:
```javascript
import { CoreImg } from '../../../core/atoms/Img/CoreImg.js';
import CoreHtmlElement from '../../../core/molecules/CoreHtmlElement.js';

export class Logo extends CoreHtmlElement {
  static propertyName = 'app-logo';
  constructor() {
    const logo = CoreImg({
      class: 'logo',
      src: 'assets/images/logo.svg',
      alt: 'Logo',
    });

    super({ element: logo });
  }
}

customElements.define(Logo.propertyName, Logo);

```

**Approach for Complex Components**

1.	Create a new folder in src/components/atoms.
2.	In this folder, create a file xxx.js, where the component will be defined.
3.	Add the import for the new component in main.js.
4.	In the xxx.js file, create a class that inherits from HtmlElement.
5.	Define the component name using the propertyName parameter.
6.	Import the core element you want to inherit, such as CoreImg.
7.	Assign it a class or any other attributes as needed.
8.	Create a shadowRoot with const shadowRoot = this.attachShadow({ mode: 'open' });
9.	Add your element to the shadowRoot using shadowRoot.appendChild(input);
10.	Define customElement.
11.	Done.

Example:
```javascript
import { CoreInput } from '../../../core/atoms/Input/CoreInput.js';
import { CoreStyle } from '../../../core/atoms/Style/CoreStyle.js';

export class Input extends HTMLElement {
  static propertyName = 'app-input';
  constructor(placeholder = '', name = '', type = 'text') {
    super();
    this.placeholder = placeholder;
    this.name = name;
    this.type = type;

    if (this.getAttribute('placeholder')) {
      const placeholder = this.getAttribute('placeholder');
      this.placeholder = placeholder.charAt(0).toUpperCase() + placeholder.slice(1);
    }

    if (this.getAttribute('name')) {
      this.name = this.getAttribute('name');
    }

    if (this.getAttribute('type')) {
      this.type = this.getAttribute('type');
    }

    const shadowRoot = this.attachShadow({ mode: 'open' });
    this.createComponent(shadowRoot);
  }

  createComponent(shadowRoot) {
    const input = CoreInput({
      placeholder: this.placeholder,
      name: this.name,
      type: this.type,
      class: 'input',
    });

    const style = CoreStyle({
      textContent: `
        .input {
          color: black;
          padding: 0.8rem;
          border: 1px solid #ccc;
          border-radius: 5px;
          width: 680px;
          cursor: pointer;
          background-color: rgba(248, 249, 250, 0.8);
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
      `,
    });

    shadowRoot.appendChild(style);
    shadowRoot.appendChild(input);
  }
}

customElements.define(Input.propertyName, Input);

```