<?php

namespace App\Controller;

use App\Entity\AccountApplication;
use App\Enum\AccountApplicationStatus;
use App\Repository\AccountApplicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class VendorApiController extends AbstractController
{

    #[Route('/api/moysklad/vendor/1.0/apps/{appId}/{accountId}', name: 'app_activate', methods: ['PUT'])]
    public function activate(
        Request $request, 
        string $appId, 
        string $accountId, 
        AccountApplicationRepository $accountApplicationRepository,
        EntityManagerInterface $em
    ): Response
    {
        $requestData = json_decode($request->getContent(), true);

        $validateData = !empty($requestData) and
                        !empty($requestData['cause']) and
                        !empty($requestData['access']) and 
                        !empty($requestData['subscription']);

        if(!$validateData){
            return new JsonResponse([
                'Invalid Request'
            ], 400);
        }

        $accountApplication = $accountApplicationRepository->getByIdentification(appId: $appId, accountId: $accountId);
        if(!$accountApplication){
            $accountApplication = new AccountApplication;
            $accountApplication
                ->setAppId($appId)
                ->setAppUid($requestData['appUid'])
                ->setAccountId($accountId)
                ->setAccountName($requestData['accountName'])
                ->setStatus(AccountApplicationStatus::SETTINGS_REQUIRED);
        }else{
            $accountApplication->setStatus(AccountApplicationStatus::ACTIVATED);
        }
        $accountApplication
            ->setAccess($requestData['access'])
            ->setCause($requestData['cause'])
            ->setSubscription($requestData['subscription']);

        $em->persist($accountApplication);
        $em->flush();
        
        return new JsonResponse([
            'status' => $accountApplication->getStatus()
        ]);
    }

    #[Route('/api/moysklad/vendor/1.0/apps/{appId}/{accountId}', name: 'app_info', methods: ['GET'])]
    public function info(
        string $appId, 
        string $accountId, 
        AccountApplicationRepository $accountApplicationRepository
    ): Response
    {
        $accountApplication = $accountApplicationRepository->getByIdentification(appId: $appId, accountId: $accountId);

        if(!$accountApplication or !in_array($accountApplication->getStatus(), [
                AccountApplicationStatus::ACTIVATING,
                AccountApplicationStatus::SETTINGS_REQUIRED,
                AccountApplicationStatus::ACTIVATED
            ])){
            return new JsonResponse([
                'status' => AccountApplicationStatus::ERROR
            ], 404);
        }

        return new JsonResponse([
            'status' => $accountApplication->getStatus()
        ]);
    }

    #[Route('/api/moysklad/vendor/1.0/apps/{appId}/{accountId}', name: 'app_deactivate', methods: ['DELETE'])]
    public function disable(
        Request $request, 
        string $appId, 
        string $accountId, 
        AccountApplicationRepository $accountApplicationRepository,
        EntityManagerInterface $em
    ): Response
    {
        $accountApplication = $accountApplicationRepository->getByIdentification(appId: $appId, accountId: $accountId);

        if(!$accountApplication){
            return new JsonResponse([
                'status' => AccountApplicationStatus::ERROR
            ], 404);
        }

        $accountApplication->setStatus(AccountApplicationStatus::UNINSTALLED);
        
        return new JsonResponse([
            'status' => $accountApplication->getStatus()
        ]);
    }
}
