document.addEventListener("DOMContentLoaded", () => {

    const cartButtons = document.querySelectorAll(".ajax-cart");

    cartButtons.forEach(btn => {
        btn.addEventListener("click", function(event) {

            event.preventDefault();
            event.stopPropagation(); // Empêche le mini-panier de se fermer

            const productId = this.dataset.id;
            const productType = this.dataset.type;
            const action = this.dataset.action;

            console.log("🛒 Action détectée :", action, "sur ID :", productId, "Type :", productType);

            let url = null;
            if (action === "increase") url = "/cart/ajax/increase";
            if (action === "decrease") url = "/cart/ajax/decrease";
            if (action === "delete") url = "/cart/ajax/delete";
            if (!url) return;

            // 🔹 Mise à jour instantanée côté front
            const row = document.querySelector(`.ajax-cart[data-id="${productId}"][data-type="${productType}"]`).closest(".navbar-cart-product");
            if (row) {
                const quantityElement = row.querySelector(".cart-quantity");
                const priceElement = row.querySelector(".cart-total");

                if (quantityElement && priceElement) {
                    let currentQty = parseInt(quantityElement.textContent);

                    if (action === "increase") currentQty++;
                    if (action === "decrease") currentQty = currentQty > 1 ? currentQty - 1 : 0;
                    if (action === "delete") currentQty = 0;

                    quantityElement.textContent = currentQty;

                    // Recalcul du prix TTC instantané
                    const priceHT = parseFloat(priceElement.dataset.priceHt);
                    const tva = parseFloat(priceElement.dataset.tva);
                    const priceTTC = priceHT * (1 + tva);
                    priceElement.textContent = (priceTTC * currentQty).toFixed(2).replace(".", ",") + " €";
                }
            }

            // 🔹 Fetch AJAX pour mise à jour réelle côté serveur
            fetch(url, {
                    method: "POST",
                    body: JSON.stringify({ id: productId, type: productType }),
                    headers: { "Content-Type": "application/json" }
                })
                .then(res => res.json())
                .then(data => {
                    updateMiniCart(data, productId, productType);
                })
                .catch(err => console.error("❌ Erreur AJAX mini-panier :", err));
        });
    });
});

function updateMiniCart(data, productId, productType) {

    console.log("🔧 Mise à jour du mini-panier…");

    const element = document.querySelector(`.ajax-cart[data-id="${productId}"][data-type="${productType}"]`);
    if (!element) return;
    const row = element.closest(".navbar-cart-product");
    if (!row) return;

    const item = data.items.find(i => i.id == productId && i.type === productType);
    if (!item) return;

    // Mise à jour quantité et prix (corrige si nécessaire)
    const quantityElement = row.querySelector(".cart-quantity");
    const priceTotalElement = row.querySelector(".cart-total");

    if (quantityElement) quantityElement.textContent = item.quantity;
    if (priceTotalElement) priceTotalElement.textContent = item.price_total_ttc.toFixed(2).replace(".", ",") + " €";

    // Mise à jour total global
    const totalGlobal = document.querySelector(".navbar-cart-total strong");
    if (totalGlobal) totalGlobal.textContent = data.total.toFixed(2).replace(".", ",") + " €";

    console.log("✅ Mini-panier mis à jour !");
}
