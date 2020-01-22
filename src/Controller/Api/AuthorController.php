<?php

namespace App\Controller\Api;

use App\Entity\Author;
use Swagger\Annotations as SWG;
use App\Repository\AuthorRepository;
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


class AuthorController extends FOSRestController
{

     /**
     * @Rest\Get(
     *  path="/api/author",
     *  name="index_author",
     * )
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="[0-9]",
     *     nullable=true,
     *     description="The page number."
     * )
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
     * @SWG\Tag(name="Author")
     * @Security(name="Bearer")
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @View
     * 
     */
    // Knp paginator and group lists does not seem to be compatible..!! @View(serializerGroups={"list"})
    public function index(Request $request, ParamFetcherInterface $paramFetcher, AuthorRepository $repository, PaginatorInterface $paginator) 
    {
        $queryBuilder = $repository->findAllWithOrder($paramFetcher->get('order')); // create query
        
        $page = NULL === $paramFetcher->get('page') ? 1 : $paramFetcher->get('page');

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $page/*page number*/,
            getenv('LIMIT')/*limit per page*/
        );

        return $pagination; 
 
    }

    /**
     * @Rest\Get(path="/api/author/{id}", name="getone_author", requirements = {"id"="\d+"})
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
     * @SWG\Tag(name="Author")
     * @Security(name="Bearer")
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @View
     */
    public function show(Author $author, AuthorRepository $repository)
    {
        $data = $repository->findOneBy(['id' => $author->getId()]);
        return $data;
    }

    /**
     * @Post(path="/api/author/new", name="new_author")
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
     * @SWG\Tag(name="Author")
     * @Security(name="Bearer")
     * 
     * @IsGranted("ROLE_SUPER_ADMIN")
     * 
     * @View
     * @ParamConverter("author", converter="fos_rest.request_body", options={ "validator"={"groups"="create"} } )
     */
    public function new(Author $author, EntityManagerInterface $manager, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            return $violations;
        }

        $manager->persist($author);
        $manager->flush();

        return $author;
    }

     /**
     * @Get(path="/api/author/update/{id}", name="update_author", requirements = {"id"="\d+"})
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
     * @SWG\Tag(name="Author")
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
     * @Rest\Delete(path="/api/author/delete/{id}", name="delete_author", requirements = {"id"="\d+"})
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
     * @SWG\Tag(name="Author")
     * @Security(name="Bearer")
     * 
     * @IsGranted("ROLE_SUPER_ADMIN")
     * 
     */
    public function delete(Request $request, Author $author, EntityManagerInterface $manager) 
    {
        $manager->remove($author);
        $manager->flush();

        return new Response('rangee supprimee !');
    }
}