<?php
namespace Centauri\Core\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class to use fluid in JavaScript.
 */

class FluidJSViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument('src', 'string', 'Source to JavaScript - can use EXT: to solve a path to an extension', true);
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        return;

        $src = $arguments["src"];
        $path = GeneralUtility::getFileAbsFileName($src);
        
        $content = file_get_contents($path);
        dump($path);
        dd($content);
    }
}
