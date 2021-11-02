<?php

declare(strict_types=1);

namespace FeeCalcApp\Service\Validation;

use Symfony\Component\Validator\Constraints as Assert;

class TransactionRequestMetadata
{
    private array $supportedCurrencyCodes;
    private array $supportedOperationTypes;
    private array $supportedClientTypes;
    private string $dateFormat;

    public function __construct(
        array $supportedCurrencyCodes,
        array $supportedOperationTypes,
        array $supportedClientTypes,
        string $dateFormat
    ) {
        $this->supportedCurrencyCodes = $supportedCurrencyCodes;
        $this->supportedOperationTypes = $supportedOperationTypes;
        $this->supportedClientTypes = $supportedClientTypes;
        $this->dateFormat = $dateFormat;
    }

    public function getMetadata(): array
    {
        return [
            'date' => [
                new Assert\DateTime($this->dateFormat, 'Wrong format of date time was provided'),
                new Assert\NotNull(),
            ],
            'userId' => [
                new Assert\Regex('/^[1-9][0-9]*$/', 'Invalid value of userId field was provided'),
                new Assert\NotNull(),
            ],
            'clientType' => [
                $this->getChoiceConstraint('client type', $this->supportedClientTypes),
                new Assert\NotNull(),
            ],
            'operationType' => [
                $this->getChoiceConstraint('operation type', $this->supportedOperationTypes),
                new Assert\NotNull(),
            ],
            'amount' => [
                new Assert\Regex('/^(0|[1-9]\d*)(.\d+)?$/', 'Amount in wrong format was provided'),
                new Assert\NotNull(),
            ],
            'currencyCode' => [
                $this->getChoiceConstraint('currency code', $this->supportedCurrencyCodes),
                new Assert\NotNull(),
            ],
        ];
    }

    private function getChoiceConstraint(string $fieldName, array $supportedOptions): Assert\Choice
    {
        $msgTemplate = 'Unsupported value of %s was provided. Supported values are %s';
        $constraint = new Assert\Choice($supportedOptions);
        $constraint->message = sprintf(
            $msgTemplate,
            $fieldName,
            implode(', ', $supportedOptions)
        );

        return $constraint;
    }
}
