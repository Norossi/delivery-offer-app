<?php

namespace App\Controller;

use App\Entity\DeliveryOffer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class DeliveryOfferController extends AbstractController
{
    #[Route('/delivery/offer', name: 'app_delivery_offer')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DeliveryOfferController.php',
        ]);
    }

    #[Route('/api/delivery-offer', name: 'create_delivery_offer', methods: ['POST'])]
    public function createDeliveryOffer(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        ParameterBagInterface $params
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return new JsonResponse(['error' => 'Invalid JSON payload'], 400);
        }

        $requiredFields = ['offer_id', 'customer_id', 'customer_name', 'customer_email', 'pickup_zipcode', 'delivery_zipcode', 'delivery_date'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return new JsonResponse(['error' => "Missing field: $field"], 400);
            }
        }

        $deliveryOffer = new DeliveryOffer();
        $deliveryOffer->setOfferId($data['offer_id']);
        $deliveryOffer->setCustomerId($data['customer_id']);
        $deliveryOffer->setCustomerName($data['customer_name']);
        $deliveryOffer->setCustomerEmail($data['customer_email']);
        $deliveryOffer->setPickupZipcode($data['pickup_zipcode']);
        $deliveryOffer->setDeliveryZipcode($data['delivery_zipcode']);
        $deliveryOffer->setDeliveryDate(new \DateTime($data['delivery_date']));

        $entityManager->persist($deliveryOffer);
        $entityManager->flush();

        $sender = $params->get('SENDER_EMAIL');
        $email = (new Email())
            ->from($sender)
            ->to($data['customer_email'])
            ->subject('Your Delivery Offer Details')
            ->html(sprintf(
                '<h1>Delivery Offer Details</h1>
        <p><strong>Offer ID:</strong> %s</p>
        <p><strong>Pickup Zipcode:</strong> %s</p>
        <p><strong>Delivery Zipcode:</strong> %s</p>
        <p><strong>Delivery Date:</strong> %s</p>
        <p>
            <a href="http://127.0.0.1:8000/api/delivery-offer/response/%s" 
               style="padding: 10px 15px; background-color: blue; color: white; text-decoration: none; border-radius: 5px;">
               Respond to Offer
            </a>
        </p>',
                $data['offer_id'],
                $data['pickup_zipcode'],
                $data['delivery_zipcode'],
                $data['delivery_date'],
                $data['offer_id'],
            ));

        $mailer->send($email);

        return new JsonResponse(['message' => 'Delivery offer created successfully'], 201);
    }

    #[Route('/api/delivery-offer/response/{offerId}', name: 'delivery_offer_response', methods: ['GET'])]
    public function showResponsePage(string $offerId, EntityManagerInterface $entityManager): \Symfony\Component\HttpFoundation\Response
    {
        $deliveryOffer = $entityManager->getRepository(DeliveryOffer::class)->findOneBy(['offerId' => $offerId]);
        if (!$deliveryOffer) {
            throw $this->createNotFoundException('Delivery offer not found.');
        }

        if($deliveryOffer->getStatus() != "pending") {
            return $this->render('deliveryOfferAlreadyResponded.html.twig', [
                'offer' => $deliveryOffer,
            ]);
        } else return $this->render('deliveryOfferResponse.html.twig', [
            'offer' => $deliveryOffer,
        ]);
    }

    #[Route('/api/delivery-offer/{offerId}/respond', name: 'delivery_offer_respond', methods: ['PATCH'])]
    public function respondToDeliveryOffer(
        string $offerId,
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {

        $action = $request->request->get('action');
        if (!in_array($action, ['accept', 'decline'], true)) {
            return new JsonResponse(['error' => 'Invalid action'], 400);
        }


        $deliveryOffer = $entityManager->getRepository(DeliveryOffer::class)->findOneBy(['offerId' => $offerId]);
        if (!$deliveryOffer) {
            return new JsonResponse(['error' => 'Offer not found'], 404);
        }

        $deliveryOffer->setStatus($action);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Offer status updated successfully.',
            'offerId' => $offerId,
            'status' => $action,
        ]);
    }

}
