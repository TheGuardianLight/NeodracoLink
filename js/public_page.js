/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

function copyToClipboard(e) {
    var text = e.target.getAttribute('data-clipboard-text');
    var textarea = document.createElement('textarea');
    textarea.textContent = text;
    textarea.style.position = 'fixed';
    document.body.appendChild(textarea);
    textarea.select();
    try {
        return document.execCommand('copy');
    } catch (ex) {
        console.warn('Copy to clipboard failed.', ex);
        return false;
    } finally {
        document.body.removeChild(textarea);
    }
}

function share(e, url) {
    if (navigator.share) {
        navigator.share({
            title: 'Check out this website',
            text: 'Here is a website I think you will like',
            url: url,
        })
            .then(() => console.log('Successful share'))
            .catch((error) => console.log('Error sharing', error));
    } else {
        console.log("Your browser does not support the Web Share API");
    }
    e.preventDefault();
}

// Modal lien désactivé ou NSFW
document.addEventListener('DOMContentLoaded', function () {
    var currentUrl = '';
    var modal = new bootstrap.Modal(document.getElementById('nsfwModal'), {
        keyboard: false
    });

    document.getElementById('continueBtn').addEventListener('click', function () {
        window.open(currentUrl, '_blank');
        modal.hide();
    });

    window.warnBeforeNsfw = function (event, url, isNsfw, isActive) {
        event.preventDefault(); // Always prevent default to avoid double open

        var modalTitle = document.getElementById('nsfwModalLabel');
        var modalMessage = document.getElementById('modalMessage');
        var modalFooter = document.getElementById('modalFooter');

        if (isActive === 0) {
            modalTitle.innerText = 'Lien désactivé';
            modalMessage.innerText = 'Ce lien est actuellement désactivé.';
            modalFooter.style.display = 'none';
            modal.show();
        } else if (isNsfw === 1) {
            currentUrl = url;
            modalTitle.innerText = 'Avertissement';
            modalMessage.innerText = 'Ce lien est marqué comme NSFW. Êtes-vous sûr de vouloir continuer?';
            modalFooter.style.display = 'block';
            modal.show();
        } else {
            window.open(url, '_blank');
        }
    };
});