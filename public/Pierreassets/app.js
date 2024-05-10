// Importation des styles CSS
import './styles/app.css';
import './styles/reset.css';
import './styles/style.css';
import './styles/bootstrap.min.css';
import './lib/owlcarousel/assets/owl.carousel.min.css';
import './lib/lightbox/css/lightbox.min.css';
import 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css';
import 'https://use.fontawesome.com/releases/v5.15.4/css/all.css';
import 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap';
import 'https://fonts.gstatic.com';
import 'https://fonts.googleapis.com';


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



  // Récupérez tous les éléments avec la classe "delete-article"
const deleteButtons = document.querySelectorAll('.delete-article');

// Parcourez chaque bouton et ajoutez un gestionnaire d'événements de clic
deleteButtons.forEach(button => {
    button.addEventListener('click', (event) => {
        // Récupérez l'ID de l'article à supprimer à partir de l'attribut data-article-id
        const articleId = event.target.dataset.articleId;

        // Envoyez une demande AJAX pour supprimer l'article du panier
        fetch(`/articles/{id}/delete`, {
            method: 'DELETE',
        })
        .then(response => {
            // Vérifiez si la suppression a réussi
            if (response.ok) {
                // Rechargez la page ou mettez à jour le panier d'une autre manière
                window.location.reload();
            } else {
                // Gérez les erreurs si nécessaire
                console.error('Erreur lors de la suppression de l\'article');
            }
        })
        .catch(error => {
            console.error('Erreur réseau :', error);
        });
    });
});

    // Fonction pour effectuer la recherche
    function searchArticle() {
        // Récupérer le terme de recherche saisi par l'utilisateur
        var searchTerm = document.getElementById('search-input').value;

        // Effectuer une requête AJAX vers votre contrôleur Symfony pour récupérer les résultats de la recherche
        $.ajax({
            url: '/search-article', // Remplacez cette URL par l'URL de votre contrôleur Symfony
            method: 'POST',
            data: { searchTerm: searchTerm },
            success: function(response) {
                // Mettre à jour l'affichage avec les résultats de la recherche
                // Ici, vous pouvez utiliser jQuery ou JavaScript pour manipuler le DOM et afficher les résultats
            },
            error: function(error) {
                console.error('Erreur lors de la recherche :', error);
            }
        });
    }

    // Attacher un gestionnaire d'événement à la touche "Enter" pour déclencher la recherche
    document.getElementById('search-input').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            searchArticle();
        }
    });










