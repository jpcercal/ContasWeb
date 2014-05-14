<?php

namespace Cekurte\Home\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cekurte\GeneratorBundle\Controller\CekurteController;
use Cekurte\GeneratorBundle\Controller\RepositoryInterface;
use Cekurte\GeneratorBundle\Office\PHPExcel as CekurtePHPExcel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Cekurte\Home\AdminBundle\Entity\Conta;
use Cekurte\Home\AdminBundle\Entity\Repository\ContaRepository;
use Cekurte\Home\AdminBundle\Form\Type\ContaFormType;
use Cekurte\Home\AdminBundle\Form\Handler\ContaFormHandler;

/**
 * Conta controller.
 *
 * @Route("/admin/conta")
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
class ContaController extends CekurteController implements RepositoryInterface
{
    /**
     * Get a instance of ContaRepository.
     *
     * @return ContaRepository
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function getEntityRepository()
    {
        return $this->get('doctrine')->getRepository('CekurteHomeAdminBundle:Conta');
    }

    /**
     * Lists all Conta entities.
     *
     * @Route("/", defaults={"page"=1, "sort"="ck.id", "direction"="asc"}, name="conta")
     * @Route("/page/{page}/sort/{sort}/direction/{direction}/", defaults={"page"=1, "sort"="ck.id", "direction"="asc"}, name="conta_paginator")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEHOMEADMINBUNDLE_CONTA, ROLE_ADMIN")
     *
     * @param int $page
     * @param string $sort
     * @param string $direction
     * @return array
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function indexAction($page, $sort, $direction)
    {
        $form = $this->createForm(new ContaFormType(), new Conta(), array(
            'search' => true,
        ));

        if ($this->get('session')->has('search_conta')) {

            $form->bind($this->get('session')->get('search_conta'));
        }

        $query = $this->getEntityRepository()->getQuery($form->getData(), $sort, $direction);

        $pagination = $this->getPagination($query, $page);

        $pagination->setUsedRoute('conta_paginator');

        return array(
            'pagination'    => $pagination,
            'delete_form'   => $this->createDeleteForm()->createView(),
            'search_form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to search a Conta entity.
     *
     * @Route("/search", name="conta_search")
     * @Method({"GET", "POST"})
     * @Template()
     * @Secure(roles="ROLE_CEKURTEHOMEADMINBUNDLE_CONTA, ROLE_ADMIN")
     *
     * @param Request $request
     * @return RedirectResponse
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function searchAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $this->get('session')->set('search_conta', $request);
        } else {
            $this->get('session')->remove('search_conta');
        }

        return $this->redirect($this->generateUrl('conta'));
    }

    /**
     * Export Conta entities to Excel.
     *
     * @Route("/export/sort/{sort}/direction/{direction}/", defaults={"sort"="ck.id", "direction"="asc"}, name="conta_export")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEHOMEADMINBUNDLE_CONTA, ROLE_ADMIN")
     *
     * @param string $sort
     * @param string $direction
     * @return StreamedResponse
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function exportAction($sort, $direction)
    {
        $form = $this->createForm(new ContaFormType(), new Conta(), array(
            'search' => true,
        ));

        if ($this->get('session')->has('search_conta')) {

            $form->bind($this->get('session')->get('search_conta'));
        }

        $query = $this->getEntityRepository()->getQuery($form->getData(), $sort, $direction);

        $translator = $this->get('translator');

        $office = new CekurtePHPExcel(sprintf(
            '%s %s',
            $translator->trans('Report of'),
            $translator->trans('Conta')
        ));

        $office
            ->setHeader(array(
                'id' => $translator->trans('Id'),
                'tipoDespesa' => $translator->trans('Tipodespesa'),
                'formaPagamento' => $translator->trans('Formapagamento'),
                'conta' => $translator->trans('Conta'),
                'valor' => $translator->trans('Valor'),
                'data' => $translator->trans('Data'),
                'createdAt' => $translator->trans('Createdat'),
            ))
            ->setData($query->getArrayResult())
            ->build()
        ;

        return $office->createResponse();
    }

    /**
     * Creates a new Conta entity.
     *
     * @Route("/", name="conta_create")
     * @Method("POST")
     * @Template("CekurteHomeAdminBundle:Conta:new.html.twig")
     * @Secure(roles="ROLE_CEKURTEHOMEADMINBUNDLE_CONTA_CREATE, ROLE_ADMIN")
     *
     * @param Request $request
     * @return array|RedirectResponse
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(new ContaFormType(), new Conta());

        $handler = new ContaFormHandler(
            $form,
            $this->getRequest(),
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag()
        );

        if ($id = $handler->save()) {
            return $this->redirect($this->generateUrl('conta_show', array('id' => $id)));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Conta entity.
     *
     * @Route("/new", name="conta_new")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEHOMEADMINBUNDLE_CONTA_CREATE, ROLE_ADMIN")
     *
     * @return array|Response
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function newAction()
    {
        $form = $this->createForm(new ContaFormType(), new Conta());

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Conta entity.
     *
     * @Route("/{id}", name="conta_show")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEHOMEADMINBUNDLE_CONTA_RETRIEVE, ROLE_ADMIN")
     *
     * @param int $id
     * @return array|Response
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function showAction($id)
    {
        $entity = $this->getEntityRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $this->createDeleteForm()->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Conta entity.
     *
     * @Route("/{id}/edit", name="conta_edit")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEHOMEADMINBUNDLE_CONTA_UPDATE, ROLE_ADMIN")
     *
     * @param int $id
     * @return array|Response
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function editAction($id)
    {
        $entity = $this->getEntityRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conta entity.');
        }

        $editForm = $this->createForm(new ContaFormType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $this->createDeleteForm()->createView(),                                                                                            );
    }

    /**
     * Edits an existing Conta entity.
     *
     * @Route("/{id}", name="conta_update")
     * @Method("PUT")
     * @Template("CekurteHomeAdminBundle:Conta:edit.html.twig")
     * @Secure(roles="ROLE_CEKURTEHOMEADMINBUNDLE_CONTA_UPDATE, ROLE_ADMIN")
     *
     * @param Request $request
     * @param int $id
     * @return array|Response
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->getEntityRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conta entity.');
        }

        $form = $this->createForm(new ContaFormType(), $entity);

        $handler = new ContaFormHandler(
            $form,
            $request,
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag()
        );

        if ($id = $handler->save()) {
            return $this->redirect($this->generateUrl('conta_show', array('id' => $id)));
        }

        $editForm = $this->createForm(new ContaFormType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $this->createDeleteForm()->createView(),                                                                                            );
    }

    /**
     * Deletes a Conta entity.
     *
     * @Route("/{id}", name="conta_delete")
     * @Method("DELETE")
     * @Secure(roles="ROLE_CEKURTEHOMEADMINBUNDLE_CONTA_DELETE, ROLE_ADMIN")
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function deleteAction(Request $request, $id)
    {
        $handler = new ContaFormHandler(
            $this->createDeleteForm(),
            $request,
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag()
        );

        if ($handler->delete('CekurteHomeAdminBundle:Conta')) {
            return $this->redirect($this->generateUrl('conta'));
        } else {
            return $this->redirect($this->generateUrl('conta_show', array('id' => $id)));
        }
    }
}
