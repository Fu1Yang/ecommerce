<?php

namespace App\Services;

class StripeService implements StripeServiceInterface 
{
    private const STRIPE_PAYEMENT_ID = "session_stripe_payment_id";
    private const STRIPE_PAYEMENT_ORDER = "session_stripe_payment_order";

    public function __construct(
        readonly private string $stripeSecret, 
        readonly private UrlGeneratorInterface $urlGenerator,
        readonly private RequestStack $requestStack)
    {
        Stripe::SetApiKey($stripeSecret);
    }

    public function Paiement($panier, $id_order): string 
    {
        $mySession = $this->requestStack->getSession();
        $session = Session::creat([
            'success_url'=>$this->urlGenerator->generate('app_stripe_success', ['order' => $id_order->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'=>$this->urlGenerator->generate('app_stripe_cancel', ['order' => $id_order->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'payment_method_types'=>['card'], 
            'line_items' => [[$panier]],
            'mode'=>'payment',
        ]);
        $mySession->set(self::STRIPE_PAYMENT_ID, $session->id);
        $mySession->set(self::STRIPE_PAYMENT_ORDER, $id_order->getid());
        return $session->url;
    }
}