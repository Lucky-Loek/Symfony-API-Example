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
        $username = $request->headers->get('username');
        $password = $request->headers->get('password');

        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repository->findOneBy(['username' => $username]);

        if (is_null($user)) {
            throw new BadCredentialsException();
        }

        $encoder = $this->get('security.password_encoder');
        $passwordValid = $encoder->isPasswordValid($user, $password);

        if (!$passwordValid) {
            throw new BadCredentialsException();
        }

        $tokenEncoder = $this->get('lexik_jwt_authentication.encoder');
        $token = $tokenEncoder->encode([
            // This is why JWT is incredibly awesome: we can just encode the username IN the token
            // Later we can decode the token and easily search for a matching user because we have the username
            // No need for storing tokens in the DB anymore! :)
            'username' => $user->getUsername(),
            'exp' => time() + 600
        ]);

        return new JsonResponse(['token' => $token]);
    }
}
