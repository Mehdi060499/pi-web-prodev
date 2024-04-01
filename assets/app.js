// Importation des styles CSS
import './styles/app.css';
import './styles/reset.css';

const searchIcon = document.getElementById('search-icon');
const searchBox = document.getElementById('search-box');
const searchInput = document.getElementById('search-input');


searchIcon.addEventListener('click', () => {
  searchBox.style.display = searchBox.style.display === 'none' ? 'block' : 'none';
});

searchInput.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
      // Lancer la recherche ici
      window.location.href = "/articles/show/{nom}";
    }
  });

  import { createCollapsible, melt } from '@melt-ui/svelte'
  const {
    elements: { root, content, trigger },
    states: { open }
  } = createCollapsible()




