document.addEventListener('DOMContentLoaded', function() {
    const promoForm = document.getElementById('promoForm');
    const promoCodeInput = document.getElementById('promoCode');
    const promoMessage = document.getElementById('promoMessage');
    const totalOriginal = document.getElementById('totalOriginal');
    const totalRemiseContainer = document.getElementById('totalRemiseContainer');
    const totalRemise = document.getElementById('totalRemise');
    const reductionPromo = document.getElementById('reductionPromo');

    promoForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const code = promoCodeInput.value.trim();

        if (!code) {
            promoMessage.textContent = 'Veuillez saisir un code promo.';
            return;
        }

        fetch(promoForm.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ promo_code: code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    promoMessage.textContent = data.error;
                    reductionPromo.textContent = '';
                    totalRemiseContainer.style.display = 'none';
                    totalOriginal.style.textDecoration = '';
                } else {
                    promoMessage.textContent = '';
                    reductionPromo.textContent = '-' + data.discount.toFixed(2).replace('.', ',') + ' €';
                    totalOriginal.style.textDecoration = 'line-through';
                    totalRemise.textContent = data.totalAfterPromo.toFixed(2).replace('.', ',') + ' €';
                    totalRemiseContainer.style.display = 'block';
                }
            })
            .catch(err => {
                console.error(err);
                promoMessage.textContent = 'Une erreur est survenue.';
            });
    });
});