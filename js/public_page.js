/**
 * Copyright (c) 2024 - Veivneorul.
 * This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

function copyToClipboard(e) {
    const text = e.target.getAttribute('data-clipboard-text');
    const textarea = document.createElement('textarea');
    textarea.textContent = text;
    textarea.style.position = 'fixed';
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand('copy');
        console.log('Text copied to clipboard');
    } catch (err) {
        console.warn('Copy to clipboard failed.', err);
    } finally {
        document.body.removeChild(textarea);
    }
}

async function share(e, url) {
    e.preventDefault();
    if (navigator.share) {
        try {
            await navigator.share({
                title: 'Check out this website',
                text: 'Here is a website I think you will like',
                url: url,
            });
            console.log('Successful share');
        } catch (error) {
            console.log('Error sharing', error);
        }
    } else {
        console.log('Your browser does not support the Web Share API');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const modalElement = document.getElementById('nsfwModal');
    const modal = new bootstrap.Modal(modalElement, { keyboard: false });
    const modalTitle = document.getElementById('nsfwModalLabel');
    const modalMessage = document.getElementById('modalMessage');
    const modalFooter = document.getElementById('modalFooter');
    let currentUrl = '';

    document.getElementById('continueBtn').addEventListener('click', () => {
        window.open(currentUrl, '_blank');
        modal.hide();
    });

    window.warnBeforeNsfw = (event, url, isNsfw, isActive) => {
        event.preventDefault();

        if (isActive === 0) {
            modalTitle.innerText = 'Lien désactivé';
            modalMessage.innerText = 'Ce lien est actuellement désactivé.';
            modalFooter.style.display = 'none';
        } else if (isNsfw === 1) {
            currentUrl = url;
            modalTitle.innerText = 'Avertissement';
            modalMessage.innerText = 'Ce lien est marqué comme NSFW. Êtes-vous sûr de vouloir continuer?';
            modalFooter.style.display = 'block';
        } else {
            window.open(url, '_blank');
            return;
        }

        modal.show();
    };
});