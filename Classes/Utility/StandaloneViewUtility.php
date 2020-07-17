<?php
namespace Centauri\Core\Utility;

class StandaloneViewUtility {
    /**
     * Renders a standalone view (template) by the given rootPaths.
     * 
     * @param array $rootPaths
     * @param array $assign
     * @param boolean $returnType
     * 
     * @return string|array|void
     */
    public static function render($rootPaths = null, $template = null, $assign = null, $returnType = false) {
        $exWord = "";

        if(is_null($rootPaths)) {
            $exWord = "Template-/Layout-/PartialRootPaths - there is one out of them not given!";
        }

        if(is_null($template)) {
            $exWord = "Template File not given (null)!";
        }

        if($exWord != "") {
            throw new \TYPO3\CMS\Core\Error\Exception("StandaloneViewUtility - can't render a View without $exWord");
        }

        $standaloneView = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Fluid\View\StandaloneView');
        foreach($rootPaths as $type => $path) {
            if($type == "Templates") {
                $standaloneView->setTemplateRootPaths([
                    \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($path)
                ]);
            }

            if($type == "Layouts") {
                $standaloneView->setLayoutRootPaths([
                    \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($path)
                ]);
            }

            if($type == "Partials") {
                $standaloneView->setPartialRootPaths([
                    \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($path)
                ]);
            }
        }

        $standaloneView->setTemplate($template);
        if(!is_null($assign)) $standaloneView->assignMultiple($assign);

        if(!$returnType) echo $standaloneView->render();
        else return $standaloneView->render();
    }
}
