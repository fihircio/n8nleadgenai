<?php

namespace Tcja\NOWPaymentsLaravel\Exceptions;

use Exception;
use Tcja\NOWPaymentsLaravel\Models\Subscription;

class IncompletePayment extends Exception
{
    /**
     * The subscription instance.
     *
     * @var \Tcja\NOWPaymentsLaravel\Models\Subscription
     */
    public $subscription;

    /**
     * The payment error details.
     *
     * @var array
     */
    public $payment;

    /**
     * Create a new IncompletePayment instance.
     *
     * @param  \Tcja\NOWPaymentsLaravel\Models\Subscription  $subscription
     * @param  array  $payment
     * @param  string|null  $message
     * @return void
     */
    public function __construct(Subscription $subscription, array $payment, $message = null)
    {
        parent::__construct($message ?? 'The payment for subscription renewal was unsuccessful.');

        $this->subscription = $subscription;
        $this->payment = $payment;
    }

    /**
     * Get the payment's error message.
     *
     * @return string|null
     */
    public function errorMessage(): ?string
    {
        return $this->payment['error']['message'] ?? null;
    }

    /**
     * Attempt to pay the incomplete payment.
     *
     * @return void
     *
     * @throws \Tcja\NOWPaymentsLaravel\Exceptions\IncompletePayment
     */
    public function pay(): void
    {
        $payment = $this->subscription->billable->nowpayments()->retryPayment($this->payment['id']);

        if ($payment['status'] !== 'completed') {
            throw new static($this->subscription, $payment);
        }

        $this->subscription->updatePaymentStatus($payment);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_merge($this->payment, [
            'subscription' => $this->subscription->toArray(),
        ]);
    }
}