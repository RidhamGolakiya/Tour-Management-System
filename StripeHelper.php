<?php
require 'vendor/autoload.php';
include_once "config.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
if(!isset($_ENV['STRIPE_API_SECRET_KEY'])) {
    $_SESSION['success']=false;
    $_SESSION['message']='STRIPE_API_SECRET_KEY not defined in .env';
}else{
    define('STRIPE_API_SECRET_KEY',$_ENV['STRIPE_API_SECRET_KEY']);
}

class StripeHelper
{
    /**
     * @var \Stripe\StripeClient
     */
    public $stripeClient;

    public function __construct()
    {
        $this->stripeClient = new \Stripe\StripeClient(STRIPE_API_SECRET_KEY);
    }
    /**
     * Create product
     * @return \Stripe\Product
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createProducts($name)
    {
        return $this->stripeClient->products->create(array(
            'name' => $name,
        ));
    }

    /**
     * Create price
     * @param $product
     * @param $productPrice
     * @return \Stripe\Price
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createProductPrice($product, $productPrice)
    {
        return $this->stripeClient->prices->create(array(
            'unit_amount' => $productPrice * 100,
            'currency' => 'INR',
            'product' => $product->id,
        ));
    }
    /**
     * Get session detail
     * @param $sessionId
     * @return \Stripe\Checkout\Session
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function getSession($sessionId)
    {
        return $this->stripeClient->checkout->sessions->retrieve($sessionId);
    }

}
