<?php

namespace Cekurte\Home\AdminBundle\Controller;

use Cekurte\GeneratorBundle\Controller\CekurteController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Cekurte\Home\AdminBundle\Entity\Conta;
use Cekurte\Home\AdminBundle\Form\Type\ContaFormType;
use Cekurte\Home\AdminBundle\Form\Handler\ContaFormHandler;

/**
 * Api Controller
 *
 * @Route("/api")
 *
 * @author  João Paulo Cercal <sistemas@cekurte.com>
 * @version 1.0
 */
class ApiController extends CekurteController
{
    /**
     * @Route("/conta/sincronizar")
     * @Method("POST")
     * @Template()
     */
    public function sincronizarContaAction(Request $request)
    {
        $form = $this->createForm(new ContaFormType(), new Conta());

        $handler = new ContaFormHandler(
            $form,
            $this->getRequest(),
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag()
        );

        $success = $handler->save();

        return new JsonResponse(array(
            'success'   => $success,
            'message'   => $success ? 'Sincronizado com sucesso!' : 'Ocorreu um problema ao realizar a sincronização!',
        ));
    }
}
