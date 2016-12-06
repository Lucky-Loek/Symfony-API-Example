<?php

namespace AppBundle\Controller;

use AppBundle\Dto\CarDtoAssembler;
use AppBundle\Entity\Car;
use AppBundle\Exception\InvalidRequestArgumentException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class CarController extends FOSRestController
{
    /**
     * Get all cars
     *
     * @Rest\Get("/car")
     */
    public function cgetCarAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Car');

        $carEntities = $repository->findAll();

        $dtos = [];
        foreach ($carEntities as $car) {
            $dtos[] = CarDtoAssembler::createFromEntity($car);
        }

        return $this->view($dtos, Response::HTTP_OK);
    }

    /**
     * Get a car by ID
     *
     * @Rest\Get("/car/{id}")
     */
    public function getCarAction($id)
    {
        $id = $this->checkId($id);

        $car = $this->retrieveCarFromDb($id);
        $dto = CarDtoAssembler::createFromEntity($car);

        return $this->view($dto, Response::HTTP_OK);
    }

    /**
     * Add a car
     *
     * @Rest\Post("/car")
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws InvalidRequestArgumentException
     */
    public function postAction(Request $request)
    {
        $data = $this->requestBodyToObject($request);

        try {
            $car = new Car($data->brand, $data->name, $data->year);
        } catch (\Exception $e) {
            throw new InvalidRequestArgumentException($e->getMessage(), $e->getCode());
        }

        $this->saveCarToDatabase($car);

        $dto = CarDtoAssembler::createFromEntity($car);

        return $this->view($dto, Response::HTTP_OK);
    }

    /**
     * Update a car
     *
     * @Rest\Patch("/car/{id}")
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws InvalidRequestArgumentException
     */
    public function patchAction($id, Request $request)
    {
        $id = $this->checkId($id);

        $data = $this->requestBodyToObject($request);

        $car = $this->retrieveCarFromDb($id);

        if (isset($data->brand)) {
            $car->setBrand($data->brand);
        }
        if (isset($data->name)) {
            $car->setName($data->name);
        }
        if (isset($data->year)) {
            $car->setYear($data->year);
        }

        $this->saveCarToDatabase($car);

        $dto = CarDtoAssembler::createFromEntity($car);

        return $this->view($dto, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/car/{id}")
     */
    public function deleteAction($id)
    {
        $id = $this->checkId($id);

        $car = $this->retrieveCarFromDb($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($car);
        $em->flush();

        $message = ['message' => 'Car deleted'];

        return $this->view($message, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @throws InvalidRequestArgumentException
     * @return \stdClass
     */
    private function requestBodyToObject(Request $request)
    {
        $content = $request->getContent();

        if (empty($content)) {
            throw new InvalidRequestArgumentException('No body given', Response::HTTP_BAD_REQUEST);
        }

        $content = json_decode($content);

        if (is_null($content)) {
            throw new InvalidRequestArgumentException('Invalid JSON given', Response::HTTP_BAD_REQUEST);
        }

        return $content;
    }

    /**
     * @param Car $car
     */
    private function saveCarToDatabase(Car $car)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($car);
        $em->flush();
    }

    /**
     * @param mixed $id
     * @return int
     * @throws InvalidRequestArgumentException
     */
    private function checkId($id)
    {
        try {
            Assert::integerish($id);
        } catch (\Exception $e) {
            throw new InvalidRequestArgumentException($e->getMessage(), $e->getCode());
        }
        return (int) $id;
    }

    /**
     * @param $id
     * @return Car
     * @throws InvalidRequestArgumentException
     */
    private function retrieveCarFromDb($id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Car');
        try {
            /** @var Car $car */
            $car = $repository->find($id);
            return $car;
        } catch (\Exception $e) {
            throw new InvalidRequestArgumentException($e->getMessage(), $e->getCode());
        }
    }
}
