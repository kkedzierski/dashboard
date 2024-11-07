import { config } from '../config.js';

class RequestApiBuilder {
  constructor() {
    this.mainUrl = config.API_URL;
    this.resource = '';
    this.id = '';
    this.params = {};
    this.body = null;
  }

  setResource(resource) {
    this.resource = resource;
    return this;
  }

  setBody(body) {
    this.body = JSON.stringify(body);
    return this;
  }

  buildUrl() {
    let url = `${this.mainUrl}/${this.resource}`;

    return url;
  }

  async post() {
    const url = this.buildUrl();
    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: this.body,
    };

    try {
      const response = await fetch(url, options);

      return this.handleResponse(response);
    } catch (error) {
      console.error('Error during POST request:', error);
      throw error;
    }
  }

  async get() {
    const url = this.buildUrl();
    const options = {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    };

    try {
      const response = await fetch(url, options);
      return this.handleResponse(response);
    } catch (error) {
      console.error('Error during GET request:', error);
      throw error;
    }
  }

  async handleResponse(response) {
    if (!response.ok) {
      const error = await response.text();
      throw new Error(`Request failed with status ${response.status}: ${error}`);
    }

    try {
      return await response.json();
    } catch (error) {
      console.error('Failed to parse response as JSON:', error);
      throw new Error('Response was not valid JSON');
    }
  }
}

export default RequestApiBuilder;
