window.onload = function() {
    document.querySelectorAll('.remove_network_button').forEach(button => {
        button.onclick = function(e) {
            let networkId = e.currentTarget.parentElement.parentElement.dataset.networkId;
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
                            e.target.parentElement.remove();
                        }, 2000);
                    } else {
                        let modal = new bootstrap.Modal(document.getElementById('error-modal'));
                        document.querySelector('#error-modal-body').textContent = "Erreur lors de la suppression : " + response;
                        modal.show();
                    }
                });
        }
    });

    document.querySelector('#add_network_form').onsubmit = function(e) {
        e.preventDefault();
        let networkName = document.querySelector('#network_name').value;
        let networkUrl = document.querySelector('#network_url').value;
        let networkIcon = document.querySelector('#network_icon').value;
        fetch("php/add_network.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `network_name=${networkName}&network_url=${networkUrl}&network_icon=${networkIcon}`
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

    document.querySelectorAll('.update-order').forEach(function(button) {
        button.addEventListener('click', function (event) {
            let networkId = button.parentElement.parentElement.dataset.networkId;
            let newOrder = button.previousElementSibling.value;

            fetch('/php/update_network_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'networkId=' + encodeURIComponent(networkId) + '&newOrder=' + encodeURIComponent(newOrder),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        let toast = new bootstrap.Toast(document.getElementById('update-success-toast'));
                        toast.show();
                    } else {
                        let modal = new bootstrap.Modal(document.getElementById('error-modal'));
                        document.querySelector('#error-modal-body').textContent = "Erreur lors de la mise à jour : " + data.error;
                        modal.show();
                    }
                })
                .catch(error => {
                    let modal = new bootstrap.Modal(document.getElementById('error-modal'));
                    document.querySelector('#error-modal-body').textContent = "Erreur lors de la requête fetch: " + error;
                    modal.show();
                });
        });
    });
};