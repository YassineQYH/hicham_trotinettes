<?php

namespace App\Service;

use App\Entity\Promotion;
use App\Entity\Product;

class PromotionService
{
    /**
     * Applique une promotion sur un prix donné
     *
     * La logique a été modifiée :
     * - Le targetType "TARGET_CATEGORY_ACCESS" vérifie désormais que
     *   le type Doctrine du produit est "accessoire"
     */
    public function applyPromotion(Promotion $promo, float $price, Product $product = null): float
    {
        if (!$promo->canBeUsed()) {
            throw new \LogicException("Promotion '{$promo->getCode()}' is not active or available.");
        }

        // Vérifie que le produit correspond au targetType
        switch ($promo->getTargetType()) {

            case Promotion::TARGET_ALL:
                // aucune restriction
                break;

            case Promotion::TARGET_CATEGORY_ACCESS:
                // IMPORTANT :
                // On vérifie maintenant le type du produit via Doctrine (discriminator)
                // Accessoire → "accessoire"
                    if (!$product || $product->getType() !== 'accessoire') {
                        throw new \LogicException("Cette promotion n'est valable que sur les accessoires.");
                    }
                    break;

            case Promotion::TARGET_PRODUCT:
                if (!$product || $product !== $promo->getProduct()) {
                    throw new \LogicException("Promotion '{$promo->getCode()}' is not valid for this product.");
                }
                break;

            case Promotion::TARGET_PRODUCT_LIST:
                if (!$product || !$promo->getProducts()->contains($product)) {
                    throw new \LogicException("Promotion '{$promo->getCode()}' is not valid for this product.");
                }
                break;
        }

        // Application de la remise
        if ($promo->getDiscountAmount() !== null) {
            // Remise en valeur (€)
            $price -= $promo->getDiscountAmount();
        } elseif ($promo->getDiscountPercent() !== null) {
            // Remise en %
            $price -= $price * ($promo->getDiscountPercent() / 100);
        }

        // Sécurité : prix minimum = 0
        return max(0, $price);
    }
}
