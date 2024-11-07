import RequestApiBuilder from './RequestApiBuilder.js';

export const loginUser = (username, password) => {
  const body = {
    username: username,
    password: password,
  };

  return new RequestApiBuilder().setResource('login').setBody(body).post();
};
