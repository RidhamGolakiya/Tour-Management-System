<?php
class Transaction
{
    /**
     * Register the user
     * @param $connection
     * @param $data
     * @return mixed
     */

     public function updateTransaction($connection,$transaction_id){
        $booking_id = $connection->insert_id;
        $updateTransaction = "update transactions set booking_id = $booking_id where id = $transaction_id";
        $result = $connection->query($updateTransaction) or die("Error in query".$connection->error);
    }

    public function insertTransaction($connection, $data,$user_id,$payment_type)
    {
        $query = "INSERT INTO transactions SET amount='{$data['total_price']}',user_id = {$user_id},payment_type = {$payment_type} ";
        $result1 = $connection->query($query) or die("Error in query".$connection->error);
        $transaction_id = $connection->insert_id;
        $_SESSION['transaction_id'] = $transaction_id;
        $formattedId = sprintf("BK_%05d", $connection->insert_id);
        $packageBooking = "INSERT INTO packageBooking SET status=1,user_id = {$user_id},package_id='{$data['package_id']}',message='{$data['message']}',total_person='{$data['total_person']}',transaction_id=$transaction_id,booking_id='$formattedId'";
        $result = $connection->query($packageBooking) or die("Error in query".$connection->error);
        $this -> updateTransaction($connection,$transaction_id);
        return $result1;
    }



    /**
     * Update the payment status
     * @param $connection
     * @param $txnId
     * @param $userId
     * @return mixed
     */

    public function updatePaymentStatus($connection, $txnId, $userId)
    {
        $query = "UPDATE transactions SET payment_status='Completed', payment_intent='$txnId' WHERE id='$userId' ";
        $result = $connection->query($query) or die("Error in query" . $connection->error);
        return $result;
    }
}
