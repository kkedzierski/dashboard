import { CoreDiv } from '../../../core/atoms/Div/CoreDiv.js';
import { CoreImg } from '../../../core/atoms/Img/CoreImg.js';
import { CoreStyle } from '../../../core/atoms/Style/CoreStyle.js';
import { deleteNewsPost } from '../../../services/ResourceApiManager.js';
import { NewsPostActionForm } from '../../organisms/NewsPostActionForm/NewsPostActionForm.js';
import { showToast } from '../Toast/ToastManager.js';

export class News extends HTMLElement {
  static propertyName = 'app-news-post';

  constructor(id = '', title = '', content = '') {
    super();
    this.id = id;
    this.title = title;
    this.content = content;
    const shadowRoot = this.attachShadow({ mode: 'open' });
    this.createComponent(shadowRoot);

    this.editIcon.addEventListener('click', () => this.handleEdit());
    this.deleteIcon.addEventListener('click', () => this.handleDelete());
  }

  createComponent(shadowRoot) {
    const style = CoreStyle({
      textContent: `
        .newsPost {
          display: flex;
          align-items: center;
          justify-content: space-between;
          color: #15396b;
          padding-right: 0.8rem;
          border: 1px solid #ccc;
          border-radius: 5px;
          width: 680px;
          background-color: rgba(248, 249, 250, 0.8);
          margin-bottom: 10px;
        }

        .content {
          display: flex;
          flex-direction: row;
          font-weight: normal;
          padding: 1rem;
        }

        .title {
          font-weight: bold;
          margin-bottom: 5px;
          padding-right: 10px;
        }

        .actions {
          display: flex;
          gap: 10px;
          padding-right: 10px;
        }

        .actions img {
          width: 20px;
          height: 20px;
          cursor: pointer;
        }

        @media (max-width: 768px) {
          .newsPost {
            width: 300px;
          }
        }
      `,
    });

    this.container = CoreDiv({ class: 'newsPost' });

    this.contentDiv = CoreDiv({ class: 'content' });
    this.titleElement = CoreDiv({ class: 'title', textContent: this.title });
    this.contentElement = CoreDiv({ class: 'text', textContent: this.content });
    this.contentDiv.appendChild(this.titleElement);
    this.contentDiv.appendChild(this.contentElement);

    this.actionsContainer = CoreDiv({ class: 'actions' });
    this.editIcon = CoreImg({ src: '/assets/images/pencil.svg', alt: 'Edit' });
    this.deleteIcon = CoreImg({ src: '/assets/images/close.svg', alt: 'Delete' });
    this.actionsContainer.appendChild(this.editIcon);
    this.actionsContainer.appendChild(this.deleteIcon);

    this.container.appendChild(this.contentDiv);
    this.container.appendChild(this.actionsContainer);
    shadowRoot.appendChild(style);
    shadowRoot.appendChild(this.container);
  }

  handleEdit() {
    let form = document.querySelector('app-news-post-action-form');
    if (!form) {
      form = new NewsPostActionForm({ id: this.id, title: this.title, content: this.content });
      document.body.appendChild(form);
    } else {
      form.updateForm({ id: this.id, title: this.title, content: this.content });
    }
  }

  async handleDelete() {
    try {
      const response = await deleteNewsPost(this.id);
      if (response.status === 200) {
        showToast('News was deleted!', 'success');
        setTimeout(() => {
          location.reload();
        }, 1000);
      } else {
        throw new Error('Failed to delete post.');
      }
    } catch (error) {
      console.error('Delete failed:', error);
      showToast('Failed to delete post.', 'error');
    }
  }
}

customElements.define(News.propertyName, News);
