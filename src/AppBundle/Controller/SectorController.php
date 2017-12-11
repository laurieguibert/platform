<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sector;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sector controller.
 *
 * @Route("sector")
 */
class SectorController extends Controller
{
    /**
     * Lists all sector entities.
     *
     * @Route("/", name="sector_index")
     * @Method("GET")
     * @ApiDoc(
     *  description="Show all sectors",
     *  section="Sector",
     *  output= {"class"=Sector::class},
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sectors = $em->getRepository('AppBundle:Sector')->findAll();
        if(empty($sectors)){
            return new Response("No sector registered !", 404);
        } else {
            $data = $this->get('serializer')->normalize([
                'sectors' => $sectors
            ]);
            return new JsonResponse($data, 200);
        }
    }

    /**
     * Creates a new sector entity.
     *
     * @Route("/new", name="sector_new")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Create new sector",
     *  section="Sector",
     *  input={
     *   "class"="AppBundle\Form\SectorType",
     *  },
     *  output= {"class"=Sector::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function newAction(Request $request)
    {
        $sector = new Sector();
        $data = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();
        $sector->setName($data['name']);
        $sector->setCreatedAt(date_create(date("Y-m-d H:i:s")));
        $sector->setUpdatedAt(null);
        $sector_parent = $em->getRepository('AppBundle:Sector')->find($data['parent_sector']);
        $sector->setParentSector($sector_parent);
        $validator = $this->get('validator');
        $errors = $validator->validate($sector);
        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return new Response($errorsString);
        }
        $em->persist($sector);
        $em->flush();
        return new JsonResponse($this->get('serializer')->normalize($sector), 200);
    }

    /**
     * Finds and displays a sector entity.
     *
     * @Route("/{id}", name="sector_show")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Show a sector",
     *  section="Sector",
     *  output= {"class"=Sector::class},
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function showAction($id)
    {
        $sector = $this->getDoctrine()->getRepository('AppBundle:Sector')->findOneById($id);
        if ($sector === null) {
            return new Response("Sector not found !", 404);
        }
        $data = $this->get('serializer')->normalize([
            'sector' => $sector
        ]);

        return new JsonResponse($data, 200);
    }

    /**
     * Displays a form to edit an existing sector entity.
     *
     * @Route("/{id}/edit", name="sector_edit")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Update a sector",
     *  section="Sector",
     *  input={
     *   "class"="AppBundle\Form\SectorType",
     *  },
     *  output= { "class"=Sector::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function editAction(Request $request, Sector $sector)
    {
        $data = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();
        $sector->setName($data['name']);
        $sector->setUpdatedAt(date_create(date("Y-m-d H:i:s")));
        if($data['parent_sector']) {
            $sector_parent = $em->getRepository('AppBundle:Sector')->find($data['parent_sector']);
            $sector->setParentSector($sector_parent);
        }
        $validator = $this->get('validator');
        $errors = $validator->validate($sector);
        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return new Response($errorsString);
        }
        $em->persist($sector);
        $em->flush();
        return new JsonResponse($this->get('serializer')->normalize($sector), 200);
    }

    /**
     * Deletes a sector entity.
     *
     * @Route("/{id}", name="sector_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Delete a sector",
     *  section="Sector",
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $sector = $em->getRepository('AppBundle:Sector')
            ->find($request->get('id'));

        if ($sector) {
            $em->remove($sector);
            $em->flush();
            return new Response("Sector " . $request->get('id') . " was deleted !", 200 );
        } else {
            return new Response("Sector " . $request->get('id') . " doesn't exist !", 404);
        }
    }
}
