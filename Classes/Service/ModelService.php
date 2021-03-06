<?php
namespace Centauri\Core\Service;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class ModelService {
    protected $templatePath = "Classes/Service/ModelTemplate.php";

    /**
     * Dynamically creates a model by given params
     * 
     * @param string $extKey Key of your extension where the given Model should be created
     * @param string $namespace For your model
     * @param string $modelName
     * @param array $properties Model properties e.g: $properties = ["string" => ["name" => "''"]]
     * 
     * @return void
     */
    public static function generate($extKey, $namespace, $modelName, $properties) {
        $flashUtility = new \Centauri\Core\Utility\FlashUtility;

        $mainPath = ExtensionManagementUtility::extPath("centauri_core");

        $templateFile = $mainPath . "Classes/Service/ModelTemplate.php";
        $templateContent = file_get_contents($templateFile);

        $domainFolder = ExtensionManagementUtility::extPath($extKey) . "Classes/Domain";
        $modelFolder = ExtensionManagementUtility::extPath($extKey) . "Classes/Domain/Model";

        if(!is_dir($domainFolder) || !is_dir($modelFolder)) {
            return $flashUtility->message("Centauri Core (EXT:centauri_core) - Model Service", "There's no such folder: $domainFolder AND/OR $modelFolder", 1);
        }

        $modelPath = ExtensionManagementUtility::extPath($extKey) . "Classes/Domain/Model/$modelName.php";

        $newModelFile = fopen($modelPath, "w")
            or
        $flashUtility->message("Centauri Core (EXT:centauri_core) - Model Service", "Couldn't create file - permissions?", 2);

        $content = self::updateContent($extKey, $templateContent, $namespace, $modelName, $properties);
        fwrite($newModelFile, $content);

        fclose($newModelFile);
    }

    public static function updateContent($extKey, $content, $namespace, $modelName, $properties) {
        $content = str_replace("{EXTENSION_NAME}", $extKey, $content);
        $content = str_replace("{NAMESPACE}", $namespace, $content);
        $content = str_replace("{CLASS_NAME}", $modelName, $content);
        $content = str_replace("{COPYRIGHT_YEAR}", date("Y"), $content);

        $dataContent = "";

        foreach($properties as $varType => $propertyDatas) {
            foreach($propertyDatas as $property => $value) {
                $type = self::updateType($varType);
                $return = self::findReturnByType($type);

                $dataContent .= "
    /**
     * $property
     * 
     * @var $type
     */
    protected $" . $property . " = " . $value . ";

    /**
     * Sets the $property
     * 
     * @param $varType $$property
     * @return $return
     */
    public function set" . ucfirst($property) . "($$property)
    {
        " . '$this->' . $property . " = $$property;
    }

    /**
     * Returns the $property
     * 
     * @return $varType $$property
     */
    public function get" . ucfirst($property) . "()
    {
        return " . '$this' . "->$property;
    }
    ";
            }
        }

        $dataContent = str_replace("$ ", "", $dataContent);
        $dataContent = str_replace("$ this", '$this', $dataContent);
        $content = str_replace("{CLASS_CONTENT}", $dataContent, $content);

        return $content;
    }

    public static function updateType($type) {
        if($type == "\TYPO3\CMS\Extbase\Domain\Model\FileReference") {
            $type = '\TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")';
        }

        return $type;
    }

    public static function findReturnByType($type) {
        $return = "void";

        if($type == "") {
            $return = "test";
        }

        return $return;
    }
}
