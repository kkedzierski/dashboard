import { CoreDiv } from '../../../core/atoms/Div/CoreDiv.js';
import { CoreElement } from '../../../core/atoms/Element/CoreElement.js';
import { CoreStyle } from '../../../core/atoms/Style/CoreStyle.js';
import { getNewsPosts } from '../../../services/ResourceApiManager.js';
import { News } from '../../atoms/News/News.js';

export class NewsList extends HTMLElement {
  static propertyName = 'app-news-list';
  constructor() {
    super();
    const shadowRoot = this.attachShadow({ mode: 'open' });
    const { wrapper, container } = this.createComponent(shadowRoot);

    this.loadNewsPosts(wrapper, container);
  }

  createComponent(shadowRoot) {
    const wrapper = CoreDiv({ class: 'news-list' });
    const container = CoreDiv({ class: 'news-container' });
    const title = CoreElement({ type: 'h2', props: { class: 'title', textContent: 'All News' } });
    const style = CoreStyle({
      textContent: `
        .news-list {
          display: flex;
          flex-direction: column;
          color: #15396b;
          font-weight: bold;
        }
      `,
    });

    shadowRoot.appendChild(wrapper);
    wrapper.appendChild(title);
    wrapper.appendChild(container);
    shadowRoot.appendChild(style);

    return { wrapper, container };
  }

  async loadNewsPosts(wrapper, container) {
    try {
      const newsPosts = await getNewsPosts();

      if (newsPosts.length === 0) {
        wrapper.style.display = 'none';
      } else {
        wrapper.style.display = 'block';
      }

      newsPosts.forEach(({ id, title, content }) => {
        const newsPost = new News(id, title, content);
        container.appendChild(newsPost);
      });
    } catch (error) {
      console.error('Failed to load news posts:', error);
    }
  }
}

customElements.define(NewsList.propertyName, NewsList);
