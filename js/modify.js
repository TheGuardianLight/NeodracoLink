/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

window.onload = function() {
    document.querySelectorAll('.remove_network_button').forEach(button => {
        button.onclick = function(e) {
            let networkId = e.currentTarget.parentElement.parentElement.dataset.networkId;
            console.log(networkId);
            fetch("php/remove_network.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `network_id=${networkId}`
            }).then(response => response.text())
                .then(response => {
                    if (response === 'Ok') {
                        var modalEl = document.querySelector('#notificationRemoveModal');
                        var modal = new bootstrap.Modal(modalEl);

                        modal.show();

                        setTimeout(() => {
                            e.target.parentElement.remove();
                        }, 2000);
                    } else {
                        var modalEl = document.querySelector('#error-modal');
                        var modal = new bootstrap.Modal(modalEl);

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
                    var modalEl = document.querySelector('#notificationModal');
                    var modal = new bootstrap.Modal(modalEl);

                    modal.show();

                    setTimeout(() => {
                        fetch('php/get_networks.php')
                            .then(response => response.text())
                            .then(content => {
                                document.querySelector('#networks_cards').innerHTML = content;
                            });
                    }, 2000);
                } else {
                    var modalEl = document.querySelector('#error-modal');
                    var modal = new bootstrap.Modal(modalEl);

                    document.querySelector('#error-modal-body').textContent = "Erreur lors de l'ajout : " + response;

                    modal.show();
                }
            });
    };
};