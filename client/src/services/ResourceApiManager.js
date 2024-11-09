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
