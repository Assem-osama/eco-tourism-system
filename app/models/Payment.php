<?php

class Payment
{
    private $db;

    public function __construct($databaseConnection)
    {
        $this->db = $databaseConnection;
    }

    public function createPayment($data)
    {
        $sql = "INSERT INTO payments (booking_id, user_id, amount, currency, transaction_id, payment_method, payment_status)
                VALUES (:booking_id, :user_id, :amount, :currency, :transaction_id, :payment_method, :payment_status)";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->execute([
            ':booking_id' => $data['booking_id'],
            ':user_id' => $data['user_id'],
            ':amount' => $data['amount'],
            ':currency' => $data['currency'] ?? 'EGP',
            ':transaction_id' => $data['transaction_id'] ?? null,
            ':payment_method' => $data['payment_method'] ?? 'visa',
            ':payment_status' => $data['payment_status'] ?? 'pending'
        ]);

        return $this->db->lastInsertId();
    }

    public function updateStatus($transaction_id, $status)
    {
        $sql = "UPDATE payments SET payment_status = :status WHERE transaction_id = :transaction_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':transaction_id' => $transaction_id
        ]);
    }
}
