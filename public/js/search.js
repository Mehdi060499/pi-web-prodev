document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-input');
    const vendeurContainer = document.getElementById('vendeur-container');
  
    searchForm.addEventListener('submit', function(event) {
      event.preventDefault();
  
      const searchTerm = searchInput.value.trim();
  
      if (searchTerm.length > 0) {
        fetch('/search?search=' + searchTerm)
          .then(response => response.json())
          .then(data => {
            vendeurContainer.innerHTML = '';
            data.vendeurs.forEach(vendeur => {
              const vendeurElement = `
                <div class="row vendeur">
                  <div class="col-lg-4 col-md-3">
                    <img src="/uploads/${vendeur.image}" class="img-fluid" />
                  </div>
                  <div class="col-lg-8 col-md-9" id="slimtest1" style="height: 250px;">
                    <div>
                      {% for stock in Stock %}
                        {% if  stock.IdVendeur == vendeur %}
                          <h5 style="color: green;">This product is already added to the Database</h5>
                        {% endif %}
                      {% endfor %}
                      <p>${vendeur.nom}</p>
                      <p>${vendeur.nomproduit}</p>
                      <p>${vendeur.email}</p>
                      <p>${vendeur.motdepasse}</p>
                      <p>${vendeur.description}</p>
                      <p>${vendeur.type}</p>
                      <a class="btn btn-primary" href="/update/${vendeur.IdVendeur}" role="button">edite</a>
                      <a class="btn" href="/delete/${vendeur.IdVendeur}">delete</a>
                      <a class="btn btn-primary" href="/stock/new/${vendeur.IdVendeur}" role="button">insert into stock</a>
                    </div>
                  </div>
                </div>
                <br>
                <br>
                <br>
              `;
              vendeurContainer.insertAdjacentHTML('beforeend', vendeurElement);
            });
          })
          .catch(error => console.error('Error:', error));
      } else {
        // Fetch all vendeurs if the search term is empty
        fetch('/search')
          .then(response => response.json())
          .then(data => {
            vendeurContainer.innerHTML = '';
            data.vendeurs.forEach(vendeur => {
              const vendeurElement = `
                <div class="row vendeur">
                  <div class="col-lg-4 col-md-3">
                    <img src="/uploads/${vendeur.image}" class="img-fluid" />
                  </div>
                  <div class="col-lg-8 col-md-9" id="slimtest1" style="height: 250px;">
                    <div>
                      {% for stock in Stock %}
                        {% if  stock.IdVendeur == vendeur %}
                          <h5 style="color: green;">This product is already added to the Database</h5>
                        {% endif %}
                      {% endfor %}
                      <p>${vendeur.nom}</p>
                      <p>${vendeur.nomproduit}</p>
                      <p>${vendeur.email}</p>
                      <p>${vendeur.motdepasse}</p>
                      <p>${vendeur.description}</p>
                      <p>${vendeur.type}</p>
                      <a class="btn btn-primary" href="/update/${vendeur.IdVendeur}" role="button">edite</a>
                      <a class="btn" href="/delete/${vendeur.IdVendeur}">delete</a>
                      <a class="btn btn-primary" href="/stock/new/${vendeur.IdVendeur}" role="button">insert into stock</a>
                    </div>
                  </div>
                </div>
                <br>
                <br>
                <br>
              `;
              vendeurContainer.insertAdjacentHTML('beforeend', vendeurElement);
            });
          })
          .catch(error => console.error('Error:', error));
      }
    });
  });