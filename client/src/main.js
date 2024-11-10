import { Section } from './components/atoms/Section/Section.js';
import { Button } from './components/atoms/Button/Button.js';
import { Input } from './components/atoms/Input/Input.js';
import { LoginForm } from './components/molecules/LoginForm/LoginForm.js';
import { Navbar } from './components/molecules/Navbar/Navbar.js';
import { Logo } from './components/atoms/Logo/Logo.js';
import { Main } from './components/atoms/Main/Main.js';
import { Footer } from './components/atoms/Footer/Footer.js';
import { Toast } from './components/atoms/Toast/Toast.js';
import LoginSection from './components/organisms/LoginSection/LoginSection.js';
import { checkAuth } from './services/ResourceApiManager.js';
import { LogoutButton } from './components/molecules/LogoutButton/LogoutButton.js';
import { NewsList } from './components/molecules/NewsList/NewsList.js';

document.addEventListener('DOMContentLoaded', async () => {
  const token = localStorage.getItem('jwtToken');
  const currentPath = window.location.pathname;

  async function validateToken() {
    try {
      const response = await checkAuth();

      if (response.status !== 200) {
        throw new Error('Unauthorized');
      }

      if (currentPath === '/') {
        window.location.href = '/dashboard';
      }
    } catch (error) {
      console.error('Authorization check failed:', error);
      localStorage.removeItem('jwtToken');
      window.location.href = '/';
    }
  }

  if (currentPath === '/' && !token) {
    return;
  }

  if (currentPath === '/' && token) {
    validateToken();
  }

  if (currentPath !== '/' && !token) {
    window.location.href = '/';
    return;
  }

  if (currentPath !== '/' && token) {
    validateToken();
  }
});
