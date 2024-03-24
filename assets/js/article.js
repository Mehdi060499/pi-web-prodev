document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-article');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const articleId = button.dataset.articleId;

            if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
                fetch(`/article/${articleId}`, {
                    method: 'DELETE'
                })
                .then(response => {
                    if (response.ok) {
                        location.reload(); // Rafraîchir la page une fois la suppression effectuée
                    } else {
                        console.error('Erreur lors de la suppression de l\'article.');
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la suppression de l\'article :', error);
                });
            }
        });
    });
});
