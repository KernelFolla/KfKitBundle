<?php

namespace Kf\KitBundle\Symfony\Controller;

/**
 * @author Marino Di Clemente <kernelfolla@gmail.com>
 */
trait RequestHelper
{
    /**
     * returns true after a standard form bind/validation
     *
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    public function isFormValid(\Symfony\Component\Form\FormInterface $form)
    {
        $req = $this->getRequest();

        return $req->isMethod('post')
//        && ($req->request->has($form->getName()) || $req->files->has($form->getName()))
        && $form->handleRequest($req)->isSubmitted()
        && $form->isValid();
    }

    /**
     * returns a redirect to te same route (prevents f5)
     *
     * @param null  $route
     * @param array $parameters
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToRoute($route, array $parameters = [], $status = 302)
    {
        if (!isset($route)) {
            $route = $this->getRequest()->get('_route');
        }

        return $this->redirect(
            $this->generateUrl($route, $parameters),
            $status
        );
    }

    protected function redirectToReferer($add = null)
    {
        return $this->redirect($this->getRequest()->headers->get('referer') . $add);
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function isResponse($data)
    {
        return $data instanceof \Symfony\Component\HttpFoundation\Response;
    }

}
