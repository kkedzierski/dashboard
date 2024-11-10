import { CoreDiv } from '../../../core/atoms/Div/CoreDiv.js';
import { CoreImg } from '../../../core/atoms/Img/CoreImg.js';
import { deleteNewsPost } from '../../../services/ResourceApiManager.js';
import { NewsPostActionForm } from '../../organisms/NewsPostActionForm/NewsPostActionForm.js';
import { showToast } from '../Toast/ToastManager.js';

export class News extends HTMLElement {
  static propertyName = 'app-news-post';

  constructor(id, title, content) {
    super();
    this.id = id;
    this.title = title;
    this.content = content;
    const shadowRoot = this.attachShadow({ mode: 'open' });
    const style = document.createElement('style');
    style.textContent = `
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
    `;

    const container = CoreDiv({ class: 'newsPost' });

    const contentDiv = CoreDiv({ class: 'content' });
    const titleElement = CoreDiv({ class: 'title', textContent: title });
    const contentElement = CoreDiv({ class: 'text', textContent: content });
    contentDiv.appendChild(titleElement);
    contentDiv.appendChild(contentElement);

    const actionsDiv = CoreDiv({ class: 'actions' });

    const editIcon = CoreImg({ src: '/assets/images/pencil.svg', alt: 'Edit' });
    editIcon.addEventListener('click', () => this.handleEdit());

    const deleteIcon = CoreImg({ src: '/assets/images/close.svg', alt: 'Delete' });
    deleteIcon.addEventListener('click', () => this.handleDelete());

    actionsDiv.appendChild(editIcon);
    actionsDiv.appendChild(deleteIcon);

    container.appendChild(contentDiv);
    container.appendChild(actionsDiv);
    shadowRoot.appendChild(style);
    shadowRoot.appendChild(container);
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
        throw new Error('Failed to delete post');
      }
    } catch (error) {
      console.error('Delete failed:', error);
      showToast('Failed to delete post', 'error');
    }
  }
}

customElements.define(News.propertyName, News);
