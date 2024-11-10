import { config } from '../../../config.js';
import { CoreElement } from '../../../core/atoms/Element/CoreElement.js';
import { CoreForm } from '../../../core/atoms/Form/Form.js';
import { createNewsPost, updateNewsPost } from '../../../services/ResourceApiManager.js';
import { showToast } from '../../atoms/Toast/ToastManager.js';
import { Button } from '../../atoms/Button/Button.js';
import { CoreInput } from '../../../core/atoms/Input/CoreInput.js';
import { CoreImg } from '../../../core/atoms/Img/CoreImg.js';
import { CoreDiv } from '../../../core/atoms/Div/CoreDiv.js';

export class NewsPostActionForm extends HTMLElement {
  static propertyName = 'app-news-post-action-form';

  constructor(postData = null) {
    super();
    this.isEditMode = false;
    this.postData = postData;
    this.id = postData ? postData.id : null;

    const shadowRoot = this.attachShadow({ mode: 'open' });

    this.form = CoreForm({
      class: 'form',
      action: `${config.API_URL}/news-posts`,
      method: this.isEditMode ? 'PATCH' : 'POST',
    });
    this.titleElement = CoreElement({
      type: 'h2',
      props: { class: 'title', textContent: 'Create news' },
    });
    this.headerContainer = CoreDiv({ class: 'header-container' });
    this.titleInput = CoreInput({
      placeholder: 'Title',
      name: 'title',
      class: 'input',
    });
    this.backToCreateButton = CoreImg({
      src: '/assets/images/close.svg',
      alt: 'Back to create',
      class: 'backToCreate',
    });
    this.backToCreateButton.style.display = 'none';
    this.backToCreateButton.addEventListener('click', () => this.backToCreate());

    this.descriptionInput = CoreElement({
      type: 'textarea',
      props: {
        placeholder: 'Description',
        name: 'description',
        class: 'textarea',
        withSlot: false,
      },
    });

    this.submitButton = new Button('submit', 'submit');
    this.submitButton.textContent = 'Create';

    this.headerContainer.appendChild(this.titleElement);
    this.headerContainer.appendChild(this.backToCreateButton);
    this.form.appendChild(this.headerContainer);
    this.form.appendChild(this.titleInput);
    this.form.appendChild(this.descriptionInput);
    this.form.appendChild(this.submitButton);

    this.form.addEventListener('click', (event) => {
      if (event.target.name === 'submit') {
        event.preventDefault();
        const postData = this.prepareData(shadowRoot);
        this.isEditMode ? this.updatePost(postData) : this.createPost(postData);
      }
    });

    const style = document.createElement('style');
    style.textContent = `
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
      .form {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        gap: 1rem;
        margin-bottom: 1rem;
      }
      .header-container {
        display: flex;
        align-items: center;
        width: 100%;
        justify-content: space-between;
      }
      .title {
        align-self: flex-start;
        font-size: 1.5rem;
        color: #15396b;
        text-align: left;
      }
      .textarea {
        background-color: rgba(248, 249, 250, 0.8);
        color: black;
        padding: 0.8rem;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 680px;
        cursor: pointer;
      }
      .backToCreate {
        width: 20px;
        height: 20px;
        cursor: pointer;
        margin-right: 0.8rem;
      }

      @media (max-width: 768px) {
        .input {
          width: 300px;
        }
        .textarea {
          width: 300px;
        }
      }
    `;

    shadowRoot.appendChild(style);
    shadowRoot.appendChild(this.form);
  }

  prepareData = (shadowRoot) => {
    const title = shadowRoot.querySelector('input[name="title"]').value;
    const description = shadowRoot.querySelector('textarea[name="description"]').value;
    return { title, description, id: this.id };
  };

  createPost = (postData) => {
    createNewsPost(postData.title, postData.description)
      .then(() => {
        showToast('News was successfull created!', 'success');
        setTimeout(() => {
          location.reload();
        }, 1000);
      })
      .catch((error) => {
        console.error('Failed to create news post:', error);
        showToast('Failed to create news post', 'error');
      });
  };

  updatePost = (postData) => {
    updateNewsPost(postData.id, postData.title, postData.description)
      .then(() => {
        showToast('News was successfull updated!', 'success');
        setTimeout(() => {
          location.reload();
        }, 1000);
      })
      .catch((error) => {
        console.error('Failed to update news:', error);
        showToast('Failed to update news.', 'error');
      });
  };

  backToCreate = () => {
    this.isEditMode = false;
    this.titleElement.textContent = 'Create News';
    this.submitButton.textContent = 'Create';
    this.titleInput.value = '';
    this.descriptionInput.value = '';
    this.backToCreateButton.style.display = 'none';
  };

  updateForm = (postData) => {
    this.isEditMode = true;
    this.id = postData.id;
    this.postData = postData;
    this.titleElement.textContent = 'Edit News';
    this.submitButton.textContent = 'Save';
    this.titleInput.value = postData.title;
    this.descriptionInput.textContent = postData.content;
    this.backToCreateButton.style.display = 'block';
  };
}

customElements.define(NewsPostActionForm.propertyName, NewsPostActionForm);
