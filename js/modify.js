window.onload = function () {
    // Charger les catégories
    fetch('php/get_categories.php')
        .then(response => response.text()) // Utilisez .text() au lieu de .json() pour voir la réponse brute
        .then(text => {
            try {
                const categories = JSON.parse(text); // Parse JSON ici
                let categorySelect = document.querySelector('#category_id');
                categories.forEach(category => {
                    let option = document.createElement('option');
                    option.value = category.cat_id;
                    option.textContent = category.cat_name;
                    categorySelect.appendChild(option);
                });
            } catch (e) {
                console.error('Erreur de parsing JSON:', e);
                console.error('Données renvoyées:', text);
            }
        });

    document.querySelectorAll('.remove_network_button').forEach(button => {
        button.onclick = function (e) {
            let targetElement = e.currentTarget.closest('.card');
            let networkId = targetElement.getAttribute('data-network-id');

            fetch("php/remove_network.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `network_id=${networkId}`
            }).then(response => response.text())
                .then(response => {
                    if (response === 'Ok') {
                        let toast = new bootstrap.Toast(document.getElementById('remove-success-toast'));
                        toast.show();
                        setTimeout(() => {
                            targetElement.remove(); // Assurez-vous de supprimer le bon élément
                        }, 2000);
                    } else {
                        let modal = new bootstrap.Modal(document.getElementById('error-modal'));
                        document.querySelector('#error-modal-body').textContent = "Erreur lors de la suppression : " + response;
                        modal.show();
                    }
                }).catch(error => {
                console.error("Error encountered:", error);
                let modal = new bootstrap.Modal(document.getElementById('error-modal'));
                document.querySelector('#error-modal-body').textContent = "Une erreur s'est produite : " + error;
                modal.show();
            });
        };
    });

    // Ajouter un lien
    document.querySelector('#add_network_form').onsubmit = function (e) {
        e.preventDefault();
        let networkName = document.querySelector('#network_name').value;
        let networkUrl = document.querySelector('#network_url').value;
        let networkIcon = document.querySelector('#network_icon').value;
        let networkNsfw = document.querySelector('#network_nsfw').checked ? 1 : 0; // Convertir en booléen (0 ou 1)
        let networkActive = document.querySelector('#network_active').checked ? 1 : 0; // Convertir en booléen (0 ou 1)
        let categoryId = document.querySelector('#category_id').value;

        fetch("php/add_network.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ // Utiliser URLSearchParams pour encoder les données de formulaire
                network_name: networkName,
                network_url: networkUrl,
                network_icon: networkIcon,
                network_nsfw: networkNsfw,
                network_active: networkActive,
                category_id: categoryId
            }).toString()
        }).then(response => response.text())
            .then(response => {
                if (response === 'Ok') {
                    let toast = new bootstrap.Toast(document.getElementById('add-success-toast'));
                    toast.show();
                    setTimeout(() => {
                        fetch('php/get_networks.php')
                            .then(response => response.text())
                            .then(content => {
                                document.querySelector('#networks_cards').innerHTML = content;
                            });
                    }, 2000);
                } else {
                    let modal = new bootstrap.Modal(document.getElementById('error-modal'));
                    document.querySelector('#error-modal-body').textContent = "Erreur lors de l'ajout : " + response;
                    modal.show();
                }
            });
    };
};