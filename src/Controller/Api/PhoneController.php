<?php

namespace App\Controller\Api;

use App\Entity\Phone;
use Swagger\Annotations as SWG;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Knp\Component\Pager\PaginatorInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



class PhoneController extends FOSRestController
{
     /**
     * @Rest\Get(
     *  path="/api/phones",
     *  name="index_phones",
     * )
     * 
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="[0-9]",
     *     nullable=true,
     *     description="The page number."
     * )
     * 
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="ASC|DESC",
     *     nullable=true,
     *     default="DESC",  
     *     description="The page number."
     * )
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of phones",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Phones::class, groups={"full"}))
     *     )
     * )
     * 
     * @SWG\Tag(name="Phones")
     * @IsGranted("ROLE_USER")
     * 
     * @View
     * 
     */
    // Knp paginator and group lists does not seem to be compatible..!! @View(serializerGroups={"list"})
    public function index(Request $request, ParamFetcherInterface $paramFetcher, PhoneRepository $repository, PaginatorInterface $paginator) 
    {
        $queryBuilder = $repository->findAllWithOrder($paramFetcher->get('order')); // create query
        
        // $page = NULL === $paramFetcher->get('page') ? 1 : $paramFetcher->get('page');

        // $pagination = $paginator->paginate(
        //     $queryBuilder, /* query NOT result */
        //     $page/*page number*/,
        //     getenv('LIMIT')/*limit per page*/
        // );

        return $queryBuilder; 
    }

    /**
     * @Rest\Get(path="/api/phones/{id}", name="getone_phone", requirements = {"id"="\d+"})
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Returns one phone",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Phones::class, groups={"full"}))
     *     )
     * )
     * 
     * s@SWG\Parameter(
     *     name="order",
     *     in="query",
     *     type="string",
     *     description="The field used to get phone"
     * )
     * @SWG\Tag(name="Phones")
     * @Security(name="Bearer")
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @View
     */
    public function show(Phone $phone, PhoneRepository $repository)
    {
        $data = $repository->findOneBy(['id' => $phone->getId()]);
        return $data;
    }

    /**
     * @Post(path="/api/phones/new", name="new_phone")
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Returns one phone",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Phones::class, groups={"full"}))
     *     )
     * )
     * 
     * @SWG\Tag(name="Phones")
     * @Security(name="Bearer")
     * 
     * @IsGranted("ROLE_SUPER_ADMIN")
     * 
     * @View
     * @ParamConverter("phone", converter="fos_rest.request_body", options={ "validator"={"groups"="create"} } )
     */
    public function new(Phone $phone, EntityManagerInterface $manager, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            return $violations;
        }

        $manager->persist($phone);
        $manager->flush();

        return $phone;
    }

     /**
     * @Get(path="/api/phones/update/{id}", name="update_phone", requirements = {"id"="\d+"})
     * 
     *  @SWG\Response(
     *     response=200,
     *     description="Returns one phone",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Phones::class, groups={"full"}))
     *     )
     * )
     * 
     * @SWG\Tag(name="Phones")
     * @Security(name="Bearer")
     * 
     * @IsGranted("ROLE_SUPER_ADMIN")
     * 
     * @View
     */
    public function update()
    {
        return ["action" => "update"];
    }

     /**
     * @Rest\Delete(path="/api/phones/delete/{id}", name="delete_phone", requirements = {"id"="\d+"})
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Returns one phone",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Phones::class, groups={"full"}))
     *     )
     * )
     * 
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     type="string",
     *     description="The field used to get phone"
     * )
     * @SWG\Tag(name="Phones")
     * @Security(name="Bearer")
     * 
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function delete(Request $request, Phone $phone, EntityManagerInterface $manager) 
    {
        $manager->remove($phone);
        $manager->flush();

        return new Response('rangee supprimee !');
    }
}