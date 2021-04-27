<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Record;
use App\Form\AddCityForm;
use App\Form\EditRecordForm;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CitiesController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class CitiesController extends AbstractController
{
    /**
     * @Route("/cities/show", name="cities_show")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $addCityForm = $this->createForm(AddCityForm::class);
        $addCityForm->handleRequest($request);

        if ($addCityForm->isSubmitted() && $addCityForm->isValid()) {

            if ($this->addCityRecord($addCityForm->get('name')->getData())) {
                $this->addFlash('success', 'New record created!');
            } else {
                $this->addFlash('notSuccess', 'Record was not created!');
            }

            return $this->redirectToRoute('cities_show');
        }

        $pagination = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(City::class)->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('page/cities.html.twig', [
            'add_city' => $addCityForm->createView(),
            'cities' => $pagination,
        ]);
    }

    /**
     * @Route("/city/records/{id}", name="city_records")
     */
    public function cityRecords(Request $request, PaginatorInterface $paginator, int $id): Response
    {

        $pagination = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(Record::class)->findBy(['city' => $id]),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('page/cityRecords.html.twig', [
            'records' => $pagination,
            'city' => $this->getDoctrine()
                ->getRepository(City::class)->find($id)->getName(),
        ]);
    }

    /**
     * @Route("/record/edit/{id}", name="record_edit")
     * @param Request $request
     * @param int $id
     */
    public function editRecord(Request $request, int $id) : Response
    {
        $recordRepository = $this->getDoctrine()
            ->getRepository(Record::class);
        $record = $recordRepository->find($id);
        $oldCity = $record->getCity();

        $editCityForm = $this->createForm(EditRecordForm::class, $record, ['user' => $this->getUser()]);
        $editCityForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($editCityForm->isSubmitted() && $editCityForm->isValid()) {

            if ($editCityForm->get('city')->getData() !== $oldCity) {
                $this->editCityRecord($record);
            }

            $this->addFlash('success', 'Record edited successfully!');
            $em->flush();
            return $this->redirectToRoute('cities_show');
        }

        return $this->render('page/editRecord.html.twig', [
            'editForm' => $editCityForm->createView(),
        ]);
    }

    /**
     * @Route("/record/delete/{id}", name="record_delete")
     * @param int $id
     */
    public function deleteRecord(int $id)
    {
        $recordRepository = $this->getDoctrine()
            ->getRepository(Record::class);
        $record = $recordRepository->find($id);

        if (!empty($record)) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($record);
            $entityManager->flush();
            $this->addFlash('success', 'Record deleted successfully!');
        }

        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/city/delete/{id}", name="city_delete")
     * @param int $id
     */
    public function deleteCity(int $id)
    {
            $cityRepository = $this->getDoctrine()
                ->getRepository(City::class);
            $city = $cityRepository->find($id);

            if (!empty($city)) {
                $entityManager = $this->getDoctrine()->getManager();
                foreach ($city->getRecords() as $record) {
                    $entityManager->remove($record);
                }

                $entityManager->remove($city);
                $entityManager->flush();
                $this->addFlash('success', 'City deleted successfully!');
            }

            $response = new Response();
            $response->send();
    }

    private function addCityRecord(string $cityName) : bool {

        $cityRepository = $this->getDoctrine()
            ->getRepository(City::class);

        if (empty($cityRepository->findBy(['name' => $cityName, 'user' => $this->getUser()]))) {
            $city = new City();
            $city->setName($cityName);
            $city->setUser($this->getUser());
        } else {
            $city = $cityRepository->findOneBy(['name' => $cityName, 'user' => $this->getUser()]);
        }

        $url = "http://api.openweathermap.org/data/2.5/weather?q=" . $cityName . "&appid=542ffd081e67f4512b705f89d2a611b2&units=metric";
        $httpClient = HttpClient::create();
        $httpResponse = $httpClient->request('GET', $url);

        if (200 !== $httpResponse->getStatusCode()) {
            return false;
        } else {
            $content = json_decode($httpResponse->getContent(), true);

            $record = new Record();
            $record->setCity($city);
            $record->setTemperature(($content){'main'}{'temp'});
            $record->setHumidity(($content){'main'}{'pressure'});
            $record->setPressure(($content){'main'}{'humidity'});

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($record);
            $entityManager->persist($city);
            $entityManager->persist($record);
            $entityManager->flush();

            return true;
        }
    }

    private function editCityRecord (Record $record): bool
    {
        $url = "http://api.openweathermap.org/data/2.5/weather?q=" . $record->getCity()->getName() . "&appid=542ffd081e67f4512b705f89d2a611b2&units=metric";
        $httpClient = HttpClient::create();
        $httpResponse = $httpClient->request('GET', $url);

        if (200 !== $httpResponse->getStatusCode()) {
            return false;
        } else {
            $content = json_decode($httpResponse->getContent(), true);

            $record->setTemperature(($content){'main'}{'temp'});
            $record->setHumidity(($content){'main'}{'pressure'});
            $record->setPressure(($content){'main'}{'humidity'});
            return true;
        }
    }
}
