<?php

namespace App\Entity;

use App\Repository\DeliveryOfferRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryOfferRepository::class)]
class DeliveryOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $offerId = null;

    #[ORM\Column(length: 255)]
    private ?string $customerId = null;

    #[ORM\Column(length: 255)]
    private ?string $customerName = null;

    #[ORM\Column(length: 255)]
    private ?string $customerEmail = null;

    #[ORM\Column(length: 255)]
    private ?string $deliveryZipcode = null;

    #[ORM\Column(length: 255)]
    private ?string $pickupZipcode = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $deliveryDate = null;

    #[ORM\Column(length: 255, options: ["default" => "pending"])]
    private ?string $status = "pending";

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOfferId(): ?string
    {
        return $this->offerId;
    }

    public function setOfferId(string $offerId): static
    {
        $this->offerId = $offerId;

        return $this;
    }

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function setCustomerId(string $customerId): static
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): static
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(string $customerEmail): static
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    public function getDeliveryZipcode(): ?string
    {
        return $this->deliveryZipcode;
    }

    public function setDeliveryZipcode(string $deliveryZipcode): static
    {
        $this->deliveryZipcode = $deliveryZipcode;

        return $this;
    }

    public function getPickupZipcode(): ?string
    {
        return $this->pickupZipcode;
    }

    public function setPickupZipcode(string $pickupZipcode): static
    {
        $this->pickupZipcode = $pickupZipcode;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(\DateTimeInterface $deliveryDate): static
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
