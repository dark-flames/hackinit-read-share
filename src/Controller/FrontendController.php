<?php
namespace ReadShare\Controller;

use ReadShare\Library\Frontend\FrontendConfigManager;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/_fe")
 */
class FrontendController extends AbstractController {
    /**
     * @Route("/config/{configName}", name="fe_config", requirements={"configName"="[0-9a-zA-Z]\w*"}, methods={"GET"})
     *
     * @param string|int $configName
     * @param FrontendConfigManager $configManager
     * @return JsonResponse
     */
    public function configAction($configName, FrontendConfigManager $configManager) {
        $config = $configManager->getConfig();

        if ($config[$configName])
            $configItem = $config[$configName]['value'];
        else
            throw new NotFoundHttpException();

        return new JsonResponse($configItem);
    }

    /**
     * @Route("/config/_versions", name="fe_versions", methods={"GET"})
     *
     * @param FrontendConfigManager $configManager
     * @return JsonResponse
     */
    public function configVersionsAction(FrontendConfigManager $configManager) {
        $config = $configManager->getConfig();

        $configVersions = [];

        foreach (array_keys($config) as $configName)
            $configVersions[$configName] = $config[$configName]['version'];

        return new JsonResponse($configVersions);
    }
}