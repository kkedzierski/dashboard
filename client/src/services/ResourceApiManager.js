import RequestApiBuilder from './RequestApiBuilder.js';

export const loginUser = (username, password) => {
  const body = {
    username: username,
    password: password,
  };

  return new RequestApiBuilder().setMethod('login').setBody(body).post();
};

export const checkAuth = () => {
  const token = localStorage.getItem('jwtToken');
  return new RequestApiBuilder().setMethod('auth').setBearerToken(token).get();
};

export const getNewsPosts = () => {
  const token = localStorage.getItem('jwtToken');
  return new RequestApiBuilder().setMethod('news-posts').setBearerToken(token).get();
};

export const createNewsPost = (title, description) => {
  const body = {
    title: title,
    content: description,
  };

  const token = localStorage.getItem('jwtToken');
  return new RequestApiBuilder().setMethod('news-posts').setBody(body).setBearerToken(token).post();
};

export const deleteNewsPost = (id) => {
  const body = {
    id: id,
  };
  const token = localStorage.getItem('jwtToken');
  return new RequestApiBuilder()
    .setMethod('news-posts')
    .setBody(body)
    .setBearerToken(token)
    .delete();
};

export const updateNewsPost = (id, title, description) => {
  const body = {
    id: id,
    title: title,
    content: description,
  };

  const token = localStorage.getItem('jwtToken');
  return new RequestApiBuilder()
    .setMethod('news-posts')
    .setBody(body)
    .setBearerToken(token)
    .patch();
};
