<?php

namespace App\Controller;

use App\Service\RulesHub;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Templating\EngineInterface;

class PopupController extends AbstractAppController
{
    private $rulesHub;
    private $template;

    public function __construct(RulesHub $rulesHub, EngineInterface $template)
    {
        $this->rulesHub = $rulesHub;
        $this->template = $template;
    }

    /**
     * @Route("/popup", name="popup", options={"expose"=true})
     */
    public function indexAction(Request $request)
    {
        $modalId = $request->query->get('modalId');
        $code = $request->query->get('code');
        $id = $request->query->get('id');

        $repo = $this->getDoctrine()->getRepository(
            $this->rulesHub->codeToClass($code)
        );

        $params = [
            'modalId' => $modalId,
            'rule' => $repo->find($id),
        ];

        if ($this->template->exists(sprintf('includes/popups/%s.html.twig', $code))) {
            return $this->render(
                sprintf('includes/popups/%s.html.twig', $code),
                $params
            );
        }

        return $this->render(
            'includes/popups/rule.html.twig',
            $params
        );
    }
}
