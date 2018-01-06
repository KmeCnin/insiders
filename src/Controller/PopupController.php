<?php

namespace App\Controller;

use App\Entity\Rule\LexiconEntry;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PopupController extends AbstractAppController
{
    /**
     * @Route("/popup", name="popup", options={"expose"=true})
     */
    public function indexAction(Request $request)
    {
        $modalId = $request->query->get('modalId');
        $code = $request->query->get('code');
        $id = $request->query->get('id');

        $template = 'rule';
        switch ($code) {
            case LexiconEntry::CODE:
                $repo = $this->getDoctrine()->getRepository(LexiconEntry::class);
                break;
            default:
                throw new InvalidArgumentException(sprintf(
                    'Unknown entity code %s',
                    $code
                ));
        }

        return $this->render(
            sprintf('includes/popups/%s.html.twig', $template),
            [
                'modalId' => $modalId,
                'rule' => $repo->find($id),
            ]
        );
    }
}
