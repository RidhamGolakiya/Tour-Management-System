
<?php
session_start();
require_once "./config.php";
require_once "StripeHelper.php";
require_once "Transaction.php";

$stripeHelper = new StripeHelper();
$transactionHelper = new Transaction();
if(isset($_SESSION['user_id'])){
    $sessionId = $_GET['session_id'];
    $userId = $_SESSION['transaction_id'];
    $checkoutSession = $stripeHelper->getSession($sessionId);
    $transactionHelper->updatePaymentStatus($con, $checkoutSession->payment_intent, $userId);
    $_SESSION['success'] = true;
    $_SESSION['message'] = "You successfully booked a tour package. Go to dashboard for show more details.";
    header("Location: $appUrl/packages.php");
    exit;
}else{
    header("Location: $appUrl");
}
?>

