<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class TokenController extends Controller
{
    /**
     * @Route("api/token")
     * @Method("POST")
     */
    public function newTokenAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repository->findOneBy(['username' => $request->getUser()]);

        if (is_null($user)) {
            throw new BadCredentialsException();
        }

        $encoder = $this->get('security.password_encoder');
        $passwordValid = $encoder->isPasswordValid($user, $request->getPassword());

        if (!$passwordValid) {
            throw new BadCredentialsException();
        }

        $tokenEncoder = $this->get('lexik_jwt_authentication.encoder');
        $token = $tokenEncoder->encode([
            'username' => $user->getUsername(),
            'exp' => time() + 600
        ]);

        return new JsonResponse(['token' => $token]);
    }
}
