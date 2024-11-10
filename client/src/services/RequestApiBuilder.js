import { config } from '../config.js';

class RequestApiBuilder {
  constructor() {
    this.mainUrl = config.API_URL;
    this.method = '';
    this.id = '';
    this.params = {};
    this.body = null;
    this.headers = {
      'Content-Type': 'application/json',
    };
  }

  setMethod(method) {
    this.method = method;
    return this;
  }

  setBody(body) {
    this.body = JSON.stringify(body);
    return this;
  }

  addHeader(key, value) {
    this.headers[key] = value;
    return this;
  }

  setBearerToken(token) {
    this.addHeader('Authorization', `Bearer ${token}`);
    return this;
  }

  buildUrl() {
    let url = `${this.mainUrl}/${this.method}`;

    return url;
  }

  async patch() {
    const url = this.buildUrl();
    const options = {
      method: 'PATCH',
      headers: this.headers,
      body: this.body,
    };

    try {
      const response = await fetch(url, options);
      return this.handleResponse(response);
    } catch (error) {
      console.error('Error during PATCH request:', error);
      throw error;
    }
  }

  async delete() {
    const url = this.buildUrl();
    const options = {
      method: 'DELETE',
      headers: this.headers,
      body: this.body,
    };

    try {
      const response = await fetch(url, options);
      return this.handleResponse(response);
    } catch (error) {
      console.error('Error during DELETE request:', error);
      throw error;
    }
  }

  async post() {
    const url = this.buildUrl();
    const options = {
      method: 'POST',
      headers: this.headers,
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
      headers: this.headers,
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
